<?php
namespace Admin\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf-8");
class LogregController extends Controller {
	public function verification(){
		ob_clean();
		$Verify = new \Think\Verify();
		$Verify->fontSize = 18;
		$Verify->length   = 4;
		$Verify->useImgBg  = false;
		$Verify->useNoise = true;
		$Verify->codeSet = '1234567890';
		$Verify->imageW = 154;
		$Verify->imageH = 40;
		$Verify->expire = 600;
		$Verify->entry();
	}
	public function login(){
		layout(false);
		$this->display();		
	}
	public function logincheck(){
		$ver = $_POST['ver'];
		$Verify = new \Think\Verify();
		/*if(!$Verify->check($ver)){
			$this->error("验证码错误");
		}*/
		$info = M('admin')->where("user_name = '".I('post.username')."' and password = '".sha1(I('post.password'))."'")->find();
		if($info){
			session('cdlchd_adminid',$info['user_id']);
			$data = [
				'last' => $info['login'],
				'login' => time()
			];
			M('admin')->where('user_id = '.$info['user_id'])->save($data);
			$this->admin_log($info['user_id'],$info['user_name'].'登录系统');
			$this->redirect('Index/index','', 0, '登录成功,跳转中...');
		}else{
			$this->admin_log(0,'尝试登录系统(账户:'.I('post.username').',密码:'.I('post.password').')');
			$this->error('密码或账户错误!');
		}
	}
	public function logout(){
		$this->admin_log(session('cdlchd_adminid'),'退出系统');
		session('cdlchd_adminid',null);
		$this->success('登出成功,跳转中...', 'login', 2);
	}
	public function admin_log($uid,$info){
		$data = [
			'log_time' => time(),
			'user_id' => $uid,
			'log_info' => $info,
			'ip_address' => $this->get_ip()
		];
		M('admin_log')->add($data);
	}
	/**
	 * 获取ip
	 */
	public function get_ip(){
		static $realip;
		if (isset($_SERVER)){
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
				$realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			} else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
				$realip = $_SERVER["HTTP_CLIENT_IP"];
			} else {
				$realip = $_SERVER["REMOTE_ADDR"];
			}
		} else {
			if (getenv("HTTP_X_FORWARDED_FOR")){
				$realip = getenv("HTTP_X_FORWARDED_FOR");
			} else if (getenv("HTTP_CLIENT_IP")) {
				$realip = getenv("HTTP_CLIENT_IP");
			} else {
				$realip = getenv("REMOTE_ADDR");
			}
		}
		return $realip;
	}
}