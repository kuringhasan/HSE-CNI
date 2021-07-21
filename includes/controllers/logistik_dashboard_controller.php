<?php
/**
 * @package Admin
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Logistik_Dashboard_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
        global $dcistem;
        
        $tpl  = new View("logistik_dashboard");
        $db   = $dcistem->getOption("framework/db"); 
        //echo "<pre>";print_r($_SESSION["framework"]);echo "</pre>";
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
            case "rekap_populasi":
                $tpl  = new View("dashboard_rekap_populasi");
                
                $color=array("XXXXXXX"=>"#d2d6de",
                            "3205226"=>"#f39c12",//karangtengah
                            "3205225"=>"#f012be",//mande
                            "3205224"=>"#01ff70",//ciranjang
                            "3205223"=>"#00a65a",//haurwangi
                            "3205222"=>"#3c8dbc",//bojong picung
                            "3205221"=>"#00c0ef");//sukaluyu
                
                $jml=$db->select("sum(case when ifnull(is_active,0)=0 and ifnull(is_need_verification,0)=1 then 1 else null end) jml_unverified,
                sum(case when ifnull(is_active,0)=0  then 1 else null end) jml_inactive","cow")->where("ifnull(afkir,0)=0")->get(0);
                $rekap_sapi['jml_unverified']=$jml->jml_unverified;
                $rekap_sapi['jml_inactive']=$jml->jml_inactive;
              
                
                $rekap2=$db->select("sum(ifnull(jml_jd,0)) jml_jantan_dewasa,sum(ifnull(jml_induk,0)) jml_induk,sum(ifnull(jml_dara,0)) jml_dara,sum(ifnull(jml_bm,0)) jml_bm,
                sum(ifnull(jml_pedet_btn,0)) jml_pedet_btn,sum(ifnull(jml_pedet_jtn,0)) jml_pedet_jtn,
                sum(ifnull(jml_undefined,0)) jml_undefined","rekap_populasi_per_tpk")->get(0);
                
                $rekap_sapi['jml_induk']=$rekap2->jml_induk;
                $rekap_sapi['jml_dara']=$rekap2->jml_dara;
                $rekap_sapi['jml_betina_muda']=$rekap2->jml_bm;
                $rekap_sapi['jml_pedet_btn']=$rekap2->jml_pedet_btn;
                $rekap_sapi['jml_pedet_jtn']=$rekap2->jml_pedet_jtn;
                $rekap_sapi['jml_jantan_dewasa']=$rekap2->jml_jantan_dewasa;
                $rkp=$rekap->hitungPopulasi($rekap2->jml_induk,$rekap2->jml_dara,$rekap2->jml_bm,$rekap2->jml_pedet_btn,$rekap2->jml_pedet_jtn,$rekap2->jml_jantan_dewasa);
                
                $rekap_sapi['jml_populasi']=$rkp['populasi'];
                $rekap_sapi['total_sapi']=$rkp['total_sapi']+(int)$rekap2->jml_undefined;
                
               // $rekap_anggota=$db->select("count(ID_ANGGOTA) jml","anggota")->where("(ifnull(STATUS_AKTIF,0)=1 and ifnull(status,0)=1)")->get(0);
                
                //echo "<pre>";print_r($rekap_anggota);echo "</pre>";
                $tpl->rekap=$rekap_sapi;
              
                $tpl->url_populasi      = url::page(2011);
                $tpl->url_unverified    = url::page(2012);
                $tpl->url_inactive      = url::page(2013);
            break;
            case "grafik_pelayanan":
                 $tpl  = new View("dashboard_grafik_pelayanan");
                 $rekap->refreshRekap("pelayanan_by_month","last_update");
                 $rekap->refreshRekap("pelayanan_by_petugas","last_update");
                 //$rekap->refreshRekap("pelayanan_by_kasus");
                
                $bulan=((int)date("m")-1);
                $labels=array('September', 'Oktober', 'Nopember', 'Desember', 'Januari', 'Februari', 'Maret', 'April');
                 $hsl2= $rekap->getRekapSapibaruByMonth("2019",$bulan,12);
                // echo "<pre>";print_r($hsl2);echo "</pre>";
                $datasets=array();
                $j=0;
                //while($data2=current($hsl2)){
                    $datasets[$j]['label']="Sapi Baru";
                    $datasets[$j]['fillColor']="#00c0ef";
                    $datasets[$j]['strokeColor']="#00c0ef";
                    $datasets[$j]['pointColor']="#00c0ef";
                    $datasets[$j]['pointStrokeColor']="#f39c12";
                    $datasets[$j]['pointHighlightFill']="#f39c12";
                    $datasets[$j]['pointHighlightStroke']="#f39c12";
                    $datasets[$j]['data']=$hsl2['data_array'];
                    
                    //$j++;
                   // next($hsl2);
               //}
              
                $line_chart['labels']=$hsl2['label'];
                $line_chart['datasets']=$datasets;
                $tpl->line_chart= json_encode($line_chart);
            break;
        }
        $tpl->content = $tpl;
        $tpl->render();
	    
   }
 

}
 

?>