<?php
class BrandAction extends CommonAction{
    private $edit_fields = array('paypassword', 'newpaypsd','respaypsd');
    private $login_fields = array('loginpassword', 'newloginpsd','resloginpsd');

    public function paypsd() {
        $brandid=$_SESSION['brandid'];

        $obj=D("Admin");

        if ($this->isPost()) {

            $postdata =$this->_post('data', false);
            $data = $this->editCheck();



            $map['brandid'] = $brandid;
           $paypassword=$postdata['paypassword'];
            //判断原密码是否正确

            $list = $obj->where($map)->select();
           // print_r($list);
                 if ($list[0]['paypassword']!=md5($paypassword)){
                     $this->baoError('原密码输入不正确');

                 }

               // if ($detail['paypassword']!=md5($paypassword)){
              //  $this->baoError($detail['paypassword']);
    //    }

            if ( $data['newpaypsd']!=$data['respaypsd']){
                $this->baoError('新密码两次输入不一致');
            }
          // $map['brandid'] = $brandid;
            $datas['paypassword'] = md5($data['newpaypsd']);

            if (false !==$obj->where($map)->save($datas)) {
                // Cac()->delete('branid'.$branid);
                $this->baoSuccess('操作成功', U('brand/paypsd'));
            }
            $this->baoError('操作失败');
        } else {
            // $this->assign('detail', $detail);
            //  $this->assign('ranks',D('Userrank')->fetchAll());
            $this->display();

        }

    }

    private function editCheck() {
        $data = $this->checkFields($this->_post('data', false), $this->edit_fields);
        $data['paypassword'] = htmlspecialchars($data['paypassword']);
        if (empty($data['paypassword'])) {
            $this->baoError('原密码不能为空');
        }

        $data['newpaypsd'] = htmlspecialchars($data['newpaypsd']);
        if (empty($data['newpaypsd'])) {
            $this->baoError('新密码不能为空');
        }
        $data['respaypsd'] = htmlspecialchars($data['respaypsd']);
        if (empty($data['respaypsd'])) {
            $this->baoError('请填写重复新密码');
        }
        return $data;
    }

    public function loginpsd() {
        $brandid=$_SESSION['brandid'];

        $obj=D("Admin");

        if ($this->isPost()) {

            $postdata =$this->_post('data', false);
            $data = $this->editCheckLoginpsd();



            $map['brandid'] = $brandid;
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

    public function dai_withdraw(){
        $User = D('Zhangbian');
        import('ORG.Util.Page'); // 导入分页类

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
        $map['creattime'] =array('between',array($todaytime,$today_end));
        $this->assign('todaytime', $todaytime); // 赋值数据
        $this->assign('today_end', $today_end); // 赋值数据


        if($_REQUEST['brandid']){
            $map['brandid']=(int)$_REQUEST['brandid'];
            $this->assign('brandid',$map['brandid']);
        }

        $map['sta']=0;

        $count = $User->where($map)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $User->where($map)->order(array('id'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k=>$val){

            $where['id']=$val['bank_id'];
            $bank = D('Brandbank')->where($where)->select();

            $val['username'] = $bank[0]['username'];
            $val['bankname'] = $bank[0]['bankname'];
            $val['banknum'] = $bank[0]['banknum'];

            $val['money'] = $val['money']/100;
            $list[$k] = $val;
        }

        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出

        $this->display(); // 输出模板

    }

    public function audit_success($id=0){
        if($id!=0){
            $obj=D("Zhangbian");
            $map['id']=$id;
            $datas['sta']=1;
            $datas['statime']=time();
            if (false !==$obj->where($map)->save($datas)) {

                $this->baoSuccess('操作成功', U('brand/dai_withdraw'));
            }

        }

    }
    public function audit_bohui($id=0){
        if($id!=0){
            $obj=D("Zhangbian");
            $map['id']=$id;
            $datas['sta']=2;
            $datas['statime']=time();
            if (false !==$obj->where($map)->save($datas)) {

                $this->baoSuccess('驳回操作成功', U('brand/dai_withdraw'));
            }

        }

    }


    public function yi_withdraw(){
        $User = D('Zhangbian');
        import('ORG.Util.Page'); // 导入分页类

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
        $map['creattime'] =array('between',array($todaytime,$today_end));
        $this->assign('todaytime', $todaytime); // 赋值数据
        $this->assign('today_end', $today_end); // 赋值数据


        if($_REQUEST['brandid']){
            $map['brandid']=(int)$_REQUEST['brandid'];
            $this->assign('brandid',$map['brandid']);
        }

        $map['sta']=1;

        $count = $User->where($map)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $User->where($map)->order(array('id'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k=>$val){

            $where['id']=$val['bank_id'];
            $bank = D('Brandbank')->where($where)->select();

            $val['username'] = $bank[0]['username'];
            $val['bankname'] = $bank[0]['bankname'];
            $val['banknum'] = $bank[0]['banknum'];

            $val['money'] = $val['money']/100;
            $list[$k] = $val;
        }

        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出

        $this->display(); // 输出模板

    }

    public function bohui(){
        $User = D('Zhangbian');
        import('ORG.Util.Page'); // 导入分页类

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
        $map['creattime'] =array('between',array($todaytime,$today_end));
        $this->assign('todaytime', $todaytime); // 赋值数据
        $this->assign('today_end', $today_end); // 赋值数据


        if($_REQUEST['brandid']){
            $map['brandid']=(int)$_REQUEST['brandid'];
            $this->assign('brandid',$map['brandid']);
        }


        $map['sta']=2;

        $count = $User->where($map)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $User->where($map)->order(array('id'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k=>$val){

            $where['id']=$val['bank_id'];
            $bank = D('Brandbank')->where($where)->select();

            $val['username'] = $bank[0]['username'];
            $val['bankname'] = $bank[0]['bankname'];
            $val['banknum'] = $bank[0]['banknum'];

            $val['money'] = $val['money']/100;
            $list[$k] = $val;
        }

        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出

        $this->display(); // 输出模板

    }
}
