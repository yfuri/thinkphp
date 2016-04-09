<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Admin\Controller;

/**
 * 供货商控制器类
 *
 * @author Sunhong
 */
class SupplierController extends \Think\Controller {

    //保存模型
    protected $_model = null;

    /**
     * 控制器初始化方法
     */
    protected function _initialize() {
        $meta_titles = array(
            'index' => "供货商管理",
            'add' => '添加供货商',
            'edit' => '修改供货商',
        );
        $meta_title = $meta_titles[ACTION_NAME];
        $this->assign('meta_title', $meta_title);
        $this->_model = D('Supplier');
    }

    /**
     * 供货商列表
     */
    public function index() {
        //获取搜索关键字的功能
        $cond = array();
        //模糊查询供货商的名字
        $keyword = I('get.keyword');
        if ($keyword) {
            $cond['name'] = array('like', '%' . $keyword . '%');
        }
        //查询数据
        $result = $this->_model->getPageList($cond);
        $this->assign($result);
        $this->display();
    }

    /**
     * 添加供货商
     */
    public function add() {
        if (IS_POST) {
            //获取数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            //添加，跳转
            if ($this->_model->add() === false) {
                $this->error(get_error($this->_model->getError()));
            } else {
                $this->success("添加供货商成功", U('index'));
            }
        } else {
            $this->display();
        }
    }

    /**
     * 修改供货商
     * @param integer $id 供货商唯一标识.
     */
    public function edit($id) {
        if (IS_POST) {
            //获取数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model->getError()));
            }
            //保存，跳转
            if ($this->_model->save() === false) {
                $this->error(get_error($this->_model->getError()));
            } else {
                $this->success("修改供货商成功", U('index'));
            }
        } else {
            $row = $this->_model->find($id);
            $this->assign('row', $row);
            $this->display('add');
        }
    }

    /**
     * 删除供货商,逻辑删除
     * @param type $id
     */
    public function delete($id) {
        $data = array(
            'name' => array('exp',"CONCAT(name,'_del')"),
            'status' => -1,
            'id' => $id
        );
        //修改供货商状态为 -1 并在名称后加上 _del
        if ($this->_model->save($data) === false) {
            $this->error(get_error($this->_model->getError()));
        } else {
            $this->success("删除供货商成功", U('index'));
        }
    }

}
