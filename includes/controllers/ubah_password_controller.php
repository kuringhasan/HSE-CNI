<?php
/**
 * @package Admin UNPAD
 * @subpackage Ubah Password Controller
 * 
 * @author Alan Ridwan M <alanrm82@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Ubah_Password_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		global $dcistem;
        $db   = $dcistem->getOption("framework/db");		
		$tpl  = new View("ubah_password");
		//$tpl->url_save  = url::page(0,"save");
		$master=new Master_Ref_Model();
        $user=new Core_User_Model();
		$msg=array();
		$hasil=array();
		if ($_SESSION['ubah_password']==true){
			$_SESSION['ubah_password']="";
			url::redirect(url::current());
		}
        if (isset($_POST['simpan'])) {
            
            $psn="";
            if($_POST["password_heubeul"]=='') {
				$msg['password_heubeul'] =  "Password lama harus diisi!";
                $psn=$msg['password_heubeul'];
			}
            if($_POST["password_anyar1"]=='') {
				$msg['password_anyar1']="Password baru harus diisi!";
                $psn=$msg['password_anyar1'];
			}
            if($_POST["password_anyar1"]<>$_POST["password_anyar2"]) {
				$msg['password_anyar2'] =  "Password baru tidak sesuai!";
                $psn=$msg['password_anyar2'];
			}
			
			$psn_err="";
            if(count($msg)==0) {
                 /* if(defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
                    echo "CRYPT_BLOWFISH is enabled!";
                  } else {
                    echo "CRYPT_BLOWFISH is NOT enabled!";
                  }*/
               	$Username=$_SESSION["framework"]["current_user"]->Username;
            	$Username_nilai=$master->scurevaluetable($Username,"string");
               
                $password_baru= TEXT::better_crypt($_POST["password_anyar1"],10);
                //echo $password_baru;exit;
                $hasil= $user->ubah_password($Username,$password_baru);
                //echo "<pre>";print_r($hasil);echo "</pre>";
            }else{
            	$hasil['success']=false;
			    $hasil['message']=$psn;
                $hasil['form_error']=$msg;
            }
           // echo "<pre>";print_r($hasil);echo "</pre>";
            $tpl->Hasil=$hasil;
            
        }
        $judul=array(array("id"=>$this->page->PageName,"label"=>$this->page->PageTitle));
		if (count($ArrJudul)>0){
			array_push($judul,$ArrJudul);
		}
		$this->tpl->Judul=$judul;
        $tpl->notice   = $msg_error; 
        $page=new Core_Page_Model();
    	//$page->log_visitor("Website Damas","Halaman ".$this->tpl->content_title,url::current(),"");
		$this->tpl->content = $tpl;
		$this->tpl->render();
	}
    public function change_password_api($username) {
        header("Access-Control-Allow-Origin: *");
        //header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
        header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description, X-Requested-With, Content');
        //echo "1<pre style='text-align:left;'>";print_r($_REQUEST); echo "</pre>";
        //exit;
        $hasil=array();
        if(trim($username)=="" or  $username == null){
            $hasil['success']=false;
  		    $hasil['message']="Username tidak boleh null atau kosong";
        }else{
            $psn="";
            if(trim($_POST["password_lama"])=='') {
    			$msg['password_lama'] =  "Password lama harus diisi!";
                $psn=$msg['password_lama'];
    		}
            if(trim($_POST["password_baru"])=='') {
    			$msg['password_baru']="Password baru harus diisi!";
                $psn=$msg['password_baru'];
    		}
            if(trim($_POST["retype_password_baru"])=='') {
    			$msg['retype_password_baru']="Retype password baru harus diisi!";
                $psn=$msg['retype_password_baru'];
    		}else{
                if(trim($_POST["password_baru"])<>trim($_POST["retype_password_baru"])) {
        			$msg['retype_password_baru'] =  "Password baru tidak sesuai!";
                    $psn=$msg['retype_password_baru'];
        		}
            }
    		
            if(trim($psn)=="") {
            	
                $password_baru= TEXT::better_crypt($_POST["password_baru"],10);
                //echo $password_baru;exit;
                $hasil= $user->ubah_password($username,$password_baru);
                //echo "<pre>";print_r($hasil);echo "</pre>";
            }else{
            	$hasil['success']=false;
    		    $hasil['message']=$psn;
            }
        }
           // echo "<pre>";print_r($hasil);echo "</pre>";
           
        echo json_encode($hasil);	
	}
    
	
}
?>