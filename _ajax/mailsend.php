<?php
const APPOINTMENT_SUCCESS_MESSAGE = 'Спасибо! Ваша заявка отправлена. Мы свяжемся с вами в ближайшее время.';
const APPOINTMENT_ERROR_MESSAGE = 'Не удалось отправить заявку. Пожалуйста, позвоните нам по телефону.';

function mail_response($status, $success, $message) {
	header('Content-Type: application/json; charset=UTF-8');
	header('Cache-Control: no-store');
	header('X-Content-Type-Options: nosniff');
	http_response_code($status);

	echo json_encode(
		array('success' => $success, 'message' => $message),
		JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
	);
}

if(!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
	return mail_response(405, false, APPOINTMENT_ERROR_MESSAGE);
}

$required = array('name', 'phone', 'email', 'car', 'message');
foreach($required as $field) {
	if(!isset($_POST[$field]) || !is_string($_POST[$field]) || trim($_POST[$field]) === '') {
		return mail_response(422, false, APPOINTMENT_ERROR_MESSAGE);
	}
}

$email_raw = trim($_POST['email']);
if(strlen($email_raw) > 190 || preg_match('/[\r\n]/', $email_raw) || !filter_var($email_raw, FILTER_VALIDATE_EMAIL)) {
	return mail_response(422, false, APPOINTMENT_ERROR_MESSAGE);
}

$name = mb_substr(secur($_POST['name'], 'tr'), 0, 120, 'UTF-8');
$phone = mb_substr(secur($_POST['phone'], 'phone'), 0, 40, 'UTF-8');
$email = $email_raw;
$car = mb_substr(secur($_POST['car'], 'tr'), 0, 160, 'UTF-8');
$message = mb_substr(secur($_POST['message'], 'tr'), 0, 3000, 'UTF-8');

if($name === '' || strlen(preg_replace('/\D+/', '', $phone)) < 6 || $car === '' || $message === '') {
	return mail_response(422, false, APPOINTMENT_ERROR_MESSAGE);
}

$subj = 'Заявка на обслуживание';
$msg = 'Уважаемый администратор!<br/><br/>';
$msg .= 'Поступила новая заявка на обслуживание.<br/><br/>';
$msg .= 'Дата: <b>'.date('d.m.Y H:i').'</b><br/>';
$msg .= 'Имя: <b>'.htmlspecialchars($name, ENT_QUOTES, 'UTF-8').'</b><br/>';
$msg .= 'Телефон: <b>'.htmlspecialchars($phone, ENT_QUOTES, 'UTF-8').'</b><br/>';
$msg .= 'E-mail: <b>'.htmlspecialchars($email, ENT_QUOTES, 'UTF-8').'</b><br/>';
$msg .= 'Автомобиль: <b>'.htmlspecialchars($car, ENT_QUOTES, 'UTF-8').'</b><br/>';
$msg .= 'Описание проблемы:<br/><b>'.nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')).'</b><br/>';

if(PHP_OS_FAMILY === 'Windows' && trim((string)ini_get('sendmail_from')) === '') {
	error_log('Appointment mail delivery failed: PHP sendmail_from is not configured.');
	return mail_response(503, false, APPOINTMENT_ERROR_MESSAGE);
}

try {
	$sent = @my_mail(ADMIN_EMAIL, $subj, $msg);
} catch(Throwable $error) {
	error_log('Appointment mail delivery failed: '.$error->getMessage());
	$sent = false;
}

if(!$sent) return mail_response(502, false, APPOINTMENT_ERROR_MESSAGE);

return mail_response(200, true, APPOINTMENT_SUCCESS_MESSAGE);
?>
