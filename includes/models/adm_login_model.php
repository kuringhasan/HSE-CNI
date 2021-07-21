
<?php

/**
 * @package Admin
 * @subpackage Admin Login Modul
 * 
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
 * @author Alan Ridwan M <alanrm82@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Adm_Login_Model extends Model {
	
	public function __construct() {
		
	}
	
    public function getLevelLogin() {
          $login_as = $_SESSION["framework"]["login_as"];
          $levellist = $_SESSION["framework"]["current_user"]->LevelList; 
          //$level = $levellist[$login_as];
          $hasil['login_as']  = $login_as;
          //echo $levellist."-".$hasil['login_as'];
         // exit;
          while ($level = current($levellist)) {
				$data = new Core_Level_Model($level->LevelID);
                
				if(is_object($data)) {
				    if ($data->LevelID==$login_as) {
    					$RefName           = $data->RefName;
    					if(trim($RefName) <> "") {
                            $hasil['refname'] = $RefName;   
                            $hasil['valname'] = $level->$RefName;  
    					}
                    }
				}
                next($levellist);
          }
          return $hasil;  
    }
    
    
   public function privilegeInputForm($form_type="button", $form_input_name,$form_input_id,$value="",$page_id_current,$nama_fungsi_eksekusi="",$otherproperties="") {
		global $dcistem;
		$auth  = $dcistem->getOption("framework/auth");
		$db    = $dcistem->getOption("framework/db");
		$master=new Master_Ref_Model();
       /** ===========================================================
        * Fungsi ini untuk menyembunyikan tombol, atau tipe inputan lain sesuai dengan privilege
        * Bila privilege tidak diijinkan maha akan di-hide
		* $nama_fungsi_eksekusi adalah nama fungsi yang bisa diakses ketika form ini dieksekusi 
        * pada tabel tbaapppageprivilege field AppPagePrivilegePrivileges 
        * =============================================================*/
        //web ID=
        
        $leveluser_login_current=	$_SESSION['framework']['login_as']; 
        $web_id_current_aplikasi= $dcistem->getOption("system/web/id");
        $leveluser_login_value=$master->scurevaluetable($leveluser_login_current);
        $page_id_value=$master->scurevaluetable($page_id_current);
		$web_id_value=$master->scurevaluetable($web_id_current_aplikasi); 
		
        $result = $db->select("AppPagePrivilegePrivileges as Privileges", "tbaapppageprivilege", 
		   "array")->where("AppPagePrivilegePageID=$page_id_value and AppPagePrivilegeLevelID=$leveluser_login_value")->get(0);
        $action=$result["Privileges"];
        $array_action= Core::checkSerialize($result["Privileges"]);
	    $input="";
        
        $cek=trim($action)==""?true:in_array($nama_fungsi_eksekusi,$array_action);
	    if ($cek==true){
	       switch($form_type){
	           case "link":
                   $input="<a id=\"".$form_input_id."\"  ".$otherproperties." >".$value."</a>";
               break;
               case "button":
                   $input="<button type=\"".$form_type."\" name=\"".$form_input_name."\" id=\"".$form_input_id."\" ".$otherproperties."  >".$value."</button>";
               break;
               case "img":
                   $input="<img  name=\"".$form_input_name."\" id=\"".$form_input_id."\" ".$otherproperties."  />";
               break;
               default:
                    $input="<input type=\"".$form_type."\" name=\"".$form_input_name."\" id=\"".$form_input_id."\" value=\"".$value."\" ".$otherproperties." />";
               break;
	       }
	    }
		return $input;
	}
    
}

?>