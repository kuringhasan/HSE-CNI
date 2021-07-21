<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Text Helper
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class text {

	public static function filter($text, $filter = "lcase ucase num space symbol enter tab", $exclude = "") {
		$option = explode(" ", $filter);
		$filter = "";
		$len    = count($option);
		for($i=0; $i<$len; $i++) {
			switch($option[$i]) {
			case "lcase" :
				$filter .= "abcdefghijklmnopqrstuvwxyz";
				break;
			case "ucase" :
				$filter .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
				break;
			case "num" :
				$filter .= "1234567890-.";
				break;
			case "space" :
				$filter .= " ";
				break;
			case "symbol" :
				$filter .= "!@#$%^&*()_+=[]{}:;,./?'".'"';
				break;
			case "enter" :
				$filter .= chr(13).chr(10);
				break;
			case "tab" :
				$filter .= "	";
				break;
			default :
				$filter .= $option[$i];
				break;
			}
		}
		$len = strlen($exclude);
		for($i=0; $i<$len; $i++) {
			$filter = str_replace($exclude[$i],"",$filter);
		}
		$i = 0;
		while($i < strlen($text)) {
			if(strpos($filter, $text[$i]) === false) {
				$text = str_replace($text[$i], "", $text);
			} else {
				$i++;
			}
		}
		return $text;
	}

	public static function wordLimit($text, $limit = 100) {
		$limit = (int) $limit;
		if(trim($text) == "" || $limit <= 0) {
			return $text;
		}
		preg_match('/^\s*+(?:\S++\s*+){1,'.$limit.'}/u', $text, $matches);
		return rtrim($matches[0]);
	}


	public static function charLimit($text, $limit = 100) {
		$limit = (int) $limit;
		if(trim($text) == "" || $limit <= 0) {
			return $text;
		}
		return substr($text, 0, $limit);
	}

	public static function nameLimit($name, $limit = 100, $abbr = false) {
		$limit = (int) $limit;

	if(trim($name) == "" || $limit <= 0) {
			return $name;
		}
		$result = "";
		$list   = explode(" ", $name);
		for($i = 0; $i < count($list); $i++) {
			if(strlen($result) + strlen($list[$i]) <= $limit) {
				$result .= ($result <> "" ? " " : "").$list[$i];
			} else {
				if($abbr && (strlen($result) + 2) <= $limit) {
					$result .= " ".$list[$i][0];
				}
			}
		}
		return $result;
	}

	public static function random($amount, $type = "lcase ucase num", $exclude = "") {
		$amount = (int) $amount;
		if($amount <= 0) {
			return "";
		}
		$option = explode(" ", $type);
		$chars  = "";
		$len    = count($option);
		for($i=0; $i<$len; $i++) {
			switch($option[$i]) {
			case "lcase" :
				$chars .= "abcdefghijklmnopqrstuvwxyz";
				break;
			case "ucase" :
				$chars .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
				break;
			case "num" :
				$chars .= "1234567890";
				break;
			default :
				$chars .= $option[$i];
				break;
			}
		}
		$len = strlen($exclude);
		for($i=0; $i<$len; $i++) {
			$filter = str_replace($exclude[$i],"",$filter);
		}
		$result = "";
		for($i = 0; $i < $amount; $i++) {
			$result .= $chars[rand(0,strlen($chars)-1)];
		}
		return $result;
	}

	public static function stripslashes($text) {
		if(is_array($text)) {
			while($data = each($text)) {
				$text[$data[0]] = self::stripslashes($data[1]);
			}
		} else if(is_object($text)) {
			$list = get_object_vars($text);
			while($data = each($list)) {
				$text->$data[0] = self::stripslashes($data[1]);
			}
		} else {
			$len_last    = strlen($text);
			$len_current = 0;
			while($len_last <> $len_current) {
				$len_last    = strlen($text);
				$text        = stripslashes($text);
				$len_current = strlen($text);
			}
		}
		if(is_array($text)) {
			reset($text);
		}
		return $text;
	}

	public static function addslashes($text) {
		if(is_array($text)) {
			while($data = each($text)) {
				$text[$data[0]] = self::addslashes($data[1]);
			}
		} else if(is_object($text)) {
			$list = get_object_vars($text);
			while($data = each($list)) {
				$text->$data[0] = self::addslashes($data[1]);
			}
		} else {
			$text = addslashes(self::stripslashes($text));
		}
		if(is_array($text)) {
			reset($text);
		}
		return $text;
	}

	public static function autoBR($text) {
		$text = str_replace("\n\r", "\r", $text);
		$text = str_replace("\r", "<br />", $text);
		return $text;
	}

	public static function trim($text, $chars = "") {
		$chars = (trim($chars) <> "" ? $chars : chr(0).chr(9).chr(10).chr(11).chr(13).chr(32));
		if(is_array($text)) {
			while($data = each($text)) {
				$text[$data[0]] = self::trim($data[1], $chars);
			}
		} else if(is_object($text)) {
			$list = get_object_vars($text);
			while($data = each($list)) {
				$text->$data[0] = self::trim($data[1], $chars);
			}
		} else {
			$text = trim($text, $chars);
		}
		if(is_array($text)) {
			reset($text);
		}
		return $text;
	}

	public static function bigint_add($val_1 = "", $val_2 = "") {
		$i     = 0;
		$sisa  = 0;
  		$val_1 = strrev($val_1);
     	$val_2 = strrev($val_2);
     	while($i < strlen($val_2) || $sisa <> 0) {
     		$tmp = strval($val_1[$i]) + strval($val_2[$i]) + $sisa;
			if($tmp > 9) {
				$sisa      = floor($tmp/10);
				$val_1[$i] = $tmp - 9;
			} else {
				$sisa = 0;
				$val_1[$i] = $tmp;
			}
      		$i++;
     	}
     	return strrev($val_1);
	}
    
    public static function bulatkan($var) {
        $var = round($var)<>$var?round($var,2) : (int)$var;
        return $var;
    }
   
    
    public static function cekkosong($var) {
        $var = empty($var)?'0':$var;
        return $var;
    }
    
 public static function kodeAcak($panjang_karakter)
	{
	    $karakter= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	    $string = '';
	    for ($i = 0; $i < $panjang_karakter; $i++) {
	        $pos = rand(0, strlen($karakter)-1);
	        $string .= $karakter{$pos};
	    }
	    return $string;
	}


 public static function better_crypt($input,$rounds =10)
  {
    /** Here we have a simple function that creates a blowfish hash 
     * from the input value using a random salt made up of letters and numbers
     * */
    $salt = "";
    $salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
    for($i=0; $i < 22; $i++) {
      $salt .= $salt_chars[array_rand($salt_chars)];
    }
    return crypt($input, sprintf('$2a$%02d$', $rounds) . $salt);
  }

}
?>