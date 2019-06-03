<?php


class OrderAction extends CommonAction
{
    public function index(){
        $user_id=$this->users['user_id'];
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
        $map['qrcodeuid']=$user_id;
        if($_REQUEST['orderid']){
            $map['tradeNo']=(int)$_REQUEST['orderid'];
            $this->assign('orderid', $map['tradeNo']); // 赋值数据集
        }else{
            $map['creattime'] =array('between',array($todaytime,$today_end));
        }




        /*if($_POST['exportExcel']=="yes"){

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
        }*/

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

    //
    public function lists(){
        $User = D('AccountLog');
        import('ORG.Util.Pageam'); // 导入分页类
        $map = array();
        $user_id=$this->users['user_id'];
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

        $map['user_id']=$user_id;

        if($_REQUEST['orderid']){

            $map['order_id']=(int)$_REQUEST['orderid'];
            $this->assign('orderid', $map['order_id']); // 赋值数据集
        }else{
            $map['creattime'] =array('between',array($todaytime,$today_end));
        }


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