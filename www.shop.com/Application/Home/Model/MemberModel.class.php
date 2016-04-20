<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Home\Model;

/**
 * Description of MemberModel
 *
 * @author Sunhong
 */
class MemberModel extends \Think\Model{
    
    /**
     * 自动验证
     * @var type 
     */
    protected $_validate = array(
        array('username', 'require', '用户名必填', self::EXISTS_VALIDATE, '', self::MODEL_INSERT),
        array('username', '', '用户名已存在', self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT),
        array('username', '2,16', '用户名长度不合法', self::EXISTS_VALIDATE, 'length', self::MODEL_INSERT),
        array('password', 'require', '密码必填', self::EXISTS_VALIDATE, '', self::MODEL_INSERT),
        array('password', '6,16', '密码长度不合法', self::EXISTS_VALIDATE, 'length', self::MODEL_INSERT),
        array('email', 'require', '邮箱必填', self::EXISTS_VALIDATE, '', self::MODEL_INSERT),
        array('email', 'email', '邮箱不合法', self::EXISTS_VALIDATE, '', self::MODEL_INSERT),
        array('email', '', '邮箱已被存在', self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT),
        array('tel', 'require', '手机号码必填', self::EXISTS_VALIDATE, '', self::MODEL_INSERT),
        array('tel', 'check_tel', '手机号码必填', self::EXISTS_VALIDATE, 'callback', self::MODEL_INSERT),
        array('tel', '', '手机号码已被存在', self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT),
        array('captcha','checkPhoneCode','手机验证码不正确',self::EXISTS_VALIDATE,'callback',self::MODEL_INSERT),
        array('checkcode', 'check_captcha', '验证码不正确', self::MUST_VALIDATE, 'callback', 'login'),
//        array('username', 'require', '用户名必填', self::MUST_VALIDATE, '', 'login'),
//        array('password', 'require', '密码必填', self::MUST_VALIDATE, '', 'login'),
//        array('captcha', 'require', '验证码必填', self::MUST_VALIDATE, '', 'login'),
//        array('captcha', 'check_captcha', '验证码不正确', self::MUST_VALIDATE, 'callback', 'login'),
    );
    
    /**
     * 自动完成
     * @var type 
     */
    protected $_auto     = array(
        array('salt', '\Org\Util\String::randString', self::MODEL_INSERT, 'function', 6),
        array('add_time', NOW_TIME, self::MODEL_INSERT),
    );
    
    /**
     * 用户注册
     */
    public function register() {
        $this->data['password'] = my_mcrypt($this->data['password'], $this->data['salt']);
        if ($this->add() === false) {
            return false;
        }
        return true;
    }
    
    /**
     * 验证验证码
     * @param type $code
     * @return bool
     */
    protected function check_captcha($code){
        $verify = new \Think\Verify();
        return $verify->check($code);
    }
    
    /**
     * 检测手机号码是否合法
     * @param type $tel
     * @return type
     */
    protected function check_tel($tel){
        $mobile = "/^(13[0-9]{9})|(14[0-9]{9})|(15[0-9]{9})|(17[0-9]{9})|(18[0-9]{9})$/";
        return preg_match($mobile, $tel);
    }
    
    /**
     * 验证手机验证码
     * @param type $code
     * @return boolean
     */
    protected function checkPhoneCode($code){
        //获取session中的验证码
        $session_code = session('TEL_CAPTCHA');
        session('TEL_CAPTCHA',null);
        if($code == $session_code){
            return true;
        }else{
            return false;
        }
    }
}
