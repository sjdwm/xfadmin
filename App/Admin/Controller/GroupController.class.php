<?php
/**
 *
 * 版权所有：小风博客<www.hotxf.com>
 * 作    者：XiaoFeng<admin@hotxf.com>
 * 日    期：2018-04-15
 * 版    本：1.0.0
 * 功能说明：用户组控制器。
 *
 **/

namespace Admin\Controller;
use Common\Org\Data;
use Vendor\Tree;
class GroupController extends ComController{
    public function index(){
        $group = M('auth_group')->select();
        $this->assign('list', $group);
        $this->assign('nav', array('user', 'grouplist', 'grouplist'));//导航
        $this->display();
    }
    //用户组成员
    public function usergroup(){
        $gid = I('get.id');
        $group = I('post.group');
        $usergroup = M('auth_group')->field('id,title')->select();
        if(empty($group)){
            $group = $gid;
        }
        $this->assign('usergroup', $usergroup);
        $model = M('users');
        $gmodel = M('auth_group_access');
        $uid = I('post.uid');       
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $field = isset($_POST['field']) ? $_POST['field'] : '';
        $keyword = isset($_POST['keyword']) ? htmlentities($_POST['keyword']) : '';
        $order = I('post.order',0);//dump($order);exit;
        $where = "xf_auth_group_access.group_id=$group";     
        //用户搜索
         if ($keyword <> '') {
            if ($field == 'username') {
                $where .= " and username LIKE '%$keyword%'";
            }
            if ($field == 'phone') {
                $where .= " and phone LIKE '%$keyword%'";
            }
            if ($field == 'name') {
                $where .= " and name LIKE '%$keyword%'";
            }
            if ($field == 'email') {
                $where .= " and email LIKE '%$keyword%'";
            }
        }
        //排序规则
        $order = user_order($order);
        $count = $model->join('xf_auth_group_access ON xf_users.id = xf_auth_group_access.uid' )->where($where)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        //对分页样式进行定制
        $Page->setConfig('prev', '上一页');
        $Page->setConfig('next', '下一页');
        $Page->setConfig('first', '首页');
        $Page->setConfig('last', '尾页');
        $Page->setConfig('theme', "<ul class='pagination'><li>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%</li></ul>");
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $model->join('xf_auth_group_access ON xf_users.id = xf_auth_group_access.uid' )->order($order)->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        // foreach ($list as $key => $value) {
        //    $s=$gmodel->where(array('group_id'=>$group,'uid'=>$value['id']))->find();
        //    if(!$s>0){
        //     unset($list[$key]);
        //    }
        // }//dump($gid);exit;
        $this->assign('list',$list);// 赋值数据集
        $this->assign('show',$show);// 赋值分页输出
        $this->assign('group',$group);
        $this->display(); // 输出模板
    }
    //添加用户到组
    public function adduser(){
        $gid = I('get.id');
        $group = I('post.group');        
        $usergroup = M('auth_group')->field('id,title')->where(array('id'=>$gid))->find();
        $this->assign('usergroup', $usergroup);
        $model = M('users');
        $gmodel = M('auth_group_access');
        $uid = I('post.uid');
       
        $p = isset($_POST['p']) ? intval($_POST['p']) : '1';
        $field = isset($_POST['field']) ? $_POST['field'] : '';
        $keyword = isset($_POST['keyword']) ? htmlentities($_POST['keyword']) : '';
        $order = I('post.order',0);//dump($order);exit;
        $where = "";     
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
        $count = $model->where($where)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        //对分页样式进行定制
        $Page->setConfig('prev', '上一页');
        $Page->setConfig('next', '下一页');
        $Page->setConfig('first', '首页');
        $Page->setConfig('last', '尾页');
        $Page->setConfig('theme', "<ul class='pagination'><li>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%</li></ul>");
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $model->order($order)->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach ($list as $key => $value) {
           $s=$gmodel->where(array('group_id'=>$gid,'uid'=>$value['id']))->find();
           if($s>0){
            unset($list[$key]);
           }
        }//dump($list);exit;
        $this->assign('list',$list);// 赋值数据集
        $this->assign('show',$show);// 赋值分页输出
        $this->assign('count',$count);// 赋值用户数量输出
        $this->display(); // 输出模板
    }
    //添加选中的用户到用户组
    public function addusergroup(){
        if (!IS_AJAX) {
                $this->redirect('index');
                return;
                }
        $uid = I('get.uid/d', 0);
        $gid = I('get.gid/d', 0);
        //$this->ajaxReturn($gid);exit;
        if($uid >= 1){
        $group=array(
                    'uid'=>$uid,
                    'group_id'=>$gid
                    );
        D('AuthGroupAccess')->add($group);
        //$this->ajaxReturn($group);exit;
        }
    }
    //删除选中的用户组
    public function delgroup(){
        if (!IS_AJAX) {
                $this->redirect('index');
                return;
                }
        $uid = I('get.uid/d', 0);
        $gid = I('get.gid/d', 0);
        //$this->ajaxReturn($gid);exit;
        if($uid >= 1){        
        D('AuthGroupAccess')->where(array('uid'=>$uid,'group_id'=>$gid))->delete();
        //$this->ajaxReturn($group);exit;
        }
    }
    //删除用户组
    public function del(){

        $ids = isset($_POST['ids']) ? $_POST['ids'] : false;
        if (is_array($ids)) {
            foreach ($ids as $k => $v) {
                $ids[$k] = intval($v);
            }
            $ids = implode(',', $ids);
            $map['id'] = array('in', $ids);
            if (M('auth_group')->where($map)->delete()) {
                addlog('删除用户组ID：' . $ids);
                $this->success('恭喜，用户组删除成功！');
            } else {
                $this->error('参数错误！');
            }
        } else {
            $this->error('参数错误！');
        }
    }
    //用户组权限修改
    public function update(){

        $data['title'] = isset($_POST['title']) ? trim($_POST['title']) : false;
        $id = isset($_POST['id']) ? intval($_POST['id']) : false;
        if ($data['title']) {
            $status = isset($_POST['status']) ? $_POST['status'] : '';
            if ($status == 'on') {
                $data['status'] = 1;
            } else {
                $data['status'] = 0;
            }
            //如果是超级管理员一直都是启用状态
            if ($id == 1) {
                $data['status'] = 1;
            }

            $rules = isset($_POST['rules']) ? $_POST['rules'] : 0;
            if (is_array($rules)) {
                foreach ($rules as $k => $v) {
                    $rules[$k] = intval($v);
                }
                $rules = implode(',', $rules);
            }
            $data['rules'] = $rules;
            if ($id) {
                $group = M('auth_group')->where('id=' . $id)->data($data)->save();
                if ($group) {
                    addlog('编辑用户组，ID：' . $id . '，组名：' . $data['title']);
                    $this->success('恭喜，用户组修改成功！','index');
                    exit(0);
                } else {
                    $this->success('未修改内容');
                }
            } else {
                M('auth_group')->data($data)->add();
                addlog('新增用户组，ID：' . $id . '，组名：' . $data['title']);
                $this->success('恭喜，新增用户组成功！');
                exit(0);
            }
        } else {
            $this->success('用户组名称不能为空！');
        }
    }
    //用户组权限编辑
    public function edit(){

        $id = isset($_GET['id']) ? intval($_GET['id']) : false;
        if (!$id) {
            $this->error('参数错误！');
        }

        $group = M('auth_group')->where('id=' . $id)->find();
        if (!$group) {
            $this->error('参数错误！');
        }
        //获取所有启用的规则
        $rule = M('auth_rule')->field('id,pid,title')->where(array('cate'=>1,'status'=>1))->order('o asc')->select();
        $hrule = M('auth_rule')->field('id,pid,title')->where(array('cate'=>2,'status'=>1))->order('o asc')->select();
        $group['rules'] = explode(',', $group['rules']);
        $rule = Data::channelLevel($rule,0,'&nbsp;','id');
        $hrule = Data::channelLevel($hrule,0,'&nbsp;','id');
        //$rule = $this->getMenu($rule);
        $this->assign('rule', $rule);
        $this->assign('hrule', $hrule);
        $this->assign('group', $group);
        $this->assign('nav', array('user', 'grouplist', 'addgroup'));//导航
        $this->display('form');
    }
    //新增用户组
    public function add(){
        //获取所有启用的规则
        $rule = M('auth_rule')->field('id,pid,title')->where(array('cate'=>1,'status'=>1))->order('o asc')->select();
        $hrule = M('auth_rule')->field('id,pid,title')->where(array('cate'=>2,'status'=>1))->order('o asc')->select();
        $rule = Data::channelLevel($rule,0,'&nbsp;','id');
        $hrule = Data::channelLevel($hrule,0,'&nbsp;','id');
        $this->assign('rule', $rule);
        $this->assign('hrule', $hrule);
        $this->display('form');
    }

    public function status(){

        $id = I('id');
        if (!$id) {
            $this->error('参数错误！');
        }
        if ($id == 1) {
            $this->error('此用户组不可变更状态！');
        }
        $group = M('auth_group')->where('id=' . $id)->find();
        if (!$group) {
            $this->error('参数错误！');
        }
        $status = $group['status'];
        if ($status == 1) {
           $res = M('auth_group')->data(array('status' => 0))->where('id=' . $id)->save();
        }
        if ($status != 1 ) {
            $res = M('auth_group')->data(array('status' => 1))->where('id=' . $id)->save();
        }
        if ($res) {
            $this->success('恭喜，更新状态成功！');
        } else {
            $this->error('更新失败！');
        }
    }
    //用户权限
     public function auth(){
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $m = M('auth_rule');
        $pagesize = 10;#每页数量
        $offset = $pagesize * ($p - 1);//计算记录偏移量
        $count = $m->count();

        $list = $m->order('o asc')->where(array('cate'=>1))->select();
        $list = Data::tree($list,'name','id','pid');//dump($cate);exit;
        //$list = $this->getMenu($list);//dump($list);exit;

        $page = new \Think\Page($count, $pagesize);
        $this->assign('list', $list);

        $this->display();
    }
    //用户权限下级菜单的显示与隐藏
    public function is_show(){
        $pid = I('get.pid');
        $model = M('auth_rule');
        $id = $model->field('id')->where(array('pid'=>$pid))->select();
        foreach ($id as $k => $v) {
            
            $zid = $model->field('id')->where(array('pid'=>$v['id']))->select();
            foreach ($zid as $key => $value) {
                $id[] = $value;
                $zzid = $model->field('id')->where(array('pid'=>$value['id']))->select();
                foreach ($zzid as $kk => $vv) {
                    $id[] = array('id'=>$vv['id']);
                }
            }
        }
        //dump($id);exit;
        $this->ajaxReturn($id);exit;
    }
    //添加权限
    public function authadd(){
        $pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0;
        $option = M('auth_rule')->order('o ASC')->field('id,pid,title')->where(array('cate'=>1))->select();
        $tree = new Tree($option);
        $str = "<option value=\$id \$selected>\$spacer\$title</option>"; //生成的形式
        $category = $tree->get_tree(0, $str, $pid);//dump($category);exit;
        //$option = $this->getMenu($option);
        $this->assign('option', $category);
        $this->display();
    }
    //更新
    public function authupdate(){
        $id = I('post.id', '', 'intval');
        $data['pid'] = I('post.pid', '', 'intval');
        $data['title'] = I('post.title', '', 'strip_tags');
        $data['name'] = I('post.name', '', 'strip_tags');
        $data['ename'] = I('post.ename', '', 'strip_tags');
        $data['icon'] = I('post.icon');
        $data['islink'] = I('post.islink', '', 'intval');
        $data['status'] = 1;
        $data['o'] = I('post.o', '', 'intval');
        $data['tips'] = I('post.tips');
        if ($id) {
            M('auth_rule')->data($data)->where("id='{$id}'")->save();
            addlog('编辑菜单，ID：' . $id);
        } else {
            M('auth_rule')->data($data)->add();
            addlog('新增菜单，名称：' . $data['title']);
        }

        $this->success('操作成功！','auth');
    }
    //修改
    public function authedit($id = 0){
        $id = intval($id);
        $m = M('auth_rule');
        $currentmenu = $m->where("id='$id'")->find();
        if (!$currentmenu) {
            $this->error('参数错误！');
        }

        $option = $m->order('o ASC')->select();
        $tree = new Tree($option);
        $str = "<option value=\$id \$selected>\$spacer\$title</option>"; //生成的形式
        $option = $tree->get_tree(0, $str, $currentmenu['pid']);
        //$option = $this->getMenu($option);
        $this->assign('option', $option);
        $this->assign('currentmenu', $currentmenu);
        $this->display('authadd');
    }
    //后台用户权限删除
    public function htdel(){

        $ids = isset($_POST['ids']) ? $_POST['ids'] : false;
        if (is_array($ids)) {
            foreach ($ids as $k => $v) {
                $ids[$k] = intval($v);
            }
            $ids = implode(',', $ids);
            $map['id'] = array('in', $ids);
            if (M('auth_rule')->where(array('pid'=>array('in',$ids)))->count()) {
                $this->error('存在子类，严禁删除!');//存在子类，严禁删除。
            } 
            if (M('auth_rule')->where($map)->delete()) {
               
                $this->success('恭喜，删除成功！');
            } else {
                //dump(M()->_sql());exit;
                $this->error('参数错误！');
            }
        } else {
            $ids=I('get.ids');
            if (M('auth_rule')->where(array('pid'=>$ids))->count()) {
                
                $this->error('存在子类，严禁删除!');//存在子类，严禁删除。
            } 
            if (M('auth_rule')->where(array('id'=>$ids))->delete()) {
               
                $this->success('恭喜，删除成功！');
            } else {
                //dump(M()->_sql());exit;
                $this->error('参数错误111！');
            }
        }
    }
    //前台用户权限
     public function homeauth(){
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $m = M('auth_rule');
        $pagesize = 10;#每页数量
        $offset = $pagesize * ($p - 1);//计算记录偏移量
        $count = $m->count();

        $list = $m->order('o asc')->where(array('cate'=>2))->select();
        $list = Data::tree($list,'name','id','pid');//dump($cate);exit;
        //$list = $this->getMenu($list);//dump($list);exit;

        $page = new \Think\Page($count, $pagesize);
        $this->assign('list', $list);

        $this->display();
    }
    //添加前台用户权限
    public function homeauthadd(){
        $pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0;
        $option = M('auth_rule')->order('o ASC')->field('id,pid,title')->where(array('cate'=>2))->select();
        $tree = new Tree($option);
        $str = "<option value=\$id \$selected>\$spacer\$title</option>"; //生成的形式
        $category = $tree->get_tree(0, $str, $pid);//dump($category);exit;
        //$option = $this->getMenu($option);
        $this->assign('option', $category);
        $this->display();
    }
    //前台用户权限更新
    public function homeauthupdate(){
        $id = I('post.id', '', 'intval');
        $data['pid'] = I('post.pid', '', 'intval');
        $data['title'] = I('post.title', '', 'strip_tags');
        $data['ename'] = I('post.ename', '', 'strip_tags');
        $data['name'] = I('post.name', '', 'strip_tags');
        $data['url'] = I('post.url', '', 'strip_tags');
        //$data['icon'] = I('post.icon');
        $data['islink'] = I('post.islink', '', 'intval');
        $data['status'] = 1;
        $data['cate'] = 2;
        $data['o'] = I('post.o', '', 'intval');
        //$data['tips'] = I('post.tips');
        if ($id) {
            M('auth_rule')->data($data)->where("id='{$id}'")->save();
            addlog('编辑菜单，ID：' . $id);
        } else {
            M('auth_rule')->data($data)->add();
            addlog('新增菜单，名称：' . $data['title']);
        }

        $this->success('操作成功！','homeauth');
    }
    //前台用户权限修改
    public function homeauthedit($id = 0){
        $id = intval($id);
        $m = M('auth_rule');
        $currentmenu = $m->where("id='$id'")->find();
        if (!$currentmenu) {
            $this->error('参数错误！');
        }

        $option = $m->order('o ASC')->where(array('cate'=>2))->select();
        $tree = new Tree($option);
        $str = "<option value=\$id \$selected>\$spacer\$title</option>"; //生成的形式
        $option = $tree->get_tree(0, $str, $currentmenu['pid']);
        //$option = $this->getMenu($option);
        $this->assign('option', $option);
        $this->assign('currentmenu', $currentmenu);
        $this->display('homeauthadd');
    }
    //删除前台权限
     public function homedel(){

        $ids = isset($_POST['ids']) ? $_POST['ids'] : false;
        if (is_array($ids)) {
            foreach ($ids as $k => $v) {
                $ids[$k] = intval($v);
            }
            $ids = implode(',', $ids);
            $map['id'] = array('in', $ids);
            if (M('auth_rule')->where(array('pid'=>array('in',$ids)))->count()) {
                $this->error('存在子类，严禁删除!');//存在子类，严禁删除。
            } 

            if (M('auth_rule')->where($map)->delete()) {
               
                $this->success('恭喜，删除成功！');
            } else {
                //dump(M()->_sql());exit;
                $this->error('参数错误！');
            }
        } else {
            $ids=I('get.ids');
            if (M('auth_rule')->where(array('pid'=>$ids))->count()) {
                
                $this->error('存在子类，严禁删除!');//存在子类，严禁删除。
            } 
            
            if (M('auth_rule')->where(array('id'=>$ids))->delete()) {
               
                $this->success('恭喜，删除成功！');
            } else {
                //dump(M()->_sql());exit;
                $this->error('参数错误111！');
            }
        }
    }
}