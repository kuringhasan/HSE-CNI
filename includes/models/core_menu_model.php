<?php
/**
 * @package Core
 * @subpackage Menu Model
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class Core_Menu_Model extends Model {

	public function __construct() {

	}

	public function getMenuFrom_ParentID_LevelID($ParentID, $LevelID,$group="") {
 		global $dcistem;
		$LevelID = ($LevelID == "super_administrator" ? "administrator" : $LevelID);
 		$db     = $dcistem->getOption("framework/db");
 		$srt_group=trim($group)==""?" AppPageListGroupID is null ":" AppPageListGroupID=$group";
   		$result = $db->select(array(
   			"AppPageListPageID"         => "PageID",
   			"AppPageListParentID"       => "PageParentID",
			"AppPageListPageName"       => "PageName",
			"AppPageListPageTitle"      => "PageTitle",
			"AppPageListPageController" => "PageController",
			"AppPageListPageProperties" => "PageProperties",
			"AppPageListIcon" => "PageIcon"), "tbaapppagelist")->join("tbaapppageprivilege")->where(array(
   		   "AppPageListPageID     = AppPagePrivilegePageID",
		   "AppPageListParentID   = '".$ParentID."'",
		   "AppPageListPageOrder <> '0'",
		   "(AppPagePrivilegeLevelID = '' OR AppPagePrivilegeLevelID = '".$LevelID."') and $srt_group"
		))->orderBy("AppPageListPageOrder")->get();
      	while($each = each($result)) {
      		$result[$each[0]]->PageUrl        = url::page($each[1]->PageID);
      		$result[$each[0]]->PageProperties = Core::checkSerialize($each[1]->PageProperties);
        	$result[$each[0]]->PageChilds     = $this->getMenuFrom_ParentID_LevelID($each[1]->PageID, $LevelID);
      	}
  		return $result;
	}

	public function getMenuFrom_LevelID($LevelID) {
		$page = new Core_Page_Model();
		$page->homepage();
		return $this->getMenuFrom_ParentID_LevelID($page->PageID, $LevelID);
	}

	public function getStaticMenuFrom_ParentID_LevelID($ParentID, $LevelID) {
 		global $dcistem;
		$LevelID = ($LevelID == "super_administrator" ? "administrator" : $LevelID);
 		$db     = $dcistem->getOption("framework/db");
   		$result = $db->select(array(
   			"AppPageListPageID"         => "PageID",
   			"AppPageListParentID"       => "PageParentID",
			"AppPageListPageName"       => "PageName",
			"AppPageListPageTitle"      => "PageTitle",
			"AppPageListPageController" => "PageController",
			"AppPageListPageProperties" => "PageProperties",
   		), "tbaapppagelist")->where(array(
		   "AppPageListParentID     = '".$ParentID."'",
		))->orderBy("AppPageListPageOrder")->get();
      	while($each = each($result)) {
      		$result[$each[0]]->PageUrl        = url::page($each[1]->PageID);
      		$result[$each[0]]->PageProperties = Core::checkSerialize($each[1]->PageProperties);
        	$result[$each[0]]->PageChilds     = $this->getStaticMenuFrom_ParentID_LevelID($each[1]->PageID, $LevelID);
      	}
  		return $result;
	}

	public function getStaticMenuFrom_LevelID($LevelID) {
		$page = new Core_Page_Model();
		$page->homepage();
		return $this->getStaticMenuFrom_ParentID_LevelID($page->PageID, $LevelID);
	}

}
?>