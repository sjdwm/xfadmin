<?php
/**
 *
 * 版权所有：小风博客<www.hotxf.com>
 * 作    者：XiaoFeng<admin@hotxf.com>
 * 日    期：2017-09-15
 * 版    本：1.0.0
 * 功能说明：后台首页控制器。
 *
 **/

namespace Admin\Controller;

class IndexController extends ComController
{
    public function index()
    {

       $model = M('users');
        $mysql = $model->query("select VERSION() as mysql");
        $count = $model->count();
        //以下是统计近十五天内增加的用户数量
        $i =0;
        $arr =array();
        for($i;$i<15;$i++)
        {
            $j =$i - 1;
            $arr[$i] =$this -> day_add(time(),'-'.$i.' day','-'.$j.' day');
        }
        $this->assign('mysql', $mysql[0]['mysql']);
        $this->assign('users',$count);
        $this -> assign('user_line',$arr);
        $this->display();
    }
    function day_add($time,$date,$mdate){
        if($date==$mdate){
            $mdate = '+1 day';
        }
        $id = session('user.id');
        $users =M('user_log');
        $day =date("m-d",strtotime($date));
        $time1 =strtotime(date("Y-m-d",strtotime($date)));
        $time2 =strtotime(date("Y-m-d",strtotime($mdate)));
        $num =$users -> where("rank=1 and time > '$time1' and time < '$time2' ")-> count();
        $result =array('day'=>$day,'num'=>$num);
        return $result;
    }
}