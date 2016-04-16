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
class SupplierModel extends \Think\Model{
    
    //自动验证
    protected $_validate = array(
        /**
         * 名字必填
         * 名字唯一
         */
        array('name','require','供应商名字不能为空',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),
        array('name','','供货商已存在',self::EXISTS_VALIDATE,'unique',self::MODEL_INSERT)
    );
    
    /**
     * 查询,分页,条件查询
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
    
    /**
     * 获取所有可用供货商
     * @return type
     */
    public function getList($fileds = '*') {
        return $this->field($fileds)->where(array('status' => 1))->select();
    }
}
