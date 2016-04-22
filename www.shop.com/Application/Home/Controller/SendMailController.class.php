<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Home\Controller;

/**
 * Description of SendMailController
 *
 * @author Sunhong
 */
class SendMailController extends \Think\Controller{
    
    public function sendMail() {
        $member_mail = "yfuri@foxmail.com";
        $token = \Org\Util\String::randString(40);
        $url = DOMAIN.'/'.U('Member/activate',array('mail'=>$member_mail,'token'=>$token));
        $mail_body = <<<BODY
                <a href="$url">点我激活</a><br />如果没有自动跳转,请复制下面的地址到地址栏进入激活<br />
                $url
BODY;
        $subject = "欢迎你加入我们";
        if(sendMail($member_mail, $subject, $mail_body) === false){
            echo '失败';
        }
        else echo '成功';
    }
}
