<?php

return array(
    'TMPL_ACTION_SUCCESS' => './Public/tpl/dispatch_jump.html', //自定义success和error的提示页面模板
    'TMPL_ACTION_ERROR' => './Public/tpl/dispatch_jump.html',
    'TMPL_EXCEPTION_FILE' => './Public/tpl/exception.php',
    'TMPL_TEMPLATE_SUFFIX' => '.php', //视图页面文件后缀
    // 'SHOW_PAGE_TRACE' => true,  //开启页面Trace功能
 
    'DB_TYPE' => 'mysql',
    'DB_HOST'=>DB_HOST,
    'DB_NAME'=>DB_NAME,  // 数据库名
    'DB_USER'=>DB_USER,  //账号
    'DB_PWD'=>DB_PASSWORD, //密码
    'DB_PORT'=>DB_PORT,
    'DB_CHARSET' => 'utf8',
    'DB_PREFIX' => 'ch_', //数据表前缀
    'DB_DEBUG' => TRUE, // 数据库调试模式 开启后可以记录SQL日志
    'LOAD_EXT_FILE' => 'code.config',    // 自动加载错误码文件

    'TMPL_PARSE_STRING' => array(
        '__LAYUIADMIN__' => '/Public/layuiadmin', // layuiadmin文件路径
        '__STATIC__' => '/Public/static', // 静态文件路径
        '__JS__' => '/Public/static/js', // 静态文件路径-JS
        '__CSS__' => '/Public/static/css', // 静态文件路径-CSS
        '__IMG__' => '/Public/static/img', // 静态文件路径-IMG
        '__IMAGES__' => '/Public/static/images', // 静态文件路径-IMAGES
        '__ADMIN__' => '/Public/static/admin', // 静态文件路径-admin后台
        '__ADMIN_JS__' => '/Public/static/admin/js', // 静态文件路径-admin后台-JS
        '__ADMIN_CSS__' => '/Public/static/admin/css', // 静态文件路径-admin后台-CSS
        '__ADMIN_IMG__' => '/Public/static/admin/img', // 静态文件路径-admin后台-IMG
        '__HOME__' => '/Public/static/home', // 静态文件路径-home前台
        '__HOME_JS__' => '/Public/static/home/js', // 静态文件路径-home前台-JS
        '__HOME_CSS__' => '/Public/static/home/css', // 静态文件路径-home前台-CSS
        '__HOME_IMG__' => '/Public/static/home/images',  // 静态文件路径-home前台-IMG
        '__WAP__' => '/Public/static/wap', // 静态文件路径-wap前台
        '__WAP_JS__' => '/Public/static/wap/js', // 静态文件路径-wap前台-JS
        '__WAP_CSS__' => '/Public/static/wap/css', // 静态文件路径-wap前台-CSS
        '__WAP_IMG__' => '/Public/static/wap/images',  // 静态文件路径-wap前台-IMG
        '__AGENT__' => '/Public/static/agent', // 静态文件路径-admin后台
        '__AGENT_JS__' => '/Public/static/agent/js', // 静态文件路径-agent后台-JS
        '__AGENT_CSS__' => '/Public/static/agent/css', // 静态文件路径-agent后台-CSS
        '__AGENT_IMG__' => '/Public/static/agent/img', // 静态文件路径-agent后台-IMG
    ),
    "wx_mini_program" => [
        'appid' => 'wxdde55c27ba2eb033',
        'appsecret' => 'cee8d445860bdc1ba9ae006a3a6feaf2'
    ]
);