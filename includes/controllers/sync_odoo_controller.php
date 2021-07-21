<?php
/**
 * @package Admin
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Sync_Odoo_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        set_time_limit(3200);
	    ini_set("memory_limit","512M"); 
        
	}
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("adm_sync");
        $db   = $dcistem->getOption("framework/db"); 
        $master=new Master_Ref_Model();
         $api=new Odoo_Api_Model();
        // $api->sync_field();
     // $test= $api->connect();
     // echo"<pre>"; print_r($test);echo"</pre>"; 
        $this->settings =$master->settings();
	
        $domain_api  = $this->settings['odoo_ip'];//"111.223.254.14";
        $port_api    = $this->settings['odoo_port'];//"8069";
        $url_odoo    = "http://".$domain_api.":".$port_api;
        $tpl->url_odoo = $url_odoo;
        
        $tpl->is_connected_odoo=$api->connect();
        $versi= $api->version();
       // echo"<pre>"; print_r($versi);echo"</pre>"; 
        $tpl->versi = $versi;
        $tpl->url_check_sync = url::current("check_sync");
        $tpl->url_sync_products = url::current("sync_products");
        $tpl->url_sync_tpk = url::current("sync_tpk");
        $tpl->url_sync_kelompok = url::current("sync_kelompok");
        $tpl->url_sync_kelompok_harga = url::current("sync_kelompok_harga");
        $tpl->url_sync_members = url::current("sync_members");
        $tpl->url_sync_sales = url::current("sync_sales");
        $tpl->url_sync_pendapatan = url::current("sync_pendapatan");
		$this->tpl->content = $tpl;
		$this->tpl->render();
	    
   }
   public function check_sync() {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $errorlevel=error_reporting();
        //error_reporting($errorlevel & ~E_NOTICE);
        
        try{
    		set_time_limit(0); 
    		ob_implicit_flush(true);
    		ob_end_flush();
            $cek_mcp=$db->select("sum(case when ifnull(sync,false)=false then 1 else 0 end ) jml_not_sync,
            sum(case when ifnull(sync,false)=1 then 1 else 0 end ) jml_sync","mcp")->get(0);
            //print_r($cek_mcp);
            sleep(1);
            $msg_tpk=$cek_mcp->jml_not_sync==0?"Sudah semua":" tersisa ".$cek_mcp->jml_not_sync." belum tersinkronisasi";
            $message = "Sinkronisasi TPK $msg_tpk"; 
            echo '<script>window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';     
            
            $cek_kel=$db->select("sum(case when ifnull(sync,false)=false then 1 else 0 end ) jml_not_sync,
        sum(case when ifnull(sync,false)=1 then 1 else null end ) jml_sync","kelompok")->get(0);
            sleep(1);
            $msg_kel=$cek_kel->jml_not_sync==0?"Sudah semua":" tersisa ".$cek_kel->jml_not_sync." belum tersinkronisasi";
            $message = "Sinkronisasi Kelompok $msg_kel"; 
            echo '<script>window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';     
            
            $cek_kh=$db->select("sum(case when ifnull(sync,false)=false then 1 else 0 end ) jml_not_sync,
            sum(case when ifnull(sync,false)=1 then 1 else 0 end ) jml_sync","kelompok_harga")->get(0);
            sleep(1);
            //print_r($cek_kh);
            $msg_kh=$cek_kh->jml_not_sync==0?"Sudah semua":" tersisa ".$cek_kh->jml_not_sync." belum tersinkronisasi";
            $message = "Sinkronisasi Kelompok Harga $msg_kh"; 
            echo '<script>window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';     
            
            $cek_pro=$db->select("sum(case when ifnull(sync,false)=false then 1 else 0 end ) jml_not_sync,
            sum(case when ifnull(sync,false)=1 then 1 else 0 end ) jml_sync","barang")->get(0);
            sleep(1);
            //print_r($cek_pro);
            $msg_pro=$cek_pro->jml_not_sync==0?"Sudah semua":" tersisa ".$cek_pro->jml_not_sync." belum tersinkronisasi";
            $message = "Sinkronisasi Product,  $msg_pro"; 
            echo '<script>window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';     
            
            $cek_mem=$db->select("sum(case when ifnull(sync,false)=false then 1 else 0 end ) jml_not_sync,
            sum(case when ifnull(sync,false)=1 then 1 else 0 end ) jml_sync","anggota")->get(0);
            sleep(1);
           // print_r($cek_pro);
            $msg_mem=$cek_mem->jml_not_sync==0?"Sudah semua":" tersisa ".$cek_mem->jml_not_sync." belum tersinkronisasi";
            $message = "Sinkronisasi Anggota KPBS,  $msg_mem"; 
            echo '<script>window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';   
        } catch (Exception $e){
    		$message = 'Error : ' .$e->getMessage();
    		echo '<script type="text/javascript">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
    	}
     
    }
     public function sync_coa() {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        $api=new Odoo_Api_Model();
        $api->sync_coa();
    }
    public function sync_tpk() {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        $api=new Odoo_Api_Model();
      $test= $api->connect();
      
      // echo"<pre>"; print_r($test);echo"</pre>"; exit;
        $msg_error  ="";
        if(!$api->connect()){
            $msg_error=date("d/m/Y H:i:s")." : Tidak dapat terhubung ke server Odoo, silahkan periksa kembali URL, username atau password API";
           
         }else{
            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "Connected to Odoo server!" + "<br />";</script>';
        }
      
        if(trim($msg_error)==""){
            try{
        		set_time_limit(0); 
        		ob_implicit_flush(true);
        		ob_end_flush();
                $list_ada=$db->select("id,mcp_type,name,address,is_active","mcp")->where("ifnull(sync,false)=false")->orderBy("id desc")->get();
                $hasil_sync=array();
                $j  = 0;
                $jumlah_data    =count($list_ada);
                $jumlah_gagal   =0;
                if(!empty($list_ada)){
                    while($data=current($list_ada)){
                        
                        $sync_data=$api->sync_tpk("tpk",$data);
                        //print_r($sync_kelompok_harga);exit;
                        if(!empty($sync_data)){
                            $hasil_sync[$i]=$sync_data;
                            if($sync_data['sync']==false){
                                $jumlah_gagal++;
                            }
                        }
                        sleep(1);
                        $persen=round(($j/$jumlah_data)*100,2);
        				$sisa_data=$jumlah_data-$j;
        				$sync=$sync_data['sync']?"Berhasil":"gagal";
        			    $message = date("d/m/Y H:i:s")." : TPK : ".$sync_data['name']." | Sync : ".$sync." | ".$persen. '% complete | '.$sync_data['message'];
        			    $progress = number_format($persen,2,",",".");;
        			    $progressor=$persen;
                        echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% sisa data :'.$sisa_data.' dari '.$jumlah_data.' | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                        $j++;
                        next($list_ada);
                    }
                    sleep(1);
        			$message = date("d/m/Y H:i:s").' : Complete sync TPK';
        			$progressor = 100;
        			echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                }else{
                    sleep(1);
                    $message="Tidak ada data yang perlu disinkronisasi";
                    echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'!" + "<br />";</script>';
                }
            } catch (Exception $e){
                $message = 'Error : ' .$e->getMessage();
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
            }
       }else{
            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$msg_error.'" + "<br />";</script>';
       }
    }
    public function sync_kelompok() {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        $api=new Odoo_Api_Model();
        $msg_error  ="";
        if(!$master->is_connected("www.google.com")){
            $msg_error=date("d/m/Y H:i:s")." : Belum terhubung ke internet";
        }else{
            if(!$api->connect()){
                $msg_error=date("d/m/Y H:i:s")." : Tidak dapat terhubung ke server Odoo, silahkan periksa kembali URL, username atau password API";
            }else{
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "Connected to Odoo server!" + "<br />";</script>';
            }
        }
        if(trim($msg_error)==""){
            try{
        		set_time_limit(0); 
        		ob_implicit_flush(true);
        		ob_end_flush();
                $list_ada=$db->select("id,mcp_id,name","kelompok")->where("ifnull(sync,false)=false")->orderBy("id desc")->get();
                $hasil_sync=array();
                $j  = 0;
                $jumlah_data    =count($list_ada);
                $jumlah_gagal   =0;
                if(!empty($list_ada)){
                    while($data=current($list_ada)){
                        
                        $sync_data=$api->sync_tpk("kelompok",$data);
                        //print_r($sync_kelompok_harga);exit;
                        if(!empty($sync_data)){
                            $hasil_sync[$i]=$sync_data;
                            if($sync_data['sync']==false){
                                $jumlah_gagal++;
                            }
                        }
                        sleep(1);
                        $persen=round(($j/$jumlah_data)*100,2);
        				$sisa_data=$jumlah_data-$j;
        				$sync=$sync_data['sync']?"Berhasil":"gagal";
        			    $message = date("d/m/Y H:i:s")." : Kelompok : ".$sync_data['name']." | Sync : ".$sync." | ".$persen. '% complete | '.$sync_data['message'];
        			    $progress = number_format($persen,2,",",".");;
        			    $progressor=$persen;
                        echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% sisa data :'.$sisa_data.' dari '.$jumlah_data.' | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                        $j++;
                        next($list_ada);
                    }
                    sleep(1);
        			$message = date("d/m/Y H:i:s").' : Complete sync products';
        			$progressor = 100;
        			echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                }else{
                    sleep(1);
                    $message="Tidak ada data yang perlu disinkronisasi";
                    echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'!" + "<br />";</script>';
                }
            } catch (Exception $e){
                $message = 'Error : ' .$e->getMessage();
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
            }
       }else{
            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$msg_error.'" + "<br />";</script>';
       }
    }
    public function sync_kelompok_harga() {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        $api=new Odoo_Api_Model();
        $msg_error  ="";
      
        if(!$api->connect()){
            $msg_error=date("d/m/Y H:i:s")." : Tidak dapat terhubung ke server Odoo, silahkan periksa kembali URL, username atau password API";
        }else{
            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "Connected to Odoo server!" + "<br />";</script>';
        }
        if(trim($msg_error)==""){
            try{
        		set_time_limit(0); 
        		ob_implicit_flush(true);
        		ob_end_flush();
                $list_ada=$db->select("id,kelompok_id,name","kelompok_harga")->where("ifnull(sync,false)=false")->orderBy("id asc")->get();
                $hasil_sync=array();
                $j  = 0;
                $jumlah_data    =count($list_ada);
                $jumlah_gagal   =0;
                while($data=current($list_ada)){
                    
                    $sync_data=$api->sync_tpk("kelompok_harga",$data);
                    //print_r($sync_kelompok_harga);exit;
                    if(!empty($sync_data)){
                        $hasil_sync[$i]=$sync_data;
                        if($sync_data['sync']==false){
                            $jumlah_gagal++;
                        }
                    }
                    sleep(1);
                    $persen=round(($j/$jumlah_data)*100,2);
    				$sisa_data=$jumlah_data-$j;
    				$sync=$sync_data['sync']?"Berhasil":"gagal";
    			    $message = date("d/m/Y H:i:s")." : Kelompok Harga : ".$sync_data['name']." | Sync : ".$sync." | ".$persen. '% complete | '.$sync_data['message'];
    			    $progress = number_format($persen,2,",",".");;
    			    $progressor=$persen;
                    echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% sisa data :'.$sisa_data.' dari '.$jumlah_data.' | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                    $j++;
                    next($list_ada);
                }
                sleep(1);
    			$message = date("d/m/Y H:i:s").' : Complete sync products';
    			$progressor = 100;
    			echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
            } catch (Exception $e){
                $message = 'Error : ' .$e->getMessage();
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
            }
       }else{
            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$msg_error.'" + "<br />";</script>';
       }
    }
     public function sync_products_from() {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $api=new Odoo_Api_Model();
        
        $api->sync_product_form_odoo();
        try{
    		set_time_limit(0); 
    		ob_implicit_flush(true);
    		ob_end_flush();
            $list_ada=$db->select("id,odoo_id,kode,name,satuan,harga,created_by,updated_by,updated_time,display_print,unit_id","barang")
            ->where("ifnull(sync,false)=false")->get();
            $hasil_sync=array();
            $j  = 0;
            $jumlah_data    =count($list_ada);
            $jumlah_gagal   =0;
            while($data=current($list_ada)){
                
                $sync_data=$api->sync_product($data);
                if(!empty($sync_data)){
                    $hasil_sync[$i]=$sync_data;
                    if($sync_data['sync']==false){
                        $jumlah_gagal++;
                    }
                }
                sleep(1);
                $persen=round(($j/$jumlah_data)*100,2);
				$sisa_data=$jumlah_data-$j;
				$sync=$sync_data['sync']?"Berhasil":"gagal";
			    $message = $sync_data['kode'] ." | ".$sync_data['name']." | Sync : ".$sync." | ".$persen. '% complete | '.$sync_data['message'];;
			    $progress = number_format($persen,2,",",".");;
			    $progressor=$persen;
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% sisa data :'.$sisa_data.' | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                $j++;
                next($list_ada);
            }
            sleep(1);
			$message = 'Complete sync products';
			$progressor = 100;
			echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
        } catch (Exception $e){
            $message = 'Error : ' .$e->getMessage();
            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
        }
    }
    public function sync_products() {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $api=new Odoo_Api_Model();
        try{
    		set_time_limit(0); 
    		ob_implicit_flush(true);
    		ob_end_flush();
            $list_ada=$db->select("id,odoo_id,kode,name,satuan,harga,created_by,updated_by,updated_time,display_print,unit_id","barang")
            ->where("ifnull(sync,false)=false")->get();
            $hasil_sync=array();
            $j  = 0;
            $jumlah_data    =count($list_ada);
            $jumlah_gagal   =0;
            while($data=current($list_ada)){
                
                $sync_data=$api->sync_product($data);
                if(!empty($sync_data)){
                    $hasil_sync[$i]=$sync_data;
                    if($sync_data['sync']==false){
                        $jumlah_gagal++;
                    }
                }
                sleep(1);
                $persen=round(($j/$jumlah_data)*100,2);
				$sisa_data=$jumlah_data-$j;
				$sync=$sync_data['sync']?"Berhasil":"gagal";
			    $message = $sync_data['kode'] ." | ".$sync_data['name']." | Sync : ".$sync." | ".$persen. '% complete | '.$sync_data['message'];;
			    $progress = number_format($persen,2,",",".");;
			    $progressor=$persen;
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% sisa data :'.$sisa_data.' | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                $j++;
                next($list_ada);
            }
            sleep(1);
			$message = 'Complete sync products';
			$progressor = 100;
			echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
        } catch (Exception $e){
            $message = 'Error : ' .$e->getMessage();
            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
        }
    }
    
    public function sync_members() {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        $api=new Odoo_Api_Model();
        $msg_error  ="";
        if(!$api->connect()){
            $msg_error=date("d/m/Y H:i:s")." : Tidak dapat terhubung ke server Odoo, silahkan periksa kembali URL, username atau password API";
        }else{
            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "Connected to Odoo server!" + "<br />";</script>';
        }
        if(trim($msg_error)==""){
            try{
        		set_time_limit(0); 
        		ob_implicit_flush(true);
        		ob_end_flush();
                $list_ada=$db->select("ID_ANGGOTA,C_ANGGOTA,NAMA,ID_KELOMPOK,ID_KELOMPOK_HARGA,DIAWASI,
                STATUS_AKTIF,PATH_FOTO,TGL_MASUK,ALAMAT1,ALAMAT2,NO_TELP,NO_HP,TGL_LAHIR,JENIS_KELAMIN,NIK,
                NoKK,tempat_lahir,agama,ang.status","anggota ang
                inner join kelompok kel on kel.id=ang.ID_KELOMPOK")->where("ifnull(ang.sync,false)=false")->get();
                
                
                $hasil_sync=array();
                $j  = 0;
                $jumlah_data    =count($list_ada);
                $jumlah_gagal   =0;
                if(!empty($list_ada)){
                    while($data=current($list_ada)){
                        
                        $sync_result=$api->sync_member($data);
                        //print_r($sync_kelompok_harga);exit;
                        if(!empty($sync_result)){
                            $hasil_sync[$i]=$sync_result;
                            if($sync_result['sync']==false){
                                $jumlah_gagal++;
                            }
                        }
                        sleep(1);
                        $persen=round(($j/$jumlah_data)*100,2);
        				$sisa_data=$jumlah_data-$j;
        				$sync=$sync_result['sync']?"Berhasil":"gagal";
        			    $message = date("d/m/Y H:i:s")." : Anggota [".$sync_result['nomor_anggota']."] ".$sync_result['nama_anggota']." | Sync : ".$sync." | ".$persen. '% complete | '.$sync_result['message'];
        			    $progress = number_format($persen,2,",",".");;
        			    $progressor=$persen;
                        echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% sisa data :'.$sisa_data.' dari '.$jumlah_data.' | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                        $j++;
                       
                        next($list_ada);
                    }
                    sleep(1);
                    $message = date("d/m/Y H:i:s").' : Complete sync products';
                    $progressor = 100;
                    echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                }else{
                    sleep(1);
                    $message="Tidak ada data yang perlu disinkronisasi";
                    echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'!" + "<br />";</script>';
                }
            } catch (Exception $e){
                $message = 'Error : ' .$e->getMessage();
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
            }
       }else{
            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$msg_error.'" + "<br />";</script>';
       }
    }
    
    public function sync_sales() {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        $api=new Odoo_Api_Model();
        $msg_error  ="";
       
        if(!$api->connect()){
            $msg_error=date("d/m/Y H:i:s")." : Tidak dapat terhubung ke server Odoo, silahkan periksa kembali URL, username atau password API";
        }else{
            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "Connected to Odoo server!" + "<br />";</script>';
        }
        if(trim($msg_error)==""){
            try{
        		set_time_limit(0); 
        		ob_implicit_flush(true);
        		ob_end_flush();
                $list_qry=$db->select("apl.id,tanggal,date_format(tanggal,'%d%m%Y') as tgl,id_barang, brg.odoo_id odoo_barang_id,brg.name ref_nama_barang,
                brg.unit_id as uom_id,nama_barang,jumlah,apl.harga real_harga,sub_total,end_date,date_format(end_date,'%d-%m-%Y') as end_date2,
                pendapatan_id,sale_id,line_id,ang.odoo_id as odoo_anggota_id,ang.C_ANGGOTA,ang.NAMA","anggota_pendapatan_logistik apl
                inner join anggota_pendapatan ap on ap.id=apl.pendapatan_id
                inner join periode pe on pe.id=ap.periode_id
                inner join anggota ang on ang.ID_ANGGOTA=ap.anggota_id
                inner join barang brg on brg.kode=apl.id_barang")
                ->where("ifnull(apl.sync,false)=false and ap.periode_id>=24 and ap.anggota_id=10021")
                ->orderBy("tanggal asc")->lim();
                $jumlah = $db->numRow($list_qry);
                $for_sync=array();
                $k=0;
                if($jumlah>0){
                    while($data=$db->fetchObject($list_qry)){
                        
                        $for_sync[$data->tgl]['sale_id']=$data->sale_id;
                        $for_sync[$data->tgl]['tanggal']=$data->tanggal;
                        $for_sync[$data->tgl]['odoo_anggota_id']=$data->odoo_anggota_id;
                        $for_sync[$data->tgl]['nomor_anggota']=$data->C_ANGGOTA;
                        $for_sync[$data->tgl]['nama_anggota']=$data->NAMA;
                      
                        $rec1 = new stdClass;
                        $rec1->id               =$data->id;
                        $rec1->line_id          =$data->line_id;
                        $rec1->id_barang        =$data->id_barang;
                        $rec1->odoo_barang_id   =$data->odoo_barang_id;
                        $rec1->nama_barang      =$data->ref_nama_barang;
                        $rec1->description      =$data->nama_barang;
                        $rec1->qty              =$data->jumlah;
                        $rec1->uom_id              =$data->uom_id;
                        $rec1->harga            =$data->real_harga;
                        
                      //  $rec->item[$data->id]=$rec1;
                        //$item=$rec1;
                        //$rec->item[$data->tgl]=$rec1;
                        //$for_sync[$data->tgl]=$rec;
                        $for_sync[$data->tgl]['item'][$k]=$rec1;
                       
                        $k++;
                    }
                     
                
                  //  echo '<pre>';print_r($for_sync);echo '</pre>';exit;
                    
                    
                    $hasil_sync=array();
                    $j  = 0;
                    $jumlah_data    =count($for_sync);
                    $jumlah_gagal   =0;
                    while($rec=current($for_sync)){
                        
                        $sync_result=$api->sync_sale((object)$rec);  
                        //print_r($sync_kelompok_harga);exit;
                        if(!empty($sync_result)){
                            $hasil_sync[$i]=$sync_result;
                            if($sync_result['sync']==false){
                                $jumlah_gagal++;
                            }
                        }
                        sleep(1);
                        $persen=round(($j/$jumlah_data)*100,2);
        				$sisa_data=$jumlah_data-$j;
        				$sync=$sync_result['sync']?"Berhasil":"gagal";
        			    $message = date("d/m/Y H:i:s")." : Sale tanggal ".$sync_result['tanggal']." an. ".$sync_result['anggota']." | Sync : ".$sync." | ".$persen. '% complete | '.$sync_result['message'];
        			    $progress = number_format($persen,2,",",".");;
        			    $progressor=$persen;
                        echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% sisa data :'.$sisa_data.' dari '.$jumlah_data.' | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                        $j++;
                        next($for_sync);
                    }
                    sleep(1);
        			$message = date("d/m/Y H:i:s").' : Complete sync penjualan barang/pakan';
        			$progressor = 100;
        			echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                }else{//jika tidak ada data
                     sleep(1);
                    $message = 'Tidak ada data untuk di-sync';
                    echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
                }
            } catch (Exception $e){
                 sleep(1);
                $message = 'Error : ' .$e->getMessage();
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
            }
       }else{
            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$msg_error.'" + "<br />";</script>';
       }
    }
    
    public function sync_pendapatan() {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        $api=new Odoo_Api_Model();
        $msg_error  ="";
        
        if(!$api->connect()){
            $msg_error=date("d/m/Y H:i:s")." : Tidak dapat terhubung ke server Odoo, silahkan periksa kembali URL, username atau password API";
        }else{
            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "Connected to Odoo server!" + "<br />";</script>';
        }
        if(trim($msg_error)==""){
            try{
        		set_time_limit(0); 
        		ob_implicit_flush(true);
        		ob_end_flush();
                  /**  field status : state
             * draft    = Request for quotation RPQ
             * purchase = Purchase Order (PO))
             * Model Purchases          : purchase.order
             * Model Journal Entries    : account.move
             * Model Sales              : sale.order
             * ************************************** */
              $list_query=$db->select("ap.id,anggota_id,odoo_id,C_ANGGOTA,NAMA,periode_id,date_format(start_date,'%d-%m-%Y') as start_date,end_date,
              date_format(end_date,'%d-%m-%Y') as end_date2,produksi,harga_per_kg,purchase_id,
              potongan_sim_wajib,potongan_shr,potongan_mt,potongan_upp,potongan_rp15,potongan_rp10,
              potongan_bpr,potongan_dkt,potongan_iuran_desa,byr_tunggak_lalu,byr_um,byr_rumput","anggota_pendapatan ap
              inner join periode pe on pe.id=ap.periode_id
              inner join anggota ang on ang.ID_ANGGOTA=ap.anggota_id")
              ->where("ifnull(ap.sync,false)=false and ifnull(pe.closed,0)=1 and ap.periode_id>=23 and anggota_id=7143")
              ->lim(0,5);
      //echo "<pre>";print_r($list_pendapatan);echo "</pre>"; exit;
                $jumlah = $db->numRow($list_query);
                $for_sync=array();
                $k=0;
                if($jumlah>0){
                    $list_pendapatan_notsync=array();
                    while($hasil=$db->fetchObject($list_query)){
                        
                        $periode="Periode ".$hasil->start_date." - ".$hasil->end_date2;
                        $pendapatan_kotor =(object)array(//purchase
                                                    "product_odoo_id"=>46,// 46=Susu Segar
                                                    "product_desc"=>$periode,// Susu Segar
                                                    "qty"=>$hasil->produksi,// total qty susu satu periode
                                                    "price_unit"=>$hasil->harga_per_kg,// harga satuan
                                                    "product_uom"=>3);//3=kg;urchase
                       
                        $potongan['simpanan_pokok']   =(object)array("name"=>"Simpanan Pokok",
                                                    "amount"=>50000,
                                                    "debit"=>null,
                                                    "credit"=>5000,
                                                    "account_odoo"=>array("id" =>539,
                                                                          "code"=>"30.01.00.01-1",
                                                                          "name"=>"SIMPANAN POKOK UP MT")
                                                    );  //SIMPANAN POKOK UP   
                        $potongan['simpanan_wajib']   =(object)array("name"=>"Simpanan Wajib",
                                                    "amount"=>$hasil->potongan_sim_wajib,
                                                    "debit"=>null,
                                                    "credit"=>$hasil->potongan_sim_wajib,
                                                    "account"=>"30.01.00.02",
                                                    "account_odoo"=>array("id" =>540,
                                                                          "code"=>"30.01.00.02-1",
                                                                          "name"=>"SIMPANAN WAJIB UP MT")
                                                    ); //SIMPANAN WAJIB UP MT
                        $potongan['shr']            =(object)array("name"=>"Simpanan Hari Raya",
                                                    "amount"=>$hasil->potongan_shr,
                                                    "debit"=>null,
                                                    "credit"=>$hasil->potongan_shr,
                                                    "account"=>"21.03.01.01",
                                                    "account_odoo"=>array("id" =>512,
                                                                          "code"=>"21.03.01.01-1",
                                                                          "name"=>"HUTANG SIMPANAN HARI RAYA ANGGOTA UP MT")
                                                    );  //HUTANG SIMPANAN HARI RAYA ANGGOTA UP MT
                        $potongan['simpanan_mt']      =(object)array("name"=>"SIMPANAN WAJIB KHUSUS MT",
                                                    "amount"=>$hasil->potongan_mt,
                                                    "debit"=>null,
                                                    "credit"=>$hasil->potongan_mt,
                                                    "account"=>"30.01.00.03",
                                                    "account_odoo"=>array("id" =>541,
                                                                          "code"=>"30.01.00.03-1",
                                                                          "name"=>"SIMPANAN WAJIB KHUSUS MT")
                                                    );  //SIMPANAN WAJIB KHUSUS MT
                        $potongan['simpanan_upp']     =(object)array("name"=>"SIMPANAN WAJIB KHUSUS UPP",
                                                    "amount"=>$hasil->potongan_upp,
                                                    "debit"=>null,
                                                    "credit"=>$hasil->potongan_upp,
                                                    "account"=>"30.01.00.04",
                                                    "account_odoo"=>array("id" =>542,
                                                                          "code"=>"30.01.00.04-1",
                                                                          "name"=>"SIMPANAN WAJIB KHUSUS UPP")
                                                    );  //SIMPANAN WAJIB KHUSUS UPP
                                                    
                        $potongan['simpanan_15']     =(object)array("name"=>"SIMPANAN KHUSUS RP 15,- UP MT",
                                                    "amount"=>$hasil->potongan_rp15,
                                                    "debit"=>null,
                                                    "credit"=>$hasil->potongan_rp15,
                                                    "account"=>"30.01.00.05",
                                                    "account_odoo"=>array("id" =>543,
                                                                          "code"=>"30.01.00.05-1",
                                                                          "name"=>"SIMPANAN KHUSUS RP 15,- UP MT")
                                                    );  //SIMPANAN KHUSUS RP 15,- UP MT
                        $potongan['simpanan_10']     =(object)array("name"=>"SIMPANAN KHUSUS RP 10,- UP MT",
                                                    "debit"=>null,
                                                    "credit"=>$hasil->potongan_rp10,
                                                    "account"=>"30.01.00.06",
                                                    "account_odoo"=>array("id" =>544,
                                                                          "code"=>"30.01.00.06-1",
                                                                          "name"=>"SIMPANAN KHUSUS RP 10,- UP MT")
                                                    );  //SIMPANAN KHUSUS RP 10,- UP MT
                        $potongan['tabungan_bpr']     =(object)array("name"=>"TABUNGAN BPR",
                                                    "amount"=>$hasil->potongan_bpr,
                                                    "debit"=>null,
                                                    "credit"=>$hasil->potongan_bpr,
                                                    "account"=>"21.03.01.03",
                                                    "account_odoo"=>array("id" =>514,
                                                                          "code"=>"21.03.01.03-1",
                                                                          "name"=>"SIMPANAN TITIPAN UP MT")
                                                    );  //akun : SIMPANAN TITIPAN UP MT
                                                    
                        $potongan['dkt']              =(object)array("name"=>"DKT",
                                                    "amount"=>$hasil->potongan_dkt,
                                                    "debit"=>null,
                                                    "credit"=>$hasil->potongan_dkt,
                                                    "account"=>"21.05.01.01",
                                                    "account_odoo"=>array("id" =>518,
                                                                          "code"=>"21.05.01.01-1",
                                                                          "name"=>"DKT UP MT")
                                                    ); //account: DKT UP MT, tipe : Current Liabilities
                        $potongan['iuran_desa']       =(object)array("name"=>"Iuran Desa",
                                                    "amount"=>$hasil->potongan_iuran_desa,
                                                    "debit"=>null,
                                                    "credit"=>$hasil->potongan_iuran_desa,
                                                    "account"=>"21.03.01.03",
                                                    "account_odoo"=>array("id" =>514,
                                                                          "code"=>"21.03.01.03-1",
                                                                          "name"=>"SIMPANAN TITIPAN UP MT")
                                                    ); //akun : SIMPANAN TITIPAN UP MT
                                                    
                        $potongan['tunggak_lalu']     =(object)array("name"=>"Tunggak Lalu",
                                                    "amount"=>$hasil->byr_tunggak_lalu,
                                                    "debit"=>null,
                                                    "credit"=>$hasil->byr_tunggak_lalu,
                                                    "account"=>"10.02.01.09",
                                                    "account_odoo"=>array("id" =>469,
                                                                          "code"=>"10.02.01.09-1",
                                                                          "name"=>"PIUTANG TUNGGAK ANGGOTA")
                                                    ); //akun : PIUTANG TUNGGAK ANGGOTA
                        $potongan['potongan_um']     =(object)array("name"=>"PIUTANG UANG MUKA UP MT",
                                                    "amount"=>$hasil->byr_um,
                                                    "debit"=>null,
                                                    "credit"=>$hasil->byr_um,
                                                    "account"=>"10.02.01.11",
                                                    "account_odoo"=>array("id" =>470,
                                                                          "code"=>"10.02.01.11-1",
                                                                          "name"=>"PIUTANG UANG MUKA UP MT")
                                                    ); //account PIUTANG UANG MUKA UP MT
                       /* $potongan['potongan_rumput'] =(object)array("name"=>"Potongan Rumput",
                                                     "amount"=>$hasil->byr_rumput,
                                                    "debit"=>null,
                                                    "credit"=>$hasil->byr_rumput,
                                                    "account"=>"10.02.01.11",
                                                    "account_odoo"=>array("id" =>1,
                                                                          "code"=>"30.01.00.01",
                                                                          "name"=>"Simpanan POKOK UP MT")
                                                    ); //account : PIUTANG UANG MUKA UP MT*/
                        $potongan['pinjaman_peternak'] =array("name"=>"Potongan Peternak",
                                                    "amount"=>$hasil->potongan_dkt,
                                                    "debit"=>null,
                                                    "credit"=>$hasil->potongan_dkt,
                                                    "account"=>"10.02.06.02",
                                                    "account_odoo"=>array("id" =>474,
                                                                          "code"=>"10.02.06.02-1",
                                                                          "name"=>"PIUTANG ANGGOTA UP MT")
                                                    ); //account : 	PIUTANG ANGGOTA UP MT
                        $potongan['potongan_swalayan'] =(object)array("name"=>"Potongan Swalayan",
                                                    "amount"=>$hasil->byr_swalayan,
                                                    "debit"=>null,
                                                    "credit"=>$hasil->byr_swalayan,
                                                    "account"=>"21.03.01.03",
                                                    "account_odoo"=>array("id" =>514,
                                                                          "code"=>"21.03.01.03-1",
                                                                          "name"=>"SIMPANAN TITIPAN UP MT")
                                                    ); //account :SIMPANAN TITIPAN UP MT tipe : Payable
                        $potongan['potongan_sapi'] =(object)array("name"=>"Potongan Sapi",
                                                    "amount"=>$hasil->byr_sapi,
                                                    "debit"=>null,
                                                    "credit"=>$hasil->byr_sapi,
                                                    "account"=>"10.02.01.06",
                                                    "account_odoo"=>array("id" =>466,
                                                                          "code"=>"10.02.01.06-1",
                                                                          "name"=>"PIUTANG SAPI PERGULIRAN")
                                                    ); //account : 	PIUTANG SAPI PERGULIRAN tipe: receiveble
                        $potongan['pinjaman_bpr'] =(object)array("name"=>"Pinjaman BPR",
                                                    "amount"=>$hasil->byr_bpr,
                                                    "debit"=>null,
                                                    "credit"=>$hasil->byr_bpr,
                                                    "account"=>"21.04.01.01",
                                                    "account_odoo"=>array("id" =>516,
                                                                          "code"=>"21.04.01.01-1",
                                                                          "name"=>"HUTANG BPR BANDUNG KIDUL UP MT")
                                                    ); //account : HUTANG BPR BANDUNG KIDUL UP MT, tipe: Current Liabilities
                        $potongan['potongan_pengobatan'] =(object)array("name"=>"Potongan Pengobatan",
                                                    "amount"=>$hasil->byr_pengobatan,
                                                    "debit"=>null,
                                                    "credit"=>$hasil->byr_pengobatan,
                                                    "account"=>"21.05.01.01",
                                                    "account_odoo"=>array("id" =>518,
                                                                          "code"=>"21.05.01.01-1",
                                                                          "name"=>"DKT UP MT")
                                                    ); //account: DKT UP MT, tipe : Current Liabilities
                        /*$potongan['tunggakan_baru'] =(object)array("name"=>"Tunggakan Baru",
                                                    "amount"=>$hasil->tunggakan_baru,
                                                    "debit"=>null,
                                                    "credit"=>$hasil->tunggakan_baru,
                                                    "account"=>"",
                                                    "account_odoo"=>array("id" =>1,
                                                                          "code"=>"30.01.00.01",
                                                                          "name"=>"Simpanan POKOK UP MT")
                                                    ); */
                        $list_sale_qry=$db->select("id,tanggal,id_barang,nama_barang,jumlah,harga,sub_total,pendapatan_id,
                        sale_id,sync","anggota_pendapatan_logistik apl")
                        ->where("ifnull(apl.sync,false)=false and apl.pendapatan_id=".$hasil->id." GROUP BY sale_id")->lim();
                        $sales=array();
                        $s=0;
                        while($jual=$db->fetchObject($list_sale_qry)){
                            $sales[$jual->sale_id]['id']=$jual->id;
                            $sales[$jual->sale_id]['sale_id']=$jual->sale_id;
                            $s++;
                        }
                                       
                        $data_pendapatan   =(object)array("id"=>$hasil->id,
                                                "purchase_id"=>$hasil->purchase_id,
                                                "anggota_id"=>$hasil->anggota_id,
                                                "nomor_id"=>$hasil->C_ANGGOTA,
                                                "name"=>$hasil->NAMA,
                                                "odoo_anggota_id"=>$hasil->odoo_id,//5418,// id partner/member di odoo
                                                "periode_id"=>$hasil->periode_id,
                                                "periode_name"=>$periode,
                                                "closing_date"=>$hasil->end_date,
                                                "pendapatan_kotor"=>$pendapatan_kotor,
                                                "potongan"=>$potongan,
                                                "sales"=>$sales);
                        //echo '<pre>';print_r($data_pendapatan);echo '</pre>';exit;
                        $sync_result=$api->sync_pendapatan($data_pendapatan);
                        if(!empty($sync_result)){
                            $hasil_sync[$i]=$sync_result;
                            if($sync_result['sync']==false){
                                $jumlah_gagal++;
                            }
                        }
                        sleep(1);
                        
                        
                        
                        $list_pendapatan_notsync[$k]=$data_pendapatan;
                       
                        $k++;
                    }
                     
                /*
                    $hasil_sync=array();
                    $j  = 0;
                    $jumlah_data    =count($for_sync);
                    $jumlah_gagal   =0;
                    while($rec=current($for_sync)){
                        
                        $sync_result=$api->sync_sale((object)$rec);  
                        //print_r($sync_kelompok_harga);exit;
                        if(!empty($sync_result)){
                            $hasil_sync[$i]=$sync_result;
                            if($sync_result['sync']==false){
                                $jumlah_gagal++;
                            }
                        }
                        sleep(1);
                        $persen=round(($j/$jumlah_data)*100,2);
        				$sisa_data=$jumlah_data-$j;
        				$sync=$sync_result['sync']?"Berhasil":"gagal";
        			    $message = date("d/m/Y H:i:s")." : Sale tanggal ".$sync_result['tanggal']." an. ".$sync_result['anggota']." | Sync : ".$sync." | ".$persen. '% complete | '.$sync_result['message'];
        			    $progress = number_format($persen,2,",",".");;
        			    $progressor=$persen;
                        echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% sisa data :'.$sisa_data.' dari '.$jumlah_data.' | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                        $j++;
                        next($for_sync);
                    }*/
                    sleep(1);
        			$message = date("d/m/Y H:i:s").' : Complete sync penjualan barang/pakan';
        			$progressor = 100;
        			echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                }else{//jika tidak ada data
                     sleep(1);
                    $message = 'Tidak ada data untuk di-sync';
                    echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
                }
            } catch (Exception $e){
                 sleep(1);
                $message = 'Error : ' .$e->getMessage();
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
            }
       }else{
            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$msg_error.'" + "<br />";</script>';
       }
    }

}
 

?>