<?
if(isset($_POST['name']) && isset($_POST['contact']) && isset($_POST['txt'])) { 
	$name = secur($_POST['name'], 'tr'); 
	$contact = secur($_POST['contact'], 'tr'); 
	$txt = secur($_POST['txt'], 'tr'); 
	if(1==1) {
		$ip = $_SERVER['REMOTE_ADDR'];
		//ini_set('default_socket_timeout', 10); 
		//$location = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"), true);
		
		$subj = 'Обратный звонок';
		$msg = '';
		$msg .= 'Уважаемый администратор!<br/><br/>';
		$msg .= 'Поступило сообщение.<br/>';
		$msg .= '<br/>';
		$msg .= 'Дата: <b>'.date('d.m.Y H:i').'</b><br/>';
		//$msg .= 'Регион: '.(@$location['city'] == '' && @$location['region'] == '' ? '<b>location n/a, ip '.($location['ip'] ? $location['ip'] : 'n/a').'</b>' : '<b>'.implode(', ', [$location['city'], $location['region'], $location['country'], $location['ip']]).'</b>').'<br/>';
		//$msg .= 'Название компании: <b>'.($order['company'] == '' ? NA : $order['company']).'</b><br/>';
		$msg .= 'ФИО: <b>'.$name.'</b><br/>';
		$msg .= 'Контакт: <b>'.$contact.'</b><br/>';
		$msg .= 'Сообщение: <b>'.nl2br($txt).'</b><br/>';
		
		if(my_mail(ADMIN_EMAIL, $subj, $msg)) { echo 'Ok'; } else { echo 'No'; }
	}
}
?>