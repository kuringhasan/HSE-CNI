<?php
/**
 * @package Core
 * @subpackage Level Model
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class Core_Level_Model extends Model {

	public function __construct($LevelID = "") {
		global $dcistem;
		$auth = $dcistem->getOption("framework/auth");
		if(!is_a($auth, "Auth")) {
   			$config = $dcistem->getOption("system/auth");
   			$driver = "Auth_".$config["driver"]."_Driver";
			$auth   = new $driver();
			$dcistem->setOption("framework/auth", $auth);
		}
		if(!empty($LevelID)) {
			$this->getDataFrom_LevelID($LevelID);
		}
	}

	public function translate($text) {
		global $dcistem;
		$auth  = $dcistem->getOption("framework/auth");
		$list  = $auth->translateLevelList();
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
       
        return $data;
	}

	public function getList($condition = "", $orders = "", $start = null, $limit = 0) {
		global $dcistem;
		$auth  = $dcistem->getOption("framework/auth");
		$db    = $dcistem->getOption("framework/db");
		$query = $db->select($this->translate_field(array(
					"LevelID",
					"LevelName",
					"RefName",
                    "Unit",
                    "UnitName"
				)), $auth->translateLevelList("table"));
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

	public function getDataFrom_LevelID($LevelID) {
  		$condition = array(
			"LevelID" => $LevelID
		);
		return $this->getData($this->translate_condition($condition));
	}
    

}
?>