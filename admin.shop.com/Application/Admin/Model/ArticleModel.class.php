<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Admin\Model;

/**
 * Description of SupplierModel
 *
 * @author Sunhong
 */
class ArticleModel extends \Think\Model{
    
    //自动验证
    protected $_validate = array(
        /**
         * 名字必填
         * 名字唯一
         */
        array('name','require','文章名字不能为空',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),
        array('name','','文章已存在',self::EXISTS_VALIDATE,'unique',self::MODEL_INSERT)
    );
    
    //自动完成
    protected $_auto = array(
        //自动录入文章输入时间
        array('inputtime',NOW_TIME,self::MODEL_INSERT)
    );


    /**
     * 查询
     * @param array $cond 模糊查询条件
     * @return array
     */
    public function getPageList(array $cond=array()) {
        //查询条件
        $condition = $cond + array(
            'status' => array('gt',-1),
        );
        //页面显示数据条数
        $page_size = C('PAGE_SIZE');
        //获取总条数
        $total = $this->where($condition)->count();
        //分页
        $page_obj = new \Think\Page($total,$page_size);
        $page_obj->setConfig('theme', C('PAGE_THEME'));
        $page_html = $page_obj->show();
        //查询
        $rows = $this->where($condition)->order('sort')->page(I('get.p'),$page_size)->select();
        $this->getCategory($rows);
        return array(
            'rows'=>$rows,
            'page_html'=>$page_html,
        );
        
    }
    
    /**
     * 文章分类处理
     * @param type $arr
     * @return type
     */
    public function getCategory(array &$arr) {
        //获取文章分类
        $category_array = D('ArticleCategory')->getPageList();
        $category_array[] = array(
            'id' => 0,
            'name' => '其他分类'
        );
        $categorys = array();
        foreach ($category_array as $category){
            $categorys[$category['id']] = $category['name'];
        }
        for( $i = 0; $i < count($arr) ; $i++) {
            $arr[$i]['category'] = $categorys[$arr[$i]['article_category_id']];
        }
        return $arr;
    }
    
    /**
     * 逻辑删除文章
     * @return boolean
     */
    public function deleteArticle() {
        $data = array(
            'name' => array('exp',"CONCAT(name,'_del')"),
            'status' => -1,
            'id' => I('get.id')
        );
        //修改文章分类状态为 -1 并在名称后加上 _del
        if($this->save($data) === false){
            $this->error = '删除失败';
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * 分表插入数据
     * @return boolean
     */
    public function addArticle() {
        //获取文章内容
        $content = I('post.content');
        //插入article表数据，获得文章id
        if(($article_id = $this->add()) === false){
            $this->error = '添加文章出错';
            return FALSE;
        }
        $data = array(
            'article_id' => $article_id,
            'content' => $content
        );
        //插入article_content表数据
        if(M('ArticleContent')->add($data) === false){
            $this->error = '添加文章出错';
            return FALSE;
        }
        return true;
    }
    
    /**
     * 分表保存数据
     * @return boolean
     */
    public function saveArticle() {
        //获取修改文章的id
        $article_id = $this->data['id'];
        //获取文章内容
        $content = I('post.content');
        //插入article表数据，获得文章id
        if($this->save() === false){
            $this->error = '修改文章出错';
            return FALSE;
        }
        $data = array(
            'content' => $content
        );
        //插入article_content表数据
        if(M('ArticleContent')->where(array('article_id' => $article_id))->save($data) === false){
            $this->error = '修改文章出错';
            return FALSE;
        }
        return true;
    }
}
