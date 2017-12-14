<?php
namespace Home\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf-8");

/**
 * Class IndexController
 * @package Home\Controller
 * 程序首页
 */
class IndexController extends CommonController {
	/**
	 * 首页信息
	 */
	public function index(){
		$map = [
			'g_start' => ['elt',time()],
			'g_end' => ['gt',time()],
			'g_show' => 1,
		];
		$info = M('goods')->where($map)->order('g_id desc')->limit('0,50')->select();
		$this->assign('info',$info);
		$this->display();
	}

	/**
	 * 分类信息奖券列表
	 */
	public function goods_list(){
		$map = [
			'g_start' => ['elt',time()],
			'g_end' => ['gt',time()],
			'g_show' => 1,
		];

		if( $_GET['type'] ){
			$map['g_type'] = $_GET['type'];
		}

	    $info = M('goods')->where($map)->limit('0,50')->select();
		$this->assign('info',$info);
		$this->assign('mark','list');
		$this->display('list');
	}
	/**
	 * 搜索
	 */
	public function search(){
		$map = [
			'g_start' => ['elt',time()],
			'g_end' => ['gt',time()],
			'g_show' => 1,
		];
		$map['g_name'] = ['exp','like "%'.$_GET['name'].'%"'];
		$info = M('goods')->where($map)->limit('0,50')->select();
		$this->assign('mark','search');
		$this->assign('info',$info);
		$this->display('list');
	}
	/**
	 * ajax请求
	 * 按分类返回劵信息
	 */
	public function get_goods(){
		$top = isset($_POST['top']) ? $_POST['top'] : 0;
		$num = isset($_POST['num']) ? $_POST['num'] : 50;
		$map = [
			'g_start' => ['elt',time()],
			'g_end' => ['gt',time()],
			'g_show' => 1,
		];
		if( $_GET['type'] ){
			$map['g_type'] = $_GET['type'];
		}

		$info = M('goods')->where($map)->limit($top.','.$num)->select();
		$this->ajaxReturn(['msg'=>'请求成功','res'=>1,'data'=>$info]);
	}
	/**
	 * 获取优惠劵详情
	 */
	public function info(){

		$info = M('goods')->where(['g_id' => $_GET['gid']])->find();

		$this->assign('info',$info);
		$this->display('details');
	}
	/**
	 * ajax请求
	 * 兑换劵
	 */
	public function buy(){
		//读取用户积分
		$uid = session('3146_uid');
		if( !$uid ){
			$this->redirect('Logreg/register');
		}
		$user = M('user')->where(['u_id' => $uid])->find();
		//读取劵价格
		$goods = M('goods')->where(['g_id' => $_POST['gid']])->find();
		//一种劵一次最多领取1张
		if( $_POST['num'] > 1 ){
			$this->ajaxReturn(['msg'=>'该劵一天最多兑换1张哦！','res'=>7,'data'=>false]);
		}
		//判断是否为限领
		if($goods['g_claim'] != -1){
			//计算已经兑换的数量
			$exchange = M('exchange')->where(['e_uid'=>$uid,'e_gid'=>$goods['g_id']])->count('e_id');
			$lottery = M('weinner')->where(['uId'=>$uid,'gid'=>$goods['g_id']])->count('id');
			if($exchange + $lottery + $_POST['num'] > $goods['g_claim']){
				$this->ajaxReturn(['msg'=>'该劵最多只能兑奖'.$goods['g_claim'].'张哦!','res'=>7,'data'=>false]);
			}
		}
		//查看用户当天是否已经领取
		$has = M('exchange')->where(array('e_uid'=>$uid,'e_gid'=>$goods['g_id']))->order('e_create desc')->limit(1)->getField('e_create');
		if( $has ){
			$time1 = strtotime(date('Y-m-d',$has).' 23:59:59');//当天最大时间
			if( time()<=$time1 ){
				$this->ajaxReturn(['msg'=>'您今天已经兑换过了哦,明天再来吧！','res'=>7,'data'=>false]);
			}
		}
		if($_POST['num'] <= 0){
			$this->ajaxReturn(['msg'=>'最少兑换一张劵哟!','res'=>5,'data'=>false]);
		}
		if($goods['g_start'] > time() || $goods['g_end'] < time()){
			$this->ajaxReturn(['msg'=>'该劵已经下架啦!','res'=>4,'data'=>false]);
		}
		if($goods['g_count'] < $_POST['num']){
			$this->ajaxReturn(['msg'=>'优惠劵已经抢光啦!','res'=>3,'data'=>false]);
		}
		//扣除劵库存
		$res = M('goods')->where(['g_id' => $_POST['gid']])->setDec('g_count',$_POST['num']);
		if(!$res){
			$this->ajaxReturn(['msg'=>'网络超时!','res'=>-1,'data'=>false]);
		}

		$data = array();
		$data['e_uid'] = $uid;
		$data['e_gid'] = $_POST['gid'];
		$data['e_price'] = $goods['g_price'];
		$data['e_use'] = 0;
		$data['e_create'] = time();
		$data['e_usetime'] = 0;
		//判断是有链接劵还是无链接劵
		if( empty($goods['g_url']) ){
			//写入记录
			//拉取劵的详细信息
			$grab = A('Admin/Api','Event');
			$code = $grab->getCouponCode($goods['g_couponid']);
			if(!$code){
				$this->ajaxReturn(['msg'=>'劵不足!','res'=>3,'data'=>false]);
			}
			$data['e_password'] = $code['password'];
			$data['e_link'] = $code['link'];
			$data['e_code'] = $code['code'];
		}else{
			$data['e_password'] = '';
			$data['e_link'] = $goods['g_url'];
			$data['e_code'] = '';
		}

		$res = M('exchange')->add($data);
		if(!$res){
			$this->ajaxReturn(['msg'=>'网络超时!','res'=>-1,'data'=>false]);
		}
		$this->ajaxReturn(['msg'=>'兑换成功','res'=>1,'data'=>true,'ma'=>1]);
	}

	public function award(){
//		layout(false);
//		$user = M('user')->where(['u_id'=>session('3146_uid')])->find();
		$list = M('award')->order('id asc')->select();

		//是否有资格抽奖 一周只能抽一次
		$last = M('log')->where(['l_uid'=>session('3146_uid')])->order('l_updated desc')->limit(1)->find();
		$week = date('W',$last['l_updated']);
		$now = date('W',time());
		if( $now==$week ){
			$has = 0;
		}else{
			$has = 1;
		}
		$this->assign('haschance',$has);
		$this->assign('list',$list);
//		$this->assign('user',$user);
		$this->display();
	}

	/**
	 * 抽奖
	 */
	public function lottery(){
		$uid = session('3146_uid');
		//抽奖
		M('log')->add(['l_gid'=>3146,'l_uid'=>$uid,'eventid'=>99,'l_updated'=>time()]);
		$lotteryConfig = M('lottery')->find();
		$lc = json_decode($lotteryConfig['lottery'],true);
		$rand = rand(1,10000);
		if($rand > end($lc)['max']){
			$this->ajaxReturn(['msg'=>'未中奖!','res'=>1,'data'=>true,'id'=>0]);
		}
		//判断是什么奖品
		foreach($lc as $k => $v){
			if($rand > $v['min'] && $rand <= $v['max']){
				$map['id'] = $v['id'];
				break;
			}
		}
		$award = M('award')->where($map)->find();
		if(!$award){
			$this->ajaxReturn(['msg'=>'未中奖!','res'=>1,'data'=>true,'id'=>0]);
		}
		//计算奖池数量
		if( $award['num']<1 ){
			$this->ajaxReturn(['msg'=>'未中奖!','res'=>1,'data'=>true,'id'=>0]);
		}
		M('award')->where(['id'=>$award['id']])->setDec('num',1);

		$data = array();
		$data['uid'] = $uid;
		$data['awardid'] = $award['id'];
		$data['awardname'] = $award['awardname'];
		$data['got'] = 0;
		$data['onlyKey'] = 0;
		$data['updated'] =  time();
		$data['gid'] =  0;
		if( empty($award['awardurl'] )){
			$grab = A('Admin/Api','Event');
			$code = $grab->getCouponCode($award['awardid']);
			if(!$code){
				$this->ajaxReturn(['msg'=>'未中奖!','res'=>1,'data'=>true,'id'=>0]);
			}
			$data['e_password'] =$code['password'];
			$data['e_link'] =$code['link'];
			$data['e_code'] =$code['code'];
		}else{
			$data['e_password'] = '';
			$data['e_link'] = $award['awardurl'];
			$data['e_code'] = '';
		}
		$res = M('weinner')->add($data);
		if($res){
			$this->ajaxReturn(['msg'=>'恭喜!','res'=>1,'data'=>true,'desc'=>$award['desc'],'id'=>$award['id'],'awardname'=>$award['awardname']]);
		}else{
			$this->ajaxReturn(['msg'=>'未中奖!','res'=>1,'data'=>true,'id'=>0]);
		}
	}
}