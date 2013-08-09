<?php
  include_once 'lib/connect.php';
  require_once 'lib/Savant3.php';

  session_start();

  date_default_timezone_set('Asia/Novosibirsk');

  // Удаляем куку, если установлена
  // setcookie('Img', '');

  if(isset($_REQUEST['upload'])) {
    // изображение выбрано
    $image_names = array();
    foreach($_FILES['image']['error'] as $key => $error)
    {
	
	  if($error == UPLOAD_ERR_OK)
	  {
		
		$tmp_image_name = $_FILES['image']['tmp_name'][$key];
	    // проверяем, является ли файл изображением
	    if(preg_match('{image/(.*)}is', $_FILES['image']['type'][$key], $p)) {
		  // генерируем имя для изображения
		  $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		  $image_name = '';
		  for($i = 0; $i < 5; $i++)
		    $image_name .= $letters[mt_rand(0, 61)];
		  // не нравится мне расширение jpeg, пусть будет jpg
		  if($p[1] == 'jpeg') $p[1] = 'jpg';
		  // полное имя файла изображения
		  $image = './images/'.$image_name.'.'.$p[1];
		  move_uploaded_file($tmp_image_name, $image);
		  // Создаем новый объект imagick
		  $im = new imagick($image);
		  // Открываем watermark
		  $watermark = new imagick('./img/watermark.png');
		  // узнаем размеры оригинального изображения
		  $im_width = $im->getImageWidth();
		  $im_height = $im->getImageHeight();
		  // узнаем размеры водяного знака
		  $watermark_width = $watermark->getImageWidth();
		  $watermark_height = $watermark->getImageHeight();
		  // посчитать x и y
		  $left = $im_width - $watermark_width - 10;
		  $top = $im_height - $watermark_height - 10;
		  // накладываем watermark на оригинальное изображение
		  $im->compositeImage($watermark,imagick::COMPOSITE_OVER,$left,$top);
		  // сохраняем оригинал
		  $im->writeImage('./images/'.$image_name.'.'.$p[1]);
		  // Копируем объект для различных типов
		  $large = $im->clone();
		  $square = $im->clone();
		  // Создаем квадратное изображение 160x160 px
		  $square->cropThumbnailImage(160, 160);
		  $square->writeImage('./images/'.$image_name.'s'.'.'.$p[1]);
		  // Создаем большое изображение с шириной 640 px и переменной высотой
		  $large->thumbnailImage(640, 0);
		  $large->writeImage('./images/'.$image_name.'l'.'.'.$p[1]);
		  // добавляем имя, расширение, дату загрузки изображения в БД
		   // выбираем коллекцию
		  $collection = $db->images;
		   // добавляем новый документ - изображение в коллекцию Images
		  $collection->insert(array( 'image' => $image_name, 'ext' => $p[1], 'title' => '', 'views' => 0, 'gallery' => 0, 'date' => date("d-m-Y H:i:s") ));
		  $image_names[] = $image_name;
		  setcookie('Img', $image_name);
	    } else {
		  echo 'Вы можете загрузить только фото!';
	    }
	
	  }
	
    }
    
    // быдлокод
    switch(count($image_names)) {
	  case 1: header("Location: /$image_names[0]"); break;
	  case 2: header("Location: /$image_names[0],$image_names[1]"); break;
	  case 3: header("Location: /$image_names[0],$image_names[1],$image_names[2]"); break;
    }

    // setcookie('Img', $image_name);

  }

  $date = date("d-m-Y",time()-(24*3600));// на день назад
  $images = $db->images;
  $cursor = $images->find(array( 'gallery' => 1, 'date' => new MongoRegex("/$date/") ))->sort(array('date' => -1))->limit(12);

  $tpl = new Savant3();
  $tpl->cursor = $cursor;
  $tpl->display('tpl/index.tpl.php');
 
?>
