<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Home\Behaviors;

/**
 * Description of CheckPermissionBehavior
 *
 * @author Sunhong
 */
class AutoLoginBehavior extends \Think\Behavior{
    
    public function run(&$params) {
        $userinfo = session('MEMBER_INFO');
        if(!$userinfo){
            D('Member')->autoLogin();
        }
    }

}
