<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Admin\Controller;

/**
 * Description of PermissionController
 *
 * @author Sunhong
 */
class PermissionController extends \Think\Controller{
    
    /**
     * 保存模型
     * @var \Admin\Model\PermissionModel
     */
    protected $_model = null;

    /**
     * 控制器初始化方法
     */
    protected function _initialize() {
        $meta_titles = array(
            'index' => "权限管理",
            'add' => '添加权限',
            'edit' => '修改权限',
        );
        $meta_title = $meta_titles[ACTION_NAME];
        $this->assign('meta_title', $meta_title);
        $this->_model = D('Permission');
    }
    
    public function index() {
        // 查询数据
        $permissions = $this->_model->getList();
        $this->assign('rows',$permissions);
        $this->display();
    }
    
    /**
     * 添加权限.
     */
    public function add() {
        if(IS_POST){
            // 获取数据
            if($this->_model->create() === false){
                $this->error(get_error($this->_model->getError()));
            }
            // 插入数据，跳转
            if($this->_model->addPermission() === false){
                $this->error(get_error($this->_model->getError()));
            }  else {
                $this->success('添加权限成功',U('index'));
            }
        }else{
            $this->_before_view();
            $this->display();
        }
    }
    
    public function edit($id) {
        if(IS_POST){
            // 获取数据
            if($this->_model->create() === false){
                $this->error(get_error($this->_model->getError()));
            }
            // 插入数据，跳转
            if($this->_model->editPermission() === false){
                $this->error(get_error($this->_model->getError()));
            }  else {
                $this->success('修改权限成功',U('index'));
            }
        }else{
            $row = $this->_model->find($id);
            $this->assign('row',$row);
            $this->_before_view();
            $this->display('add');
        }
    }
    
    /**
     * 删除权限及其后代权限.
     * @param integer $id
     */
    public function delete($id) {
        if($this->_model->deletePermission($id) === false){
            $this->error(get_error($this->_model->getError()));
        }
        $this->success('删除成功',U('index'));
    }
    
    /**
     * 获取视图所需数据
     */
    public function _before_view() {
        $permissions = $this->_model->getList('id,name,parent_id');
        array_unshift($permissions,array('id'=>0,'name'=>'顶级权限','parent_id'=>0));
        $this->assign('permissions',json_encode($permissions));
    }
}
