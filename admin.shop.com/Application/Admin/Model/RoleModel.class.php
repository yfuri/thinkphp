<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Admin\Model;

/**
 * Description of RoleModel
 *
 * @author Sunhong
 */
class RoleModel extends \Think\Model{
    
    /**
     * 自动验证,权限名称不能为空
     * @var type 
     */
    protected $_validate = array(
        array('name','require','权限名称不能为空',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),
    );

    /**
     * 添加角色
     * @return boolean
     */
    public function addRole() {
        unset($this->data['id']);
        // 保存角色信息
        if(($role_id = $this->add()) === false ){
            return FALSE;
        }
        // 保存角色-权限关联信息
        if($this->_save_permission($role_id)===false){
            $this->error = '角色-权限关联信息保存失败';
            return false;
        }
        return true;
    }
    
    /**
     * 获取角色详情,包括权限.
     * @param integer $id 角色id.
     * @return boolean
     */
    public function getRoleInfo($id){
        $row = $this->where(array('status' => 1))->find($id);
        if(empty($row)){
            $this->error = '角色不存在';
            return false;
        }
        $permission_model =M('RolePermission');
        $permission_ids = $permission_model->where(array('role_id'=>$id))->getField('permission_id',true);
        $row['permission_ids'] = json_encode($permission_ids);
        return $row;
    }
    
    /**
     * 保存角色和权限的关联关系
     * @param type $role_id
     * @param type $is_new
     * @return boolean
     */
    private function _save_permission($role_id,$is_new=true){
        $perms = I('post.perms');
        if(empty($perms)){
            return true;
        }
        $data = array();
        foreach ($perms as $perm){
            $data[] = array(
                'role_id'=>$role_id,
                'permission_id'=>$perm,
            );
        }
        $model = M('RolePermission');
        if(!$is_new){
            $model->where(array('role_id'=>$role_id))->delete();
        }
        return M('RolePermission')->addAll($data);
    }
    
    /**
     * 修改角色并保存关联的权限.
     * @return boolean
     */
    public function updateRole(){
        $role_id = $this->data['id'];
        // 保存基本信息
        if($this->save() === false){
            return false;
        }
        // 保存关联关系
        if($this->_save_permission($role_id, false) === false){
            $this->error = '角色-权限关联信息保存失败';
            return false;
        }
        return true;
    }
    
    /**
     * 删除角色,逻辑删除,不删除关联的权限
     * @param type $id
     * @return type
     */
    public function deleteRole($id){
        return $this->where(array('id'=>$id))->setField('status',0);
    }
    
    /**
     * 获取所有可用角色
     * @return type
     */
    public function getList($fileds = '*') {
        return $this->field($fileds)->where(array('status' => 1))->select();
    }
}
