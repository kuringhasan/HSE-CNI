<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage URL Helper
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class url {

	public static function base($protocol = "http") {
		global $dcistem;
	 	if ($_SERVER["HTTPS"] == "on") {$protocol.= "s";}
		$path = $protocol."://".$_SERVER["HTTP_HOST"].$dcistem->getOption("framework/value/cms_path");
		return $path;
	}

	public static function page($PageID = 0, $method = "", $arguments = "", $protocol = "http") {
		global $dcistem;
		$PageID = ((int) $PageID < 1 ? self::pageID() : $PageID);
		if(!is_array($aguments) && trim($arguments) <> "") {
			if(is_object($arguments)) {
				$list      = get_object_vars($arguments);
				$arguments = array();
				while($data = each($list)) {
					$arguments[$data[0]] = $data[1];
				}
			} else {
				$arguments = array($arguments);
			}
		}
		$path = "";
		$page = new Core_Page_Model($PageID);
		while($page->ParentID <> 0) {
			$path = "/".$page->PageName.$path;
			$page = new Core_Page_Model($page->ParentID);
		}
		if(!empty($arguments)) {
			$method = (trim($method) <> "" ? $method : "index");
			$path .= "/".$method."/".implode("/", $arguments);
		} else {
			if($method <> "") {
				$path .= "/".$method;
			}
		}
		if($dcistem->getOption("system/urlrewrite")) {
			return self::base($protocol).trim($path, "/");
		} else {
			return self::base($protocol)."index.php".$path;
		}
	}

	public static function home() {
		global $dcistem;
		$page = new Core_Page_Model();
		$page->homepage($dcistem->getOption("system/web/id"));
		return self::page($page->PageID);
	}

	public static function current($method = "", $arguments = "", $protocol = "http") {
		global $dcistem;
		$method    = (trim($method) <> "" ? $method : self::method());
		return self::page(self::pageID(), $method, $arguments, $protocol);
	}

	public static function redirect($path) {
		header("Location: ".$path);
		exit;
	}
	public static function is_exist($url){
	    $ch = curl_init($url);    
	    curl_setopt($ch, CURLOPT_NOBODY, true);
	    curl_exec($ch);
	    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	    if($code == 200){
	       $status = true;
	    }else{
	      $status = false;
	    }
	    curl_close($ch);
	   return $status;
	}
	public static function pageID() {
		global $dcistem;
		return $dcistem->getOption("framework/url/page_id");
	}

	public static function controller() {
		global $dcistem;
		return $dcistem->getOption("framework/url/controller");
	}

	public static function method() {
		global $dcistem;
		return $dcistem->getOption("framework/url/method");
	}

	public static function arguments() {
		global $dcistem;
		return $dcistem->getOption("framework/url/arguments");
	}

	public static function fullpath() {
		global $dcistem;
		return self::base().trim($dcistem->getOption("framework/url/full_path"), "/");
	}

}
?>