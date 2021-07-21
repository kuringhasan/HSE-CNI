<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Socket Helper
 * 
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class socket {
	
	public static function post($url, $arguments = "", $mode = "body") {
		$url  = parse_url($url);
		if(is_array($arguments)) {
			if(strlen($arguments[0])) {
				$arguments = implode("&", $arguments);
			} else {
				$args = array();
				while($argument = current($arguments)) {
					$args[] = key($arguments)."=".urlencode($argument);
					next($arguments);
				}
				$arguments = implode("&", $args);
			}
		}		
		$cmd  = "POST ".$url["path"].($url["query"] <> "" ? "?".$url["query"] : "")." HTTP/1.1\r\n";
		$cmd .= "Host: ".$url["host"]."\r\n";		
		$cmd .= "User-Agent: Mozilla/4.0\r\n";
		$cmd .= "Content-Length: ".strlen($arguments)."\r\n";
		$cmd .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$cmd .= "\r\n";
		$cmd .= $arguments."\r\n";
		$fp = @fsockopen($url["host"], 80, $errno, $errstr, 30);
		if($fp) {
			$result = "";
			$read   = false;
			fwrite($fp, $cmd);
			while (!feof($fp)) {
				$data = fgets($fp, 1024);
				if(trim($data) == "" && !$read) {
					$read = true;
					if($mode == "head") {
						break;
					}
				}
				if($read || $mode == "full") {
					$result .= $data; 
				}
				if(!$read && $mode == "head") {
					$result .= $data;
				}
			}
			fclose($fp);
			return text::trim($result);
		} else {
			return false;
		}
	}
	
	public static function get($url, $mode = "body") {
		$url  = parse_url($url);
		$cmd  = "GET ".$url["path"].($url["query"] <> "" ? "?".$url["query"] : "")." HTTP/1.1\r\n";
		$cmd .= "Host: ".$url["host"]."\r\n";
		$cmd .= "Connection: Close\r\n";
		$cmd .= "\r\n";
		$fp = @fsockopen($url["host"], 80, $errno, $errstr, 30);
		if($fp) {
			$result = "";
			$read   = false;
			fwrite($fp, $cmd);
			while (!feof($fp)) {
				$data = fgets($fp, 1024);
				if(trim($data) == "" && !$read) {
					$read = true;
					if($mode == "head") {
						break;
					}
				}
				if($read || $mode == "full") {
					$result .= $data; 
				}
				if(!$read && $mode == "head") {
					$result .= $data;
				}
			}
			fclose($fp);
			return text::trim($result);
		} else {
			return false;
		}
	}
	
	public static function getStatus($url, $timeout = 30) {
		$url  = parse_url($url);
		$cmd  = "GET ".$url["path"].($url["query"] <> "" ? "?".$url["query"] : "")." HTTP/1.1\r\n";
		$cmd .= "Host: ".$url["host"]."\r\n";
		$cmd .= "Connection: Close\r\n";
		$cmd .= "\r\n";
		$fp = @fsockopen($url["host"], 80, $errno, $errstr, $timeout);
		if($fp) {
			$result = "";
			$read   = false;
			fwrite($fp, $cmd);
			$data   = fgets($fp);
			fclose($fp);
			$part   = explode(" ", $data);
			$result = $part[1];
			return text::trim($result);
		} else {
			return false;
		}
	}
	
}
?>