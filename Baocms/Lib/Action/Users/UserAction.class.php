<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2019/5/22
 * Time: 上午10:30
 */
class UserAction extends CommonAction
{
    public function index(){
        $qrcode = D('Qrcode');
        import('ORG.Util.Pageam'); // 导入分页类
        $map = array('closed' => 0,'pid'=>$this->_user['user_id']);

        $count = $qrcode->where($map)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 25); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $qrcode->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach ($list as $k => &$val) {

        }
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display();
    }
}