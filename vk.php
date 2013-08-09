<?php
  require_once 'lib/functions.php';

  $member = checkVkLogin();

  if($member !== FALSE){
	// ToDo: регистрация пользователя
  } else {
	header('Location: index.php');
  }
?>









