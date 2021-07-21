<?php
/**
 * @package Admin
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Dashboard_Guest_Controller extends Web_Template_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("dashboard");
        $db   = $dcistem->getOption("framework/db"); 
       
		$this->tpl->content = $tpl;
		$this->tpl->render();
	    
   }
 
 

}
 

?>