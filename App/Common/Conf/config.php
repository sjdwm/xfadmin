<?php
/**
 *
 * 版权所有：小风博客<www.hotxf.com>
 * 作    者：XiaoFeng<admin@hotxf.com>
 * 日    期：2018-04-18 10:11:40
 * 版    本：1.0.0
 * 功能说明：配置文件。
 *
 **/
return array(
    //网站配置信息
    'URL' => 'https://'.$_SERVER['HTTP_HOST'], //网站根URL
    'COOKIE_SALT' => 'xiaofeng', //设置cookie加密密钥
    'DEFAULT_TIMEZONE'      =>  'UTC',  // 默认时区PRC中国,UTC国际
    //auth权限定义用户表
    'AUTH_CONFIG'            => array(
            'AUTH_USER'      => 'users'   //用户信息表
        ),
    // 文章默认图片
    'ARTICLE_IMG'               =>  '/Public/img/1.jpg',          
    // 允许访问的模块列表
    'MODULE_ALLOW_LIST'    =>    array('Home','Admin','Api'),
    'DEFAULT_MODULE'       =>    'Home',                       // 默认模块
    'TAGLIB_BUILD_IN'       =>  'Cx,Common\Tag\My',           //加载自定义标签
    // 表单令牌验证相关的配置参数
    'TOKEN_ON'      =>    true,            // 是否开启令牌验证 默认关闭
    'TOKEN_NAME'    =>    '__hash__',     // 令牌验证的表单隐藏字段名称，默认为__hash__
    'TOKEN_TYPE'    =>    'md5',         //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET'   =>    true,         //令牌验证出错后是否重置令牌 默认为true 
    //备份配置
    'DB_PATH_NAME' => 'db',        //备份目录名称,主要是为了创建备份目录
    'DB_PATH' => './db/',     //数据库备份路径必须以 / 结尾；
    'DB_PART' => '20971520',  //该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M
    'DB_COMPRESS' => '1',         //压缩备份文件需要PHP环境支持gzopen,gzwrite函数        0:不压缩 1:启用压缩
    'DB_LEVEL' => '9',         //压缩级别   1:普通   4:一般   9:最高
    //扩展配置文件
    'LOAD_EXT_CONFIG' => 'db',
    'SITENAME' => '小风博客', //标题
    //***********************************SESSION设置*****************************
    'SESSION_OPTIONS'       =>  array(
        'name'              =>  'SESSION',                 //设置session名
        'expire'            =>  24*3600*15,                   //SESSION保存15天
        'use_trans_sid'     =>  1,                            //跨页传递
        'use_only_cookies'  =>  0,                            //是否只开启基于cookies的session的会话方式
    ),
    //***********************************URL*************************************
    'URL_MODEL'             =>  2,       // 为了兼容性更好而设置成1 如果确认服务器开启了mod_rewrite 请设置为 2
    'URL_CASE_INSENSITIVE'  =>  false,  // 区分url大小写
    //默认错误跳转对应的模板文件
    'TMPL_ACTION_ERROR' => 'Public/jump',
    //默认成功跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => 'Public/jump',
    //***********************************评论管理***********************************************
    'COMMENT_REVIEW'            =>  '0',                    // 评论审核1:开启 0:关闭
    'COMMENT_SEND_EMAIL'        =>  '0',                    // 被评论邮件通知1:开启 0:关闭
    'EMAIL_RECEIVE'             =>  'admin@hotxf.com',     // 接收评论通知邮箱
    //***********************************邮件服务器**********************************
    'EMAIL_FROM_NAME'        => 'admin@hotxf.com',   // 发件人
    'EMAIL_SMTP'             => 'smtp.office365.com',   // smtp
    'EMAIL_USERNAME'         => 'admin@hotxf.com',   // 账号
    'EMAIL_PASSWORD'         => '2030',   // 密码  注意: 163和QQ邮箱是授权码；不是登录的密码
    'EMAIL_SMTP_SECURE'      => 'tls',   // 链接方式 如果使用QQ邮箱；需要把此项改为  ssl
    'EMAIL_PORT'             => '587', // 端口 如果使用QQ邮箱；需要把此项改为  587
);