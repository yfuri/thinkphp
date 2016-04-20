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
 * 
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

function sendMail($email = '',$data = '') {
    vendor('PHPMailer.PHPMailerAutoload');

    $mail = new PHPMailer;

    //$mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.163.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'yfuri_sh@163.com';                 // SMTP username
    $mail->Password = 'sunhong163';                           // SMTP password
//    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
//    $mail->Port = 587;                                    // TCP port to connect to

    $mail->setFrom('yfuri_sh@163.com', 'sunhong');
    $mail->addAddress('yfuri@foxmail.com');     // Add a recipient
//    $mail->addAddress('ellen@example.com');               // Name is optional
//    $mail->addReplyTo('info@example.com', 'Information');
//    $mail->addCC('cc@example.com');
//    $mail->addBCC('bcc@example.com');

//    $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = 'Here is the test subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message has been sent';
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