<?php
/**
 * @package Web
 * @subpackage Text Controller
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class Web_Text_Controller extends Web_Template_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
 	   global $dcistem;
		$tpl             = new View("home");
        
        $db              = $dcistem->getOption("framework/db");
       
		$this->tpl->content   = $tpl;
		$this->tpl->render();
	}
    
    
}
?>