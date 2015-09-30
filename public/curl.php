<?php

echo date('Y-m-d H:i:s') . PHP_EOL;

//$url = 'https://fuwu.taobao.com/ser/detail.html?service_code=appstore-10687';
$url = 'https://fuwu.taobao.com/ser/detail.html?service_code=FW_GOODS-1887142';

//$url = 'https://qianniu.fuwu.taobao.com/ser/detail.html?spm=a1z13.7324217.0.0.JwlQEb&service_code=FW_GOODS-1887142&tracelog=category&scm=1215.1.1.53364011&ppath=&labels=';
//$url = 'https://qianniu.fuwu.taobao.com/ser/detail.html?service_code=FW_GOODS-1887142&tracelog=category';
//$url = 'https://qianniu.fuwu.taobao.com/ser/detail.html?service_code=FW_GOODS-1887142';



// 初始化一个 cURL 对象
$curl = curl_init();

// 设置你需要抓取的URL
curl_setopt($curl, CURLOPT_URL, $url);

curl_setopt($curl, CURLOPT_FILETIME, true);
curl_setopt($curl, CURLOPT_FRESH_CONNECT, false);
curl_setopt($curl, CURLOPT_CLOSEPOLICY, CURLCLOSEPOLICY_LEAST_RECENTLY_USED);
curl_setopt($curl, CURLOPT_MAXREDIRS, 5);
curl_setopt($curl, CURLOPT_HEADER, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_TIMEOUT, 5184000);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 120);
curl_setopt($curl, CURLOPT_NOSIGNAL, true);
curl_setopt($curl, CURLOPT_REFERER, 'https://fuwu.taobao.com/index.html');
curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.10; rv:39.0) Gecko/20100101 Firefox/39.0');
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');


// 运行cURL，请求网页
$data = curl_exec($curl);


// 关闭URL请求
curl_close($curl);

// 显示获得的数据
var_export($data);
echo date('Y-m-d H:i:s');