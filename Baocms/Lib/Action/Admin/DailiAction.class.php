<?php
require_once LIB_PATH.'/GatewayClient/Gateway.php';

use GatewayClient\Gateway;
class DailiAction extends CommonAction
{
    private $edit_fields = array('rate','username','remark');
    public function cotr(){
        $User = D('Admin');
        import('ORG.Util.Page'); // 导入分页类
        // $map['pid']=$_SESSION['admin_id'];
        $map['pid']= $this->admin['admin_id'];

        $count = $User->where($map)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $User->where($map)->order(array('admin_id'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k=>$val){


            $val['rate'] = $val['rate']/100;
            $list[$k] = $val;
        }

        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出

        $this->display(); // 输出模板

    }

    public function edit($daili_id = 0) {
        if ($daili_id = (float) $daili_id) {
            $obj = D('Admin');
            if (!$detail = $obj->find($daili_id)) {
                $this->baoError('请选择要编辑的会员');
            }

            $detail['rate'] = $detail['rate']/100;


            if ($this->isPost()) {
                $data = $this->editCheck();
                $data['admin_id'] = $daili_id;
                if (false !==$obj->save($data)) {
                    // Cac()->delete('userinfo_'.$daili_id);
                    $this->baoSuccess('操作成功', U('daili/cotr'));
                }
                $this->baoError('操作失败');
            } else {
                $this->assign('detail', $detail);

                $this->display();
            }
        } else {
            $this->baoError('请选择要编辑的会员');
        }
    }
    private function editCheck() {
        $data = $this->checkFields($this->_post('data', false), $this->edit_fields);
        $data['rate'] = htmlspecialchars($data['rate']);
        if (empty($data['rate'])) {
            $this->baoError('费率不能为空');
        }else{

            $data['rate']= $data['rate']*100;
        }

        $data['username'] = htmlspecialchars($data['username']);
        if (empty($data['username'])) {
            $this->baoError('登录账号不能为空');
        }

        $data['remark'] = htmlspecialchars($data['remark']);
        if (empty($data['remark'])) {
            $this->baoError('商户备注不能为空');
        }
        return $data;
    }


    public function lookbank($brand_id = 0) {
        $User = D('Brandbank');
        import('ORG.Util.Page'); // 导入分页类
        $map['brandid']=$brand_id;

        $count = $User->where($map)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $User->where($map)->order(array('id'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k=>$val){



            $list[$k] = $val;
        }

        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出

        $this->display(); // 输出模板
    }
    public function moneyinfo($brandid = 0){
        //入款总额  入款成功总额   入款失败总额   提现成功总额   提现失败总额

        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));

        $counts['count_ord']=D('Payord')->where("brandid=$brandid and creattime>".$beginToday)->count();
//
        $counts['count_zong_ord']=D('Payord')->where("brandid=$brandid")->count();
        //
//入款总额
        $pay_zong_money=D("Payord")->where("brandid=$brandid")->field('sum(money) as money')->select();
        $counts['count_zong_money']=$pay_zong_money[0]['money']/100;


        $counts['count_ord_success']=D('Payord')->where("brandid=$brandid and sta=1 and creattime>".$beginToday)->count();
        $counts['count_ord_fail']=D('Payord')->where("brandid=$brandid and sta=0  and creattime>".$beginToday )->count();

        $pay_money=D("Payord")->where("brandid=$brandid and sta=1  and creattime>".$beginToday)->field('sum(pay_money) as pay_money')->select();
        $counts['pay_money']=$pay_money[0]['pay_money']/100;

        $day_rujin=D("Payord")->where("brandid=$brandid and sta=1  and creattime>".$beginToday)->field('sum(money) as money')->select();
        $counts['day_rujin']=$day_rujin[0]['money']/100;

//入款总额
        $rujin=D("Payord")->where("brandid=$brandid and sta=1")->field('sum(money) as money')->select();
        $counts['rujin']=$rujin[0]['money']/100;

        //入款失败总额
        $rujin_shibai=D("Payord")->where("brandid=$brandid and sta=0")->field('sum(money) as money')->select();
        $counts['rujin_shibai']=$rujin_shibai[0]['money']/100;


        $txian=D("Zhangbian")->where("brandid=$brandid and sta<2")->field('sum(money) as money')->select();
        $counts['txian']=$txian[0]['money']/100;
        if(empty($counts['txian'])){
            $counts['txian']=0;

        }


        $txian_zong_success=D("Zhangbian")->where("brandid=$brandid and sta=1")->field('sum(money) as money')->select();
        $counts['txian_zong_success']=$txian_zong_success[0]['money']/100;
        if(empty($counts['txian_zong_success'])){
            $counts['txian_zong_success']=0;

        }


        $txian_zong_shibai=D("Zhangbian")->where("brandid=$brandid and sta=2")->field('sum(money) as money')->select();
        $counts['txian_zong_shibai']=$txian_zong_shibai[0]['money']/100;
        if(empty($counts['txian_zong_shibai'])){
            $counts['txian_zong_shibai']=0;

        }


        $counts['brandid']=$brandid;
        //账号余额- 提现表==账户余额可提现
        $counts['money']=($counts['rujin']-$counts['txian']);
        $this->assign('counts', $counts);

        $this->display();
    }

    public function search(){
        $map = array();
        $User = D('Payord');
        import('ORG.Util.Pageam'); //导入分页类
        $map['pid'] =$this->admin['admin_id'] ;


        if ($this->isPost()) {
            $sta=$_POST['bg_date'];
            $end=$_POST['end_date'];
            $todaytime=strtotime($sta);
            $today_end=strtotime($end);
            if(!empty($_POST['brandid'])||!$_POST['brandid']==0){

                $map['brandid'] =$_POST['brandid'] ;

            }
            if(!empty($_POST['ordid'])|| !$_POST['ordid']==0){
                $map['orderNo'] =$_POST['ordid'] ;
            }else{
                $map['creattime'] = array('between', array($todaytime, $today_end));

            }

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
            //提取日期文本框内容
            //如果为空 则默认今天
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
            if ($val['notifystatus'] == 101){
                $val['ifsuccess']=101;
            }else{
                $val['ifsuccess']=$val['notifystatus'];
            }
            $val['creattime']=date("Y-m-d H:i:s",$val['creattime']);
            $val['paidTime']=date("Y-m-d H:i:s",$val['paidTime']);
            if ($val['sta'] == 1){
                $val['sta']="1";
            }elseif($val['sta'] == 0 && $val['creattime']<time()-300){
                $val['sta']="2";
            }else{
                $val['sta']="0";
            }
            $list[$k] = $val;
        }

        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('todaytime', $todaytime); // 赋值数据
        $this->assign('today_end', $today_end); // 赋值数据
        $this->assign('ordid', $_POST['ordid']); // 赋值数据

        $this->assign('brandid', $_POST['brandid']); // 赋值数据

        $this->display(); // 输出模板

    }
    public function tongji(){

        //$brandid=$this->admin['admin_id'];

        if ($this->isPost()) {
            $sta=$_POST['bg_date'];
            $end=$_POST['end_date'];
            $todaytime=strtotime($sta);
            $today_end=strtotime($end);
            if(!empty($_POST['brandid'])||!$_POST['brandid']==0){
                $map['brandid'] =$_POST['brandid'] ;
            }
            if(!empty($_POST['ordid'])|| !$_POST['ordid']==0){
                $map['orderNo'] =$_POST['ordid'] ;
            }else{
                $map['creattime'] = array('between', array($todaytime, $today_end));

            }

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
            //提取日期文本框内容
            //如果为空 则默认今天
        }



        $User = D('Payord');
        import('ORG.Util.Pageam'); // 导入分页类
        $map = array();
        //$map['pid']=$brandid;
        if($_REQUEST['order_id']){
            $map['tradeNo']=(int)$_REQUEST['order_id'];
            $this->assign('order_id',$map['tradeNo']); // 赋值数据集
        }
        if($_REQUEST['brandid']){
            $map['brandid']=(int)$_REQUEST['brandid'];
            $this->assign('brandid',$map['brandid']); // 赋值数据集
        }
        $map['creattime'] =array('between',array($todaytime,$today_end));

        $count = $User->where($map)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $User->where($map)->order(array('id'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k=>$val){
            $val['pay_money'] =$val['pay_money'] /100;
            $val['payAmt'] =$val['payAmt'] /100;
            $val['money'] =$val['money'] /100;
            $val['rate'] =$val['rate'] /10;
            if ($val['notifystatus'] == 101){
                $val['ifsuccess']=101;
            }else{
                $val['ifsuccess']=$val['notifystatus'];
            }
            $val['creattime']=date("Y-m-d H:i:s",$val['creattime']);
            if($val['paidTime']>0){
                $val['paidTime']=date("Y-m-d H:i:s",$val['paidTime']);
            }

            if ($val['sta'] == 1){
                $val['sta']="1";
            }elseif($val['sta'] == 0 && $val['creattime']<time()-300){
                $val['sta']="2";
            }else{
                $val['sta']="0";
            }
            $list[$k] = $val;
        }
        // print_r($list);
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('todaytime', $todaytime); // 赋值数据
        $this->assign('today_end', $today_end); // 赋值数据
        $this->assign('ranks',D('Userrank')->fetchAll());
        $this->display(); // 输出模板

    }
    public function budan($id=0){
        if($id =(int) $id){
            $obj = D('Payord');
            $orderInfo = $obj->where('id='.$id)->find();

            if($orderInfo['sta']==1){
                $this->baoError('操作失败');
            }

            $data['sta'] = 1;
            $data['paidTime'] = time();
            $data['notifytime']=time();
            $data['notifystatus']=1;
            if($obj->where("id=".$id)->save($data)){
                D('Users')->unfrozen($orderInfo['tradeNo']);
                D('Users')->reducescore($orderInfo['tradeNo']);
                $users=D('Admin');
                $where['brandid']=$orderInfo["brandid"];
                $besinessInfo=$users->where($where)->find();

                $key=$besinessInfo['merId'];
                $notifyData["orderNo"]=$orderInfo['orderNo'];
                $notifyData["brandId"]=$orderInfo['brandid'];
                $notifyData["paidTime"]=$data['notifytime'];
                $notifyData["sign"]=$this->getSignK($notifyData,$key);
                $res=$this->https_request($orderInfo['notifyUrl'],$notifyData);
                if($res=="success"){
                    $data['notifystatus']=101;
                    $obj->where("id=".$id)->save($data);
                    $this->baoSuccess('补单成功',U('daili/tongji'));
                }else{
                    $this->baoSuccess('补单成功,通知失败',U('daili/tongji'));
                }
            }
            $this->baoError('操作失败');
        }else{
            $this->baoError('请选要补单的行');
        }
    }

    private function getSignK($Obj,$key){

        foreach ($Obj as $k => $v)
        {
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String =$this->formatBizQueryParaMap($Parameters, false);
        //echo '【string1】'.$String.'</br>';


        // $this->writeLog($String);
        //签名步骤二：在string后加入KEY
        $String = $String."&accessKey=".$key;
        //echo "【string2】".$String."</br>";

        //echo $String;
        //签名步骤三：MD5加密

        $String = md5($String);

        //echo "【string3】 ".$String."</br>";
        //签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        //echo "【result】 ".$result_."</br>";
        return $result_;
    }
    function formatBizQueryParaMap($paraMap, $urlencode){
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v)
        {
            if($urlencode)
            {
                $v = urlencode($v);
            }
            //$buff .= strtolower($k) . "=" . $v . "&";
            $buff .= $k . "=" . $v . "&";
        }

        if (strlen($buff) > 0)
        {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }
    private function https_request($url,$data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {return 'ERROR '.curl_error($curl);}
        curl_close($curl);
        return $data;
    }

}
