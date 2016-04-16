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
     * 删除商品分类
     * 先确定分类下有无商品
     * @return boolean
     */
    public function deleteGoodsCategory() {
        $id = I('get.id');
//        //判断商品分类下有无商品
//        if(M('Goods')->getbyGoodsCategoryId($id) !== false){
//            $this->error = '不能删除有商品的商品分类';
//            return FALSE;
//        }
//        $data = array(
//            'name' => array('exp',"CONCAT(name,'_del')"),
//            'status' => -1,
//            'id' => $id
//        );
//        //修改商品分类状态为 -1 并在名称后加上 _del
//        if($this->save($data) === false){
//            $this->error = '删除失败';
//            return FALSE;
//        }
//        return TRUE;
        //获取到所有的后代分类
        //获取当前分类的左右节点
        $category = $this->where(array('id'=>$id))->getField('id,lft,rght');
        $cond = array(
            'lft'=>array('egt',$category[$id]['lft']),
            'rght'=>array('elt',$category[$id]['rght']),
        );
        return $this->where($cond)->save(array('name'=>array('exp',"CONCAT(name,'_del')"),'status'=>0));
    }
    
    /**
     * 添加商品分类，使用nestedsets计算节点和层级
     * @return type
     */
    public function addGoodsCategory() {
        //实例化DbMysqlLogic
        $mysql_db = D('DbMysql','Logic');
        //实例化nestedsets
        $nestedsets = new \Admin\Service\NestedSets($mysql_db, $this->trueTableName, 'lft', 'rght', 'parent_id', 'id', 'level');
        return $nestedsets->insert($this->data['parent_id'], $this->data, 'bottom');
    }
    
    /**
     * 修改商品分类
     * @return boolean
     */
    public function updateGoodsCategory() {
        //如果没有修改父级分类,就不需要计算节点和层级
        //获取原来的父级节点
        $parent_id = $this->getFieldById($this->data['id'],'parent_id');
        if($parent_id != $this->data['parent_id']){
            //重新计算左右节点和层级
            //实例化nestedsets
             $mysql_db = D('DbMysql','Logic');
            //实例化nestedsets
            $nestedsets = new \Admin\Service\NestedSets($mysql_db, $this->trueTableName, 'lft', 'rght', 'parent_id', 'id', 'level');
            //执行移动的方法
            if($nestedsets->moveUnder($this->data['id'], $this->data['parent_id'], 'bottom')===false){
                $this->error = '不能将当前分类移动到其后代分类';
                return false;
            }
        }
        return $this->save();
    }
    
    /**
     * 获取所有可用品牌
     * @return type
     */
    public function getList($fileds = '*') {
        return $this->field($fileds)->where(array('status' => 1))->order('lft')->select();
    }
}
