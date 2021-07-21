<?php
/**
 * @package Web
 * @subpackage Redirect Controller
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class Web_Redirect_Controller extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		global $dcistem;
		$redirect = new Web_Redirect_Model();
		$LevelID  = $_SESSION["framework"]["login_as"];
		$LevelID  = ($LevelID == "super_administrator" ? "administrator" : $LevelID);
		$Target   = $redirect->getDataFrom_PageID_LevelID(url::pageID(), $LevelID);
		
		$TargetID = $Target->TargetID;
		if(empty($TargetID)) {
			$Target   = $redirect->getDataFrom_PageID_LevelID(url::pageID(), "");
			$TargetID = $Target->TargetID;
		}
		if(empty($TargetID)) {
			Core::fatalError("<b>Web Redirect Error!!</b><br />Empty <b>PageID</b> with the current <b>LevelID</b>!");
		}
		$page = new Core_Page_Model($TargetID);
		if(empty($page->PageID)) {
			Core::fatalError("<b>Web Redirect Error!!</b><br /><b>TargetID</b> Not Found!");
		}
		url::redirect(url::page($page->PageID));
	}

}
?>