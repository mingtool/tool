$(function(){
    $.get('http://www.dianzhentan.com/api/pop2',function(data){
        $('body').append(data);
        $("#current_version .ver_num").text(chrome.app.getDetails().version);
        $.get('http://www.dianzhentan.com/api/version',function(data){
        	if(intVersion(chrome.app.getDetails().version) < intVersion(data.v)){
        		$("#current_version").append('<a class="update_link" href="' + data.link + '" target="_blank">' + data.desc + '</a>');
        	}
        });
    });
});

function intVersion(str){
	return parseInt(str.replace(/\./ig,''));
}


