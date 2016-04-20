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
        sendMail();
    }
}
