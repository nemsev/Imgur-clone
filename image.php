<?php
  include_once 'lib/connect.php';
  require_once 'lib/functions.php';
  require_once 'lib/Savant3.php';
  session_start();

  $url = explode("/", $_SERVER['REQUEST_URI']);

  // выбираем коллекцию
  $images = $db->images;
  $tpl = new Savant3();

  date_default_timezone_set('Asia/Novosibirsk');
  
  // добавление заголовка к фото
  if(isset($_POST['title']) && isset($_POST['name']) && isset($_SESSION['image_name'])) {
	$image_title = substr(trim($_POST['title']),0,100);
	$image_name  = $_POST['name'];
	if(strlen($image_title) > 7){
	  $images->update(array('image' => $image_name), array('$set' => array('title' => $image_title)));
	  echo htmlspecialchars($image_title);
	}
  }
  
  // Смена фото
  if(isset($_GET['index']) && isset($_GET['names']) && isset($_SESSION['image_name'])) {
	// массив имен изображений
	$image_names = $_GET['names'];
	// номер изображения
	$index = $_GET['index'];
	$image = $images->findOne(array( 'image' => $image_names[$index] ));
	// определение времени добавления
	$uploaded = showDate(strtotime($image['date']));
	// возвращаем имя изображения и расширение
	echo json_encode(array('title' => $image['title'], 'name' => $image['image'].'.'.$image['ext'], 'when_upl' => $uploaded));
	// echo $image['image'].'.'.$image['ext'];
  } elseif(isset($_GET['names']) && isset($_SESSION['image_name'])) {
    // массив имен изображений
	$image_names = $_GET['names'];
	// выбираем первое фото из списка
	$image = $images->findOne(array( 'image' => $image_names[0] ));
	if($image){
	  // определяем кол-во фоток в массиве 1,2 или 3
	  $number_of_images = count($image_names);
	  $tpl->image = $image;
	  $tpl->session_image = trim($_SESSION['image_name']);
	  $tpl->number = $number_of_images;
	  $tpl->url = $url[1];
	  $tpl->display('tpl/image.tpl.php');	
	}
	else
	{
	  $tpl->display('tpl/404.tpl.php');		
	}
  } elseif(isset($_GET['names']) && !isset($_SESSION['image_name'])) {
	// читаем комментарии изображения
	// $comments = $db->comments;
	// генерим случайное число, используется для показа фоток из галереи
	if(lcg_value() > 0.5) {
		// по возрастанию?
		$rnd = 1;
	} else {
		// по убыванию?
		$rnd = -1;
	}
	$date = date("d-m-Y",time()-(24*3600));// на день назад
	
	$image_names = $_GET['names'];
	$image = $images->findOne(array( 'image' => $image_names[0] ));
	if($image){
	  // увеличиваем кол-во просмотров
	  $images->update( array( 'image' => $image_names[0] ), array( '$inc' => array('views' => 1) ) );
	  $cursor = $images->find(array( 'gallery' => 1, 'date' => new MongoRegex("/$date/"), 'image' => array('$ne' => $image_names[0]) ))->sort(array('date' => $rnd))->limit(6);
	  $tpl->image = $image;
	  $tpl->cursor = $cursor;
	  $tpl->display('tpl/lookimage.tpl.php');	
	}
	else
	{
	  $tpl->display('tpl/404.tpl.php');	
	}

  }

  /*
  // проверяем установлена ли кука
  if(isset($_COOKIE['Img'])) {
    // достаем имя фото из БД
	$images->update( array( 'image' => $image_name ), array( '$inc' => array('views' => 1) ) );
	$tpl->display('image.tpl.php');
  } else {
	$cursor = $images->find(array( 'gallery' => 1 ))->limit(8);
	$tpl->cursor = $cursor;
	$tpl->display('lookimage.tpl.php');
  }*/
?>