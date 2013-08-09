(function(d){function i(){var b=d("script:first"),a=b.css("color"),c=false;if(/^rgba/.test(a))c=true;else try{c=a!=b.css("color","rgba(0, 0, 0, 0.5)").css("color");b.css("color",a)}catch(e){}return c}function g(b,a,c){var e="rgb"+(d.support.rgba?"a":"")+"("+parseInt(b[0]+c*(a[0]-b[0]),10)+","+parseInt(b[1]+c*(a[1]-b[1]),10)+","+parseInt(b[2]+c*(a[2]-b[2]),10);if(d.support.rgba)e+=","+(b&&a?parseFloat(b[3]+c*(a[3]-b[3])):1);e+=")";return e}function f(b){var a,c;if(a=/#([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/.exec(b))c=
[parseInt(a[1],16),parseInt(a[2],16),parseInt(a[3],16),1];else if(a=/#([0-9a-fA-F])([0-9a-fA-F])([0-9a-fA-F])/.exec(b))c=[parseInt(a[1],16)*17,parseInt(a[2],16)*17,parseInt(a[3],16)*17,1];else if(a=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(b))c=[parseInt(a[1]),parseInt(a[2]),parseInt(a[3]),1];else if(a=/rgba\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9\.]*)\s*\)/.exec(b))c=[parseInt(a[1],10),parseInt(a[2],10),parseInt(a[3],10),parseFloat(a[4])];return c}
d.extend(true,d,{support:{rgba:i()}});var h=["color","backgroundColor","borderBottomColor","borderLeftColor","borderRightColor","borderTopColor","outlineColor"];d.each(h,function(b,a){d.fx.step[a]=function(c){if(!c.init){c.a=f(d(c.elem).css(a));c.end=f(c.end);c.init=true}c.elem.style[a]=g(c.a,c.end,c.pos)}});d.fx.step.borderColor=function(b){if(!b.init)b.end=f(b.end);var a=h.slice(2,6);d.each(a,function(c,e){b.init||(b[e]={a:f(d(b.elem).css(e))});b.elem.style[e]=g(b[e].a,b.end,b.pos)});b.init=true}})(jQuery);

var animating = false;

$(function(){
	var pathname = window.location.pathname;
	var image_names = pathname.slice(1).split(',');
	var names = [ image_names[0], image_names[1], image_names[2] ];
	var to_image_name = image_names[0].split('/')[1];
	
	$('#sizes a').click(function(){
		var direct_link = $('#direct').val();
		var ex = '.'+direct_link.split('.')[2];
		var index = $(this).attr('name'); // определяем индекс изображения:оригинал, большое или маленькое
		var suffix = ['', 'l', 's']; // буква в массиве соответствует размеру изображения
		var exts = [ex, ex, ex];
		
		$('#sizes a').removeClass('current-selected');
		$(this).addClass('current-selected');
		
		// Меняем ссылки для соответствующих изображений
		ChangeLinks(suffix[index], exts[index]);
	});
	
	$('#more-images a').click(function(){
		$('#more-images a').removeClass('green');
		$(this).addClass('green');
		$.get('/image.php', {'names[]':names, index:$(this).attr('id')}, function(data){
			var img = $.parseJSON(data);
			var short_name = img.name.split('.')[0];
			$('#unique').html('<h2 id="title">'+img.title+'</h2><div id="image"><a href="http://i.imgush.ru/'+img.name+'"><img src="http://i.imgush.ru/'+img.name+'"></a></div><div id="under-image"><div class="views">загружена '+img.when_upl+' : просмотров 0</div><div id="social"><ul><li><a class="vk_share" title="Сохранить в ВК" target="_blank" href="http://vk.com/share.php?url=http://imgush.ru/'+short_name+'"></a></li>&nbsp;<li><a class="mailru_share" title="Сохранить в Mail.ru" target="_blank" href="http://connect.mail.ru/share?url=http://imgush.ru/'+short_name+'"></a></li></ul></div><div class="clear"></div></div>');
			ChangeImage(img.name);
		});
		$('#sizes a').removeClass('current-selected');
		$('#first').addClass('current-selected');
	});
	
	// появление модального окна и маски
    $('#title-edit').click(function(){
	    var childMargTop = ($('#child').height() + 100) / 2;
	    var childMargLeft = ($('#child').width() + 100) / 2;
	
	    $('#child').css({
			'margin-top' : -childMargTop,
			'margin-left' : -childMargLeft
	    });
		$('#child, #mask').fadeIn();
    });

    // его исчезновение вместе с маской
    $('#mask').click(function(){
		$('#child, #mask').fadeOut();
    });

    // добавление заголовка фото
    $('#add-title').click(function(){
		var img_title = $('input[name=title]').val();
		var img_index = $('.green').attr('id');
		if(img_index == undefined) img_index = 0;
		var img_name = image_names[img_index];
		if(img_title.length > 7){
		  $.post('/image.php', {title:img_title, name:img_name}, function(title){
			$('#title').replaceWith('<h2 id="title">'+title+'</h2>');
			$('input[name=title]').val('');
		    $('#child, #mask').fadeOut();
		  });	
		}
    });

    // подготовка элемента textarea для отправки подписи
    $('#caption_textarea').focus(function(){
	  $(this).val('');
	  $(this).height(60);
	  $(this).css('color', 'FFFFFF');
	  $('.counter').css('display', 'block');
    });

    // Добавление комментария
	$('#add-caption').click(function(){
		// проверить аутентификацию юзера
		VK.Auth.getLoginStatus(function(response){
			if(response.status === 'connected'){
				var uid = response.session.mid;
				var sid = response.session.sid;
				var user_name = response.session.user.first_name + '' + response.session.user.last_name;
				// добавление комментария
				var user_caption = $('#caption_textarea').val().substr(0,140);
				// проверки
				if(user_caption.length > 4){
				  $.post('/comment.php', {imagename:to_image_name, caption:user_caption, username:user_name}, function(result){
					result = jQuery.parseJSON(result);
					if(result.error)
					{
					  $('.error').remove();
					  $('#post-caption').after("<div class='error'>"+result.error+"</div>");
					}
					else
					{
					  $('.error').css('display', 'none');
					  $('#captions').prepend("<div class='caption'><div class='author'><span style='color:#4E76C9;'>"+result.username+"</span>&nbsp;<span>5 секунд назад</span></div>"+result.caption+"</div>");
					}
				  });
				  $('#caption_textarea').val('');
			    }
			    else
			    {
				  $('.error').css('display', 'block');
			    }
			} else {
				var childMargTop = ($('#child').height() + 100) / 2;
			    var childMargLeft = ($('#child').width() + 100) / 2;

			    $('#child').css({
					'margin-top' : -childMargTop,
					'margin-left' : -childMargLeft
			    });
				$('#child, #mask').fadeIn();
			}
		});
	});
	
	// Подсчет символов в textarea
	$('#caption_textarea').keyup(function(){
		var char_num = $(this).val().length;
		var remaining = 140 - char_num;
		$('.counter').html(remaining);
	});
	
	$('#view-images a img').hover(function(){
		$(this).css('opacity', '1');
	},function(){
		$(this).css('opacity', '0.6');
	});
		
});


function ChangeLinks(prefix, ext){
	if(animating) return;
	animating = true;
	
	var reg = /(([A-z]|[0-9]){5})(s|b|t|m|l|h)?\.(jpe?g|gif|png)/g;
	
	// воспроизвести анимацию в полях ввода
	$('.tag').animate({backgroundColor: '#EE4444'}, 250, function(){
		$('.tag').animate({backgroundColor: '#181817'}, 250, function(){
			animating = false;
		});
	});
	
	// поменять ссылки в полях на другой размер изображений
	$('#direct').val($('#direct').val().replace(reg, "$1"+prefix+ext));
	$('#board').val($('#board').val().replace(reg, "$1"+prefix+ext));
	$('#htmllink').val($('#htmllink').val().replace(reg, "$1"+prefix+ext).replace(reg, "$1"+prefix+ext));
}

function ChangeImage(full_image_name){
	if(animating) return;
	animating = true;
	
	image_name = full_image_name.split('.');
	
	// воспроизвести анимацию в полях ввода
	$('.tag').animate({backgroundColor: '#EE4444'}, 250, function(){
		$('.tag').animate({backgroundColor: '#181817'}, 250, function(){
			animating = false;
		});
	});
	
	// поменять имя фото в ссылках
	$('#link').val('http://imgush.ru/'+image_name[0]);
	$('#direct').val('http://i.imgush.ru/'+full_image_name);
	$('#board').val('[IMG]http://i.imgush.ru/'+full_image_name+'[/IMG]');
	$('#htmllink').val('<a href="http://imgush.ru/'+image_name[0]+'">http://imgush.ru/'+full_image_name+'</a>');
}
