<?php
class UserAction extends CommonAction {

    private $create_fields = array('account','password', 'mobile','reg_time');

    public function lists(){
        $qrcode = D('Users');
        import('ORG.Util.Pageam'); // 导入分页类


        if($_REQUEST['account']){
            $map['account']=htmlspecialchars($_REQUEST['account']);
            $this->assign('account', $_REQUEST['account']); // 赋值数据
        }
        if($_REQUEST['user_id']){
            $map['user_id']=(int)$_REQUEST['user_id'];
            $this->assign('user_id', $_REQUEST['user_id']); // 赋值数据
        }
        if($_REQUEST['closed']){
            $map['user_id']=(int)$_REQUEST['closed'];
            $this->assign('closed', $_REQUEST['closed']); // 赋值数据
        }
        /*$map['creattime'] =array('between',array($todaytime,$today_end));*/

        $count = $qrcode->where($map)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $qrcode->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach ($list as $k => &$val) {
            $val['yue']=D('Users')->getUserMoney($val['user_id'])/100;
            $sumsumoney=D('Payord')->where(array('qrcodeuid'=>$val['user_id'],'sta'=>1))->field('sum(money) as money')->select();
            $val['edudown']=$sumsumoney[0]['money']/100;
            $sucount=D('Payord')->where(array('qrcodeuid'=>$val['user_id'],'sta'=>1))->count();
            $allcount=D('Payord')->where(array('qrcodeuid'=>$val['user_id']))->count();
            $val['percent']=(int)($sucount/$allcount*10000)/100;
        }
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display();
    }

    public function qrcodelists(){
        $user_id=(int)$_REQUEST['user_id'];
        $qrcode = D('Qrcode');
        import('ORG.Util.Pageam'); // 导入分页类
        $map =
        $map = array('is_active' => 1,'user_id'=>$user_id);

        $count = $qrcode->where($map)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $qrcode->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach ($list as $k => &$val) {

            $val['connect']=D('Qrcode')->isQrcodeOnline($val['id']);
            $val['queue']=D('Qrcode')->isQrcodeIn($val['id']);
            $val['creatime']=date('Y-m-d h:i:s',$val['creatime']);
        }
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display();
    }

    public function edit(){

    }


    public function addscore(){
        $user_id=$_REQUEST['user_id'];
        if($this->isPost()){
            $score=(int)$_REQUEST['score']*100;
            if($score>0){
                if(D('Users')->addscore($user_id,$score,'系统上分')){
                    $this->baoSuccess('添加成功', U('user/lists'));
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

        if($this->isPost()){
            $score=(int)$_REQUEST['score']*100;
            if($score>0){
                if(D('Users')->getUserMoney($user_id)<$score){
                    $this->baoError('用户余额不足！');
                }
                if(D('Users')->downscore($user_id,$score,'下级下分')){
                    $this->baoSuccess('添加成功', U('user/lists'));
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
    public function unfrozenorder(){
        $orderid=$_REQUEST['orderid'];

        $orderInfo=D('Payord')->where('id='.$orderid)->find();
        if(empty($orderInfo)){
            $this->baoError('订单不存在！');
        }
        if($orderInfo['frozen']!=1||$orderInfo['remark']==1||$orderInfo['sta']==1){
            $this->baoError('当前状态无法解冻！');
        }
        if($orderInfo['creattime']>time()-300){
            $this->baoError('订单还未超时！');
        }
        if(D('Users')->unfrozen($orderInfo['tradeNo'],'官方解冻订单')){

            $data['forzen']=0;
            $data['remark']=1;
            D('Payord')->where('id='.$orderid)->save($data);
            $this->baoSuccess('订单解冻成功！',U('daili/tongji'));
        }
    }
    public function closed(){

    }
    public function create() {
        if ($this->isPost()) {
            $data = $this->createCheck();
            $obj = D('Users');
            if ($obj->add($data)) {
                $this->baoSuccess('添加成功', U('user/lists'));
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
    //
    public function jifen(){
        $User = D('AccountLog');
        import('ORG.Util.Pageam'); // 导入分页类
        $map = array();

        if ($this->isPost()) {
            $sta=$_POST['bg_date'];
            $end=$_POST['end_date'];
            $todaytime=strtotime($sta);
            $today_end=strtotime($end);
        }else{
            $sta=$_GET['bg_date'];
            $end=$_GET['end_date'];
            $todaytime=strtotime($sta);
            $today_end=strtotime($end);
            if(empty($todaytime)){
                $todaytime=mktime(0,0,0,date('m'),date('d'),date('Y'));
            }
            if(empty($today_end)){
                $today_end=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
            }
        }
        if($_REQUEST['user_id']){
            $map['user_id']=(int)$_REQUEST['user_id'];
            $this->assign('user_id', $map['user_id']);
        }


        if($_REQUEST['orderid']){
            $map['order_id']=(int)$_REQUEST['orderid'];
            $this->assign('orderid', $map['orderid']); // 赋值数据集
        }
        $map['creattime'] =array('between',array($todaytime,$today_end));

        $count = $User->where($map)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $User->where($map)->order(array('id'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k=>$val){
            $val['score'] =$val['score'] /100;
            $val['creatime']=date("Y-m-d H:i:s",$val['creatime']);
            $list[$k] = $val;
        }
        // print_r($list);
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('todaytime', $todaytime); // 赋值数据
        $this->assign('today_end', $today_end); // 赋值数据
        $this->display(); // 输出模板
    }
}

