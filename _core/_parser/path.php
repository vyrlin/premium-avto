<?php
if (strrpos($_GET['link'], '?') == 0) { $pos = strlen($_GET['link']); } else { $pos = strrpos($_GET['link'], '?'); }
$string = substr($_GET['link'], 0, $pos);

$path = explode('/', $string); array_shift($path); $path = array_reverse($path);
$count = count($path);
$page = $path[0];
$num = secur($page, 'i'); 
//if(!preg_match("/.+[0-9].+/", $hash)) $hash = false;
if($page == '') { $page = false;  }
$folder = str_replace($page, '', $string);

// базовый PATH
$_PATH = array('page'=>$page, 'count'=>$count, 'path_array'=>$path, 'path_string'=>$string, 'folder_string'=>$folder);
if($num != '') $_PATH['num'] = $num;
$hash = str_replace('.html', '', $page); $hash = secur($hash, 'hash');
if(is_hash($hash)) $_PATH['hash'] = $hash;

// subnum & subhash
if($count > 1) {
	$subnum = secur($path[1], 'i'); 
	$subhash = secur($path[1], 'hash'); 
	if($subnum == $path[1]) { 
		$_PATH['subnum'] = $subnum; 
		$_PATH['folder_string'] = preg_replace("/".$subnum."\/$/u", '', $_PATH['folder_string']); 
	} else {
		if(($subhash == $path[1]) && is_hash($subhash)) { 
			$_PATH['subhash'] = $subhash; 
			$_PATH['folder_string'] = preg_replace("/".$subhash."\/$/u", '', $_PATH['folder_string']); 
		}
	}
}

// subsubnum & subsubhash
if(count($path) > 2) {
	$subsubnum = secur($path[2], 'i'); 
	$subsubhash = secur($path[2], 'hash'); 
	if($subsubnum == $path[2]) { 
		$_PATH['subsubnum'] = $subsubnum; 
		$_PATH['folder_string'] = preg_replace("/".$subsubnum."\/$/u", '', $_PATH['folder_string']); 
	} else {
		if(($subsubhash == $path[2]) && is_hash($subsubhash)) { 
			$_PATH['subsubhash'] = $subsubhash; 
			$_PATH['folder_string'] = preg_replace("/".$subsubhash."\/$/u", '', $_PATH['folder_string']); 
		}
	}
}

// my_dump($_PATH);
// my_dump($_SESSION);
?>