<?php
/**
 *
 * 版权所有：小风博客<www.hotxf.com>
 * 作    者：XiaoFeng<admin@hotxf.com>
 * 日    期：2017-12-05 17:39:19
 * 版    本：1.2.0
 * 功能说明：数据库配置文件。
 *
 **/
return array(
    'DB_TYPE' => 'mysql', //数据库类型
    'DB_HOST' => 'localhost', //数据库主机
    'DB_NAME' => 'xfadmin', //数据库名称
    'DB_USER' => 'root', //数据库用户名
    'DB_PWD' => '', //数据库密码
    'DB_PORT' => '3306', //数据库端口
    'DB_PREFIX' => 'xf_', //数据库前缀
	'DB_CHARSET'=> 'utf8', // 字符集
	'DB_DEBUG'  => '', // 数据库调试模式 开启后可以记录SQL日志
);
?>