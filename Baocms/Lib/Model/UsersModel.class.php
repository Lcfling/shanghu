<?php
class UsersModel extends CommonModel
{
    protected $pk = 'user_id';
    protected $tableName = 'users';
    //protected $_integral_type = array('share' => '发帖分享', 'reply' => '回复帖子', 'mobile' => '手机认证', 'email' => '邮件认证');

    /**根据用户id获取用户信
     * @param $user_id
     * @param bool $cleanCache
     * @return $userinfo
     */
    public function getUserByUid($user_id,$cleanCache=false){
        if($cleanCache){
            Cac()->set('userinfo_'.$user_id,null);
            $data = $this->find(array('where' => array('user_id' => $user_id)));
        }else{
            $data=Cac()->get('userinfo_'.$user_id);
            $data=unserialize($data);
            if(!empty($data)){
                return $this->_format($data);
            }else{
                $data = $this->find(array('where' => array('user_id' => $user_id)));
                Cac()->set('userinfo_'.$user_id,serialize($data));
            }
        }

        //$data = $this->find(array('where' => array('account' => $account)));
        return $this->_format($data);
    }
    /**根据用户名称获取用户信
     * @param $user_id
     * @param bool $cleanCache
     * @return $userinfo
     */
    public function getUserByUsername($username){
        $data = $this->find(array('where' => array('account' => $username)));
        //$data = $this->find(array('where' => array('account' => $account)));
        return $this->_format($data);
    }
    /**根据用户mobile获取用户信
     * @param $user_id
     * @param bool $cleanCache
     * @return $userinfo
     */
    public function getUserByMobile($mobile,$cleanCache=false){
        if($cleanCache){
            //Cac()->set('userinfo_'.$mobile,null);
            $data = $this->find(array('where' => array('account' => $mobile)));
            Cac()->set('userinfo_mobile_'.$mobile,null);
        }else{
            $data=Cac()->get('userinfo_mobile_'.$mobile);
            if($data!=null){
                $data=unserialize($data);
            }
            if(!empty($data)){
                return $this->_format($data);
            }else{
                $data = $this->find(array('where' => array('user_id' => $mobile)));
                Cac()->set('userinfo_mobile_'.$mobile,serialize($data));
            }
        }
        //$data=$this->where(array('account'=>(String)$mobile))->find();
        //$data = $this->find(array('where' => array('account' => (String)$mobile)));

        return $data;
    }

    /**获取用户余额
     * @param $uid
     * @return mixed
     */
    public function getUserMoney($uid){
        $sql="SELECT SUM(score) AS usermoney FROM __PREFIX__account_log WHERE user_id=$uid";
        $res=$this->Query($sql);
        $money=$res[0]['usermoney'];
        if(empty($money)){
            $money=0;
        }
        return $money;
    }

    /**更新用户缓存
     * @param $userInfo
     * @return mixed
     */
    public function updateLoginCache($userInfo){
        $userInfo['last_ip']=$data['last_ip']=getip();

        $userInfo['last_time']=$data['last_time']=time();
        $token=rand_string(6,1);
        $userInfo['token']=$data['token']=md5($token);

        $this->where(array('account'=>(string)$userInfo['account']))->save($data);
        Cac()->set('userinfo_'.$userInfo['user_id'],serialize($userInfo));
        Cac()->set('userinfo_mobile_'.$userInfo['account'],serialize($userInfo));
        return $userInfo;
    }

    public function insertUserInfo($mobile,$pid=0){
        $info['account']=$mobile;
        $info['password']=md5(rand_string(11,1));
        $info['nickname']=rand_string(6,1);
        $info['money']=0;
        $info['mobile']=$mobile;
        $info['reg_ip']=$info['last_ip']=getip();
        $info['reg_time']=$info['last_time']=time();
        $token=$info['token']=md5(rand_string(6,1));
        if($pid==0||$pid==""||$pid==null)
            $pid=0;
        $info['pid']=$pid;
        $this->add($info);
        $userInfo=$this->find(array('where'=>array('account'=>$mobile)));
        $userInfo['nickname']='*'.$userInfo['user_id'];
        $data['nickname']='*'.$userInfo['user_id'];
        $this->where(array('account'=>$mobile))->save($data);
        Cac()->set('userinfo_'.$userInfo['user_id'],serialize($userInfo));
        Cac()->set('userinfo_mobile_'.$userInfo['account'],serialize($userInfo));
        $this->addmoney($userInfo['user_id'],500,1,1,"体验金");
        return $userInfo;
    }

    /**添加用户金额
     */
    public function addscore($uid,$score,$remark=''){
        $info['score']=$score;
        $info['type']=3;
        $info['user_id']=$uid;
        $info['order_id']=0;
        if($remark){
            $info['remark']=$remark;
        }else{
            $info['remark']='上分';
        }
        $info['creatime']=time();

        $m=D('AccountLog');
        if($m->add($info)){
            return true;
        }else{
            return false;
        }
    }

    /**
     *
     * 下分
     */
    public function downscore($uid,$score,$remark=''){
        $info['score']=-$score;
        $info['type']=4;
        $info['user_id']=$uid;
        $info['order_id']=0;

        if($remark){
            $info['remark']=$remark;
        }else{
            $info['remark']='用户下分';
        }
        $info['creatime']=time();
        $info['creatime']=time();

        $m=D('AccountLog');
        if($m->add($info)){
            return true;
        }else{
            return false;
        }
    }

    public function reducescore($orderid){
        $orderData=D('Payord')->where('tradeNo="'.$orderid.'"')->find();
        if(empty($orderData)){
            return false;
        }
        $info['score']=-$orderData['money'];
        $info['type']=2;
        $info['user_id']=$orderData['qrcodeuid'];
        $info['order_id']=$orderid;

        $info['remark']="成交扣除";
        $info['creatime']=time();
        $m=D('AccountLog');
        if($m->add($info)){
            return true;
        }else{
            return false;
        }
    }


    //冻结金额
    public function frozen($orderid,$orderData,$remark=''){

        $info['score']=-$orderData['money'];
        $info['type']=1;
        $info['user_id']=$orderData['qrcodeuid'];
        $info['order_id']=$orderid;


        if($remark){
            $info['remark']=$remark;
        }else{
            $info['remark']='资金冻结';
        }
        $info['creatime']=time();

        $m=D('AccountLog');
        if($m->add($info)){
            return true;
        }else{
            return false;
        }
    }
    //资金解冻
    public function unfrozen($orderid,$remark=''){

        $orderData=D('Payord')->where('tradeNo='.$orderid)->find();
        if(empty($orderData)){
            return false;
        }
        $info['score']=$orderData['money'];
        $info['type']=2;
        $info['user_id']=$orderData['qrcodeuid'];
        $info['order_id']=$orderid;

        if($remark){
            $info['remark']=$remark;
        }else{
            $info['remark']='资金解冻';
        }

        $info['creatime']=time();
        $m=D('AccountLog');
        if($m->add($info)){
            //解冻
            D('Payord')->where('tradeNo='.$orderid)->save(array('frozen'=>0));
            return true;
        }else{
            return false;
        }
    }

    //提现锁
    public function txLock($uid,$str){

        Cac()->rPush('txLock'.$uid,$str);
        $value=Cac()->lGet('txLock'.$uid,0);
        if($value==$str){
            return true;
        }else{
            return false;
        }
    }
    //提现解锁
    public function txopenLock($uid){
        Cac()->delete('txLock'.$uid);
    }



}