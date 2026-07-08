<?
// ======================== DB ===================================
const DB_SERVER = 'localhost';
const DB_NAME = 'detalauto_pa';
const DB_PORT = 3306;
const DB_USER = 'root';
const DB_PWD  = '';
const DB_CHARSET = "utf8";
// ======================== COOKIE ===================================
const COOKIE_NAME = 'jh_hash';
const COOKIE_DOMAIN_NAME = '.premium-avto.ru';
const COOKIE_DAYS = 7;
const SESSION_TIMEOUT = 5; // период (в сек) перепроверки прав пользователя
const COOKIE_IP_TIMEOUT = 60; // период (в сек) перепроверки IP пользователя
// ======================== ETC ===================================
const DOMAIN_NAME = 'premium-avto.ru';
// ======================== SITE & TMP ===================================
$_SITE['step'] = 25;
$_TMP['dtTable_count'] = 0;
$_TMP_FORM_INPUTS = array();
// ======================== MAIL ===================================
$headers  = "MIME-Version: 1.0\n";
$headers .= "Return-Path: <robot@".DOMAIN_NAME.">\n";
$headers .= "X-Mailer: SquirrelMail/1.4.13\n";
$sign = preg_replace("/([^0-9])/", "", microtime());
$sign = substr(md5($sign), 0, 16);
$headers .= "Message-ID: <".$sign."@sweb.ru>\n";
$headers .= "X-MSMail-Priority: Normal\n";
$headers .= "X-Priority: 3\n";
$headers .= "Content-Type: text/html; charset=UTF-8\n";
$headers .= "From: ".DOMAIN_NAME." <robot@".DOMAIN_NAME.">\r\n";
$_EMAIL_HEADERS = $headers;
const ADMIN_EMAIL = 'virlin@mail.ru';
// ======================== MESSAGES ===================================
$_YES = array(); $_ATT = array(); $_ERR = array();

$_YES['db_insert'] = 'Данные успешно вставлены.';
$_YES['db_save'] = 'Данные успешно сохранены.';
$_YES['db_delete'] = 'Данные успешно удалены.';
$_YES['email'] = 'Емайл успешно отправлен.';
$_YES['file_save'] = 'Файл(ы) успешно сохранен(ы).';
$_YES['file_delete'] = 'Файл(ы) успешно удален(ы).';
$_YES['pasw_change'] = 'Пароль успешно изменен.';
$_YES['email'] = 'Письмо успешно отправлено.';
$_YES['sms'] = 'SMS успешно отправлено.';

$_ATT['db_null'] = 'Не найдено ни одной записи.';

$_ERR['user_ban'] = 'Вам закрыт доступ! Обратитесь к администрации сайта.';
$_ERR['user_auth'] = 'Неверный пароль!';
$_ERR['user_login'] = 'Такой пользователь отсутствует в системе!';
$_ERR['user_ip_timeout'] = 'Обратите внимание, менее 1 минуты назад Вы заходили в систему с другого IP';
$_ERR['db_null'] = 'Не найдено ни одной записи.';
$_ERR['db_connect'] = 'Нет подключения к БД!';
$_ERR['db_query'] = 'Ошибка запроса к БД!';
$_ERR['db_insert'] = 'Не могу вставить данные!';
$_ERR['db_save'] = 'Не могу сохранить данные!';
$_ERR['db_delete'] = 'Не могу удалить данные!';
$_ERR['db_index'] = 'Ошибка индекса!';
$_ERR['db_access'] = 'Недостаточно прав для управления данной записью!';
$_ERR['file'] = 'Файл(ы) не найден(ы)!';
$_ERR['file_save'] = 'Не могу сохранить файл(ы)!';
$_ERR['file_delete'] = 'Не могу удалить файл(ы)!';
$_ERR['file_upload'] = 'Не могу загрузить файл!';
$_ERR['file_mime'] = 'Неверный MIME-тип файла!';
$_ERR['email'] = 'Не могу отправить емайл!';
$_ERR['sms'] = 'Не могу отправить SMS!';
$_ERR['const_null'] = 'Не найдена константа!';
$_ERR['function_args'] = 'Неверные параметры!';
$_ERR['url'] = 'Неверный адрес страницы!';
$_ERR['template'] = 'Отсутствует файл шаблона!';
$_ERR['access'] = 'Недостаточно прав для просмотра данной страницы!';
$_ERR['form_require'] = 'Обратите внимание: не все обязательные поля заполнены.';
$_ERR['email'] = 'Не могу отправить письмо!';


?>