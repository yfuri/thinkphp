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
}