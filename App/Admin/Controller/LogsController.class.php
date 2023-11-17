<?php
/**
 *
 * 版权所有：小风博客<www.hotxf.com>
 * 作    者：XiaoFeng<admin@hotxf.com>
 * 日    期：2017-09-15
 * 版    本：1.0.0
 * 功能说明：后台日志控制器。
 *
 **/

namespace Admin\Controller;
use Common\Org\AjaxPage;
use Common\Org\IpLocation;
class LogsController extends ComController{
    /*
     * 用户日志
     */
    public function user_log_list(){
        $log = M("user_log");
        $rank['rank'] = I('post.rank','0');
        if($rank['rank'] == '0'){unset($rank);}
        $count = $log->where($rank)->count();
        $Page = new AjaxPage($count,16);
        $show = $Page->show();
        $list = $log->where($rank)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);
        $this->assign('show',$show);
        $this->display();
    }
    /*
     * 访客日志
     */
    public function login_log_list(){
        $Ip = new IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
        $log = M("user_login");
        $rank['rank'] = I('post.rank','0');
        if($rank['rank'] == '0'){unset($rank);}
        $count = $log->where($rank)->count();
        $Page = new AjaxPage($count,16);
        $show = $Page->show();
        $list = $log->where($rank)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        //dump($Ip->getlocation('137.59.100.132'));exit;
        foreach ($list as $k => $v) {
            $list[$k]['weizhi']=$Ip->getlocation($v['ip']);
        }
        $this->assign('list',$list);
        $this->assign('show',$show);
        $this->display();
    }
   
}