<?php
/**
 * @package Auth
 * @subpackage Web Driver
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class Auth_Web_Driver {

	public function check($Username, $Password, $login_as) {
		global $dcistem;
       
		$config = $dcistem->getOption("system/auth");
  		$usr   = new Core_User_Model();
        //$user->getDataFrom_Username_Password($Username,  md5(crypt($Password, $Password)));
        $user=$usr->getDataFrom_Username_Password($Username,$Password);
  		//$user->getDataFrom_Username_Password($Username, md5(crypt($Password, $Password)));
        
        //echo $user->LevelList;

		if(!is_object($user)) {
			return false;
		}
		if(empty($user->Username)) {
			return false;
		}
		$levels          = $user->LevelList;
    // print_r($user->LevelList);
		$user->LevelList = array();
		if (!empty($levels)){
			while($level = each($levels)) {
				
	            if(in_array($level[1]->LevelID, $config["allowed"])) {
				 
					$user->LevelList[] = $level[1];
				}
			}
		}
      // print_r($user->LevelList);exit;
		if(count($user->LevelList)) {
		  
			return $user;
            
		} else {
			return false;
		}
	}
    
    public function check_for_change_role($Username, $Password, $login_as) {
		global $dcistem;
       
		$config = $dcistem->getOption("system/auth");
  		$usr   = new Core_User_Model();
        //$user->getDataFrom_Username_Password($Username,  md5(crypt($Password, $Password)));
        $user=$usr->getDataFrom_Username_Password_ERP($Username,$Password);
  		//$user->getDataFrom_Username_Password($Username, md5(crypt($Password, $Password)));
        
        //echo $user->LevelList;

		if(!is_object($user)) {
			return false;
		}
		if(empty($user->Username)) {
			return false;
		}
		$levels          = $user->LevelList;
    // print_r($user->LevelList);
		$user->LevelList = array();
		if (!empty($levels)){
			while($level = each($levels)) {
				
	            if(in_array($level[1]->LevelID, $config["allowed"])) {
				 
					$user->LevelList[] = $level[1];
				}
			}
		}
      // print_r($user->LevelList);exit;
		if(count($user->LevelList)) {
		 
			return $user;
            
		} else {
			return false;
		}
	}

	public function translateLevelList($translate = "field") {
		if($translate == "table") {
			return "tbaapplevellist";
		} else {
			return array(
				"AppLevelListLevelID"   => "LevelID",
				"AppLevelListLevelName" => "LevelName",
				"AppLevelListRefName"   => "RefName",
                "AppLevelUnit"          => "Unit",
                "AppLevelUnitName"      => "UnitName"
			);
		}
	}

	public function translateUserList($translate = "field") {
		if($translate == "table") {
			return "tbaappuserlist";
		} else {
			return array(
				"AppUserListUsername"   => "Username",
				"AppUserListPassword"   => "Password",
				"AppUserListName"       => "Name",
				"AppUserListEmail"      => "Email",
                "AppUserListNoInduk"      => "NoInduk",
                "AppUserListPersonalID"  =>"PersonalID"
			);
		}
	}

	public function translateUserLevel($translate = "field") {
		if($translate == "table") {
			return "tbaappuserlevel ul inner join tbaapplevellist lv on lv.AppLevelListLevelID=ul.AppUserLevelLevelID";
		} else {
			return array(
				"AppUserLevelUsername" => "Username",
				"AppUserLevelLevelID"  => "LevelID",
                "AppLevelListLevelName"  => "LevelName",
                "AppLevelListRefName"  => "RefName",
				"AppUserLevelRefID"    => "RefID"
			);
		}
	}

}
?>