<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
 <head>
	<title>test</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="/css/main.css" type="text/css" />
	<link rel="stylesheet" href="/css/gallery.css" type="text/css" />
	<script type="text/javascript" src="/js/jquery-1.6.4.min.js"></script>
	<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?48"></script>
	<script type="text/javascript" src="/js/vk.js"></script>
	<script type="text/javascript" src="/js/share.js"></script>
 </head>

 <body>
	  <script type="text/javascript">
	    VK.init({apiId: 2957270, onlyWidgets: true});
	    VK.Auth.getLoginStatus(vk_auth, true);
	  </script>
	  
	  <div id="topbar">
		<div class="left">
		  <div class="gallery-ref">
		    <a href="/gallery/">галерея</a>
		  </div>
		</div>
		<div class="right">
		  <div class="vk_login">
		    <span class="login_text">Войти</span>&nbsp;<span class="login_icon"></span>
		  </div>
		</div>
		<div class="clear"></div>
	  </div>
	
	  <div id="header">
		<div class="left">
	      <a href="/"><img src="/img/imgush-small.gif" alt="image you share" /></a>
	    </div>
	    <h1>Галерея</h1>
	    <div class="clear"></div>
	  </div>
	
	  <script type="text/javascript">
	    $(function(){
		  var page = 1;
		  var fetching = false;

		  function lastPageFunc()
		  {
			page++;
		    fetching = true;
		    $.get('/gallery.php', {page:page}, function(data){
				if(data != ""){
				  	$('#imagelist-loader').after(data);
				    $('#imagelist-loader').remove();
					setTimeout(function(){
						fetching = false;
					},500);
				}
				/*
				else
				{
					fetching = true;
				}*/
		    });
		  }

	      $(window).scroll(function(){
		    var bufferzone = $(window).scrollTop() * 0.20;
		    if(!fetching && ($(window).scrollTop() + bufferzone > ($(document).height() - $(window).height()) )){
			  lastPageFunc();
		    }
	      });

	    });
	  </script>
	
	  <div id="content" class="gallery">
		
		<div class="panel left">
		  <div class="headerbox">
			<div class="left">
			  <h2>Лучшие фото сегодня</h2>
			</div>
			<div class="clear"></div>
		  </div>
		
		  <div id="imagelist">
		    <div class="posts">
	        <?php foreach($this->cursor as $image): ?>
		      <div id="<?php echo $image['image'];?>" class="post">
		        <a href="/gallery/<?php echo $image['image'];?>">
		         <img src="http://i.imgush.ru/<?php echo $image['image'].'s'.'.'.$image['ext'];?>" alt="" />
		        </a>
		      </div>
	        <?php endforeach; ?>
	          <div class="clear"></div>
	        </div>
	        <div id="imagelist-loader" class="textbox">
		      <img src="/img/album_loader.gif" />
		    </div>
	      </div>
	    </div>
	    <div class="clear"></div>
		
	  </div>
	
 </body>
</html>