<?php
namespace Home\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf-8");

class CommonController extends Controller {
    public function _initialize(){
        //判断登录状态
        if( !session('3146_uid') ){
            $this->redirect('Logreg/register');
        }
        $nav = ['音乐阅读','电商购物','旅游出行','生活服务','视频演出','餐饮外卖',];
        $this->assign('nav',$nav);

    }
    public function authorize(){

        $wx = new \WX_LC('','wx91660942fa7be4c2');
        $code = $_GET['code'];
        if($code==''){
            $wx->getCode();//'snsapi_base'
        }else{
            $data = $wx->getOpenid($code);
            if($data->errcode){
                $wx->getCode();
                exit();
            }
            $user['u_openid'] = $data->openid;
            $info = $wx->getUserSimpleInfo($data->access_token,  $user['u_openid']);
            $user['u_nick'] = $info->nickname;
            $user['u_headimg'] = $info->headimgurl;
        }
        if(!$user){
            exit();
        }
        return $user;
    }
}
function filterEmoji($str){
    $str = preg_replace_callback(
        '/./u',
        function (array $match) {
            return strlen($match[0]) >= 4 ? '' : $match[0];
        },
        $str);
    return $str;
}
/*function filterEmoji($str) {
    $str = preg_replace_callback('/./u', "handler", $str);
    return $str;
}
function handler($match) {
    return strlen($match[0]) >= 4 ? '' : $match[0];
}*/
