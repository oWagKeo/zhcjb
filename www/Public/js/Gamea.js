(function(){
    var evtP;
    var game,time_speed,times= 0,sh,stop;
    var me=2;
    var istrans=false;

    window.initGame=function(){
        stage.name='stage';
        evtP=document.getElementById("container");
        game=new lib.gamePanel();
        stage.addChild(game);
        for (var i=0;i<8;i++){
            var j=i+1;
            var p=eval("game.p"+j);
            p.gotoAndStop(1);
        }
        game.btn.gotoAndStop(0);

        game.start.addEventListener("click",starGame);

        //判断当前动画停留在第几帧
        // game.p.currentFrame

        createjs.Ticker.setFPS(30);
        createjs.Ticker.addEventListener("tick", onframe);

    };
    function starGame(){
        if(istrans==false){
            if(parseInt($(".awardTime input").val())==0){
                alertOverwrite("您本周已经抽过奖了，下周再来吧！")
                return false;
            }
            game.btn.gotoAndStop(1);
            for (var i=0;i<8;i++){
                var j=i+1;
                var p=eval("game.p"+j);
                p.gotoAndStop(1);
            }
            clearInterval(sh);
            me=2;
            times=0;
            time_speed=400;
            sh = setInterval(star,time_speed);
            award();
            istrans=true;
        }
    }
    var nrr;
    function award(){
        $.ajax({
            type:"Post",
            url:__url__+"/lottery",
            data:{
            },
            success:function(data){
                if(!data){
                    alertOverwrite(data.msg)
                }else{
                    if(data.id==0){
                        stop=3;
                    }else if(data.id==1){
                        nrr=data.awardname;
                        stop=6;
                    }else if(data.id==2){
                        nrr=data.awardname;
                        stop=2;
                    }else if(data.id==3){
                        nrr=data.awardname;
                        stop=1;
                    }else if(data.id==4){
                        nrr=data.awardname;
                        stop=8;
                    }else if(data.id==5){
                        nrr=data.awardname;
                        stop=4;
                    }else if(data.id==6){
                        nrr=data.awardname;
                        stop=5;
                    }else if(data.id==7){
                        nrr=data.awardname;
                        stop=7;
                    }
                }
            }
        })
    }

    function star(){
        if(times>=0&&times<=4){
            clearInterval(sh);
            time_speed=400;
            sh = setInterval(star,time_speed);
        }else if(times>4&&times<=32){
            clearInterval(sh);
            time_speed=50;
            sh = setInterval(star,time_speed);
        }else if(times>32) {
            if (times <32 + stop) {
                clearInterval(sh);
                time_speed =1000;
                sh = setInterval(star, time_speed);
            } else {
                clearInterval(sh);
                istrans = false;
                game.btn.gotoAndStop(0);
                if(stop==3){
                    setTimeout(function(){
                        $(".alertContent").addClass("on");
                        $(".alertTitle").html("很遗憾");
                        $(".nr").html("您可能是抽了假奖!");
                        $(".alertWindow").show();
                        $(".button").hide();
                    },1000);
                }else{
                    setTimeout(function(){
                        $(".alertContent").removeClass("on");
                        $(".alertTitle").html("恭喜你");
                        $(".nr").html("获得了"+nrr+"代金券");
                        $(".alertWindow").show();
                        $(".button").show();
                    },1000);
                }
            }
        }

        for(var i=1;i<me;i++){
            var p2=eval("game.p"+i);
            p2.gotoAndStop(0);
            if(i>=2){
                var p3=eval("game.p"+(i-1));
                p3.gotoAndStop(1);
            }
            else if(i==1){
                game.p8.gotoAndStop(1);
            }
        }
        times=times+1;
        if(me<9){
            me=me+1;
        }
        else{
            me=2;
        }
    }
    //gotoandstop() 和 play()
    function onframe(){
        stage.update();
    }
})();












