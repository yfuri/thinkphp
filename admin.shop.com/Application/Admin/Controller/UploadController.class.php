<?php

/*
 * 文件说明
 * @author sunhong
 */

namespace Admin\Controller;

/**
 * 文件上传
 *
 * @author Sunhong
 */
class UploadController extends \Think\Controller{
    
    public function upload() {
        //创建上传类
        $config = C('UPLOAD_SETTING');
        $upload = new \Think\Upload($config);
        //上传
        $file_info = $upload->upload($_FILES);
        $file_url = $file_info['file']['savepath'] . $file_info['file']['savename'];
        if($config['driver'] == 'Qiniu'){
            $file_url = str_replace('/', '_', $file_url);
        }
        $data = array(
            'status' => $file_info?1:0,
            'file_url' => $file_url,
            "msg" => $upload->getError(),
            'yun_domain' => $config['driverConfig']['domain']
        );
        $this->ajaxReturn($data);
    }
}
