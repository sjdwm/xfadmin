<?php
/**
 *
 * 版权所有：小风博客<www.hotxf.com>
 * 作    者：XiaoFeng<admin@hotxf.com>
 * 日    期：2017-09-14 10:12:21
 * 版    本：1.0.0
 * 功能说明：模块公共文件。
 *
 **/

//清除数组中所有字符串两端空格
function TrimArray($Input){
    if (!is_array($Input))
        return trim($Input);
    return array_map('TrimArray', $Input);
}
//显示用户所在公司及部门
function user_company($mid){
    $mid = explode(',',$mid);
    $group = '';

    foreach ($mid as $key => $value) {
        $group .= M('company')->where(array('id'=>$value))->getField('cname').',';
        //两个组以上的就显示多个用户组
        if($key>1){
            return '多个属性';exit;
        }
    }
    return substr($group,0, -1);
}
/**
 *
 * 获取用户信息
 *
 **/
function member($id, $field = false){
    $model = M('users');
    if ($field) {
        return $model->field($field)->where(array('id' => $id))->find();
    } else {
        return $model->where(array('id' => $id))->find();
    }
}
/**
 * 管理员操作记录
 * @param $rank 日志类型,1为登录日志
 * @param $log_info 记录信息
 */
function userLog($log_info,$rank = 1,$username = '记住密码用户'){
    $add['name'] = session('user.username')==''?$username:session('user.username');
    $add['desc'] = $log_info;     //操作内容
    $add['ip'] = get_client_ip(); //获取客户端IP
    $add['time'] = time();        //记录时间
    $add['rank'] = $rank;         //日志类别,1为登录日志,2登录失败被锁,3管理员操作
    M("user_log")->add($add);
}
/**
 * 用户访问记录
 * @param $rank 日志类型,1为访问日志
 * @param $log_info 访问记录信息
 */
function userLogin($rank = 1,$username = '访客'){
    $protocol = (!empty($_SERVER['HTTP_FROM_HTTPS']) && $_SERVER['HTTP_FROM_HTTPS'] !== 'off') ? "https://" : "http://";
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $add['name'] = session('user.username')==''?$username:session('user.username');//登录用户名
    $add['url'] = $url;//访问的url;
    $add['os'] = get_os();     //操作系统
    $add['broswer'] = get_broswer()[0];     //浏览器
    $add['broswerb'] = get_broswer()[1];     //浏览器版本号
    $add['ip'] = get_client_ip(); //获取客户端IP
    $add['date'] = date('Y-m-d',time());//记录时间
    $add['time'] = time();        //记录时间
    $add['rank'] = $rank;         //日志类别,1为访问日志
    $model = M("user_login");
    $state = $model->where(array('url'=>$add['url'],'ip'=>$add['ip'],'date'=>$add['date']))->find();
    if(!$state>0){
        $model->add($add);
    }
    
}
function UpImage($callBack = "image", $width = 100, $height = 100, $image = "")
{

    echo '<iframe scrolling="no" frameborder="0" border="0" onload="this.height=this.contentWindow.document.body.scrollHeight;this.width=this.contentWindow.document.body.scrollWidth;" width=' . $width . ' height="' . $height . '"  src="' . U('Upload/uploadpic',
            array('Width' => $width, 'Height' => $height, 'BackCall' => $callBack)) . '"></iframe>
         <input type="hidden" ' . 'value = "' . $image . '"' . 'name="' . $callBack . '" id="' . $callBack . '">';
}

function BatchImage($callBack = "image", $width = 100, $height = 100, $image = "")
{
    
    echo '<iframe scrolling="no" frameborder="0" border="0" width=100% onload="this.height=this.contentWindow.document.body.scrollHeight;" src="' . U('Upload/batchpic',
            array('Width' => $width, 'Height' => $height, 'BackCall' => $callBack)) . '"></iframe>
		<input type="hidden" ' . 'value = "' . $image . '"' . 'name="' . $callBack . '" id="' . $callBack . '">';
}


/*
 * 函数：网站配置获取函数
 * @param  string $k      可选，配置名称
 * @return array          用户数据
*/
function setting($k = 'all')
{
    $cache = S($k);
    //如果缓存不为空直接返回
    if (null != $cache) {
        return $cache;
    }
    $data = '';
    $setting = M('setting');
    //判断是否查询全部设置项
    if ($k == 'all') {
        $setting = $setting->field('k,v')->select();
        foreach ($setting as $v) {
            $config[$v['k']] = $v['v'];
        }
        $data = $config;

    } else {
        $result = $setting->where("k='{$k}'")->find();
        $data = $result['v'];

    }
    //建立缓存
    if ($data) {
        S($k, $data);
    }
    return $data;
}

/**
 * 函数：格式化字节大小
 * @param  number $size 字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '')
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) {
        $size /= 1024;
    }
    return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 函数：加密
 * @param string            密码
 * @return string           加密后的密码
 */
function password($password)
{
    /*
    *后续整强有力的加密函数
    */
    return md5('X' . $password . 'F');

}

/**
 * 随机字符
 * @param number $length 长度
 * @param string $type 类型
 * @param number $convert 转换大小写
 * @return string
 */
function random($length = 6, $type = 'string', $convert = 0)
{
    $config = array(
        'number' => '1234567890',
        'letter' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'string' => 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789',
        'all' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    );

    if (!isset($config[$type])) {
        $type = 'string';
    }
    $string = $config[$type];

    $code = '';
    $strlen = strlen($string) - 1;
    for ($i = 0; $i < $length; $i++) {
        $code .= $string{mt_rand(0, $strlen)};
    }
    if (!empty($convert)) {
        $code = ($convert > 0) ? strtoupper($code) : strtolower($code);
    }
    return $code;
}

//获取所有的子级id
function category_get_sons($sid, &$array = array())
{
    //获取当前sid下的所有子栏目的id
    $categorys = M("category")->where("pid = {$sid}")->select();

    $array = array_merge($array, array($sid));
    foreach ($categorys as $category) {
        category_get_sons($category['id'], $array);
    }
    $data = $array;
    unset($array);
    return $data;

}


/**
 * 获取文章url地址
 * url结构：http://wwww.hotxf.com/分类/子分类/子分类/id.html
 * 使用方法：模板中{:articleUrl(array('aid'=>$val['aid']))}
 *
 *
 * @param $data
 * @return $string
 */
function articleUrl($data)
{
    //如果数组为空直接返回空字符
    if (!$data) {
        return '';
    }
    //如果参数错误直接返回空字符
    if (!isset($data['aid'])) {
        return '';
    }

    $aid = (int)$data['aid'];

    //获取文章信息
    $article = M('article')->where(array('aid' => $aid))->find();
    //获取当前内容所在分类
    $category = M('category')->where(array('id' => $article['sid']))->find();
    //获取当前分类
    $categoryUrl = $category['dir'];
    //遍历获取当前文章所在分类的有上级分类并且组合url
    while ($category['pid'] <> 0) {
        $category = M('category')->where(array('id' => $category['pid']))->find();
        $categoryUrl = $category['dir'] . "/" . $categoryUrl;
        //如果上级分类已经无上级分类则退出
    }

    $categoryUrl = __ROOT__ . "/" . $categoryUrl;
    //组合文章url
    $articleUrl = $categoryUrl . '/' . $aid . ".html";
    return $articleUrl;

}
/**  
 * 获取客户端浏览器信息
 * @param   null  
 * @author  https://hotxf.com
 * @return  string   
 */  
function get_broswer(){
    $sys = $_SERVER['HTTP_USER_AGENT'];  //获取用户代理字符串  
    if (stripos($sys, "Firefox/") > 0) {  
        preg_match("/Firefox\/([^;)]+)+/i", $sys, $b);  
        $exp[0] = "Firefox";  
        $exp[1] = $b[1];    //获取火狐浏览器的版本号  
    } elseif (stripos($sys, "Maxthon") > 0) {  
        preg_match("/Maxthon\/([\d\.]+)/", $sys, $aoyou);  
        $exp[0] = "傲游";  
        $exp[1] = $aoyou[1];  
    } elseif (stripos($sys, "MSIE") > 0) {  
        preg_match("/MSIE\s+([^;)]+)+/i", $sys, $ie);  
        $exp[0] = "IE";  
        $exp[1] = $ie[1];  //获取IE的版本号  
    } elseif (stripos($sys, "OPR") > 0) {  
        preg_match("/OPR\/([\d\.]+)/", $sys, $opera);  
        $exp[0] = "Opera";  
        $exp[1] = $opera[1];    
    } elseif(stripos($sys, "Edge") > 0) {  
        //win10 Edge浏览器 添加了chrome内核标记 在判断Chrome之前匹配  
        preg_match("/Edge\/([\d\.]+)/", $sys, $Edge);  
        $exp[0] = "Edge";  
        $exp[1] = $Edge[1];  
    } elseif (stripos($sys, "Chrome") > 0) {  
        preg_match("/Chrome\/([\d\.]+)/", $sys, $google);  
        $exp[0] = "Chrome";  
        $exp[1] = $google[1];  //获取google chrome的版本号  
    } elseif(stripos($sys,'rv:')>0 && stripos($sys,'Gecko')>0){  
        preg_match("/rv:([\d\.]+)/", $sys, $IE);  
        $exp[0] = "IE";  
        $exp[1] = $IE[1];  
    }else {  
        $exp[0] = "未知浏览器";  
        $exp[1] = "";   
    }  
    return $exp;  
}
/**  
 * 获取客户端操作系统信息,包括win10 
 * @param   null  
 * @author  http://hotxf.com
 * @return  string   
 */  
function get_os(){
    $agent = $_SERVER['HTTP_USER_AGENT'];
    $os = false;
    if (preg_match('/win/i', $agent) && preg_match('/nt 6.0/i', $agent))  
    {  
      $os = 'Windows Vista';  
    }  
    else if (preg_match('/win/i', $agent) && preg_match('/nt 6.1/i', $agent))  
    {  
      $os = 'Windows 7';  
    }  
      else if (preg_match('/win/i', $agent) && preg_match('/nt 6.2/i', $agent))  
    {  
      $os = 'Windows 8';  
    }else if(preg_match('/win/i', $agent) && preg_match('/nt 10.0/i', $agent))  
    {  
      $os = 'Windows 10';#添加win10判断  
    }else if (preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent))  
    {  
      $os = 'Windows XP';  
    }  
    else if (preg_match('/win/i', $agent) && preg_match('/nt 5/i', $agent))  
    {  
      $os = 'Windows 2000';  
    }  
    else if (preg_match('/win/i', $agent) && preg_match('/nt/i', $agent))  
    {  
      $os = 'Windows NT';  
    }  
    else if (preg_match('/win/i', $agent) && preg_match('/32/i', $agent))  
    {  
      $os = 'Windows 32';  
    }  
    else if (preg_match('/linux/i', $agent))  
    {  
      $os = 'Linux';  
    }  
    else if (preg_match('/unix/i', $agent))  
    {  
      $os = 'Unix';  
    }  
    else if (preg_match('/sun/i', $agent) && preg_match('/os/i', $agent))  
    {  
      $os = 'SunOS';  
    }  
    else if (preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent))  
    {  
      $os = 'IBM OS/2';  
    }  
    else if (preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent))  
    {  
      $os = 'MAC';  
    }  
    else if (preg_match('/PowerPC/i', $agent))  
    {  
      $os = 'PowerPC';  
    }  
    else if (preg_match('/AIX/i', $agent))  
    {  
      $os = 'AIX';  
    }  
    else if (preg_match('/HPUX/i', $agent))  
    {  
      $os = 'HPUX';  
    }  
    else if (preg_match('/NetBSD/i', $agent))  
    {  
      $os = 'NetBSD';  
    }  
    else if (preg_match('/BSD/i', $agent))  
    {  
      $os = 'BSD';  
    }  
    else if (preg_match('/OSF1/i', $agent))  
    {  
      $os = 'OSF1';  
    }  
    else if (preg_match('/IRIX/i', $agent))  
    {  
      $os = 'IRIX';  
    }  
    else if (preg_match('/FreeBSD/i', $agent))  
    {  
      $os = 'FreeBSD';  
    }  
    else if (preg_match('/teleport/i', $agent))  
    {  
      $os = 'teleport';  
    }  
    else if (preg_match('/flashget/i', $agent))  
    {  
      $os = 'flashget';  
    }  
    else if (preg_match('/webzip/i', $agent))  
    {  
      $os = 'webzip';  
    }  
    else if (preg_match('/offline/i', $agent))  
    {  
      $os = 'offline';  
    }  
    else  
    {  
      $os = '未知操作系统';  
    }  
    return $os;    
}
/**
 * 发送邮件
 * @param  string $address 需要发送的邮箱地址 发送给多个地址需要写成数组形式
 * @param  string $addcc 需要抄送的邮箱地址 发送给多个地址需要写成数组形式
 * @param  string $subject 标题
 * @param  string $content 内容
 * @param  array $user    发件账号array('name','pwd')
 * @return boolean       是否成功
 */
function send_email($address,$subject,$content,$user='',$addcc){
    if(empty($user)){
        $email_username=C('EMAIL_USERNAME');
        $email_password=C('EMAIL_PASSWORD');
        $email_from_name=C('EMAIL_FROM_NAME');
    }else{
        $email_username=$user['name'];
        $email_password=$user['pwd'];
        $email_from_name=$user['name'];
    }    
    $email_smtp=C('EMAIL_SMTP');
    $email_smtp_secure=C('EMAIL_SMTP_SECURE');
    $email_port=C('EMAIL_PORT');
    if(empty($email_smtp) || empty($email_username) || empty($email_password) || empty($email_from_name)){
        return array("error"=>1,"message"=>'邮箱配置不完整');
    }
    require_once './App/Common/Org/class.phpmailer.php';
    require_once './App/Common/Org/class.smtp.php';
    $phpmailer=new \Phpmailer();
    // 设置PHPMailer使用SMTP服务器发送Email
    $phpmailer->IsSMTP();
    // 设置设置smtp_secure
    $phpmailer->SMTPSecure=$email_smtp_secure;
    // 设置port
    $phpmailer->Port=$email_port;
    // 设置为html格式
    $phpmailer->IsHTML(true);
    // 设置邮件的字符编码'
    $phpmailer->CharSet='UTF-8';
    // 设置SMTP服务器。
    $phpmailer->Host=$email_smtp;
    // 设置为"需要验证"
    $phpmailer->SMTPAuth=true;
    // 设置用户名
    $phpmailer->Username=$email_username;
    // 设置密码
    $phpmailer->Password=$email_password;
    // 设置邮件头的From字段。
    $phpmailer->From=$email_username;
    // 设置发件人名字
    $phpmailer->FromName=$email_from_name;
    // 添加收件人地址，可以多次使用来添加多个收件人
    if(is_array($address)){
        foreach($address as $addressv){
            $phpmailer->AddAddress($addressv);
        }
    }else{
        $phpmailer->AddAddress($address);
    }
    //添加抄送人地址
    if(!empty($addcc)){
        if(is_array($addcc)){
            foreach($addcc as $addccv){
                $phpmailer->AddCC($addccv);
            }
        }else{
            $phpmailer->AddCC($addcc);
        }
    }
    // 设置邮件标题
    $phpmailer->Subject=$subject;
    // 设置邮件正文
    $phpmailer->Body=$content;
    // 发送邮件。
    if(!$phpmailer->Send()) {
        $phpmailererror=$phpmailer->ErrorInfo;
        return array("error"=>1,"message"=>$phpmailererror);
    }else{
        return array("error"=>0);
    }
}









