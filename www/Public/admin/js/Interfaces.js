/**
 * Created by c-07 on 2017/5/25.
 */
var SocketContraller={};
(function (s) {
    s.contect=function () {
        s.socket=new WebSocket('ws://103.54.127.6:8282');
        s.socket.onopen=openSocket;
        s.socket.onclose = closeSocket;
        s.socket.onmessage = socketGetMsg;
    }

    function openSocket() {
        s.sendMsg('adminLogin',1);
    }

    function closeSocket() {
        console.log('close');
        alertMsg('与服务器断开连接!');
        loadBar.hide();
    }

    function socketGetMsg(e) {
        try {
            var data = JSON.parse(e.data);
            if(data.type=='adminLogin'){
                s.sendMsg('admin_info',1);
            }
        } catch(ev) {}
    }
    s.sendMsg=function (type,data) {
        var msg={
            'type':type,
            'data':data
        }
        s.socket.send(JSON.stringify(msg));
    }
})(SocketContraller);