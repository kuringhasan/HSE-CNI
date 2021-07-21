<?php
/**
 * @package Auth
 * @subpackage Logout Controller
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

//class Auth_Logout_Controller extends Web_Template_Controller {
class Auth_Logout_Controller extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index($status = "") {
		$auth = new Auth();
		$auth->logout();
	
		url::redirect(url::home());
	}
    
    public function change_role() {

	    //$this->ID			= $_SESSION["framework"]["ref_id"];		
        $username=$_SESSION["framework"]["current_user"]->Username;
		$login_as=	$_SESSION['framework']['login_as']; 
        $selected_role = $_POST["selected_role"];
       // print_r($_POST);
       
        if(trim($login_as)<>"guest" and trim($login_as)<>trim($selected_role) and trim($login_as)<>"" and trim($username)<>""){
			$auth     = new Auth();
	
	        $hasil_login=$auth->change_role($username,$selected_role);
	      // echo "<pre style='text-align:right;'>";print_r($hasil_login); echo "</pre>";exit;
			if($hasil_login==true) {
	           	global $dcistem;
		    	$db   = $dcistem->getOption("framework/db"); 
	          // $db    = $dcistem->getOption("framework/db");
	          	$master= new Master_Ref_Model();
	          	$master->referensi_session();
	        	date_default_timezone_set("Asia/Jakarta");
	            $ip = core::get_ip();
	   		   	$db->insert("tbatranslog",array(
	                "tLogCat" => "erp-kpbs", 
	                "tLogdate" => date("Y-m-d H:i:s"), 
	                "tLogUid" => $username, 
	                "tLogIP" => $ip[0], 
	                "tLogName" => "Change Role", 
	                "tLogDesc" => "Sukses change role as $selected_role"));
	             // echo "ce2";exit;  
				url::redirect(url::home());
			}else {
				url::redirect(url::current("index","error"));
		
			}
      }
	}
  

}
?>