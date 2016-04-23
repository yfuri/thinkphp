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
        array('checkcode', 'check_captcha', '验证码不正确', self::MUST_VALIDATE, 'callback',self::MODEL_INSERT),

        array('username', 'require', '用户名必填', self::MUST_VALIDATE, '', 'login'),
        array('password', 'require', '密码必填', self::MUST_VALIDATE, '', 'login'),
        array('checkcode', 'require', '验证码必填', self::MUST_VALIDATE, '', 'login'),
        array('checkcode', 'check_captcha', '验证码不正确', self::MUST_VALIDATE, 'callback', 'login'),
    );
    
    /**
     * 自动完成
     * @var type 
     */
    protected $_auto     = array(
        array('salt', '\Org\Util\String::randString', self::MODEL_INSERT, 'function'),
        array('add_time', NOW_TIME, self::MODEL_INSERT),
    );
    
    /**
     * 用户注册
     */
    public function register() {
        $this->data['password'] = my_mcrypt($this->data['password'], $this->data['salt']);
        $member_mail = $this->data['email'];
        if (($member_id = $this->add()) === false) {
            return false;
        }
        // 发送激活邮件
        if($this->_send_activate_mail($member_id,$member_mail) === false){
            return FALSE;
        }
        return true;
    }
    
    /**
     * 会员登录
     * @return boolean
     */
    public function login(){
        //为了安全我们将用户信息都删除
        session('MEMBER_INFO',null);
        $request_data = $this->data;
        //1.验证用户名是否存在
        $userinfo = $this->getByUsername($this->data['username']);
        if(empty($userinfo)){
            $this->error = '用户不存在';
            return false;
        }
        //2.进行密码匹配验证
        $password = my_mcrypt($request_data['password'], $userinfo['salt']);
        if($password != $userinfo['password']){
            $this->error = '密码不正确';
            return false;
        }
        //为了后续会话获取用户信息,我们存下来
        session('MEMBER_INFO',$userinfo);
        
        //保存自动登陆信息
        $this->_save_token($userinfo['id']);
        if($this->_cookie2db() === false){
            $this->error = '购物车同步失败';
            return false;
        }
        return true;
    }
    
    /**
     * 保存自动登录信息
     * @param type $member_id
     * @return boolean
     */
    private function _save_token($member_id){
        //清空原有的令牌
        $token_model = M('MemberToken');
        cookie('AUTO_LOGIN_TOKEN',null);
        $token_model->delete($member_id);
        
        //判断是否需要自动登陆
        $remeber = I('post.remember');
        if(!$remeber){
            return true;
        }
        $data = [
            'member_id'=>$member_id,
            'token'=>sha1(mcrypt_create_iv(32)),
        ];
        //存到cookie和数据表中
        cookie('AUTO_LOGIN_TOKEN',$data,604800);
        return $token_model->add($data);
    }
    
    /**
     * 同步购物车
     * @return type
     */
    private function _cookie2db(){
        //将用户的cookie购物车保存到数据库中
        $shopping_car_model = D('ShoppingCar');
        return $shopping_car_model->cookie2db();
    }
    
    /**
     * 发送激活邮件
     * @param type $member_id
     * @param type $member_mail
     * @return boolean
     */
    private function _send_activate_mail($member_id,$member_mail){
        $token = \Org\Util\String::randString(40);
        $url = DOMAIN.'/'.U('Member/activate',array('mail'=>$member_mail,'token'=>$token));
        $mail_body = <<<BODY
                <a href="$url">点我激活</a><br />如果没有跳转,请点击下面的地址或复制到地址栏进入激活<br />
                $url
BODY;
        $subject = "欢迎你加入我们";
        $data = array(
            'id' => $member_id,
            'token' => $token,
            'send_time' => NOW_TIME 
        );
        if(sendMail($member_mail, $subject, $mail_body) === false){
            $this->error = '激活邮件发送失败';
            return FALSE;
        }
        if($this->save($data) === false){
            return FALSE;
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
    
    /**
     * 自动登录
     */
    public function autoLogin() {
        $data = cookie('AUTO_LOGIN_TOKEN');
        if(empty($data)){
            return false;
        }
        $token_model = M("MemberToken");
        // 如果cookie中保存的token信息错误
        if(!$token_model->where($data)->count()){
            return false;
        }
        // 重新保存token
        if($this->_save_token($data['member_id']) === false){
            return false;
        }else{
            // 保存用户信息到session,包括基本信息,权限信息
            $userinfo = $this->find($data['member_id']);
            session('MEMBER_INFO', $userinfo);
            return TRUE;
        }
    }
    
}
