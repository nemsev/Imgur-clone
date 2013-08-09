<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
 <head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="title" content="<?php if(isset($this->image['title'])) echo $this->image['title'];?>" />
	<link rel="stylesheet" href="/css/main.css" type="text/css" />
	<link rel="stylesheet" href="/css/gallery.css" type="text/css" />
	<link rel="image_src" href="http://i.localhost/<?php echo $this->image['image'].'l.'.$this->image['ext'];?>" />
	<script type="text/javascript" src="/js/jquery-1.6.4.min.js"></script>
	<!-- загрузить openapi.js -->
	<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?48"></script>
	<script type="text/javascript" src="http://vk.com/js/api/share.js?10" charset="windows-1251"></script>
	<!-- загрузить функции для работы с API VK -->
	<script type="text/javascript" src="/js/vk.js"></script>
	<script type="text/javascript" src="/js/share.js"></script>
 </head>

 <body>
	  <div style="display:none;" id="mask"></div>
	  <div id="child">
		<h2>Кликните по кнопке Вход в правом верхнем углу.</h2>
	  </div>
	  <script type="text/javascript">
	    <!-- инициализация приложения для localhost -->
	    VK.init({apiId: 2957270, onlyWidgets: true});
	    <!-- Получить состояние - юзер онлайн -->
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
	    <div class="clear"></div>
	  </div>
	
	  <script type="text/javascript">
	    $(function(){
		 var fetching = false;
		
		 function lastPostFunc()
		 {
			fetching = true;
			var from = $('.caption:last').attr('id');
			$.post('/gallery.php', {from:from,gotimage:to_image_name}, function(data){
				if(data != ""){
					$('.caption:last').after(data);
					setTimeout(function(){
						fetching = false;
					},500);
				}
				else
				{
					// заглушка
					fetching = true;
				}
			});
		 }
		 
		 var pathname = window.location.pathname;
	     var image_names = pathname.slice(1).split(',');
	     var to_image_name = image_names[0].split('/')[1];
         
         if(to_image_name != undefined){
	       $(window).scroll(function(){
		     var bufferzone = $(window).scrollTop() * 0.20;
			 if(!fetching && ($(window).scrollTop() + bufferzone > ($(document).height() - $(window).height()) )){
		       lastPostFunc();
			 }
		   });
         }

	    });
	
	  </script>
	
	  <div id="content" class="two-column">
		<div class="left">
		  <div class="panel">
			<h2 id="title"><?php if(isset($this->image['title'])) echo $this->eprint($this->image['title']);?></h2>
		    <div id="image">
		      <a href="http://i.imgush.ru/<?php echo $this->image['image'].'.'.$this->image['ext'];?>">
			   <img src="http://i.imgush.ru/<?php echo $this->image['image'].'.'.$this->image['ext'];?>" />
		      </a>
		    </div>
		    <div id="under-image">
			  <div>
				<div class="views">
				  просмотров <?php echo $this->image['views'];?><?php if(isset($this->comments)) echo ' : комментариев '.$this->total_captions;?>
				</div>
			    <div id="social">
				 <ul>
				  <li><a class="vk_share" href="http://vk.com/share.php?url=http://imgush.ru/gallery/<?php echo $this->image['image'];?>" target="_blank" title="Сохранить в ВК"></a></li>
				  <li>
				   <a class="mailru_share" href="http://connect.mail.ru/share?url=http://imgush.ru/gallery/<?php echo $this->image['image'];?>" target="_blank" title="Сохранить в Mail.ru"></a>
				  </li>
				 </ul>
				</div>
				<div class="clear"></div>
			  </div>
			  <!-- -->
			  <?php if(isset($this->comments)): ?>
		      <div id="post-caption">
		        <textarea id="caption_textarea" name="caption">Добавить комментарий</textarea>
		        <div class="right">
			      <input id="add-caption" type="button" value="Добавить" name="save">
			      <span class="counter">140</span>
			    </div>
		        <div class="clear"></div>
		      </div>
		      <div class='error'>Комментарий должен содержать не менее 5 символов</div>
		      <div id="captions">
			  <?php $index = 0;?>
			  <?php foreach($this->captions as $caption): ?>
				<?php $index++;?>
			    <div id="<?php echo $index;?>" class="caption">
				  <div class="author">
				    <span style="color:#4E76C9;"><?php echo $caption['username'];?></span>
				    <span><?php echo showDate($caption['uploaded']);?></span>
				  </div>
				  <?php $this->eprint($caption['caption']);?>
				</div>
			  <?php endforeach; ?>
		      </div>
		      <?php endif; ?>
		      <!-- -->
		    </div>
		  </div>
		  <div id="footer">
		    <a title="Вопросы и ответы" href="/faq.php">faq</a>
		    <a href="/about.php">о сайте</a>
		  </div>
		</div>
		<div class="right">
	      <div class="panel">
		    <a href="/gallery/"><input class="button-medium" type="button" onclick="javascript:window.location='/gallery';" value="галерея" /></a>
		    <div class="alert">Лучшие фото сегодня</div>
		    <div class="clear"></div>
		    <div class="thumbnails">
		    <?php foreach($this->cursor as $image): ?>
			  <div class="thumb">
		        <a href="/gallery/<?php echo $image['image'];?>">
			     <img src="http://i.imgush.ru/<?php echo $image['image'].'s'.'.'.$image['ext'];?>" />
			    </a>
			  </div>
		    <?php endforeach; ?>
		      <div class="clear"></div>
		    </div>
		  </div>
		  <div class="panel">
			<div class="stats">
		      Загружена <?php echo showDate(strtotime($this->image['date']));?><br />
		      Количество просмотров <?php echo $this->image['views'];?>
		    </div>
		  </div>
		</div>
		<div class="clear"></div>
	  </div>
 </body>
</html>