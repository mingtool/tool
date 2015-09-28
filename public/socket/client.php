<?php

$i=1;
do{
    $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

    socket_connect($sock, '127.0.0.1', 9675);

    socket_send($sock,'ttest'.$i,1024,0);

    /*读取从服务端返回过来的值*/
    $h = socket_read($sock,8192);
    echo $h;
    socket_close($sock);
    $i++;
    sleep(1);
}while($i<50);

