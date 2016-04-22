<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Home\Model;

/**
 * Description of GoodsModel
 *
 * @author Sunhong
 */
class GoodsModel extends \Think\Model{
    
    /**
     * 获取指定状态的商品
     * @param type $goods_status
     * @param type $limit
     * @return type
     */
    public function getGoodsListByGoodsStatus($goods_status,$limit=5){
        $cond = [
            'goods_status & ' . $goods_status,
            'status'=>1,
            'is_on_sale'=>1,
        ];
        return $this->field('id,name,logo,shop_price')->where($cond)->select();
    }
    
    /**
     * 获取商品详情
     * @param type $id
     * @return boolean
     */
    public function getGoodsInfo($id){
        $cond = [
            'status'=>1,
            'is_on_sale'=>1,
            'id'=>$id,
        ];
        $row = $this->where($cond)->find();
        if(!$row){
            $this->error = '您所查找的商品不存在';
            return false;
        }
        $row['brand_name'] = M('Brand')->where(['id'=>$row['brand_id']])->getField('name');
        $row['content'] = M('GoodsIntro')->where(['goods_id'=>$id])->getField('content');
        $row['galleries'] = M('GoodsGallery')->where(['goods_id'=>$id])->getField('path',true);

        return $row;
    }
}
