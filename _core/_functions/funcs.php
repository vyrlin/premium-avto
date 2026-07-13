<?php
// ======================== SECUR ===================================
function my_strip_tags($var, $dict=1) {
	$tmp = '';
	if($dict) $array = array('javascript', 'vbscript', 'execscript', 'eval', 'cookie', 'onload', 'onerror', 'fromCharCode', 'onMouse', 'Location', '()' );
	while($tmp != $var) { 
		$tmp = $var; 
		$var = preg_replace ( "/<script[^>]*?>.*?<\/script>/ui", '', $var);
		$var = preg_replace("/<[^<>]*>/u", '', $var); // удаление тэгов
//		$var = preg_replace("/\((([^()]*|(?R))*)\)/u", '', $var); // рекурсия
		$var = preg_replace("/\([^()]*\([^()]*\)[^()]*\)/u", '', $var); // удаление вложенных круглых скобок
		$var = preg_replace("/\(.*[^0-9a-zA-Zа-яА-ЯёЁ,!\?\s-]+.*\)/uU", '', $var); // удаление круглых скобок со спецсимволами внутри
		$var = preg_replace("/(\%|\\x|\&\#)\d[0-9a-zA-Z]/ui", '', $var); // вырезать %dd коды
		if($dict) $var = str_ireplace($array, '', $var);
	}
	$var = str_replace(array('<', '>', '()'), '', $var);
	return $var;
}
function secur($var, $type='', $length=0) {
	if(is_array($var)) {
		$tmp = array();
		foreach($var as $key=>$value) { $tmp[$key] = secur($value, $type,  $length); }
		return($tmp);
	}
	$var = trim($var);
	if($length !== 0) $var = substr($var, 0, $length);
	$var = trim($var);
	switch($type):
		case('none'): break;
		case('hex'): $var = bin2hex($var); break;
		case('dehex'): $var = hex2bin($var); break;
		case('t'): 
			$var = mysqli_real_escape_string(DB::$connect, $var); 
			$var = str_replace('\r\n', "\r\n", $var);
			break;
		case('te'):
			$var = my_strip_tags($var);
			$var = preg_replace("/[^a-zA-Z0-9@#№%$\&\*,:;!\?\(\)\[\]\{\}\.\/\\\|_\+=\s-]*/u", '', $var);
			break;
		case('tr'):
			$var = my_strip_tags($var);
			$var = preg_replace("/[^a-zA-Z0-9а-яА-ЯёЁ@#№%$\&\*,:;!\?\(\)\[\]\{\}\.\/\\\|_\+=\s-]*/u", '', $var);
			break;
		case('email'): $var = preg_replace("/[^a-zA-Z0-9@\._]*/u", '', $var); break;
		case('phone'): $var = preg_replace("/[^0-9\+\(\)\s-]*/u", '', $var); break;
		case('filename'): $var = preg_replace("/[\/\\\|]/iu", '', $var); break;
		case('pwd'): $var = preg_replace("/[^a-zA-Z0-9_]*/u", '', $var); break;
		case('hash'): $var = preg_replace("/[^a-fA-F0-9]*/u", '', $var); break;
		case('form'):
			$var = my_strip_tags($var);
			$var = stripslashes($var);
			$var = htmlspecialchars($var, ENT_QUOTES);
			break;
		case('i'): $var = preg_replace("/[^0-9]*/u", '', $var); break;
		case('im'): $var = preg_replace("/[^0-9-]*/u", '', $var); break;
		case('i:'): $var = preg_replace("/[^0-9:]*/u", '', $var); break;
		case('p'):
			$var = preg_replace("/[^0-9,\.]*/u", '', $var);
			$var = str_replace(',', '.', $var);
			break;
		case('pm'):
			$var = preg_replace("/[^0-9-,\.]*/u", '', $var);
			$var = str_replace(',', '.', $var);
			break;
		default: $var = mysqli_real_escape_string(DB::$connect, $var); break;
	endswitch;
	return($var);
}
// ======================== DATA ===================================
function get_block($number) {
	$number = secur($number, 'i');
	$html = '';
	switch($number) {
		case(1): 
			$txts = DB::select("SELECT * FROM texts WHERE block='$number' AND active='1' ORDER BY id LIMIT 2");
			foreach($txts as $t) {
				$html .= '<div class="text2cols"><h2 style="color:#ed1c24;">'.$t['title'].'</h2><br><br>';
				$html .= $t['text'].'</div>';
			}
			break;
		case(2): 
			$txts = DB::select("SELECT * FROM texts WHERE block='$number' AND active='1' ORDER BY id LIMIT 6");
			foreach($txts as $t) {
				$alt = htmlspecialchars(strip_tags($t['title']), ENT_QUOTES, 'UTF-8');
				$text = (strip_tags($t['text']) == $t['text']) ? nl2br($t['text'], false) : $t['text'];
				$html .= '<article class="service-card">';
				$html .= '<div class="service-card__media">';
				$html .= '<img src="/_style/serv'.$t['id'].'.png" class="service-card__image" alt="'.$alt.'">';
				$html .= '</div>';
				$html .= '<div class="service-card__body">';
				$html .= '<h3 class="service-card__title">'.$t['title'].'</h3>';
				$html .= '<div class="service-card__divider" aria-hidden="true"></div>';
				$html .= '<div class="service-card__text">'.$text.'</div>';
				$html .= '</div>';
				$html .= '</article>';
			}
			break;
		case(3): 
			$txts = DB::select("SELECT * FROM gallery WHERE active='1' ORDER BY id");
			foreach($txts as $t) {
				$alt = trim(strip_tags($t['text']));
				if(!$alt) $alt = 'Сервисный центр Premium Avto';
				$alt = htmlspecialchars($alt, ENT_QUOTES, 'UTF-8');
				$html .= '<a href="#" class="gallery-showcase__item" onClick="'."showModals('".$t['id']."')".'; return false;">';
				$html .= '<img src="/pics/'.$t['photo'].'.'.$t['ext'].'" class="gallery-showcase__image" alt="'.$alt.'" loading="lazy" decoding="async">';
				$html .= '</a>';
			}
			break;
		case(4): 
			$txt = DB::selectOne("SELECT * FROM texts WHERE block='$number' AND active='1' ORDER BY id LIMIT 1");
			if($txt) $html .= $txt['text'];
			break;
		case(5): 
			$txt = DB::selectOne("SELECT * FROM texts WHERE block='$number' AND active='1' ORDER BY id LIMIT 1");
			if($txt) $html .= $txt['text'];
			break;
		case(6): 
			$txts = DB::select("SELECT * FROM texts WHERE block='$number' AND active='1' ORDER BY id LIMIT 4");
			foreach($txts as $t) {
				$html .= '<div class="smallblock25_4cols"><h3>'.$t['title'].'</h3>';
				$html .= $t['text'].'</div>';
			}
			break;
	}
	return $html;
}
// ======================== INTERFACE ===================================
function echo_table($titles, $data, $aligns=0, $valigns=0, $fltr=true, $header=true, $variant='') {
	global $_TMP;
	if(count($data) && ((count($titles) == count($data[0])) || (count($titles) == count($data[0])-1))) {
		if($fltr) { 
			$_TMP['dtTable_count']++;
			$fltr = ' id="dtTable'.$_TMP['dtTable_count'].'" class="display dt-table"'; 
			include_once($_SERVER['DOCUMENT_ROOT'].'/_inc/DataTables/include'.$variant.'.php'); 
		} else { $fltr = ''; }
		if($header) { $h = ''; } else { $h = ' style="display:none;"'; }
		echo '<table'.$fltr.'><thead'.$h.'><tr>'."\r\n";
		$i = 0;
		foreach($titles as $title) { 
			$class = '';
			if(is_array($aligns) && isset($aligns[$i])) $class = ' class="align2'.$aligns[$i].'"'; 
			echo '<th'.$class.'>'.$title."</th>\r\n"; 
			$i++;
		}
		echo "</tr></thead><tbody>\r\n";
		foreach($data as $dt) { 
			echo "<tr>\r\n";
			$i = 0;
			foreach($dt as $d) { 
				if($i < count($titles)) {
					$class = '';
					if(is_array($aligns) && isset($aligns[$i])) $class = 'align2'.$aligns[$i]; 
					if(count($titles) != count($dt)) $class .= ' allocated';
					if($class) $class = 'class="'.$class.'"';
					if(is_array($valigns) && isset($valigns[$i])) { $valign = 'style="vertical-align:'.$valigns[$i].';"'; } else { $valign = ''; }
					echo '<td data-label="'.$titles[$i].'" '.$class.' '.$valign.'>'.$d."</td>\r\n"; 
					$i++; 
				}
			}
			echo "</tr>\r\n";
		}
		echo '</tbody></table>';
	} else { echo_att('db_null'); }
}
function in_div($data) {
	return '<div style="width:100%; height:100%; vertical-align:top; text-align:left;">'.$data.'</div>';
}
function echo_yes($text='', $plus='') {
	global $_YES;
	$text = secur($text, 'te'); $plus = secur($plus, 'tr');
	if(isset($_YES[$text])) { 
		if($plus) $plus = '<br>'.$plus;
		echo '<div class="success_msg">'.$_YES[$text].$plus.'</div>'; 
	} else { echo '<div class="error_msg">'.$_ERR['const_null'].'</div>'; }
}
function echo_att($text='', $plus='') {
	global $_ATT;
	$text = secur($text, 'te'); $plus = secur($plus, 'tr');
	if(isset($_ATT[$text])) { 
		if($plus) $plus = '<br>'.$plus;
		echo '<div class="attention_msg">'.$_ATT[$text].$plus.'</div>'; 
	} else { echo '<div class="error_msg">'.$_ERR['const_null'].'</div>'; }
}
function echo_err($text='', $plus='') {
	global $_ERR;
	$text = secur($text, 'te'); $plus = secur($plus, 'tr');
	if(isset($_ERR[$text])) { 
		if($plus) $plus = '<br>'.$plus;
		echo '<div class="error_msg">'.$_ERR[$text].$plus.'</div>'; 
	} else { echo '<div class="error_msg">'.$_ERR['const_null'].'</div>'; }
}
function echo_site_err($text='') {
	global $_ERR;
	$text = secur($text, 'te'); 
	if(isset($_ERR[$text])) { 
		return '<div class="error_msg">'.$_ERR[$text].'</div>'; 
	} else { return '<div class="error_msg">'.$_ERR['const_null'].'</div>'; }
}
function echo_msg($text='') {
	$text = secur($text, 'tr');
	if($text) echo '<div class="attention_msg">'.nl2br($text).'</div>'; 
}
function echo_ajax($type, $text='') {
	$prefix = '<div id="close_alert" onClick="closeAlert();">&#8855;</div>';
	switch($type) {
		case('yes'): echo $prefix; echo_yes($text); break;
		case('att'): echo $prefix; echo_att($text); break;
		case('err'): echo $prefix; echo_err($text); break;
		case('msg'): echo $prefix; echo_msg($text); break;
	}
}
function get_img($file, $plus='') {
	if(is_file($_SERVER['DOCUMENT_ROOT'].$file)) { return '<img src="'.$file.'" '.$plus.'>'; } else { return false; }
}
// ======================== FORMS ===================================
function prepare_data($data) {
	$result = array();
	foreach($data as $d) {
		$result[$d['type']] = $d['value'];
	}
	return $result;
}
function maybe_null($value) {
	if($value == '') { return 'NULL'; } else { return "'".$value."'"; }
}
function get_options($items, $plus=false, $sel_value='*') {
	$result = '';
	if($plus) $result .= '<option value="">---'.$plus."---</option>\r\n";
	foreach($items as $item) { 
		if($item['value'] == $sel_value) { $sel = ' selected'; } else { $sel = ''; }
		$result .= '<option value="'.$item['value'].'"'.$sel.'>'.$item['text']."</option>\r\n";
	}
	return $result;
}
function get_js_options($items, $sel_value=0) {
	$result = array();
	$i = 0;
	foreach($items as $item) { 
		if($item['value'] == $sel_value) { $sel = 1; } else { $sel = 0; }
		$result[$i] = array('value'=>$item['value'], 'text'=>$item['text'], 'selected'=>$sel);
		$i++;
	}
	return $result;
}
function load_userpic($file, $hash) {
	$type = exif_imagetype($file); 
	if($type == (IMAGETYPE_PNG || IMAGETYPE_JPEG || IMAGETYPE_GIF)) {
		switch($type) { 
			case(1): $origin = imagecreatefromgif($file); break; 
			case(2): $origin = imagecreatefromjpeg($file); break; 
			case(3): $origin = imagecreatefrompng($file); break; 
		}		
		$w = imagesx($origin); $h = imagesy($origin); $size = min($w, $h); $half = floor($size/2);
		$x = 0; $y = 0;
		if($w > $h) $x = floor(($w-$h)/2);
		if($w < $h) $y = floor(($h-$w)/2);
		$quad = imagecrop($origin, ['x'=>$x, 'y'=>$y, 'width'=>$size, 'height'=>$size]);

		$thumb = imagecreatetruecolor(USERPIC_PX, USERPIC_PX);
		if($type == 3) { imagealphablending($thumb, false); imagesavealpha($thumb, true); }
		imagecopyresized($thumb, $quad, 0, 0, 0, 0, USERPIC_PX, USERPIC_PX, $size, $size);
		@unlink($_SERVER['DOCUMENT_ROOT'].'/file/db/user_photo/'.$hash.'.png');
		$flag = imagepng($thumb, $_SERVER['DOCUMENT_ROOT'].'/file/db/user_photo/'.$hash.'.png'); 
	} else { $flag = false; }
	return $flag;
}
function my_mail($email, $header, $text) {
	global $_EMAIL_HEADERS;
	$head = '=?UTF-8?B?'.base64_encode(DOMAIN_NAME.': '.$header).'?=';
	if (mail($email, $head, $text, $_EMAIL_HEADERS)) { return true; } else { return false; }
}
// ======================== SERVICE ===================================
function get_xml_content($xml_tag, $xml_text, $u='u') {
	$preg_search = '/<'.$xml_tag.'[^>]*>(.*)<\/'.$xml_tag.'>/i'.$u;
	preg_match($preg_search, $xml_text, $result);
	if($result) { return $result[1]; } else { return FALSE; }
}
function slice_text($text, $length) {
	if(mb_strlen($text, "UTF-8") > $length) {
		$tmp = mb_strrpos(mb_substr($text, 0, $length, "UTF-8"), ' ', 0, "UTF-8");
		if (!$tmp) $tmp = $length-3; 
		$result = mb_substr($text, 0, $tmp, "UTF-8").'...';
	} else { $result = $text; }
	return $result;
}
function format_date($date) {
	return date('d.m.Y', strtotime($date));
}
function format_datetime($date, $nobr=false) {
	if($nobr) {
		return '<span style="white-space:nowrap;">'.date('d.m.Y H:i', strtotime($date)).'</span>';
	} else { return date('d.m.Y H:i', strtotime($date)); }
}
function format_phone($phone) {
	$tmp = secur($phone, 'i');
	if(strlen($tmp) == 11) {
		return '+7('.substr($phone, 1, 3).')'.substr($phone, 4, 3).'-'.substr($phone, 7, 2).'-'.substr($phone, 9, 2);
	} else { return $phone; }
}
function get_rand($range, $num=1) {
	$tmp_array = array_fill(1, $range, 0);
	$result = array_rand($tmp_array, $num);
	return $result;
}
function sort_by(&$data, $field, $ord='asc') { 
	if($ord == 'asc') {
		$code = "return strnatcmp(\$a['$field'], \$b['$field']);"; 
	} else { $code = "return strnatcmp(\$b['$field'], \$a['$field']);"; }
	usort($data, function ($a, $b) use ($code) {
    eval($code);
	}); 
} 
function order_by(&$data, $field, $ord='asc') { 
	if($ord == 'asc') {
		$code = "return strnatcmp(\$a['$field'], \$b['$field']);"; 
	} else { $code = "return strnatcmp(\$b['$field'], \$a['$field']);"; }
	usort($data, function ($a, $b) use ($code) {
    eval($code);
	}); 
} 

function parse_days($days) {
	$result = array();
	if ($days != '') {
		$tmp = explode('-', $days);
		if (count($tmp) == 2) { 
			$tmp[0] = 1*$tmp[0]; $tmp[1] = 1*$tmp[1];
			if ($tmp[0] < 10) $tmp[0] = '0'.$tmp[0]; 
			if ($tmp[1] < 10) $tmp[1] = '0'.$tmp[1]; 
			$result[0] = $tmp[0]; $result[1] = $tmp[1];
		} else { 
			$days = 1*$days;
			if ($days < 10) $days = '0'.$days; 
			$result[0] = $days;
		}
	}
	return($result);
}
function get_years($start=false, $finish=false) {
	if(!$start) { $start = 2015; } else { $start = secur($start, 'i'); }
	if(!$finish) { $finish = date("Y"); } else { $finish = secur($finish, 'i'); }
	$result = array();
	for ($y=$start; $y<=$finish; $y++) array_push($result, array('value'=>$y, 'text'=>$y)); 
	return $result;
}
function my_dump($var) {
	echo '<pre>'; print_r($var); echo '</pre>';
}

?>
