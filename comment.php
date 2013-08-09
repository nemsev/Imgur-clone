<?php
  include_once 'lib/connect.php';
  require_once 'lib/functions.php';

  $member = checkVkLogin();

  if($member !== FALSE){
    // читаем комментарии изображения
	$comments = $db->comments;
	// массив ошибок
	$errors = array();

    if(isset($_POST['caption']) && isset($_POST['username']) && isset($_POST['imagename'])) {
	
	  if(!empty($_POST['caption'])){
		if(strlen($_POST['caption']) > 4){
	      $user_caption = substr(trim($_POST['caption']),0,140);
	      $user_name  = $_POST['username'];
	      $my_image_name = $_POST['imagename'];
	      $uploaded = time();
	      $comments->insert(array( 'image' => $my_image_name, 'username' => $user_name, 'caption' => $user_caption, 'uploaded' => $uploaded ));
	      $response = json_encode(array( 'image' => $my_image_name, 'username' => $user_name, 'caption' => htmlspecialchars($user_caption), 'uploaded' => $uploaded ));
	      echo $response;
        } else {
	      $errors['error'] = 'Комментарий не должен содержать менее 5 символов';
	      echo json_encode($errors);
        }
	  } else {
	    $errors['error'] = 'Комментарий к фото пуст';
	    echo json_encode($errors);
	  }
	
	}
	
  } else {
	header('Location: index.php');
  }


?>