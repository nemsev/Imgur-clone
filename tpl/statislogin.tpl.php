<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
 <head>
	<title></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="/css/main.css" type="text/css" />
	<link rel="stylesheet" href="/css/statis.css" type="text/css" />
 </head>

 <body>
	
	<div id="header" class="signin">
	  <div class="left">
	    <a href="/"><img src="/img/imgush-small.gif" alt="image you share" /></a>
	  </div>
	  <div class="clear"></div>
	</div>
	
	<div id="content" class="private">
	  <h2>Войти</h2>
	  <hr>
	  <div id="login-form">
	    <form method="post" action="/statislogin.php">
		  <div>
		    <label for="username">Username:</label>
		    <input type="text" name="username" id="username" />
		  </div>
		  <div>
		    <label for="password">Пароль:</label>
		    <input type="password" name="password" id="password" />
		  </div>
		  <div>
			<input type="submit" name="login" value="Вход" />
		  </div>
		</form>
	  </div>
	</div>
	
 </body>
</html>