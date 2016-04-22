<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Home\Model;

/**
 * Description of GoodsCategoryModel
 *
 * @author Sunhong
 */
class GoodsCategoryModel extends \Think\Model{
    
    /**
     * 获取所有可用品牌
     * @return type
     */
    public function getList($fileds = '*') {
        return $this->field($fileds)->where(array('status' => 1))->order('lft')->select();
    }
}
