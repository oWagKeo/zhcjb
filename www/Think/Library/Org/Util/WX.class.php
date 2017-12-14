<?php
/**
 * 实现微信接口相关的所有内容！
 */

class WX_LC
{
    private $id;
    private $secret;
    private $gid;
    /**
     * 微信接口对象，实现微信主要接口。
     * @param string $appId 微信公众号ID
     * @param string $secret 微信公众号密匙
     * @param string  $gid 项目id
     * @param string $wx_token 存放微信token的表(非必须)
     */
    public function __construct ($appId,$secret,$gid='',$wx_token='wx_token')
    {
        $this->id=$appId;
        $this->secret=$secret;
        $this->gid=$gid;
        $this->wx_token=$wx_token;
    }

    /**
     * 微信用户授权，一般会调用两次
     * @param string $redirect 重定向页面
     * @param string $code 为空是第一次请求，不为空时表明授权成功。
     * @param boolean $isBase 是否是基本授权。默认true;
     * @return int 是否授权成功(0==未成功，1==成功，2==已经缓存（意味着昵称需要从数据库获取）)
     */
    public function authorize($code='',$isBase=true)
    {
        $redirect="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";//授权后重定向
        $type=$isBase?"snsapi_base":"snsapi_userinfo";
        $s=new S($this->gid);
        if($code=="")
        {
            if($s->getOpenId()==null)
            {
                echo "<script>window.open('https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->id."&redirect_uri=".$redirect."&response_type=code&scope=".$type."#wechat_redirect','_self');</script>";
            }else{
                return 2;
            }
        }else{
            $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->id."&secret=".$this->secret."&code=".$code."&grant_type=authorization_code";
            $dataStr=file_get_contents($url);
            $data=json_decode($dataStr);

            if($data->errcode == 40029 || $data->errcode == '40029' || $data->openid == undefined || $data->openid == 'undefined')
            {
                echo "<script>window.open('https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->id."&redirect_uri=".$redirect."&response_type=code&scope=".$type."#wechat_redirect','_self');</script>";
            }else{
                $openid=$data->openid;
                $s->setOpenId($openid);
                $s->setToken($data->access_token);
                return 1;
            }
        }
        return 0;
    }

    /**
     * snsapi_userinfo授权之后，获取用户常用信息
     * @param string $access_token 授权时的token,$s->getToken();
     * @param string $openid opneId $s->getOpenId();
     * @return mixed 未筛选的官方返回OBJECT。
     */
    public function getUserSimpleInfo($access_token,$openid){
        $url="https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
        $dataStr=file_get_contents($url);
        $data=json_decode($dataStr);
        return $data;
    }

    /**
     * 获取微信分享配置文件
     * @param array $jsApiList 接口列表，为了灵活配置作为参数传进来。默认值：
     * 				array(
    'checkJsApi',
    'onMenuShareTimeline',
    'onMenuShareAppMessage',
    'onMenuShareQQ',
    'onMenuShareWeibo',
    'hideMenuItems',
    'showMenuItems',
    'chooseImage'
    )
     * @return array  array   微信分享配置。
     */
    public function getSharedConfig($jsApiList=null){
        if($jsApiList==null)
        {
            $jsApiList=array(
                'checkJsApi',
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
                'onMenuShareQQ',
                'onMenuShareWeibo',
                'hideMenuItems',
                'showMenuItems',
                'hideAllNonBaseMenuItem',
                'chooseImage'
            );
        }
        $jsapiTicket = $this->getJSAPI_ticket();
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $timestamp = time();
        $nonceStr = $this->createNonceStr();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=".$jsapiTicket."&noncestr=".$nonceStr."&timestamp=".$timestamp."&url=".$url;
        $signature = sha1($string);
        $config=array(
            'debug'=>false,
            'appId'=> $this->id,
            'timestamp'=> $timestamp,
            'nonceStr'=> $nonceStr,
            'signature'=> $signature,
            'jsApiList'=> $jsApiList
        );
        //$wx_config=json_encode($config);
        return $config;
    }

    /**
     * 获取用户详细信息
     * @param string $openid
     * @return array
     */
    public function getUserInfo($openid){
        $Token=$this->getToken();
        $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$Token."&openid=".$openid."&lang=zh_CN";
        $dataStr=file_get_contents($url);
        $data=json_decode($dataStr);
        return $data;
    }

    /**
     * 获取token
     * @return string
     */
    private  function getToken(){
        $this->connectMysql();
        $t=time();
        $sql="SELECT * FROM `$this->wx_token` WHERE `id` =1";
        $result=mysql_query($sql);
        $token="";
        if($result)
        {
            if($row=mysql_fetch_array($result))
            {
                if($t-$row['updated']>=6000)
                {
                    $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->id."&secret=".$this->secret;
                    $dataStr=file_get_contents($url);
                    $data=json_decode($dataStr);
                    $token=$data->access_token;
                    $sql="UPDATE `$this->wx_token` SET `token` ='$token',`updated` ='$t' WHERE `id` = 1;";
                    mysql_query($sql);
                }else{
                    $token=$row['token'];
                }
            }
        }
        return $token;
    }

    /**
     * 获取jsapi_ticket
     * @return string
     */
    private  function getJSAPI_ticket(){
        $this->connectMysql();
        $t=time();
        $sql="SELECT * FROM `$this->wx_token` WHERE `id` = 2;";
        $result=mysql_query($sql);
        $token="";
        if($result)
        {
            if($row=mysql_fetch_array($result))
            {
                if($t-$row['updated']>=6000)
                {
                    $token=$this->getToken();
                    $url="https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$token."&type=jsapi";
                    $dataStr=file_get_contents($url);
                    //echo "GOT=>".$dataStr."</br>";
                    $data=json_decode($dataStr);
                    $token=$data->ticket;
                    //echo "GOT=>".$token."</br>";
                    $sql="UPDATE `$this->wx_token` SET `token` ='$token',`updated` ='$t' WHERE `id` = 2;";
                    $result=mysql_query($sql);
                    if($result)
                    {
                        //echo "成功";
                    }
                }else{
                    $token=$row['token'];
                }
            }
        }
        return $token;
    }

    /**
     * 开启数据库
     */
    private function connectMysql(){
        include_once 'ConnDb.php';
        $db=new ConnDb();
        $db->getConnId();
    }

    /**
     * 创建一个随机字符串
     * @param int $length 字符串长度
     * @return string
     */
    private function createNonceStr($length = 10) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}