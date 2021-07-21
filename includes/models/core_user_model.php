<?php
/**
 * @package Core
 * @subpackage User Model
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class Core_User_Model extends Model {

	public function __construct($Username = "") {
		global $dcistem;
		$auth = $dcistem->getOption("framework/auth");
		if(!is_a($auth, "Auth")) {
   			$config = $dcistem->getOption("system/auth");
   			$driver = "Auth_".$config["driver"]."_Driver";
			$auth   = new $driver();
			$dcistem->setOption("framework/auth", $auth);
		}
		if(!empty($Username)) {
			$this->getDataFrom_Username($Username);
		}
	}

	public function translate($text) {
		global $dcistem;
		$auth  = $dcistem->getOption("framework/auth");
		$list  = $auth->translateUserList();
		$key   = array_keys($list);
		$value = array_values($list);
		$find  = array_search($text, $key);
		if($find !== false) {
			return $value[$find];
		}
		$find  = array_search($text, $value);
		if($find !== false) {
			return $key[$find];
		}
		return false;
	}

	public function getData($condition) {

		$data = $this->getList($condition, "", 0);
   
		if(is_object($data)) {
			$this->appendVariable($data);
             //echo "<pre>";print_r($data);echo "</pre>";
		}
        //echo "<pre>";print_r($data);echo "</pre>";
        return $data;
	}

	public function getList($condition = "", $orders = "", $start = null, $limit = 0) {
		global $dcistem;
	//	echo $condition;exit;
        $auth  = $dcistem->getOption("framework/auth");
		$db    = $dcistem->getOption("framework/db");
		$query = $db->select($this->translate_field(array(
					"Username",
					"Password",
					"Name",
					"Email",
                    "NoInduk",
                    "PersonalID"
				)), $auth->translateUserList("table"));
		
        //$query=$db->select("AppUserListUsername, AppUserListPassword , AppUserListName, AppUserListEmail","DB_USERMANAGdev.dbo.tbaAppUserList");
       
        if(!empty($condition)) {
			$query = $query->where($condition);
            
		}
		if(!empty($orders)) {
			$query = $query->orderBy($orders);
		}
		if($limit > 0) {
			$result = text::trim($query->get($start, $limit));
		} else {
			if(!is_null($start)) {
			$result = text::trim($query->get($start));
			} else {
				$result = text::trim($query->get());
			}
		}
          
       	$level_name = array();
       
		if(is_object($result)) {
   			$user_level = new Core_User_Level_Model();
   			$level_list = $user_level->getListFrom_Username($result->Username);
            
  			while($level_data = current($level_list)) {
         		if(!key_exists($level_data->LevelID, $level_name)) {
         			$level_name[$level_data->LevelID] = new Core_Level_Model($level_data->LevelID);
         		}
         		$level = $level_name[$level_data->LevelID];
              
				if(is_object($level)) {
	         		$tmp          = new stdClass;
	         		$tmp->LevelID = $level->LevelID;
                    $tmp->RefName       = $level->RefName;
                    $tmp->LevelName     = $level->LevelName;
                    
				    //$tmp->RefName = $level_data->RefID;
					if(trim($level->RefName) <> "") {
						$RefName       = $level->RefName;
						$tmp->$RefName = $level_data->RefID;
					}
           		}
                $tmp->RefID     = $level_data->RefID;
           		$result->LevelList[] = $tmp;
  				next($level_list);
  			}
			 //reset($result->LevelList);
			 reset($result);
		} else if(is_array($result)) {
			while($data = current($result)) {
	   			$user_level = new Core_User_Level_Model();
	   			$level_list = $user_level->getListFrom_Username($data->Username);
	  			while($level_data = current($level_list)) {
	         		if(!key_exists($level_data->LevelID, $level_name)) {
	         			$level_name[$level_data->LevelID] = new Core_Level_Model($level_data->LevelID);
	         		}
	         		$level = $level_name[$level_data->LevelID];
                   
					if(is_object($level)) {
		         		$tmp          = new stdClass;
		         		$tmp->LevelID = $level->LevelID;
                        $tmp->RefName       = $level->RefName;
                        $tmp->LevelName     = $level->LevelName;
				        //$tmp->RefName = $level_data->RefID;
						if(trim($level->RefName) <> "") {
							$RefName       = $level->RefName;
							$tmp->$RefName = $level_data->RefID;
						}
	           		}
                    $tmp->RefID     = $level_data->RefID;
	           		$data->LevelList[] = $tmp;
	  				next($level_list);
	  			}
				reset($data->LevelList);
				next($result);
			}
			reset($result);
		}
      
		return $result;
	}

	public function getDataFrom_Username($Username) {
  		$condition = array(
			"Username" => $Username
		);
        
		return $this->getData($this->translate_condition($condition));
	}
	public function getDataByUsername($Username) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db"); 
		$where="AppUserListUsername='".$Username."'";
		$data=$db->select("AppUserListUsername as Username,AppUserListPassword Password,AppUserListNoInduk as NoInduk,
			AppUserListPersonalID PersonalID,AppUserListHP as NoHP,AppUserListName as Name,
			AppUserListEmail as Email,ul.AppUserLevelLevelID LevelID,level.AppLevelListLevelName LevelName,
			level.AppLevelListRefName RefName,ul.AppUserLevelRefID RefID","tbaappuserlist user
			left join tbaappuserlevel ul on ul.AppUserLevelUsername=user.AppUserListUsername
			left join tbaapplevellist level on level.AppLevelListLevelID=ul.AppUserLevelLevelID")
		 ->where($where)->get(0);
		 return $data;
	}
	public function getDataFrom_Username_Password2($Username, $Password) {
	   //$condition ="AppUserListUsername like '".$Username."%' and AppUserListPassword='".$Password."'";
	   //$master=new Master_Ref_Model();
	   //echo $Username." - ".$Password;exit;
	 	$condition = array(
		 	"(AppUserListUsername = '".$Username."' or AppUserListNoInduk = '".$Username."' or AppUserListEmail = '".$Username."')",
			"AppUserListPassword    ='".$Password."'"
		);
      
       /*  $condition = array(
			"Username"    =>$Username,
			"Password"    =>$Password
		);*/
        $hasil=$this->getData($condition);
        
      return $hasil;
	//$this->getData($this->translate_condition($condition));
	}
     public function getDataFrom_Username_Password($Username, $Password) {
        global $dcistem;
        $db   = $dcistem->getOption("framework/db"); 
        //echo cipher::encrypt("123456");
        
        $get_user=$this->getDataByUsername($Username);
        
        $hasil=false;
        if(!empty($get_user)){
            //print_r($get_user);exit;
            $hash_password_database=$get_user->Password;
            //echo "$Password <br />";
            //$hash_password_form=TEXT::better_crypt($Password);
          //  echo "$hash_password_database";
            if( password_verify($Password,$hash_password_database)){
              
                $hasil=$this->getDataFrom_Username($Username);
            
                 
            }
            //echo $hash_password;exit;
        }
        return $hasil;
        
	}
    public	function  ubah_password($Username,$new_password_encript){
		global $dcistem;
    	$db              = $dcistem->getOption("framework/db");
        $result=false;
        if(trim($Username)<>"" and trim($new_password_encript)<>""){
        	$sql="UPDATE tbaappuserlist SET AppUserListPassword='".$new_password_encript."' WHERE AppUserListUsername = '".$Username."';";
            //$db->query($sql);
            $rsl=$db->query($sql);
			if(isset($rsl->error) and $rsl->error===true){
		   	 		$result['success']=false;
                	$result['message']="Error, ".$rsl->query_last_message;
			}else{
                $result['success']=true;
                $result['message']="Password sudah diubah"; 
               
            }
        }else{
            $result['success']=false;
            $result['message']="Passord harus diisi"; 
        }
    	return $result;
	}
    public	function  change_password($Username,$new_password_non_encripted){
		global $dcistem;
    	$db              = $dcistem->getOption("framework/db");
        $msg=array();
        //echo "user:".$Username." new:".$new_password_non_encripted;
        if(trim($Username)<>"" and trim($new_password_non_encripted)<>""){
            $pass_hash=TEXT::better_crypt($new_password_non_encripted);
        	$sql="UPDATE tbaappuserlist SET AppUserListPassword='".$pass_hash."' WHERE AppUserListUsername = '".$Username."';";
            $result=$db->query($sql);
            $result=true;
            if(isset($result->error) and $result->error===true){
				$msg['success']=false;
		        $msg['message']="Error edit data, ".$result->query_last_message;
			}else{	
                $msg['success']     =true;
                $msg['message']     ="Password berhasil diubah";
            }
        }else{
            $msg['success']=false;
            $msg['message']="Error, Username dan password baru harus diisi";
        }
    	return $msg;
	}
    
   
  public function getDataFrom_Username_API($Username, $Password,$login_as,$client_secret) {
        global $dcistem;
        $db   = $dcistem->getOption("framework/db"); 
        $condition ="AppUserListUsername = '".$Username."' and AppUserListPassword='".$Password."'
                and AppUserLevelRefID='".$client_secret."' and AppUserLevelLevelID='".$login_as."'";
        $user=$db->select("AppUserListUsername","tbaappuserlist usr
                inner join tbaappuserlevel ul on ul.AppUserLevelUsername=usr.AppUserListUsername")
        ->where($condition)->get(0);
        if(empty($user)){
            return false;
        }else{
            return true;
        }
	}
    public function loginUsingAPI($Username, $Password,$login_as,$client_secret) {
        global $dcistem;
        $db   = $dcistem->getOption("framework/db"); 
        $condition ="AppUserListUsername = '".$Username."' and AppUserListPassword='".$Password."'
                and AppUserLevelRefID='".$client_secret."' and AppUserLevelLevelID='".$login_as."'";
        $user=$db->select("AppUserListUsername","tbaappuserlist usr
                inner join tbaappuserlevel ul on ul.AppUserLevelUsername=usr.AppUserListUsername")
        ->where($condition)->get(0);
        if(empty($user)){
            return false;
        }else{
            return true;
        }
	}
  
public	function  userLogged($id_personal){
		global $dcistem;
    	$db              = $dcistem->getOption("framework/db");
    
    	$user=$db->select("pID,pNoInduk NoInduk, pNama Nama, pKelamin Kelamin,
		pTempatLahir TempatLahir,pTanggalLahir TanggalLahir,pGolonganDarah GolonganDarah,
		pAlamat Alamat, pFileFoto FileFoto","tbmpersonal")->where("pID='".$id_personal."'")->get(0);
    	return $user;
	}
public function getUserProfil($username,$ref_id,$ref_name) {
    global $dcistem;
	$db   = $dcistem->getOption("framework/db");
	$master=new Master_Ref_Model();
	$data="";
	if ($ref_id<>"" and $ref_name=="id_personal"){
		$id_personal=$master->scurevaluetable($ref_id);
		$Sortir="pID=$id_personal ";
		$data  = $db->select("pID,pNoInduk NoInduk, pNama Nama, pKelamin Kelamin,
		pTempatLahir TempatLahir,pTanggalLahir TanggalLahir,pGolonganDarah GolonganDarah,
		pAlamat Alamat, pFileFoto FileFoto","tbmpersonal 
		left join tbaappuserlist on tbaAppUserList.AppUserListUsername='".$username."'")
		->WHERE($Sortir)->get(0);
	  }              
	 return $data;
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
        /**
     * Is the given user logged in?
     *
     * @param int $user Member ID
     * @param int $offset Allowed time from last login
     * @return bool
     */
  public  function isOnline($user, $offset = 30)
    {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $user = (int) $user;
        $offset = (int) $offset;

        $time = time();
        $now = $time - $offset;

       $data  = $db->select("tLogUid","tbatranslog")->where("tLogdate >= $now AND ID = $user");
        
        if (!empty($data)) {
            return true;
        } else {
            return false;
        }
    }

}
?>