<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Admin\Controller;

/**
 * Description of RoleController
 *
 * @author Sunhong
 */
class RoleController extends \Think\Controller{
    
    /**
     * 存储模型对象.
     * @var \Admin\Model\RoleModel 
     */
    private $_model = null;

    /**
     * 设置标题和初始化模型.
     */
    protected function _initialize() {
        $meta_titles  = array(
            'index'  => '角色管理',
            'add'    => '添加角色',
            'edit'   => '修改角色',
        );
        $meta_title   = $meta_titles[ACTION_NAME];
        $this->assign('meta_title', $meta_title);
        $this->_model = D('Role');
    }
    
    /**
     * 角色列表页
     */
    public function index() {
        $rows = $this->_model->getList();
        $this->assign('rows', $rows);
        $this->display();
    }
    
    /**
     * 添加角色
     */
    public function add() {
        if(IS_POST){
            // 获取数据
            if($this->_model->create() === false){
                $this->error(get_error($this->_model->getError()));
            }
            // 插入数据，跳转
            if($this->_model->addRole() === false){
                $this->error(get_error($this->_model->getError()));
            }  else {
                $this->success('添加权限成功',U('index'));
            }
        }else{
            $this->_before_view();
            $this->display();
        }
    }
    
    /**
     * 编辑角色.
     * @param type $id
     */
    public function edit($id) {
         if(IS_POST){
            if($this->_model->create() === false){
                $this->error(get_error($this->_model->getError()));
            }
            if($this->_model->updateRole() === false){
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('修改成功',U('index'));
        }else{
            $row = $this->_model->getRoleInfo($id);
            $this->assign('row', $row);
            $this->_before_view();
            $this->display('add');
        }
    }
    
    /**
     * 删除角色
     * @param type $id
     */
    public function delete($id){
        if($this->_model->deleteRole($id)===false){
            $this->error(get_error($this->_model->getError()));
        }
        $this->success('删除成功',U('index'));
    }
    
    /**
     * 获取视图所需数据
     */
    public function _before_view() {
        $permissions = D('Permission')->getList('id,name,parent_id');
        $this->assign('permissions',json_encode($permissions));
    }
}
