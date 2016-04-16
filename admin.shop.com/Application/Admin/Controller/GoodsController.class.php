<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Admin\Controller;

/**
 * Description of GoodsController
 *
 * @author Sunhong
 */
class GoodsController extends \Think\Controller{
    
    //保存模型
    protected $_model = null;

    /**
     * 控制器初始化方法
     */
    protected function _initialize() {
        $meta_titles = array(
            'index' => "商品管理",
            'add' => '添加商品',
            'edit' => '修改商品',
        );
        $meta_title = $meta_titles[ACTION_NAME];
        $this->assign('meta_title', $meta_title);
        $this->_model = D('Goods');
    }
    
    /**
     * 商品列表,模糊查询
     */
    public function index() {
        //获取视图显示数据-------------
        // 获取分类数据
        $categories = D('GoodsCategory')->where('status=1')->getField('id,name');
        $this->assign('categories', $categories);
        // 获取品牌列表
        $brands = D('Brand')->where('status=1')->getField('id,name');
        $this->assign('brands',$brands);
        // 获取供货商列表
        $suppliers = D('Supplier')->where('status=1')->getField('id,name');
        $this->assign('suppliers',$suppliers);
        // 获取商品状态
        $status = $this->_model->goods_status;
        $this->assign('status',$status);
        // 获取商品上架状态
        $is_on_sales = $this->_model->is_on_sales;
        $this->assign('is_on_sales',$is_on_sales);
        
        //获取查询条件---------------------
        $category_id = I('get.category_id');
        $brand_id = I('get.brand_id');
        $supplier_id = I('get.supplier_id');
        $goods_status = I('get.goods_status');
        $is_on_sale = I('get.is_on_sale');
        $keyword = I("get.keyword");
        //判断条件并组合查询添加数组
        $condition = array();
        if(!empty($category_id)){
            $condition['goods_category_id'] = $category_id;
        }
        if(!empty($brand_id)){
            $condition['brand_id'] =  $brand_id;
        }
        if(!empty($supplier_id)){
            $condition['supplier_id'] = $supplier_id;
        }
        if(!empty($goods_status)){
            $condition[] = "goods_status&".$goods_status;
        }
        if(!empty($is_on_sale)){
            $condition['is_on_sale'] = $is_on_sale;
        }
        if(!empty($keyword)){
            $condition['name'] = array('like','%'.$keyword.'%');
        }
        // 获取商品数据
        $rows = $this->_model->getPageList($condition);
        $this->assign($rows);
        $this->display();
    }
    
    /**
     * 添加商品
     */
    public function add() {
        if(IS_POST){
            // 获取数据
            if($this->_model->create() === false){
                $this->error(get_error($this->_model->getError()));
            }
            // 插入数据，跳转
            if($this->_model->addGoods() === false){
                $this->error(get_error($this->_model->getError()));
            }  else {
                $this->success('添加商品成功',U('index'));
            }
        }else{
            $this->_before_view();
            $this->display();
        }
    }
    
    /**
     * 修改商品
     * @param type $goods_id
     */
    public function edit($id) {
        if(IS_POST){
            // 获取数据
            if($this->_model->create() === false){
                $this->error(get_error($this->_model->getError()));
            }
            // 保存数据，跳转
            if($this->_model->saveGoods() === false){
                $this->error(get_error($this->_model->getError()));
            }  else {
                $this->success('修改商品成功',U('index'));
            }
        }else{
            $row = $this->_model->getGoods($id);
            $this->assign('row',$row);
            $this->_before_view();
            $this->display('add');
        }
    }
    
    /**
     * 删除商品
     * @param type $id
     */
    public function delete($id) {
        if(D('Goods')->deleteGoods($id) === false){
            $this->error(get_error($this->_model->getError()));
        }else {
                $this->success('删除商品成功',U('index'));
        }
    }
    
    /**
     * 获取视图所需数据
     */
    public function _before_view() {
        // 获取商品分类列表
        $categorys = D('GoodsCategory')->getList('id,name,parent_id');
        $categorys = json_encode($categorys);
        $this->assign('categorys', $categorys);
        // 获取品牌列表
        $brands = D('Brand')->getList('id,name');
        $this->assign('brands',$brands);
        // 获取供货商列表
        $suppliers = D('Supplier')->getList('id,name');
        $this->assign('suppliers',$suppliers);
    }
}
