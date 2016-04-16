<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Admin\Controller;

/**
 * 商品分类控制器
 *
 * @author Sunhong
 */
class GoodsCategoryController extends \Think\Controller{
    
    //保存模型
    protected $_model = null;

    /**
     * 控制器初始化方法
     */
    protected function _initialize() {
        $meta_titles = array(
            'index' => "商品分类管理",
            'add' => '添加商品分类',
            'edit' => '修改商品分类',
        );
        $meta_title = $meta_titles[ACTION_NAME];
        $this->assign('meta_title', $meta_title);
        $this->_model = D('GoodsCategory');
    }

    /**
     * 商品分类列表
     */
    public function index() {

        //查询数据
        $rows = $this->_model->getList();
        $this->assign('rows',$rows);
        $this->display();
    }

    /**
     * 添加商品分类
     */
    public function add() {
        if (IS_POST) {
            //获取数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            //添加，跳转
            if ($this->_model->addGoodsCategory() === false) {
                $this->error(get_error($this->_model->getError()));
            } else {
                $this->success("添加商品分类成功", U('index'));
            }
        } else {
            $this->_before_view();
            $this->display();
        }
    }

    /**
     * 修改商品分类
     * @param integer $id 商品分类唯一标识.
     */
    public function edit($id) {
        if (IS_POST) {
            // 获取数据.
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            //保存，跳转
            if ($this->_model->updateGoodsCategory() === false) {
                $this->error(get_error($this->_model->getError()));
            } else {
                $this->success("修改商品分类成功", U('index'));
            }
        } else {
            $row = $this->_model->find($id);
            // 获取分类列表.
            $categorys = $this->_model->getList();
            // 添加顶级分类.
            array_unshift($categorys,array('id'=>0,'name'=>'顶级分类','parent_id'=>0));
            foreach ($categorys as $value) {
                if($value['id'] == $row['parent_id']){
                    $this->assign('category_name', $value['name']);
                }
            }
            // 传递数据，显示视图.
            $this->assign('row', $row);
            $this->_before_view();
            $this->display('add');
        }
    }

    /**
     * 删除商品分类,逻辑删除
     */
    public function delete() {
        //修改商品分类状态为 -1 并在名称后加上 _del
        if ($this->_model->deleteGoodsCategory() === false) {
            $this->error(get_error($this->_model->getError()));
        } else {
            $this->success("删除商品分类成功", U('index'));
        }
    }
    
    public function _before_view() {
        //获取分类列表
        $categorys = $this->_model->getList();
        array_unshift($categorys,array('id'=>0,'name'=>'顶级分类','parent_id'=>0));
        // 将分类数组转换成json字符串
        $categorys = json_encode($categorys);
        $this->assign("categorys",$categorys);
    }
}
