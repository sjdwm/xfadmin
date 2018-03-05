#xfadmin变更日志

## V1.2
### 2017-10-15

1、删除`config_bak.php`文件，增加`config.php`,`db.php`为数据库配置文件，安装请在`db.php`修改数据库信息
2、增加栏目英文目录
3、增加获取文章url功能吗，可以实现 `http://wwww.xfadmin.com/分类/子分类/子分类/id.html`的url结构，
具体参考`App/Common/Common/function.php`函数 articleUrl函数
4、ThinkPHP框架下增加的文件,TP3.2.3版本
\ThinkPHP\Library\ 目录下2个目录
Tree
Wechat
\ThinkPHP\Library\Vendor  目录下4个文件
Database.class.php
HttpDownload.class.php
Tree.class.php
Zip.class.php
5、admin后台安装:localhost/install  
6、后台登录地址localhost/admin  账号密码:admin
7、有的环境.htaccess URL重写方法以及遇到No input file specified的解决方法
在Fastcgi模式下，php不支持rewrite的目标网址的PATH_INFO的解析，当我们的 ThinkPHP运行在URL_MODEL=2时，就会出现
 No input file specified.的情况， 

这时可以修改网站目录的.htaccess文件： 
将 
RewriteRule ^(.*)$ index.php/$1 [QSA,PT,L] 
改为 RewriteRule ^(.*)$ index.php?s=$1 [QSA,PT,L] 

就可以了。
