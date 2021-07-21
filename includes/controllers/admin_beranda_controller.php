<?php
/**
 * @package Admin
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Admin_Beranda_Controller extends Web_Template_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("admin_beranda");
        $db   = $dcistem->getOption("framework/db"); 
        /*$user=new Core_User_Model();
        $usr=$user->getDataByUsername("hasan");
		echo "c";	print_r($usr);*/
		
		$this->tpl->content = $tpl;
		$this->tpl->render();
	    
   }
   
 

}
 

?>