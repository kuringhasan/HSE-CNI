<?php
/**
 * @package Web
 * @subpackage Redirect Model
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class App_History_Model extends Model {

	public function __construct($PageID = 0, $LevelID = "") {
	
		
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