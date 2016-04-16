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
 * 将一个二维数组转为一个下拉列表
 * @param Array $data
 * @param String $name
 * @param String $value_field
 * @param String $name_field
 * @param String $select
 * @return string
 */
function array2select($data,$name,$value_field = 'id',$name_field = 'name',$select=''){
    $html = '<select name="'.$name.'">';
    $html .= '<option value="0">请选择...</option>';
    foreach ($data as $value) {
        if($value[$value_field] == $select){
            $html .= '<option value="'.$value[$value_field].'" selected="selected">' .$value[$name_field]. '</option>';
        }else{
            $html .= '<option value="'.$value[$value_field].'">'.$value[$name_field].'</option>';
        }
    }
    $html .= '</select>';
    return $html;
}

/**
 * 将一个一维数组转为一个下拉列表
 * @param Array $data
 * @param String $name
 * @param String $value_field
 * @param String $name_field
 * @param String $select
 * @return string
 */
function oneArray2select($data,$name,$select=''){
    $html = '<select name="'.$name.'">';
    $html .= '<option value="0">请选择...</option>';
    foreach ($data as $key=>$value) {
        $key = (String)$key;
        if($key == $select){
            $html .= '<option value="'.$key.'" selected="selected">' .$value. '</option>';
        }else{
            $html .= '<option value="'.$key.'">'.$value.'</option>';
        }
    }
    $html .= '</select>';
    return $html;
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