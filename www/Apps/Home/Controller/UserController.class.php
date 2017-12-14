<?php
namespace Home\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf-8");
class UserController extends CommonController {
    public function user(){
        $info = M('user')->where(['u_id'=>session('3146_uid')])->find();
        $this->assign('info',$info);
        $pr = C('DB_PREFIX');

        $type = isset($_GET['type']) ? $_GET['type'] : 1;
        if($type == 1){
            $goods = M('exchange')->join($pr.'goods a on '.$pr.'exchange.e_gid = a.g_id')->order('e_id desc')->where(['e_uid'=>session('3146_uid'),'e_use'=>0])->select();
        }else{
            $goods = M('weinner as a')->field('a.e_link,a.id,b.awardname,b.awardthum3,b.desc')
                    ->join($pr.'award as b on a.awardid = b.id')->order('a.id desc')->where(['a.uid'=>session('3146_uid'),'a.got'=>0])->select();
        }

        $g_sum = M('exchange')->where(['e_uid'=>session('3146_uid'),'e_use'=>0])->count();
        $a_sum = M('weinner')->where(['uid'=>session('3146_uid'),'got'=>0])->count();
        $this->assign('goods',$goods);
        $this->assign('g_sum',$g_sum);
        $this->assign('a_sum',$a_sum);
        $this->display();
    }
 /*   public function my_goods(){

        $this->display();
    }*/
    public function goods_info(){
        $data = M('exchange')->where(['e_id' => $_GET['eid']])->find();
        $info = M('goods')->where(['g_id'=>$data['e_gid']])->find();
        $this->assign('info',$info);
        $this->assign('data',$data);
        $this->display();
    }

    public function award_info(){
        $data = M('weinner')->field('id,e_code,e_password,e_link,awardid')->where(['id' => $_GET['id']])->find();
        $info = M('award')->where(['id'=>$data['awardid']])->find();
        $this->assign('info',$info);
        $this->assign('data',$data);
        $this->display();
    }

    public function help(){
        $info = file_get_contents('help.txt');
        $this->assign('info',$info);
        $this->display();
    }

    /**
     * 使用优惠券
     */
    public function use_goods(){
        //判断是奖品还是优惠券
        if( $_POST['type'] == 'goods'){
            $check = M('exchange')->where(['e_id'=>$_POST['eid']])->find();
            if($check['e_uid'] != session('3146_uid')){
                $this->ajaxReturn(['msg'=>'非法操作!','res'=>-1,'data'=>false]);
            }
            $res = M('exchange')->where(['e_id'=>$_POST['eid']])->save(['e_use'=>1,'e_usetime'=>time()]);
            $link = $check['e_link'];
        }else{
            $check = M('weinner')->where(['id'=>$_POST['eid']])->find();
            if( $check['uid'] != session('3146_uid') ){
                $this->ajaxReturn(['msg'=>'非法操作!','res'=>-1,'data'=>false]);
            }
            $res = M('weinner')->where(['id'=>$_POST['eid']])->save(['got'=>1]);
            $link = $check['e_link'];
        }
        if(!$res){
            $this->ajaxReturn(['msg'=>'网络超时!','res'=>-1,'data'=>false]);
        }
        $this->ajaxReturn(['msg'=>'使用成功','res'=>1,'data'=>true,'link'=>$link]);
    }
}
