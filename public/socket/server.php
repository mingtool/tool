<?php
//确保在连接客户端时不会超时
set_time_limit(0);

$ip   = '127.0.0.1';
$port = 9675;

/*
 +-------------------------------
 *    @socket通信整个过程
 +-------------------------------
 *    @socket_create
 *    @socket_bind
 *    @socket_listen
 *    @socket_accept
 *    @socket_read
 *    @socket_write
 *    @socket_close
 +--------------------------------
 */

/*----------------    以下操作都是手册上的    -------------------*/
if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) < 0) {
    echo "socket_create() 失败的原因是:" . socket_strerror($sock) . "\n";
}

if (($ret = socket_bind($sock, $ip, $port)) < 0) {
    echo "socket_bind() 失败的原因是:" . socket_strerror($ret) . "\n";
}

if (($ret = socket_listen($sock, 4)) < 0) {
    echo "socket_listen() 失败的原因是:" . socket_strerror($ret) . "\n";
}

$count = 1;

do {
    $msgsock = intval(socket_accept($sock));
    if (0 > $msgsock) {
        echo "socket_accept() failed: reason: " . socket_strerror($msgsock) . "\n";
        break;
    } else {

        echo "测试成功了啊\n";
        $buf = @socket_read($msgsock, 8192);

        //发到客户端
        $msg = "收到的消息是：" . $buf . "\n";
        socket_write($msgsock, $msg, strlen($msg));


        echo $msg;

        if (++$count >= 300) {
            break;
        };


    }
    //echo $buf;
    socket_close($msgsock);

} while (true);

socket_close($sock);
?>