<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Home\Controller;

/**
 * 验证码控制器类
 *
 * @author Sunhong
 */
class VerifyController extends \Think\Verify{
    
    /**
     * 显示一张验证码图片.
     */
    public function verify(){
        $options = array(
            'length'=>4,
        );
        $verify = new \Think\Verify($options);
        // 保存验证码
        $verify->entry();
    }
}
