<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2019/5/22
 * Time: 上午10:30
 */
class TeamAction extends CommonAction
{

    private $create_fields = array('account','password', 'mobile','reg_time');
    public function index(){
        $users = D('Users');
        import('ORG.Util.Pageam'); // 导入分页类
        $map = array('closed' => 0,'pid'=>$this->_users['user_id']);
        //print_r($this->_users);

        $count = $users->where($map)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $users->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach ($list as $k => &$val) {
            $val['yue']=D('Users')->getUserMoney($val['user_id'])/100;
            $sucount=D('Payord')->where(array('qrcodeuid'=>$val['user_id'],'sta'=>1))->count();
            $allcount=D('Payord')->where(array('qrcodeuid'=>$val['user_id']))->count();
            $val['percent']=(int)($sucount/$allcount*10000)/100;
            $sumsumoney=D('Payord')->where(array('qrcodeuid'=>$val['user_id'],'sta'=>1))->field('sum(money) as money')->select();
            $val['edudown']=$sumsumoney[0]['money']/100;
        }
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display();
    }
    public function create() {
        if ($this->isPost()) {
            $data = $this->createCheck();
            $obj = D('Users');
            if ($obj->add($data)) {
                $this->baoSuccess('添加成功', U('team/index'));
            }
            $this->baoError('操作失败！');
        } else {
            //$this->assign('roles', D('Role')->fetchAll());
            $this->display();
        }
    }
    private function createCheck() {
        $data = $this->checkFields($this->_post('data', false), $this->create_fields);
        $data['account'] = htmlspecialchars($data['mobile']);
        $data['mobile'] = htmlspecialchars($data['mobile']);
        $data['password'] = md5($data['password']);
        $data['pid']=$this->_users['user_id'];
        if (empty($data['account'])) {
            $this->baoError('账号不能为空');
        }
        if (empty($data['mobile'])) {
            $this->baoError('手机不能为空');
        }
        if (!isMobile($data['mobile'])) {
            $this->baoError('手机格式不正确');
        }
        $data['reg_time'] = time();
        return $data;
    }
    public function closeuser(){

    }
    public function addscore(){
        $user_id=$_REQUEST['user_id'];
        $myteam=D('Users')->where('pid='.$this->_users['user_id'])->field('user_id')->select();
        $instatus=false;
        foreach ($myteam as $v){
            if($v['user_id']==$user_id){
                $instatus=true;
            }
        }
        if(!$instatus){
            $this->baoError('没有权限！');
        }
        if($this->isPost()){
            $score=(int)$_REQUEST['score']*100;
            if($score>0){
                if(D('Users')->getUserMoney($this->_users['user_id'])<$score){
                    $this->baoError('你的余额不足！');
                }
                if(D('Users')->addscore($user_id,$score,'下级上分')){
                    D('Users')->downscore($this->_users['user_id'],$score,'下级上分扣除');
                    $this->baoSuccess('添加成功', U('team/index'));
                }else{
                    $this->baoError('操作失败');
                }
            }else{
                $this->baoError('分数必须大于零！');
            }

        }else{
            $this->assign('user_id',$user_id);
            $this->display();
        }
    }
    public function reducescore(){
        $user_id=$_REQUEST['user_id'];
        $myteam=D('Users')->where('pid='.$this->_users['user_id'])->field('user_id')->select();
        $instatus=false;
        foreach ($myteam as $v){
            if($v['user_id']==$user_id){
                $instatus=true;
            }
        }
        if(!$instatus){
            $this->baoError('没有权限！');
        }
        if($this->isPost()){
            $score=(int)$_REQUEST['score']*100;
            if($score>0){
                if(D('Users')->getUserMoney($user_id)<$score){
                    $this->baoError('下级余额不足！');
                }
                if(D('Users')->downscore($user_id,$score,'下级下分')){
                    D('Users')->addscore($this->_users['user_id'],$score,'下级下分增加');
                    $this->baoSuccess('添加成功', U('team/index'));
                }else{
                    $this->baoError('操作失败');
                }
            }else{
                $this->baoError('分数必须大于零！');
            }

        }else{
            $this->assign('user_id',$user_id);
            $this->display();
        }
    }

    public function editpsw() {
        $user_id=$this->_users['user_id'];

        $obj=D("Users");

        if ($this->isPost()) {

            $postdata =$this->_post('data', false);
            $data = $this->editCheckLoginpsd();



            $map['user_id'] = $user_id;
            $loginpassword=$postdata['loginpassword'];
            //判断原密码是否正确

            $list = $obj->where($map)->select();
            // print_r($list);
            if ($list[0]['password']!=md5($loginpassword)){
                $this->baoError('原密码输入不正确');
            }


            if ( $data['newloginpsd']!=$data['resloginpsd']){
                $this->baoError('新密码两次输入不一致');
            }
            // $map['brandid'] = $brandid;
            $datas['password'] = md5($data['newloginpsd']);

            if (false !==$obj->where($map)->save($datas)) {
                // Cac()->delete('branid'.$branid);
                $this->baoSuccess('操作成功', U('brand/loginpsd'));
            }
            $this->baoError('操作失败');
        } else {
            $this->display();
        }
    }
    private function editCheckLoginpsd() {
        $data = $this->checkFields($this->_post('data', false), $this->login_fields);
        $data['loginpassword'] = htmlspecialchars($data['loginpassword']);
        if (empty($data['loginpassword'])) {
            $this->baoError('原密码不能为空');
        }

        $data['newloginpsd'] = htmlspecialchars($data['newloginpsd']);
        if (empty($data['newloginpsd'])) {
            $this->baoError('新密码不能为空');
        }
        $data['resloginpsd'] = htmlspecialchars($data['resloginpsd']);
        if (empty($data['resloginpsd'])) {
            $this->baoError('请填写重复新密码');
        }
        return $data;
    }
}