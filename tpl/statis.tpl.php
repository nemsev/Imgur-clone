<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
 <head>
	<title>test</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="/css/main.css" type="text/css" />
	<link rel="stylesheet" href="/css/statis.css" type="text/css" />
	<script type="text/javascript" src="/js/jquery-1.6.4.min.js"></script>
 </head>

 <body>
	  <div id="header">
		<div class="left">
		  <h1> Imgush Moderation Zone</h1>
	    </div>
	    <div class="right">
		  Всего изображений на сайте <?php echo $this->total_images;?><br />
		  <a href="/statislogout.php">Выйти</a>
		</div>
	    <div class="clear"></div>
	  </div>
	
	  <script type="text/javascript">
	    $(function(){
		  var page = 0;
		  var fetching = false;
		  
		  function lastPageFunc()
		  {
			page++;
		    fetching = true;
		    $.get('/statis.php', {page:page}, function(data){
				if(data != ""){
				  	$('#imagelist-loader').after(data);
				    $('#imagelist-loader').remove();
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
		
	      $(window).scroll(function(){
		    var bufferzone = $(window).scrollTop() * 0.20;
		    if(!fetching && ($(window).scrollTop() + bufferzone > ($(document).height() - $(window).height()) )){
			  lastPageFunc();
			  //var from = $('.post:last').attr('id');
			  //$.get('/statis.php', {from:from}, function(data){
				//$('.post:last').after(data);
			  //});
		    }
	      });
	
	    });
	  </script>
	
	  <div id="content" class="two-column">
	    <div class="panel left">
		  <div class="headerbox">
		    <div class="left">
			  <h2>Загрузки сегодня</h2>
			</div>
			<div class="right">
			  <?php echo $this->today_img_number;?>
			</div>
			<div class="clear"></div>
		  </div>
		  <div id="imagelist">
		    <div class="posts">
	        <?php foreach($this->cursor as $image): ?>
		    <div id="<?php echo $image['image'];?>" class="post">
		      <a href="http://imgush.ru/statis.php?img=<?php echo $image['image'];?>">
		       <img src="http://i.imgush.ru/<?php echo $image['image'].'s'.'.'.$image['ext'];?>" />
		      </a>
		    </div>
	        <?php endforeach; ?>
	        <div class="clear"></div>
	        </div> <!-- end div posts -->
	        <div id="imagelist-loader" class="textbox">
		      <img src="/img/album_loader.gif" />
		    </div>
	      </div><!-- end div imagelist -->
	     </div><!-- end div panel left -->
	     <div class="right">
	       <div class="panel">
		     <?php echo $this->today_imgs_in_gal;?> добавлено в галерею
		   </div>
	     </div>
	     <div class="clear"></div>
	     </div>
	
	  <div id="footer">
		.
	  </div>
	
 </body>
</html>