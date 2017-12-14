/**
 * Created with JetBrains WebStorm.
 * User: lsl
 * Date: 16-6-29
 * Time: 下午7:30
 * To change this template use File | Settings | File Templates.
 */
/*********************横屏以及竖屏适应屏事件处理*********************************/
var orientation= 0,brWidth,brHeight,scale,pyNum,container, w;
(function () {
    var innerWidthTmp = window.innerWidth;
    scale= 1;
    pyNum= 0;
    function rotation(){
        brWidth = document.documentElement.clientWidth;
        brHeight = document.documentElement.clientHeight;
        scale=brHeight/960;
        w=760*scale;
        container = document.getElementById("container");
        container.style.width=w+'px';
        container.style.height=brHeight+'px';
        /*document.getElementById("container2").style.height=brHeight+'px';*/
        container.style.marginLeft = (brWidth-w)/2+"px";

        pyNum=(brWidth-w)/2;
       /* container.style.display="block";*/
    }
    rotation();
//转屏事件，内部功能可以自定义
    function screenOrientationEvent(){
        if(orientation == 0){
            rotation();
            setRem();
        }
        else{
            rotation();
            setRem();

        }
    }
//横竖屏事件监听方法
    function screenOrientationListener(){
        try{
            var iw = window.innerWidth;
            //屏幕方向改变处理
            if(iw != innerWidthTmp){
                if(iw>window.innerHeight)orientation = 90;
                else orientation = 0;
                //调用转屏事件
                screenOrientationEvent();
                innerWidthTmp = iw;
            }
        } catch(e){alert(e);}
        //间隔固定事件检查是否转屏，默认500毫秒
        setTimeout(screenOrientationListener,500);
    }
//启动横竖屏事件监听
    screenOrientationListener();

    /*********************font-size(Rem)根节点的字号重置*********************************/
    var docEl = document.documentElement;
    var resize = 'orientationchange' in window ? 'orientationchange' : 'resize';
    var setRem = function () {
        var screenWidth = brWidth || brWidth || 360;
        // 760 PSD宽度(可变的)
        docEl.style.fontSize = (100 * screenWidth / 750) + 'px';
    };
    window.addEventListener('resize', setRem, false);
    setRem();
})()
/*判断客户端型号*/
var tool_browser={
    versions:function(){
        var u = navigator.userAgent, app = navigator.appVersion;
        return {
            trident: u.indexOf('Trident') > -1, //IE内核
            presto: u.indexOf('Presto') > -1, //opera内核
            webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
            gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1,//火狐内核
            mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
            ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
            android: u.indexOf('Android') > -1 || u.indexOf('Adr') > -1, //android终端
            iPhone: u.indexOf('iPhone') > -1 , //是否为iPhone或者QQHD浏览器
            iPad: u.indexOf('iPad') > -1, //是否iPad
            webApp: u.indexOf('Safari') == -1, //是否web应该程序，没有头部与底部
            weixin: u.indexOf('MicroMessenger') > -1, //是否微信 （2015-01-22新增）
            qq: u.match(/\sQQ/i) == " qq" //是否QQ
        };
    }(),
    language:(navigator.browserLanguage || navigator.language).toLowerCase()
}

//打乱数组方法
function randomArray(_array)
{
    _array.sort(function () {return ( Math.floor(Math.random()*3) ? (1) : (-1) ) });
    return _array;
}
//电话号码的正则验证
function checkPhone(data) {
    if (data == '' || data == null) {
        return false
    } else {
        var reg = /^(1[0-9])\d{9}$/;
        var result = reg.test(data);
        return result
    }
}
//检查动画是否播放到最后一帧
function checkPlayEnd(mc){
    return (mc.currentFrame==mc.timeline.duration-1);
}
/*软键盘挡住输入框的问题*/
window.addEventListener("resize", function() {
    if(document.activeElement.tagName=="INPUT" || document.activeElement.tagName=="TEXTAREA") {
        window.setTimeout(function() {
            document.activeElement.scrollIntoViewIfNeeded();
        },0);
    }
});
//根据id获取元素
function getElementById(id){
    return document.getElementById(id);
}
//获取随机rgb颜色
function getRandomColor(){
    var r=Math.floor(Math.random()*256);
    var g=Math.floor(Math.random()*256);
    var b=Math.floor(Math.random()*256);
    return "rgb("+r+','+g+','+b+")";
}
//在没有引用jquery的情况下，查找元素的所有兄弟节点，代替$().siblings()方法。
function getSiblings(elm){
    var a=[];
    var p=elm.parentNode.children;
    for(var i=0,pl=p.length;i<pl;i++){
        if( p[i] !== elm ) {
            a.push(p[i]);
        }
    }
    return a;
}
//设置用户离开页面和返回页面的方法
function setLeaveAndBack(leaveFunc,backFunc) {
    document.addEventListener("visibilitychange", function(){
        if(document.hidden){
            leaveFunc();
        }else{
            backFunc();
        }
    });
}


/*弹窗*/
window.alertOverwrite = function(txt) {
    getE("contentpage").innerHTML = txt;
    getE("alert").style.display = "block";
};
window.closeAlert=function() {
    getE('alert').style.display = "none";
};
function getE(id){
    return document.getElementById(id);
}
/*弹窗*/