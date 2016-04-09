<?php

/* 
 * 公共方法
 * @author sunhong
 */

/**
 * 将错误信息转化为无序列表
 * @param Array|String $errors
 * @return string
 */
function get_error($errors){
    if(!is_array($errors)){//如果错误信息不是数组，将其转换为数组
        $errors = array($errors);
    }
    //遍历数组，生成无序列表
    $error_html = '<ul>';
    foreach ($errors as $error) {
        $error_html .= '<li>'.$error.'</li>';
    }
    $error_html .= '</ul>';
    return $error_html;
}

/**
 * 验证昵称
 * @param String $nickname
 * @return boolean
 */
function validate_nickname($nickname) {
    if (mb_strlen($nickname, 'UTF-8') < 2) {
        return false;
    } else {
        return true;
    }
}

/**
* 验证爱好是否合法.
* 只要填写的爱好选项在1-4之间即可
* @param Array $hobby
* @return boolean
*/
function validate_hobby($hobby){
   $diff = array_diff($hobby,array('足球','篮球','乒乓球','双色球'));
   if($diff){
       return false;
   }else{
       return true;
   }
}

/**
 * 加盐加密.
 * @param string $password 原密码.
 * @param string $salt     盐.
 * @return string 加盐加密后的结果.
 */
function my_mcrypt($password,$salt){
    return md5(md5($password).$salt);
}