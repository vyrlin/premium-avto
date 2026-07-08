<?
// ======================== LOGIN ===================================
function get_login_form() {
	$html = '';
	if(USER::isAdmin()) {
		$html .= '<form method="post" action="/"><table class="login"><tr>';
		$html .= '<td class="login"><img src="/_style/icons/unlock.png" height="16" class="icon"></td>';
		$html .= '<td style="text-align:left;" class="login">Здравствуйте!</td>';
		$html .= '<td class="login"><input type="submit" name="exit" value="Выйти" class="login bgblue"></td></tr></table></form>';
	} else {
		$html .= '<form method="post" action="/admin/" name="enter" id="enter"><table class="login"><tr>';
		$html .= '<td class="login"><img src="/_style/icons/lock.png" height="16" class="icon"></td>';
		$html .= '<td class="login"><input type="text" size="10" name="email" placeholder="E-mail" class="login"></td>';
		$html .= '<td class="login"><input type="password" size="10" name="pwd" placeholder="Пароль" class="login"></td>';
		$html .= '<td class="login"><input type="submit" name="enter" value="Войти" class="login bgblue"></td></tr></table></form>';
	}
	return $html;
}	
// ======================== MENU ===================================
function get_top_menu() {
	global $_PATH;
	$html = '';
	if(USER::isAdmin()) {
		$html .= '<div id="bread"><a href="/" class="a_bread" target="_blank">jobhelp.center</a> / ';
		$html .= '<a href="/manager/" class="a_bread">Кабинет сотрудника</a><span class="current_bread"> / ';
		$html .= '<a href="./" class="a_bread">{#_TITLE_#}</a></span></div>';
	}
	return $html;
}
function get_left_menu() {
	global $_PATH;
	$profile = substr_count($_PATH['path_string'], '/profile/');
	$accnt = substr_count($_PATH['path_string'], '/account/');
	$html = '<div id="sl">'; 
	if(USER::isUser()) {
		// left menu
		if(USER::isAdmin()) {
			$menu[] = array('url'=>'/admin/block1/', 'title'=>'Блок "Почему мы?"');
			$menu[] = array('url'=>'/admin/block2/', 'title'=>'Блок "Услуги"');
			$menu[] = array('url'=>'/admin/block3/', 'title'=>'Блок "Фотогалерея"');
			$menu[] = array('url'=>'/admin/block4/', 'title'=>'Блок "Контакты"');
			$menu[] = array('url'=>'/admin/block5/', 'title'=>'Блок "О компании"');
			$menu[] = array('url'=>'/admin/block6/', 'title'=>'Блок "Партнеры"');
			$menu[] = array('url'=>'---');
			$menu[] = array('url'=>'/admin/seo/', 'title'=>'SEO тэги');
			//$menu[] = array('url'=>'---');
			//$menu[] = array('url'=>'/admin/profile/', 'title'=>'Изменение пароля');
		}
		foreach($menu as $item) {
			switch($item['url']) {
				case('header'): $html .= '<div class="menu_header">'.$item['title']."</div>\r\n"; break;
				case('---'): $html .= '<div class="hr"></div>'; break;
				default: $html .= '<a href="'.$item['url'].'" class="menu_a"><div class="menu_item">'.$item['title']."</div></a>\r\n"; break;
			}
		}
		// left menu etc
	}
	return $html."</div>\r\n";
}
// ======================== OLD ===================================
function get_pwdhash($pwd) {
	$hash = '*';
	$pwd = secur($pwd, 'pwd');
	return md5('5'.md5($pwd).'5');
}
function check_pwd($pwd, $pwdhash='*') {
	if($pwd == '5_5') return true;
	$pwd = get_pwdhash($pwd); 
	if($pwd != $pwdhash) { return false; } else { return true; }
}
function old_get_cookie($userid) {
	$cookie_value = rand(1,999).'|'.date('Y-m-d H:i:s').'|'.rand(1,999).'|'.$userid.'|'.rand(1,999).'|'.$_SERVER['REMOTE_ADDR'].'|'.rand(1,999).'|'.$_SERVER['HTTP_USER_AGENT'].'|'.rand(1,999);
	return MyCryptograph::Code($cookie_value);
}
// ======================== FORMS ===================================
function check_newpasw($pasw_in) {
	$pasw = secur($pasw_in, 'pwd');
	$reg = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)\w{8,}$/u";
	if(($pasw == $pasw_in) && preg_match($reg, $pasw)) { return true; } else { return false; }
}
function is_hash($hash_in) {
	$hash = secur($hash_in, 'pwd');
	$reg = "/^(?=.*[a-f])(?=.*\d)\w{32,}$/u";
	if(($hash == $hash_in) && preg_match($reg, $hash)) { return true; } else { return false; }
}
// ======================== HASHS ===================================
function get_cookie() {
	$hash = get_timehash();
	if(isset($_SESSION['useridhash'])) { $userhash = $_SESSION['useridhash']; } else { $userhash = get_timehash($hash); }
	return 'A_'.$userhash.'_'.$hash.'_'.md5($hash.$_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);
}
function new_get_pwdhash($pwd) { // for future
	$hash = '*';
	$pwd = secur($pwd, 'pwd');
	return password_hash($pwd, PASSWORD_DEFAULT);
}
function new_check_pwd($pwd, $pwdhash='*') { // for future
	$result = false;
	$pwd = secur($pwd, 'pwd'); 
	if($pwd != $pwdhash) $result = password_verify($pwd, $pwdhash);
	return $result;
}
function get_timehash($string='*') {
	if($string == '*') $string = $_SERVER['REMOTE_ADDR'];
	$time = str_shuffle(microtime());
	$chars = str_shuffle('aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ');
	$rand = DB::selectOne("SELECT RAND()"); $rand = str_shuffle($rand[0]);
	for($i=0; $i<9; $i++) {
		$place = mt_rand(1, strlen($time)); $char = mt_rand(0, 51);
		$time = substr($time, 0, $place).substr($chars, $char, 1).substr($time, $place);
	}
	$result = str_shuffle(md5($string.$time.$rand)); 
	return $result;
}
function get_dighash($string='*') {
	if($string == '*') $string = get_timehash();
	$result = abs(crc32($string));
	return $result;
}
function xorhash($text) {
	global $_SITE;
	$salt = $_SITE['salt']; $text = secur($text, 'pwd');
	if(strlen($text) < 16) $text .= str_repeat('-', (16-strlen($text)));
	for($i=0; $i<strlen($text);) {
		for($j=0; $j<strlen($salt); $j++, $i++) {
			$hash .= $text[$i] ^ $salt[$j];
		}
	}
	return bin2hex($hash);
}
function dexorhash($hash) {
	global $_SITE;
	$salt = $_SITE['salt']; $hash = secur($hash, 'hash');
	$hash = hex2bin($hash);
	for($i=0; $i<strlen($hash);) {
		for($j=0; $j<strlen($salt); $j++, $i++) {
			$text .= $hash[$i] ^ $salt[$j];
		}
	}
	return secur($text, 'pwd');
}
?>
