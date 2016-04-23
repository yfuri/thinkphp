<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Home\Controller;

/**
 * Description of MemberController
 *
 * @author Sunhong
 */
class MemberController extends \Think\Controller{
    
    /**
     * 存储模型对象
     * @var \Admin\Model\MemberModel 
     */
    private $_model = null;

    /**
     * 初始化模型
     */
    protected function _initialize() {
        $this->_model = D('Member');
    }
    
    public function register() {
        if(IS_POST){
            if($this->_model->create() === false){
                $this->error(get_error($this->_model->getError()));
            }
            if($this->_model->register() === false){
                $this->error(get_error($this->_model->getError()));
            }
            $this->success('注册成功',U('login'));
        }else{
            $this->display();
        }
    }
    
    /**
     * 验证是否唯一.
     */
    public function checkUniqueByParams(){
        $model = D('Member');
        $cond = I('get.');
        if($cond){
            if($model->where($cond)->count()){
                $this->ajaxReturn(false);
            }
        }
        $this->ajaxReturn(true);
    }
    
    public function sendSMS($telphone) {
        $code = \Org\Util\String::randNumber(100000, 999999);
        //存session
        session('TEL_CAPTCHA',$code);
        //发短信
        $data = [
            'code'=>$code,
            'product'=>'yfuri',
        ];
        if(sendSMS($telphone, $data)){
            echo  '发送成功';
        }else{
            echo '发送失败';
        }
    }
    
    /**
     * 激活账号
     * @param type $mail
     * @param type $token
     */
    public function activate($mail,$token) {
        $cond = [
            'email'=>$mail,
            'token'=>$token,
            'send_time'=>['egt',NOW_TIME - 86400],//send_time + 86400 > now_time
        ];
        if(!$this->_model->where($cond)->count()){
            $this->error('验证失败',U('register'));
        }
        if($this->_model->where($cond)->setField(['status'=>1,'token'=>'','send_time'=>0])===false){
            $this->error('激活失败',U('login'));
        }
        $this->success('激活成功,请登录',U('login'));
    }
    
    /**
     * 前台会员登陆
     */
    public function login(){
        if(IS_POST){
            //收集数据
            if($this->_model->create('','login') === false){
                $this->error(get_error($this->_model->getError()));
            }
            //执行修改
            if(($password = $this->_model->login()) === false){
                $this->error(get_error($this->_model->getError()));
            }
            
            //跳转
            $this->success('登陆成功',U('Index/index'));
        }else{
            $this->display();
        }
    }
    
    
    /**
     * 退出
     */
    public function logout(){
        session(null);
        cookie(null);
        $this->success('退出成功',U('login'));
    }
    
    
    public function getmemberInfo() {
        if($member_info = session("MEMBER_INFO")){
            $member['id'] = $member_info['id'];
            $member['name'] = $member_info['username'];
            $this->ajaxReturn($member);
        }else{
            $this->ajaxReturn(false);
        }
    }
}
