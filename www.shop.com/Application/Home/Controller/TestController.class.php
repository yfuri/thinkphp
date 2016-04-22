<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Home\Controller;

/**
 * Description of testController
 *
 * @author Sunhong
 */
class TestController extends \Think\Controller{
    
    
    public function test() {
        session("name", "value");
    }
}
