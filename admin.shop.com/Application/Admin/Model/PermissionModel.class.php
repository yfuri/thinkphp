<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Admin\Model;

/**
 * Description of PermissionModel
 *
 * @author Sunhong
 */
class PermissionModel extends \Think\Model{
    
    /**
     * 自动验证,权限名称不能为空
     * @var type 
     */
    protected $_validate = array(
        array('name','require','权限名称不能为空',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),
    );

    /**
     * 添加权限
     * @return boolean
     */
    public function addPermission() {
        unset($this->data['id']);
        $nestedsets = $this->_get_nestedsets();
        //执行插入操作
        if($nestedsets->insert($this->data['parent_id'], $this->data, 'bottom')===false){
            $this->error = '创建权限失败';
            return false;
        }
        return true;
    }
    
    /**
     * 修改权限
     * @return boolean
     */
    public function editPermission() {
        // 判断是否移动了父级权限
        $parent_id = $this->getFieldById($this->data['id'],'parent_id');
        // 如果移动了就使用nestedsets重新计算
        if($this->data['parent_id']!=$parent_id){
            $nestedsets = $this->_get_nestedsets();
            if($nestedsets->moveUnder($this->data['id'], $this->data['parent_id'], 'bottom')===false){
                $this->error = '错误权限移动';
                return false;
            }
        }
        // 否则直接保存即可
        return $this->save();
    }
    
    /**
     * 删除权限及其后代权限
     * @param type $id
     * @return boolean
     */
    public function deletePermission($id){
        $permission_info = $this->where(array('id'=>$id))->getField('id,lft,rght');
        if(!$permission_info){
            $this->error = '权限不存在';
            return false;
        }
        $cond = array(
            'lft'=>array('egt',$permission_info[$id]['lft']),
            'rght'=>array('elt',$permission_info[$id]['rght']),
        );
        return $this->where($cond)->setField('status',0);
    }
    
    /**
     * 实例化一个nestedsets
     * @return \Admin\Service\NestedSets
     */
    private function _get_nestedsets() {
        $mysql_db = D('DbMysql','Logic');
        //实例化nestedsets
        return new \Admin\Service\NestedSets($mysql_db, $this->trueTableName, 'lft', 'rght', 'parent_id', 'id', 'level');
    }
    
    /**
     * 获取所有可用权限列表
     * @return type
     */
    public function getList($fileds = '*') {
        return $this->field($fileds)->where(array('status' => 1))->order('lft')->select();
    }
}
