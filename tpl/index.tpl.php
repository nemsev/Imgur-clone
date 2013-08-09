<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
 <head>
	<title></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="/css/main.css" type="text/css" />
	<script type="text/javascript" src="/js/jquery-1.6.4.min.js"></script>
	<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?48"></script>
	<script type="text/javascript" src="/js/vk.js"></script>
	<script type="text/javascript" src="/js/share.js"></script>
	<script type="text/javascript" src="/js/swfupload.js"></script>
	<script type="text/javascript" src="/js/swfupload.queue.js"></script>
 </head>

 <body>
	<div style="display:none;" id="mask"></div>
	<div id="child">
	  <h2>Загрузка...</h2>
	  <div class="progress-bar green stripes">
	    <span class="bar" style="width:0%"></span>
	  </div>
	</div>
	<script type="text/javascript">
	  VK.init({apiId: 2957270});
	  VK.Auth.getLoginStatus(vk_auth, true);
	
	  var swfu;
	  var redirect_url = '/';
	  var pic_name = '';
	
	  $(function(){
		
		$('#begin-upload-button').click(function(){
		  setCookie('PHPSESSID',"<?php echo session_id();?>");
		  swfu.startUpload();
		});
	
	    var settings_object = {
			upload_url : "upload.php",
			post_params: {"PHPSESSID" : "<?php echo session_id();?>"},
			flash_url : "http://imgush.ru/swfupload.swf",
			file_size_limit : "5 MB",
			file_upload_limit : "3",
			file_types : "*.jpg; *.png; *.jpeg; *.gif",
			file_types_description : "Images",
			button_placeholder_id : "SwfUploadButton",
			button_width: 120,
			button_height: 30,
			button_window_mode : SWFUpload.WINDOW_MODE.TRANSPARENT,
			button_cursor: SWFUpload.CURSOR.HAND,
			debug: false,
			file_queued_handler : fileQueued,
			upload_start_handler : uploadStart,
			upload_progress_handler : uploadProgress,
			upload_complete_handler : uploadComplete,
			upload_success_handler : uploadSuccess,
			queue_complete_handler : queueComplete
	    };
	
	    swfu = new SWFUpload(settings_object);
	
	    function fileQueued(file){
		  $('#start-upload').css('display', 'block');
		  $('#begin-upload-button').css('display', 'block');
		  $('.textbox ul').append('<li>'+file.name+'</li>');
	    }
	
	    function queueComplete(){
		  redirect_url = redirect_url.slice(0,-1);
		  //setCookie('Img',pic_name);
		  window.location.replace(redirect_url);
	    }
	
	    function uploadStart(file){
		  return true;
	    }
	
	    function uploadProgress(file,bytesLoaded,bytesTotal){
		  var childMargTop = ($('#child').height() + 100) / 2;
	      var childMargLeft = ($('#child').width() + 100) / 2;

	      $('#child').css({
			'margin-top' : -childMargTop,
			'margin-left' : -childMargLeft
	      });
		  $('#child, #mask').fadeIn();
	    }
	
	    function uploadComplete(file){
		  $('.bar').animate({width:'100%',opacity:'0.5'}, 1000, function(){
		    $('#child, #mask').fadeOut();	
		  });
	    }
	
	    function uploadSuccess(file, serverData){
		  pic_name = serverData;
		  redirect_url = redirect_url+serverData+',';
	    }
	   
	  })
	
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
	
	<div id="container">
	
	  <div id="header">
		<div id="logo">
	    </div>
	  </div>
	
	  <div id="content" class="welcome"> 
	    <form action="/index.php" method="post" enctype="multipart/form-data">
	      <div id="buttons">
		    <div id="flash-container">
		      <div id="SwfUploadButton"></div>
		    </div>
		    <div id="upload-button">
			  Выбрать фото
			  <input type="file" multiple="multiple" name="image[]" />
			</div>
			<div id="begin-upload-button">
			  Загрузить
			</div>
			<div class="right">
			  <div class="browse">
			    <a href="/gallery/">Смотреть все</a>
			  </div>
			</div>
			<div class="clear"></div>
		  </div>
		  <div id="start-upload">
		    <div class="textbox">
			  <ul>
			  </ul>
			</div>
		  </div>
	    </form>
	
	    <div id="view-images">
		<?php foreach($this->cursor as $image): ?>
		   <a href="/gallery/<?php echo $image['image'];?>">
		    <img src="http://i.imgush.ru/<?php echo $image['image'].'s'.'.'.$image['ext'];?>">
		   </a>
		<?php endforeach; ?>
		</div>
	  </div>
	  
	  <div id="footer">
		<a title="Вопросы и ответы" href="/faq.php">faq</a>
	    <a href="/about.php">о сайте</a>
	  </div>
	
	</div>
 </body>
</html>
