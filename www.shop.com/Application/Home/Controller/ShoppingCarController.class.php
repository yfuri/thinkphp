<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Home\Controller;

/**
 * Description of ShoppingCarController
 *
 * @author Sunhong
 */
class ShoppingCarController extends \Think\Controller{
    
    /**
     * @var \Home\Model\ShoppingCarModel 
     */
    private $_model = null;
    protected function _initialize(){
        $this->_model = D('ShoppingCar');
    }


    public function flow1(){
        //取出购物车数据
        $rows = $this->_model->getShoppingCar();
        $this->assign($rows);
        $this->display();
    }
    public function flow2(){
        $this->display('flow1');
    }
}
