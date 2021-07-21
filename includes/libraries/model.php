<?php
/**
 * @package  DCISTEM PHP Framework
 * @subpackage Model Library
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class Model {

	public function appendVariable($var) {
		if(is_object($var)) {
			$object = get_object_vars($var);
			while($data = each($object)) {
				$this->$data[0] = $data[1];
			}
		}
		if(is_array($var)) {
			while($data = each($var)) {
				$this->$data[0] = $data[1];
			}
		}
	}
    static function array2Object($nilaiarray)  {  
        //print_r($nilaiarray);exit;
        $obj=new Model();
        if (is_array($nilaiarray)) {
            $nilai= (object) array_map(array('model',__FUNCTION__),$nilaiarray);
        }
        if(is_object($nilaiarray))  {
            // Return object
            $nilai=$nilaiarray;
        }
        return $nilai;
	}
	public static function getOptionList($table, $key, $value, $order = "", $condition = "") {
		global $dcistem;
		$db     = $dcistem->getOption("framework/db");
		$fields = "";
		if(is_array($key)) {
			$fields .= implode(", ", $key);
		} else {
			$fields .= $key;
		}
		if(is_array($value)) {
			$fields .= ", ".implode(", ", $value);
		} else {
			$fields .= ", ".$value;
		}
		$query = $db->select($fields, $table, "row");
		if(!empty($condition)) {
			$query->where($condition);
		}
		if(!empty($order)) {
			$query->orderBy($order);
		}
		$list = $query->get();
		$result = array();
		while($data = current($list)) {
			$key = $data[0];
			if(isset($data[2])) {
				$value = array_slice($data, 1);
			} else {
				$value = $data[1];
			}
			$result[$key] = $value;
			next($list);
		}
		return $result;
	}

	public function memcache_set($id, $content, $expires = "") {
		global $dcistem;
		$expires = (int) $expires;
		$expires = ($expires < 1 ? $dcistem->getOption("memcache/expires") : $expires);
		$max     = 3600;
		$max     = ($expires > $max ? $expires + $max : $max);
		if($dcistem->getOption("memcache/use_memcache")) {
			$memcache = $dcistem->getOption("framework/memcache");
			if(is_a($memcache, "Memcache")) {
				$session_id           = "DCISTEM_PHP_Framework:".$id;
				$memcache_variable_id = "DCISTEM_PHP_Framework:memcache_variable";
				$memcache_variable    = $memcache->get($memcache_variable_id);
				if($memcache_variable === false) {
					$memcache->set($memcache_variable_id, array(), false, $max);
				}
				if(!in_array($session_id, $memcache_variable)) {
					$memcache_variable[] = $session_id;
				}
				if(!$memcache->replace($memcache_variable_id, $memcache_variable, false, $max)) {
					$memcache->set($memcache_variable_id, $memcache_variable, false, $max);
				}
				if(!$memcache->replace($session_id, $content, false, $expires)) {
					$memcache->set($session_id, $content, false, $expires);
				}
			}
		}
	}

	public function memcache_get($id) {
		global $dcistem;
		if($dcistem->getOption("memcache/use_memcache")) {
			$memcache = $dcistem->getOption("framework/memcache");
			if(is_a($memcache, "Memcache")) {
				$session_id = "DCISTEM_PHP_Framework:".$id;
				return $memcache->get($session_id);
			}
		} else {
			return false;
		}
	}

	public function memcache_clear($id = "") {
		global $dcistem;
		if($dcistem->getOption("memcache/use_memcache")) {
			$memcache = $dcistem->getOption("framework/memcache");
			if(is_a($memcache, "Memcache")) {
				$expires              = $dcistem->getOption("memcache/expires");
				$session_id           = "DCISTEM_PHP_Framework:".$id;
				$memcache_variable_id = "DCISTEM_PHP_Framework:memcache_variable";
				$memcache_variable    = $memcache->get($memcache_variable_id);
				if($memcache_variable !== false) {
					$tmp = array();
					$len = strlen($session_id);
					while($data = each($memcache_variable)) {
						if(substr($data[1], 0, $len) == $session_id) {
							$memcache->delete($data[1]);
						} else {
							$tmp[] = $data[1];
						}
					}
					if(!$memcache->replace($memcache_variable_id, $tmp, false, $expires)) {
						$memcache->set($memcache_variable_id, $tmp, false, $expires);
					}
				}
			}
		}
	}

	public function translate_field($field) {
		if(is_array($field)) {
			if(isset($field[0])) {
				$result = array();
				while($data = each($field)) {
					$result[$this->translate($data[1])] = $data[1];
				}
				return $result;
			} else {
				return $field;
			}
		} else {
			if(count(explode(" as ", strtolower($field))) > 1) {
				return $field;
			}
			$list   = explode(",", $field);
			$result = array();
			while($data = each($list)) {
				$data                               = text::trim($data);
				$result[$this->translate($data[1])] = $data[1];
			}
			return $result;
		}
	}

	public function translate_condition($condition) {
		if(is_array($condition)) {
			if(strlen($condition[0])) {
				$condition = implode(" AND ", $condition);
			} else {
				$cond = array();
				while($data = each($condition)) {
					$cond[] = $data[0]." = '".$data[1]."'";
				}
				$condition = implode(" AND ", $cond);
			}
		}
		$words = explode(" ", $condition);
		while($data = each($words)) {
			$translate = $this->translate(text::trim($data[1]));
			if($translate !== false) {
				$words[$data[0]] = $translate;
			}

		}
		$condition = implode(" ", $words);
		return $condition;
	}

	public function translate_value($value) {
		if(is_array($value)) {
			$value  = "(".implode(", ", array_keys($value)).") VALUES ('".implode("', '", array_values($value))."')";
		}
		$name = array_pop(explode("(",array_shift(explode(")", $value))));
		$words = explode(",", $name);
		while($data = each($words)) {
			$translate = $this->translate(text::trim($data[1]));
			if($translate !== false) {
				$words[$data[0]] = $translate;
			}

		}
		$value = "(".implode(",", $words).substr($value, strlen($name) + 1);
		return $value;
	}

	public function translate_set($set) {
		if(is_array($set)) {
			if(strlen($set[0])) {
				$set = implode(", ", $set);
			} else {
				$sets = array();
				while($data = each($set)) {
					$sets[] = $data[0]." = '".$data[1]."'";
				}
				$set = implode(", ", $sets);
			}
		}
		$words = explode(" ", $set);
		while($data = each($words)) {
			$translate = $this->translate(text::trim($data[1]));
			if($translate !== false) {
				$words[$data[0]] = $translate;
			}

		}
		$set = implode(" ", $words);
		return $set;
	}

}
?>