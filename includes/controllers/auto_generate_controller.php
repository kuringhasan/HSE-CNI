<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Auto_Generate_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        global $dcistem;
        
        $this->auto_generate=new Auto_Generate_Model();
       
	}
    public function index() {
	   global $dcistem;
    
		$tpl  = new View("list_generate");
        $db   = $dcistem->getOption("framework/db");
        
        $login=new Adm_Login_Model();
        $ref_if=$_SESSION["framework"]["ref_id"] ;
        $Username=$_SESSION["framework"]["current_user"]->Username ;
        $tpl->url_week_periode      = url::current("week_periode");
        $tpl->url_rekap_logistik      = url::current("rekap_logistik");
        $tpl->url_laporan_pelayanan      = url::current("laporan_pelayanan");
		$this->tpl->content = $tpl;
		$this->tpl->render();      
  }
    public function week_periode($tahun){
    global $dcistem;
     $db   = $dcistem->getOption("framework/db");
     date_default_timezone_set("Asia/Jakarta");
     $periode=new List_Periode_Model();
     $tgl_stamp=mktime(0,0,0,1,1,$tahun);//
     $no_hari=date("N",$tgl_stamp);// 1=senin
     echo $no_hari."  ".date("Y-m-d",$tgl_stamp)."<br />";
     if($no_hari>1){
        $tgl_stamp=mktime(0,0,0,1,1-($no_hari-1),$tahun);//
     }
     $no_hari=date("N",$tgl_stamp);// 1=senin
     echo $no_hari."  ".date("Y-m-d",$tgl_stamp)."<br />";
     $tgl_stamp2=mktime(0,0,0,(int)date("m",$tgl_stamp),((int)date("d",$tgl_stamp)+6),(int)date("Y",$tgl_stamp));//
     //$tgl_stamp2=mktime(0,0,0,1,1-($no_hari-6),$tahun);//
     echo "Minggu Ke 1 ".date("Y-m-d",$tgl_stamp)." s.d. ".date("Y-m-d",$tgl_stamp2)."<br />";
     $filter="week=1 and tahun=$tahun";
     $cek=$db->select("id","week_periode")->where($filter)->get(0);
     if(empty($cek)){
        $in_periode= $periode->insert_periode(1,date("Y-m-d",$tgl_stamp),date("Y-m-d",$tgl_stamp2),$tahun);
     }else{
        $sql="UPDATE week_periode SET start_date='".date("Y-m-d",$tgl_stamp)."',end_date='".date("Y-m-d",$tgl_stamp2)."'  WHERE $filter";
        $db->query($sql);
     }
     print_r($in_periode);echo "<br />";
     for($j=2;$j<=52;$j++){
        $mulai=($j-1)*7+1;
        $tgl_stamp=mktime(0,0,0,(int)date("m",$tgl_stamp2),((int)date("d",$tgl_stamp2)+1),(int)date("Y",$tgl_stamp2));//
        $tgl_stamp2=mktime(0,0,0,(int)date("m",$tgl_stamp),((int)date("d",$tgl_stamp)+6),(int)date("Y",$tgl_stamp));//
         echo "Minggu Ke $j ".date("Y-m-d",$tgl_stamp)." s.d. ".date("Y-m-d",$tgl_stamp2)."<br />";
         $filter="week=".$j." and tahun=$tahun";
         $cek=$db->select("id","week_periode")->where($filter)->get(0);
         if(empty($cek)){
            $in_periode= $periode->insert_periode($j,date("Y-m-d",$tgl_stamp),date("Y-m-d",$tgl_stamp2),$tahun);
         }else{
            $sql="UPDATE week_periode SET start_date='".date("Y-m-d",$tgl_stamp)."',end_date='".date("Y-m-d",$tgl_stamp2)."'  WHERE $filter";
            $db->query($sql);
         }
        
     print_r($in_periode);echo "<br />";
         
     }
  
    }
  public function rekap_logistik($kategori,$current_time=false){     
     global $dcistem;
     $db   = $dcistem->getOption("framework/db");
     /** kategori : harian, bulanan  */
     date_default_timezone_set("Asia/Jakarta");
  
     switch($kategori){
        case "bulanan":
            list($tahun,$bln,$tgl)=explode("-",date("Y-m-d"));
            if((int)$tgl==1 or $current_time==true){
               
                $waktu_acuan=mktime(0,0,0,((int)$bln-1),$tgl,$tahun);
                $generate_value		=date("Y-m",$waktu_acuan);
                if($current_time==true){
                    $generate_value		=date("Y-m");
                } 
                
                $check_qry=$db->select("DISTINCT trx_date","logistik_trx")->where("DATE_FORMAT(trx_date,'%Y-%m')='".$generate_value."'")->lim();
                while($data = $db->fetchObject($check_qry))
                { 
                    if(!empty($data)){
                        $this->auto_generate->rekap_transaksi_logistik($data->trx_date,"harian");
                    }
                }
                $this->auto_generate->rekap_transaksi_logistik($generate_value,$kategori,$current_time);
                echo "Done!";
            }
        break;
     }        
  } 
    
    public function laporan_pelayanan($jenis){
        $this->auto_generate->generate_laporan($jenis);
        echo "Done!";
    }
 
}
?>