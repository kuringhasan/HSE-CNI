<?php
/**
 * @package Auth
 * @subpackage Login Controller
 *
 * @author Hasan <kuring.hasan@gmail.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class Auth_Login_Controller extends Login_Template_Controller {
//class Auth_Login_Controller extends Controller {
	public function __construct() {
		parent::__construct();
	}

	public function login() {
        global $dcistem;
	    $db   = $dcistem->getOption("framework/db"); 
		$username = text::filter($_POST["username"], "lcase num . _","-");
		$password = text::filter(($_POST["password"]), "lcase ucase num space symbol enter tab");
		$login_as = text::filter($_POST["login_as"], "lcase num @_");
		$ref_id   = text::filter($_POST["ref_id"], "lcase ucase num space symbol");
        
		if(trim($username)<>"" and trim($password)<>""){
			$auth     = new Auth();
	
	        $hasil_login=$auth->login($username, $password, $login_as, $ref_id);
	       //echo "<pre style='text-align:right;'>";print_r($hasil_login); echo "</pre>";exit;
			if($hasil_login==true) {
	           	
	          // $db    = $dcistem->getOption("framework/db");
	          	$master= new Master_Ref_Model();
	          	$master->referensi_session();
	        	date_default_timezone_set("Asia/Jakarta");
	            $ip = core::get_ip();
	   		   	$db->insert("tbatranslog",array(
	                "tLogCat" => "erp-ceria", 
	                "tLogdate" => date("Y-m-d H:i:s"), 
	                "tLogUid" => $username, 
	                "tLogIP" => $ip[0], 
	                "tLogName" => "Login sistem", 
	                "tLogDesc" => "Sukses login as $login_as"));
				url::redirect(url::home());
			}else {
				url::redirect(url::current("index","error"));
			}
		}else{
		  
			url::redirect(url::current("index","err"));
		}
	}
	public function login_api() {
        header("Access-Control-Allow-Origin: *");
        //header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
        header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description, X-Requested-With, Content');
        //echo "1<pre style='text-align:left;'>";print_r($_REQUEST); echo "</pre>";
        //exit;
       //$data= $request->getParsedBody();
       // echo "2<pre style='text-align:left;'>";print_r($data); echo "</pre>";
		$username = text::filter($_POST["username"], "lcase num . _","-");
		$password = text::filter(($_POST["password"]), "lcase ucase num space symbol enter tab");
		$login_as = text::filter($_POST["role"], "lcase num @_");
        $imei       = text::filter($_POST["imei"], "num");
		$ref_id   = text::filter($_POST["ref_id"], "lcase ucase num space symbol");
       // echo $username;
		if(trim($username)<>"" and trim($password)<>"" and trim($login_as)<>""){
			$auth     = new Auth();
            $modelimei   =new Adm_Imei_Model();
           // echo "ce";
	        //$cek_imei=$modelimei->checkImei($imei);
           
           //if(!empty($cek_imei)){
	           $result=$auth->loginAPI($username, $password, $login_as, $ref_id);
            /*}else{
                $result['success']=false;
                $result['message']="IMEI HP tidak terdaftar";
            }*/
	      //  echo "<pre style='text-align:right;'>";print_r($hasil_login); echo "</pre>";exit;
		}else{
            $result['success']=false;
            $result['message']="Username, password dan role tidak boleh kosong";
		}
        echo json_encode($result);	
	}
	public function _login_as() {
    	global $dcistem;
		$list           = $dcistem->getOption("system/auth");
		$tpl            = new View("form_login_as");
		$tpl->url_login = url::current("login");
		$user           = new Core_User_Model($_POST["username"]);
		$levels         = array();
		
      //echo "<pre style='text-align:right;'>";print_r($user->LevelList); echo "</pre>";exit;
		while($each = each($user->LevelList)) {
			$data = new Core_Level_Model($each[1]->LevelID);
			if(is_object($data) && in_array($data->LevelID, $list["allowed"])) {
				$level            = new stdClass();
				$level->LevelID   = $data->LevelID;
				$level->LevelName = $data->LevelName;
				$RefName          = $data->RefName;
				if(trim($RefName) <> "") {
					$level->RefName   = $data->RefName;
					$level->RefID     = $each[1]->$RefName;
				}
				$levels[] = $level;
			}
		}
      
		$tpl->levels   = $levels;
		$tpl->username = $_POST["username"];
		$tpl->password = $_POST["password"];
		$tpl->footer   = $dcistem->getOption("system/web/name");
       $this->tpl->content_title = "";
		$this->tpl->content = $tpl;
		$this->tpl->render();
	}

	public function index($status = "") {
		global $dcistem;
		$tpl = new View("auth_login");
		
	//	$tpl->appendVariable($this->page->PageProperties["params"]);
		if($status == "error") {
			$tpl->msg_error = "Kombinasi Username dan Password salah!";
		}
        if($status == "err") {
			$tpl->msg_error = "Isi username dan password!";
		}
		$url_foto_profil=url::base()."foto/nofoto_man.jpg";
		$tpl->url_profil_foto   =$url_foto_profil;
		$tpl->url_login = url::current("login");
        $this->tpl->content_title = "";
		$this->tpl->content = $tpl;
		$this->tpl->render();
	}
    

}
?>