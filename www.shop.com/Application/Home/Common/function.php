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
 * 发送短信
 * @param type $telphone
 * @param type $data
 */
function sendSMS($telphone, $data){
    $config = C('ALIDAYU_SETTING');
    vendor('Alidayu.Autoloader');
    $c = new \TopClient;
    $c->format = 'json';
    $c->appkey    = $config['ak'];
    $c->secretKey = $config['sk'];
//    $c->appkey =  '23350663';
//    $c->secretKey = '001adb1e2ef1e6d3fe83d08e04d0c13c';
    $req = new \AlibabaAliqinFcSmsNumSendRequest;
    $req->setSmsType("normal");
    $req->setSmsFreeSignName("注册验证");
    $req->setSmsParam(json_encode($data));//"{code:'111111',product:'yfuri'}"
    $req->setRecNum($telphone);
    $req->setSmsTemplateCode("SMS_8090043");
    $resp = $c->execute($req);
    dump($resp);
    exit;
    if(isset($resp->result->success) && $resp->result->success){
        return true;
    }else{
        return false;
    }
}
//PHPMailerAutoload


function sendMail($emailAddress,$subject,$body) {
    vendor('PHPMailer.PHPMailerAutoload');
    $mail = new PHPMailer;
    $config = C('PHPMAILER_SETTING');
    //$mail->SMTPDebug = 3;                               // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = $config['Host'];  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $config['Username'];                 // SMTP username
    $mail->Password = $config['Password'];                           // SMTP password
//    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
//    $mail->Port = 465;                                    // TCP port to connect to

    $mail->setFrom($config['Username']);
    $mail->addAddress($emailAddress);     // Add a recipient
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->CharSet = 'UTF-8';
    $mail->Subject = $subject;
    $mail->Body    = $body;
    return $mail->send();
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

/**
 * 
 * @staticvar type $instance
 * @return \Redis
 */
function get_redis(){
    static $instance = null;
    if(empty($instance)){
        $instance = new Redis();
        $instance->connect(C('REDIS_HOST'),C('REDIS_PORT'));
    }
    return $instance;
}

/**
 * 金额格式化
 * @param number $number        原始数字.
 * @param integer $decimals     小数点后的位数.
 * @param string $dec_point     小数点使用的字符.
 * @param string $thousands_sep 千位分隔符.
 * @return string
 */
function money_format($number,$decimals=2,$dec_point ='.',$thousands_sep=''){
    return number_format($number,$decimals,$dec_point,$thousands_sep);
}