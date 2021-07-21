<?php
/**
 * @package Admin
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Generate_Otomatis_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        set_time_limit(6400);
	    ini_set("memory_limit","1024M"); 
        
	}
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("generate_otomatis");
        $db   = $dcistem->getOption("framework/db"); 
        
        $tpl->url_check_sync = url::current("check_sync");
        $tpl->url_sync = url::current("sync");
        $tpl->url_sync_sapi = url::current("sync_cow");
        $tpl->url_sync_event = url::current("sync_event");
        $tpl->url_sync_logistik = url::current("sync_logistik");
        $tpl->url_update = url::current("update");
         $tpl->url_update_cow = url::current("update_cow");
     
		$this->tpl->content = $tpl;
		$this->tpl->render();
	    
   }
   
    public function otomatisasi($kategori) {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        /** Sapi yang kepemilikannya kepada 11606 -> 370101	PINDAH otomatis sapi tidak aktif (is_active=0)
         * */
        $sql="UPDATE cow SET is_active=null WHERE (anggota_id=11606 or ifnull(afkir,0)=1)";
        $rsl=$db->query($sql);
        if(isset($rsl->error) and $rsl->error===true){
           
            $message_result="Error insert, ".$rsl->query_last_message;
        }else{	
           
            $message_result="Berhasil insert sapi";
           
        }
         
       
      
    }
   

}
 

?>