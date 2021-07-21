<?php
/**
 * @package Admin
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Membership_Dashboard_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
        global $dcistem;
        
        $tpl  = new View("membership_dashboard");
        $db   = $dcistem->getOption("framework/db"); 
        $tpl->url_dashboard      = url::current("dashboard");
        $this->tpl->content = $tpl;
        $this->tpl->render();
	    
   }
   public function dashboard($kategori) {
        global $dcistem;
        
        
        $db   = $dcistem->getOption("framework/db"); 
        $master         =new Master_Ref_Model();
        $rekap=new Adm_Rekap_Model();
        $this->settings =$master->settings();
        switch($kategori){
            case "rekap_anggota":
                $tpl  = new View("dashboard_rekap_anggota");
                                
                 $jml=$db->select("sum(case when status=1 then 1 else 0 end) jml_aktif,
                    sum(case when status=2 then 1 else 0 end) jml_aktif1, sum(case when status=3 then 1 else 0 end) jml_aktif2,
                    sum(case when status=4 then 1 else 0 end) jml_beku,sum(case when status=5 then 1 else 0 end) jml_keluar,
                    sum(case when status=0 then 1 else 0 end) calon_anggota,
                    sum(case when JENIS_KELAMIN='L' and status=1 then 1 else 0 end) jml_lakilaki,
                    sum(case when JENIS_KELAMIN='P' and status=1 then 1 else 0 end) jml_perempuan","anggota","array")->get(0);
                
             // echo "<pre>";print_r($jml);echo "</pre>";
                $tpl->rekap=$jml;
              
                $tpl->url_populasi      = url::page(2011);
                $tpl->url_unverified    = url::page(2012);
                $tpl->url_inactive      = url::page(2013);
            break;
        }
        $tpl->content = $tpl;
        $tpl->render();
	    
   }
 

}
 

?>