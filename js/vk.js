function vk_auth(response){
	if(response.status === 'connected'){
		//var uid = response.session.mid;
		//var sid = response.session.sid;
		var name = response.session.user.first_name + ' ' + response.session.user.last_name;
		$('.vk_login').html('<span class="login_text">'+name+'</span>&nbsp;<span class="login_icon"></span>');
	}
};

function setCookie(name,value){
	var valueEscaped = escape(value);
	//var expiresDate = new Date();
	//expiresDate.setTime(expiresDate.getTime() + 365 * 24 * 60 * 60 * 1000); // срок - 1 год
	//var expires = expiresDate.toGMTString();
	var newCookie = name + "=" + valueEscaped + "; path=/; ";
	if (valueEscaped.length <= 4000) document.cookie = newCookie + ";";
};

$(function(){
	$('.vk_login').click(function(){
		VK.Auth.login(vk_auth, 1027);
		return false;
	});
	
});