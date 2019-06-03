<?php


class PayAction extends CommonAction
{
    public function showinfo(){
        $orderNo=htmlspecialchars($_GET["order"]);
        $orderInfo=D("Payord")->where("tradeNo=".$orderNo);
        if(empty($orderInfo)){
            $this->ajaxReturn("","订单未找到",0);
        }
        die("sss");
        if(time()-$orderInfo["creattime"]>300){
            $this->ajaxReturn("","订单已经过期11",0);
        }
        if(time()-$orderInfo["sta"]>0){
            $this->ajaxReturn("","订单已经过期22",0);
        }
        $res["order"]=$orderInfo["tradeNo"];
        $res["money"]=$orderInfo["money"];
        $res["timeS"]=time()-$orderInfo["creattime"];

        $this->assign("orderinfo",$res);
        $this->display();
    }
}