<?php
/**
 * 增加日志
 * @param $log
 * @param bool $name
 */

function addlog($log, $name = false)
{
    $Model = M('log');
    if (!$name) {
        session_start();
        $uid = session('uid');
        if ($uid) {
            $user = M('member')->field('user')->where(array('uid' => $uid))->find();
            $data['name'] = $user['user'];
        } else {
            $data['name'] = '';
        }
    } else {
        $data['name'] = $name;
    }
    $data['t'] = time();
    $data['ip'] = $_SERVER["REMOTE_ADDR"];
    $data['log'] = $log;
    $Model->data($data)->add();
}
/**
 * 用户订单列表--排序规则
 * @param $id 传入数字
 */
function user_order($id){
switch($id){
    case '0':
        return 'id asc';
    break;
    case '1':
        return 'id desc';
    break;
    case '2':
        return 'reg_time asc';
    break;
    case '3':
        return 'reg_time desc';
    break;
    case '4':
        return 'login_time asc';
    break;
    case '5':
        return 'login_time desc';
    break;
    default:
        return 'id asc';
    }
}
//显示用户组
function user_group($uid){
    $usergroup_access = M('auth_group_access')->where(array('uid'=>$uid))->getField('group_id',true);//dump($usergroup_access);
    $group = '';

    foreach ($usergroup_access as $key => $v) {
        $group .= M('auth_group')->where(array('id'=>$v))->getField('title').',';
        //两个组以上的就显示多个用户组
        if($key>1){
            return '多个用户组';exit;
        }
    }
    return substr($group,0, -1);
}