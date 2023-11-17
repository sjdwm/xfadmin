<?php
/**
 *
 * 版权所有：小风博客<www.hotxf.com>
 * 作    者：XiaoFeng<admin@hotxf.com>
 * 日    期：2017-09-15
 * 版    本：1.0.0
 * 功能说明：用户组控制器。
 *
 **/

namespace Admin\Controller;
use Common\Org\Data;
use Vendor\Tree;
class CompanyController extends ComController{
   //用户权限
     public function index(){
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $m = M('company');
        $pagesize = 10;#每页数量
        $offset = $pagesize * ($p - 1);//计算记录偏移量
        $count = $m->count();

        $list = $m->order('o asc')->select();
        $list = Data::tree($list,'cname','id','pid');//dump($cate);exit;
        //$list = $this->getMenu($list);
        //dump($list);exit;

        $page = new \Think\Page($count, $pagesize);
        $this->assign('list', $list);

        $this->display();
    }

    // public function add()
    // {

    //     //获取所有启用的规则
    //     $rule = M('company')->field('id,pid,name')->where('status=1')->order('o asc')->select();
    //     $list = Data::channelLevel($rule,0,'&nbsp;','id');//dump($list);exit;
    //     $rule = $this->getMenu($rule);//dump($rule);exit;
    //     $this->assign('rule', $list);
    //     $this->display('form');
    // }


    //用户权限下级菜单的显示与隐藏
    public function is_show(){
        $pid = I('get.pid');
        $id = M('company')->field('id')->where(array('mid'=>$pid))->select();
        foreach ($id as $k => $v) {
            
            $zid = M('company')->field('id')->where(array('pid'=>$v['id']))->select();
            foreach ($zid as $key => $value) {
                $id[] = $value;
            }
        }
        //dump($id);exit;
        $this->ajaxReturn($id);exit;
    }
    //添加权限
    public function add($act = null){
        if ($act == 'order') {
            $id = I('post.id', 0, 'intval');
            if (!$id) {
                die('0');
            }
            $o = I('post.o', 0, 'intval');
            M('company')->data(array('o' => $o))->where(array('id'=>$id))->save();
            //addlog('分类修改排序，ID：' . $id);
            die('1');
        }elseif ($act == 'email') {
            $id = I('post.id', 0, 'intval');
            if (!$id) {
                die('0');
            }
            $o = I('post.o','',FILTER_VALIDATE_EMAIL);
            M('company')->data(array('email' => $o))->where(array('id'=>$id))->save();
            die('1');
        }elseif ($act == 'bm') {
            $id = I('post.id', 0, 'intval');
            if (!$id) {
                die('0');
            }
            $o = I('post.o');
            M('company')->data(array('bm' => $o))->where(array('id'=>$id))->save();
            die('1');
        }
        $pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0;
        $option = M('company')->order('o ASC')->field('id,pid,cname')->select();
        $tree = new Tree($option);
        $str = "<option value=\$id \$selected>\$spacer\$cname</option>"; //生成的形式
        $category = $tree->get_tree(0, $str, $pid);//dump($category);exit;
        //$option = $this->getMenu($option);
        $this->assign('option', $category);
        $this->display();
    }
    //更新
    public function update(){
      
        $id = I('post.id', '', 'intval');
        $data['pid'] = I('post.pid', '', 'intval');
        $data['cname'] = I('post.cname', '', 'strip_tags');
        $data['ename'] = I('post.ename', '', 'strip_tags');
        $data['email'] = I('post.email');
        $data['address'] = I('post.address');
        $data['phone'] = I('post.phone');
        $data['islink'] = I('post.islink', '', 'intval');
        $data['status'] = 1;
        $data['o'] = I('post.o', '', 'intval');
        $data['tips'] = I('post.tips');
        $data['time'] = time(); 
        $data['mid']= M('company')->where(array('id'=>$data['pid']))->getField('mid');
               
        if ($id) {
            M('company')->data($data)->where("id='{$id}'")->save();
            //addlog('编辑菜单，ID：' . $id);
        } else {
            
            M('company')->data($data)->add();
            //addlog('新增菜单，名称：' . $data['title']);
        }

        $this->success('操作成功！','index');
    }
    //修改
    public function edit($id = 0){
        $id = intval($id);
        $m = M('company');
        $currentmenu = $m->where("id='$id'")->find();
        if (!$currentmenu) {
            $this->error('参数错误！');
        }

        $option = $m->order('o ASC')->select();
        $tree = new Tree($option);
        $str = "<option value=\$id \$selected>\$spacer\$cname</option>"; //生成的形式
        $option = $tree->get_tree(0, $str, $currentmenu['pid']);
        //$option = $this->getMenu($option);
        $this->assign('option', $option);
        $this->assign('currentmenu', $currentmenu);
        $this->display('add');
    }
}