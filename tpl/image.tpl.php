<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
 <head>
	<title></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="/css/main.css" type="text/css" />
	<link rel="stylesheet" href="/css/share.css" type="text/css" />
	<script type="text/javascript" src="/js/jquery-1.6.4.min.js"></script>
	<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?48"></script>
	<script type="text/javascript" src="/js/vk.js"></script>
	<script type="text/javascript" src="/js/share.js"></script>
 </head>

 <body>
	  <div style="display:none;" id="mask"></div>
	  <div id="child">
	    <div id="title-adding">
		  <h2>Добавление заголовка</h2>
		  <input type="text" name="title" id="title-field">
		  <br>
		  <input id="add-title" type="submit" value="Сохранить">
		  <span>минимум 8 символов</span>
	    </div>
	  </div>
	
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
		<div class="clear"></div>
	  </div>
	
	  <div id="content" class="two-column">
		<div class="left">
		  <?php if($this->number == 3): ?>
		  <div id="multiple-images">
			<div id="more-images">
		      <a id="0" class="green" href="javascript:;">первое фото</a> /
		      <a id="1" href="javascript:;">второе фото</a> /
		     <a id="2" href="javascript:;">третье фото</a>
		    </div>
		  </div>
		  <?php elseif($this->number == 2): ?>
		  <div id="multiple-images">
			<div id="more-images">
		      <a id="0" class="green" href="javascript:;">первое фото</a> /
		      <a id="1" href="javascript:;">второе фото</a>
		    </div>
		  </div>
		  <?php endif; ?>
		  <div id="unique" class="panel">
			<h2 id="title"><?php $this->eprint($this->image['title']);?></h2>
		    <div id="image">
			  <a href="http://i.imgush.ru/<?php echo $this->image['image'].'.'.$this->image['ext'];?>">
			   <img src="http://i.imgush.ru/<?php echo $this->image['image'].'.'.$this->image['ext'];?>">
			  </a>
		    </div>
		    
		    <div id="under-image">	
			  <div class="views">
			    загружена <?php echo showDate(strtotime($this->image['date']));?> : просмотров <?php echo $this->image['views'];?>
			  </div>
			  <div id="social">
			    <ul>
				  <li><a class="vk_share" href="http://vk.com/share.php?url=http://imgush.ru/<?php echo $this->image['image'];?>" target="_blank" title="Сохранить в ВК"></a></li>
				  <li><a class="mailru_share" href="http://connect.mail.ru/share?url=http://imgush.ru/<?php echo $this->image['image'];?>" target="_blank" title="Сохранить в Mail.ru"></a></li>
				 </ul>
			  </div>
			  <div class="clear"></div>
			</div>
		  </div>
		  <div id="footer">
		    <a title="Вопросы и ответы" href="/faq.php">faq</a>
		    <a href="/about.php">о сайте</a>
		  </div>
		</div>
		
		<div class="right">
			  <?php if(isset($this->session_image)): ?>
				<?php if(preg_match("/$this->session_image/", $this->url)): ?>
				  <div class="panel">
				    <div id="title-edit-panel">
			          <a id="title-edit" href="javascript:;">Добавить заголовок</a>
			        </div>
			      </div>
		        <?php endif; ?>
			  <?php endif; ?>
		  <div id="link-codes" class="panel">
		    <div>
			  <h3>Ссылка (email & IM)</h3>
			  <input id="link" type="text" class="tag" readonly="readonly" autocomplete="off" value='http://imgush.ru/<?php echo $this->image['image'];?>'>
			</div>
			<div>
			  <h3>Прямая Ссылка</h3>
			  <input id="direct" type="text" class="tag" readonly="readonly" autocomplete="off" value='http://i.imgush.ru/<?php echo $this->image['image'].'.'.$this->image['ext'];?>'>
			</div>
		    <div>
			  <h3>Ссылка для форума</h3>
			  <input id="board" type="text" class="tag" readonly="readonly" autocomplete="off" value='[IMG]http://i.imgush.ru/<?php echo $this->image['image'].'.'.$this->image['ext'];?>[/IMG]'>
			</div>
		    <div>
			  <h3>Ссылка для веб-сайта</h3>
			  <input id="htmllink" type="text" class="tag" readonly="readonly" autocomplete="off" value='<a href="http://imgush.ru/<?php echo $this->image['image'];?>">http://imgush.ru/<?php echo $this->image['image'].'.'.$this->image['ext'];?></a>'>
			</div>
			
			<div id="sizes">
			  <div class="left"><strong>Размеры:&nbsp;</strong></div>
			  <div class="left">
			  	<a name="0" class="current-selected" id="first" href="javascript:;">Оригинал</a>
			    /
			    <a name="1" href="javascript:;">Стандартная</a>
			    /
			    <a name="2" href="javascript:;">Квадратная</a>
			  </div>
			  <div class="clear"></div>
		    </div>

		  </div> <!-- link-codes end div -->
		
		</div> <!-- right end -->
		<div class="clear"></div>
	  </div> <!-- content end -->
	
 </body>
</html>