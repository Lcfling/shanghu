<?php


class QrcodeAction extends CommonAction
{
    private $create_fields = array('user_id','mobile', 'qrcode', 'type','remark','filepath','creatime');
    //private $edit_fields = array('password', 'role_id', 'mobile','city_id','rate','minpay');
    public function index(){
        $qrcode = D('Qrcode');
        import('ORG.Util.Pageam'); // 导入分页类
        $map = array('user_id' => $this->_users['user_id']);

        $count = $qrcode->where($map)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $qrcode->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach ($list as $k => &$val) {

            $val['connect']=D('Qrcode')->isQrcodeOnline($val['id']);
            $val['queue']=D('Qrcode')->isQrcodeIn($val['id'],$val['type']);
            $val['creatime']=date('Y-m-d h:i:s',$val['creatime']);
        }
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display();
    }

    public function create() {
        if ($this->isPost()) {
            $data = $this->createCheck();
            $obj = D('Qrcode');
            if ($obj->add($data)) {
                $this->baoSuccess('添加成功', U('qrcode/index'));
            }
            $this->baoError('操作失败！');
        } else {
            //$this->assign('roles', D('Role')->fetchAll());
            $this->display();
        }
    }
    private function createCheck() {
        $data = $this->checkFields($this->_post('data', false), $this->create_fields);
        $data['remark'] = htmlspecialchars($data['remark']);
        $data['mobile'] = htmlspecialchars($data['mobile']);
        $data['user_id']=$this->_users['user_id'];
        if (empty($data['mobile'])) {
            $this->baoError('手机不能为空');
        }
        if (!isMobile($data['mobile'])) {
            $this->baoError('手机格式不正确');
        }
        if (empty($data['filepath'])) {
            $this->baoError('请上传缩略图');
        }
        if (!isImage($data['filepath'])) {
            $this->baoError('缩略图格式不正确');
        }
        $data['creatime'] = time();
        return $data;
    }
    public function testredis(){
        //Cac()->delete('test');
        $id=(int)$_REQUEST['id'];
        Cac()->lRange('Qrcode_Queue',0,-1);
        print_r(Cac()->lRange('Qrcode_Queue',0,-1));
    }
    //开始接单
    public function beginreciveorder(){
        $id=(int)$_REQUEST['id'];
        if(D('Qrcode')->RpushQueue($id)){
            $this->baoSuccess('开启接单！', U('qrcode/index'));
        }else{
            $this->baoError('开启接单失败！');
        }
    }
    public function forbiden(){
        $id=(int)$_REQUEST['id'];
        $data['is_active']=0;

        //$qrcode=D('Qrcode')->where()
        $map['id']=$id;
        $map['user_id']=$this->_users['user_id'];
        if(D('Qrcode')->where($map)->save($data)){
            $this->baoSuccess('禁用成功！', U('qrcode/index'));
        }else{
            $this->baoError('操作失败！');
        }

    }
    public function active(){
        $id=(int)$_REQUEST['id'];
        $data['is_active']=1;

        //$qrcode=D('Qrcode')->where()
        $map['id']=$id;
        $map['user_id']=$this->_users['user_id'];
        if(D('Qrcode')->where($map)->save($data)){
            $this->baoSuccess('开启成功！', U('qrcode/index'));
        }else{
            $this->baoError('操作失败！');
        }

    }
}