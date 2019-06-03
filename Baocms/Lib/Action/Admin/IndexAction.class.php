<?php
class IndexAction extends CommonAction
{
    public function index()
    {
        $menu = D('Menu')->fetchAll();
        if ($this->_admin['role_id'] != 1) {
            if ($this->_admin['menu_list']) {
                foreach ($menu as $k => $val) {
                    if (!empty($val['menu_action']) && !in_array($k, $this->_admin['menu_list'])) {
                        unset($menu[$k]);
                    }
                }
                foreach ($menu as $k1 => $v1) {
                    if ($v1['parent_id'] == 0) {
                        foreach ($menu as $k2 => $v2) {
                            if ($v2['parent_id'] == $v1['menu_id']) {
                                $unset = true;
                                foreach ($menu as $k3 => $v3) {
                                    if ($v3['parent_id'] == $v2['menu_id']) {
                                        $unset = false;
                                    }
                                }
                                if ($unset) {
                                    unset($menu[$k2]);
                                }
                            }
                        }
                    }
                }
                foreach ($menu as $k1 => $v1) {
                    if ($v1['parent_id'] == 0) {
                        $unset = true;
                        foreach ($menu as $k2 => $v2) {
                            if ($v2['parent_id'] == $v1['menu_id']) {
                                $unset = false;
                            }
                        }
                        if ($unset) {
                            unset($menu[$k1]);
                        }
                    }
                }
            } else {
                $menu = array();
            }
        }
        $this->assign('menuList', $menu);
        $this->display();
    }


    public function indexs()
    {
        $menu = D('Menu')->fetchAll();
        if ($this->_admin['role_id'] != 1) {
            if ($this->_admin['menu_list']) {
                foreach ($menu as $k => $val) {
                    if (!empty($val['menu_action']) && !in_array($k, $this->_admin['menu_list'])) {
                        unset($menu[$k]);
                    }
                }
                foreach ($menu as $k1 => $v1) {
                    if ($v1['parent_id'] == 0) {
                        foreach ($menu as $k2 => $v2) {
                            if ($v2['parent_id'] == $v1['menu_id']) {
                                $unset = true;
                                foreach ($menu as $k3 => $v3) {
                                    if ($v3['parent_id'] == $v2['menu_id']) {
                                        $unset = false;
                                    }
                                }
                                if ($unset) {
                                    unset($menu[$k2]);
                                }
                            }
                        }
                    }
                }
                foreach ($menu as $k1 => $v1) {
                    if ($v1['parent_id'] == 0) {
                        $unset = true;
                        foreach ($menu as $k2 => $v2) {
                            if ($v2['parent_id'] == $v1['menu_id']) {
                                $unset = false;
                            }
                        }
                        if ($unset) {
                            unset($menu[$k1]);
                        }
                    }
                }
            } else {
                $menu = array();
            }
        }
        $this->assign('menuList', $menu);
        $this->display();
    }
    public function main(){
        $brandid=$_SESSION['brandid'];
      $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));

        $counts['count_ord']=D('Payord')->where("brandid=$brandid and creattime>".$beginToday)->count();


        $counts['count_ord_success']=D('Payord')->where("brandid=$brandid and sta=1 and creattime>".$beginToday)->count();
        $counts['count_ord_fail']=D('Payord')->where("brandid=$brandid and sta=0  and creattime>".$beginToday )->count();

        $pay_money=D("Payord")->where("brandid=$brandid and sta=1  and creattime>".$beginToday)->field('sum(pay_money) as pay_money')->select();
        $counts['pay_money']=$pay_money[0]['pay_money']/100;

        $day_rujin=D("Payord")->where("brandid=$brandid and sta=1  and creattime>".$beginToday)->field('sum(money) as money')->select();
        $counts['day_rujin']=$day_rujin[0]['money']/100;


        $rujin=D("Payord")->where("brandid=$brandid and sta=1")->field('sum(payAmt) as payAmt')->select();
        $counts['rujin']=$rujin[0]['payAmt']/100;





        $txian=D("Zhangbian")->where("brandid=$brandid and sta<2")->field('sum(money) as money')->select();
        $counts['txian']=$txian[0]['money']/100;

        if(empty($counts['txian'])){
            $counts['txian']=0;

        }

        //驳回额度
        $txian_bohui=D("Zhangbian")->where("brandid=$brandid and sta=2")->field('sum(money) as money')->select();
        $counts['txian_bohui']=$txian_bohui[0]['money']/100;

        if(empty($counts['txian_bohui'])){
            $counts['txian_bohui']=0;

        }


        //账号余额- 提现表==(账户余额可提现+被驳回的额度)
        $counts['money']=$counts['rujin']-$counts['txian'];

        $this->assign('counts', $counts);


        $this->display();
    }

    public function mains(){
        $brandid=$_SESSION['brandid'];
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));

        $counts['count_ord']=D('Payord')->where("brandid=$brandid and creattime>".$beginToday)->count();


        $counts['count_ord_success']=D('Payord')->where("brandid=$brandid and sta=1 and creattime>".$beginToday)->count();
        $counts['count_ord_fail']=D('Payord')->where("brandid=$brandid and sta=0  and creattime>".$beginToday )->count();

        $pay_money=D("Payord")->where("brandid=$brandid and sta=1  and creattime>".$beginToday)->field('sum(payAmt) as payAmt')->select();
        $counts['payAmt']=$pay_money[0]['payAmt']/100;

        $day_rujin=D("Payord")->where("brandid=$brandid and sta=1  and creattime>".$beginToday)->field('sum(money) as money')->select();
        $counts['day_rujin']=$day_rujin[0]['money']/100;

        $all_money=D("Payord")->where("brandid=$brandid and sta=1")->field('sum(money) as money')->select();
        $counts['all_money']=$all_money[0]['money']/100;


        $rujin=D("Payord")->where("brandid=$brandid and sta=1")->field('sum(payAmt) as payAmt')->select();
        $counts['rujin']=$rujin[0]['payAmt']/100;

        $txian=D("Zhangbian")->where("brandid=$brandid and sta<2")->field('sum(money) as money')->select();
        $counts['txian']=$txian[0]['money']/100;

        $txiansu=D("Zhangbian")->where("brandid=$brandid and sta=1")->field('sum(money) as money')->select();
        $counts['txiansu']=$txiansu[0]['money']/100;

        if(empty($counts['txian'])){
            $counts['txian']=0;

        }

        //驳回额度
        $txian_bohui=D("Zhangbian")->where("brandid=$brandid and sta=2")->field('sum(money) as money')->select();
        $counts['txian_bohui']=$txian_bohui[0]['money']/100;

        if(empty($counts['txian_bohui'])){
            $counts['txian_bohui']=0;

        }


        //账号余额- 提现表==(账户余额可提现+被驳回的额度)
        $counts['money']=$counts['rujin']-$counts['txian'];

        //表单数据

        if($_REQUEST["time"]){
            $todaybegin=strtotime($_REQUEST["time"]);

        }else{
            $todaybegin=strtotime(date('Y-m-d'),time());
        }
        $time=$todaybegin;

        /*$todayEnd=$todaybegin+86400;
        $sevenbegin=time()-144*60*10;*/
        $bengin=$todaybegin;

        for($i=0;$i<48;$i++){
            $end=$bengin+1800;
            $allcount=D("Payord")->where("brandid=$brandid and creattime>=".$bengin." and creattime<".$end)->count();
            $succount=D("Payord")->where("brandid=$brandid and sta=1 and creattime>=".$bengin." and creattime<".$end)->count();
            //echo D()->getLastSql();
            $temp=$temp2=$temp3=$temp4=array();
            if($i%4==0){
                $temp[]=$i;
                $temp[]=date('H:i',$bengin);
                $res[]=$temp;
            }

            $temp2[]=$i;
            $temp2[]=$allcount;
            $temp3[]=$i;
            $temp3[]=$succount;
            $temp4[]=$i;
            $temp4[]=$succount/$allcount*100;

            $value[]=$temp2;
            $value2[]=$temp3;
            $value3[]=$temp4;
            $bengin=$end;
        }
        $this->assign('value3', json_encode($value3));
        $this->assign('value2', json_encode($value2));
        $this->assign('value', json_encode($value));
        $this->assign('res', json_encode($res));
        $this->assign('time', $time);
        $this->assign('counts', $counts);
        $this->display();
    }
    public function testap(){
        $brandid=$_SESSION['brandid'];
        //$count=D("Payord")->where("brandid=$brandid and sta=1")->count();
        $todaybegin=strtotime(date('Y-m-d'),time());
        $todayEnd=$todaybegin+86400;
        $sevenbegin=time()-144*60*10;
        $bengin=$todaybegin;
        for($i=0;$i<144;$i++){
            $end=$bengin+600;
            $allcount=D("Payord")->where("brandid=$brandid and creattime>=".$bengin." and creattime<".$end)->count();
            $succount=D("Payord")->where("brandid=$brandid and sta=1 and creattime>=".$bengin." and creattime<".$end)->count();
            //echo D()->getLastSql();
            if($i%12==0){
                $temp[]=$i;
                $temp[]=date('H:i',$bengin);
                $res[]=$temp;
            }

            $temp2[]=$i;
            $temp2[]=$allcount;

            $value[]=$temp2;

            $bengin=$end;
        }
        //echo json_encode($res);
    }
}
