<?php
header("Content-Type: text/html; charset=utf-8");
setlocale( LC_ALL, 'ru_RU.utf8' );
include_once($_CORE_ROOT.'/_core/_config/config.php'); 
include_once($_CORE_ROOT.'/_core/_functions/funcs.php'); 
include_once($_CORE_ROOT.'/_core/_functions/auth.php'); 
include_once($_CORE_ROOT.'/_core/_classes/classes.php'); 
include_once($_CORE_ROOT.'/_core/_parser/path.php');  

DB::init();
USER::init();

PAGE::init();
PAGE::html();

DB::func('close');
?>
