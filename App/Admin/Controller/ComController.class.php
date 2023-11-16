<?php
/**
 *
 * 版权所有：小风博客<www.hotxf.com>
 * 作    者：XiaoFeng<admin@hotxf.com>
 * 日    期：2017-09-14 10:11:40
 * 版    本：1.0.0
 * 功能说明：后台公用控制器。
 *
 **/

namespace Admin\Controller;

use Common\Controller\BaseController;
use Think\Auth;

class ComController extends BaseController
{
    public $USER;

    public function _initialize()
    {

        C(setting());
        if (!C("COOKIE_SALT")) {
            $this->error('请配置COOKIE_SALT信息');
        }
        /**
         * 不需要登录控制器
         */
        if (in_array(CONTROLLER_NAME, array("Login"))) {
            return true;
        }
        //检测是否登录
        $flag =  $this->check_login();        
        if (!$flag) {
           $this->error('您还没有登录!',U("login/index"));
        }
        $m = M();
        $prefix = C('DB_PREFIX');
        $UID = $this->USER['uid'];
        $userinfo = $m->query("SELECT * FROM {$prefix}auth_group g left join {$prefix}auth_group_access a on g.id=a.group_id where a.uid=$UID");
        $Auth = new Auth();
        $allow_controller_name = array('Upload');//放行控制器名称
        $allow_action_name = array();//放行函数名称
        if ($userinfo[0]['group_id'] != 1 && !$Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME . '/' . ACTION_NAME,
                $UID) && !in_array(CONTROLLER_NAME, $allow_controller_name) && !in_array(ACTION_NAME,
                $allow_action_name)
        ) {
            $this->error('没有权限访问本页面!');
        }
        $current_action_name = ACTION_NAME == 'edit' ? "index" : ACTION_NAME;
        $current = $m->query("SELECT s.id,s.title,s.name,s.tips,s.pid,p.pid as ppid,p.title as ptitle FROM {$prefix}auth_rule s left join {$prefix}auth_rule p on p.id=s.pid where s.name='" . MODULE_NAME.'/'.CONTROLLER_NAME . '/' . $current_action_name . "'");
        $this->assign('current', $current[0]);


        $menu_access_id = $userinfo[0]['rules'];

        if ($userinfo[0]['group_id'] != 1) {

            $menu_where = "AND id in ($menu_access_id)";

        } else {

            $menu_where = '';
        }
        $menu = M('auth_rule')->field('id,title,pid,name,icon')->where("islink=1 $menu_where ")->order('o ASC')->select();
        $menu = $this->getMenu($menu);
        $this->assign('menu', $menu);

    }


    protected function getMenu($items, $id = 'id', $pid = 'pid', $son = 'children')
    {
        $tree = array();
        $tmpMap = array();
        //修复父类设置islink=0，但是子类仍然显示的bug
        foreach( $items as $item ){
            if( $item['pid']==0 ){
                $father_ids[] = $item['id'];
            }
        }
        //----
        foreach ($items as $item) {
            $tmpMap[$item[$id]] = $item;
        }

        foreach ($items as $item) {
            //修复父类设置islink=0，但是子类仍然显示的bug 
            if( $item['pid']<>0 && !in_array( $item['pid'], $father_ids )){
                continue;
            }
            //----
            if (isset($tmpMap[$item[$pid]])) {
                $tmpMap[$item[$pid]][$son][] = &$tmpMap[$item[$id]];
            } else {
                $tree[] = &$tmpMap[$item[$id]];
            }
        }
        return $tree;
    }

    public function check_login(){
        session_start();
        $flag = false;
        $salt = C("COOKIE_SALT");
        $ip = get_client_ip();
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $auth = cookie('auth');
        $uid = session('user.id');
        if (!$uid) {
            $user = M('users')->field('id,name,ename,username,password,head_img,mid,gid,email,lang,lock,token')->where(array('id' => $uid))->find();

            if ($user) {
                if ($auth ==  password($uid.$user['username'].$user['password'].$ip.$ua.$salt)) {
                    $flag = true;
                    $this->USER = array('uid'=>$user['id']);
                    Session('user', $user);
                    userLog('后台登录成功(记住密码),用户名:'.$user['username'],1);
                }
            }
        }else{
            $flag = true;
            $this->USER = array('uid'=>$uid);
        }
        return $flag;
    }
}