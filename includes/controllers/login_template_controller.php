<?php
defined("PANDORA") OR die("No direct access allowed.");
class Login_Template_Controller extends Controller {
	public function __construct() {
		global $dcistem;
		parent::__construct();
		
		$this->tpl = new View("login_template");
		$login_as=	$_SESSION['framework']['login_as']; 
		//$this->tpl->appendVariable($this->page->PageProperties["params"]);
		$this->tpl->page_title    = $this->page->PageTitle;
		$this->tpl->content_title = $this->page->PageTitle;
		$this->tpl->Unit 	 			=  $dcistem->getOption("system/web/unit");
		$this->tpl->WebID 	 			=  $dcistem->getOption("system/web/id");
		$this->tpl->WebName	 			=  $dcistem->getOption("system/web/name");
		$this->tpl->CurrentUserLevel=$_SESSION['framework']['user_level'];
		$this->tpl->PageID=$this->page->PageID;
		$menu             = new Core_Menu_Model();		
		$this->tpl->menus = $menu->getMenuFrom_LevelID($_SESSION["framework"]["login_as"]);
		
	}

}
?>