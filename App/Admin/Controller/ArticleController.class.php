<?php
/**
 *
 * 版权所有：小风博客<www.hotxf.com>
 * 作    者：XiaoFeng<admin@hotxf.com>
 * 日    期：2017-09-14 10:11:40
 * 版    本：1.0.0
 * 功能说明：文章控制器。
 *
 **/

namespace Admin\Controller;

use Vendor\Tree;

class ArticleController extends ComController{

    public function add(){

        $category = M('category')->field('id,pid,name')->order('o asc')->where(array('show'=>0))->select();
        $tree = new Tree($category);
        $str = "<option value=\$id \$selected>\$spacer\$name</option>"; //生成的形式
        $category = $tree->get_tree(0, $str, 0);
        $this->assign('category', $category);//导航
        $this->display('form');
    }

    public function index($sid = 0, $p = 1){


        $p = intval($p) > 0 ? $p : 1;

        $article = M('article');
        $pagesize = 16;#每页数量
        $offset = $pagesize * ($p - 1);//计算记录偏移量
        $prefix = C('DB_PREFIX');
        $sid = isset($_GET['sid']) ? $_GET['sid'] : '';
        $keyword = isset($_GET['keyword']) ? htmlentities($_GET['keyword']) : '';
        $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
        $where = '1 = 1 ';
        if ($sid) {
            $sids_array = category_get_sons($sid);
            $sids = implode(',',$sids_array);
            $where .= "and {$prefix}article.cid in ($sids) ";
        }
        if ($keyword) {
            $where .= "and {$prefix}article.title like '%{$keyword}%' ";
        }
        //默认按照时间降序
        $orderby = "time desc";
        if ($order == "asc") {

            $orderby = "time asc";
        }
        //获取栏目分类
        $category = M('category')->field('id,pid,name')->order('o asc')->select();
        $tree = new Tree($category);
        $str = "<option value=\$id \$selected>\$spacer\$name</option>"; //生成的形式
        $category = $tree->get_tree(0, $str, $sid);
        $this->assign('category', $category);//导航


        $count = $article->where($where)->count();
        $list = $article->field("{$prefix}article.*,{$prefix}category.name,{$prefix}category.pid")->where($where)->order($orderby)->join("{$prefix}category ON {$prefix}category.id = {$prefix}article.cid")->limit($offset . ',' . $pagesize)->select();
        foreach ($list as $key => $value) {
            $list[$key]['s_name'] = M('category')->where(array('id'=>$value['pid']))->getField('name').'-';
        }
        //dump($list);exit;
        $page = new \Think\Page($count,16);
        //对分页样式进行定制
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('first', '首页');
        $page->setConfig('last', '尾页');
        $page->setConfig('theme', "<ul class='pagination'><li>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%</li></ul>");
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }
    //删除文章
    public function del(){

        $aids = isset($_REQUEST['aids']) ? $_REQUEST['aids'] : false;
        if ($aids) {
            if (is_array($aids)) {
                $aids = implode(',', $aids);
                $map['aid'] = array('in', $aids);
            } else {
                $map = 'aid=' . $aids;
            }
            if (M('article')->where($map)->data(array('is_delete'=>1))->save()) {
                addlog('删除文章，AID：' . $aids);
                $this->success('恭喜，文章删除成功！');
            } else {
                $this->error('参数错误！');
            }
        } else {
            $this->error('参数错误！');
        }

    }
    //编辑文章
    public function edit($aid){

        $aid = intval($aid);
        //$article = M('article')->where('aid=' . $aid)->find();
        $article = D('Article')->getDataByAid($aid);
        if ($article) {

            $category = M('category')->field('id,pid,name')->order('o asc')->where(array('show'=>0))->select();
            $tree = new Tree($category);
            $str = "<option value=\$id \$selected>\$spacer\$name</option>"; //生成的形式
            $category = $tree->get_tree(0, $str, $article['cid']);
            $this->assign('category', $category);//导航

            $this->assign('article', $article);
        } else {
            $this->error('参数错误！');
        }
        $this->display('form');
    }
    //修改文章
    public function update($aid = 0){

        $aid = intval($aid);
        $data['cid'] = isset($_POST['cid']) ? intval($_POST['cid']) : 0;
        $data['title'] = isset($_POST['title']) ? $_POST['title'] : false;
        $data['title_en'] = isset($_POST['title_en']) ? $_POST['title_en'] : false;
        $data['is_show'] = isset($_POST['is_show']) ? intval($_POST['is_show']) : 0;
        $data['is_top'] = isset($_POST['is_top']) ? intval($_POST['is_top']) : 0;
        $data['is_s'] = isset($_POST['is_s']) ? intval($_POST['is_s']) : 0;
        $data['keywords'] = I('post.keywords', '', 'strip_tags');
        $data['description'] = I('post.description', '', 'strip_tags');
        $data['content'] = isset($_POST['content']) ? $_POST['content'] : false;
        $data['thumbnail'] = I('post.thumbnail', '', 'strip_tags');
        
        if (!$data['cid'] or !$data['title'] or !$data['content']) {
            $this->error('警告！文章分类、文章标题及文章内容为必填项目。');
        }
        if ($aid) {
            // 反转义为下文的 preg_replace使用
            $data['content']=htmlspecialchars_decode($data['content']);
            // 修改图片默认的title和alt
            $data['content']=preg_replace('/title=\"(?<=").*?(?=")\"/','title="MAXIM_EIP"',$data['content']);
            $data['content']=preg_replace('/alt=\"(?<=").*?(?=")\"/','alt="MAXIM_EIP"  class="zoom" onclick="zoom(this, this.getAttribute(&#39;zoomfile&#39;)||this.src, 0, 0, 1)"',$data['content']);
            $data['content']=htmlspecialchars($data['content']);
            // 将绝对路径转换为相对路径
        $data['content']=preg_replace('/src=\"^\/.*\/Upload\/image\/ueditor$/','src="/Upload/image/ueditor',$data['content']);
            D('article')->data($data)->where('aid=' . $aid)->save();
            addlog('编辑文章，AID：' . $aid);
            $this->success('恭喜！文章编辑成功！','index');
        } else {
            $data['time'] = time();
            $aid=D('Article')->addData($data);
            if ($aid) {
                addlog('新增文章，AID：' . $aid);
                $this->success('恭喜！文章新增成功！','index');
            } else {
                $this->error('抱歉，未知错误！');
            }

        }
    }
    //设置文章是否显示
    public function astop(){
        if (!IS_AJAX) {
            $this->redirect('index');
            return;
        }
            //var_dump($_GET);exit;
            $User = M("article"); // 实例化config
            $vo = I('get.v');
            $name = I('get.name');
            $id = I('get.id',0);
            //$this->ajaxReturn($vo);exit;
        if($vo == 0){
            //要修改is_show值,0正常,1不显示
            //$data['is_show'] = 0;
            $User->where(array('aid'=>$id))->save(array($name=>0));
        }elseif($vo == 1){
            //$data['is_show'] = 1;
            $User->where(array('aid'=>$id))->save(array($name=>1)); // 根据条件更新记录        
        }
            
    }
    //用户发布文章管理
    public function userlist($sid = 0, $p = 1){
        //集团公告在分类里的ID是31,所有20转为31
        $mid = session('user.mid')==20?31:session('user.mid');
        $p = intval($p) > 0 ? $p : 1;
        $article = M('article');
        $pagesize = 16;#每页数量
        $offset = $pagesize * ($p - 1);//计算记录偏移量
        $prefix = C('DB_PREFIX');
        $sid = isset($_GET['sid']) ? $_GET['sid'] : '';
        $keyword = isset($_GET['keyword']) ? htmlentities($_GET['keyword']) : '';
        $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
        $where = "{$prefix}article.cid={$mid} and is_delete=0";
        if ($sid) {
            $sids_array = category_get_sons($sid);
            $sids = implode(',',$sids_array);
            $where .= " and {$prefix}article.cid in ($sids)";
        }
        if ($keyword) {
            $where .= " and {$prefix}article.title like '%{$keyword}%'";
        }//echo $where;exit;
        //默认按照时间降序
        $orderby = "time desc";
        if ($order == "asc") {

            $orderby = "time asc";
        }
        //获取栏目分类
        $category = M('category')->field('id,pid,name,ename')->order('o asc')->where(array('id'=>$mid))->select();
       
        $this->assign('category', $category);


        $count = $article->where($where)->count();
        $list = $article->field("{$prefix}article.*,{$prefix}category.name,{$prefix}category.ename")->where($where)->order($orderby)->join("{$prefix}category ON {$prefix}category.id = {$prefix}article.cid")->limit($offset . ',' . $pagesize)->select();

        $page = new \Think\Page($count, $pagesize);
        //对分页样式进行定制
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('first', '首页');
        $page->setConfig('last', '尾页');
        $page->setConfig('theme', "<ul class='pagination'><li>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%</li></ul>");
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        //判断用户语言
        if(session('user.lang')==1){
             $this->display('userlist.en');exit;
        }else{
             $this->display();exit;
        }        
    }
    //新增文章
    public function useradd(){
        $mid = session('user.mid')==20?31:session('user.mid');
        $category = M('category')->field('id,pid,name,ename')->order('o asc')->where(array('show'=>0,'id'=>$mid))->select();
        
        $this->assign('category', $category);
        //$this->display('useredit');
        //判断用户语言
        if(session('user.lang')==1){
             $this->display('useredit.en');exit;
        }else{
             $this->display('useredit');exit;
        }
    }
    //用户修改文章 
    public function useredit($aid){
        $mid = session('user.mid');
        if (!IS_POST) {
                $aid = intval($aid);
                //$article = M('article')->where('aid=' . $aid)->find();
                $article = D('Article')->getDataByAid($aid);
                if ($article) {

                    $category = M('category')->field('id,pid,name,ename')->order('o asc')->where(array('show'=>0,'id'=>$mid))->select();
                    
                    $this->assign('category', $category);

                    $this->assign('article', $article);
                } else {
                    $this->error('参数错误！');
                }
                if(session('user.lang')==1){
                     $this->display('useredit.en');exit;
                }else{
                     $this->display('useredit');exit;
                }
            }else{
            //POST提交修改数据
                $aid = intval($aid);
                $data['cid'] = isset($_POST['cid']) ? intval($_POST['cid']) : 0;
                if($data['cid'] != $mid){
                    $this->error('抱歉，分类错误,是否有权限！');
                }
                $data['title'] = isset($_POST['title']) ? $_POST['title'] : false;
                $data['title_en'] = isset($_POST['title_en']) ? $_POST['title_en'] : false;
                $data['is_show'] = isset($_POST['is_show']) ? intval($_POST['is_show']) : 0;
                $data['is_top'] = isset($_POST['is_top']) ? intval($_POST['is_top']) : 0;
                $data['is_s'] = isset($_POST['is_s']) ? intval($_POST['is_s']) : 0;
                $data['keywords'] = I('post.keywords', '', 'strip_tags');
                $data['description'] = I('post.description', '', 'strip_tags');
                $data['content'] = isset($_POST['content']) ? $_POST['content'] : false;
                $data['thumbnail'] = I('post.thumbnail', '', 'strip_tags');                
                if (!$data['cid'] or !$data['title'] or !$data['content']) {
                    $this->error('警告！文章分类、文章标题及文章内容为必填项目。');
                }
                // 反转义为下文的 preg_replace使用
                $data['content']=htmlspecialchars_decode($data['content']);
                // 修改图片默认的title和alt
                $data['content']=preg_replace('/title=\"(?<=").*?(?=")\"/','title="MAXIM_EIP"',$data['content']);
                $data['content']=preg_replace('/alt=\"(?<=").*?(?=")\"/','alt="MAXIM_EIP"  class="zoom" onclick="zoom(this, this.getAttribute(&#39;zoomfile&#39;)||this.src, 0, 0, 1)"',$data['content']);
                $data['content']=htmlspecialchars($data['content']);
                // 将绝对路径转换为相对路径
                $data['content']=preg_replace('/src=\"^\/.*\/Upload\/image\/ueditor$/','src="/Upload/image/ueditor',$data['content']);
                //dump($data['content']);exit;
                if ($aid) {
                    D('article')->data($data)->where('aid=' . $aid)->save();
                    addlog('编辑文章，AID：' . $aid);
                    $this->success('恭喜！文章编辑成功！','userlist');
                } else {
                    $data['time'] = time();
                    $data['uid']=session('user.id');
                    $data['is_show'] = isset($_POST['is_show']) ? intval($_POST['is_show']) : 0;
                    //dump($data);exit;
                    $aid=D('Article')->data($data)->where('aid=' . $aid)->add();
                    if ($aid) {
                        addlog('新增文章，AID：' . $aid);
                        $this->success('恭喜！文章新增成功！','userlist');
                    } else {
                        $this->error('抱歉，未知错误！');
                    }

                }

            }
        
    }
    //用户删除文章
    public function userdel(){
        $mid = session('user.mid');
        $aids = isset($_REQUEST['aids']) ? $_REQUEST['aids'] : false;
        if ($aids) {
            if (is_array($aids)) {
                $aids = implode(',', $aids);
                $map['aid'] = array('in', $aids);
            } else {
                $map = 'aid=' . $aids;
            }
            $map = $map." and cid = $mid";
            if (M('article')->where($map)->data(array('is_delete'=>1))->save()) {
                addlog('删除文章，AID：' . $aids);
                $this->success('恭喜，文章删除成功！');
            } else {
                $this->error('参数错误！');
            }
        } else {
            $this->error('参数错误！');
        }

    }
    //待审核的
    //用户发布文章管理
    public function usershow($sid = 0, $p = 1){

        $mid = session('user.mid');
        $p = intval($p) > 0 ? $p : 1;
        $article = M('article');
        $pagesize = 16;#每页数量
        $offset = $pagesize * ($p - 1);//计算记录偏移量
        $prefix = C('DB_PREFIX');
        $sid = isset($_GET['sid']) ? $_GET['sid'] : '';
        $keyword = isset($_GET['keyword']) ? htmlentities($_GET['keyword']) : '';
        $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
        $where = "{$prefix}article.is_show= 0";
        if ($sid) {
            $sids_array = category_get_sons($sid);
            $sids = implode(',',$sids_array);
            $where .= " and {$prefix}article.cid in ($sids)";
        }
        if ($keyword) {
            $where .= " and {$prefix}article.title like '%{$keyword}%'";
        }//echo $where;exit;
        //默认按照时间降序
        $orderby = "time desc";
        if ($order == "asc") {

            $orderby = "time asc";
        }
        //获取栏目分类
        //$category = M('category')->field('id,pid,name,ename')->order('o asc')->where(array('id'=>$mid))->select();
        $category = M('category')->field('id,pid,name')->order('o asc')->where(array('show'=>0))->select();
            $tree = new Tree($category);
            $str = "<option value=\$id \$selected>\$spacer\$name</option>"; //生成的形式
            $category = $tree->get_tree(0, $str, $sid);
        $this->assign('category', $category);


        $count = $article->where($where)->count();
        $list = $article->field("{$prefix}article.*,{$prefix}category.name")->where($where)->order($orderby)->join("{$prefix}category ON {$prefix}category.id = {$prefix}article.cid")->limit($offset . ',' . $pagesize)->select();

        $page = new \Think\Page($count, $pagesize);
        //对分页样式进行定制
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('first', '首页');
        $page->setConfig('last', '尾页');
        $page->setConfig('theme', "<ul class='pagination'><li>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%</li></ul>");
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }
    //审核文章
    public function usershows(){
        
        $aids = isset($_REQUEST['aids']) ? $_REQUEST['aids'] : false;
        if ($aids) {
            
            $data = array('is_show'=>1);
            if (M('article')->where(array('aid'=>$aids))->data($data)->save()) {
                addlog('删除文章，AID：' . $aids);
                $this->success('恭喜，通过审核！');
            } else {
                $this->error('参数错误！');
            }
        } else {
            $this->error('参数错误！');
        }

    }
}