<?php
  include_once 'lib/connect.php';
  require_once 'lib/functions.php';
  require_once 'lib/Savant3.php';

  // читаем галерею
  $images = $db->images;

  // читаем комментарии изображения
  $comments = $db->comments;

  $tpl = new Savant3();

  date_default_timezone_set('Asia/Novosibirsk');
  
  $data = explode("/", $_SERVER['REQUEST_URI']);
  if(isset($data[2]) && !empty($data[2])){
	$image_name = $data[2];
  }

  if(isset($_GET['page'])) {
	$page = $_GET['page'];
	$date = date("d-m-Y",time()-($page*24*3600));// на день назад
	$images_for_day = $images->find(array('gallery' => 1, 'date' => new MongoRegex("/$date/")))->sort(array('date' => -1))->limit(40 );
	if($images_for_day->count() == 0) {
	  echo '<div class="headerbox">';
	  echo '<div class="left">';
	  echo '<h2>Лучшие фото&nbsp;'.showDateinGallery($date, $page).'</h2>';
	  echo '</div>';
	  echo '<div class="clear"></div>';
	  echo '</div>';
      echo '<div class="posts">';
	  echo '<h2>Нет</h2>';
	  echo '</div>';
	  echo '<div id="imagelist-loader" class="textbox">';
	  echo '<img src="/img/album_loader.gif">';
	  echo '</div>';
    } else {
	echo '<div class="headerbox">';
	echo '<div class="left">';
	echo '<h2>Лучшие фото&nbsp;'.showDateinGallery($date, $page).'</h2>';
    echo '</div>';
	echo '<div class="clear"></div>';
	echo '</div>';
	echo '<div class="posts">';
    foreach($images_for_day as $image_cand){
	  echo "<div id=\"{$image_cand['image']}\" class=\"post\">";
	  echo "<a href=\"http://imgush.ru/gallery/{$image_cand['image']}\"><img src=\"http://i.imgush.ru/{$image_cand['image']}s.{$image_cand['ext']}\"></a>";
	  echo '</div>';}
	echo '<div class="clear"></div>';
    echo '</div>';
    echo '<div id="imagelist-loader" class="textbox">';
	echo '<img src="/img/album_loader.gif">';
	echo '</div>';
    }
  } elseif(!isset($_GET['page']) && !isset($image_name) && !isset($_POST['from']) && !isset($_POST['gotimage'])) {
	$date = date("d-m-Y",time()-(24*3600));// на день назад
	// лучшие фото сегодня, это лучшие фото, отобранные админами вчера
	$cursor = $images->find(array( 'gallery' => 1, 'date' => new MongoRegex("/$date/")))->sort(array('date' => -1))->limit(40);
	$tpl->cursor = $cursor;
	$tpl->display('tpl/gallery.tpl.php');
  }

  if(isset($image_name)) {
	// генерим случайное число, используется для показа фоток из галереи
	if(lcg_value() > 0.5) {
		// по возрастанию?
		$rnd = 1;
	} else {
		// по убыванию?
		$rnd = -1;
	}
	$date = date("d-m-Y",time()-(24*3600));// на день назад
	// показываем фото
	// достаем имя фото из БД
	$image = $images->findOne(array( 'gallery' => 1, 'image' => $image_name ));
	
	if($image){
	  // читаем все комментарии для данного изображения
	  $captions = $comments->find(array( 'image' => $image_name ))->sort(array('uploaded' => -1))->limit(10);

	  // считаем количество комментариев
	  $total_captions = $comments->find(array( 'image' => $image_name ))->count();

	  // увеличиваем кол-во просмотров
	  $images->update( array( 'image' => $image_name ), array( '$inc' => array('views' => 1) ) );

	  $cursor = $images->find(array( 'gallery' => 1, 'date' => new MongoRegex("/$date/"), 'image' => array('$ne' => $image_name)))->sort(array('date' => $rnd))->limit(6);

	  $tpl->image = $image;
	  $tpl->cursor = $cursor;
	  $tpl->captions = $captions;
	  $tpl->total_captions = $total_captions;
	  $tpl->comments = TRUE;
	  $tpl->display('tpl/lookimage.tpl.php');
	}
	else
	{
	  $tpl->display('tpl/404.tpl.php');
	}
	
	
  }
  elseif(isset($_POST['from']) && isset($_POST['gotimage'])) {
	$from = $_POST['from'];
    $more_captions = $comments->find(array('image' => $_POST['gotimage']))->skip($from)->limit(10)->sort(array('uploaded' => -1));
    $ind = $from;
    foreach($more_captions as $caption){
	  $ind++;
	  echo "<div id=\"$ind\" class=\"caption\">";
	  echo '<div class="author">';
	  echo "<span style=\"color:#4E76C9;\">{$caption['username']}</span>";
	  echo '<span>'.showDate($caption['uploaded']).'</span>';
	  echo '</div>';
	  echo htmlspecialchars($caption['caption']);
	  echo '</div>';
	}
  }
  
?>