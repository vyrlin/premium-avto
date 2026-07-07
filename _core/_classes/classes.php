<?
// ======================== PAGE ===================================
class PAGE {
	public static $title='premium-avto.ru';
	//public static $title_button='';
	public static $content;
	public static $template;
	public static $no_template=false;
	public static $modal=array();
	public static $modals=array();
	public static $seo=array();
	public static $errors=array();
	protected static $_instance; 
	private function __construct() { }
	private function __clone() { }
	private function __wakeup() { }
	public static function init() { 
		if (self::$_instance === null) self::$_instance = new self; 
		return self::$_instance; 
	}
	public static function setTitle($value) {
		self::$title = $value;
	}
	//public static function setTitleButton($value) {
		//self::$title_button = $value;
	//}
	public static function setSeo($seoname, $seovalue) {
		self::$seo[$seoname] = $seovalue;
	}
	public static function setModal($title, $content, $buttons='', $width=400, $top=false) {
		self::$modal['title'] = $title;
		self::$modal['content'] = $content;
		if($buttons) { 
			self::$modal['buttons'] = $buttons; 
		} else { self::$modal['buttons'] = '<input type="button" value="Ok" onClick="closeModal(); return false;">'; }
		self::$modal['width'] = $width;
		if($top) self::$modal['top'] = $top;
	}
	public static function addModal($id, $title, $content, $buttons='', $width='80%', $top=50) {
		$tmp['title'] = $title;
		$tmp['content'] = $content;
		if($buttons) { 
			$tmp['buttons'] = $buttons; 
		} else { $tmp['buttons'] = '<input type="button" value="Ok" onClick="closeModals('.$id.'); return false;">'; }
		$tmp['width'] = $width;
		$tmp['top'] = $top;
		self::$modals[$id] = $tmp;
	}
	public static function unsetModal() {
		self::$modal = array();
	}
	public static function unsetModals() {
		self::$modals = array();
	}
	public static function getYear() {
		return date("Y");
	}
	public static function redirect($uri) {
		header('Location: '.$uri);
	}
	public static function getContent() {
		global $_PATH; global $_CORE_ROOT; 
		$allow = true;
		$subdir = $subsubdir = false;
		$dir = $_PATH['path_array'][count($_PATH['path_array'])-1];
		if(isset($_PATH['path_array'][count($_PATH['path_array'])-2])) $subdir = $_PATH['path_array'][count($_PATH['path_array'])-2];
		if(isset($_PATH['path_array'][count($_PATH['path_array'])-3])) $subsubdir = $_PATH['path_array'][count($_PATH['path_array'])-3];
		switch($dir) {
			case('admin'): 
				if(!USER::isAdmin()) $allow = false;
				break;
		}
		if($allow) {
			if($_PATH['page']) {
				$filename = $_CORE_ROOT.$_PATH['path_string']; 
			} else { $filename = $_CORE_ROOT.$_PATH['path_string'].'index.html'; }
			if(!file_exists($filename)) $filename = $_CORE_ROOT.$_PATH['folder_string'].'index.html'; 
			if(!file_exists($filename)) $filename = $_CORE_ROOT.'/404.html'; 
			ob_start();
			include($filename);
			self::$content = ob_get_clean(); 
		} else { PAGE::redirect('/signin/'); }
	}
	public static function getModal() {
		$html = '';
		if(count(self::$modal) >= 4) {
			$html .= '<div id="modal-top"><span id="modal-title">'.self::$modal['title'].'</span><span id="modal-close"><a href="#" id="a-modal-close" onClick="closeModal(); return false;">X</a></span></div>';
			$html .= '<div id="modal-content">'.self::$modal['content'].'</div>';
			$html .= '<div id="modal-buttons"><span>'.self::$modal['buttons'].'</span></div>';
		}
		return $html;
	}
	public static function getModalWidth() {
		$html = '';
		if(isset(self::$modal['width']) || isset(self::$modal['top'])) {
			$html = ' style="';
			if(isset(self::$modal['width'])) $html .= 'width:'.self::$modal['width'].'px;';
			if(isset(self::$modal['top'])) $html .= 'top:'.self::$modal['top'].'px;';
			$html .= '"';
		}
		return $html;
	}
	public static function getModals() {
		$html = '';
		if(count(self::$modals)) {
			foreach(self::$modals as $id=>$m) {
				if(count($m) == 5) {
					$html .= '<div class="modals-wrap" id="modals-wrap_'.$id.'">';
					//$html .= '<div class="modals" id="modal_'.$id.'" style="width:'.$m['width'].'px; top:'.$m['top'].'px;">';
					$html .= '<div class="modals" id="modal_'.$id.'" style="top:'.$m['top'].'px;">';
					$html .= '<div class="modal-top"><span class="modal-title" id="modal-title_'.$id.'">'.$m['title'].'</span><span class="modal-close"><a href="#" class="a-modal-close" id="a-modal-close_'.$id.'" onClick="closeModals('."'".$id."'".'); return false;">X</a></span></div>';
					$html .= '<div class="modal-content" id="modal-content_'.$id.'">'.$m['content'].'</div>';
					$html .= '<div class="modal-buttons" id="modal-buttons_'.$id.'"><span>'.$m['buttons'].'</span></div>';
					$html .= "</div></div>\r\n";
				}
			}
		}
		return $html;
	}
	public static function html() {
		global $_PATH; global $_CORE_ROOT; 
		self::getContent();
		if(!self::$template) {
			$dir = $_PATH['path_array'][count($_PATH['path_array'])-1];
			switch($dir) {
				case('admin'): self::$template = 'cabinet'; break;
				case('signin'): self::$template = 'cabinet'; break;
				default: self::$template = 'landing'; break;
			}
		}
		$html = file_get_contents($_CORE_ROOT.'/_core/_parser/templates/'.self::$template.'.html'); 
		if($html) { 
			if(!self::$no_template) {
				if(self::$template == 'cabinet') { 
					$html = str_replace('{#_TOP_MENU_#}', get_top_menu(), $html);
					$html = str_replace('{#_LEFT_MENU_#}', get_left_menu(), $html);
					$html = str_replace('{#_SIGNIN_#}', get_login_form(), $html);
				}
				if(self::$template == 'landing') { 
					$html = str_replace('{#_BLOCK_1_#}', get_block(1), $html);
					$html = str_replace('{#_BLOCK_2_#}', get_block(2), $html);
					$html = str_replace('{#_BLOCK_3_#}', get_block(3), $html);
					$html = str_replace('{#_BLOCK_4_#}', get_block(4), $html);
					$html = str_replace('{#_BLOCK_5_#}', get_block(5), $html);
					$html = str_replace('{#_BLOCK_6_#}', get_block(6), $html);
				}
				$html = str_replace('{#_TITLE_#}', self::$title, $html);
				$html = str_replace('{#_YEAR_#}', self::getYear(), $html);
				$html = str_replace('{#_MODAL_WIDTH_#}', self::getModalWidth(), $html);
				$html = str_replace('{#_MODAL_#}', self::getModal(), $html);
				$html = str_replace('{#_MODAL_S_#}', self::getModals(), $html);
				if(count(self::$seo)) {
					$seotags = '';
					foreach(self::$seo as $seoname=>$seovalue) {
						$seotags .= '<META NAME="'.$seoname.'" CONTENT="'.$seovalue.'">'."\r\n";
					}
					$html = str_replace('{#_SEO_#}', $seotags, $html);
				} else { $html = str_replace('{#_SEO_#}', '', $html); }
				$html = str_replace('{#_CONTENT_#}', (self::$content), $html);
			} else { $html = self::$content; }
			echo $html;
		} else { echo 'template error!'; }
	}
}
// ======================== DB ===================================
class DB {
	protected static $_instance; 
	//private static $connect;
	public static $connect;
	private function __construct() {
		self::$connect = mysqli_connect(DB_SERVER, DB_USER, DB_PWD, DB_NAME);
		if(!self::$connect) { PAGE::$errors[] = 'db_connect'; } else { mysqli_query(self::$connect, "SET NAMES ".DB_CHARSET); }
	}
	private function __clone() { }
	private function __wakeup() { }
	public static function init() { 
		if (self::$_instance === null) self::$_instance = new self; 
		return self::$_instance; 
	}
	public static function query($sql) {
		$result = mysqli_query(self::$connect, $sql);
		return $result;
	}
	public static function select($sql, $key='*') {
		$result = array();
		$flag = true;
		if ($sql_result = mysqli_query(self::$connect, $sql)) { 
			while($item = mysqli_fetch_array($sql_result, MYSQLI_BOTH)) {
				if ($key != '*') { 
					if (!isset($item[$key])) $flag = false;
					$result[strval($item[$key])] = $item; 
				} else { array_push($result, $item); }
			}
			mysqli_free_result($sql_result);
		}
		if (!$flag) PAGE::$errors[] = 'db_index'; 
		return $result;
	}
	public static function selectOne($sql) {
		$result = self::select($sql);
		if(count($result)) { return $result[0]; } else { return false; }
	}
	public static function func($sql) {
		switch($sql) {
			case('insert_id'): $result = mysqli_insert_id(self::$connect); break;
			case('close'): $result = mysqli_close(self::$connect); break;
			default: $result = false; break;
		}
		return $result;
	}
	public static function secure($txt) {
		return mysqli_real_escape_string(self::$connect, $txt);
	}
}
// ======================== USERS & ENTITIES ===================================
class USER {
	public static $id = 0;
	public static $idhash = '';
	public static $role = null;
	protected static $_instance; 
	private function __construct() {
		// login
		if(isset($_POST['enter']) && isset($_POST['email']) && isset($_POST['pwd']) && !isset($_POST['exit']) && !isset($_GET['exit'])) {
			$login = secur($_POST['email'], 'email'); $pwd = secur($_POST['pwd'], 'pwd'); 
			$user = DB::selectOne("SELECT * FROM users WHERE email LIKE '$login' LIMIT 1"); 
			if($user) { 
				if(check_pwd($pwd, $user['pwdhash'])) { 
					if($user['active']) {
						$_SESSION['userid'] = $user['id'];
						$_SESSION['useridhash'] = $user['idhash'];
						$_SESSION['usertime'] = time();
						$_SESSION['userdata'] = $user;
						$cookie_hash = get_cookie();
						setcookie(COOKIE_NAME, $cookie_hash, time()+(60*60*24*COOKIE_DAYS), "/");
						DB::query("UPDATE users SET last_ip=INET_ATON('".$_SERVER['REMOTE_ADDR']."'), last_date=NOW() WHERE id='".$user['id']."' LIMIT 1");
						self::getUser($user); 
						switch(self::$role) {
							case('admin'): PAGE::redirect('/admin/'); break;
						}
					} else { PAGE::redirect('/'); }
				} else { PAGE::redirect('/'); }
			} else { PAGE::redirect('/'); }
		}
		// loguot
		if(isset($_POST['exit']) || isset($_GET['exit'])) {
			setcookie(COOKIE_NAME, '', time()-10000, "/"); 
			setcookie(session_name(), '', time()-10000, "/"); 
			foreach($_SESSION as $key=>$value) { unset($_SESSION[$key]); }
		}
		// auto enter
		if(!isset($_POST['exit']) && !isset($_GET['exit']) && !isset($_POST['enter'])) { 
			self::isUser(); 
		}
	}
	private function __clone() { }
	private function __wakeup() { }
	public static function init() { 
		if (self::$_instance === null) self::$_instance = new self; 
		return self::$_instance; 
	}
	private static function getUser($user) { 
		if(count($user)) {
			self::$id = $user['id'];
			self::$idhash = $user['idhash'];
			self::$role = $user['role'];
		} 
	}
	public static function isUser() { 
		$flag = false;
		if(isset($_SESSION['userid']) && isset($_SESSION['usertime']) && isset($_SESSION['userdata'])) { 
			if(($_SESSION['usertime']+SESSION_TIMEOUT) < time()) { 
				$user = DB::selectOne("SELECT * FROM users WHERE id='".$_SESSION['userid']."' AND active='1' AND last_ip=INET_ATON('".$_SERVER['REMOTE_ADDR']."') LIMIT 1"); 
				if($user) { 
					$_SESSION['usertime'] = time(); 
					$_SESSION['userdata'] = $user;
					self::getUser($user);
					$flag = true;  
				}
			} else { 
				self::getUser($_SESSION['userdata']);
				$flag = true;  
			}
		} else {  
			if(isset($_COOKIE[COOKIE_NAME]) && !isset($_POST['enter']) && !isset($_POST['exit']) && !isset($_GET['exit'])) { 
				$jh_hash = secur($_COOKIE[COOKIE_NAME], 'pwd');
				$hashes = explode('_', $jh_hash);
				if(count($hashes) == 4) {
					$key = $hashes[0]; $idhash = secur($hashes[1], 'hash'); $hash_key = secur($hashes[2], 'hash');
					$user = DB::selectOne("SELECT * FROM users WHERE idhash='$idhash' AND active='1' LIMIT 1");
					if($user) { 
						$_SESSION['userid'] = $user['id'];
						$_SESSION['useridhash'] = $user['idhash'];
						$_SESSION['usertime'] = time();
						$_SESSION['userdata'] = $user;
						self::getUser($user);
						$flag = true; 
						setcookie('premium-avto_cookie', '', time()-10000, "/"); 
					}
				}
			}
		}
		return $flag;
	}
	public static function isAdmin() { 
		if(self::isUser() && (self::$role == 'admin')) { return true; } else { return false; }
	}
}
// ======================== FORMS ===================================
$_methods = array('post', 'get', 'both', 'session');
class Form {
	public $id;
	public $name;
	public $action;
	public $method;
	public $required_flag;
	public $ext='';
	public $hide=false;
	public $inputs=array();
	function __construct($name, $method='post', $action='*') {
		global $_TMP_FORM_INPUTS;
		$this->inputs = $_TMP_FORM_INPUTS;
		$this->action = $action;
		$this->required_flag = false;
		if(is_array($name)) {
			$this->name = secur($name['name'], 'te'); $this->id = secur($name['id'], 'te');
		} else { $this->name = secur($name, 'te'); $this->id = $this->name; }
		switch($method) {
			case('get'): $this->method = 'get'; break;
			case('both'): $this->method = 'both'; break;
			case('session'): $this->method = 'session'; break;
			default: $this->method = 'post'; break;
		}
		$_TMP_FORM_INPUTS = array();
	}
	public function setAttr($attrname, $attrvalue) {
		$this->$attrname = $attrvalue;
	}
	public function setInputsStyle($value) {
		$inputs = $this->inputs;
		foreach($inputs as $input) { 
			switch(get_class($input)) {
				//case('InputDateOld'): $input->days->style = $value; $input->month->style = $value; $input->year->style = $value; break;
				default: $input->style = $value; break;
			}
		}
	}
	public function setInputsExt($value) {
		$inputs = $this->inputs;
		foreach($inputs as $input) {
			switch(get_class($input)) {
				//case('InputDateOld'): $input->days->ext .= $value; $input->month->ext .= $value; $input->year->ext .= $value; break;
				default: $input->ext .= $value; break;
			}
		}
	}
	public function catchValues($values=array()) {
		$fileflag = false; $inputs = $this->inputs; $result = array();
		$action_flag = false;
		switch($this->method) {
			case('post'): if(isset($_POST[$this->name])) $action_flag = true; break;
			case('get'): if(isset($_GET[$this->name])) $action_flag = true; break;
			case('both'): if(isset($_GET[$this->name]) || isset($_POST[$this->name])) $action_flag = true; break;
		}
		foreach($inputs as $input) { 
			$input->formmethod = $this->method; $input->formname = $this->name;
			switch(get_class($input)) {
				case('InputDateOld'): 
					$input->value = $input->year->value.'-'.$input->month->value.'-'.$input->days->value;
					$result[$input->name] = $input->value;
					break;
				case('InputPlace'): 
					$input->value = $input->place_code->value;
					$result[$input->name] = $input->place_code->value;
					$result[$input->name.'_text'] = $input->place->value;
					$result[$input->name.'_id'] = $input->place_code->value;
					break;
				case('InputMetro'): 
					$input->value = $input->metro_id->value;
					$result[$input->name] = $input->metro_id->value;
					$result[$input->name.'_text'] = $input->metro->value;
					$result[$input->name.'_id'] = $input->metro_id->value;
					break;
				default: 
					if(isset($values[$input->name])) { $input->catchValue($this->method, $values[$input->name]); } else { $input->catchValue($this->method); }
					$result[$input->name] = $input->value; 
					break;
			}
			if($action_flag && $input->required) {
				switch(get_class($input)) {
					case('InputDateOld'): 
						if(($input->days->value == '') || ($input->month->value == '') || ($input->year->value == '')) $this->required_flag = true;
						break;
					case('InputSelectMulti'): 
						if(($input->value == '') && (!is_array($input->value) || (count($input->value)==0))) $this->required_flag = true; 
						break;
					default: 
						if($input->value == '') $this->required_flag = true; 
						break;
				}
			}
			if(get_class($input) == 'InputFile') $fileflag = true;

		}
		if($fileflag) $this->ext .= ' enctype="multipart/form-data"';
		return $result;
	}
	public function resetValues() {
		$inputs = $this->inputs;
		foreach($inputs as $input) { 
			$input->value = '';
		}
	}
	public function html($type='open') {
		if($this->hide) return '';
		if($this->action != '*') { $action = ' action="'.$this->action.'"'; } else { $action = ''; }
		switch($type) {
			case('open'): $html = '<form name="'.$this->name.'" id="'.$this->id.'"'.$action.' method="'.$this->method.'" '.$this->ext.' autocomplete="off" novalidate><input type=hidden name="'.$this->name.'">'; break;
			case('close'): $html = '</form>';  break;
		}
		return $html."\r\n";
	}
	public function readOnly() {
		$this->hide = true;
		$inputs = $this->inputs;
		foreach($inputs as $input) { 
			$input->readonly = true; 
			$input->disabled = true;
			switch(get_class($input)) {
				case('InputButton'): $input->hideMe(); break;
				case('InputHidden'): $input->hideMe(); break;
			}
		}
	}
			
}
class Input {
	public $id;
	public $formmethod;
	public $formname;
	public $name;
	public $value='';
	public $label='';
	public $required;
	public $helper='';
	public $readonly=false;
	public $disabled=false;
	public $hide=false;
	public $ext='';
	public $style='';
	public $cssclass='';
	public $html;
	function __construct($name, $secur='t', $required=false) {
		global $_TMP_FORM_INPUTS;
		if(is_array($name)) {
			$this->name = secur($name['name'], 'te'); $this->id = secur($name['id'], 'te');
		} else { $this->name = secur($name, 'te'); $this->id = $this->name; }
		if ($this->name != '') {
			$this->secur = $secur;
			//$this->value = secur($value, $secur);
			$this->required = $required;
			$this->required_flag = false;
			$helper_html = '<div class="helper" id="'.$this->name.'_helper">This is Helper</div>';
			$_TMP_FORM_INPUTS[] = $this; 
		} else { echo 'Form name error!'; }
	}
	function catchValue($method='post', $value=NULL) {
		if($value == NULL) {
			switch($method) {
				case('post'): 
					if(isset($_POST[$this->name])) $this->value = secur($_POST[$this->name], $this->secur); 
					break;
				case('get'): 
					if(isset($_GET[$this->name])) $this->value = secur($_GET[$this->name], $this->secur); 
					break;
				default: 
					if(isset($_GET[$this->name])) $this->value = secur($_GET[$this->name], $this->secur); 
					if(isset($_POST[$this->name])) $this->value = secur($_POST[$this->name], $this->secur);
					break;
			}
		} else { $this->value = secur($value, $this->secur); }
		$action = false;
		switch($this->formmethod) {
			case('get'): if(isset($_GET[$this->formname])) $action = true; break;
			case('post'): if(isset($_POST[$this->formname])) $action = true; break;
			default: if(isset($_POST[$this->formname]) || isset($_GET[$this->formname])) $action = true; break;
		}
		if(($this->required)) $this->ext .= ' required';
		if(($this->required) && $action) {
			if($this->value == '') { $this->cssclass .= ' required'; }
		}
	}
	public function set($newvalue) {
		$this->value = secur($newvalue, $this->secur);
	}
	public function setAttr($attrname, $attrvalue) {
		$this->$attrname = $attrvalue;
	}
	public function setHelper($newvalue) {
//		$this->helper = '<div class="helper" id="'.$this->name.'_helper">'.secur($newvalue, 'tt').'</div>';
		$this->helper = secur($newvalue, 'tr');
	}
	public function get() {
		return $this->value;
	}
	public function label() {
		return $this->label;
	}
	public function hideMe() { 
		$this->hide = true;
	}
}
class InputText extends Input {
	public function html($size='*') {
		if($this->hide) return '';
		$ext = $this->ext;
		if($size != '*') $this->style .= 'width:'.secur($size, 'i').'px;';
		if($this->style != '') $ext .= ' style="'.$this->style.'"';
		if($this->cssclass != '') $ext .= ' class="'.$this->cssclass.'"';
		if($this->disabled) $ext .= ' disabled';
		if($this->readonly) $ext .= ' readonly';
		//if($size != '*') $ext .= ' size="'.secur($size, 'i').'"';
		$this->html = '<input type="text" name="'.$this->name.'" id="'.$this->id.'" value="'.secur($this->value, 'form').'" '.$ext.'>';
	// $this->html .= $this->helper;
		return $this->html."\r\n";
	}
}
class InputDate extends Input {
	function __construct($name, $required=false) {
		global $_TMP_FORM_INPUTS;
		if(is_array($name)) {
			$this->name = secur($name['name'], 'te'); $this->id = secur($name['id'], 'te');
		} else { $this->name = secur($name, 'te'); $this->id = $this->name; }
		if ($this->name != '') {
			$this->secur = 'im';
			$this->value = date("Y-m-d");
			$this->required = $required;
			$this->required_flag = false;
			$_TMP_FORM_INPUTS[] = $this; 
		} else { echo 'Form name error!'; }
	}
	public function html() {
		if($this->hide) return '';
		$ext = $this->ext;
		if($this->style != '') $ext .= ' style="'.$this->style.'"';
		if($this->cssclass != '') $ext .= ' class="'.$this->cssclass.'"';
		if($this->disabled) $ext .= ' disabled';
		if($this->readonly) $ext .= ' readonly';
		$this->html = '<input type="date" name="'.$this->name.'" id="'.$this->id.'" value="'.secur($this->value, 'form').'" '.$ext.'>';
	// $this->html .= $this->helper;
		return $this->html."\r\n";
	}
}
class InputTime extends Input {
	function __construct($name, $required=false) {
		global $_TMP_FORM_INPUTS;
		if(is_array($name)) {
			$this->name = secur($name['name'], 'te'); $this->id = secur($name['id'], 'te');
		} else { $this->name = secur($name, 'te'); $this->id = $this->name; }
		if ($this->name != '') {
			$this->secur = 'i:';
			$this->value = date("H:i");
			$this->required = $required;
			$this->required_flag = false;
			$_TMP_FORM_INPUTS[] = $this; 
		} else { echo 'Form name error!'; }
	}
	public function html() {
		if($this->hide) return '';
		$ext = $this->ext;
		if($this->style != '') $ext .= ' style="'.$this->style.'"';
		if($this->cssclass != '') $ext .= ' class="'.$this->cssclass.'"';
		if($this->disabled) $ext .= ' disabled';
		if($this->readonly) $ext .= ' readonly';
		$this->html = '<input type="time" name="'.$this->name.'" id="'.$this->id.'" value="'.secur($this->value, 'form').'" '.$ext.'>';
	// $this->html .= $this->helper;
		return $this->html."\r\n";
	}
}
class InputCK extends Input {
	public function html($width='*', $height='*') {
		if($this->hide) return '';
		$ext = $this->ext;
		$this->html = '<script src="/_inc/_ckeditor/ckeditor.js"></script>';
		if($this->disabled) $ext .= ' disabled';
		if($this->readonly) $ext .= ' readonly';
		if(($width != '*') || ($height != '*')) {
			$w = $h = '100%';
			if($width != '*') $w = $width.'px';
			if($height != '*') $h = $height.'px';
			$wh = 'editor.resize("'.$w.'", "'.$h.', true")';
		} else { $wh = ''; } $wh = '';
		$this->html .= '<textarea name="'.$this->name.'" id="'.$this->id.'" '.$ext.'>'.$this->value.'</textarea>';
		$this->html .= '<script>var editor = CKEDITOR.replace("'.$this->id.'", { resize_enabled: false }); '.$wh.'</script>';
		return $this->html."\r\n";
	}
}
class InputCKFull extends Input {
	public function html($width='*', $height='*') {
		if($this->hide) return '';
		$ext = $this->ext;
		$this->html = '<script src="/_inc/_ckeditor/ckeditor.js"></script>';
		if($this->disabled) $ext .= ' disabled';
		if($this->readonly) $ext .= ' readonly';
		if($width != '*') $ext .= ' width="'.secur($width, 'i').'"';
		if($height != '*') $ext .= ' height="'.secur($height, 'i').'"';
		$this->html .= '<textarea name="'.$this->name.'" id="'.$this->id.'" '.$ext.'>'.$this->value.'</textarea>';
		$this->html .= '<script>';
		$this->html .= "CKEDITOR.replace(".$this->id.", { customConfig: '/_inc/_ckeditor/config_full.js' });";
		$this->html .= 'CKEDITOR.replace("'.$this->id.'");</script>';
		return $this->html."\r\n";
	}
}
class InputCKMini extends Input {
	public function html($width='*', $height='*') {
		if($this->hide) return '';
		$ext = $this->ext;
		$this->html = '<script src="/_inc/_ckeditor/ckeditor.js"></script>';
		if($this->disabled) $ext .= ' disabled';
		if($this->readonly) $ext .= ' readonly';
		if($width != '*') $ext .= ' width="'.secur($width, 'i').'"';
		if($height != '*') $ext .= ' height="'.secur($height, 'i').'"';
		$this->html .= '<textarea name="'.$this->name.'" id="'.$this->id.'" '.$ext.'>'.$this->value.'</textarea>';
		$this->html .= '<script>';
		$this->html .= "CKEDITOR.replace(".$this->id.", { customConfig: '/_inc/_ckeditor/config_mini.js' });</script>";
		return $this->html."\r\n";
	}
}
class InputEmail extends Input {
	public function html($size='*') {
		if($this->hide) return '';
		$ext = $this->ext;
		if($size != '*') $this->style .= 'width:'.secur($size, 'i').'px;';
		if($this->style != '') $ext .= ' style="'.$this->style.'"';
		if($this->cssclass != '') $ext .= ' class="'.$this->cssclass.'"';
		if($this->disabled) $ext .= ' disabled';
		if($this->readonly) $ext .= ' readonly';
		//if($size != '*') $ext .= ' size="'.secur($size, 'i').'"';
		$this->html = '<input type="email" placeholder="user@yandex.ru" name="'.$this->name.'" id="'.$this->id.'" value="'.secur($this->value, 'form').'" '.$ext.'>';
		return $this->html."\r\n";
	}
}
class InputPassword extends Input {
	public function html($size='*') {
		if($this->hide) return '';
		$ext = $this->ext;
		if($size != '*') $this->style .= 'width:'.secur($size, 'i').'px;';
		if($this->style != '') $ext .= ' style="'.$this->style.'"';
		if($this->cssclass != '') $ext .= ' class="'.$this->cssclass.'"';
		if($this->disabled) $ext .= ' disabled';
		if($this->readonly) $ext .= ' readonly';
		//if($size != '*') $ext .= ' size="'.secur($size, 'i').'"';
		$this->html = '<input type="password" name="'.$this->name.'" id="'.$this->id.'" value="" '.$ext.'>';
		return $this->html."\r\n";
	}
}
class InputCheckbox extends Input {
	function __construct($name, $value=0) {
		global $_TMP_FORM_INPUTS;
		if(is_array($name)) {
			$this->name = secur($name['name'], 'te'); $this->id = secur($name['id'], 'te');
		} else { $this->name = secur($name, 'te'); $this->id = $this->name; }
		if ($this->name != '') {
			$this->secur = 'i';
			$this->value = $value;
			$_TMP_FORM_INPUTS[] = $this; 
		} else { echo 'Form name error!'; }
	}
	function catchValue($method='post', $value=NULL) {
		if($value == NULL) {
			$this->value = 0; 
			switch($method) {
				case('post'): if(isset($_POST[$this->name])) $this->value = 1; break;
				case('get'): if(isset($_GET[$this->name])) $this->value = 1; break;
				default: 
					if(isset($_GET[$this->name])) $this->value = 1; 
					if(isset($_POST[$this->name])) $this->value = 1;
					break;
			}
		} else { 
			if($value != 0) { $this->value = 1; } else { $this->value = 0; }
		}
	}
	public function html() {
		if($this->hide) return '';
		$ext = $this->ext;
		if($this->style != '') $ext .= ' style="'.$this->style.'"';
		if($this->cssclass != '') $ext .= ' class="'.$this->cssclass.'"';
		if($this->disabled) $ext .= ' disabled';
		if($this->readonly) $ext .= ' readonly';
		if($this->value > 0) $ext .= ' checked';
		$this->html = '<input type="checkbox" name="'.$this->name.'" id="'.$this->id.'" '.$ext.'>';
		return $this->html."\r\n";
	}
}
class InputRadio extends Input {
	public function html($id, $val, $checked=null) {
		if($this->hide) return '';
		$ext = $this->ext;
		if($this->style != '') $ext .= ' style="'.$this->style.'"';
		if($this->cssclass != '') $ext .= ' class="'.$this->cssclass.'"';
		if($this->disabled) $ext .= ' disabled';
		if($this->readonly) $ext .= ' readonly';
		if((($this->value == $val) && ($checked === null)) || ($checked === true)) $ext .= ' checked';
		$this->html = '<input type="radio" name="'.$this->name.'" id="'.$id.'" value="'.$val.'" '.$ext.'>';
		return $this->html."\r\n";
	}
}
class InputHidden extends Input {
	public function html() {
		if($this->hide) return '';
		$ext = $this->ext;
		if($this->disabled) $ext .= ' disabled';
		$this->html = '<input type="hidden" name="'.$this->name.'" id="'.$this->id.'" value="'.secur($this->value, 'form').'" '.$ext.'>';
		return $this->html."\r\n";
	}
}
class InputTextarea extends Input {
	public function html($rows=0, $cols=0) {
		if($this->hide) return '';
		$ext = $this->ext;
		$rows = secur($rows, 'i'); $cols = secur($cols, 'i');
		if($rows) $this->style .= 'width:'.$rows.'px;'; if($cols) $this->style .= 'height:'.$cols.'px;';
		//if($rows) $ext .= ' rows="'.$rows.'"'; if($cols) $ext .= ' cols="'.$cols.'"';
		if($this->style != '') $ext .= ' style="'.$this->style.'"';
		if($this->cssclass != '') $ext .= ' class="'.$this->cssclass.'"';
		if($this->disabled) $ext .= ' disabled';
		if($this->readonly) $ext .= ' readonly';
		//$this->html = '<textarea name="'.$this->name.'" id="'.$this->id.'" '.$ext.' wrap="virtual">'.secur($this->value, 'form').'</textarea>';
		$this->html = '<textarea name="'.$this->name.'" id="'.$this->id.'" '.$ext.' wrap="virtual">'.$this->value.'</textarea>';
		return $this->html."\r\n";
	}
}
class InputSelect extends Input {
	public function html($options) {
		if($this->hide) return '';
		$ext = $this->ext;
		if($this->style != '') $ext .= ' style="'.$this->style.'"';
		if($this->cssclass != '') $ext .= ' class="'.$this->cssclass.'"';
		if($this->disabled) $ext .= ' disabled';
		if($this->readonly) $ext .= ' readonly';
		$this->html = '<select name="'.$this->name.'" id="'.$this->id.'" '.$ext.'>'."\r\n";
		foreach($options as $opt) {
			if($opt['value'] == $this->value) { $sel = ' selected'; } else { $sel = ''; }
			if(isset($opt['group'])) $sel .= ' disabled="disabled"';
			$this->html .= '<option value="'.$opt['value'].'"'.$sel.'>'.$opt['text'].'</option>'."\r\n";
		}
		$this->html .= '</select>';
		return $this->html."\r\n";
	}
}
class InputSelectMulti extends Input {
	public function html($options) {
		if($this->hide) return '';
		$ext = $this->ext;
		if($this->style != '') $ext .= ' style="'.$this->style.'"';
		if($this->cssclass != '') $ext .= ' class="'.$this->cssclass.'"';
		if($this->disabled) $ext .= ' disabled';
		if($this->readonly) $ext .= ' readonly';
		$this->html = '<select name="'.$this->name.'[]" id="'.$this->id.'" multiple '.$ext.'>'."\r\n";
		foreach($options as $opt) {
			$vls = $this->value;
			if(in_array($opt['value'], $vls)) { $sel = ' selected'; } else { $sel = ''; }
			if(isset($opt['group'])) $sel .= ' disabled="disabled"';
			$this->html .= '<option value="'.$opt['value'].'"'.$sel.'>'.$opt['text'].'</option>'."\r\n";
		}
		$this->html .= '</select>';
		return $this->html."\r\n";
	}
}
class InputFile extends Input {
	public function html($size='*') {
		if($this->hide) return '';
		$ext = $this->ext;
		if($size != '*') $this->style .= 'width:'.secur($size, 'i').'px;';
		if($this->style != '') $ext .= ' style="'.$this->style.'"';
		//if($this->cssclass != '') $ext .= ' class="'.$this->cssclass.'"';
		if($this->disabled) $ext .= ' disabled';
		//if($size != '*') $ext .= ' size="'.secur($size, 'i').'"';
		$this->html = '<label class="fileinput-label">Загрузить файл<input type="file" name="'.$this->name.'" id="'.$this->id.'" value="'.$this->value.'" '.$ext.' class="fileinput-file" onChange="inputFile('."'".$this->id."'".');"></label><span id="file_'.$this->id.'" class="show-hide">yyy</span>';
		return $this->html."\r\n";
	}
}
class InputButton extends Input {
	function __construct($name, $value='') {
		global $_TMP_FORM_INPUTS;
		if(is_array($name)) {
			$this->name = secur($name['name'], 'te'); $this->id = secur($name['id'], 'te');
		} else { $this->name = secur($name, 'te'); $this->id = $this->name; }
		if ($this->name != '') {
			$this->value = secur($value, 't');
			$_TMP_FORM_INPUTS[] = $this; 
		} else { echo 'Form name error!'; }
	}
	public function html($bg='blue', $type='submit') {
		if($this->hide) return '';
		$ext = $this->ext;
		if($this->style != '') $ext .= ' style="'.$this->style.'"';
		if($this->cssclass != '') $ext .= ' class="'.$this->cssclass.'"';
		if($this->disabled) $ext .= ' disabled';
		switch($type) {
			case('submit'): $type = 'submit'; break;
			case('reset'): $type = 'reset'; break;
			default: $type = 'button'; break;
		}
		$this->html = '<input type="'.$type.'" name="'.$this->name.'" id="'.$this->id.'" value="'.$this->value.'" class="bg'.$bg.'" '.$ext.'>';
		return $this->html."\r\n";
	}
}
// ======================== FILTERS ===================================
// ======================== LISTS ===================================
// ======================== MEMCACHE ===================================

// ======================== OLD & ALIEN ===================================


?>