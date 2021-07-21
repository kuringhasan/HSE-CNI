<?php
/**
 * @package Core
 * @subpackage User Level Model
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class Core_User_Level_Model extends Model {

	public function __construct($Username = "") {
		global $dcistem;
		$auth = $dcistem->getOption("framework/auth");
		if(!is_a($auth, "Auth")) {
   			$config = $dcistem->getOption("system/auth");
   			$driver = "Auth_".$config["driver"]."_Driver";
			$auth   = new $driver();
			$dcistem->setOption("framework/auth", $auth);
		}
		if(!empty($Username)) {
			return $this->getListFrom_Username($Username);
		}
	}

	public function translate($text) {
		global $dcistem;
		$auth  = $dcistem->getOption("framework/auth");
		$list  = $auth->translateUserLevel();
		$key   = array_keys($list);
		$value = array_values($list);
		$find  = array_search($text, $key);
		if($find !== false) {
			return $value[$find];
		}
		$find  = array_search($text, $value);
		if($find !== false) {
			return $key[$find];
		}
		return false;
	}

	public function getData($condition) {
		$data = $this->getList($condition, "", 0);
		if(is_object($data)) {
			$this->appendVariable($data);
		}
	}

	public function getList($condition = "", $orders = "", $start = null, $limit = 0) {
		global $dcistem;
		$auth  = $dcistem->getOption("framework/auth");
		$db    = $dcistem->getOption("framework/db");
		$query = $db->select($this->translate_field(array(
					"Username",
					"LevelID",
                    "LevelName",
                    "RefName",
					"RefID"
				)), $auth->translateUserLevel("table"));
		if(!empty($condition)) {
			$query = $query->where($condition);
		}
		if(!empty($orders)) {
			$query = $query->orderBy($orders);
		}
		if($limit > 0) {
			$result = text::trim($query->get($start, $limit));
		} else {
			if(!is_null($start)) {
			$result = text::trim($query->get($start));
			} else {
				$result = text::trim($query->get());
			}
		}
		return $result;
	}

	public function getListFrom_Username($Username) {
	   
  		$condition = array(
            "(AppUserLevelUsername    = '".$Username."')"
		
		);
        
		return $this->getList($this->translate_condition($condition), $this->translate_condition("LevelID"));
	}
    
    public function getUserLevel($username,$level_id) {
	   global $dcistem;
		$auth  = $dcistem->getOption("framework/auth");
		$db    = $dcistem->getOption("framework/db");
  		$condition = array(
            "AppUserLevelUsername    = '".$username."'",
            "AppUserLevelLevelID    = '".$level_id."'"
		
		);
        $query = $db->select($this->translate_field(array(
					"Username",
					"LevelID",
                    "LevelName",
                    "RefName",
					"RefID"
				)), $auth->translateUserLevel("table"));
		
		$query = $query->where($condition);
	   	$result = $query->get(0);
		
	
        
        
		return $result;
	}
    
   

}
?>