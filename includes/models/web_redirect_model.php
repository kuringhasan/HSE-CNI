<?php
/**
 * @package Web
 * @subpackage Redirect Model
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class Web_Redirect_Model extends Model {

	public function __construct($PageID = 0, $LevelID = "") {
		global $dcistem;
		$PageID  = (int) $PageID;
		if($PageID > 0) {
			$this->getDataFrom_PageID_LevelID($PageID, $LevelID);
		}
	}

	public function translate($text) {
		$list = array(
			"AppWebRedirectPageID"   => "PageID",
			"AppWebRedirectLevelID"  => "LevelID",
			"AppWebRedirectTargetID" => "TargetID"
		);
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
		global $dcistem;
		$data = $this->getList($condition, "", 0);
		if(is_object($data)) {
			$data->PageProperties = Core::checkSerialize($data->PageProperties);
			$this->appendVariable($data);
		}
	}

	public function getList($condition = "", $orders = "", $start = null, $limit = 0) {
		global $dcistem;
		$db    = $dcistem->getOption("framework/db");
		$query = $db->select($this->translate_field(array(
					"PageID",
					"LevelID",
					"TargetID"
				)), "tbaappwebredirect");
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

	public function getDataFrom_PageID_LevelID($PageID, $LevelID) {
		global $dcistem;
		$db     = $dcistem->getOption("framework/db");
		$result = $this->getList($this->translate_condition(array(
			"PageID"  => $PageID,
			"LevelID" => $LevelID
		)));
		return $result[0];
	}

}
?>