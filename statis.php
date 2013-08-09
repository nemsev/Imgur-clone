<?php
  include_once 'lib/connect.php';
  require_once 'lib/functions.php';
  require_once 'lib/Savant3.php';
  
  require_login();

  $tpl = new Savant3();

  date_default_timezone_set('Asia/Novosibirsk');
  
  // выбираем коллекцию
  $images = $db->images;

  // всего изображений на сайте
  $total_images = $images->find()->count();
  
  // просмотр конкретного фото
  if(isset($_GET['img'])) {
	$img = $_GET['img'];
	$image_for_view = $images->findOne(array('gallery' => 0, 'image' => $img));
	$tpl->img = $image_for_view;
	$tpl->display('tpl/viewimage.tpl.php');
  }

  // подгрузка фоток
  if(isset($_GET['page'])) {
	$page = $_GET['page'];
    $date = date("d-m-Y",time()-($page*24*3600));
    $cursor1 = $images->find(array('gallery' => 0, 'title' => array('$ne' => ''), 'date' => new MongoRegex("/$date/")))->sort(array('date' => -1));
    $chislo_fotok = $cursor1->count();
    if($chislo_fotok == 0) {
	  echo '<div class="headerbox">';
	  echo '<div class="left">';
	  echo '<h2>Загрузки '.showDate(strtotime($date)).'</h2>';
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
	echo '<h2>Загрузки '.showDate(strtotime($date)).'</h2>';
    echo '</div>';
    echo '<div class="right">'.$chislo_fotok.'</div>';
	echo '<div class="clear"></div>';
	echo '</div>';
	echo '<div class="posts">';
    foreach($cursor1 as $image_cand){
	  echo "<div id=\"{$image_cand['image']}\" class=\"post\">";
	  echo "<a href=\"http://imgush.ru/statis.php?img={$image_cand['image']}\"><img src=\"http://i.imgush.ru/{$image_cand['image']}s.{$image_cand['ext']}\"></a>";
	  echo '</div>';}
	echo '<div class="clear"></div>';
    echo '</div>';
    echo '<div id="imagelist-loader" class="textbox">';
	echo '<img src="/img/album_loader.gif">';
	echo '</div>';
    }
  } elseif(!isset($_GET['page']) && !isset($_GET['img'])) {
	$date = date("d-m-Y");
	$cursor1 = $images->find(array('gallery' => 0, 'title' => array('$ne' => ''), 'date' => new MongoRegex("/$date/")))->sort(array('date' => -1));
	$today_img_number = $cursor1->count();
	// получить количество добавленных фото в галлерею сегодня
	$today_imgs_in_gal = $images->find(array('gallery' => 1, 'date' => new MongoRegex("/$date/")))->count();
	$tpl->today_imgs_in_gal = $today_imgs_in_gal;
    $tpl->total_images = $total_images;
    $tpl->today_img_number = $today_img_number;
    $tpl->cursor = $cursor1;
    $tpl->display('tpl/statis.tpl.php');
  }
  
  // добавить в галерею
  if(isset($_REQUEST['add']) && isset($_REQUEST['name'])) {
	$image_name = $_REQUEST['name'];
	$images->update( array( 'image' => $image_name ), array( '$set' => array('gallery' => 1) ) );
	header("Location: http://imgush.ru/statis"); // поменять на имя домена
  }
  
?>







