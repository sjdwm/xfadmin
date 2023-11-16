<?php
/**
 *
 * 版权所有：小风博客<www.hotxf.com>
 * 作    者：XiaoFeng<admin@hotxf.com>
 * 日    期：2017-09-15
 * 版    本：1.0.0
 * 功能说明：后台登录控制器。
 *
 **/

namespace Admin\Controller;

use Admin\Controller\ComController;

class LoginController extends ComController
{
    public function index()
    {
        $flag = $this->check_login();
        if ($flag) {
            $this->error('您已经登录,正在跳转到主页', U("index/index"));
        }

        $this->display();
    }

    public function login()
    {
        $verify = isset($_POST['verify']) ? trim($_POST['verify']) : '';
        if (!$this->check_verify($verify, 'login')) {
            $this->error('验证码错误！', U("login/index"));
        }

        $username = isset($_POST['user']) ? trim($_POST['user']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $remember = isset($_POST['remember']) ? $_POST['remember'] : 0;
        if ($username == '') {
            $this->error('用户名不能为空！', U("login/index"));
        } elseif ($password == '') {
            $this->error('密码必须！', U("login/index"));
        }

        $model = M("users");
        $user = $model->field('id,name,ename,username,password,head_img,mid,gid,email,lang,lock,token,black_time')->where(array('username' => $username,'stop' =>0))->find();
        //如果没有
        if (is_null($user)) {
            $this->error('用户名不存在,或被禁用', url("login/index"));
           
        }
        //判断账号是否锁
        if($user['black_time']+60>time()){
            $s = ($user['black_time']+60)-time();
            $this->error("该账号已锁，请 {$s} 秒后再试！");
            
        }
        if ($user['password'] == password($password)) {
            $salt = C("COOKIE_SALT");
            $ip = get_client_ip();
            $ua = $_SERVER['HTTP_USER_AGENT'];
            session_start();
            session('user',$user);
            //加密cookie信息
            $auth = password($user['id'].$user['username'].$user['password'].$ip.$ua.$salt);
            if ($remember) {
                cookie('auth', $auth, 3600 * 24 * 365);//记住我30天  
                cookie('uid', $user['id'], 3600 * 24 * 30);//记住我
            } else {
                /*Cookie::set('auth', $auth, 3600);//一小时
                Cookie::set('uid', $user['id'], 3600);//一小时*/
            }
            //修改登录时间和IP
            $model->where(array('id'=>$user['id']))->save(array('login_time'=>time(),'login_ip'=>$ip));
            userLog('后台登录成功,用户名:'.$user['username'],3);
            $this->success('登录成功！',U('index/index'));
            
        } else {
            //addlog('登录失败。', $username);
            $this->error('登录失败，请重试！', U("login/index"));
        }
    }

    function check_verify($code, $id = '')
    {
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }

    public function verify()
    {
        $config = array(
            'fontSize' => 14, // 验证码字体大小
            'length' => 4, // 验证码位数
            'useNoise' => false, // 关闭验证码杂点
            'imageW' => 100,
            'imageH' => 30,
        );
        $verify = new \Think\Verify($config);
        $verify->entry('login');
    }
    //退出登录
    public function logout()
    {
        cookie('uid',null);
        cookie('auth',null);
        session(null);
        $url = U("login/index");
        header("Location: {$url}");
        exit(0);
    }
}
