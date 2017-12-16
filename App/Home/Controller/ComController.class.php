<?php
/**
 *
 * 版权所有：小风博客<www.hotxf.com>
 * 作    者：XiaoFeng<admin@hotxf.com>
 * 日    期：2017-09-14 10:11:40
 * 版    本：1.0.0
 * 功能说明：前台公用控制器。
 *
 **/

namespace Home\Controller;

use Think\Controller;

class ComController extends Controller
{

    public function _initialize()
    {
        C(setting());
        /*
        $links = M('links')->limit(10)->order('o ASC')->select();
        $this->assign('links',$links);
        */
    }
}