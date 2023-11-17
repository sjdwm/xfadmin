<?php
/**
 *
 * 版权所有：小风博客<www.hotxf.com>
 * 作    者：XiaoFeng<admin@hotxf.com>
 * 日    期：2017-09-15
 * 版    本：1.0.0
 * 功能说明：用户控制器。
 *
 **/

namespace Admin\Controller;
use Common\Org\Data;
use Vendor\Tree;
use Common\Org\AjaxPage;
use Common\Org\Page;
class MemberController extends ComController
{
     //负责数据查询---显示会员列表
    public function index(){
        $usergroup = M('auth_group')->field('id,title')->select();
        $this->assign('usergroup', $usergroup);
        $this->display();
    }
    //用户列表
    public function ulist(){
        $model = M('users'); // 实例化User对象
        $uid = I('post.uid');
        $gid = I('post.group');
       
        $p = isset($_POST['p']) ? intval($_POST['p']) : '1';
        $field = isset($_POST['field']) ? $_POST['field'] : '';
        $keyword = isset($_POST['keyword']) ? htmlentities($_POST['keyword']) : '';
        $order = I('post.order',0);//dump($order);exit;
        $where = '';     
        //用户搜索
         if ($keyword <> '') {
            if ($field == 'username') {
                $where = "username LIKE '%$keyword%'";
            }
            if ($field == 'phone') {
                $where = "phone LIKE '%$keyword%'";
            }
            if ($field == 'name') {
                $where = "name LIKE '%$keyword%'";
            }
            if ($field == 'email') {
                $where = "email LIKE '%$keyword%'";
            }
        }
        //排序规则
        $order = user_order($order);
        if($gid>0){
            if($keyword <> ''){
                $where = $where." and group_id=$gid";
            }else{
                $where = "group_id=$gid";
            }  
            $count = $model->join('xf_auth_group_access ON xf_users.id=xf_auth_group_access.uid')->field('xf_auth_group_access.group_id,xf_users.*')->where($where)->count();
            $Page = new AjaxPage($count,12);
            $show = $Page->show();
            $list = $model->order($order)->join('xf_auth_group_access ON xf_users.id=xf_auth_group_access.uid')->field('xf_auth_group_access.group_id,xf_users.*')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
            
        }else{
            
            $count = $model->where($where)->count();// 查询满足要求的总记录数
            $Page = new AjaxPage($count,1);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show = $Page->show();// 分页显示输出
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $list = $model->order($order)->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        }       
       
        $this->assign('list',$list);// 赋值数据集
        $this->assign('show',$show);// 赋值分页输出
        $this->assign('count',$count);// 赋值用户数量输出
        $this->display(); // 输出模板
    }
    //会员禁用和开启
    public function userstop(){
        if (!IS_AJAX) {
            $this->redirect('index');
            return;
        }
            //var_dump($_GET);exit;
            $User = M("users"); // 实例化config
            $vo = I('get.v');
            $id = I('get.id');
            if($id==1){
                $this->ajaxReturn('超级管理员不可以禁用');exit;
            }
            
        if($vo == 1){
            //要修改stop值,0正常,1禁用
            $data['stop'] = 0;
            $User->where(array('id'=>$id))->save($data); // 根据条件更新记录    
        }elseif($vo == 0){
            $data['stop'] = 1;
            $User->where(array('id'=>$id))->save($data); // 根据条件更新记录        
        }
            
    }
    //在职状态修改
    public function userstatus(){
        if (!IS_AJAX) {
            $this->redirect('index');
            return;
        }
            //var_dump($_GET);exit;
            $User = M("users"); // 实例化config
            $vo = I('get.v');
            $id = I('get.id');
            if($id==1){
                $this->ajaxReturn('超级管理员不可以修改');exit;
            }
        if($vo == 1){
            //要修改stop值,0正常,1禁用
            $data['status'] = 0;
            $User->where(array('id'=>$id))->save($data); // 根据条件更新记录    
        }elseif($vo == 0){
            $data['status'] = 1;
            $User->where(array('id'=>$id))->save($data); // 根据条件更新记录        
        }
            
        }
    //执行修改用户密码(返回到当前分页)
    public function pass(){
        if (!IS_AJAX) {
                $this->redirect('index');
                return;
                }
        $id = trim(I('post.uid'));
        $p1 = trim(I('post.password'));
        $p2 = trim(I('post.repassword'));
        $name = trim(I('post.username'));
        $user = M('users');
        if($p1!=$p2){
            $this->ajaxReturn(array('code'=>0,'msg'=>'两次密码不同'));exit;
        }
        if(strlen($p2)<5 || strlen($p2)>16){
            $this->ajaxReturn(array('code'=>1,'msg'=>'密码必须在5-16位'));exit;
        }      
            $data['password'] = password($p2);
            $user->where(array('id'=>$id))->save($data);
            userLog('后台修改用户密码,用户名:'.$name,5); 
            $this->ajaxReturn(array('code'=>2,'msg'=>'修改成功,请手动关闭窗口'));exit;
        
        
    }
    public function del(){

        $uids = isset($_REQUEST['uids']) ? $_REQUEST['uids'] : false;
        //uid为1的禁止删除
        if ($uids == 1 or !$uids) {
            $this->error('参数错误！');
        }
        if (is_array($uids)) {
            foreach ($uids as $k => $v) {
                if ($v == 1) {//uid为1的禁止删除
                    unset($uids[$k]);
                }
                $uids[$k] = intval($v);
            }
            if (!$uids) {
                $this->error('参数错误！');
                $uids = implode(',', $uids);
            }
        }

        $map['id'] = array('in', $uids);
        $mapg['uid'] = array('in', $uids);
        if (M('users')->where($map)->delete()) {
            M('auth_group_access')->where($mapg)->delete();
            addlog('删除会员UID：' . $uids);
            $this->success('恭喜，用户删除成功！');
        } else {
            $this->error('参数错误！');
        }
    }

    public function edit(){
        //usercompany(3);exit;
        $uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;
        if ($uid) {
            //$member = M('member')->where("uid='$uid'")->find();
            $prefix = C('DB_PREFIX');
            $user = M('users');
            $member = $user->field("{$prefix}users.*,{$prefix}auth_group_access.group_id")->join("{$prefix}auth_group_access ON {$prefix}users.id = {$prefix}auth_group_access.uid")->where("{$prefix}users.id=$uid")->find();

        } else {
            $this->error('参数错误！');
        }
        //查出用户工号
        $member['job'] = M('personnel_files')->where(array('uid'=>$member['id'],'is_delete'=>0))->getField('job');
        $usergroup = M('auth_group')->field('id,title')->select();
        $usergroup_access = M('auth_group_access')->where(array('uid'=>$uid))->getField('group_id',true);
        $rule = M('company')->field('id,pid,cname')->where(array('status'=>1,'pid'=>0))->order('o asc')->select();
        $gidname = M('company')->where(array('id'=>$member['gid'],'status'=>1))->getField('cname');;
        //$mid = explode(',',$member['mid']);
        //$gid = explode(',',$member['gid']);
        $this->assign('gidname', $gidname);
        $this->assign('mid', $member['mid']);
        $this->assign('gid', $member['gid']);
        $this->assign('rule', $rule);
        $this->assign('usergroup', $usergroup);
        $this->assign('usergroup_access', $usergroup_access);
        $this->assign('member', $member);
        $this->display('form');
    }
    //查看
    public function show(){
        //usercompany(3);exit;
        $uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;
        if ($uid) {
            //$member = M('member')->where("uid='$uid'")->find();
            $prefix = C('DB_PREFIX');
            $user = M('users');
            $member = $user->field("{$prefix}users.*,{$prefix}auth_group_access.group_id")->join("{$prefix}auth_group_access ON {$prefix}users.id = {$prefix}auth_group_access.uid")->where("{$prefix}users.id=$uid")->find();

        } else {
            $this->error('参数错误！');
        }
        //查出用户工号
        $member['job'] = M('personnel_files')->where(array('uid'=>$member['id'],'is_delete'=>0))->getField('job');
        $usergroup = M('auth_group')->field('id,title')->select();
        $usergroup_access = M('auth_group_access')->where(array('uid'=>$uid))->getField('group_id',true);
        $rule = M('company')->field('id,pid,cname')->where(array('status'=>1,'pid'=>0))->order('o asc')->select();
        $gidname = M('company')->where(array('id'=>$member['gid'],'status'=>1))->getField('cname');;
        //$mid = explode(',',$member['mid']);
        //$gid = explode(',',$member['gid']);
        $this->assign('gidname', $gidname);
        $this->assign('mid', $member['mid']);
        $this->assign('gid', $member['gid']);
        $this->assign('rule', $rule);
        $this->assign('usergroup', $usergroup);
        $this->assign('usergroup_access', $usergroup_access);
        $this->assign('member', $member);
        $this->display();
    }
    //修改用户信息
    public function update($ajax = ''){
        if ($ajax == 'yes') {
            $uid = I('get.uid', 0, 'intval');
            $gid = I('get.gid', 0, 'intval');
            M('auth_group_access')->data(array('group_id' => $gid))->where("uid='$uid'")->save();
            die('1');
        }
        $d = I('post.');
        // foreach ($d['company'] as $key => $value) {
        //    $company .=$value.',';
        // }
        // foreach ($d['rules'] as $key => $value) {
        //    $rules .=$value.',';
        // }
        $data['mid'] = $d['mid'];
        $data['gid'] = $d['gid'];
        
        $uid = isset($_POST['uid']) ? intval($_POST['uid']) : false;
        $user = isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES) : '';
        $group_id = I('post.group_ids');
        if (!$group_id) {
            $this->error('请选择用户组！');
        }
        $password = isset($_POST['password']) ? trim($_POST['password']) : false;
        if ($password) {
            $data['password'] = password($password);
        }
        //$head = I('post.head', '', 'strip_tags');
        $data['sex'] = isset($_POST['sex']) ? intval($_POST['sex']) : 0;
        if($data['sex']==1){
            $data['head_img'] = '/Public/img/1.png';
        }else{
            $data['head_img'] = '/Public/img/2.gif';
        }
        
        //$data['birthday'] = isset($_POST['birthday']) ? strtotime($_POST['birthday']) : 0;
        $data['birthday'] = isset($_POST['birthday']) ? $_POST['birthday'] : 0;
        $data['phone'] = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $data['telphone'] = isset($_POST['telphone']) ? trim($_POST['telphone']) : '';
        $data['post'] = isset($_POST['post']) ? trim($_POST['post']) : '';
        $data['name'] = isset($_POST['name']) ? trim($_POST['name']) : '';
        $data['ename'] = isset($_POST['ename']) ? trim($_POST['ename']) : '';
        $data['email'] = isset($_POST['email']) ? trim($_POST['email']) : '';
        $data['lang'] = isset($_POST['lang']) ? trim($_POST['lang']) : '1';
        
        //dump($data);exit;
        if (!$uid) {
            if ($user == '') {
                $this->error('用户名称不能为空！');
            }
            if (!$password) {
                $this->error('用户密码不能为空！');
            }
            if (M('users')->where("username='$user'")->count()) {
                $this->error('用户名已被占用！');
            }
            $data['username'] = trim($user);
            $data['reg_time'] = time();
            $uid = M('users')->data($data)->add();
            foreach ($group_id as $k => $v) {
                $group=array(
                    'uid'=>$uid,
                    'group_id'=>$v
                    );
                D('AuthGroupAccess')->add($group);
            }
            addlog('新增会员，会员UID：' . $uid);
        } else {
         
            // 修改权限,先删除
            D('AuthGroupAccess')->where(array('uid'=>$uid))->delete();
            foreach ($group_id as $k => $v) {
                $group=array(
                    'uid'=>$uid,
                    'group_id'=>$v
                    );
                D('AuthGroupAccess')->add($group);
            }
            //工号修改
            //查出用户工号
            $job = M('personnel_files')->where(array('uid'=>$uid,'is_delete'=>0))->getField('job');
            $rejob = M('personnel_files')->where(array('job'=>$_POST['job'],'is_delete'=>0))->getField('id');
            if($job != $_POST['job']){
                //如果库里已经存在job则不能修改,不能重复job
                if($rejob<1){
                    M('personnel_files')->where(array('uid'=>$uid,'is_delete'=>0))->save(array('job'=>$_POST['job']));
                }
                
            }
            // addlog('编辑会员信息，会员UID：' . $uid);
           $s= M('users')->data($data)->where(array('id'=>$uid))->save();
        }
        $this->success('操作成功！','index');
    }


    public function add(){

        $usergroup = M('auth_group')->field('id,title')->select();
        $rule = M('company')->field('id,pid,cname')->where(array('status'=>1,'pid'=>0))->order('o asc')->select();
        $this->assign('rule', $rule);
        $this->assign('usergroup', $usergroup);
        $this->display('form');
    }
    //Ajax获取部门信息
    public function gidinfo(){
        if (!IS_AJAX) {
                $this->redirect('add');
                return;
                }
        // $mid = I('post.mid');
        // $rule = M('company')->field('id,pid,cname')->where(array('status'=>1,'pid'=>$mid))->order('o asc')->select();
        
        // $this->ajaxReturn($rule);exit;
        $mid = I('post.mid');
        $pid = I('post.gid');
        $rule = M('company')->field('id,pid,cname,ename')->where(array('status'=>1,'islink'=>1))->order('o asc')->select();
        $tree = new Tree($rule);
       
        //判断用户语言
        if(session('user.lang')==1){
              $str = "<option value=\$id \$selected>\$spacer\$ename</option>";
        }else{
              $str = "<option value=\$id \$selected>\$spacer\$cname</option>";
        }
        $category = $tree->get_tree($mid, $str, $pid);
        if($category){
            $data = '<select id="gid" name="gid" class="form-control"><option value="">选择</option>'.$category.'</select>';
        }else{
            $data = '<select id="gid" name="gid" class="form-control"><option value="">空</option></select>';
        }
        
        //dump($category);exit;
        $this->ajaxReturn($data);exit;
    }
}
