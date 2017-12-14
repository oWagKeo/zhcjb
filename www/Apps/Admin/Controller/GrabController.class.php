<?php
namespace Admin\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf-8");

class GrabController extends Controller {
	public function index(){
		$data = $this->grab();
		//批量添加数据
		if(!empty($data['add'])){
			M('goods')->addAll($data['add']);
		}
		//批量更新数据
		if(!empty($data['up'])){
			$this->saveAll('g_3146_goods',$data['up'],'g_couponid');
		}
	}
	public function grab($i = 1,$info = [],$check = ''){
		if ($check == ''){
			$c = M('goods')->field('g_couponid')->select();
			foreach ($c as $k => $v){
				$check .= ','.$v['g_couponid'];
			}
			$check = $check.',';
		}
		$Api = A('Api','Event');
		$data = $Api->getCouponList($i);
		foreach ($data as $k => $v){
			if(strpos($check,','.$v['couponId'].',') === FALSE){
				$info['add'][] = [
					'g_name' => $v['couponName'],
					'g_thum' => $v['imageUrl'],
					'g_info' => $v['instruction'],
					'g_type' => $i,
					'g_num' => $v['totalAmount'],
					'g_count' => $v['totalAmount'],
					'g_price' => 0,
					'g_show' => 0,
					'g_start' => strtotime($v['validStartDate']),
					'g_end' => strtotime($v['validEndDate']),
					'g_create' => time(),
					'g_usetype' => $v['couponForm'],
					'g_couponid' => $v['couponId'],
					'g_claim' => $v['claimLimit'],
					'g_desc' => $v['prizeDesc'],
				];
			}else{
				$info['up'][] = [
					'g_name' => $v['couponName'],
					'g_thum' => $v['imageUrl'],
					'g_info' => $v['instruction'],
					'g_type' => $i,
					'g_num' => $v['totalAmount'],
					'g_create' => time(),
					'g_start' => strtotime($v['validStartDate']),
					'g_end' => strtotime($v['validEndDate']),
					'g_usetype' => $v['couponForm'],
					'g_couponid' => $v['couponId'],
					'g_claim' => $v['claimLimit'],
					'g_desc' => $v['prizeDesc'],
				];
			}
		}
		if($i == 11){
			return $info;
		}else{
			return $this->grab($i+1,$info,$check);
		}
	}
	private function saveAll($table_name='',$data=array(),$field=''){
		$sql = 'UPDATE '.$table_name;
		$con = [];
		$con_sql = [];
		$fields = [];
		foreach ($data as $key => $value) {
			$x=0;
			foreach ($value as $k => $v) {
				if($k != $field && !$con[$x] && $x == 0){
					$con[$x] = " set {$k} = (CASE {$field} ";
				}elseif($k != $field && !$con[$x] && $x>0){
					$con[$x] = " {$k} = (CASE {$field} ";
				}
				if($k != $field){
					$temp = $value[$field];
					$con_sql[$x] .=  " WHEN '{$temp}' THEN '{$v}' ";
					$x++;
				}
			}
			$temp = $value[$field];
			if(!in_array($temp,$fields)){
				$fields[] = $temp;
			}
		}
		$num = count($con)-1;
		foreach ($con as $key => $value) {
			foreach ($con_sql as $k => $v) {
				if($k==$key&&$key<$num){
					$sql.=$value.$v.' end),';
				}elseif($k==$key&&$key==$num){
					$sql.=$value.$v.' end)';
				}
			}
		}
		$str=implode(',',$fields);
		$sql.=" where {$field} in({$str})";
		$res = M()->execute($sql);
		return $res;
	}
}