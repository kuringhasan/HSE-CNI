<?php
/**
 * @package Pandora PHP Framework
 * @subpackage Controller Library
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class Controller {

	public function __construct() {
		$this->page = new Core_Page_Model(url::pageID());
	}

	public function index() {

	}

	public function _getAccess($method) {
		global $dcistem;
		if($_SESSION["framework"]["login_as"] == "super_administrator") {
			return true;
		}
		if(empty($method)) {
			return false;
		}
		if(!is_a($this->page, "Core_Page_Model")) {
			Core::fatalError("Missing Parent Construct in Controller!");
		}
		$privileges = $this->page->privilege(url::pageID(), $_SESSION["framework"]["login_as"]);
		if(in_array($method, $privileges)) {
			return true;
		}
		return false;
	}

	public function __call($method, $arguments) {
		Core::error404();
	}

}
?>