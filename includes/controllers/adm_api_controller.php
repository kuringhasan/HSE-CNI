<?php
/**
 * @package Admin
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Adm_Api_Controller extends Login_Template_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	

 public function upload_foto() {
    global $dcistem;
    $db   = $dcistem->getOption("framework/db");
    $msg    = array();
   //echo '<pre>';print_r($_FILES['myimage']);echo '</pre>';
   //$hasil=$_POST;//json_decode(file_get_contents("php://input"));
    ///echo 'se<pre>';print_r($hasil);echo '</pre>ed';exit;
    if(trim($_POST['username'])<>"" and trim($_POST['password'])<>"" and  trim($_POST['client_secret'])<>""){
       
        $auth     = new Auth();
        $hasil_login=$auth->loginAPI($_POST['username'], trim($_POST['password']), "auth_api",trim($_POST['client_secret']));
        if($hasil_login==true) {
            $direktori = 'foto/';
			$target = $direktori.$_FILES['myimage']['name'];
           if(move_uploaded_file($_FILES['myimage']['tmp_name'], $target)){
               
                $sqlr="update tbmmahasiswa mhs
                    inner join  tbtmahasiswareg reg on reg.mhsRegNomorIdentitas=mhs.mhsNomorIdentitas
                    set mhs.mhsFileFoto='".$_FILES['myimage']['name']."' WHERE reg.mhsRegNPM='".$_POST['NPM']."'";
			    $rslr=$db->query($sqlr);
                if(isset($rslr->error) and $rslr->error===true){
    				$msg['success']=false;
    	            $msg['message']="Sukses upload. Gagal update. ".$rslr->query_last_message. $sqlr;
    			}else{
    			     $msg['success']=true;
                    $msg['message']="Sukses";
   			    }
                
            }else{
                $msg['success']=false;
                $msg['message']="Error, gagal upload di server target";
            }
        }else{
            $msg['success']=false;
            $msg['message']="Gagal login";
        }
    }else{
        $msg['success']=false;
        $msg['message']="User, password atau Kode API tidak boleh kosong";
    }
  
    echo json_encode($msg);
    exit;
 }
 

}
 

?>