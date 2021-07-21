<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Auth Library
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class Auth {
	public $_driver;
	public $config;

	public function __construct() {
		global $dcistem;
		$this->config  = $dcistem->getOption("system/auth");
		$driver        = "Auth_".$this->config["driver"]."_Driver";
		$this->_driver = new $driver();
	}

	public function login($Username, $Password, $login_as, $ref_id = "") {
		global $dcistem;
        
		$logged = false;
		$user   = $this->_driver->check($Username, $Password, $login_as);
	//	echo "<pre>";print_r($user);	echo "</pre>";exit;
		if(is_object($user)) {
			if($login_as == "") {
				if(count($user->LevelList) > 1) {
					$_POST["username"] = $Username;
					$_POST["password"] = $Password;
					$class             = str_replace(" ","_", ucwords(str_replace("_"," ", url::controller()." Controller")));
					$Controller        = new $class();
					$Controller->_login_as();
					exit;
				} else {
					$login_as = $user->LevelList[0]->LevelID;
					$level    = new Core_Level_Model($login_as);
				//	echo "<pre>";print_r($level);echo "</pre>";exit;
					$RefName  = $level->RefName;
					if(!empty($RefName)) {
						$ref_id = $user->LevelList[0]->$RefName;
					} else {
						$ref_id = "";
					}
				}
			}
			$logged = false;
			
			if(in_array($login_as, $this->config["allowed"])) {
				if($ref_id == "") {
					$ref_id = text::filter(strtolower($_POST["ref_id"]), "ucase lcase num space symbol");
				}
                $ref_id = strtoupper($ref_id);
				$find = false;
				while($data = each($user->LevelList)) {
					$find_level = false;
					$find_ref   = false;
					if($data[1]->LevelID == $login_as) {
						$find_level = true;
					}
					$level    = new Core_Level_Model($data[1]->LevelID);
					if(is_object($level)) {
						$RefName  = $level->RefName;
					}
					if(empty($RefName) || $data[1]->LevelID == "super_administrator") {
						$find_ref = true;
					} else {
						if(strtoupper($data[1]->$RefName) == strtoupper($ref_id)) {
							$find_ref = true;
						}
					}
					if($find_level && $find_ref) {
						$find = true;
						break;
					}
				}
			
				if($find) {
					$logged = true;
				}
			}
		}
		if($logged) {
			//print_r($user->LevelList[0]);
			$level_cu    = new Core_Level_Model($login_as);
			$_SESSION["framework"]["user_level"]=$level_cu;
			$_SESSION["framework"]["current_user"] = $user;
			$_SESSION["framework"]["login_as"]     = $login_as;
			$_SESSION["framework"]["ref_id"]       = $ref_id;
		}
		return $logged;
	}
  public function loginAPI($Username, $Password, $login_as, $client_scret ) {
		global $dcistem;
         $db   = $dcistem->getOption("framework/db");
        $result=array();
        if($login_as <> "") {
            $usr_lvl=new Core_User_Level_Model();
            $current_role = $usr_lvl->getUserLevel($Username,$login_as);
            if(!empty($current_role)){
                $user   = $this->_driver->check($Username, $Password, $login_as);
                //print_r($user);
                if($user==false){
                    $result['success']=false;
                    $result['message']="Username atau password salah";
                }else{
                    $token=TEXT::kodeAcak(15);
                    $sql="UPDATE tbaappuserlist SET AppUserListAPIKey='".$token."' WHERE AppUserListUsername='".$Username."'";
                    $db->query($sql);
                    unset($user->LevelList);
                    $result['success']=true;
                    $result['message']="Berhasil login";
                     $result['ref_id']=$current_role->RefID;
                     $result['key']=$token;
                    $result['role']=$current_role->LevelID;
                    $result['role_detail']=$current_role ;
                    $result['user']=$user;
                    
                }
            }else{
                $result['success']=false;
                $result['message']="Role tidak ditemukan";
            }
        }else{
    	    $result['success']=false;
            $result['message']="Role harus ditentukan";
    	}	
		return $result;
	}
public function change_role($Username,$role_selected,$ref_id="") {
		global $dcistem;
        
		$logged = false;
        $usr   = new Core_User_Model();
        $user=$usr->getDataFrom_Username($Username);

		if(is_object($user)) {
		
			$logged = false;
			
			if(in_array($role_selected, $this->config["allowed"])) {
			 
				if($ref_id == "") {
					$usr_level=new Core_User_Level_Model(); 
                    $ul=$usr_level->getUserLevel($Username,$role_selected);
                   
                    $ref_id=$ul->RefID;
                    //$ref_id = text::filter(strtolower($_POST["ref_id"]), "ucase lcase num space symbol");
				}
                $ref_id = strtoupper($ref_id);
				$find = false;
               
				while($data = each($user->LevelList)) {
					$find_level = false;
					$find_ref   = false;
					if($data[1]->LevelID == $role_selected) {
						$find_level = true;
					}
					$level    = new Core_Level_Model($data[1]->LevelID);
					if(is_object($level)) {
						$RefName  = $level->RefName;
					}
					if(empty($RefName) || $data[1]->LevelID == "super_administrator") {
						$find_ref = true;
					} else {
						if(strtoupper($data[1]->$RefName) == strtoupper($ref_id)) {
							$find_ref = true;
						}
					}
					if($find_level && $find_ref) {
						$find = true;
						break;
					}
				}
			
				if($find) {
					$logged = true;
				}
			}
		}
       // echo $logged;
        // 
		if($logged) {
			//print_r($user->LevelList[0]);
            $_SESSION["framework"]["current_user"] = new Core_User_Model();
    		$_SESSION["framework"]["login_as"]     = "guest";
    		$_SESSION["framework"]["ref_id"]       = "";
    	//	session_destroy();
            
			$level_cu    = new Core_Level_Model($role_selected);
			$_SESSION["framework"]["user_level"]=$level_cu;
			$_SESSION["framework"]["current_user"] = $user;
			$_SESSION["framework"]["login_as"]     = $role_selected;
			$_SESSION["framework"]["ref_id"]       = $ref_id;
		}
        //echo "$logged<pre >";print_r($_SESSION["framework"]); echo "</pre>";exit;
		return $logged;
	}
	public function logout() {
		$_SESSION["framework"]["current_user"] = new Core_User_Model();
		$_SESSION["framework"]["login_as"]     = "guest";
		$_SESSION["framework"]["ref_id"]       = "";
		session_destroy();
		return true;
	}

}
?>