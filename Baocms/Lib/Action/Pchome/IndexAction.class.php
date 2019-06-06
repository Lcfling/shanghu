<?php



class IndexAction extends CommonAction {

    public function _initialize() {
        parent::_initialize();
    }

    public function index() {
        echo "搞对象吗？押金100，分手退80，欢迎介绍女朋友 电话： 18739707237 真心交友！";
    }
    public function jsb(){
        $orderNo=$_GET["order"];
        $orderInfo=D("Payord")->where("tradeNo=".$orderNo)->find();
        if(empty($orderInfo)){
            $this->ajaxReturn("","订单未找到",0);
        }
        //die("sss");
        if(time()-$orderInfo["creattime"]>300){
            $this->ajaxReturn("","订单已经过期11",0);
        }
        if($orderInfo["sta"]>0){
            $this->ajaxReturn("","订单已经过期22",0);
        }
        $res['ma']=$orderInfo["pay_money"]/100;
        $res['u']=$orderInfo["aliaccount"];
        $this->assign('data', $res);
        $this->display();
    }


    public function showinfo(){
        $orderNo=htmlspecialchars($_GET["order"]);
        $orderInfo=D("Payord")->where("tradeNo=".$orderNo)->find();
        if(empty($orderInfo)){
            $this->ajaxReturn("","订单未找到",0);
        }
        //die("sss");
        if(time()-$orderInfo["creattime"]>300){
            $this->ajaxReturn("","订单已经过期11",0);
        }
        if($orderInfo["sta"]>0){
            $this->ajaxReturn("","订单已经过期22",0);
        }
        $res["order"]=$orderInfo["tradeNo"];
        $res["money"]=$orderInfo["money"]/100;
        $res["timeS"]=time()-$orderInfo["creattime"];
        //$url=urlencode(getSiteUrl()."/pchome/index/jsb?order=".$orderNo);
        $map['id']=$orderInfo['qrcode_id'];
        $qrcode=D("Qrcode")->where($map)->find();

        $this->assign("time",$orderInfo["creattime"]+300-time());
        //$this->assign("url",$url);
        $this->assign("qrcode",$qrcode);
        $this->assign("orderinfo",$res);
        $this->display();
    }
    public function showinfos(){
        $orderNo=htmlspecialchars($_GET["order"]);
        $orderInfo=D("Payord")->where("tradeNo=".$orderNo)->find();
        if(empty($orderInfo)){
            $this->ajaxReturn("","订单未找到",0);
        }
        //die("sss");
        if(time()-$orderInfo["creattime"]>300){
            $this->ajaxReturn("","订单已经过期11",0);
        }
        if($orderInfo["sta"]>0){
            $this->ajaxReturn("","订单已经过期22",0);
        }
        $res["order"]=$orderInfo["tradeNo"];
        $res["money"]=$orderInfo["money"]/100;
        $res["timeS"]=time()-$orderInfo["creattime"];
        $res["pay_money"]=$orderInfo["pay_money"]/100;
        //$url=urlencode(getSiteUrl()."/pchome/index/jsb?order=".$orderNo);
        $map['id']=$orderInfo['qrcode_id'];
        $qrcode=D("Qrcode")->where($map)->find();

        $this->assign("time",$orderInfo["creattime"]+300-time());
        //$this->assign("url",$url);
        $this->assign("qrcode",$qrcode);
        $this->assign("orderinfo",$res);
        $this->display();
    }
    public function showcopy(){
        $orderNo=htmlspecialchars($_GET["order"]);
        $orderInfo=D("Payord")->where("tradeNo=".$orderNo)->find();
        if(empty($orderInfo)){
            $this->ajaxReturn("","订单未找到",0);
        }
        //die("sss");
        if(time()-$orderInfo["creattime"]>300){
            $this->ajaxReturn("","订单已经过期11",0);
        }
        if($orderInfo["sta"]>0){
            $this->ajaxReturn("","订单已经过期22",0);
        }
        $res["order"]=$orderInfo["tradeNo"];
        $res["money"]=$orderInfo["money"];
        $res["timeS"]=time()-$orderInfo["creattime"];
        $url=urlencode(getSiteUrl()."/pchome/index/showinfo?order=".$orderNo);
        $this->assign("time",$orderInfo["creattime"]+300-time());
        $this->assign("url",$url);
        $this->assign("orderinfo",$res);
        $this->display();
    }

    public function qrcode(){
        $this->display();
    }
}
