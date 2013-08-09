<?php
  function check_login() {
    if(!empty($_SESSION['username'])) {
	  header('Location: index.php');
    }
  };

  function require_login() {
	session_start();

	if(empty($_SESSION['username'])) {
		header('Location: /statislogin.php');
	}
  };

  function showDate($date) {
	$stf = 0;
	$current_time = time();
	$diff =  $current_time - $date;
	
	$seconds = array('секунду', 'секунды', 'секунд');
	$minutes = array('минуту', 'минуты', 'минут');
	$hours = array('час', 'часа', 'часов');
	$days = array('день', 'дня', 'дней');
	$weeks = array('неделю', 'недели', 'недель');
	$months = array('месяц', 'месяца', 'месяцев');
	$years = array('год', 'года', 'лет');
	
	$phrase = array($seconds, $minutes, $hours, $days, $weeks, $months, $years);
	$length = array(1, 60, 3600, 86400, 604800, 2630880, 31570560);
	
	for($i = sizeof($length) - 1; ($i >= 0) && (($no = $diff/$length[$i]) <= 1); $i-- );
	if($i < 0) $i = 0;
	$_time = $current_time - ($diff % $length[$i]);
	$no = floor($no);
	$value = sprintf("%d %s ", $no, getPhrase($no, $phrase[$i]));
	
	if(($stf == 1) && ($i >= 1) && (($current_time - $_time) > 0)) $value .= time_ago($_time);
	
	if(strCaseCmp($value, '1 день ') == 0){
		return 'вчера';
	} else {
		return $value . ' назад';
	}
  };

  function getPhrase($number, $titles) {
	$cases = array(2,0,1,1,1,2);
	return $titles[ ($number%100>4 && $number%100<20)? 2 : $cases[min($number%10, 5)] ];
  };

  function showDateinGallery($date, $page) {
    // $date - время в секундах
    switch($page) {
	  case 2: return 'вчера'; break;
	  case 3: return '2 дня назад'; break;
	  case 4: return '3 дня назад'; break;
	  case 5: return '4 дня назад'; break;
	  case 6: return '5 дней назад'; break;
	  case 7: return '6 дней назад'; break;
	  case 8: return '1 неделю назад'; break;
	  //default: return $date;
	  default: $date_plus_one_day = strtotime($date.'+1 day');
			   $date = date("d-m-Y",$date_plus_one_day);
			   showRusDate($date);
    }
  };

  function showRusDate($date) {
    $d_m_y = explode("-", $date);
    switch ($d_m_y[1]){
	  case 1: $m='января'; break;
	  case 2: $m='февраля'; break;
	  case 3: $m='марта'; break;
	  case 4: $m='апреля'; break;
	  case 5: $m='мая'; break;
	  case 6: $m='июня'; break;
	  case 7: $m='июля'; break;
	  case 8: $m='августа'; break;
	  case 9: $m='сентября'; break;
	  case 10: $m='октября'; break;
	  case 11: $m='ноября'; break;
	  case 12: $m='декабря'; break;
	}
	echo '<h2>'.$d_m_y[0].'&nbsp;'.$m.'&nbsp;'.$d_m_y[2].'</h2>';
  };

  function checkVkLogin(){
    $session = array();
    $member = FALSE;
    $valid_keys = array('expire', 'mid', 'secret', 'sid', 'sig');
    // читаем куку которую установил Вконтакте
    if(isset($_COOKIE['vk_app_'.'2957270'])){
      $app_cookie = $_COOKIE['vk_app_'.'2957270'];
    } // APP_ID
    // если ВК установил куку
    if(isset($app_cookie)){
	  // разбиваем строку - куки на пары ключ=значение
	  $session_data = explode('&', $app_cookie, 10);
	  // для каждой пары ключ=значение из массива $session_data
	  foreach($session_data as $pair){
		// разрезать каждую пару на ключ и значение по символу '='
	    list($key, $value) = explode('=', $pair, 2);
	    if(empty($key) || empty($value) || !in_array($key, $valid_keys)){
		  continue;
	    }
	    // заполнить массив $session как ключ=>значение
	    $session[$key] = $value;
	  }
	  foreach($valid_keys as $key){
		if(!isset($session[$key])) return $member;
	  }
	  ksort($session);

	  $sign = '';
	  foreach($session as $key => $value){
		if($key != 'sig'){
			$sign .= ($key.'='.$value);
		}
	  }
	  $sign .= 'iAAKK5rkpRkuyNhf5zBK'; // инициализировать константу
	  $sign = md5($sign);
	  if($session['sig'] == $sign && $session['expire'] > time()){
	    $member = array(
	      'id' => intval($session['mid']),
	      'secret' => $session['secret'],
	      'sid' => $session['sid']
	    );
	  }

    }
    return $member;
  };

?>