<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Admin\Behaviors;

/**
 * Description of CheckPermissionBehavior
 *
 * @author Sunhong
 */
class CheckPermissionBehavior extends \Think\Behavior{
    
    public function run(&$params) {
        $url = implode('/', [MODULE_NAME,CONTROLLER_NAME,ACTION_NAME,]);
        // 忽略检测列表
        $ignore = C('IGNORE_SETTING');
            
        // 检测用户是否有权限访问该路径
        $userinfo = session('USERINFO');
        if(!$userinfo){
            if (D('Admin')->autoLogin()){
                $userinfo = session('USERINFO');

            }
        }
        if(!in_array($url, $ignore)){
            //判断是否是超级管理员
            if ($userinfo) {
                //如果发现是超级管理员用户,就可以操作任何请求
                if($userinfo['username'] == 'admin'){
                    return true;
                }
            }
            $paths= session('PATHS');
            if(!in_array($url, $paths)){
                $url = U('Admin/login');
                redirect($url);
            }
        }
    }

}
