<?php
define('DOMAIN', 'http://www.shop.com');
return array(
	//'配置项'=>'配置值'
    /* 模板替换字符串 */
    'TMPL_PARSE_STRING' => array(
        '__JS__' => DOMAIN . '/Public/js',
        '__STYLE__' => DOMAIN . '/Public/style',
        '__IMAGES__' => DOMAIN . '/Public/images',
        '__UPLOAD_URL_PREFIX__' => 'http://7xsv2x.com1.z0.glb.clouddn.com',
    ),
    /* 数据库设置 */
    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => '127.0.0.1', // 服务器地址
    'DB_NAME' => 'thinkphp', // 数据库名
    'DB_USER' => 'root', // 用户名
    'DB_PWD' => '123456', // 密码
    'DB_PORT' => '3306', // 端口
    'DB_PREFIX' => '', // 数据库表前缀
    'DB_PARAMS' => array(), // 数据库连接参数    
    'DB_DEBUG' => TRUE, // 数据库调试模式 开启后可以记录SQL日志
    'DB_FIELDS_CACHE' => false, // 启用字段缓存
    'DB_CHARSET' => 'utf8', // 数据库编码默认采用utf8
    'DB_DEPLOY_TYPE' => 0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'DB_RW_SEPARATE' => false, // 数据库读写是否分离 主从式有效
    'DB_MASTER_NUM' => 1, // 读写分离后 主服务器数量
    'DB_SLAVE_NO' => '', // 指定从服务器序号
    
    /* 阿里大鱼配置 */
    'ALIDAYU_SETTING' =>array(
        'ak'=>'23350663',
        'sk'=>'001adb1e2ef1e6d3fe83d08e04d0c13c',
    ),
    /* phpMailer配置 */
    'PHPMAILER_SETTING' => array(
        'Host'  => 'smtp.163.com',
        'Username' => 'yfuri_sh@163.com',
        'Password' => 'sunhong163',
    ),
    
    //Redis Session配置
    'SESSION_AUTO_START'    =>  true,    // 是否自动开启Session
    'SESSION_TYPE'          =>  'Redis',    //session类型
    'SESSION_PERSISTENT'    =>  1,        //是否长连接(对于php来说0和1都一样)
    'SESSION_CACHE_TIME'    =>  1,        //连接超时时间(秒)
    'SESSION_EXPIRE'        =>  0,        //session有效期(单位:秒) 0表示永久缓存
    'SESSION_PREFIX'        =>  'sess_',        //session前缀
    'SESSION_REDIS_HOST'    =>  '127.0.0.1', //分布式Redis,默认第一个为主服务器
    'SESSION_REDIS_PORT'    =>  '6379',           //端口,如果相同只填一个,用英文逗号分隔
    'SESSION_REDIS_AUTH'    =>  '',    //Redis auth认证(密钥中不能有逗号),如果相同只填一个,用英文逗号分隔
    
    
//    'HTML_CACHE_ON'     =>    true, // 开启静态缓存
//    'HTML_CACHE_TIME'   =>    60,   // 全局静态缓存有效期（秒）
//    'HTML_FILE_SUFFIX'  =>    '.shtml', // 设置静态缓存文件后缀
//    'HTML_CACHE_RULES'  =>     array(
//        'goods'=>['goods_info_{id}',60],//所有的控制器的goods方法都缓存成'goods_info_' . $_GET['id'] . '.shtml 缓存60秒
//        'Index:goods'=>['goods/goods_{id}',60],//缓存Index控制器的goods操作,生成的文件放在goods目录下
//        'Index:index'=>['{:action}',3600],//缓存Index控制器的index操作,文件名是index.shtml,缓存1小时
//    ),
);