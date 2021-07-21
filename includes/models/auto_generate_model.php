<?php
/**
 * @package Mahasiswa
 * @subpackage Fakultas Model
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Auto_Generate_Model extends Model {
	
	public function __construct() {
	   }
   public function status_anggota() {
	global $dcistem;
		$db   = $dcistem->getOption("framework/db");
         $filter_nonaktif="";
         //generate jadi non aktif
         $cek_populasi=$db->select("","cow")->
	     $max_ID  = $db-> select("*","anggota")->where("ifnull(STATUS_AKTIF,0)=0 ")->get(0);		
		 $IDBaru=$max_ID->maxID +1;
       
		return $IDBaru;
		
	
	}
    
    public function change_cow_status_sapi_keluar() {
	   global $dcistem;
		$db   = $dcistem->getOption("framework/db");

         // ubah sapi pemilik : 11662 PINDAH /370101 yang is_active=1 menjadi is_active=0
        $rslc=$db->query("UPDATE cow SET is_active=0 where anggota_id=11662 and ifnull(is_active,0)=1");
        $hasil=array();
        if(isset($rslc->error) and $rslc->error===true){
   	 		$hasil['success']=false;
        	$hasil['message']="Gagal, ".$rslc->query_last_message;
	    }else{
	        
            $hasil['success']=true;
           	$hasil['message']="Populasi diperbarui";
        }
       
		return $hasil;
		
	
	}
    public function change_cow_status_inactive_member() {
	   global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        /** penonaktifan sapi yang berelasi dengan anggota yang tidak aktif STATUS_AKTIF=0 
            atau anggota anggota tidak ada
        =================================================================================*/
        $rslc=$db->query("UPDATE cow c LEFT JOIN anggota ang on ang.ID_ANGGOTA=c.anggota_id
         SET c.is_active=0 where (ifnull(ang.STATUS_AKTIF,0)=0  or ifnull(ang.ID_ANGGOTA,'')='') and ifnull(c.is_active,0)=1");
        $hasil=array();
        if(isset($rslc->error) and $rslc->error===true){
   	 		$hasil['success']=false;
        	$hasil['message']="Gagal, ".$rslc->query_last_message;
	    }else{
	        
            $hasil['success']=true;
           	$hasil['message']="Populasi diperbarui";
        }
       
		return $hasil;
		
	
	}
    public function sync_status_anggota_dev_from_production() {
	   global $dcistem;
		$db   = $dcistem->getOption("framework/db");

         /** Sinkronisasi penonaktifan anggota di database kpbs_db_dev 
            sesuai dengan anggota yang tidak aktif di database kpbs_db */
         
        $sql_update=" UPDATE kpbs_db_dev.anggota a1
        left join kpbs_db.anggota a2 on a2.C_ANGGOTA=a1.C_ANGGOTA SET a1.STATUS_AKTIF=0 
        where (ifnull(a2.ID_ANGGOTA,'')='' or ifnull(a2.STATUS_AKTIF,0)=0) and ifnull(a1.STATUS_AKTIF,0)=1";
        $rslc=$db->query($sql_update);
        $hasil=array();
        if(isset($rslc->error) and $rslc->error===true){
   	 		$hasil['success']=false;
        	$hasil['message']="Gagal, ".$rslc->query_last_message;
	    }else{
	        
            $hasil['success']=true;
           	$hasil['message']="Populasi diperbarui";
        }
       
		return $hasil;
		
	
	}
    
    public function rekap_transaksi_logistik($generate_for_value,$kategori="harian",$until_current_time=false) {
	   global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        /** format tanggal yyyy-mm-dd, bulan dalam bentuk yyyy-mm */
        date_default_timezone_set("Asia/Jakarta");
        $master= new Master_Ref_Model();
        $TglSkrg		=date("Y-m-d H:i:s");
       	$tgl_skrg_val	=$master->scurevaluetable($TglSkrg,"string");
        $username		=$_SESSION["framework"]["current_user"]->Username;
        $username_val	=$master->scurevaluetable($username,"string");
        $hasil=array();
        if(trim($generate_for_value)=="" or  $generate_for_value == null){
            $hasil['success']=false;
           	$hasil['message']="Error, tanggal atau bulan yang akan digenerate kosong";
                
        }else{
            
            switch ($kategori){
                case "harian": 
                    $time_now=time();
                    list($tahun,$bln,$tgl)=explode("-",$generate_for_value);
       	            $waktu_acuan=mktime(30,59,59,$bln,$tgl,$tahun);
                    $locked="";
                    if($time_now>$waktu_acuan){
                        $locked="1";
                    }
                    
                    $locked_val	=$master->scurevaluetable($locked,"string");
                    $rkp_qry=$db->select("trx_date, trx.pegawai_id,a.ID_KELOMPOK,trx.periode_id, barang_id, brg.name nama_barang,det.harga, sum(ifnull(jumlah_package,0)) jumlah_package,
                    sum(ifnull(jumlah,0)) jumlah_qty,sum(ifnull(sub_total,0)) sub_total","logistik det
                    inner join barang brg on brg.id=det.barang_id
                    inner join logistik_trx trx on trx.id=det.trx_id
                    inner join anggota a on a.ID_ANGGOTA=trx.anggota_id
                    where trx_date='".$generate_for_value."' GROUP BY  trx_date,trx.periode_id,pegawai_id,a.ID_KELOMPOK, barang_id ")->lim();
                   
                    if($db->numRow($rkp_qry)>0){  
                        while($data=$db->fetchObject($rkp_qry)){
                            
                            $trx_date_val =$master->scurevaluetable($generate_for_value,"string");
                            $periode_val =$master->scurevaluetable($data->periode_id,"number");
                            $kelompok_val =$master->scurevaluetable($data->ID_KELOMPOK,"number");
                            $barang_val =$master->scurevaluetable($data->barang_id,"number");
                            $pegawai_val =$master->scurevaluetable($data->pegawai_id,"number");
                            $jml_package_val =$master->scurevaluetable($data->jumlah_package,"number");
                            $jml_qty_val =$master->scurevaluetable($data->jumlah_qty,"number");
                            $harga_satuan_val =$master->scurevaluetable($data->harga,"number",false);
                            $sub_total_val =$master->scurevaluetable($data->sub_total,"number",false);
                           
                            if(trim($data->pegawai_id)<>""){ 
                                $filter_cek="trx_date='".$generate_for_value."' and kelompok_id=".$data->ID_KELOMPOK." and barang_id=".$data->barang_id." and 
                                            pegawai_id=".$data->pegawai_id."";
                                $cek_rekap=$db->select("id,ifnull(locked,0) locked","rekap_barangpakan_harian")->where($filter_cek)->get();
                                
                                $sql="";
                                if(empty($cek_rekap)){
                                    /** INSERT REKAP */
                                    $cols="trx_date,pegawai_id,kelompok_id,periode_id,barang_id,jml_package,jml_qty,harga_satuan,
                                            jml_harga,created_time,last_update,operator,locked";
                                    $vals   = "$trx_date_val,$pegawai_val,$kelompok_val,$periode_val,$barang_val,$jml_package_val,
                                            $jml_qty_val,$harga_satuan_val,$sub_total_val,$tgl_skrg_val,$tgl_skrg_val,$username_val,$locked_val";
                                    $sql="INSERT INTO rekap_barangpakan_harian($cols) VALUES($vals);";
                                    //echo $sql;
                                    $rslc=$db->query($sql);
                                    if(isset($rslc->error) and $rslc->error===true){
                               	 		$hasil['success']=false;
                                    	$hasil['message']="Error, ".$rslc->query_last_message;
                            	    }else{
                                        $hasil['success']=true;
                                       	$hasil['message']="Create generate rekap transaksi telah berkasil";
                                    }
                                    
                                }else{
                                     /** UPDATE REKAP */
                                    if($cek_rekap->locked==false){ 
                                        $cols_and_vals="trx_date=$trx_date_val,pegawai_id=$pegawai_val,periode_id=$periode_val,kelompok_id=$kelompok_val,
                                                barang_id=$barang_val,jml_package=$jml_package_val,jml_qty=$jml_qty_val,
                                                harga_satuan=$harga_satuan_val,jml_harga=$sub_total_val,last_update=$tgl_skrg_val,
                                                operator=$username_val,locked=$locked_val";
                                        $sql="UPDATE rekap_barangpakan_harian SET $cols_and_vals WHERE ".$filter_cek;
                                        //echo $sql;
                                        $rslc=$db->query($sql);
                                        if(isset($rslc->error) and $rslc->error===true){
                                   	 		$hasil['success']=false;
                                        	$hasil['message']="Error, ".$rslc->query_last_message;
                                	    }else{
                                            $hasil['success']=true;
                                           	$hasil['message']="Update generate rekap transaksi telah berkasil";
                                        }
                                    }else{
                                        $hasil['success']=false;
               	                        $hasil['message']="Error, data rekap sudah dikunci";
                                    }
                                 
                                }
                            }// jika pegawai kosong
                        }
                    }else{
                        $hasil['success']=false;
                        $hasil['message']="Error, data rekap sudah dikunci";
                    }
                 break;
                 case "bulanan": 
                    /**  start of bulanan  */
                    
                    $time_now=time();
                    list($tahun,$bln)=explode("-",$generate_for_value);
       	            //$waktu_acuan=mktime(7,0,1,((int)$bln+1),1,$tahun); 
                    $waktu_acuan=mktime(7,0,1,(int)$bln+1,1,$tahun); // tanggal berikutnya 1 pukul 7:00:001
                    if($from_current_time==true){
                        $waktu_acuan=mktime(7,0,1,(int)$bln+1,1,$tahun);
                    }
                    $locked="";
                    if($time_now>$waktu_acuan){
                        // kalau waktu skrg lebih besar dari tanggal 1 jam 7
                        $locked="1";
                    }
                    
                    if($time_now>$waktu_acuan or $until_current_time==true){ 
                        
                        $locked_val	=$master->scurevaluetable($locked,"string");
                        $rkp_qry=$db->select("trx_date, pegawai_id,kelompok_id, barang_id,harga_satuan, sum(ifnull(jml_package,0)) jumlah_package,
                        sum(ifnull(jml_qty,0)) jumlah_qty,sum(ifnull(jml_harga,0)) jml_harga","rekap_barangpakan_harian
                        where DATE_FORMAT(trx_date,'%Y-%m')='".$generate_for_value."' GROUP BY  DATE_FORMAT(trx_date,'%Y-%m'), pegawai_id,kelompok_id, barang_id ")->lim();
                        if($db->numRow($rkp_qry)>0){  
                            while($data=$db->fetchObject($rkp_qry)){
                              //  echo "<pre>";print_r($data);echo "</pre>";
                                $trx_date_val =$master->scurevaluetable($generate_for_value,"string");
                                $kelompok_val =$master->scurevaluetable($data->kelompok_id,"number");
                                $barang_val =$master->scurevaluetable($data->barang_id,"number");
                                $pegawai_val =$master->scurevaluetable($data->pegawai_id,"number");
                                $jml_package_val =$master->scurevaluetable($data->jumlah_package,"number");
                                $jml_qty_val =$master->scurevaluetable($data->jumlah_qty,"number");
                                $harga_satuan_val =$master->scurevaluetable($data->harga_satuan,"number",false);
                                $jml_harga_val =$master->scurevaluetable($data->jml_harga,"number",false);
                                
                                if(trim($data->pegawai_id)<>""){
                                    $filter_cek="bulan='".$generate_for_value."' and kelompok_id=".$data->kelompok_id." and barang_id=".$data->barang_id." and 
                                                pegawai_id=".$data->pegawai_id."";
                                    $cek_rekap=$db->select("id,ifnull(locked,0) locked","rekap_barangpakan_bulanan")->where($filter_cek)->get();
                                    $sql="";
                                    if(empty($cek_rekap)){
                                        /** INSERT REKAP */
                                       
                                        $cols="bulan,pegawai_id,kelompok_id,barang_id,jml_package,jml_qty,harga_satuan,
                                                jml_harga,created_time,last_update,operator,locked";
                                        $vals   = "$trx_date_val,$pegawai_val,$kelompok_val,$barang_val,$jml_package_val,
                                                $jml_qty_val,$harga_satuan_val,$jml_harga_val,$tgl_skrg_val,$tgl_skrg_val,$username_val,$locked_val";
                                        $sql="INSERT INTO rekap_barangpakan_bulanan($cols) VALUES($vals);";
                                        $rslc=$db->query($sql);
                                        if(isset($rslc->error) and $rslc->error===true){
                                   	 		$hasil['success']=false;
                                        	$hasil['message']="Error, ".$rslc->query_last_message;
                                	    }else{
                                            $hasil['success']=true;
                                           	$hasil['message']="Create generate rekap transaksi telah berkasil";
                                        }
                                        
                                    }else{
                                         /** UPDATE REKAP */
                                       
                                        if($cek_rekap->locked==false){ 
                                            $cols_and_vals="bulan=$trx_date_val,pegawai_id=$pegawai_val,kelompok_id=$kelompok_val,
                                                    barang_id=$barang_val,jml_package=$jml_package_val,jml_qty=$jml_qty_val,
                                                    harga_satuan=$harga_satuan_val,jml_harga=$jml_harga_val,last_update=$tgl_skrg_val,
                                                    operator=$username_val,locked=$locked_val";
                                            $sql="UPDATE rekap_barangpakan_bulanan SET $cols_and_vals WHERE ".$filter_cek;
                                            $rslc=$db->query($sql);
                                            if(isset($rslc->error) and $rslc->error===true){
                                       	 		$hasil['success']=false;
                                            	$hasil['message']="Error, ".$rslc->query_last_message;
                                    	    }else{
                                                $hasil['success']=true;
                                               	$hasil['message']="Update generate rekap transaksi telah berkasil";
                                            }
                                        }else{
                                            $hasil['success']=false;
                   	                        $hasil['message']="Error, data rekap sudah dikunci";
                                        }
                                     
                                    }
                                }else{
                                    //echo "kosong";
                                }
                            }
                        }else{
                            $hasil['success']=false;
                            $hasil['message']="Error, data rekap sudah dikunci";
                        }
                    }else{
                        $hasil['success']=false;
                        $hasil['message']="Error, belum waktunya generate rekap";
                    }
                 /**  end of bulanan  */
                 break;
           }
        }
		return $hasil;
		
	
	}
 public function rekap_populasi($generate_for_value,$kategori="harian",$until_current_time=false) {
	   global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        /** format tanggal yyyy-mm-dd, bulan dalam bentuk yyyy-mm */
        date_default_timezone_set("Asia/Jakarta");
        $master= new Master_Ref_Model();
        $TglSkrg		=date("Y-m-d H:i:s");
       	$tgl_skrg_val	=$master->scurevaluetable($TglSkrg,"string");
        $username		=$_SESSION["framework"]["current_user"]->Username;
        $username_val	=$master->scurevaluetable($username,"string");
        $hasil=array();
        if(trim($generate_for_value)=="" or  $generate_for_value == null){
            $hasil['success']=false;
           	$hasil['message']="Error, tanggal atau bulan yang akan digenerate kosong";
                
        }else{
            
            switch ($kategori){
                case "harian": 
                    $time_now=time();
                    list($tahun,$bln,$tgl)=explode("-",$generate_for_value);
       	            $waktu_acuan=mktime(30,59,59,$bln,$tgl,$tahun);
                    $locked="";
                    if($time_now>$waktu_acuan){
                        $locked="1";
                    }
                    
                    $locked_val	=$master->scurevaluetable($locked,"string");
                    $rkp_qry=$db->select("trx_date, trx.pegawai_id,a.ID_KELOMPOK,trx.periode_id, barang_id, brg.name nama_barang,det.harga, sum(ifnull(jumlah_package,0)) jumlah_package,
                    sum(ifnull(jumlah,0)) jumlah_qty,sum(ifnull(sub_total,0)) sub_total","logistik det
                    inner join barang brg on brg.id=det.barang_id
                    inner join logistik_trx trx on trx.id=det.trx_id
                    inner join anggota a on a.ID_ANGGOTA=trx.anggota_id
                    where trx_date='".$generate_for_value."' GROUP BY  trx_date,trx.periode_id,pegawai_id,a.ID_KELOMPOK, barang_id ")->lim();
                   
                    if($db->numRow($rkp_qry)>0){  
                        while($data=$db->fetchObject($rkp_qry)){
                            
                            $trx_date_val =$master->scurevaluetable($generate_for_value,"string");
                            $periode_val =$master->scurevaluetable($data->periode_id,"number");
                            $kelompok_val =$master->scurevaluetable($data->ID_KELOMPOK,"number");
                            $barang_val =$master->scurevaluetable($data->barang_id,"number");
                            $pegawai_val =$master->scurevaluetable($data->pegawai_id,"number");
                            $jml_package_val =$master->scurevaluetable($data->jumlah_package,"number");
                            $jml_qty_val =$master->scurevaluetable($data->jumlah_qty,"number");
                            $harga_satuan_val =$master->scurevaluetable($data->harga,"number",false);
                            $sub_total_val =$master->scurevaluetable($data->sub_total,"number",false);
                           
                            if(trim($data->pegawai_id)<>""){ 
                                $filter_cek="trx_date='".$generate_for_value."' and kelompok_id=".$data->ID_KELOMPOK." and barang_id=".$data->barang_id." and 
                                            pegawai_id=".$data->pegawai_id."";
                                $cek_rekap=$db->select("id,ifnull(locked,0) locked","rekap_barangpakan_harian")->where($filter_cek)->get();
                                
                                $sql="";
                                if(empty($cek_rekap)){
                                    /** INSERT REKAP */
                                    $cols="trx_date,pegawai_id,kelompok_id,periode_id,barang_id,jml_package,jml_qty,harga_satuan,
                                            jml_harga,created_time,last_update,operator,locked";
                                    $vals   = "$trx_date_val,$pegawai_val,$kelompok_val,$periode_val,$barang_val,$jml_package_val,
                                            $jml_qty_val,$harga_satuan_val,$sub_total_val,$tgl_skrg_val,$tgl_skrg_val,$username_val,$locked_val";
                                    $sql="INSERT INTO rekap_barangpakan_harian($cols) VALUES($vals);";
                                    //echo $sql;
                                    $rslc=$db->query($sql);
                                    if(isset($rslc->error) and $rslc->error===true){
                               	 		$hasil['success']=false;
                                    	$hasil['message']="Error, ".$rslc->query_last_message;
                            	    }else{
                                        $hasil['success']=true;
                                       	$hasil['message']="Create generate rekap transaksi telah berkasil";
                                    }
                                    
                                }else{
                                     /** UPDATE REKAP */
                                    if($cek_rekap->locked==false){ 
                                        $cols_and_vals="trx_date=$trx_date_val,pegawai_id=$pegawai_val,periode_id=$periode_val,kelompok_id=$kelompok_val,
                                                barang_id=$barang_val,jml_package=$jml_package_val,jml_qty=$jml_qty_val,
                                                harga_satuan=$harga_satuan_val,jml_harga=$sub_total_val,last_update=$tgl_skrg_val,
                                                operator=$username_val,locked=$locked_val";
                                        $sql="UPDATE rekap_barangpakan_harian SET $cols_and_vals WHERE ".$filter_cek;
                                        //echo $sql;
                                        $rslc=$db->query($sql);
                                        if(isset($rslc->error) and $rslc->error===true){
                                   	 		$hasil['success']=false;
                                        	$hasil['message']="Error, ".$rslc->query_last_message;
                                	    }else{
                                            $hasil['success']=true;
                                           	$hasil['message']="Update generate rekap transaksi telah berkasil";
                                        }
                                    }else{
                                        $hasil['success']=false;
               	                        $hasil['message']="Error, data rekap sudah dikunci";
                                    }
                                 
                                }
                            }// jika pegawai kosong
                        }
                    }else{
                        $hasil['success']=false;
                        $hasil['message']="Error, data rekap sudah dikunci";
                    }
                 break;
                 case "bulanan": 
                    /**  start of bulanan  */
                    
                    $time_now=time();
                    list($tahun,$bln)=explode("-",$generate_for_value);
       	            //$waktu_acuan=mktime(7,0,1,((int)$bln+1),1,$tahun); 
                    $waktu_acuan=mktime(7,0,1,(int)$bln+1,1,$tahun); // tanggal berikutnya 1 pukul 7:00:001
                    if($from_current_time==true){
                        $waktu_acuan=mktime(7,0,1,(int)$bln+1,1,$tahun);
                    }
                    $locked="";
                    if($time_now>$waktu_acuan){
                        // kalau waktu skrg lebih besar dari tanggal 1 jam 7
                        $locked="1";
                    }
                    
                    if($time_now>$waktu_acuan or $until_current_time==true){ 
                        
                        $locked_val	=$master->scurevaluetable($locked,"string");
                        $rkp_qry=$db->select("trx_date, pegawai_id,kelompok_id, barang_id,harga_satuan, sum(ifnull(jml_package,0)) jumlah_package,
                        sum(ifnull(jml_qty,0)) jumlah_qty,sum(ifnull(jml_harga,0)) jml_harga","rekap_barangpakan_harian
                        where DATE_FORMAT(trx_date,'%Y-%m')='".$generate_for_value."' GROUP BY  DATE_FORMAT(trx_date,'%Y-%m'), pegawai_id,kelompok_id, barang_id ")->lim();
                        if($db->numRow($rkp_qry)>0){  
                            while($data=$db->fetchObject($rkp_qry)){
                              //  echo "<pre>";print_r($data);echo "</pre>";
                                $trx_date_val =$master->scurevaluetable($generate_for_value,"string");
                                $kelompok_val =$master->scurevaluetable($data->kelompok_id,"number");
                                $barang_val =$master->scurevaluetable($data->barang_id,"number");
                                $pegawai_val =$master->scurevaluetable($data->pegawai_id,"number");
                                $jml_package_val =$master->scurevaluetable($data->jumlah_package,"number");
                                $jml_qty_val =$master->scurevaluetable($data->jumlah_qty,"number");
                                $harga_satuan_val =$master->scurevaluetable($data->harga_satuan,"number",false);
                                $jml_harga_val =$master->scurevaluetable($data->jml_harga,"number",false);
                                
                                if(trim($data->pegawai_id)<>""){
                                    $filter_cek="bulan='".$generate_for_value."' and kelompok_id=".$data->kelompok_id." and barang_id=".$data->barang_id." and 
                                                pegawai_id=".$data->pegawai_id."";
                                    $cek_rekap=$db->select("id,ifnull(locked,0) locked","rekap_barangpakan_bulanan")->where($filter_cek)->get();
                                    $sql="";
                                    if(empty($cek_rekap)){
                                        /** INSERT REKAP */
                                       
                                        $cols="bulan,pegawai_id,kelompok_id,barang_id,jml_package,jml_qty,harga_satuan,
                                                jml_harga,created_time,last_update,operator,locked";
                                        $vals   = "$trx_date_val,$pegawai_val,$kelompok_val,$barang_val,$jml_package_val,
                                                $jml_qty_val,$harga_satuan_val,$jml_harga_val,$tgl_skrg_val,$tgl_skrg_val,$username_val,$locked_val";
                                        $sql="INSERT INTO rekap_barangpakan_bulanan($cols) VALUES($vals);";
                                        $rslc=$db->query($sql);
                                        if(isset($rslc->error) and $rslc->error===true){
                                   	 		$hasil['success']=false;
                                        	$hasil['message']="Error, ".$rslc->query_last_message;
                                	    }else{
                                            $hasil['success']=true;
                                           	$hasil['message']="Create generate rekap transaksi telah berkasil";
                                        }
                                        
                                    }else{
                                         /** UPDATE REKAP */
                                       
                                        if($cek_rekap->locked==false){ 
                                            $cols_and_vals="bulan=$trx_date_val,pegawai_id=$pegawai_val,kelompok_id=$kelompok_val,
                                                    barang_id=$barang_val,jml_package=$jml_package_val,jml_qty=$jml_qty_val,
                                                    harga_satuan=$harga_satuan_val,jml_harga=$jml_harga_val,last_update=$tgl_skrg_val,
                                                    operator=$username_val,locked=$locked_val";
                                            $sql="UPDATE rekap_barangpakan_bulanan SET $cols_and_vals WHERE ".$filter_cek;
                                            $rslc=$db->query($sql);
                                            if(isset($rslc->error) and $rslc->error===true){
                                       	 		$hasil['success']=false;
                                            	$hasil['message']="Error, ".$rslc->query_last_message;
                                    	    }else{
                                                $hasil['success']=true;
                                               	$hasil['message']="Update generate rekap transaksi telah berkasil";
                                            }
                                        }else{
                                            $hasil['success']=false;
                   	                        $hasil['message']="Error, data rekap sudah dikunci";
                                        }
                                     
                                    }
                                }else{
                                    //echo "kosong";
                                }
                            }
                        }else{
                            $hasil['success']=false;
                            $hasil['message']="Error, data rekap sudah dikunci";
                        }
                    }else{
                        $hasil['success']=false;
                        $hasil['message']="Error, belum waktunya generate rekap";
                    }
                 /**  end of bulanan  */
                 break;
           }
        }
		return $hasil;
		
	
	}
  
 	
}
?>