<?
if(isset($_SESSION['ajax'])) {
	$ajax = preg_replace("/[^a-zA-Z0-9\/_-]*/u", '', $_SESSION['ajax']);
	if($ajax != '') {
		include ($_CORE_ROOT.'/_core/_config/config.php'); 
		include ($_CORE_ROOT.'/_core/_classes/classes.php'); 
		include ($_CORE_ROOT.'/_core/_functions/funcs.php'); 
		include ($_CORE_ROOT.'/_core/_functions/auth.php');
		
		DB::init();
		USER::init();

		$allow = true;
		$_path = explode('/', $ajax);
		$dir = $_path[0];
		switch($dir) {
			case('admin'): 
				if(!USER::isAdmin()) $allow = false;
				break;
		}
		if($allow) { include ($_CORE_ROOT.'/_ajax/'.$ajax.'.php'); } else { echo 'No'; }
		
		DB::func('close');
	}
	unset($_SESSION['ajax']);
}
?>