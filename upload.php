<?php
  include_once 'lib/connect.php';
  if(isset($_POST['PHPSESSID'])){
	session_id($_POST['PHPSESSID']);
  }
  session_start();

  date_default_timezone_set('Asia/Novosibirsk');

  if(isset($_FILES)){
    	
    //$image_names = array();
    $tmp_image_name = $_FILES['Filedata']['tmp_name'];
    $size = getimagesize($tmp_image_name);
    // проверяем, является ли файл изображением
    if(preg_match('{image/(.*)}is', $size['mime'], $p)) {
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
	  if($p[1] != 'gif') {
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
	  }
	  else 
	  {
		// сохраняем .gif с учетом анимации
		$im->writeImages('./images/'.$image_name.'.'.$p[1], true);
	  }
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
	  //$image_names[] = $image_name;
	  $_SESSION['image_name'] = $image_name;
	  echo $image_name;
    } 
    else 
    {
	  echo 'Вы можете загрузить только фото!';
    }

  }

?>



