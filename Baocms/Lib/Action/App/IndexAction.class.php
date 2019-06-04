<?php


class IndexAction extends CommonAction
{
    // 平台公钥
    private $publicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA8inx2kXGAvMdn8htPE383uIXNpDGpyI9Qlon3tmBaaJZGXQtdb8QA44a31kl4lE5teXDIjmcO4hCrp1zekmYEjOgr/0hQ5/0ExSSnnblM6VXiMxNInuXSlZEo3QO2RNCwI+7XBL8N+E9ATXbkJoSKfoklOEG1C82znWwltcSkhXD/+Bg3yGvCI0TKthOZ0UTwFW1NpDlIVoz2DkAx7WdYKtQ4n3ls313Om3cyihrE0cUBwPxhBaHf2KmUdVfwaRc8Oz3xuAE6SQg10oWVd3+TedOx9nAkAJEXuyRM/k2k1YUEOqL9woH1HEa4kug1i0MlFprHJoDheyxQTzktVPFaQIDAQAB';

    // 商户私钥
    private $merPriKey = 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDyKfHaRcYC8x2fyG08Tfze4hc2kManIj1CWife2YFpolkZdC11vxADjhrfWSXiUTm15cMiOZw7iEKunXN6SZgSM6Cv/SFDn/QTFJKeduUzpVeIzE0ie5dKVkSjdA7ZE0LAj7tcEvw34T0BNduQmhIp+iSU4QbULzbOdbCW1xKSFcP/4GDfIa8IjRMq2E5nRRPAVbU2kOUhWjPYOQDHtZ1gq1DifeWzfXc6bdzKKGsTRxQHA/GEFod/YqZR1V/BpFzw7PfG4ATpJCDXShZV3f5N507H2cCQAkRe7JEz+TaTVhQQ6ov3CgfUcRriS6DWLQyUWmscmgOF7LFBPOS1U8VpAgMBAAECggEBALotFzx5YheIc6ERRTIUvzFX3wLbYW7DOxeVGowQ6pac8yVHlV+uCZDCItTNw/tv+Q9oWpIoen2mb6WxdUNx6xErUgGbeYR1J/+3VFWyENqY5mhMsYyuOg0zr8d+hZ/MWAiVODU8f14ys2UH1Asi+I3/OzqemlJtDhTClIYTSbq2tCmRnDjqtqI+IdCD1pAZhxc9pyyVebcSA1nhRgVqzDWfrauvM745C8Vxsqq8QXoSHnhGqzQ7oR4/75hw8buEZnhisgpshYYMFkVEkB9jLJlvEQXUh5ekIbmi6vofdCZwQOa0FEnH0paYfdpciuSmf/ALpR6HQLJ1rTyJGTQrO5UCgYEA/vin4dXefVh3Vq5lWtOQlXCDhQCsyZHAqGNJ+bbla+S32IQcqKJpuLORngbeuMMBtxlsdgDnRDMVF84T0Z2SXWFDi9itQoMYSKJ9QyCpDVk/isKpfwfJ7SLXp2hkDPEQBvNavKsWeiWk/y4sPu0YzbvVYfyB10EDe+N3hxqkGS8CgYEA8yQPl1XYzDfFW0PtEJwDBw37/d4ztAKH1fQA1invHMHUNMAhqMtdDRmefI3ksVTPavg5rUe2u4cIVJ28qAkpS07YHibbfEOuYqII46EmBhqyk1Nex4jHxvL5ihoPSQOQt9P9ogl1FqqMnYQDzA3Y7vTVGrbogMQAi+XLZJQ0tOcCgYBxRo9vadDa5z69p+UnoO6PNdl4cYBSXQq2xMXMx1DNymNTMf55fQ5zHHQJPQweEaTlb8ob8vLL/dXVKZzsd5DbI6carjzrW8qiGm6EMDJq18e/IhSjdxZi4CPnIB2zEjYIoH0jbO8jfS38oMrPeg6W+GgojEIzG5MsqzYjM9bLyQKBgEXyq+GXuuUVbluRMRRELcLLzSD92dYBnF568fqq2bVmNcxvMb/DsaNhKW0fzRkPExTtEHq/VNyiPk1Ji8qzyAN4TUhvWVlohzSs9O23oJ7E83ba1zuEKCUeJZgLYzZYJjwcaq1BF0RMEGQIZKdBnJUyLlSfJkhVGuskUt0+neMlAoGAMkYvIkbpreRFVbaAB763LB1R+0VUqOv0/LBDh8AcewSpDdt5PXg5iKOoNwxR87NnWkupGt8ZYxa0WpaXzJe/TdxDY0cjU3lUbsDB0VCCWxRsd4amdSUjHL12YL3ZFTDQ0v76v8DvoAaZvTaPLsCKJDGEE+gsS6NDcUl7whosAVE=';

    private function get_err_msg($code) {
        $err_msg = array(
            '1002' => '支付失败',
            '0000' => '验签失败',
            '0001' => '商户不存在',
            '0002' => '商户未启用',
            '0003' => '必传参数为空',
            '0004' => '产生订单失败',
            '0005' => '订单金额有误',
            '0006' => '订单金额超出支付范围',
            '0007' => '支付类型无效',
            '0017' => '系统异常',
            '0019' => '修改订单支付方式失败',
            '0020' => '交易金额格式错误',
            '0040' => '代理不存在',
            '0041' => '中转手机不存在',
            '0042' => '生成收款码失败',
            '0043' => '未找到商户所属中转手机代理',
            '0044' => '无手机在线',
            '0045' => '未找到商户的支付通道'
        );
        return $err_msg[$code];
    }

    public function index(){

    }

    public function kuaifupay(){

        $datas["brandId"] = $_POST['brandId'];
        $datas["orderNo"] =$_POST['orderNo'];
        $datas["tradeMoney"] = ((int)($_POST['tradeMoney']));
        $datas["payType"] = "alipay";
        $datas["notifyUrl"] = $_POST['notifyUrl'];

        $sign=$_POST['sign'];
        DebugLog(var_export($datas,true)." and sign=".$sign,"kuaifupay");

        //$extraParams=$_POST['extraParams']; //子商户在我们平台的记录  不参与签名
        if(empty($datas["brandId"])){
            $this->ajaxReturn('error40002','商户号不能为空!',0);
        }
        $users=D('Admin');
        $where['brandid']=$datas["brandId"];
        $line_rate=$users->where($where)->find();
        if(empty($line_rate)){
            $this->ajaxReturn('error40003','商户号不存在!',0);
        }
        if($datas["orderNo"]==""||empty($datas["orderNo"])){
            $this->ajaxReturn('error40001','订单号不能是空!',0);
        }
        if($datas["tradeMoney"]<$line_rate['minpay']){
            $this->ajaxReturn('error40004','金额不合法!',0);
        }
        $key=$line_rate['merId'];
        if( $sign!=$this->getSignK($datas,$key)){
            $this->ajaxReturn('error','签名错误!',0);
        }
        //参数过滤完毕 开始生成订单

        //获取一个支付码

        //平台订单
        $paltform_oderid=time().rand(10000,99999);

        $payData=$this->getpaydata($datas,$line_rate,0);

        if(!$payData){
            $this->ajaxReturn('error','可用码不足!',0);
        }

        //10分钟内不领取重复订单


        //组装数据
        $orderData["orderNo"]=$datas["orderNo"];//商家订单
        $orderData["tradeNo"]=$paltform_oderid;//平台订单
        $orderData["pay_money"]=$payData['payMoney'];//实际支付金额
        $orderData["aliaccount"]=$payData['Qrcode']['qrcode'];
        $orderData["qrcode_id"]=$payData['qrcode_id'];
        $orderData["qrcodeuid"]=$payData['Qrcode']['user_id'];
        $orderData["payAmt"]=$payData['payAmt'];
        $orderData["frozen"]=1;
        $orderData["rate"]=$line_rate['rate'];
        $orderData["rate_money"]=$payData['rate_money'];
        $orderData["money"]=$datas["tradeMoney"]*100;
        $orderData["brandid"]=$datas["brandId"];
        $orderData["notifyUrl"]=$datas["notifyUrl"];
        $orderData["sign"]=$sign;
        $orderData["creattime"]=time();
        $orderData["paidTime"]=0;
        $orderData["sta"]=0;

        //开始入库
        DebugLog(var_export($orderData,true),"orderData");

        if(D("Payord")->add($orderData)){
            D('Users')->frozen($paltform_oderid,$orderData);
            $retrunUrl=getSiteUrl()."/pchome/index/showinfo?order=".$paltform_oderid;
            $this->ajaxReturn($retrunUrl,'success',1);
        }else{
            DebugLog("faild message:".D("Payord")->getLastSql(),"orderData");
            $this->ajaxReturn('error','订单创建失败!',0);
        }
    }


    public function youpay(){

        $datas["brandId"] = $_POST['brandId'];
        $datas["orderNo"] =$_POST['orderNo'];
        $datas["tradeMoney"] = ((int)($_POST['tradeMoney']));
        $datas["payType"] = "alipay";
        $datas["notifyUrl"] = $_POST['notifyUrl'];

        $sign=$_POST['sign'];
        DebugLog(var_export($datas,true)." and sign=".$sign,"kuaifupay");

        //$extraParams=$_POST['extraParams']; //子商户在我们平台的记录  不参与签名
        if(empty($datas["brandId"])){
            $this->ajaxReturn('error40002','商户号不能为空!',0);
        }
        $users=D('Admin');
        $where['brandid']=$datas["brandId"];
        $line_rate=$users->where($where)->find();
        if(empty($line_rate)){
            $this->ajaxReturn('error40003','商户号不存在!',0);
        }
        if($datas["orderNo"]==""||empty($datas["orderNo"])){
            $this->ajaxReturn('error40001','订单号不能是空!',0);
        }
        if($datas["tradeMoney"]<$line_rate['minpay']){
            $this->ajaxReturn('error40004','金额不合法!',0);
        }
        $key=$line_rate['merId'];
        if( $sign!=$this->getSignK($datas,$key)){
            $this->ajaxReturn('error','签名错误!',0);
        }
        //参数过滤完毕 开始生成订单

        //获取一个支付码

        //平台订单
        $paltform_oderid=time().rand(10000,99999);

        $payData=$this->getpaydata($datas,$line_rate,0);

        if(!$payData){
            $this->ajaxReturn('error','可用码不足!',0);
        }

        //10分钟内不领取重复订单


        //组装数据
        $orderData["orderNo"]=$datas["orderNo"];//商家订单
        $orderData["tradeNo"]=$paltform_oderid;//平台订单
        $orderData["pay_money"]=$payData['payMoney'];//实际支付金额
        $orderData["aliaccount"]=$payData['Qrcode']['qrcode'];
        $orderData["qrcode_id"]=$payData['qrcode_id'];
        $orderData["qrcodeuid"]=$payData['Qrcode']['user_id'];
        $orderData["payAmt"]=$payData['payAmt'];
        $orderData["frozen"]=1;
        $orderData["rate"]=$line_rate['rate'];
        $orderData["rate_money"]=$payData['rate_money'];
        $orderData["money"]=$datas["tradeMoney"]*100;
        $orderData["brandid"]=$datas["brandId"];
        $orderData["notifyUrl"]=$datas["notifyUrl"];
        $orderData["sign"]=$sign;
        $orderData["creattime"]=time();
        $orderData["paidTime"]=0;
        $orderData["sta"]=0;

        //开始入库
        DebugLog(var_export($orderData,true),"orderData");

        if(D("Payord")->add($orderData)){
            D('Users')->frozen($paltform_oderid,$orderData);
            $retrunUrl=getSiteUrl()."/pchome/index/showinfos?order=".$paltform_oderid;
            $this->ajaxReturn($retrunUrl,'success',1);
        }else{
            DebugLog("faild message:".D("Payord")->getLastSql(),"orderData");
            $this->ajaxReturn('error','订单创建失败!',0);
        }
    }

    private function getpaydata($datas,$line_rate,$i){
        $i++;
        $Qrcode=D('Qrcode')->getOneQrcode();

        if(!$Qrcode){
            return false;
        }
        $res['qrcode_id']=$Qrcode["id"];
        $res['Qrcode']=$Qrcode;
        //实际支付金额
        $res['payMoney']=$datas["tradeMoney"]*100-rand(1,10);

        //费率分割
        $res['rate_money']=((int)($datas['tradeMoney']*100*$line_rate['rate']/1000));
        $res['payAmt']=$datas['tradeMoney']*100-$res['rate_money'];

        $if=D("Payord")->where("qrcode_id=".$res['qrcode_id']." and pay_money=".$res['payMoney']." and creattime>".(time()-300))->find();
        $if2=D("Payord")->where("qrcode_id=".$res['qrcode_id']." and pay_money=".$res['payMoney']." sta=1 and creatime>".(time()-86400)." and creattime<".(time()-300))->find();
        $money=D('Users')->getUserMoney($Qrcode['user_id']);
        $userInfo=D('Users')->getUserByUid($Qrcode['user_id']);
        if(empty($if)&&empty($if2)&&$money>$datas["tradeMoney"]&&!$userInfo['closed']){
            return $res;
        }else{
            if($i>10){
                return false;
            }
            $res=$this->getpaydata($datas,$line_rate,$i);
            return $res;
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
    private function formatBizQueryParaMap($paraMap, $urlencode){
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
    private function getData($Obj){
        foreach ($Obj as $k => $v)
        {
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        //ksort($Parameters);
        $String =$this->formatBizQueryParaMap($Parameters, false);
        return $String;
    }
    function https_post_kfs($url,$data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            return 'Errno'.curl_error($curl);
        }
        curl_close($curl);
        return $result;
    }
    protected function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;
        return false;
    }
    public function get_json_request() {
        // 获取请求参数
        $request = file_get_contents('php://input');
        if ($request == "") {
            return null;
        }
        //获取$request编码
        $encoding = mb_detect_encoding($request, 'auto');
        //convert to unicode
        if ($encoding != 'UTF-8') {
            //将$request的编码设置为UTF-8
            $request = iconv($encoding, 'UTF-8', $request);
        }
        //将json字符串转为数组
        $request = json_decode($request);
        return $request;
    }
    public function verify($data, $sign) {
        if(!$this->checkEmpty($this->publicKey)){
            $pubKey = "-----BEGIN PUBLIC KEY-----\n".wordwrap($this->publicKey, 64, "\n", true)."\n-----END PUBLIC KEY-----";
        }

        ($pubKey) or die('平台RSA公钥错误。请检查公钥文件格式是否正确');

        //调用openssl内置方法验签，返回bool值
        return (openssl_verify($data, base64_decode($sign), $pubKey, OPENSSL_ALGO_SHA1) === 1);
    }
    public function decrypt($data) {
        if(!$this->checkEmpty($this->merPriKey)){
            //拼接字符串
            $priKey = "-----BEGIN RSA PRIVATE KEY-----\n" . wordwrap($this->merPriKey, 64, "\n", true) ."\n-----END RSA PRIVATE KEY-----";
        }

        ($priKey) or die('您使用的商户私钥格式错误，请检查RSA私钥配置');

        $decodes = str_split(base64_decode($data), 256);
        $strnull = "";
        $dcyCont = "";
        foreach ($decodes as $decode) {
            if (!openssl_private_decrypt($decode, $dcyCont, $priKey)) {
                echo "<br/>" . openssl_error_string() . "<br/>";
            }
            $strnull .= $dcyCont;
        }
        return $strnull;
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

    public function callback(){
        $datas["money"]=(int)($_POST['money']*100);
        $datas["qrcode_id"]=$_POST['user_id'];

        DebugLog("testapi--".var_export($_POST,true),"callback");

        if($datas["money"]==""||$datas["qrcode_id"]==""){
            $this->ajaxReturn("","faild",0);
        }
        $orderInfo=D("Payord")->where("pay_money=".$datas['money']." and qrcode_id='".$datas["qrcode_id"]."' and creattime > ".(time()-300))->find();
        if(empty($orderInfo)){
            //没有匹配订单 入库异常队列
            $datas["creatime"]=time();
            D("payord_no")->add($datas);
            $this->ajaxReturn("","faild1",1);
        }else{
            //订单匹配完成
            if($orderInfo['sta']==1){
                //重复 入库异常队列
                $datas["creatime"]=time();
                D("payord_no")->add($datas);
                $this->ajaxReturn("","faild2",1);
            }else{
                $save['sta']=1;
                $save['paidTime']=time();
                D("payord")->where("id=".$orderInfo['id'])->save($save);

                D('Users')->unfrozen($orderInfo['tradeNo']);
                D('Users')->reducescore($orderInfo['tradeNo']);
                $users=D('Admin');
                $where['brandid']=$orderInfo["brandid"];
                $besinessInfo=$users->where($where)->find();

                $key=$besinessInfo['merId'];

                $notifyData["orderNo"]=$orderInfo['orderNo'];
                $notifyData["brandId"]=$orderInfo['brandid'];
                $notifyData["paidTime"]=$save['paidTime'];


                $notifyData["sign"]=$this->getSignK($notifyData,$key);
                $res=$this->https_request($orderInfo['notifyUrl'],$notifyData);


                if($res=="success"){

                    $save2['notifystatus']=101;
                    $save2['notifytime']=time();
                    D("payord")->where("id=".$orderInfo['id'])->save($save2);
                    $this->ajaxReturn("","success",1);
                }else{
                    $save2['notifystatus']=1;
                    $save2['notifytime']=time();
                    D("payord")->where("id=".$orderInfo['id'])->save($save2);
                    $this->ajaxReturn("","success",1);
                }
            }
        }
    }

    public function testapi(){
        $datas["brandId"] = 30006;
        $datas["orderNo"] =time().rand(100,999);
        $datas["tradeMoney"] = 1;
        $datas["payType"] = "alipay";
        $datas["notifyUrl"] = $this->url."app/index/callbacktest";
        $key="9a9aa71e447f9cd7caabfb785c10a53b";
        $datas['sign']=$this->getSignK($datas,$key);

        $url=$this->url."app/index/kuaifupay";
        $res=$this->https_request($url,$datas);
        DebugLog("testapi--".var_export($datas,true)." and sign=".$datas['sign'],"kuaifupay");
        //print_r($res);
        $result=json_decode($res,true);
        //print_r($result);
        if($result['status']==1){
            header("Location:".$result['data']);
        }else{
            print_r($result);
        }

    }
    public function testapiyou(){
        $datas["brandId"] = 30006;
        $datas["orderNo"] =time().rand(100,999);
        $datas["tradeMoney"] = 1;
        $datas["payType"] = "alipay";
        $datas["notifyUrl"] = $this->url."app/index/callbacktest";
        $key="9a9aa71e447f9cd7caabfb785c10a53b";
        $datas['sign']=$this->getSignK($datas,$key);

        $url=$this->url."app/index/youpay";
        $res=$this->https_request($url,$datas);
        DebugLog("testapi--".var_export($datas,true)." and sign=".$datas['sign'],"kuaifupay");
        //print_r($res);
        $result=json_decode($res,true);
        //print_r($result);
        if($result['status']==1){
            header("Location:".$result['data']);
        }else{
            print_r($result);
        }

    }

    public function callbacktest(){

        $datas["orderNo"]=$_POST['orderNo'];
        $datas["brandId"]=$_POST['brandId'];
        $datas["paidTime"]=$_POST['paidTime'];
        $key="9a9aa71e447f9cd7caabfb785c10a53b";
        $sign=$this->getSignK($datas,$key);
        if($sign==$_POST['sign']){
            DebugLog("回调成功--".var_export($_POST,true),"callback");
            echo "success";
        }else{
            DebugLog("回调失败--".var_export($_POST,true),"callback");
            echo "faild";
        }
    }

    //回调定时器
    public function timercall(){

        $map["sta"]=1;
        $map['notifystatus']=array('between',array(1,4));
        $map['paidTime']=array('GT',time()-3600);
        $tasklist=D("Payord")->where($map)->select();
        
        if(!empty($tasklist)){
            foreach ($tasklist as $value){
                if($value["notifytime"]<time()-60){
                    $save=array();
                    //$save[]
                    $save['notifytime']=time();
                    $notifyData["orderNo"]=$value['orderNo'];
                    $notifyData["brandId"]=$value['brandid'];
                    $notifyData["paidTime"]=$value['paidTime'];

                    $users=D('Admin');
                    $where['brandid']=$value["brandid"];
                    $besinessInfo=$users->where($where)->find();

                    $key=$besinessInfo['merId'];
                    $notifyData["sign"]=$this->getSignK($notifyData,$key);
                    $res=$this->https_request($value['notifyUrl'],$notifyData);
                    if($res=="success"){
                        $save['notifystatus']=101;
                    }else{
                        $save['notifystatus']=$value['notifystatus']+1;
                    }
                    D("Payord")->where("id=".$value['id'])->save($save);
                }
            }
        }
    }
    public function ping(){
        $id=(int)$_REQUEST['id'];
        if(!empty($id)){
            Cac()->set('ping_'.$id,time(),86400);
        }
    }
    public function testapi2(){
        $datas["brandId"] = 30006;
        $datas["orderNo"] =time().rand(100,999);
        $datas["tradeMoney"] = 1;
        $datas["payType"] = "alipay";
        $datas["notifyUrl"] = $this->url."app/index/callbacktest";
        $key="9a9aa71e447f9cd7caabfb785c10a53b";
        $datas['sign']=$this->getSignK($datas,$key);

        $url=$this->url."app/index/kuaifupay";
        $res=$this->https_request($url,$datas);
        DebugLog("testapi--".var_export($datas,true)." and sign=".$datas['sign'],"kuaifupay");
        //print_r($res);
        $result=json_decode($res,true);
        print_r($result);
        die();
        if($result['status']==1){
            header("Location:".$result['data']);
        }else{
            print_r($result);
        }

    }
    public function unfrozen(){
        $map['frozen']=1;
        $map['sta']=0;
        $map['creattime']=array('LT',time()-60*60*3);
        $orderlist=D('Payord')->where($map)->limit(25)->select();
        if(!empty($orderlist)){
            foreach ($orderlist as $value){
                D('Users')->unfrozen($value['tradeNo']);
            }
        }
    }

    //轮训订单
    public function getneworder(){
        DebugLog("testapi--".var_export($_POST,true),"getneworder");
        $qrcode_id=(int)$_REQUEST['id'];
        $map['qrcode_id']=$qrcode_id;
        $map['upstatus']=0;
        $map['creattime']=array('lt',time()-300);
        $s=D('Payord')->where($map)->find();
        if(!empty($s)){
            $data['orderid']=$s['id'];
            $data['money']=$s['pay_money'];
            $this->ajaxReturn($data,'获取最新订单',1);
        }else{
            $this->ajaxReturn('null','没有订单',0);
        }
    }
    public function uploadcodestr(){
        DebugLog("testapi--".var_export($_POST,true),"uploadcodestr");
        $orderid=(int)$_REQUEST['orderid'];
        $code=$_REQUEST['code'];
        if($code==''){
            $this->ajaxReturn('null','没有code参数',0);
        }
        $map['id']=$orderid;
        $res=D('Payord')->where($map)->find();
        if(!empty($res)&&$res['upstatus']==0){
            $save['upstatus']=1;
            $save['corestring']=$code;
            if(D('Payord')->where($map)->save($save)){
                $this->ajaxReturn('null','成功',1);
            }else{
                $this->ajaxReturn('null','更新数据失败！',0);
            }
        }else{
            $this->ajaxReturn('null','订单不存在',0);
        }
    }
}