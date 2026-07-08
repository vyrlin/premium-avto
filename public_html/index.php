<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//ini_set('post_max_size', '200M'); ini_set('upload_max_filesize', '200M'); 
session_start();
$_CORE_ROOT = preg_replace("/\/[^\/]+?$/ui", '', $_SERVER['DOCUMENT_ROOT']);


if(preg_match("/^\/_ajax\/([a-zA-Z0-9\/_-]*)\.php$/ui", $_SERVER['REQUEST_URI'], $tmp)) {
	$_SESSION['ajax'] = $tmp[1];
	include ($_CORE_ROOT.'/_ajax/_loader.php');
} else { include ($_CORE_ROOT.'/_core/_parser/page.php'); }

?>