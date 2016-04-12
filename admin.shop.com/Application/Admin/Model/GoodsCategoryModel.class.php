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
class GoodsCategoryModel extends \Think\Model{
    
    //自动验证
    protected $_validate = array(
        /**
         * 名字必填
         * 名字唯一
         */
        array('name','require','商品分类名字不能为空',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),
        array('name','','商品分类已存在',self::EXISTS_VALIDATE,'unique',self::MODEL_INSERT)
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
//        //页面显示数据条数
//        $page_size = C('PAGE_SIZE');
//        //获取总条数
//        $total = $this->where($condition)->count();
//        //分页
//        $page_obj = new \Think\Page($total,$page_size);
//        $page_obj->setConfig('theme', C('PAGE_THEME'));
//        $page_html = $page_obj->show();
        //查询
        $rows = $this->where($condition)->order('sort')->select();
        return $rows;
        //'page_html'=>$page_html,
        
    }
    
    /**
     * 删除商品分类
     * 先确定分类下有无商品
     * @return boolean
     */
    public function deleteGoodsCategory() {
        $id = I('get.id');
        //判断商品分类下有无商品
        if(M('Goods')->getbyGoodsCategoryId($id) !== false){
            $this->error = '不能删除有商品的商品分类';
            return FALSE;
        }
        $data = array(
            'name' => array('exp',"CONCAT(name,'_del')"),
            'status' => -1,
            'id' => $id
        );
        //修改商品分类状态为 -1 并在名称后加上 _del
        if($this->save($data) === false){
            $this->error = '删除失败';
            return FALSE;
        }
        return TRUE;
    }
    
    public function addGoodsCategory() {
        return $this->add();
    }
}
