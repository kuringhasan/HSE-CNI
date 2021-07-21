<?php
/**
 * @package Pandora PHP Framework
 * @subpackage Core Library
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class Core {
	public $options;

	public function __construct() {
		global $dcistem;
		if(!is_a($dcistem, "Core")) {
			$dcistem = $this;
		}
		$this->benchmarkStart();
		$this->loadConfig("system");
		$this->loadConfig("memcache");
		$this->startSetting();
        
		$this->startSession();
		$this->startDB();
		$this->startFramework();
       
	}

	public static function init() {
		global $dcistem;
		$dcistem = new Core();
	}

	public function setOption($name, $value) {
		$name = trim($name);
		if(!strlen($name)) {
			return false;
		}
		$temp    = array();
		$part    = explode("/", $name);
		$temp[0] = isset($this->options[$part[0]]) ? $this->options[$part[0]] : array();
		$end     = count($part) - 1;
		for($i=1;$i<=$end;$i++) {
			$temp[$i] = isset($temp[$i-1][$part[$i]]) ? $temp[$i-1][$part[$i]] : array();

		}
		$temp[$end] = $value;
		for($i=$end-1;$i>=0;$i--) {
			$temp[$i][$part[$i+1]] = $temp[$i+1];
		}
		$this->options[$part[0]] = $temp[0];
	}

	public function getOption($name) {
		$part = trim($name, "/");
		$part = explode("/", $name);
		//echo "<pre>";print_r($part);echo "</pre>";
		$end  = count($part) - 1;
		$data = $this->options[$part[0]];
		for($i=1; $i<=$end; $i++) {
			$data = $data[$part[$i]];
		}
		return $data;
	}

	public function clearEvalCode($code) {
	   
		if(!count($_SESSION["framework"]["safe_eval_func_list"])) {
			$func = get_defined_functions();
			$list = array();
			while($data = each($func["internal"])) {
				$function = $data[1];
				if(preg_match_all('/[0-9A-Za-z_]/', $function, $matches) == strlen($function)) {
					$list[] = $function."(";
				}
			}
			$_SESSION["framework"]["safe_eval_func_list"] = $list;
		}
        
		$list = $_SESSION["framework"]["safe_eval_func_list"];
		reset($list);
		while($func = current($list)) {
            $pos = strpos($code, $func);
            if($pos !== false) {
                $chr = text::filter($code[$pos-1], "lcase ucase num", "-.");
                if($chr == "") {
                    return false;
                }
			}
			next($list);
		}
       
		return $code;
	}

	public function loadConfig($file) {
		if(preg_match_all('/[0-9A-Za-z_]/', $file, $matches) == strlen($file)) {
			$path = "includes/config/".$file.".php";
			if(file_exists($path)) {
				include $path;
				$this->setOption($file, $config);
			}
		}
	}

	private function startSetting() {
		global $dcistem;
		spl_autoload_register(array("Core", "autoload"));
		register_shutdown_function(array("Core", "shutdown"));
		$system             = $this->getOption("system");
		//print_r($system);exit;
		$system["timezone"] = ($system["timezone"] == "" ? "Asia/Jakarta" : $system["timezone"]);
		date_default_timezone_set($system["timezone"]);
		array_push($system["auth"]["allowed"], "super_administrator", "administrator");
		$system["session"]["name"]    = ($system["session"]["name"] == "" ? "dcistem" : $system["session"]["name"]);
		$system["session"]["expires"] = ($system["session"]["expires"] == "" ? "300" : $system["session"]["expires"]);
       // echo "<pre>";print_r($system["dir"]);echo "</pre>";
		if(!is_writable($system["dir"]["file"])) {
		  
			Core::fatalError("File Directory /".$system["dir"]["file"]." is not writeable");
		}
		if(!is_writable($system["dir"]["temp"])) {
			Core::fatalError("Temp Directory /".$system["dir"]["temp"]." is not writeable");
		}
		$this->setOption("system", $system);
	}

	private function startSession() {
		global $dcistem;
		$system   = $this->getOption("system");
		$memcache = $this->getOption("memcache");
		ini_set("session.name", $system["session"]["name"]);
		if(!strlen($_COOKIE[$system["session"]["name"]])) {
			$session_id = uniqid("dcistem-");
			session_id($session_id);
		}

		session_start();

		if($memcache["use_memcache"]) {
			if(!class_exists("Memcache")) {
				Core::fatalError("Memcache not installed!");
			}
			$mem = new Memcache;
			if(!@$mem->connect($memcache["host"], $memcache["port"])) {
				Core::fatalError("Can't connect to Memcache server on '".$memcache["host"].":".$memcache["port"]."'");
			}
			$session_id = $system["web"]["id"].":session:".session_id();
			$data       = $mem->get($session_id);
			if($data["framework"]["memcache_update"] > $_SESSION["framework"]["memcache_update"]) {
				$_SESSION = $data;
			}
			$dcistem->setOption("framework/memcache", $mem);
		}

	}

	private function stopSession() {
		global $dcistem;
		$system   = $this->getOption("system");
		$memcache = $dcistem->getOption("memcache");
		if($memcache["use_memcache"]) {
			$_SESSION["framework"]["memcache_update"] = time();
			$mem = $dcistem->getOption("framework/memcache");
			if(is_a($mem, "Memcache")) {
				$session_id = $system["web"]["id"].":session:".session_id();
				if(!$mem->replace($session_id, $_SESSION, false, $system["session"]["expires"]) === false) {
					$mem->set($session_id, $_SESSION, false, $system["session"]["expires"]);
				}
			}
		}
	}

	private function startDB() {
		global $dcistem;
		$db = new DB();
		$db->connect();
		$dcistem->setOption("framework/db", $db);
	}

	private function stopDB() {
		global $dcistem;
		$db = $dcistem->getOption("framework/db");
		if(is_a($db, "DB")) {
			$db->close();
		}
	}

	private function startFramework() {
		global $dcistem;
		$db             = $dcistem->getOption("framework/db");
		
        $scriptname     = "/".trim(dirname((isset($_SERVER["ORIG_SCRIPT_NAME"]) ? $_SERVER["ORIG_SCRIPT_NAME"] : $_SERVER["SCRIPT_NAME"])), "/");
        $scriptname    .= (substr($scriptname, -1) <> "/" ? "/" : "");
        
        if(isset($_SERVER["QUERY_STRING"]) && strlen($_SERVER["QUERY_STRING"])) {
			if($_SERVER["QUERY_STRING"][0] == "/") {
				$part     = explode("?", $_SERVER["QUERY_STRING"]);
	            if(count($part) == 1) {
	                $part = explode("&", $_SERVER["QUERY_STRING"]);
	                if($part > 1) {
	                    $tmp  = array_shift($part);
	                    $part = array(implode("&", $part));
	                    array_unshift($part, $tmp);
	                }
	            }
				$pathinfo = "/".trim($part[0], "/");
				$_GET     = array();
				$uri      = explode($_SERVER["QUERY_STRING"], $_SERVER["REQUEST_URI"]);
				if(isset($part[1]) || count($uri) > 1) {
					if(!empty($uri[1]) && $uri[1][0] == "?") {
						$part[1] = substr($uri[1], 1);
					}
				}
				if(!empty($part[1])) {
					parse_str($part[1], $_GET);
				}
			}
			 else {
                $pathinfo = "/".trim((isset($_SERVER["ORIG_PATH_INFO"]) ? $_SERVER["ORIG_PATH_INFO"] : $_SERVER["PATH_INFO"]), "/");
            }
		} else {
			$pathinfo = "/".trim((isset($_SERVER["ORIG_PATH_INFO"]) ? $_SERVER["ORIG_PATH_INFO"] : $_SERVER["PATH_INFO"]), "/");
		}
       // echo $pathinfo;exit;
		$pathinfo       = text::filter($pathinfo, "lcase ucase num /_()");
		$path           = ($pathinfo == "/" ? array("") : explode("/", $pathinfo));
		$PageID         = 0;
		$PageController = "";//
		$realpath       = array();
		while($data = each($path)) {
			$PageName = $data[1];
			$page     = new Core_Page_Model();
			$page->getDataFrom_PageName_ParentID($PageName, $PageID);
			
			if($page->PageID < 1) {
				break;
			}
			if($page->PageID > 0) {
				$realpath[]     = $PageName;
				$PageID         = $page->PageID;
				$PageController = $page->PageController;
			}
		}
       
		if(!count($realpath)) {
			Core::fatalError("Default Home Page not found!");
		}
		
		$realpath  = implode("/", $realpath);
		$arguments = explode("/", trim(substr($pathinfo, strlen($realpath)), "/"));
		$method    = array_shift($arguments);
		$method    = (trim($method) <> "" ? $method : "index");
        
		$dcistem->setOption("framework/url", array(
			"full_path"  => $pathinfo,
			"path"       => $realpath,
			"controller" => $PageController,
			"method"     => $method,
			"arguments"  => $arguments,
			"page_id"    => $PageID
		));
         
		$dcistem->setOption("framework/value/cms_path", $scriptname);
		if(!is_object($_SESSION["framework"]["current_user"])) {
			$_SESSION["framework"]["current_user"] = new Core_User_Model();
			$_SESSION["framework"]["login_as"]     = "guest";
			$_SESSION["framework"]["ref_id"]       = "";
		}
         
		$_SESSION["framework"]["theme"] = (!strlen(trim($_SESSION["framework"]["theme"])) ? $dcistem->getOption("system/web/theme") : $_SESSION["framework"]["theme"] );
		$dcistem->setOption("framework/value/theme_path", $dcistem->getOption("framework/value/cms_path").$dcistem->getOption("system/dir/theme").$_SESSION["framework"]["theme"]."/");
		if(trim(url::controller()) == "" || substr(url::method(),0,1) == "_") {
			url::redirect(url::home());
		}
         
		$class = str_replace(" ", "_", ucwords(str_replace("_"," ", url::controller())))."_Controller";
		if(!file_exists($dcistem->getOption("system/dir/controller").strtolower($class).".php")) {
			Core::error404();
		}
         
		$controller = new $class();
		if(!method_exists($controller, url::method())) {
			Core::error404();
		}

		if(!$controller->_getAccess(url::method())) {
   			if($_SESSION["framework"]["login_as"] == "guest") {
   				//echo "cek".url::base().(!$dcistem->getOption("system/urlrewrite") ? "index.php" : "")."auth";exit;
   				url::redirect(url::base().(!$dcistem->getOption("system/urlrewrite") ? "index.php/" : "")."login");
   			} else {
   			 // echo core::debug($dcistem);
   			  // echo core::debug(url::method());exit;
				Core::error403();
			}
		}
         
        $args       = url::arguments();
		$arg        = range(0, (count($args) ? count($args) - 1 : 0), 1);
		$method     = url::method()."(".(count($args) ? '$args['.implode('], $args[', $arg)."]" : "").")";
    
      eval(Core::clearEvalCode('$controller->'.$method.";"));

	}

	public static function autoload($class) {
		global $dcistem;
		if(class_exists($class)) {
			return;
		}
		if(substr($class, 0, 8) == 'PHPExcel') {
			return;
		}
		$dir    = $dcistem->getOption("system/dir");
		$path   = "";
		$part   = explode("_", $class);
		$prefix = $part[0];
		$suffix = end($part);
		if(count($part) > 1) {
			switch($suffix) {
			case "Controller" :
				if(count($part) < 3) {
					Core::fatalError("Invalid name for Controller! (".$class.")");
				}
				$controller = strtolower($class);
				$path       = $dir["controller"].$controller;
				break;
			case "Model" :
				if(count($part) < 3) {
					Core::fatalError("Invalid name for Model! (".$class.")");
				}
				$model = strtolower($class);
				$path  = $dir["model"].$model;
				break;
			case "Driver" :
				if(count($part) < 3) {
					Core::fatalError("Invalid name for Driver! (".$class.")");
				}
				$driver  = strtolower(substr($class, strlen($prefix) + 1));
				$library = strtolower($prefix);
				$path    = $dir["driver"].$library."/".$driver;
				break;
			}
		} else {
			if($class[0] < "a") {
				if(count($part) > 1) {
					Core::fatalError("Invalid name for Library! (".$class.")");
				}
				$path = $dir["library"].strtolower($class);
			} else {
				$path = $dir["helper"].strtolower($class);
			}
		}
		if(file_exists($path.".php") && trim($path) <> "") {
			require_once $path.".php";
		} else {
			$path = ($path == "" ? $class : $path);
			Core::fatalError("File <b>".$class."</b> not found! (".$path.")");
		}
	}

	public static function shutdown() {
		global $dcistem;
		$dcistem->stopDB();
		$dcistem->stopSession();
	}

	public static function isSerialize($data) {
		if(!is_string($data)) {
			return false;
		}
		$data = trim($data);
		if($data == "N;") {
			return true;
		}
		if(!preg_match("/^([adObis]):/", $data, $match)) {
			return false;
		}
		if(in_array($match[1],array("a","O","s")) && preg_match("/^".$match[1].":[0-9]+:.*[;}]\$/s", $data)) {
			return true;
		}
		if(in_array($match[1],array("b","i","d")) && preg_match("/^".$match[1].":[0-9.E-]+;\$/", $data)) {
			return true;
		}
		return false;
	}

	public static function checkSerialize($data) {
		if(self::isSerialize($data)) {
			return @unserialize($data);
		} else {
			return $data;
		}
	}

	public static function getToken() {
		if($_SESSION["framework"]["_token"] == "") {
			$_SESSION["framework"]["_token"] = md5(uniqid("DCISTEM_TOKEN"));
		}
		return $_SESSION["framework"]["_token"];
	}

	public static function checkToken($token) {
		return ($token == $_SESSION["framework"]["_token"] ? true : false);
	}

	public static function error403() {
		global $dcistem;
		header("HTTP/1.1 403 Forbidden");
		$tpl          = new View("core_error");
		$tpl->status  = "Error 403";
		$tpl->title   = "Konfirmasi Hak Akses!";
		$tpl->message = "Mohon Maaf, Anda tidak diberi hak akses untuk fitur ini.";
		$tpl->footer  = $dcistem->getOption("system/web/name");
		$tpl->render();
		exit;
	}

	public static function error404() {
		global $dcistem;
		header("HTTP/1.1 404 File Not Found");
		$tpl          = new View("core_error");
		$tpl->status  = "Error 404";
		$tpl->title   = "Page Not Found!";
		$tpl->message = "The Web Server cannot find the file or script you asked for. Please check the URL to ensure that the path is correct.";
		$tpl->footer  = $dcistem->getOption("system/web/name");
		$tpl->render();
		exit;
	}

	public static function error($message = "",$align="left") {
		global $dcistem;
		$content = "";
        $text_align="text-align:$align;";
		if(!$dcistem->getOption("framework/print/error")) {
			$content .= "
			<style>
				.core_error {color:#990000; border:1px solid #cc0000; font:12px Verdana; background:#ffcccc; padding:10px; -moz-border-radius:5px;$text_align}
			</style>";
		}
		$dcistem->setOption("framework/print/error", true);
		$content .= "
		<div class='core_error'>
			".$message."
		</div>";
		return $content;
	}

	public static function fatalError($message = "") {
		echo self::error($message);
		exit;
	}

	public static function debug() {
		global $dcistem;
		self::benchmarkSave();
		$content = "";
		if(!$dcistem->getOption("framework/print/debug")) {
			$content .= "
			<style>
				.core_debug {border:1px solid #4c1400; padding:1px; margin:5px 0px; background:#ffffff;}
				.core_debug ul {margin:0px; padding:0px; list-style:none;}
				.core_debug li {padding-left:20px;}
				.core_debug .title {padding:5px; background:#4c1400; color:#ffffff; font:11px Tahoma; margin-bottom:1px;}
				.core_debug div.content {padding:3px 5px; font:11px Tahoma;}
				.core_debug div.content:hover {background:#eeeeee;}
				.core_debug .name {font:bold 12px 'Courier New';}
				.core_debug .info {font:11px Tahoma; color:#999999;}
				.core_debug .code {	font:13px 'Courier New'; border:1px solid #777777; background:#eeeeee; color:#777777; padding:5px; margin-left:5px;}
			</style>
			<script>
				function core_debug(id){var obj = document.getElementById(id); if(obj){obj.style.display = (obj.style.display == 'none' ? '' : 'none'); }}
			</script>";
		}
		$dcistem->setOption("framework/print/debug", true);
		$args = func_get_args();
		while($arg = each($args)) {
			$id  = uniqid("debug_");
			$content .= "
			<div class='core_debug'>
				<div class='title'><b>Pandora</b> Debug</div>".self::_debug_var($arg[1])."
			</div>";
		}
		$content = str_replace("	", "", $content);
		return $content;
	}

	public static function _debug_var($var, $id = "") {
		if($id == "") {
			$child = (is_array($var) || is_object($var) || (is_string($var) && strlen($var) > 50) ? 1 : 0);
			$id    = uniqid("debug_");
			$content = "
			<ul>
				<li style='padding-left:0px;'>
					<div class='content' style='".($child ? "cursor:pointer;" : "")."' ".($child ? "onclick=\"core_debug('".$id."')\"" : "")."><span class='name'>...</span> ".self::_debug_var_info($var)."</div>".self::_debug_var($var, $id)."
				</li>
			</ul>";
			return $content;
		}
		if(is_array($var)) {
			$content = "
			<ul id='".$id."' style='display:none;'>";
				while($each = each($var)) {
					$child    = (is_array($each[1]) || is_object($each[1]) || (is_string($each[1]) && strlen($each[1]) > 50) ? 1 : 0);
					$id       = uniqid("debug_");
					$content .= "
					<li>
						<div class='content' style='".($child ? "cursor:pointer;" : "")."' ".($child ? "onclick=\"core_debug('".$id."')\"" : "")."><span class='name'>".$each[0]."</span> ".self::_debug_var_info($each[1])."</div>".self::_debug_var($each[1], $id)."
					</li>";
				}
				$content .= "
			</ul>";
		} else if(is_object($var)) {
			$content = "
			<ul id='".$id."' style='display:none;'>";
				$vars = get_object_vars($var);
				while($each = each($vars)) {
					$child    = (is_array($each[1]) || is_object($each[1]) || (is_string($each[1]) && strlen($each[1]) > 50) ? 1 : 0);
					$id       = uniqid("debug_");
					$content .= "
					<li>
						<div class='content' style='".($child ? "cursor:pointer;" : "")."' ".($child ? "onclick=\"core_debug('".$id."')\"" : "")."><span class='name'>".$each[0]."</span> ".self::_debug_var_info($each[1])."</div>".self::_debug_var($each[1], $id)."
					</li>";
				}
				$vars = get_class_methods(get_class($var));
				while($each   = each($vars)) {
					$content .= "
					<li>
						<div class='content'><span class='name'></span> (Method) <b>".$each[1]."</b></div>
					</li>";
				}
				$content .= "
			</ul>";
		} else if(is_string($var) && strlen($var) > 50) {
			$content .= "
			<div class='code' id='".$id."' style='display:none;'>
				".str_replace("\r", "<br />", htmlentities($var))."
			</div>";
		}
		return $content;
	}

	public static function _debug_var_info($var) {
		if(is_array($var)) {
			$content .= "(Array, <span class='info'>".count($var)." elements</span>)";
		} else if(is_object($var)) {
			$content .= "(Object) <b>".get_class($var)."</b>";
		} else if(is_string($var)) {
			$content .= "(String, <span class='info'>".strlen($var)." characters</span>) <b>".htmlentities(strlen($var) > 50 ? substr($var, 0, 47)."..." : $var)."</b>";
		} else if(is_int($var)) {
			$content .= "(Integer) <b>".$var."</b>";
		} else if(is_float($var)) {
			$content .= "(Float) <b>".$var."</b>";
		} else if(is_bool($var)) {
			$content .= "(Boolean) <b>".($var ? "TRUE" : "FALSE")."</b>";
		} else if(is_resource($var)) {
			$content .= "(Resource) <b>".get_resource_type($var)."</b>";
		} else if(is_null($var)) {
			$content .= "(NULL)";
		}
		return $content;
    }

	public static function printVar($var) {
		global $dcistem;
		self::benchmarkSave();
		$content = "";
		$args    = func_get_args();
		while($arg = each($args)) {
			$content .= self::_printVar_var($var);
		}
		return $content;
    }

	public static function _printVar_var($var, $tab = 0) {
		if(is_array($var) || is_object($var)) {
			$content .= (is_array($var) ? "Array" : get_class($var))." {\r\n";
			while($data = each($var)) {
				$content .= str_repeat("    ", $tab + 1)."[".$data[0]."] => ".self::_printVar_var($data[1], $tab + 2)."\r\n";
			}
			$content .= str_repeat("    ", $tab)."}";
		} else {
			$content .= $var;
		}
		return $content;
	}

	public static function benchmarkStart() {
		global $dcistem;
		$memory           = memory_get_usage();
		$microtime        = microtime();
		list($sec,$micro) = explode(" ", $microtime);
		$time             = $sec + $micro;
		$benchmark        = array(
								"memory" => $memory,
								"time"   => $time
							);
		$dcistem->setOption("framework/benchmark/0", $benchmark);
	}

	public static function benchmarkUpdate() {
		global $dcistem;
		$benchmark        = $dcistem->getOption("framework/benchmark");
		$memory_0         = $benchmark["0"]["memory"];
		$time_0           = $benchmark["0"]["time"];
		$memory_1         = memory_get_usage();
		$microtime        = microtime();
		list($sec,$micro) = explode(" ", $microtime);
		$time_1           = $sec + $micro;

		$benchmark["0"]["memory"] = ($memory_0 ? $memory_0 : $memory_1);
		$benchmark["0"]["time"]   = ($time_0 ? $time_0 : $time_1);
		$benchmark["1"]["memory"] = $memory_1;
		$benchmark["1"]["time"]   = $time_1;
		$dcistem->setOption("framework/benchmark", $benchmark);
	}

	public static function benchmarkSave() {
		global $dcistem;
		self::benchmarkUpdate();
		$benchmark = $dcistem->getOption("framework/benchmark");
		$memory    = $benchmark["1"]["memory"] - $benchmark["0"]["memory"];
		$time      = $benchmark["1"]["time"] - $benchmark["0"]["time"];
		if($memory < 1024) {
			$unit   = 1024;
			$suffix = " B";
		} else if($memory < 1048576) {
			$unit   = 1048576;
			$suffix = " KB";
		} else {
			$unit   = 1073741824;
			$suffix = " MB";
		}
		$memory      = number_format(($memory/$unit), 2, ".", ",").$suffix;
		$time        = number_format($time, 3, ".", ",");
		list($s, $m) = explode(".", $time);
		$m           = str_pad($m, 3, "0", STR_PAD_RIGHT);
		$time        = $s.".".$m." second(s)";
		$dcistem->setOption("framework/value/memory_usage", $memory);
		$dcistem->setOption("framework/value/time_execution", $time);
	}

	public function __toString() {
		global $dcistem;
		return Core::debug($dcistem);
	}
    public static function get_ip() {
        $result = array();
        foreach(array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if(array_key_exists($key, $_SERVER) === true) {
                foreach(explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if(filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        $result[] = $ip;
                    }
                }
            }
        }
        reset($result);
        return $result;
    }
}
?>