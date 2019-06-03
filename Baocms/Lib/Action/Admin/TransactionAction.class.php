<?php
class TransactionAction extends CommonAction {
    private $create_fields = array('account', 'password','rank_id', 'face','mobile','email','nickname','face','ext0');
    private $edit_fields = array('account', 'password','rank_id','face', 'mobile','email','nickname','face','ext0');
    private $notice_fields = array('title', 'content');
    //推广金奖励
//获取分销信息数据
//获取分销级
//匹配字符串，获取分销数额
    private $numLevel;
    private $globalEdu;//会员充值额度;
    private $priceAry=array();
    private $vipAry=array();
    private $priceString;
    private $current_userid;
    private $len;
    private $pidAry=array();
    private $gametype;
    public function fzmoney(){
        $EX = D('Usersex');
        import('ORG.Util.Page'); // 导入分页类
        $map = array('frozen_money'=>array('GT',0));
        if($is_no_frozen = (int)$this->_param('is_no_frozen')){
            if($is_no_frozen == 1){
                $map['is_no_frozen'] = 1;
            }else{
                $map['is_no_frozen'] = 0;
            }
            $this->assign('is_no_frozen',$is_no_frozen);
        }
        if($is_tui_money = (int)$this->_param('is_tui_money')){
            if($is_tui_money == 1){
                $map['is_tui_money'] = 1;
            }else{
                $map['is_tui_money'] = 0;
            }
            $this->assign('is_tui_money',$is_tui_money);
        }
        $count = $EX->where($map)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $EX->where($map)->order(array('user_id'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $user_ids = $invites_id = array();
        foreach($list as $k=>$val){
            $user_ids[$val['user_id']] = $val['user_id'];
        }
        $users = D('Users')->itemsByIds($user_ids);
        foreach($users as $v){
            if(!empty($v['invite1']))$invites_id[$v['invite1']] = $v['invite1'];
            if(!empty($v['invite2']))$invites_id[$v['invite2']] = $v['invite2'];
            if(!empty($v['invite3']))$invites_id[$v['invite3']] = $v['invite3'];
            if(!empty($v['invite4']))$invites_id[$v['invite4']] = $v['invite4'];
            if(!empty($v['invite5']))$invites_id[$v['invite5']] = $v['invite5'];
            if(!empty($v['invite6']))$invites_id[$v['invite6']] = $v['invite6'];
        }
        $inviteUsers = D('Users')->itemsByIds($invites_id);
        $inviteUsersex = $EX -> itemsByIds($invites_id);
        $this->assign('inviteUsers',$inviteUsers);
        $this->assign('inviteUsersex',$inviteUsersex);
        $this->assign('users',$users);
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }





    public function success() {
        $brandid=$_SESSION['brandid'];

        if ($this->isPost()) {
            $sta=$_POST['bg_date'];
            $end=$_POST['end_date'];
            $todaytime=strtotime($sta);
            $today_end=strtotime($end);
        }else{

            if($bg_date= $this->_param('bg_date','htmlspecialchars')){
                $todaytime =strtotime($bg_date);

            }else{
                $todaytime=mktime(0,0,0,date('m'),date('d'),date('Y'));

            }

            if($end_date= $this->_param('end_date','htmlspecialchars')){
                $today_end =strtotime($end_date);

            }else{
                $today_end=mktime(0,0,0,date('m'),date('d')+1,date('Y'));
            }
            $map['creattime'] = array('between', array($todaytime, $today_end));
        }


        $User = D('Payord');
        import('ORG.Util.Page'); // 导入分页类
        $map = array();
        $map['brandid']=$brandid;
        $map['sta']=1;

        if($ordid = $this->_param('ordid','htmlspecialchars')){
            $map['orderNo'] =$ordid;
        }else{
            $map['creattime'] =array('between',array($todaytime,$today_end));
        }

        $count = $User->where($map)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 15); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $User->where($map)->order(array('id'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k=>$val){
            $val['pay_money'] =$val['pay_money'] /100;
            $val['payAmt'] =$val['payAmt'] /100;
            $val['money'] =$val['money'] /100;
            $val['rate'] =$val['rate'] /100;
            if ($val['ifsuccess'] == 1){
                $val['ifsuccess']="回调成功";
            }else{
                $val['ifsuccess']="回调失败";
            }


            $list[$k] = $val;
        }

        $pay_money=D("Payord")->where($map)->field('sum(money) as money')->select();
        $counts['money']=$pay_money[0]['money']/100;


        // print_r($list);
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('todaytime', $todaytime); // 赋值数据
        $this->assign('today_end', $today_end); // 赋值数据
        $this->assign('counts',$counts);
        $this->assign('ordid',$ordid);
        $this->display(); // 输出模板

    }

    public function failure() {
        $brandid=$_SESSION['brandid'];

        if ($this->isPost()) {
            $sta=$_POST['bg_date'];
            $end=$_POST['end_date'];
            $todaytime=strtotime($sta);
            $today_end=strtotime($end);
        }else{

            if($bg_date= $this->_param('bg_date','htmlspecialchars')){
                $todaytime =strtotime($bg_date);

            }else{
                $todaytime=mktime(0,0,0,date('m'),date('d'),date('Y'));

            }

            if($end_date= $this->_param('end_date','htmlspecialchars')){
                $today_end =strtotime($end_date);

            }else{
                $today_end=mktime(0,0,0,date('m'),date('d')+1,date('Y'));

            }
            $map['creattime'] = array('between', array($todaytime, $today_end));
        }


        $User = D('Payord');
        import('ORG.Util.Page'); // 导入分页类
        $map = array();
        $map['brandid']=$brandid;
        $map['sta']=0;

        if($ordid = $this->_param('ordid','htmlspecialchars')){
            $map['orderNo'] =$ordid;

        }else{

            $map['creattime'] =array('between',array($todaytime,$today_end));
        }

        $count = $User->where($map)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 15); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $User->where($map)->order(array('id'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k=>$val){
            $val['pay_money'] =$val['pay_money'] /100;
            $val['payAmt'] =$val['payAmt'] /100;
            $val['money'] =$val['money'] /100;
            $val['rate'] =$val['rate'] /100;
            if ($val['ifsuccess'] == 1){
                $val['ifsuccess']="回调成功";
            }else{
                $val['ifsuccess']="回调失败";
            }
            $list[$k] = $val;
        }
        $pay_money=D("Payord")->where($map)->field('sum(pay_money) as pay_money')->select();
        $counts['pay_money']=$pay_money[0]['pay_money']/100;
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('todaytime', $todaytime); // 赋值数据
        $this->assign('today_end', $today_end); // 赋值数据
        $this->assign('ordid',$ordid);
        $this->assign('counts',$counts);
        $this->display(); // 输出模板

    }


    public function lists(){
        $brandid=$this->admin['brandid'];
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
        $User = D('Payord');
        import('ORG.Util.Pageam'); // 导入分页类
        $map = array();

        if($_REQUEST["sta"]=="null"||!isset($_REQUEST["sta"])){
            unset($_REQUEST["sta"]);
        }else{
            $this->assign('sta', $_REQUEST['sta']); // 赋值数据
            $map['sta']=$_REQUEST["sta"];
        }
        $map['brandid']=$brandid;

        $map['creattime'] =array('between',array($todaytime,$today_end));


        if($_POST['exportExcel']=="yes"){

            $exportData = $User->where($map)->select();
            $title=array('商户号','订单号','交易额(元)','实付金额(元)','单笔到账(元)','交易状态','平台订单号','创建订单');
            foreach($exportData as $v){
                $temp=array();
                $temp[]=$v['brandid'];
                $temp[]=$v['orderNo'];
                $temp[]=$v['money'];
                $temp[]=$v['pay_money'];
                $temp[]=$v['payAmt'];
                if($v['sta']==1){
                    $temp[]="已支付";
                }else{
                    $temp[]="未支付";
                }
                $temp[]=$v['tradeNo'];
                $temp[]=date("Y-m-d H:i:s",$v['creattime']);
                $export[]=$temp;
            }
            $this->export($title,$export);
            return;
        }

        $count = $User->where($map)->count(); // 查询满足要求的总记录数
        $map2=$map;
        $map2['sta']=1;
        $allmoney=$User->where($map2)->field('sum(money) as money')->select();
        $allmoney=$allmoney[0]['money']/100;
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $User->where($map)->order(array('id'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k=>$val){
            $val['pay_money'] =$val['pay_money'] /100;
            $val['payAmt'] =$val['payAmt'] /100;
            $val['money'] =$val['money'] /100;
            $val['rate'] =$val['rate'] /10;
            if ($val['notifystatus'] == 101){
                $val['ifsuccess']="1";
            }elseif($val['notifystatus'] == 0){
                $val['ifsuccess']="0";
            }else{
                $val['ifsuccess']=$val['notifystatus'];
            }


            if ($val['sta'] == 1){
                $val['sta']="1";
            }elseif($val['creattime']<(time()-300)){
                $val['sta']="2";
            }else{
                $val['sta']="0";
            }
            if($val['creattime']){
                $val['creattime']=date("Y-m-d H:i:s",$val['creattime']);
            }
            if($val['paidTime']){
                $val['paidTime']=date("Y-m-d H:i:s",$val['paidTime']);
            }
            $list[$k] = $val;
        }
        // print_r($list);
        $this->assign('allmoney', $allmoney); // 赋值数据集
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('todaytime', $todaytime); // 赋值数据
        $this->assign('today_end', $today_end); // 赋值数据

        $this->assign('ranks',D('Userrank')->fetchAll());
        $this->display(); // 输出模板
    }

    private function export($title,$data){
        exportExcel($title, $data, date("Y-m-d H:i:s".time()), './', true);
    }
}

