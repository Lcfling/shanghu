<?php


class LoginAction extends CommonAction{
    
    public function index(){
        $this->display();
    }
    public function indexs(){
        $this->display();
    }
    
    public function loging(){
        $yzm = $this->_post('yzm');
        if(strtolower($yzm) != strtolower(session('verify'))){
            session('verify',null);
            $this->baoError('验证码不正确!',2000,true);
        }
        $username = $this->_post('username','trim');
        $password = $this->_post('password','trim,md5');
        $usersObj = D('Users');
        $users = $usersObj->getUserByUsername($username);
        if(empty($users) || $users['password'] != $password){
            session('verify',null);
            $this->baoError('用户名或密码不正确!'.$users,2000,true);
        }
		
        if($users['closed'] == 1){
           session('verify',null);
           $this->baoError('该账户已经被禁用!',2000,true); 
        }
		
		$ip = $users['last_ip']; //旧的IP
		
        $users['last_time'] = NOW_TIME;
        $users['last_ip']  = get_client_ip();
		if(!empty($ip)){//首先判断是否等于空
			if($ip != $users['last_ip']){
				$usersObj->where("users_id=%d",$users['users_id'])->save(array('is_ip'=>1));//对比IP不对更更新is_ip值不一样
			}
		}
		$token=md5(rand(0,9999));
		$users['token']=$token;
        $usersObj->where("user_id=%d",$users['user_id'])->save(array('last_time'=>$users['last_time'],'last_ip'=>$users['last_ip'],'token'=>$users['token']));
        
        session('users',$users);
        session('token',$token);
        $this->baoSuccess('登录成功！',U('index/index'));
    }
    
    public function logout(){
		
		$users_ids = $this->_users = session('users');
		$usersObj = D('users');
	    $usersObj->where("users_id=%d",$users_ids['users_id'])->save(array('is_ip'=>0));//不论怎么样退出的时候值修改为0，只有登录时候IP不一样才会修改
        session('users',null);
        $this->success('退出成功',U('login/index'));
    }
    
    public function verify(){
        import('ORG.Util.Image');
        Image::buildImageVerify(5,2,'png',60,30);
    }
    
}
