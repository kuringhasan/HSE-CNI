<?php
/**
 * @package Admin
 * @subpackage Template Controller
 *
 * @author Hasa <kuring.hasan@gmail.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class Admin_Template_Controller extends Controller {

	public function __construct() {
	global $dcistem;
		parent::__construct();
		
		$this->tpl = new View("admin_template");
		$user	= new Core_User_Model();
		//$biodata	= new List_Personal_Model();
		$this->ID			= $_SESSION["framework"]["ref_id"];		
        $username=$_SESSION["framework"]["current_user"]->Username;
		$this->DataUser=$user->getDataByUsername($username);
		$login_as=	$_SESSION['framework']['login_as']; 
		//$this->tpl->appendVariable($this->page->PageProperties["params"]);
		$this->tpl->page_title    = $this->page->PageTitle;
		$this->tpl->content_title = $this->page->PageTitle;
        $this->tpl->url_current_page = url::page($this->page->PageID);
		$this->tpl->Unit 	 			=  $dcistem->getOption("system/web/unit");
		$this->tpl->WebID 	 			=  $dcistem->getOption("system/web/id");
		$this->tpl->WebName	 			=  $dcistem->getOption("system/web/name");
        $this->tpl->CurrentUser=$_SESSION['framework']['current_user'];
		$this->tpl->CurrentUserLevel=$_SESSION['framework']['user_level'];
		$this->tpl->PageID=$this->page->PageID;
		$menu           = new Core_Menu_Model();
			
		$this->tpl->menus = $menu->getMenuFrom_LevelID($_SESSION["framework"]["login_as"]);
	
		$is_online=$user->isOnline($username);
		$this->tpl->is_online   =$is_online;
		//$npm			=$_SESSION["framework"]["current_user"]->NoInduk;	
		$profil			= $this->DataUser;
		$url_foto_profil=url::base()."foto/nofoto_man.jpg";
		if($profil->pKelamin=="P"){
			$url_foto_profil=url::base()."foto/nofoto_woman.jpg";
		}
		
		if(trim($profil->pFileFoto)<>""){
			$url_foto_profil=$profil->url_foto;
			
			if (!@getimagesize($url_foto_profil)) {
				$url_foto_profil=url::base()."foto/nofoto_man.jpg";
				if($profil->pKelamin=="P"){
					$url_foto_profil=url::base()."foto/nofoto_woman.jpg";
				}
			}
		}
		$demo 	 			=  $dcistem->getOption("database/config/0/name");
		if(trim($demo)=="stieparyapari_demo"){
			$this->tpl->demo="<span style=\"font-family:Tahoma, Geneva, sans-serif; margin-left:10px;\">  DEMO APLIKASI</span>";
		}
		$this->tpl->url_updatebiodata   =url::page(2023);
		$this->tpl->user   =$profil;
        $this->tpl->user_level   =$_SESSION["framework"]['user_level'];
		$this->tpl->url_profil_foto   =$url_foto_profil;
		
		//echo "<pre>";print_r($this->DataUser);echo "</pre>";//exit;
        
        $notifications=new List_Notification_Model();
		
        $this->tpl->UnreadNotofications=$notifications->getListUnreadNotifications($login_as,$username);	
		
		$adm=new Core_Admin_Model();
        $this->tpl->CurrentUnit	=$adm->CurrentLevelUnit();
      	$this->tpl->url_ubahpassword    = url::page(2006);
        $this->tpl->url_logout    		= url::page(2003);
        $this->tpl->url_change_role    		= url::page(2003,"change_role");
	}

}
?>