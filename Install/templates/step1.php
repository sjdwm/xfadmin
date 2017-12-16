<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<title><?php echo $Title; ?> - <?php echo $Powered; ?></title>
<link rel="stylesheet" href="./css/install.css?v=9.0" />
</head>
<body>
<div class="wrap">
  <?php require './templates/header.php';?>
  <div class="section">
    <div class="main cc">
      <pre class="pact" readonly="readonly">小风博客-开源软件使用协议

版权所有 (c)2015-2026，小风博客 保留所有权利。 
感谢您选择小风博客admin开源系统，基于 PHP + MySQL 的技术，采用ThinkPHP框架开发。
为了使你正确并合法的使用本软件，请你在使用前务必阅读清楚下面的协议条款：

一、协议许可的权利
1、您可以在完全遵守本最终用户授权协议的基础上，将本软件应用于任何用途，而不必支付软件版权授权费用。
2、您可以在协议规定的约束和限制范围内修改 小风博客admin 源代码或界面风格以适应您的网站要求。
3、您拥有使用本软件构建的网站全部内容所有权，并独立承担与这些内容的相关法律义务。

二、有限担保和免责声明
1、本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。 
2、用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未购买产品技术服务之前，我们不承诺对免费用户提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任。

版本最新更新： 2017年12月01日 By 小风博客

小风博客网站：http://www.hotxf.com
小风博客演示站：http://demo.hotxf.com


</pre>
    
    </div>
    <div class="bottom tac"> <a href="<?php echo $_SERVER['PHP_SELF']; ?>?step=2" class="btn">接 受</a> </div>
  </div>
</div>
<?php require './templates/footer.php';?>
</body>
</html>