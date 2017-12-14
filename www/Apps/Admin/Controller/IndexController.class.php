<?php
namespace Admin\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf-8");
class IndexController extends CommonController {
	public $type = ['音乐阅读','电商购物','旅游出行','生活服务','视频演出','餐饮外卖',];
	public function index(){
		$strTimeToString = "000111222334455556666660";
		$strWenhou = array('夜深了！','凌晨了！','早上好！','上午好！','中午好！','下午好！','晚上好！');
		$we = $strWenhou[(int)$strTimeToString[(int)date('G',time())]];
		$this->assign('we',$we);
		$this->display();
	}
	/**
	 * 修改密码
	 */
	public function rev_pwd(){
		$this->assign('nav',$this->nowNav([['管理员修改密码','']]));
		$this->display();
	}
	public function rev_pwd_save(){
		$old = I('post.old');
		$new = I('post.new');
		$con = I('post.con');
		if($new != $con){
			$this->error('两次密码输入不一样','',1);
		}
		$map = ['user_id' => session('cdlchd_adminid'),'password' => sha1($old)];
		$ck = M('admin')->where($map)->find();
		if(!$ck){
			$this->error('密码错误','',1);
		}
		$res = M('admin')->where('user_id ='.session('cdlchd_adminid'))->save(['password' => sha1($new)]);
		$this->admin_log('修改密码');
		if($res){
			$this->error('修改成功','index',1);
		}else{
			$this->error('修改失败','',1);
		}
	}
	/**
	 * 用户管理
	 */
	public function user_list(){
		$map['u_name'] = ['exp','is not null'];
		if(isset($_GET['user']) && $_GET['user'] != ''){
			$map['u_name'] = $_GET['user'];
			$search['user'] = $_GET['user'];
		}
		if(isset($_GET['phone']) && $_GET['phone'] != ''){
			$map['u_phone'] = $_GET['phone'];
			$search['phone'] = $_GET['phone'];
		}
		if($_GET['reg'] != 'all' && $_GET['reg'] != ''){
			if($_GET['reg'] == 1){
				$map['u_name'] = ['exp','is not null'];
			}else{
				$map['u_name'] = ['exp','is null'];
			}
			$search['reg'] = $_GET['reg'];
		}
		$count = M('user')->where($map)->count('u_id');
		$Page = new \Think\Page($count);// 实例化分页类
		$info = M('user')->where($map)->order('u_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign("page", $Page->show());
		$this->assign("count",$count);
		$this->assign("search",$search);
		$this->assign("info",$info);
		$this->display();
	}
	public function user_info(){
		$info = M('user')->where(['u_id'=>$_GET['uid']])->find();
		$this->assign('nav',$this->nowNav([['用户管理'],['用户管理',U('Index/user_list')],['编辑用户信息']]));
		$this->assign('info',$info);
		$this->display();
	}
	public function user_save(){
		$data = [
			'u_name' => $_POST['name'],
			'u_cardno' => $_POST['cardno'],
			'u_phone' => $_POST['phone'],
			'u_score' => $_POST['score'],
			'u_lottery' => $_POST['lottery'],
		];
		$res = M('user')->where(['u_id' => $_POST['id']])->save($data);
		if($res){
			$this->success('保存成功','user_list',1);
		}else{
			$this->error('保存失败','',1);
		}
	}
	public function get_user_score(){
		$score = M('user')->where(['u_id'=>$_POST['uid']])->getField('u_score');
		$pr = C('DB_PREFIX');
		$log = M('exchange')->alias('b')->join($pr.'goods a on b.e_gid = a.g_id')->where(['e_uid'=>$_POST['uid']])->select();
		foreach ($log as $k => $v){
			$log[$k]['e_create'] = date('Y-m-d H:i:s',$v['e_create']);
		}
		$this->ajaxReturn(['score'=>$score,'log'=>$log]);
	}
	public function user_remove(){
		$res = M('user')->where(['u_id'=>$_POST['uid']])->delete();
		$this->ajaxReturn($res);
	}
	/**
	 * 优惠劵
	 */
	public function goods_list(){
		$map['1'] = '1';
		if(isset($_GET['name']) && $_GET['name'] != ''){
			$map['g_name'] = $_GET['name'];
			$search['name'] = $_GET['name'];
		}
		if(isset($_GET['type']) && $_GET['type'] != 'all'){
			if($_GET['type'] == 'free'){
				$map['g_free'] = ['neq',0];
			}else{
				$map['g_type'] = $_GET['type'];
			}
			$search['type'] = $_GET['type'];
		}
		$count = M('goods')->where($map)->count('g_id');
		$Page = new \Think\Page($count);// 实例化分页类
		$info = M('goods')->where($map)->order('g_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign("page", $Page->show());
		$this->assign("count",$count);
		$this->assign("search",$search);
		$this->assign("info",$info);
		$this->assign("type",$this->type);
		$this->display();
	}

	/**
	 * 添加优惠券
	 */
	public function goods_add(){
		$this->assign("action",'add');
		$this->assign("type",$this->type);
		$this->assign('time',time());
		$this->display('goods_info');
	}

	/**
	 * 添加优惠券
	 */
	public function goods_add_save(){
		$data = [
			'g_name' => $_POST['name'],
			'g_desc' => $_POST['desc'],
			'g_info' => $_POST['info'],
			'g_type' => $_POST['type'],
			'g_num' => $_POST['count'],
			'g_count' => $_POST['count'],
			'g_free' => 0,
			'g_price' => 0,
			'g_show' => $_POST['show'],
			'g_start' => strtotime($_POST['start']),
			'g_end' => strtotime($_POST['end']),
			'g_term' => $_POST['term'],
			'g_create' => time(),
			'g_couponid' => $_POST['couponid'],
			'g_usetype' => 0,
			'g_claim' => -1,
			'g_url' => $_POST['g_url']
		];
		$upload = new \Think\Upload();                   // 实例化上传类
		$upload->exts       =     array('jpg','png','jpeg');          // 设置附件上传类型
		$upload->rootPath   =     './Public/img/goods/'; // 设置附件上传根目录
		$upload->saveName   =     date('YmdHis').'_'.rand(1000,9999);              //上传文件名
		$upload->autoSub    =     false;                  //不创建子目录
		$upload->replace    =     true;                   //重复覆盖
		$upload->savePath   =      ''; // 设置附件上传（子）目录
		// 上传文件
		$info = $upload->upload();
		if(!$info) {// 上传错误提示错误信息
			$this->error($upload->getError());
		}else{// 上传成功
			$data['g_thum'] = '/Public/img/goods/'.$info['pic']['savename'];
		}
		$res = M('goods')->add($data);
		if($res){
			$this->success('保存成功','goods_list',1);
		}else{
			$this->error('保存失败','',1);
		}
	}
	public function goods_edit(){
		$info = M('goods')->where(['g_id' => $_GET['gid']])->find();
		$this->assign("action",'edit');
		$this->assign("info",$info);
		$this->assign('nav',$this->nowNav([['优惠劵管理'],['优惠劵列表',U('Index/goods_list')],['编辑优惠劵']]));
		$this->assign("type",$this->type);
		$this->display('goods_info');
	}

	public function goods_edit_save(){
		$data = [
			'g_name' => $_POST['name'],
			'g_desc' => $_POST['desc'],
			'g_info' => $_POST['info'],
			'g_type' => $_POST['type'],
			'g_count' => $_POST['count'],
			'g_free' => $_POST['free'],
			'g_price' => $_POST['price'],
			'g_show' => $_POST['show'],
			'g_start' => strtotime($_POST['start']),
			'g_end' => strtotime($_POST['end']),
			'g_term' => $_POST['term'],
			'g_url' => $_POST['g_url'],
		];
		if($_FILES['pic']['name'] != ''){
			$upload = new \Think\Upload();                   // 实例化上传类
			$upload->maxSize   =     2097152*4;               // 设置附件上传大小
			//$upload->exts       =     array('jpg,png,jpeg');          // 设置附件上传类型
			$upload->rootPath  =     './Public/img/goods/'; // 设置附件上传根目录
			$upload->saveName  =     date('YmdHis').'_'.rand(1000,9999);              //上传文件名
			$upload->autoSub   =     false;                  //不创建子目录
			$upload->replace   =     true;                   //重复覆盖
			$upload->savePath  =      ''; // 设置附件上传（子）目录
			// 上传文件
			$info = $upload->upload();
			if(!$info) {// 上传错误提示错误信息
				$this->error($upload->getError());
			}else{// 上传成功
				$data['g_thum'] =  '/Public/img/goods/'.$info['pic']['savename'];
			}
		}
		$res = M('goods')->where(['g_id'=>$_POST['id']])->save($data);
		if($res){
			$this->success('编辑成功','',1);
		}else{
			$this->error('编辑失败','',1);
		}
	}

	public function goods_remove(){
		$id = M('goods')->where(['g_id'=>$_POST['uid']])->delete();
		if( $id ){
			$this->ajaxReturn(['msg'=>'删除成功','state'=>1]);
		}else{
			$this->ajaxReturn(['msg'=>'删除失败','state'=>0]);
		}
	}
	/**
	 * 数据统计
	 */
	public function user_count(){
		if(!isset($_GET['start']) || !isset($_GET['end'])){
			$start = time()-7*86400;
			$end = time();
		}else{
			$start = strtotime($_GET['start']);
			$end = strtotime($_GET['end']);
		}
		$map_n = [
			'u_create' => ['lt',$end],
			'u_create' => ['gt',$start],
			'u_name' => ['exp','is null']
		];
		$map_u = [
			'u_create' => ['lt',$end],
			'u_create' => ['gt',$start],
			'u_name' => ['exp','is not null']
		];

		$n = M('user')->where($map_n)->group("FROM_UNIXTIME(u_create,'%Y-%m-%d')")->field('count(u_id) a,FROM_UNIXTIME(u_create,\'%Y-%m-%d\') daytime')->select();
		$u = M('user')->where($map_u)->group("FROM_UNIXTIME(u_create,'%Y-%m-%d')")->field('count(u_id) a,FROM_UNIXTIME(u_create,\'%Y-%m-%d\') daytime')->select();

		for ($i = $start;$i<=$end;$i += 86400){
			if(strstr(json_encode($n),date('Y-m-d',$i))){
				$data_n[] = (int)end(explode('{"a":"',strstr(json_encode($n),'","daytime":"'.date('Y-m-d',$i),true)));
			}else{
				$data_n[] = 0;
			}
			if(strstr(json_encode($u),date('Y-m-d',$i))){
				$data_u[] = (int)end(explode('{"a":"',strstr(json_encode($u),'","daytime":"'.date('Y-m-d',$i),true)));
			}else{
				$data_u[] = 0;
			}
			$date[] = date('m-d',$i);
		}
		$this->assign("search",['start'=>$start,'end'=>$end]);
		$this->assign("date",json_encode($date));
		$this->assign("n",json_encode($data_n));
		$this->assign("u",json_encode($data_u));
		$this->display();
	}

	/**
	 * 兑换统计
	 */
	public function exchange_count(){
		if(!isset($_GET['start']) || !isset($_GET['end'])){
			$start = time()-7*86400;
			$end = time();
		}else{
			$start = strtotime($_GET['start']);
			$end = strtotime($_GET['end']);
		}
		$map_free = [
			'e_create' => ['exp','between '.$start.' and '.$end],
			'e_price' => ['eq',0]
		];
		$map_price = [
			'e_create' => ['exp','between '.$start.' and '.$end],
			'e_price' => ['neq',0]
		];
		$map_lottery = [
			'l_updated' => ['exp','between '.$start.' and '.$end],
			'eventId' => ['eq','99']
		];

		$free = M('exchange')->where($map_free)->group("FROM_UNIXTIME(e_create,'%Y-%m-%d')")->field('count(e_id) a,FROM_UNIXTIME(e_create,\'%Y-%m-%d\') daytime')->select();
		$price = M('exchange')->where($map_price)->group("FROM_UNIXTIME(e_create,'%Y-%m-%d')")->field('count(e_id) a,FROM_UNIXTIME(e_create,\'%Y-%m-%d\') daytime')->select();
		$lottery = M('log')->where($map_lottery)->group("FROM_UNIXTIME(l_updated,'%Y-%m-%d')")->field('count(l_id) a,FROM_UNIXTIME(l_updated,\'%Y-%m-%d\') daytime')->select();

		for ($i = $start;$i<=$end;$i += 86400){
			if(strstr(json_encode($free),date('Y-m-d',$i))){
				$data_free[] = (int)end(explode('{"a":"',strstr(json_encode($free),'","daytime":"'.date('Y-m-d',$i),true)));
			}else{
				$data_free[] = 0;
			}
			if(strstr(json_encode($price),date('Y-m-d',$i))){
				$data_price[] = (int)end(explode('{"a":"',strstr(json_encode($price),'","daytime":"'.date('Y-m-d',$i),true)));
			}else{
				$data_price[] = 0;
			}
			if(strstr(json_encode($lottery),date('Y-m-d',$i))){
				$data_lottery[] = (int)end(explode('{"a":"',strstr(json_encode($lottery),'","daytime":"'.date('Y-m-d',$i),true)));
			}else{
				$data_lottery[] = 0;
			}
			$date[] = date('m-d',$i);
		}
		$this->assign("search",['start'=>$start,'end'=>$end]);
		$this->assign("date",json_encode($date));
		$this->assign("free",json_encode($data_free));
		$this->assign("price",json_encode($data_price));
		$this->assign("lottery",json_encode($data_lottery));
		$this->display();
	}

	/**
	 * 兑换比例
	 */
	public function exchange_pie(){
		if(!isset($_GET['start']) || !isset($_GET['end'])){
			$start = time()-7*86400;
			$end = time();
		}else{
			$start = strtotime($_GET['start']);
			$end = strtotime($_GET['end']);
		}
		$map = [
			'e_create' => ['exp','between '.$start.' and '.$end],
		];
		$all = M('exchange')->where($map)->count('e_id');
		$pr = C('DB_PREFIX');
		$data = M('exchange')->where($map)->group('e_gid')->field('g_name,g_type,e_gid,count(e_gid) c')->alias('b')->join($pr.'goods a on b.e_gid = a.g_id')->select();
		//取出所有分类
		$group = M('exchange')->where($map)->group('g_type')->field('g_type,count(e_gid) c')->alias('b')->join($pr.'goods a on b.e_gid = a.g_id')->select();
		foreach ($data as $k => $v){
			$arr[$v['g_type']]['name'][] = $v['g_name'].'('.$v['c'].'次)';
			$arr[$v['g_type']]['c'][] = $v['c'];
		}
		foreach ($group as $k => $v){
			$num = [];
			foreach ($arr[$v['g_type']]['c'] as $key => $val){
				$num[] = round($val/$all,4)*100;
			}
			$info[] = [
				'y' => round($v['c']/$all,4)*100,
				//'color' => 'color['.$k.']',
				'drilldown' => [
					'name' => $this->type[$v['g_type']-1],
					'categories' => $arr[$v['g_type']]['name'],
					'data' => $num,
					//'color' => 'color['.$k.']'
				]
			];
			$type[] = $this->type[$v['g_type']-1].'('.$v['c'].'次)';
		}
		$this->assign('info',json_encode($info));
		$this->assign('type',json_encode($type));
		$this->assign("search",['start'=>$start,'end'=>$end]);
		$this->display();
	}

	/**
	 * 兑换记录
	 */
	public function log(){
		$map['1'] = '1';
		if(isset($_GET['user']) && $_GET['user'] != ''){
			$map['u_name'] = $_GET['user'];
			$search['user'] = $_GET['user'];
		}
		if(isset($_GET['name']) && $_GET['name'] != ''){
			$map['g_name'] = $_GET['name'];
			$search['name'] = $_GET['name'];
		}
		if(isset($_GET['type']) && $_GET['type'] != 'all'){
			if($_GET['type'] == 'free'){
				$map['g_price'] = 0;
			}else{
				$map['g_type'] = $_GET['type'];
			}
			$search['type'] = $_GET['type'];
		}
		$pr = C('DB_PREFIX');
		$count = M('exchange')->join($pr.'goods a on '.$pr.'exchange.e_gid = a.g_id')->join($pr.'user b on '.$pr.'exchange.e_uid = b.u_id')->where($map)->count('g_id');
		$Page = new \Think\Page($count);// 实例化分页类
		$info = M('exchange')->join($pr.'goods a on '.$pr.'exchange.e_gid = a.g_id')->join($pr.'user b on '.$pr.'exchange.e_uid = b.u_id')->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign("page", $Page->show());
		$this->assign("count",$count);
		$this->assign("search",$search);
		$this->assign("info",$info);
		$this->assign("type",$this->type);
		$this->display();
	}


	/**
	 * 抽奖商品列表
	 */
	public function award_list(){
		$list = M('award')->select();
		$this->assign('list',$list);
		$this->display();
	}

	/**
	 * 添加奖品
	 */
	public function award_add(){
//		$info = M('lottery')->where('id=1')->find();
//		print_r(json_decode($info['lottery']));die();
		$this->assign('action','add');
		$this->display('award_info');
	}

	public function award_add_save(){

		//判断概率和是否超过10000
		$sum = M('award')->sum('chance');
		if( $sum+$_POST['chance']>10000 ){
			$this->error('概率之和应该小于10000','',1);
		}
		$data = array();
		$data['awardid'] = $_POST['awardid'];
		$data['awardname'] = $_POST['name'];
		$data['num'] = $_POST['num'];
		$data['chance'] = $_POST['chance'];
		$data['desc'] = $_POST['desc'];
		$data['awardinfo'] = $_POST['awardinfo'];
		$data['awardurl'] = $_POST['awardurl'];

		$upload = new \Think\Upload();                   // 实例化上传类
		$upload->exts       =     array('jpg','png','jpeg');          // 设置附件上传类型
		$upload->rootPath   =     './Public/img/award/'; // 设置附件上传根目录
		$upload->autoSub    =     false;                  //不创建子目录
		$upload->replace    =     true;                   //重复覆盖
		$upload->savePath   =      ''; // 设置附件上传（子）目录
		// 上传文件
		$info   =   $upload->upload();
		if(!$info) {// 上传错误提示错误信息
			$this->error($upload->getError());
		}else{
//			print_r($info);die();
			for( $i = 0; $i < count($info); $i++ ){
				$data['awardthum'.($i+1)] = '/Public/img/award/'.$info[$i]['savename'];
			}
		}
		M('award')->startTrans();
		$insert = M('award')->add($data);
		if( $insert ){
			$list = M('award')->select();
			$data_arr = [];
			foreach( $list as $k => $v ){
				//计算随机数范围
				if($k == 0){
					$data_arr[$k]['min'] = 0;
					$data_arr[$k]['max'] = $v['chance'];
				}else{
					$data_arr[$k]['min'] = $data_arr[$k-1]['max'];
					$data_arr[$k]['max'] = $data_arr[$k-1]['max']+$v['chance'];
				}
				$data_arr[$k]['id'] = $v['id'];
				$data_arr[$k]['award'] = $v['awardname'];
				$data_arr[$k]['b'] = $v['chance'];
			}
			$res = M('lottery')->where('id = 1')->save(['lottery' => json_encode($data_arr)]);
			$this->success('添加成功！','award_list',1);
			M('award')->commit();
		}else{
			$this->error('添加失败！','award_list',1);
			M('award')->rollback();
		}
	}

	/**
	 * 编辑奖品
	 */
	public function award_edit(){
		$info = M('award')->where(['id'=>$_GET['id']])->find();
		$this->assign('info',$info);
		$this->assign('nav',$this->nowNav([['抽奖设置'],['奖品列表',U('Index/award_list')],['编辑奖品']]));
		$this->assign('action','edit');
		$this->display('award_info');
	}
	public function award_edit_save(){
		//判断概率和是否超过10000
		$where = array();
		$where['id'] = array('NEQ',$_POST['id']);
		$sum = M('award')->where($where)->sum('chance');
		if( $sum+$_POST['chance']>10000 ){
			$this->error('概率之和应该小于10000','',1);
		}
		$data = array();
		$data['awardid'] = $_POST['awardid'];
		$data['awardname'] = $_POST['name'];
		$data['num'] = $_POST['num'];
		$data['chance'] = $_POST['chance'];
		$data['desc'] = $_POST['desc'];
		$data['awardinfo'] = $_POST['awardinfo'];
		if( $_FILES['pic']['name'][0]||$_FILES['pic']['name'][1]||$_FILES['pic']['name'][2]){
			$upload = new \Think\Upload();                   // 实例化上传类
			$upload->exts = array('jpg', 'png', 'jpeg');          // 设置附件上传类型
			$upload->rootPath = './Public/img/award/'; // 设置附件上传根目录
			$upload->autoSub = false;                  //不创建子目录
			$upload->replace = true;                   //重复覆盖
			$upload->savePath = ''; // 设置附件上传（子）目录
			// 上传文件
			$info = $upload->upload();
			if (!$info) {// 上传错误提示错误信息
				$this->error($upload->getError());
			} else {
				foreach( $info as $k=>$v ){
					$data['awardthum'.( $k+1)] = '/Public/img/award/'.$v['savename'];
				}
			}
		}
		$insert = M('award')->where(['id'=>$_POST['id']])->save($data);
		if( $insert ){
			$list = M('award')->select();
			$data_arr = [];
			foreach( $list as $k => $v ){
				//计算随机数范围
				if($k == 0){
					$data_arr[$k]['min'] = 0;
					$data_arr[$k]['max'] = $v['chance'];
				}else{
					$data_arr[$k]['min'] = $data_arr[$k-1]['max'];
					$data_arr[$k]['max'] = $data_arr[$k-1]['max']+$v['chance'];
				}
				$data_arr[$k]['id'] = $v['id'];
				$data_arr[$k]['award'] = $v['awardname'];
				$data_arr[$k]['b'] = $v['chance'];
			}
			M('lottery')->where('id = 1')->save(['lottery' => json_encode($data_arr)]);

			$this->success('编辑成功！','award_list',1);
		}else{
			$this->error('编辑失败！','award_list',1);
		}
	}

	/**
	 * 删除奖品
	 */
	public function award_remove(){
		$id = M('award')->where(['id'=>$_POST['uid']])->delete();
		if( $id ){
			$this->ajaxReturn(['msg'=>'删除成功！','state'=>1]);
		}else {
			$this->ajaxReturn(['msg' => '删除失败！', 'state' => 0]);
		}
	}

	/**
	 * 抽奖
	 */
	public function award(){
		//echo  json_encode($data);
		$info = M('lottery')->where('id = 1')->getField('lottery');
		$this->assign('info',json_decode($info,true));
//		print_r(json_decode($info,true));die();
		$this->display();
	}
	public function award_save(){
		$data = [];
		$ck = 0;
		foreach ($_POST['b'] as $k => $v){
			//计算随机数范围
			if($k == 0){
				$data[$k]['min'] = 0;
				$data[$k]['max'] = $v;
			}else{
				$data[$k]['min'] = $data[$k-1]['max'];
				$data[$k]['max'] = $data[$k-1]['max']+$v;
			}
			$data[$k]['a'] = $k+1;
			$data[$k]['award'] = $_POST['award'][$k];
			$data[$k]['b'] = $v;
			$ck += $v;
		}
		if($ck > 10000){
			$this->error('中奖率总和应小于10000');
		}
		$res = M('lottery')->where('id = 1')->save(['lottery' => json_encode($data)]);
		if($res){
			$this->success('保存成功','',1);
		}else{
			$this->success('保存失败','',1);
		}
	}
	public function pool(){
		$this->display();
	}
	public function pool_save(){
		$time = time();
		$awardNum = $_POST['num'];
		$awardName = array('','携程','途牛','木鸟短租','OFO','摩拜','唯品会');
		$gid = array('',15,4,8,3,14,1);
		$awardARR = [];
		for($i=0;$i<count($awardNum);$i++){
			for ($j=0;$j<$awardNum[$i];$j++){
				array_push($awardARR, $i+1);
			}
		}
		$num = rand(1234567, 9876541)+rand(1234567, 9876541);
		$data = [];
		for ($i=0;$i<count($awardARR);$i++){
			$data[] = [
				'awardid' => $awardARR[$i],
				'awardname' => $awardName[$awardARR[$i]],
				'got' => 0,
				'uId' => 0,
				'onlyKey' => md5($time.$num.$i),
				'gid' => $gid[$awardARR[$i]],
			];

		}
		$res = M('award')->addAll($data);
		if($res){
			$this->success('成功生成'.count($data));
		}else{
			$this->error('成功失败'.$res);
		}
	}
	public function overplus(){
		$pr = C('DB_PREFIX');
		$info = M()->query('SELECT tb.awardname,a,b FROM (SELECT awardname FROM '.$pr.'award GROUP BY awardid) tb LEFT JOIN (SELECT awardname,count(id) a FROM '.$pr.'award where got = 0 GROUP BY awardid) tb_a ON tb.awardname = tb_a.awardname LEFT JOIN (SELECT awardname,count(id) b FROM '.$pr.'award where got = 1 GROUP BY awardid) tb_b ON tb.awardname = tb_b.awardname');
		$this->assign('info',$info);
		$this->display();
	}
	public function award_log(){
		$pr = C('DB_PREFIX');
		$count = M('weinner')->count('id');
		$Page = new \Think\Page($count);// 实例化分页类
		$info = M('weinner')->alias('a')->join($pr.'user b on a.uid = b.u_id','LEFT')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('count',$count);
		$this->assign('page',$Page->show());
		$this->assign('info',$info);
		$this->display();
	}
	public function help(){
		$info = file_get_contents('help.txt');
		$this->assign('info',$info);
		$this->display();
	}
	public function help_save(){
		$res = file_put_contents('help.txt',$_POST['info']);
		$this->ajaxReturn($res);
	}
}
