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
    
    /**
     * 获取商品点击数,并且保存到数据库中
     * @param type $goods_id
     * @return int
     */
    public function getGoodsClick($goods_id){
        $goods_click_model = M('GoodsClick');
        $count =$goods_click_model->getFieldByGoodsId($goods_id,'click_times');
        if(empty($count)){
            $goods_click_model->add(['goods_id'=>$goods_id,'click_times'=>1]);
            return 1;
        } else{
            $goods_click_model->where(['goods_id'=>$goods_id])->setInc('click_times', 1);
            return $count;
        }
    }
    
    /**
     * 从redis中获取商品的点击数,商品的点击数存放在goods_click的hash中
     * @param type $goods_id
     * @return type
     */
    public function getGoodsClickFromRedis($goods_id){
        $key = 'goods_click';
        $redis = get_redis();
        return $redis->hIncrBy($key,$goods_id,1);
    }
}
