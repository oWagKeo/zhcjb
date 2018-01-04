<?php
namespace Home\Controller;
use Org\Util\Sms;
use Org\Util\Validate;
use Think\Controller;
header("content-type:text/html;charset=utf-8");

class LogregController extends Controller {
	public function _initialize(){
//		$wx = new \WX_LC('_3146','wx91660942fa7be4c2');
//		$wx_config=json_encode($wx->getSharedConfig());
//		$this->assign('wx_config',"var wx_config=$wx_config;");
	}
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
	 public function tips(){
         $this->display("tipsPage");
     }

	public function register(){
		$this->display();
	}

	/**
	 * 获取手机验证码
	 */
	public function get_code(){
		$code = str_pad(rand(0,999999),6,0);

		if( !empty(session('3146_msg_time')) && time()-session('3146_msg_time')<60 ){
			$this->ajaxReturn(['msg'=>'短信已发送！','res'=>1,'data'=>false]);
			exit();
		}
		session('3146_msg_code',$code);
		session('3146_msg_time',time()+5*60);
//		$sms = new Sms();
//		$re = $sms->send($_POST['phone'],'您的场景宝验证码为:'.$code.',验证码5分钟之内有效！【邮储成都市分行】');
//		if( $re ){
//			$this->ajaxReturn(['msg'=>'发送成功！','res'=>1,'data'=>true]);
//		}else{
//			$this->ajaxReturn(['msg'=>'发送失败！','res'=>1,'data'=>false]);
//		}
		$this->ajaxReturn(['msg'=>$code,'res'=>1,'data'=>true]);
	}

	/**
	 * 登录注册
	 */
	public function register_save(){
		if( $_POST['phone'] == ''){
			$this->ajaxReturn(['msg'=>'请填写必要信息!','res'=>101,'data'=>false]);
		}
		//验证短信
		if(session('3146_msg_code') != $_POST['code'] || session('3146_msg_time') < time()){
			$this->ajaxReturn(['msg'=>'验证码超时或错误!','res'=>3,'data'=>false]);
		}
		//验证唯一
		$map = [
			'u_phone' => $_POST['phone']
		];
		$check = M('user')->where($map)->find();
		//存在则登录
		if($check){
// 			$this->ajaxReturn(['msg'=>'重复的银行卡或电话!','res'=>2,'data'=>false]);
			session('3146_uid',$check['u_id']);
			$this->ajaxReturn(['msg'=>'登录成功','res'=>1,'data'=>true]);
		}
		//不存在则注册
		$user = array();
		$user['u_nick'] = $_POST['phone'];
		$user['u_name'] = $_POST['phone'];
		$user['u_phone'] = $_POST['phone'];
		$user['u_create'] = time();
		$user['u_update'] = time();
		$res = M('user')->add($user);
// 		$res = M('user')->where(['u_id'=>session('3146_uid')])->save(['u_cardno'=>$_POST['card'],'u_name'=>$_POST['user'],'u_phone'=>$_POST['phone'],]);
		if(!$res){
			$this->ajaxReturn(['msg'=>'网络超时!','res'=>-1,'data'=>false]);
		}
		session('3146_uid',$res);
		$this->ajaxReturn(['msg'=>'注册成功','res'=>1,'data'=>true]);
	}
	public function logout(){
		session('3146_uid',null);
	}
}