<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Admin\Model;

/**
 * 品牌模型
 *
 * @author Sunhong
 */
class BrandModel extends \Think\Model{
    
    //自动验证
    protected $_validate = array(
        /**
         * 名字必填
         * 名字唯一
         */
        array('name','require','品牌名字不能为空',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),
        array('name','','品牌已存在',self::EXISTS_VALIDATE,'unique',self::MODEL_INSERT)
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
        return array(
            'rows'=>$rows,
            'page_html'=>$page_html,
        );
        
    }
}
