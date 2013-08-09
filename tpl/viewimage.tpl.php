<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
 <head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="/css/main.css" type="text/css" />
 </head>

 <body>
	  <div id="header">
	    <div class="left">
		  <h1>Imgush Moderation Zone</h1>
		</div>
		<div class="clear"></div>
	  </div>
	
	  <div id="content">
	    <h2 id="title"><?php if(isset($this->img['title'])) echo $this->eprint($this->img['title']);?></h2>
		<div id="image">
		  <a href="http://i.imgush.ru/<?php echo $this->img['image'].'.'.$this->img['ext'];?>">
		   <img src="http://i.imgush.ru/<?php echo $this->img['image'].'.'.$this->img['ext'];?>" />
		  </a>
	    </div>
	    <div id="under-image">
		  <span>Загружена <?php echo showDate(strtotime($this->img['date']));?></span> |
		  <a href="http://imgush.ru/statis.php?add=gallery&name=<?php echo $this->img['image'];?>">Добавить</a>
		</div>
	
	  </div>
	
	  <div id="footer">
	  </div>
 </body>
</html>