/**
 * Created by c-017 on 2017/9/6.
 */
//验证码倒计时
var isRun=false;
var time1;
var time2;
var  isMobile=/^1[34578]\d{9}$/;
function timer(time){
    var date1=new Date();
    time1=parseInt(date1.getTime()/1000);
    var sh=setInterval(function(){
        var date2=new Date();
        time2=parseInt(date2.getTime()/1000);
        if(time2-time1<time){
            $(".getCode").text(time-(time2-time1)+"秒");

        }
        else{
            $(".getCode").text("重新发送");
            isRun=false;
        }
    },1000);
}

$(".getCode").click(function () {
    if(isRun){
        return;
    }
    if(!isMobile.test($(".phone").val())){
        alertOverwrite("请输入正确的电话号码")
    }else{
        $.ajax({
            type:"Post",
            url:__url__+"/get_code",
            data:{
                phone:$(".phone").val()
            },
            success:function(data){
                if( data.data ){
                    $('.code').val(data.msg);
                    timer(60);
                    isRun=true;
                }
                alertOverwrite(data.msg);
            }
        })
    }

});

//手机登录注册
$(".loginBtn").click(function(){
    if(!isMobile.test($(".phone").val())||$(".code").val()==""){
        alertOverwrite("请完整填写您的信息");
    }else{
        $.ajax({
            type:"Post",
            url:__url__+"/register_save",
            data:{
                phone:$(".phone").val(),
                code:$(".code").val()
            },
            success:function(data){
                console.log(data);
                if(data.data){
                    window.location.href=__module__+"/Index/index/pageId/1"
                } else {
                    alertOverwrite(data.msg);
                }
            }
        })
    }
});

$(".cardNum2").blur(function(){
    if($(".cardNum1").val()!=$(".cardNum2").val()){
        alertOverwrite("两次不一致");
        $(".cardNum2").val("")
    }
});

//奖券兑换
$(".exchange").click(function(){
   if($(".numInput").val()==""){
       alertOverwrite("请输入兑换数量");
   }else{
       $.ajax({
           type:"post",
           url:__url__+"/buy",
           data:{
               gid:$(this).attr("data-id"),
               num:$(".numInput").val(),
               mark:$('#mark').val()
           },
           success:function(data){
               if(data.data){
                   window.location.href=__module__+'/User/user/pageId/3/type/'+data.ma;
               }else{
                   alertOverwrite(data.msg);
               }
           }
       })
   }

});

$(".useBtn").click(function () {
    $.ajax({
        type:"post",
        url:__url__+"/use_goods",
        data:{
            eid:$(this).attr("data-id"),
            type:$('.typeInput').val()
        },
        success:function(data){
            if(data.data){
                window.location = data.link;
            }else{
                alertOverwrite(data.msg);
            }
        }
    })
})

$('.searchBtn').click(function () {
    window.location.href = __url__+'/search/name/'+$('.searchName').val();
})

var length1=50,isLoading=false,top1=50,num1=50;
/*无限加载*/
function loadMeinv(type){
    if(length1<num1){
        isLoading=true;
    }
    if(isLoading==false){
        isLoading=true;
        var load=new loading("container");
        load.show();
        $.ajax({
            type: 'POST',
            url: "__URL__/get_goods",
            cache: false,
            data: {
                type:type,
                top:top1,
                num:num1
            },
            dataType: 'json',
            success: function (data) {
                length1 = data.data.length;
                load.hide();
                for(var j=0;j<data.data.length;j++) {
                    var html = "";
                    html = ' <li><div class="imgBox"><img src='+__img__+'"/goods/"'+data.data.g_thum+'></div><div class="right"><div class="title">'+data.data.g_name+'</div><div class="xq">'+data.data.g_desc+'</div><div class="num">剩余'+data.data.g_count+'张</div><div class="score"><span>'+data.data.g_price+'</span>积分</div><a href='+__url__+'"/info/gid/'+data.data.g_id+'" class="more">详情</a></div></li>';
                    $('.ul1').append(html);
                }
                if(data.data.length<num1){
                    isLoading=true;
                }else{
                    isLoading=false;
                    top1+=num1;
                }
            }
        });
    }
}

$('.list').scroll(function(){
    /*   if(length1<num1){
     alertOverwrite("到底啦");
     }*/
    var $this =$(this),
        viewH =$(this).height(),//可见高度
        contentH =$(this).get(0).scrollHeight,//内容高度
        scrollTop =$(this).scrollTop();//滚动高度
    if(scrollTop/(contentH -viewH)>=0.95){ //到达底部100px时,加载新内容
        loadMeinv($this.attr('data-type'));
    }

});
$(".alertClose").click(function(){
    $(".alertWindow").hide();
});

$(".sold").click(function(e){
    e.preventDefault();
    $(".zhezhao").show();
});
$(".w_close").click(function(){
    $(".zhezhao").hide()
});
$(".w_ul li").click(function(){
    $(this).addClass("on").siblings().removeClass("on")
});
$(".w_sureBtn").click(function(){
alertOverwrite("暂未开放 敬请期待!");
});

if(pageId){
    if(pageId=="1"){
        $(".footer li").removeClass("on");
        $(".li1").addClass("on");
    }else if(pageId=="2"){
        $(".footer li").removeClass("on");
        $(".li2").addClass("on");
    }else if(pageId=="3"){
        $(".footer li").removeClass("on");
        $(".li4").addClass("on");
    }else if(pageId=="4"){
        $(".footer li").removeClass("on");
        $(".li3").addClass("on");
    }
}




if(user_type == ''){
    user_type = 1;
}

$(".tabMenu .tabLi").removeClass("on");
$(".tab"+user_type).addClass("on");