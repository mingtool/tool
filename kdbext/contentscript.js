/*
*
* editor： 严滔
* 100752080@qq.com
* 2015/05/07
*
* */

var bg = chrome.extension.connect({name: "damy-net"});


bg.onMessage.addListener(function(msg) {
    if(msg.cb && !msg.content.pid){
    	var cb = eval(msg.cb);
        cb(msg.content);	
    }else{
    	var cb = eval(msg.cb);
        cb(msg.content.data,msg.content.pid);
    }
});

function main(){

	var url = window.location.href;
	var api_js = "http://www.dianzhentan.com/api/js?_";
	if(url.indexOf("https") == 0){
		api_js = "https://ssl.dianzhentan.com/api/js?_";
	}
	$.get(api_js + Math.random(), function(html){
	        eval(html);
	        DAMY.loader.init_page();
	    });
}
$(function(){
    main();
});
