<?php
/**
 * @package Admin
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Migrasi_Keswan_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        set_time_limit(6400);
	    ini_set("memory_limit","1024M"); 
        
	}
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("migrasi_keswan");
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
    public function sync() {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
       
      
       // if(trim($msg_error)==""){
            try{
        		set_time_limit(0); 
        		ob_implicit_flush(true);
        		ob_end_flush();
                $list_qry=$db->select("IDAnggota,NamaLengkap,TPK,TPS,Korwil,Catatan,NonAktip,Foto,Kel,TglMskCalon,
                TglMskAngg,case when ifnull(TglMskAngg,'')<>'' then DATE_FORMAT(TglMskAngg,'%Y-%m-%d') else null end TanggalMasuk,Calon,PosPenampung,Kelompok,Alamat1,Alamat2,
                Aktif,FarmRecord,MasukAnggota,TglLahirAnggota,DATE_FORMAT(TglLahirAnggota,'%Y-%m-%d') TglLahir,
                JnsKelamin,migrasi","db_keswan.anggota_1")->where("ifnull(migrasi,0)=0")->orderBy("IDAnggota desc")->lim();
                $hasil_sync=array();
                $j  = 0;
                $jumlah_data    =$db->numRow($list_qry);
                $jumlah_gagal   =0;
                $jumlah_berhasil   =0;
                if($jumlah_data>0){
                     while($data = $db->fetchObject($list_qry)){
                    	$TglSkrg		=date("Y-m-d H:i:s");
                        $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
                        $message="";
                        $check_anggota=$db->select("ID_ANGGOTA,C_ANGGOTA,NAMA,TGL_MASUK","anggota")->where("C_ANGGOTA='".$data->IDAnggota."'")->get();
                        $migrasi=false;
                        if(empty($check_anggota)){
                           $migrasi=true;
                           //insert anggota
                           $cols="C_ANGGOTA,BARCODE,NAMA,ID_KELOMPOK,ID_KELOMPOK_HARGA,
                           STATUS_AKTIF,TGL_MASUK,ALAMAT1,ALAMAT2,TGL_LAHIR,JENIS_KELAMIN,
                           alamat,updated_time";
                           $nama_val           =$master->scurevaluetable($data->NamaLengkap);
        	               $no_anggota_val     =$master->scurevaluetable($data->IDAnggota);
                           $status_val		=$master->scurevaluetable($data->NonAktip,"number",false);
                           $tgl_masuk_val     =$master->scurevaluetable($data->TanggalMasuk);
                           $alamat1_val     =$master->scurevaluetable($data->Alamat1);
                           $alamat2_val     =$master->scurevaluetable($data->Alamat2);
                           $TglLahir_val     =$master->scurevaluetable($data->TglLahir);
                           $sex_val     =$master->scurevaluetable($data->JnsKelamin);
            				$values="$no_anggota_val,$no_anggota_val,$nama_val,null,null,
                            $status_val,$tgl_masuk_val,$alamat1_val,$alamat2_val,$TglLahir_val,$sex_val,$alamat1_val,
                            $tgl_skrg_val";
            				$sqlin="INSERT INTO anggota ($cols) VALUES ($values);";
                            $rsl=$db->query($sqlin);
            				if(isset($rsl->error) and $rsl->error===true){
            					$migrasi=false;
            			        $message_result="Error insert, ".$rsl->query_last_message;
            				}else{	
            				    $jumlah_berhasil++;
                                $message_result="Berhasil insert anggota";
                                $sqlup="UPDATE db_keswan.anggota_1 set migrasi=1 WHERE IDAnggota='".$data->IDAnggota."';";
                                $db->query($sqlup);
                                
            	            }
                        }else{
                           $jumlah_gagal++;
                            $message_result="Data anggota sudah ada di ERP";
                            if(trim($check_anggota->TGL_MASUK)=="" and trim($data->TanggalMasuk)<>""){
                                $tgl_masuk_val     =$master->scurevaluetable($data->TanggalMasuk);
                                $sqlupa="UPDATE anggota set TGL_MASUK=$tgl_masuk_val WHERE C_ANGGOTA='".$data->IDAnggota."';";
                                $hsl2=$db->query($sqlupa);
                                if(isset($hsl2->error) and $hsl2->error===true){
                                    $migrasi=false;
                                    $message_result="Error update, ".$rsl->query_last_message;
                                }else{	
                                    $migrasi=true;
                                    $message_result="Update tanggal masuk di ERP";
                                     $sqlup="UPDATE db_keswan.anggota_1 set migrasi=1 WHERE IDAnggota='".$data->IDAnggota."';";
                                    $db->query($sqlup);
                	            }
                            }else{
                                $migrasi=false;
                                $sqlup="UPDATE db_keswan.anggota_1 set migrasi=1 WHERE IDAnggota='".$data->IDAnggota."';";
                                $db->query($sqlup);
                            }
                        }
                        sleep(1);
                        $persen=round(($j/$jumlah_data)*100,2);
        				$sisa_data=$jumlah_data-$j;
        				$migration=$migrasi?"Berhasil":"Gagal";
        			    $message = date("d/m/Y H:i:s")."  <span id='col-members'> [".$data->IDAnggota."] ".$data->NamaLengkap." </span> | <span id='col-status'> ".$migration." </span>| <span id='col-persen'>".$persen. '% </span> | '.$message_result;
        			    $progress = number_format($persen,2,",",".");;
        			    $progressor=$persen;
                        echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% Eksekusi :'.$j.' dari '.$jumlah_data.' (sisa : '.$sisa_data.') | Berhasil :'.$jumlah_berhasil.', Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                       // echo '<script ">var element = window.parent.document.getElementById("divProgress");var text = document.createTextNode("'.$message.'");element.appendChild(text);element.prepend(element)+ "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% jumlah data :'.$j.' dari '.$jumlah_data.' (sisa : '.$sisa_data.') | Berhasil :'.$jumlah_berhasil.', Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                        
                        $j++;
                    }
                    sleep(1);
        			$message = date("d/m/Y H:i:s").' : Complete migrasi data';
        			$progressor = 100;
        			echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                }else{
                    sleep(1);
                    $message="Tidak ada data yang perlu dimigrasi";
                    echo '<script ">var someDiv=window.parent.document.getElementById("divProgress");someDiv.innerHTML += "'.$message.'!" + "<br />";</script>';
                }
            } catch (Exception $e){
                $message = 'Error : ' .$e->getMessage();
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
            }/*
       }else{
            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$msg_error.'" + "<br />";</script>';
       }*/
    }
    public function update($jenis="") {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
       
      
       // if(trim($msg_error)==""){
            try{
        		set_time_limit(0); 
        		ob_implicit_flush(true);
        		ob_end_flush();
                $list_qry=$db->select("NAMA,KODE_ANGGOTA,KELOMPOK,NIK,NO_KK,AGAMA,TEMPAT_LAHIR,TANGGAL_LAHIR,JENIS_KELAMIN,
                ALAMAT,RT,RW,DESA,KECAMATAN,TANGGAL_MASUK,NO_TELP,NO_HP,LOKASI,PHOTO,STATUS","kpbs_db.biodata_anggota")->where("ifnull(migrasi,0)=0")->lim();
                $hasil_sync=array();
                $j  = 0;
                $jumlah_data            =$db->numRow($list_qry);
                $jumlah_gagal           =0;
                $jumlah_berhasil        =0;
                $jumlah_update          =0;
                if($jumlah_data>0){
                     while($data = $db->fetchObject($list_qry)){
                    	$TglSkrg		=date("Y-m-d H:i:s");
                        $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
                        $message="";
                        $check_anggota=$db->select("ID_ANGGOTA,C_ANGGOTA,C_ANGGOTA_LAMA,NAMA,ID_KELOMPOK,ID_KELOMPOK_HARGA,
                        TGL_MASUK,ALAMAT1,ALAMAT2,NO_TELP,NO_HP,LOKASI,TGL_LAHIR,JENIS_KELAMIN,NIK,NoKK,tempat_lahir,
                        agama,alamat_kabupaten,alamat_kecamatan,alamat_desa,alamat_rt,alamat_rw,alamat","kpbs_db.anggota")
                        ->where("C_ANGGOTA='".$data->KODE_ANGGOTA."'")->get(0);
                        $migrasi=false;
                        if(!empty($check_anggota)){
                            //echo $j.". Anggota : ".$data->KODE_ANGGOTA."-".$data->NAMA." | ".$check_anggota->C_ANGGOTA."-".$check_anggota->NAMA."<br />";
                           $migrasi=true;
                           $cols_and_vals="";
                            $ref_tanggal_masuk=str_ireplace("-","",$data->TANGGAL_MASUK);
                            $tar_tanggal_masuk=$check_anggota->TGL_MASUK;
                            //echo $j.". Tgl Masuk : ".$data->TANGGAL_MASUK." | ".$check_anggota->TGL_MASUK."<br />";
                            if(trim($tar_tanggal_masuk)=="" and (trim($ref_tanggal_masuk)<>"" and trim($ref_tanggal_masuk)<>'00000000')){
                                $tgl_masuk_val           =$master->scurevaluetable($data->TANGGAL_MASUK);
                                $cols_and_vals=trim($cols_and_vals)==""?"TGL_MASUK=$tgl_masuk_val":$cols_and_vals.",TGL_MASUK=$tgl_masuk_val";
                            }
                            $ref_tl=str_ireplace("-","",$data->TEMPAT_LAHIR);
                            $tar_tl=str_ireplace("-","",$check_anggota->tempat_lahir);
                           // echo $j.". Tempat Lahir : ".$data->TEMPAT_LAHIR." | ".$check_anggota->tempat_lahir."<br />";
                            if(trim($tar_tl)=="" and trim($ref_tl)<>""){
                                $tempat_lahir_val           =$master->scurevaluetable($data->TEMPAT_LAHIR);
                                $cols_and_vals=trim($cols_and_vals)==""?"tempat_lahir=$tempat_lahir_val":$cols_and_vals.",tempat_lahir=$tempat_lahir_val";
                            }
                            
                            $ref_tanggal_lahir=str_ireplace("-","",$data->TANGGAL_LAHIR);
                           // echo $j.". Tgl Lahir : ".$data->TANGGAL_LAHIR." | ".$check_anggota->TGL_LAHIR."<br />";
                            if(trim($check_anggota->TGL_LAHIR)=="" and (trim($ref_tanggal_lahir)<>"" and trim($ref_tanggal_lahir)<>'00000000')){
                                $tgl_masuk_val           =$master->scurevaluetable($data->TANGGAL_LAHIR);
                                $cols_and_vals=trim($cols_and_vals)==""?"TGL_MASUK=$tgl_masuk_val":$cols_and_vals.",TGL_MASUK=$tgl_masuk_val";
                            }
                            $ref_nik=str_ireplace("-","",$data->NIK);
                            $tar_nik=str_ireplace("-","",$check_anggota->NIK);
                            //echo $j.". NIK : ".$data->NIK." | ".$check_anggota->NIK."<br />";
                            if(trim($tar_nik)=="" and trim($ref_nik)<>""){
                                $nik_val           =$master->scurevaluetable($data->NIK);
                                $cols_and_vals=trim($cols_and_vals)==""?"NIK=$nik_val":$cols_and_vals.",NIK=$nik_val";
                            }
                            $ref_kk=str_ireplace("-","",$data->NO_KK);
                            $tar_kk=str_ireplace("-","",$check_anggota->NoKK);
                           // echo $j.". KK : ".$data->NO_KK." | ".$check_anggota->NoKK."<br />";
                            if(trim($tar_kk)=="" and trim($ref_kk)<>""){
                                $nokk_val           =$master->scurevaluetable($data->NO_KK);
                                $cols_and_vals=trim($cols_and_vals)==""?"NoKK=$nokk_val":$cols_and_vals.",NoKK=$nokk_val";
                            }
                            $ref_sex=str_ireplace("-","",$data->JENIS_KELAMIN);
                            $tar_sex=trim(str_ireplace("-","",$check_anggota->JENIS_KELAMIN));
                            //echo $j.". Sex : ".$ref_sex." | ".$tar_sex."<br />";
                            if(trim($tar_sex)=="" and trim($ref_sex)<>""){
                              //  echo "masuk ".$j;
                                $sex_val           =$master->scurevaluetable($data->JENIS_KELAMIN);
                                $cols_and_vals=trim($cols_and_vals)==""?"JENIS_KELAMIN=$sex_val":$cols_and_vals.",JENIS_KELAMIN=$sex_val";
                            }
                            $ref_rt=str_ireplace("-","",$data->RT);
                            $tar_rt=str_ireplace("-","",$check_anggota->alamat_rt);
                            //echo $j.".RT : ".$data->RT." | ".$check_anggota->alamat_rt."<br />";
                            if(trim($tar_rt)=="" and trim($ref_rt)<>""){
                                $rt_gen ="00".trim($data->RT);
                                $rt     =substr(strlen($rt_gen)-3,3) ;
                                $rt_val           =$master->scurevaluetable($rt);
                                $cols_and_vals=trim($cols_and_vals)==""?"alamat_rt=$rt_val":$cols_and_vals.",alamat_rt=$rt_val";
                            }
                            $ref_rw=str_ireplace("-","",$data->RW);
                            $tar_rw=str_ireplace("-","",$check_anggota->alamat_rw);
                            //echo $j.". RW : ".$data->RW." | ".$check_anggota->alamat_rw."<br />";
                            if(trim($tar_rw)=="" and trim($ref_rw)<>""){
                                $rw_gen ="00".trim($data->RW);
                                $rw     =substr(strlen($rw_gen)-3,3) ;
                                $rw_val           =$master->scurevaluetable($rw);
                                $cols_and_vals=trim($cols_and_vals)==""?"alamat_rw=$rw_val":$cols_and_vals.",alamat_rw=$rw_val";
                            }
                            $ref_alamat=str_ireplace("-","",$data->ALAMAT);
                            $tar_alamat=str_ireplace("-","",$check_anggota->alamat);
                            //echo $j.". Alamat : ".$data->ALAMAT." | ".$check_anggota->alamat."<br />";
                            if(trim($tar_alamat)=="" and trim($ref_alamat)<>""){
                                $alamat_val           =$master->scurevaluetable($data->ALAMAT);
                                $cols_and_vals=trim($cols_and_vals)==""?"alamat=$alamat_val":$cols_and_vals.",alamat=$alamat_val";
                            }
                            $tar_alamat1=str_ireplace("-","",$check_anggota->ALAMAT1);
                            //echo $j.". Alamat1 : ".$data->ALAMAT." | ".$check_anggota->ALAMAT1."<br />";
                            if(trim($tar_alamat1)=="" and trim($ref_alamat)<>""){
                                $alamat_val           =$master->scurevaluetable($data->ALAMAT);
                                $cols_and_vals=trim($cols_and_vals)==""?"ALAMAT1=$alamat_val":$cols_and_vals.",ALAMAT1=$alamat_val";
                            }
                            $ref_telp=str_ireplace("-","",$data->NO_TELP);
                            $tar_telp=str_ireplace("-","",$check_anggota->NO_TELP);
                           // echo $j.". Telp : ".$data->NO_TELP." | ".$check_anggota->NO_TELP."<br />";
                            if(trim($tar_telp)=="" and trim($ref_telp)<>""){
                                $telp_val           =$master->scurevaluetable($data->NO_TELP);
                                $cols_and_vals=trim($cols_and_vals)==""?"NO_TELP=$telp_val":$cols_and_vals.",NO_TELP=$telp_val";
                            }
                            $ref_hp=str_ireplace("-","",$data->NO_HP);
                            $tar_hp=str_ireplace("-","",$check_anggota->NO_HP);
                           // echo $j.". HP : ".$data->NO_HP." | ".$check_anggota->NO_HP."<br />";
                            if(trim($tar_hp)=="" and trim($ref_hp)<>""){
                                $hp_val           =$master->scurevaluetable($data->NO_HP);
                                $cols_and_vals=trim($cols_and_vals)==""?"NO_HP=$hp_val":$cols_and_vals.",NO_HP=$hp_val";
                            }
                            $ref_lokasi=str_ireplace("-","",$data->LOKASI);
                            $tar_lokasi=str_ireplace("-","",$check_anggota->LOKASI);
                            //echo $j.". Lokasi : ".$data->LOKASI." | ".$check_anggota->LOKASI."<br />";
                            if(trim($tar_lokasi)=="" and trim($ref_lokasi)<>""){
                                $lokasi_val           =$master->scurevaluetable($data->LOKASI);
                                $cols_and_vals=trim($cols_and_vals)==""?"LOKASI=$lokasi_val":$cols_and_vals.",LOKASI=$lokasi_val";
                            }
                            $ref_kec=str_ireplace("-","",$data->KECAMATAN);
                            $tar_kec=str_ireplace("-","",$check_anggota->alamat_kecamatan);
                            //echo $j.". Kec. : ".$data->KECAMATAN." | ".$check_anggota->alamat_kecamatan."<br />";
                            if(trim($tar_kec)=="" and trim($ref_kec)<>""){
                                $kec_val           =$master->scurevaluetable($data->KECAMATAN);
                                $cols_and_vals=trim($cols_and_vals)==""?"alamat_kecamatan=$kec_val":$cols_and_vals.",alamat_kecamatan=$kec_val";
                            }
                            $ref_desa=str_ireplace("-","",$data->DESA);
                            $tar_desa=str_ireplace("-","",$check_anggota->alamat_desa);
                           // echo $j.". Desa : ".$data->DESA." | ".$check_anggota->alamat_desa."<br />";
                            if(trim($tar_desa)=="" and trim($ref_desa)<>""){
                                $desa_val           =$master->scurevaluetable($data->DESA);
                                $cols_and_vals=trim($cols_and_vals)==""?"alamat_desa=$desa_val":$cols_and_vals.",alamat_desa=$desa_val";
                            }
                            $ref_agama=str_ireplace("-","",$data->AGAMA);
                            $tar_agama=str_ireplace("-","",$check_anggota->agama);
                            //echo $j.". Agama : ".$data->AGAMA." | ".$check_anggota->agama."<br />";
                            if(trim($tar_agama)=="" and trim($ref_agama)<>""){
                                //$agama=$db->select("agamaKode,agamaNama","tbragama")->where("LOWER(agamaNama)='".strtolower($data->AGAMA)."'")->get(0);
                                //$bapak_id_val     =$master->scurevaluetable($jantan->id,"number");
                                $agama_val           =$master->scurevaluetable($data->AGAMA);
                                $cols_and_vals=trim($cols_and_vals)==""?"agama=$agama_val":$cols_and_vals.",agama=$agama_val";
                            }
                            //echo $j.". cols and vals :".$cols_and_vals."<br />";
            				if(trim($cols_and_vals)<>""){
                                $cols_and_vals=$cols_and_vals.",updated_time=$tgl_skrg_val";
                				$sqlin="UPDATE kpbs_db.anggota set $cols_and_vals WHERE C_ANGGOTA='".$data->KODE_ANGGOTA."';";
                                $rsl=$db->query($sqlin);
                				if(isset($rsl->error) and $rsl->error===true){
                					$migrasi=false;
                			        $message_result="Error insert, ".$rsl->query_last_message;
                                    $jumlah_gagal++;
                				}else{	
                				    $jumlah_berhasil++;
                                    $jumlah_update++;
                                    $message_result="Berhasil update anggota";
                	            }
                             }else{
                                $jumlah_berhasil++;
             			        $message_result="Tidak ada field yang diupdate";
                             }
                             if($migrasi==true){
                                 $sqlup="UPDATE kpbs_db.biodata_anggota set migrasi=1 WHERE KODE_ANGGOTA='".$data->KODE_ANGGOTA."';";
                                 $db->query($sqlup);
                             }
                        }else{
                            $jumlah_gagal++;
                            $message_result="Data anggota tidak ditemukan";
                            
                        }
                        //echo $j." ". $message_result." <br />";
                        sleep(1);
                        $persen=round(($j/$jumlah_data)*100,2);
        				$sisa_data=$jumlah_data-$j;
        				$migration=$migrasi?"Berhasil":"Gagal";
        			    $message = date("d/m/Y H:i:s")."  <span id='col-members'> [".$data->KODE_ANGGOTA."] ".$data->NAMA." </span> | <span id='col-status'> ".$migration." </span>| <span id='col-persen'>".$persen. '% </span> | '.$message_result;
        			    $progress = number_format($persen,2,",",".");;
        			    $progressor=$persen;
                        echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% Eksekusi :'.$j.' dari '.$jumlah_data.' (sisa : '.$sisa_data.') | Berhasil : '.$jumlah_berhasil.', Update : '.$jumlah_update.', Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                       // echo '<script ">var element = window.parent.document.getElementById("divProgress");var text = document.createTextNode("'.$message.'");element.appendChild(text);element.prepend(element)+ "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% jumlah data :'.$j.' dari '.$jumlah_data.' (sisa : '.$sisa_data.') | Berhasil :'.$jumlah_berhasil.', Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                        
                        $j++;
                    }
                    sleep(1);
        			$message = date("d/m/Y H:i:s").' : Complete migrasi data';
        			$progressor = 100;
        			echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                }else{
                    sleep(1);
                    $message="Tidak ada data yang perlu dimigrasi";
                    echo '<script ">var someDiv=window.parent.document.getElementById("divProgress");someDiv.innerHTML += "'.$message.'!" + "<br />";</script>';
                }
            } catch (Exception $e){
                $message = 'Error : ' .$e->getMessage();
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
            }/*
       }else{
            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$msg_error.'" + "<br />";</script>';
       }*/
    }
    
    public function update_cow() {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        $referensi  = $master->referensi_session();
      
       // if(trim($msg_error)==""){
            try{
        		set_time_limit(0); 
        		ob_implicit_flush(true);
        		ob_end_flush();
                  
                $list_qry=$db->select("po.id,IDAnggota,NamaLengkap,NamaKelompokPeternak,PosPenampung,IDSapi,Tipe_Tipe,
                MaxOfLaktasiKe,TglIdentifikasi,Telinga,IDTipe,c.id cow_id,c.name, a.C_ANGGOTA"," populasiperorang po
                left  join cow c on c.name=po.IDSapi
                left join anggota a on a.C_ANGGOTA=po.IDAnggota")->where("ifnull(po.migrasi,0)=0")->lim();
                
                $hasil_sync=array();
                $j  = 0;
                $jumlah_data    =$db->numRow($list_qry);
                $jumlah_gagal   =0;
                $jumlah_berhasil   =0;
                if($jumlah_data>0){
                     while($data = $db->fetchObject($list_qry)){
                        $message_result="";  
                        $message="";
                        $eartag=trim(text::filter($data->IDSapi,"lcase ucase num space . -"));
                        $posisi_eartag_val        =$master->scurevaluetable($data->Telinga);
                    	$TglSkrg		=date("Y-m-d H:i:s");
                        $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
                      
                        $tipe_id_val          =$master->scurevaluetable($data->IDTipe,"number");
                        $type_name_val        =$master->scurevaluetable($data->Tipe_Tipe);
                        $sex=1;
                        $jantan=array(2,6,7);
                        if(in_array($tipe_id,$jantan)){
                            $sex=2;
                        }
                        $sex_val            =$master->scurevaluetable($sex,"number");
                        $tanggal_identifikasi_val		=$master->scurevaluetable($data->TglIdentifikasi,"string");
                        $laktasi_ke_val     =$master->scurevaluetable($data->MaxOfLaktasiKe,"number");
                        
                        $eartag_val           =$master->scurevaluetable($eartag);
                        // $posisi_eartag_val     =$master->scurevaluetable($data->Telinga);
                        $metode_val		=$master->scurevaluetable($data->Metoda,"number",false);
                        $member=$db->select("ID_ANGGOTA,C_ANGGOTA","anggota")->where("C_ANGGOTA='".$data->IDAnggota."'")->get(0);
                        //print_r($member);
                        $migrasi=true;
                        if(!empty($member)){
                            $anggota_id_val     =$master->scurevaluetable($member->ID_ANGGOTA,"number"); 
                            if(trim($data->name)==""){//insert
                                
                                $cols="name,barcode,anggota_id,is_active,type,tipe,gender,tanggal_identifikasi,
                                posisi_eartag,laktasi_ke,is_need_verification,verification_date,created_by,created_time";
                               
                				$values="$eartag_val,$eartag_val,$anggota_id_val,1,$type_name_val,$tipe_id_val,$sex_val,$tanggal_identifikasi_val,
                                $posisi_eartag_val,$laktasi_ke_val,0,now(),null,now()";
                				$sqlin="INSERT INTO cow ($cols) VALUES ($values);";
                               
                                $rsl=$db->query($sqlin);
                				if(isset($rsl->error) and $rsl->error===true){
                				    $jumlah_gagal++; 
                                    $migrasi=false;
                			        $message_result="Error insert, ".$rsl->query_last_message.' '.$data->IDSapi;
                				}else{	
                				    $jumlah_berhasil++;
                                   
                                    $message_result="Berhasil insert sapi";
                                    $sqlup="UPDATE populasiperorang set migrasi=1 WHERE id=".$data->id.";";
                                    $rslup=$db->query($sqlup);
                                    if(isset($rslup->error) and $rslup->error===true){
                                        $message_result="Berhasil insert sapi. Gagal update populasiperorang ".$rslup->query_last_message;
                                    }
                                    
                	            }
                                echo $j.". ".$data->IDSapi." -  $message_result | Insert : $eartag ".$sqlin." <br />";
                            }else{
                                $cols_and_vals="anggota_id=$anggota_id_val,is_active=1,type=$type_name_val,tipe=$tipe_id_val,gender=$sex_val,
                                posisi_eartag=$posisi_eartag_val,tanggal_identifikasi=$tanggal_identifikasi_val,is_need_verification=0,verification_date=now(),last_update=now()";
                              
                				$sqlin="UPDATE cow SET $cols_and_vals WHERE id=".$data->cow_id.";";
                               // echo $j.". Update : $eartag ".$sqlin." <br />";
                                $rsl=$db->query($sqlin);
                				if(isset($rsl->error) and $rsl->error===true){
                				    $jumlah_gagal++;
                                    $migrasi=false;
                			        $message_result="Error update, ".$rsl->query_last_message;
                				}else{	
                				    $jumlah_berhasil++;
                                    $message_result="Berhasil update sapi";
                                    $sqlup="UPDATE populasiperorang set migrasi=1 WHERE id=".$data->id.";";
                                    $rslup=$db->query($sqlup);
                                    if(isset($rslup->error) and $rslup->error===true){
                                        $message_result="Berhasil insert sapi. Gagal update populasiperorang ".$rslup->query_last_message;
                                    }
                	            }
                                echo $j.". ".$data->IDSapi." - $message_result | Update : $eartag ".$sqlin." <br />";
                            }
                        }else{
                            $jumlah_gagal++;
                            $migrasi=false;
                            $message_result="Data anggota tidak ditemukan";
                            echo $j.". ".$data->IDSapi." -  No Member  <br />";
                        }
                       
                        sleep(1);
                        $persen=round(($j/$jumlah_data)*100,2);
        				$sisa_data=$jumlah_data-$j;
        				$migration=$migrasi?"Berhasil":"Gagal";
        			    $message = date("d/m/Y H:i:s")."  <span id='col-members'>".$data->IDSapi." - ".$data->IDAnggota."  </span> | <span id='col-status'> ".$migration." </span>| <span id='col-persen'>".$persen. '% </span> | '.$message_result;
        			    $progress = number_format($persen,2,",",".");;
        			    $progressor=$persen;
                        echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% Eksekusi :'.$j.' dari '.$jumlah_data.' (sisa : '.$sisa_data.') | Berhasil :'.$jumlah_berhasil.', Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                       
                        $j++;
                    }
                    sleep(1);
        			$message = date("d/m/Y H:i:s").' : Complete update data';
        			$progressor = 100;
        			echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                   
                }else{
                    sleep(1);
                    $message="Tidak ada data yang perlu diupdate";
                    echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'!" + "<br />";</script>';
                }
            } catch (Exception $e){
                $message = 'Error : ' .$e->getMessage();
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
            }/*
       }else{
            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$msg_error.'" + "<br />";</script>';
       }*/
    }
    public function sync_cow($limit="") {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
       
      
       // if(trim($msg_error)==""){
            try{
                if(trim($limit)==""){
            		set_time_limit(0); 
            		ob_implicit_flush(true);
            		ob_end_flush();
                    $limit=null;
                   /* $list_qry=$db->select("IDsapi,Telinga,NamaSapi,HasilET,Kembar,case when Metoda=0 then null else Metoda end Metoda,TglLahir , DATE_FORMAT(TglLahir,'%Y-%m-%d') TanggalLahir,IDsapiIbu,IDsapiBapak,
                    IDAnggota,IDPemilikAwal,IDSapiAwal,IDKomp,TglIdentifikasi,DATE_FORMAT(TglIdentifikasi,'%Y-%m-%d') TanggalIdentifikasi,
                    migrasi","db_keswan.identifikasi")->where("ifnull(migrasi,0)=0")->lim();*/
                }else{
                    /*$list_qry=$db->select("IDsapi,Telinga,NamaSapi,HasilET,Kembar,case when Metoda=0 then null else Metoda end Metoda,TglLahir , DATE_FORMAT(TglLahir,'%Y-%m-%d') TanggalLahir,IDsapiIbu,IDsapiBapak,
                    IDAnggota,IDPemilikAwal,IDSapiAwal,IDKomp,TglIdentifikasi,DATE_FORMAT(TglIdentifikasi,'%Y-%m-%d') TanggalIdentifikasi,
                    migrasi","db_keswan.identifikasi")->where("ifnull(migrasi,0)=0")->lim(0,(int)$limit);*/
                }
                
                $list_qry=$db->select("IDsapi,Telinga,NamaSapi,HasilET,Kembar,case when Metoda=0 then null else Metoda end Metoda,TglLahir , DATE_FORMAT(TglLahir,'%Y-%m-%d') TanggalLahir,IDsapiIbu,IDsapiBapak,
                IDAnggota,IDPemilikAwal,IDSapiAwal,IDKomp,TglIdentifikasi,DATE_FORMAT(TglIdentifikasi,'%Y-%m-%d') TanggalIdentifikasi,
                migrasi","db_keswan.identifikasi")->where("ifnull(migrasi,0)=0")->lim();
                
                $hasil_sync=array();
                $j  = 0;
                $jumlah_data    =$db->numRow($list_qry);
                $jumlah_gagal   =0;
                $jumlah_berhasil   =0;
                if($jumlah_data>0){
                     while($data = $db->fetchObject($list_qry)){
                        $eartag=trim(text::filter($data->IDsapi,"lcase ucase num space . -"));
                    	$TglSkrg		=date("Y-m-d H:i:s");
                        $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
                        $message="";
                        $check_sapi=$db->select("id,name,koloni_name,tanggal_identifikasi","cow")->where("name='".$eartag."'")->get();
                        $migrasi=false;
                        if(empty($check_sapi)){
                           $migrasi=true;
                           //insert sapi
                           $eartag_val           =$master->scurevaluetable($eartag);
        	               $posisi_eartag_val     =$master->scurevaluetable($data->Telinga);
                           $metode_val		=$master->scurevaluetable($data->Metoda,"number",false);
                           $member=$db->select("ID_ANGGOTA,C_ANGGOTA","anggota")->where("C_ANGGOTA='".$data->IDAnggota."'")->get(0);
                           $anggota_id_val     =$master->scurevaluetable($member->ID_ANGGOTA,"number",false);                           
                           $tgl_identifikasi_val     =$master->scurevaluetable($data->TanggalIdentifikasi);
                           $no_jantan=trim(text::filter($data->IDsapiBapak,"lcase ucase num space . -"));
                           $jantan=$db->select("id,no_pejantan","keswan_pejantan")->where("no_pejantan='".$no_jantan."'")->get(0);
                           $bapak_id_val     =$master->scurevaluetable($jantan->id,"number");
                          
                           $eartag_induk=trim(text::filter($data->IDsapiIbu,"lcase ucase num space . -"));
                           $induk=$db->select("id,name","cow")->where("name='".$eartag_induk."'")->get(0);
                           $induk_val     =$master->scurevaluetable($induk->id,"number");
                           
                           $eartag_asal=$eartag=trim(text::filter($data->IDSapiAwal,"lcase ucase num space"));
                           $eartag_asal_val     =$master->scurevaluetable($eartag_asal);
                           $TglLahir_val     =$master->scurevaluetable($data->TanggalLahir);
                           $koloni_name_val     =$master->scurevaluetable($data->NamaSapi);
                           $cols="name,koloni_name,birthdate,barcode,metode_perolehan,anggota_id,posisi_eartag,
                           tanggal_identifikasi,induk,bapak,no_eartag_asal,gender,created_by,created_time,is_active=null";
                           
            				$values="$eartag_val,$koloni_name_val,$TglLahir_val,$eartag_val,$metode_val,$anggota_id_val,$posisi_eartag_val,
                            $tgl_identifikasi_val,$induk_val,$bapak_id_val,$eartag_asal_val,1,null,now()";
            				$sqlin="INSERT INTO cow ($cols) VALUES ($values);";
                            $rsl=$db->query($sqlin);
            				if(isset($rsl->error) and $rsl->error===true){
            				    $jumlah_gagal++;
            					$migrasi=false;
            			        $message_result="Error insert, ".$rsl->query_last_message;
            				}else{	
            				    $jumlah_berhasil++;
                                $message_result="Berhasil insert sapi";
                                $sqlup="UPDATE db_keswan.identifikasi set migrasi=1 WHERE IDsapi='".str_replace("'","\'",$data->IDsapi)."';";
                                $rslup=$db->query($sqlup);
                                if(isset($rslup->error) and $rslup->error===true){
                                    $message_result="Berhasil insert sapi. Gagal update identifikasi ".$rslup->query_last_message;
                                }
                                
            	            }
                        }else{
                            
                            $message_result="Data sapi sudah ada di ERP";
                            if(trim($check_sapi->tanggal_identifikasi)=="" and trim($data->TanggalIdentifikasi)<>""){
                                $tgl_iden_val     =$master->scurevaluetable($data->TanggalIdentifikasi);
                                $col_and_vals="tanggal_identifikasi=$tgl_iden_val";
                                if(trim($data->TanggalLahir)<>""){
                                    $TglLahir_val     =$master->scurevaluetable($data->TanggalLahir);
                                    $col_and_vals=$col_and_vals.",birthdate=$TglLahir_val";
                                }
                                $sqlupa="UPDATE cow set $col_and_vals WHERE name='".$eartag."';";
                                $hsl2=$db->query($sqlupa);
                                if(isset($hsl2->error) and $hsl2->error===true){
                                    $jumlah_gagal++;
                                    $migrasi=false;
                                    $message_result="Error update, ".$rsl->query_last_message;
                                }else{	
                                    $jumlah_berhasil++;
                                    $migrasi=true;
                                    $message_result="Update tanggal identifikasi di ERP";
                                     $sqlup="UPDATE db_keswan.identifikasi set migrasi=1 WHERE IDsapi='".str_replace("'","\'",$data->IDsapi)."';";
                                    $db->query($sqlup);
                	            }
                            }else{
                                $migrasi=false;
                            }
                           
                        }
                        if(trim($limit)==""){
                            sleep(1);
                            $persen=round(($j/$jumlah_data)*100,2);
            				$sisa_data=$jumlah_data-$j;
            				$migration=$migrasi?"Berhasil":"Gagal";
            			    $message = date("d/m/Y H:i:s")."  <span id='col-members'>".$data->IDsapi." - ".$data->IDAnggota."  </span> | <span id='col-status'> ".$migration." </span>| <span id='col-persen'>".$persen. '% </span> | '.$message_result;
            			    $progress = number_format($persen,2,",",".");;
            			    $progressor=$persen;
                            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% Eksekusi :'.$j.' dari '.$jumlah_data.' (sisa : '.$sisa_data.') | Berhasil :'.$jumlah_berhasil.', Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                        }
                        $j++;
                    }
                    if(trim($limit)==""){
                        sleep(1);
            			$message = date("d/m/Y H:i:s").' : Complete migrasi data';
            			$progressor = 100;
            			echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                    }
                }else{
                    sleep(1);
                    $message="Tidak ada data yang perlu dimigrasi";
                    echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'!" + "<br />";</script>';
                }
            } catch (Exception $e){
                $message = 'Error : ' .$e->getMessage();
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
            }/*
       }else{
            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$msg_error.'" + "<br />";</script>';
       }*/
    }
    public function sync_event($jenis="event") {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        $referensi      = $master->referensi_session();
        switch($jenis){
            case "event":
       // if(trim($msg_error)==""){
                try{
            		set_time_limit(0); 
            		ob_implicit_flush(true);
            		ob_end_flush();
                    $list_qry=$db->select("IDEvent,IDSapi,TglKejadian,JamKejadian,DATE_FORMAT(TglKejadian,'%Y-%m-%d') TanggalKejadian,
                    IDJenisKejadian,IDTipe,IDSatusReproduksi,
                    IDStatusLatasi,LaktasiKe,WaktuDataEntry,IDpetugas","db_keswan.event")->where("ifnull(migrasi,0)=0")->orderBy("IDsapi desc, IDJenisKejadian asc")->lim();
                    $hasil_sync=array();
                    $j  = 0;
                    $jumlah_data    =$db->numRow($list_qry);
                    $jumlah_gagal   =0;
                    $jumlah_berhasil   =0;
                    if($jumlah_data>0){
                         while($data = $db->fetchObject($list_qry)){
                            $pelayanan_id=$data->IDEvent;
                            //$eartag=trim(str_replace("'","",$data->IDSapi));
                            $eartag=trim(text::filter($data->IDSapi,"lcase ucase num space . -"));
                        	$TglSkrg		=date("Y-m-d H:i:s");
                            $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
                            $message="";
                            $check_event=$db->select("id,cow_id,tanggal_pelayanan,jenis_pelayanan,tipe_sapi,status_reproduksi,
                            status_laktasi,laktasi_ke,petugas","keswan_pelayanan_sapi")->where("id=".$pelayanan_id."")->get(0);
                            $migrasi=false;
                            if(empty($check_event)){
                               $migrasi=true;
                               //insert sapi
                               $sapi=$db->select("id,name","cow")->where("name='".$eartag."'")->get(0);
                               if(!empty($sapi)){
                                   $cow_id_val     =$master->scurevaluetable($sapi->id,"number");
                                   $tgl_pelayanan_val     =$master->scurevaluetable($data->TglKejadian);
                                   $jenis_pelayanan_val           =$master->scurevaluetable($data->IDJenisKejadian,"number");
                                   $tipe_sapi_val           =$master->scurevaluetable($data->IDTipe,"number");
                                   $status_repro_val           =$master->scurevaluetable($data->IDSatusReproduksi,"number");
                                   $status_laktasi_val           =$master->scurevaluetable($data->IDStatusLatasi,"number");
                                   $laktasi_ke_val           =$master->scurevaluetable($data->LaktasiKe,"number");
                                   $tgl_entry           =$master->scurevaluetable($data->WaktuDataEntry);
                                   $petugas_id_val           =$master->scurevaluetable($data->IDpetugas,"number");
                                   
                                  
                                   $cols="id,cow_id,tanggal_pelayanan,jenis_pelayanan,tipe_sapi,
                                   status_reproduksi,status_laktasi,laktasi_ke,petugas,created,lastupdate";
                                   
                    				$values="$pelayanan_id,$cow_id_val,$tgl_pelayanan_val,$jenis_pelayanan_val,$tipe_sapi_val,
                                    $status_repro_val,$status_laktasi_val,$laktasi_ke_val,$petugas_id_val,$tgl_entry,now()";
                    				$sqlin="INSERT INTO keswan_pelayanan_sapi ($cols) VALUES ($values);";
                                    $rsl=$db->query($sqlin);
                    				if(isset($rsl->error) and $rsl->error===true){
                    				    $jumlah_gagal++;
                    					$migrasi=false;
                    			        $message_result="Error insert, ".$rsl->query_last_message;
                    				}else{	
                    				    $jumlah_berhasil++;
                                        $message_result="Berhasil insert pelayanan sapi $eartag";
                                        $sqlup="UPDATE db_keswan.event set migrasi=1 WHERE IDEvent=$pelayanan_id;";
                                        $rslup=$db->query($sqlup);
                                        if(isset($rslup->error) and $rslup->error===true){
                                            $message_result="Berhasil insert pelayanan. Gagal update pelayanan status migrasi ".$rslup->query_last_message;
                                        }
                    	            }
                                 }else{
                                    $migrasi=false;
                                    $jumlah_gagal++;
                                    $message_result="Gagal migrasi, data sapi $eartag tidak ditemukan";
                                 }
                            }else{
                                $jumlah_gagal++;
                                $migrasi=false;
                                $message_result="Data pelayanan sudah ada di ERP";
                               
                            }
                            sleep(1);
                            $persen=round(($j/$jumlah_data)*100,2);
            				$sisa_data=$jumlah_data-$j;
            				$migration=$migrasi?"Berhasil":"Gagal";
            			    $message = date("d/m/Y H:i:s")." <span id='col-id'>".$data->IDEvent."</span> | <span id='col-sapi'>".$data->IDSapi."  </span> | <span id='col-jenis'>".$data->IDJenisKejadian." </span> | <span id='col-tanggal'>".$data->TanggalKejadian." </span> | <span id='col-status'> ".$migration." </span>| <span id='col-persen'>".$persen. '% </span> | '.$message_result;
            			    $progress = number_format($persen,2,",",".");;
            			    $progressor=$persen;
                            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% Eksekusi :'.$j.' dari '.$jumlah_data.' (sisa : '.$sisa_data.') | Berhasil :'.$jumlah_berhasil.', Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                            $j++;
                            next($list_ada);
                        }
                        sleep(1);
            			$message = date("d/m/Y H:i:s").' : Complete migrasi data';
            			$progressor = 100;
            			echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                    }else{
                        sleep(1);
                        $message="Tidak ada data pelayanan yang perlu dimigrasi";
                        echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'!" + "<br />";</script>';
                    }
                } catch (Exception $e){
                    $message = 'Error : ' .$e->getMessage();
                    echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
                }/*
           }else{
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$msg_error.'" + "<br />";</script>';
           }*/
           break;
           case "perkawinan":
       // if(trim($msg_error)==""){
                try{
            		set_time_limit(0); 
            		ob_implicit_flush(true);
            		ob_end_flush();
                    $list_qry=$db->select("IDEvent,NoPejantan,NoBatch,LamaBirahi,IB_KE,PengamatBirahi,Dosis,
                    Biaya","db_keswan.ib")->where("ifnull(migrasi,0)=0")->orderBy("IDEvent desc")->lim();
                    $hasil_sync=array();
                    $j  = 0;
                    $jumlah_data    =$db->numRow($list_qry);
                    $jumlah_gagal   =0;
                    $jumlah_berhasil   =0;
                    if($jumlah_data>0){
                         while($data = $db->fetchObject($list_qry)){
                            $pelayanan_id=$data->IDEvent;
                            $eartag=trim(str_replace("'","",$data->IDSapi));
                        	$TglSkrg		=date("Y-m-d H:i:s");
                            $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
                            $message="";
                            $check_event=$db->select("id,pejantan,metode_perkawinan,no_batch,lama_birahi,
                            kawin_ke,dosis,biaya,last_action,breeding_status,pelayanan_id","keswan_perkawinan")
                            ->where("pelayanan_id=".$pelayanan_id."")->get(0);
                            $migrasi=false;
                            if(empty($check_event)){
                               $migrasi=true;
                               //insert sapi
                               $no_jantan=trim(text::filter($data->NoPejantan,"lcase ucase num space . -"));
                               $pejantan=$db->select("id,no_pejantan","keswan_pejantan")->where("no_pejantan='".$no_jantan."'")->get(0);
                               $pejantan_id_val     =$master->scurevaluetable($pejantan->id,"number");
                               $no_batch_val     =$master->scurevaluetable($data->NoBatch);
                               $lama_birahi_val           =$master->scurevaluetable($data->LamaBirahi,"number");
                               $ib_ke_val           =$master->scurevaluetable($data->IB_KE,"number");
                               $dosis_val     =$master->scurevaluetable($data->Dosis);
                                $biaya_val     =$master->scurevaluetable($data->Biaya,"number");
                              
                               $cols="pelayanan_id,pejantan,metode_perkawinan,no_batch,lama_birahi,kawin_ke,dosis,biaya";
                               
                				$values="$pelayanan_id,$pejantan_id_val,'IB',$no_batch_val,$lama_birahi_val,
                                $ib_ke_val,$dosis_val,$biaya_val";
                				$sqlin="INSERT INTO keswan_perkawinan ($cols) VALUES ($values);";
                                $rsl=$db->query($sqlin);
                				if(isset($rsl->error) and $rsl->error===true){
                				    $jumlah_gagal++;
                					$migrasi=false;
                			        $message_result="Error insert, ".$rsl->query_last_message;
                				}else{	
                				    $jumlah_berhasil++;
                                    $message_result="Berhasil insert IB";
                                    $sqlup="UPDATE db_keswan.ib set migrasi=1 WHERE IDEvent=$pelayanan_id;";
                                    $rslup=$db->query($sqlup);
                                    if(isset($rslup->error) and $rslup->error===true){
                                        $message_result="Berhasil insert IB. Gagal update IB status migrasi ".$rslup->query_last_message;
                                    }
                                    
                	            }
                            }else{
                                $jumlah_gagal++;
                                $migrasi=false;
                                $message_result="Data IB sudah ada di ERP";
                                
                               
                            }
                            sleep(1);
                            $persen=round(($j/$jumlah_data)*100,2);
            				$sisa_data=$jumlah_data-$j;
            				$migration=$migrasi?"Berhasil":"Gagal";
            			    $message = date("d/m/Y H:i:s")." <span id='col-id'>".$data->IDEvent."</span> | <span id='col-sapi'> Pejantan : ".$data->NoPejantan."  </span> | <span id='col-jenis'> IB </span> | <span id='col-status'> ".$migration." </span>| <span id='col-persen'>".$persen. '% </span> | '.$message_result;
            			    $progress = number_format($persen,2,",",".");;
            			    $progressor=$persen;
                            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% Eksekusi :'.$j.' dari '.$jumlah_data.' (sisa : '.$sisa_data.') | Berhasil :'.$jumlah_berhasil.', Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                            $j++;
                        }
                        sleep(1);
            			$message = date("d/m/Y H:i:s").' : Complete migrasi data';
            			$progressor = 100;
            			echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                    }else{
                        sleep(1);
                        $message="Tidak ada data pelayanan yang perlu dimigrasi";
                        echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'!" + "<br />";</script>';
                    }
                    
                } catch (Exception $e){
                    $message = 'Error : ' .$e->getMessage();
                    echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
                }/*
           }else{
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$msg_error.'" + "<br />";</script>';
           }*/
           break;
           case "pkb":
       // if(trim($msg_error)==""){
                try{
            		set_time_limit(0); 
            		ob_implicit_flush(true);
            		ob_end_flush();
                    
                    $list_qry=$db->select("IDEvent,TandaKebuntingan,Posisi,UmurKebuntingan,
                    'B' state","db_keswan.bunting")->where("ifnull(migrasi,0)=0")->lim();
                    
                    $list_qry_kosong =$db->select("IDEvent,Uterus,OvariKiri,OvariKanan,Cervix,
                    PerkiraanSiklus,'K' state ","db_keswan.kosong")->where("ifnull(migrasi,0)=0")->lim();
                    $hasil_sync=array();
                    $j  = 0;
                    $jumlah_data_b    =$db->numRow($list_qry);
                    $jumlah_data_k    =$db->numRow($list_qry_kosong);
                    $jumlah_data    =$jumlah_data_b+$jumlah_data_k;
                    $jumlah_gagal   =0;
                    $jumlah_berhasil   =0;
                    if($jumlah_data_b>0){
                         while($data = $db->fetchObject($list_qry)){
                            $pelayanan_id=$data->IDEvent;
                        	$TglSkrg		=date("Y-m-d H:i:s");
                            $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
                            $message="";
                            $check_event=$db->select("id,pelayanan_id,status_bunting","keswan_periksa_kebuntingan")
                            ->where("pelayanan_id=".$pelayanan_id."")->get(0);
                            $migrasi=false;
                            if(empty($check_event)){
                               $migrasi=true;
                               //insert sapi
                              
                               $status_bunting_val      =$master->scurevaluetable($data->state);
                               $tanda_bunting_val           =$master->scurevaluetable($data->TandaKebuntingan,"number");
                               $posisi_val           =$master->scurevaluetable($data->Posisi,"number");
                               $umur_val           =$master->scurevaluetable($data->UmurKebuntingan,"number");
                             
                               $cols="pelayanan_id,status_bunting,tanda_kebuntingan,posisi,umur";
                				$values="$pelayanan_id,$status_bunting_val,$tanda_bunting_val,$posisi_val,$umur_val";
                				$sqlin="INSERT INTO keswan_periksa_kebuntingan ($cols) VALUES ($values);";
                                $rsl=$db->query($sqlin);
                				if(isset($rsl->error) and $rsl->error===true){
                				    $jumlah_gagal++;
                					$migrasi=false;
                			        $message_result="Error insert, ".$rsl->query_last_message;
                				}else{	
                				    $jumlah_berhasil++;
                                    $message_result="Berhasil insert PKB (Bunting)";
                                    $sqlup="UPDATE db_keswan.bunting set migrasi=1 WHERE IDEvent=$pelayanan_id;";
                                    $rslup=$db->query($sqlup);
                                    if(isset($rslup->error) and $rslup->error===true){
                                        $message_result="Berhasil insert PKB (Bunting). Gagal update PKB status migrasi ".$rslup->query_last_message;
                                    }
                                    
                	            }
                            }else{
                                $jumlah_gagal++;
                                $migrasi=false;
                                $message_result="Data PKB (Bunting) sudah ada di ERP";
                                
                               
                            }
                            sleep(1);
                            $persen=round(($j/$jumlah_data)*100,2);
            				$sisa_data=$jumlah_data-$j;
            				$migration=$migrasi?"Berhasil":"Gagal";
            			    $message = date("d/m/Y H:i:s")." <span id='col-id'>".$data->IDEvent."</span> | <span id='col-sapi'> PKB Bunting  </span> | <span id='col-status'> ".$migration." </span>| <span id='col-persen'>".$persen. '% </span> | '.$message_result;
            			    $progress = number_format($persen,2,",",".");;
            			    $progressor=$persen;
                            echo '<script>window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% Eksekusi :'.$j.' dari '.$jumlah_data.' (sisa : '.$sisa_data.') | Berhasil :'.$jumlah_berhasil.', Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                            $j++;
                            next($list_ada);
                        }
                        sleep(1);
            			$message = date("d/m/Y H:i:s").' : Complete migrasi data PKB (Bunting)';
            			//$progressor = 100;
            			echo '<script>window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                    }else{
                        sleep(1);
                        $message="Tidak ada data event bunting yang perlu dimigrasi";
                        echo '<script>window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'!" + "<br />";</script>';
                    }
                    if($jumlah_data_k>0){
                         while($data = $db->fetchObject($list_qry_kosong)){
                            $pelayanan_id=$data->IDEvent;
                        	$TglSkrg		=date("Y-m-d H:i:s");
                            $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
                            $message="";
                            $check_event_k=$db->select("id,pelayanan_id,status_bunting","keswan_periksa_kebuntingan")
                            ->where("pelayanan_id=".$pelayanan_id."")->get(0);
                            $migrasi=false;
                            if(empty($check_event_k)){
                               $migrasi=true;
                               //insert sapi
                              
                               $status_bunting_val      =$master->scurevaluetable($data->state);
                               $uterus_val           =$master->scurevaluetable($data->Uterus,"number");
                               $ovari_kiri_val           =$master->scurevaluetable($data->OvariKiri,"number");
                               $ovari_kanan_val           =$master->scurevaluetable($data->OvariKanan,"number");
                               $temuan_cervix_val           =$master->scurevaluetable($data->Cervix,"number");
                               $siklus_val           =$master->scurevaluetable($data->PerkiraanSiklus,"number");
                             
                               $cols="pelayanan_id,status_bunting,temuan_uterus,temuan_ovari_kiri,temuan_ovari_kanan,temuan_cervix,perkiraan_siklus";
                				$values="$pelayanan_id,$status_bunting_val,$uterus_val,$ovari_kiri_val,$ovari_kanan_val,$temuan_cervix_val,$siklus_val";
                				$sqlin2="INSERT INTO keswan_periksa_kebuntingan ($cols) VALUES ($values);";
                                $rsl2=$db->query($sqlin2);
                				if(isset($rsl2->error) and $rsl2->error===true){
                				    $jumlah_gagal++;
                					$migrasi=false;
                			        $message_result="Error insert, ".$rsl2->query_last_message;
                				}else{	
                				    $jumlah_berhasil++;
                                    $message_result="Berhasil insert PKB (Kosong)";
                                    $sqlup2="UPDATE db_keswan.bunting set migrasi=1 WHERE IDEvent=$pelayanan_id;";
                                    $rslup2=$db->query($sqlup2);
                                    if(isset($rslup2->error) and $rslup2->error===true){
                                        $message_result="Berhasil insert PKB (Kosong). Gagal update PKB status migrasi ".$rslup2->query_last_message;
                                    }
                                    
                	            }
                            }else{
                                $jumlah_gagal++;
                                $migrasi=false;
                                $message_result="Data PKB (Kosong) sudah ada di ERP";
                                
                               
                            }
                            sleep(1);
                            $persen=round(($j/$jumlah_data)*100,2);
            				$sisa_data=$jumlah_data-$j;
            				$migration=$migrasi?"Berhasil":"Gagal";
            			    $message = date("d/m/Y H:i:s")." <span id='col-id'>".$data->IDEvent."</span> | <span id='col-sapi'> PKB Kosong  </span> | <span id='col-status'> ".$migration." </span>| <span id='col-persen'>".$persen. '% </span> | '.$message_result;
            			    $progress = number_format($persen,2,",",".");;
            			    $progressor=$persen;
                            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% Eksekusi :'.$j.' dari '.$jumlah_data.' (sisa : '.$sisa_data.') | Berhasil :'.$jumlah_berhasil.', Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                            $j++;
                            next($list_ada);
                        }
                        sleep(1);
            			$message = date("d/m/Y H:i:s").' : Complete migrasi data PKB (Kosong)';
            			//$progressor = 100;
            			echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                    }else{
                        sleep(1);
                        $message="Tidak ada data event kosong yang perlu dimigrasi";
                        echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'!" + "<br />";</script>';
                    }
                } catch (Exception $e){
                    $message = 'Error : ' .$e->getMessage();
                    echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
                }/*
           }else{
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$msg_error.'" + "<br />";</script>';
           }*/
           break;
           case "kelahiran":
       // if(trim($msg_error)==""){
                try{
            		set_time_limit(0); 
            		ob_implicit_flush(true);
            		ob_end_flush();
                    $list_qry=$db->select("IDEvent,JmlLahir,JmlDipelihara,JmlMati,JmlDijual,IDKelamin,NoPedet,
                    HargaBilaDijual,Berat,IDKeadaanMelahirkan","db_keswan.kelahiran")->where("ifnull(migrasi,0)=0")->lim();
                    $hasil_sync=array();
                    $j  = 0;
                    $jumlah_data    =$db->numRow($list_qry);
                    $jumlah_gagal   =0;
                    $jumlah_berhasil   =0;
                    if($jumlah_data>0){
                         while($data = $db->fetchObject($list_qry)){
                            $pelayanan_id=$data->IDEvent;
                        	$TglSkrg		=date("Y-m-d H:i:s");
                            $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
                            $message="";
                            $check_event=$db->select("id,pelayanan_id","keswan_kelahiran")
                            ->where("pelayanan_id=".$pelayanan_id."")->get(0);
                            $migrasi=false;
                            if(empty($check_event)){
                               $migrasi=true;
                               //insert sapi
                             // mlLahir,JmlDipelihara,JmlMati,JmlDijual,IDKelamin,NoPedet,
                   // HargaBilaDijual,Berat,IDKeadaanMelahirkan
                               $jml_bayi_val     =$master->scurevaluetable($data->JmlLahir,"number");
                               $jml_dipelihara_val     =$master->scurevaluetable($data->JmlDipelihara,"number");
                               $jml_mati_val     =$master->scurevaluetable($data->JmlMati,"number");
                               $jml_dijual_val           =$master->scurevaluetable($data->JmlDijual,"number");
                               $hrg_dijual_val           =$master->scurevaluetable($data->HargaBilaDijual,"number");
                               $keadaan_melahirkan_val     =$master->scurevaluetable($data->IDKeadaanMelahirkan,"number");
                               $berat_val     =$master->scurevaluetable($data->Berat,"number");
                               $sex_val     =$master->scurevaluetable($data->IDKelamin,"number");
                               $cols="pelayanan_id,jumlah_bayi,jumlah_dipelihara,jumlah_mati,
                                jumlah_dijual,harga_dijual,keadaan_melahirkan,jenis_kelamin";
                               
                				$values="$pelayanan_id,$jml_bayi_val,$jml_dipelihara_val,$jml_mati_val,$jml_dijual_val,
                                $hrg_dijual_val,$keadaan_melahirkan_val,$sex_val";
                				$sqlin="INSERT INTO keswan_kelahiran ($cols) VALUES ($values);";
                                $rsl=$db->query($sqlin);
                				if(isset($rsl->error) and $rsl->error===true){
                				    $jumlah_gagal++;
                					$migrasi=false;
                			        $message_result="Error insert, ".$rsl->query_last_message;
                				}else{	
                				    $jumlah_berhasil++;
                                    $message_result="Berhasil insert Kelahiran";
                                    $sqlup="UPDATE db_keswan.kelahiran set migrasi=1 WHERE IDEvent=$pelayanan_id;";
                                    $rslup=$db->query($sqlup);
                                    if(isset($rslup->error) and $rslup->error===true){
                                        $message_result="Berhasil insert Kelahiran. Gagal update Kelahiran status migrasi ".$rslup->query_last_message;
                                    }
                                    
                	            }
                            }else{
                                $jumlah_gagal++;
                                $migrasi=false;
                                $message_result="Data Kelahiran sudah ada di ERP";
                                
                               
                            }
                            sleep(1);
                            $persen=round(($j/$jumlah_data)*100,2);
            				$sisa_data=$jumlah_data-$j;
            				$migration=$migrasi?"Berhasil":"Gagal";
            			    $message = date("d/m/Y H:i:s")." <span id='col-id'>".$data->IDEvent."</span> | <span id='col-sapi'> ".$referensi['sex_sapi'][$data->IDKelamin]." (".$data->JmlLahir.")  </span> | <span id='col-status'> ".$migration." </span>| <span id='col-persen'>".$persen. '% </span> | '.$message_result;
            			    $progress = number_format($persen,2,",",".");;
            			    $progressor=$persen;
                            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% Eksekusi :'.$j.' dari '.$jumlah_data.' (sisa : '.$sisa_data.') | Berhasil :'.$jumlah_berhasil.', Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                            $j++;
                            next($list_ada);
                        }
                        sleep(1);
            			$message = date("d/m/Y H:i:s").' : Complete migrasi data';
            			$progressor = 100;
            			echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                    }else{
                        sleep(1);
                        $message="Tidak ada data Kelahiran yang perlu dimigrasi";
                        echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'!" + "<br />";</script>';
                    }
                } catch (Exception $e){
                    $message = 'Error : ' .$e->getMessage();
                    echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
                }/*
           }else{
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$msg_error.'" + "<br />";</script>';
           }*/
           break;
           case "ganti_pemilik":
       // if(trim($msg_error)==""){
                try{
            		set_time_limit(0); 
            		ob_implicit_flush(true);
            		ob_end_flush();
                    $list_qry=$db->select("IDEvent,IDPemilik_Lama,IDPemilik_Baru","db_keswan.ganti_pemilik")->where("ifnull(migrasi,0)=0")->lim();
                    $hasil_sync=array();
                    $j  = 0;
                    $jumlah_data    =$db->numRow($list_qry);
                    $jumlah_gagal   =0;
                    $jumlah_berhasil   =0;
                    if($jumlah_data>0){
                         while($data = $db->fetchObject($list_qry)){
                            $pelayanan_id=$data->IDEvent;
                        	$TglSkrg		=date("Y-m-d H:i:s");
                            $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
                            $message="";
                            $check_event=$db->select("id,pemilik_lama,pemilik_baru,keterangan,
                            pelayanan_id","keswan_ganti_pemilik")->where("pelayanan_id=".$pelayanan_id."")->get(0);
                            $migrasi=false;
                            if(empty($check_event)){
                               $migrasi=true;
                               //insert sapi
                               $member=$db->select("ID_ANGGOTA,C_ANGGOTA","anggota")->where("C_ANGGOTA='".$data->IDPemilik_Lama."'")->get(0);
                               $pemilik_lama_val     =$master->scurevaluetable($member->ID_ANGGOTA,"number");  
                               
                               $member_baru=$db->select("ID_ANGGOTA,C_ANGGOTA","anggota")->where("C_ANGGOTA='".$data->IDPemilik_Baru."'")->get(0);
                               $pemilik_baru_val     =$master->scurevaluetable($member_baru->ID_ANGGOTA,"number"); 
                               
                               $cols="pelayanan_id,pemilik_lama,pemilik_baru";
                               
                				$values="$pelayanan_id,$pemilik_lama_val,$pemilik_baru_val";
                				$sqlin="INSERT INTO keswan_ganti_pemilik ($cols) VALUES ($values);";
                                $rsl=$db->query($sqlin);
                				if(isset($rsl->error) and $rsl->error===true){
                				    $jumlah_gagal++;
                					$migrasi=false;
                			        $message_result="Error insert, ".$rsl->query_last_message;
                				}else{	
                				    $jumlah_berhasil++;
                                    $message_result="Berhasil insert Ganti Pemilik";
                                    $sqlup="UPDATE db_keswan.ganti_pemilik set migrasi=1 WHERE IDEvent=$pelayanan_id;";
                                    $rslup=$db->query($sqlup);
                                    if(isset($rslup->error) and $rslup->error===true){
                                        $message_result="Berhasil insert Ganti Pemilik. Gagal update Ganti Pemilik status migrasi ".$rslup->query_last_message;
                                    }
                                    
                	            }
                            }else{
                                $jumlah_gagal++;
                                $migrasi=false;
                                $message_result="Data Ganti Pemilik sudah ada di ERP";
                                
                               
                            }
                            sleep(1);
                            $persen=round(($j/$jumlah_data)*100,2);
            				$sisa_data=$jumlah_data-$j;
            				$migration=$migrasi?"Berhasil":"Gagal";
            			    $message = date("d/m/Y H:i:s")." <span id='col-id'>".$data->IDEvent."</span> | <span id='col-sapi'> ".$data->IDPemilik_Lama." -> ".$data->IDPemilik_Baru."  </span> | <span id='col-status'> ".$migration." </span>| <span id='col-persen'>".$persen. '% </span> | '.$message_result;
            			    $progress = number_format($persen,2,",",".");;
            			    $progressor=$persen;
                            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% Eksekusi :'.$j.' dari '.$jumlah_data.' (sisa : '.$sisa_data.') | Berhasil :'.$jumlah_berhasil.', Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                            $j++;
                            next($list_ada);
                        }
                        sleep(1);
            			$message = date("d/m/Y H:i:s").' : Complete migrasi data';
            			$progressor = 100;
            			echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                    }else{
                        sleep(1);
                        $message="Tidak ada data Ganti Pemilik yang perlu dimigrasi";
                        echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'!" + "<br />";</script>';
                    }
                } catch (Exception $e){
                    $message = 'Error : ' .$e->getMessage();
                    echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
                }/*
           }else{
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$msg_error.'" + "<br />";</script>';
           }*/
           break;
           case "ganti_eartag":
       // if(trim($msg_error)==""){
                try{
            		set_time_limit(0); 
            		ob_implicit_flush(true);
            		ob_end_flush();
                    $list_qry=$db->select("IDEvent,IDPemilik_Lama,IDPemilik_Baru","db_keswan.ganti_id_sapi")->where("ifnull(migrasi,0)=0")->lim();
                    $hasil_sync=array();
                    $j  = 0;
                    $jumlah_data    =$db->numRow($list_qry);
                    $jumlah_gagal   =0;
                    $jumlah_berhasil   =0;
                    if($jumlah_data>0){
                         while($data = $db->fetchObject($list_qry)){
                            $pelayanan_id=$data->IDEvent;
                        	$TglSkrg		=date("Y-m-d H:i:s");
                            $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
                            $message="";
                            $check_event=$db->select("id,pemilik_lama,pemilik_baru,keterangan,
                            pelayanan_id","keswan_ganti_pemilik")->where("pelayanan_id=".$pelayanan_id."")->get(0);
                            $migrasi=false;
                            if(empty($check_event)){
                               $migrasi=true;
                               //insert sapi
                               $member=$db->select("ID_ANGGOTA,C_ANGGOTA","anggota")->where("C_ANGGOTA='".$data->IDPemilik_Lama."'")->get(0);
                               $pemilik_lama_val     =$master->scurevaluetable($member->ID_ANGGOTA,"number");  
                               
                               $member_baru=$db->select("ID_ANGGOTA,C_ANGGOTA","anggota")->where("C_ANGGOTA='".$data->IDPemilik_Baru."'")->get(0);
                               $pemilik_baru_val     =$master->scurevaluetable($member_baru->ID_ANGGOTA,"number"); 
                               
                               $cols="pelayanan_id,pemilik_lama,pemilik_baru";
                               
                				$values="$pelayanan_id,$pemilik_lama_val,$pemilik_baru_val";
                				$sqlin="INSERT INTO keswan_ganti_pemilik ($cols) VALUES ($values);";
                                $rsl=$db->query($sqlin);
                				if(isset($rsl->error) and $rsl->error===true){
                				    $jumlah_gagal++;
                					$migrasi=false;
                			        $message_result="Error insert, ".$rsl->query_last_message;
                				}else{	
                				    $jumlah_berhasil++;
                                    $message_result="Berhasil insert Ganti Pemilik";
                                    $sqlup="UPDATE db_keswan.ganti_id_sapi set migrasi=1 WHERE IDEvent=$pelayanan_id;";
                                    $rslup=$db->query($sqlup);
                                    if(isset($rslup->error) and $rslup->error===true){
                                        $message_result="Berhasil insert Ganti Pemilik. Gagal update Ganti Pemilik status migrasi ".$rslup->query_last_message;
                                    }
                                    
                	            }
                            }else{
                                $jumlah_gagal++;
                                $migrasi=false;
                                $message_result="Data Ganti Pemilik sudah ada di ERP";
                                
                               
                            }
                            sleep(1);
                            $persen=round(($j/$jumlah_data)*100,2);
            				$sisa_data=$jumlah_data-$j;
            				$migration=$migrasi?"Berhasil":"Gagal";
            			    $message = date("d/m/Y H:i:s")." <span id='col-id'>".$data->IDEvent."</span> | <span id='col-sapi'> ".$data->IDPemilik_Lama." -> ".$data->IDPemilik_Baru."  </span> | <span id='col-status'> ".$migration." </span>| <span id='col-persen'>".$persen. '% </span> | '.$message_result;
            			    $progress = number_format($persen,2,",",".");;
            			    $progressor=$persen;
                            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% Eksekusi :'.$j.' dari '.$jumlah_data.' (sisa : '.$sisa_data.') | Berhasil :'.$jumlah_berhasil.', Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                            $j++;
                            next($list_ada);
                        }
                        sleep(1);
            			$message = date("d/m/Y H:i:s").' : Complete migrasi data';
            			$progressor = 100;
            			echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                    }else{
                        sleep(1);
                        $message="Tidak ada data Ganti Pemilik yang perlu dimigrasi";
                        echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'!" + "<br />";</script>';
                    }
                } catch (Exception $e){
                    $message = 'Error : ' .$e->getMessage();
                    echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
                }/*
           }else{
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$msg_error.'" + "<br />";</script>';
           }*/
           break;
           case "mutasi":
       // if(trim($msg_error)==""){
                try{
            		set_time_limit(0); 
            		ob_implicit_flush(true);
            		ob_end_flush();
                    $list_qry=$db->select("IDEvent,JenisMutasi,Alasan1,Alasan2,Penerimaan,Bertambah,Keterangan,
                    NoUrut_surat,Umur_Sapi,Produksi,Kandang,Kondisi,Laporan,Anggota,Santunan,Rincian,Setoran_DKT,
                    No_Polis,Jangka_Waktu_Berlaku_Pollis,migrasi","db_keswan.mutasi")->where("ifnull(migrasi,0)=0")->orderBy("IDEvent desc")->lim();
                    $hasil_sync=array();
                    $j  = 0;
                    $jumlah_data    =$db->numRow($list_qry);
                    $jumlah_gagal   =0;
                    $jumlah_berhasil   =0;
                    if($jumlah_data>0){
                         while($data = $db->fetchObject($list_qry)){
                            
                            $pelayanan_id=$data->IDEvent;
              
                        	$TglSkrg		=date("Y-m-d H:i:s");
                            $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
                            $message="";
                           
                                $check_mutasi=$db->select("id,pelayanan_id,jenis_mutasi,alasan1,alasan2,keterangan,no_polis,
                                kadaluarsa_polis,no_urut_surat,kondisi_kandang,kondisi_sapi,laporan,santunan,tunggak,
                                verification,verification_date","mutasi_sapi")->where("pelayanan_id=".$pelayanan_id."")->get(0);
                                $migrasi=false;
                                print_r($check_mutasi);
                                if(empty($check_mutasi)){
                                   $migrasi=true;
                                   //insert sapi
                                  
                                   $jenis_mutasi_val     =$master->scurevaluetable($data->JenisMutasi,"number");
                                   $alasan1=$data->Alasan1==0?"":$data->Alasan1;
                                   $alasan2=$data->Alasan2==0?"":$data->Alasan2;
                                   $alasan1_val           =$master->scurevaluetable($alasan1,"number");
                                   $alasan2_val           =$master->scurevaluetable($alasan2,"number");
                                   $keterangan_val     =$master->scurevaluetable($data->Keterangan);
                                   $no_polis_val     =$master->scurevaluetable($data->No_Polis);
                                   $kadaluarsa_polis_val     =$master->scurevaluetable($data->Jangka_Waktu_Berlaku_Pollis);
                                   $no_urut_surat_val     =$master->scurevaluetable($data->NoUrut_surat);
                                   
                                   $kandang_val     =$master->scurevaluetable($data->Kandang);
                                   $kondisi_val     =$master->scurevaluetable($data->Kondisi);
                                   $laporan_val     =$master->scurevaluetable($data->Laporan);
                                   $santunan_val     =$master->scurevaluetable($data->Santunan,"number",false);
                                   $tunggak_val     =$master->scurevaluetable($data->Setoran_DKT,"number",false);
                                  
                                   $cols="pelayanan_id,jenis_mutasi,alasan1,alasan2,keterangan,no_polis,
                                    kadaluarsa_polis,no_urut_surat,kondisi_kandang,kondisi_sapi,laporan,santunan,tunggak,
                                    verification,verification_date";
                                   
                    				$values="$pelayanan_id,$jenis_mutasi_val,$alasan1_val,$alasan2_val,$keterangan_val,
                                    $no_polis_val,$kadaluarsa_polis_val,$no_urut_surat_val,$kandang_val,$kondisi_val,$laporan_val,
                                    $santunan_val,$tunggak_val,1,$tgl_skrg_val";
                    				$sqlin="INSERT INTO mutasi_sapi ($cols) VALUES ($values);";
                                  // echo $sqlin;exit;
                                    $rsl=$db->query($sqlin);
                    				if(isset($rsl->error) and $rsl->error===true){
                    				    $jumlah_gagal++;
                    					$migrasi=false;
                    			        $message_result="Error insert, ".$rsl->query_last_message;
                    				}else{	
                    				    $jumlah_berhasil++;
                                        $message_result="Berhasil insert mutasi";
                                        $sqlup="UPDATE db_keswan.mutasi set migrasi=1 WHERE IDEvent=$pelayanan_id;";
                                        $rslup=$db->query($sqlup);
                                        if(isset($rslup->error) and $rslup->error===true){
                                            $message_result="Berhasil insert mutasi. Gagal update mutasi status migrasi ".$rslup->query_last_message;
                                        }
                                        
                    	            }
                                }else{
                                    $jumlah_gagal++;
                                    $migrasi=false;
                                    $message_result="Data Mutasi sudah ada di ERP";
                                    
                                   
                                }
                                
                            sleep(1);
                            $persen=round(($j/$jumlah_data)*100,2);
            				$sisa_data=$jumlah_data-$j;
            				$migration=$migrasi?"Berhasil":"Gagal";
            			    $message = date("d/m/Y H:i:s")." <span id='col-id'>".$data->IDEvent."</span> | <span id='col-sapi'>   </span> | <span id='col-jenis'> Mutasi </span> | <span id='col-status'> ".$migration." </span>| <span id='col-persen'>".$persen. '% </span> | '.$message_result;
            			    $progress = number_format($persen,2,",",".");;
            			    $progressor=$persen;
                            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% Eksekusi :'.$j.' dari '.$jumlah_data.' (sisa : '.$sisa_data.') | Berhasil :'.$jumlah_berhasil.', Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                            $j++;
                        }
                        sleep(1);
            			$message = date("d/m/Y H:i:s").' : Complete migrasi data';
            			$progressor = 100;
            			echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                    }else{
                        sleep(1);
                        $message="Tidak ada data pelayanan yang perlu dimigrasi";
                        echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'!" + "<br />";</script>';
                    }
                    
                } catch (Exception $e){
                    $message = 'Error : ' .$e->getMessage();
                    echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
                }/*
           }else{
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$msg_error.'" + "<br />";</script>';
           }*/
           break;
       }
    }
    
    public function sync_logistik($category="trx_from_detail") {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        $referensi      = $master->referensi_session();
        switch($category){
            case "trx_from_detail":
       // if(trim($msg_error)==""){
                try{
            		set_time_limit(0); 
            		ob_implicit_flush(true);
            		ob_end_flush();
                    $list_qry=$db->select("id,barang_id,barang_kredit_id,anggota_barang_kredit_detail_id,jumlah,
                    harga,sub_total,anggota_id,kelompok_harga_id,tanggal,created_time,periode_id,closed,kredit_first,
                    created_by,odoo_id,line_id,sync,trx_id","logistik")->where("ifnull(trx_id,'')=''")->orderBy("id asc")->lim(0,30000);
                    $hasil_sync=array();
                    $j  = 0;
                    $jumlah_data    =$db->numRow($list_qry);
                   // echo $jumlah_data;exit;
                    $jumlah_gagal   =0;
                    $jumlah_berhasil   =0;
                    if($jumlah_data>0){
                         while($data = $db->fetchObject($list_qry)){
                        	$TglSkrg		=date("Y-m-d H:i:s");
                            $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
                            $message="";
                           
                           $migrasi=true;
                          
                           $barang_id_val     =$master->scurevaluetable($data->barang_id,"number");
                           $barang_kredit_id_val     =$master->scurevaluetable($data->barang_kredit_id,"number");
                           $anggota_barang_kredit_detail_id_val     =$master->scurevaluetable($data->anggota_barang_kredit_detail_id,"number");
                           $anggota_id_val     =$master->scurevaluetable($data->anggota_id,"number");
                           $periode_id_val     =$master->scurevaluetable($data->periode_id,"number");
                           $tanggal_val     =$master->scurevaluetable($data->tanggal);
                           $created_time_val     =$master->scurevaluetable($data->created_time);
                           $sub_total_val     =$master->scurevaluetable($data->sub_total,"number");
                           
                           $member_qry   =$db->query("SELECT NAMA FROM anggota WHERE ID_ANGGOTA=".$data->anggota_id);
                           $member    =$db->fetchArray($member_qry);
                           $nama_anggota_val     =$master->scurevaluetable($member['NAMA']);
                          
                            $cols="periode_id,anggota_id,trx_date,created,operator,pengambil,total";
                           
            				$values="$periode_id_val,$anggota_id_val,$tanggal_val,$created_time_val,null,$nama_anggota_val,$sub_total_val";
            				$sqlin="INSERT INTO logistik_trx ($cols) VALUES ($values);";
                            $rsl=$db->query($sqlin);
            				if(isset($rsl->error) and $rsl->error===true){
            				    $jumlah_gagal++;
            					$migrasi=false;
            			        $message_result="Error insert, ".$rsl->query_last_message;
            				}else{	
            				    $last   =$db->query("SELECT LAST_INSERT_ID() as new_id;");
                                $new    =$db->fetchArray($last);
                                $message_result="Berhasil updatr logistik_trx";
                                $sqlup="UPDATE logistik set trx_id=".$new['new_id']." WHERE id=".$data->id;
                                $rslup=$db->query($sqlup);
                                if(isset($rslup->error) and $rslup->error===true){
                                    $message_result="Berhasil insert logistik_trx. Gagal update logistik ".$rslup->query_last_message;
                                }
            	            }
                              
                            sleep(1);
                            $persen=round(($j/$jumlah_data)*100,2);
            				$sisa_data=$jumlah_data-$j;
            				$migration=$migrasi?"Berhasil":"Gagal";
            			    $message = date("d/m/Y H:i:s")." <span id='col-id'>".$data->IDEvent."</span> | <span id='col-sapi'>".$data->IDSapi."  </span> | <span id='col-jenis'>".$data->IDJenisKejadian." </span> | <span id='col-tanggal'>".$data->TanggalKejadian." </span> | <span id='col-status'> ".$migration." </span>| <span id='col-persen'>".$persen. '% </span> | '.$message_result;
            			    $progress = number_format($persen,2,",",".");;
            			    $progressor=$persen;
                            echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% Eksekusi :'.$j.' dari '.$jumlah_data.' (sisa : '.$sisa_data.') | Berhasil :'.$jumlah_berhasil.', Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                            $j++;
                            next($list_ada);
                        }
                        sleep(1);
            			$message = date("d/m/Y H:i:s").' : Complete migrasi data';
            			$progressor = 100;
            			echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                    }else{
                        sleep(1);
                        $message="Tidak ada data pelayanan yang perlu dimigrasi";
                        echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'!" + "<br />";</script>';
                    }
                } catch (Exception $e){
                    $message = 'Error : ' .$e->getMessage();
                    echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
                }/*
           }else{
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$msg_error.'" + "<br />";</script>';
           }*/
           break;
           
       }
    }

}
 

?>