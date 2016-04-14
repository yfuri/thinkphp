<?php

define('DOMAIN', 'http://admin.shop.com');
define('YUN_DOMAIN', 'http://7xsv2x.com1.z0.glb.clouddn.com');
return array(
    //'配置项'=>'配置值'
    /* 默认设定 */
    'DEFAULT_M_LAYER' => 'Model', // 默认的模型层名称
    'DEFAULT_C_LAYER' => 'Controller', // 默认的控制器层名称
    'DEFAULT_V_LAYER' => 'View', // 默认的视图层名称
    'DEFAULT_LANG' => 'zh-cn', // 默认语言
    'DEFAULT_THEME' => '', // 默认模板主题名称
    'DEFAULT_MODULE' => 'Admin', // 默认模块
    'DEFAULT_CONTROLLER' => 'Index', // 默认控制器名称
    'DEFAULT_ACTION' => 'index', // 默认操作名称
    'DEFAULT_CHARSET' => 'utf-8', // 默认输出编码
    'DEFAULT_TIMEZONE' => 'PRC', // 默认时区
    'DEFAULT_AJAX_RETURN' => 'JSON', // 默认AJAX 数据返回格式,可选JSON XML ...
    'DEFAULT_JSONP_HANDLER' => 'jsonpReturn', // 默认JSONP格式返回的处理方法
    'DEFAULT_FILTER' => 'htmlspecialchars', // 默认参数过滤方法 用于I函数...

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
    'DB_FIELDS_CACHE' => true, // 启用字段缓存
    'DB_CHARSET' => 'utf8', // 数据库编码默认采用utf8
    'DB_DEPLOY_TYPE' => 0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'DB_RW_SEPARATE' => false, // 数据库读写是否分离 主从式有效
    'DB_MASTER_NUM' => 1, // 读写分离后 主服务器数量
    'DB_SLAVE_NO' => '', // 指定从服务器序号

    /* 模板替换字符串 */
    'TMPL_PARSE_STRING' => array(
        '__JS__' => DOMAIN . '/Public/Js',
        '__STYLES__' => DOMAIN . '/Public/Styles',
        '__IMAGES__' => DOMAIN . '/Public/Images',
        '__UPLOADIFY__' => DOMAIN . '/Public/Ext/uploadify',
        '__LAYER__' => DOMAIN . '/Public/Ext/layer',
        '__ZTREE__' => DOMAIN . '/Public/Ext/ztree',
        '__TREEGRID__' => DOMAIN . '/Public/Ext/treegrid'
    ),
    /* 分页 */
    'PAGE_SIZE' => 20,
    'PAGE_THEME' => '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%',
    'SHOW_PAGE_TRACE'=> TRUE,
    
    /*文件上传配置项*/
    
    'UPLOAD_SETTING' => array(
//        'mimes'         =>  array(), //允许上传的文件MiMe类型
        'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
        'exts'          =>  array('jpeg','png','jpg','gif'), //允许上传的文件后缀
        'autoSub'       =>  true, //自动子目录保存文件
        'subName'       =>  array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath'      =>  './Public/Uploads/', //保存根路径
        'savePath'      =>  '', //保存路径
        'saveName'      =>  array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
        'replace'       =>  false, //存在同名是否覆盖
        'hash'          =>  true, //是否生成hash编码
        'callback'      =>  false, //检测文件是否存在回调，如果存在返回文件信息数组
        'driver'        =>  'Qiniu', // 文件上传驱动
        'driverConfig'  =>  array(
            'secrectKey'     => 'smsv3dnuIMpw1FFLQTEhzABnZbtb4Pp9hhFFNTjK', //七牛密码
            'accessKey'      => '-fm4rz7eqRxNISCvgxFO4kaTWNdoMI5gjtP3OU7Q', //七牛用户
            'domain'         => YUN_DOMAIN, //七牛服务器
            'bucket'         => 'thinkphp', //空间名称
            'timeout'        => 300, //超时时间
        ), // 上传驱动配置
    ),
);
