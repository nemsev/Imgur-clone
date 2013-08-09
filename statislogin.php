<?php
  include_once 'lib/connect.php';
  require_once 'lib/Savant3.php';
  
  session_start();

  $tpl = new Savant3();

  // Если по кнопке входа кликнули
  if(isset($_REQUEST['login'])) {
	// получаем username и пароль
	$username = trim($_REQUEST['username']);
	$password = trim($_REQUEST['password']);
	
	$admins = $db->admins;
	
	// если username и пароль совпадают
	if($admins->find(array( 'username' => $username, 'password' => md5($password) ))->count() == 1) {
		$_SESSION['username'] = $username;
		header('Location: statis.php');
	} else {
		echo 'Неправильный username или пароль!';
	}
	
  }
  
  $tpl->display('tpl/statislogin.tpl.php');
?>