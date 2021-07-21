<?php
/**
 * @package Admin
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Migrasi_To_Production_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        set_time_limit(6400);
	    ini_set("memory_limit","1024M"); 
        
	}
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("migrasi_to_production");
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
   public function check_migrasi() {
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
   
    public function sync_cow() {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
       
      
       // if(trim($msg_error)==""){
            try{
        		set_time_limit(0); 
        		ob_implicit_flush(true);
        		ob_end_flush();
                $ser_qry=$db->select(" GROUP_CONCAT(induk) serialis","induk")->lim();
                $s=$db->fetchObject($ser_qry);
               // echo $s->serialis;exit;
                $list_qry=$db->select("id,name,koloni_name,posisi_eartag,birthplace,birthdate,cw.barcode,tanggal_identifikasi,is_active,
                anggota_id,C_ANGGOTA,ang1.NAMA,type,tipe,laktasi_ke,state_reproduction,state_lactation,metode_perolehan,induk,
                bapak,no_eartag_asal,gender,created_by,created_time,
                last_update,is_need_verification,verification_date,verificator,afkir,afkir_date","kpbs_db_dev.cow cw
                inner join kpbs_db_dev.anggota ang1 on ang1.ID_ANGGOTA=cw.anggota_id")
                ->where("ifnull(cw.sync,0)=0 or ( id in (".$s->serialis.")  and ifnull(cw.sync,0)=0)")->lim();//and  ifnull(cw.is_active,0)=1 id in (".$s->serialis.")
                
                $hasil_sync=array();
                $hasil_failed=array();
                $no_failed=0;
                $j  = 0;
                $jumlah_data    =$db->numRow($list_qry);
                $jumlah_gagal   =0;
                $jumlah_berhasil   =0;
                $message_result="";
                if($jumlah_data>0){
                     while($data = $db->fetchObject($list_qry)){
                        
                        $id_val           =$master->scurevaluetable($data->id,"number",false);
                        $eartag           =strtoupper($data->name);
                        $eartag_val           =$master->scurevaluetable($eartag);
                        $koloni_name_val     =$master->scurevaluetable($data->koloni_name);
                        $posisi_eartag_val     =$master->scurevaluetable($data->posisi_eartag);
                        $birthplace_val     =$master->scurevaluetable($data->birthplace);
                        $birthdate_val     =$master->scurevaluetable($data->birthdate);
                        $barcode_val     =$master->scurevaluetable($data->barcode);
                        $tgl_identifikasi_val     =$master->scurevaluetable($data->tanggal_identifikasi);
                        $is_active_val           =$master->scurevaluetable($data->is_active,"number",false);
                        
                        $anggota_prod=$db->select("ID_ANGGOTA as id","kpbs_db.anggota")->where("C_ANGGOTA='".$data->C_ANGGOTA."'")->get(0);
                        $anggota_id=$anggota_prod->id;
                        $migrasi=false;
                        if(trim($anggota_prod->id)==""){
                            $jumlah_gagal++;
                            $migrasi=false;
          			        $message_result="No Anggota ".$data->C_ANGGOTA." (".$data->NAMA.") tidak ada dalam database";
                            
                        }else{
                            $type_val     =$master->scurevaluetable($data->type);
                            $tipe_val		=$master->scurevaluetable($data->tipe,"number",false);
                            $laktasi_ke_val		=$master->scurevaluetable($data->laktasi_ke,"number",false);                        
                            $state_reproduction_val		=$master->scurevaluetable($data->state_reproduction,"number",false);       
                            $state_lactation_val		=$master->scurevaluetable($data->state_lactation,"number",false); 
                            $metode_perolehan_val		=$master->scurevaluetable($data->metode_perolehan,"number",false); 
                            
                            $induk_val		=$master->scurevaluetable($data->induk,"number",false); 
                            $bapak_val		=$master->scurevaluetable($data->bapak,"number",false);                           
                            $eartag_asal_val     =$master->scurevaluetable($data->no_eartag_asal);
                            $gender_val		=$master->scurevaluetable($data->gender,"number",false);    
                            
                            $created_time_val     =$master->scurevaluetable($data->created_time);
                            $last_update_val     =$master->scurevaluetable($data->last_update);
                            $is_need_verification_val		=$master->scurevaluetable($data->is_need_verification,"number",false);  
                            $verification_date_val     =$master->scurevaluetable($data->verification_date);
                            $verificator_val		=$master->scurevaluetable($data->verificator,"number",false);  
                            $afkir_val		=$master->scurevaluetable($data->afkir,"number",false);   
                            $afkir_date_val     =$master->scurevaluetable($data->afkir_date);
                            
                            $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
                            $message="";
                            $check_sapi=$db->select("id","kpbs_db.cow")->where("id=".$data->id."")->get();
                            if(empty($check_sapi)){//insert
                               $cols="id,name,koloni_name,posisi_eartag,birthplace,birthdate,barcode,tanggal_identifikasi,
                                is_active,anggota_id,type,tipe,laktasi_ke,state_reproduction,state_lactation,
                                metode_perolehan,induk,bapak,no_eartag_asal,gender,created_by,created_time,
                                last_update,is_need_verification,verification_date,verificator,afkir,afkir_date";
                               
                				$values="$id_val,$eartag_val,$koloni_name_val,$posisi_eartag_val,$birthplace_val,$birthdate_val,$eartag_val,$tgl_identifikasi_val,
                                $is_active_val,$anggota_id,$type_val,$tipe_val,$laktasi_ke_val,$state_reproduction_val,$state_lactation_val,
                                $metode_perolehan_val,$induk_val,$bapak_val,$eartag_asal_val,$gender_val,null,$created_time_val,
                                $last_update_val,$is_need_verification_val,$verification_date_val,$verificator_val,$afkir_val,$afkir_date_val";
                				$sqlin="INSERT INTO kpbs_db.cow ($cols) VALUES ($values);";
                                $rsl=$db->query($sqlin);
                				if(isset($rsl->error) and $rsl->error===true){
                				    $jumlah_gagal++;
                					$migrasi=false;
                			        $message_result="Error insert, ".$rsl->query_last_message;
                				}else{	
                				    $jumlah_berhasil++;
                                    $migrasi=true;
                                    $message_result="Berhasil insert sapi";
                                    $sqlup="UPDATE kpbs_db_dev.cow set sync=1 WHERE id=".$data->id.";";
                                    $db->query($sqlup);
                	            }
                                 //echo $j.' '.$message_result." $sqlin<br />";
                            }else{
                                
                                $col_and_vals="name=$eartag_val,koloni_name=$koloni_name_val,posisi_eartag=$posisi_eartag_val,birthplace=$birthplace_val,
                                birthdate=$birthdate_val,barcode=$eartag_val,tanggal_identifikasi=$tgl_identifikasi_val,is_active=$is_active_val,
                                anggota_id=$anggota_id,type=$type_val,tipe=$tipe_val,laktasi_ke=$laktasi_ke_val,state_reproduction=$state_reproduction_val,
                                state_lactation=$state_lactation_val,metode_perolehan=$metode_perolehan_val,induk=$induk_val,bapak=$bapak_val,
                                no_eartag_asal=$eartag_asal_val,gender=$gender_val,created_by=null,created_time=$created_time_val,
                                last_update=$last_update_val,is_need_verification=$is_need_verification_val,verification_date=$verification_date_val,
                                verificator=$verificator_val,afkir=$afkir_val,afkir_date=$afkir_date_val";
                                $sqlupa="UPDATE kpbs_db.cow set $col_and_vals WHERE id=".$data->id.";";
                                $hsl2=$db->query($sqlupa);
                                if(isset($hsl2->error) and $hsl2->error===true){
                                    $jumlah_gagal++;
                                    $migrasi=false;
                                    $message_result="Error update, ".$hsl2->query_last_message;
                                }else{	
                                    $jumlah_berhasil++;
                                    $migrasi=true;
                                    $message_result="Berhasil update sapi";
                                    $sqlup="UPDATE kpbs_db_dev.cow set sync=1 WHERE id=".$data->id.";";
                                    $db->query($sqlup);
                	            }
                                 //echo $j.' '.$message_result." $sqlupa<br />";
                            }
                        }//anggota_id
                        if($migrasi==false){
                            $hasil_failed[$no_failed]['id_sapi']=$data->id;
                            $hasil_failed[$no_failed]['no_eartag']=$data->name;
                            $hasil_failed[$no_failed]['active']=$data->is_active;
                            $hasil_failed[$no_failed]['afkir']=$data->afkir;
                            $hasil_failed[$no_failed]['induk']=$data->induk;
                            $hasil_failed[$no_failed]['bapak']=$data->bapak;
                            $hasil_failed[$no_failed]['anggota_id']=$data->anggota_id;
                            $hasil_failed[$no_failed]['anggota_no']=$data->C_ANGGOTA;
                            $hasil_failed[$no_failed]['anggota_nama']=$data->NAMA;
                            $hasil_failed[$no_failed]['message']=$message_result;
                            $no_failed++;
                        }
                        sleep(1);
                        $persen=round(($j/$jumlah_data)*100,2);
        				$sisa_data=$jumlah_data-$j;
        				$migration=$migrasi?"Berhasil":"Gagal";
        			    $message = date("d/m/Y H:i:s")."  <span id='col-members'> id : ".$data->id." - No : ".$eartag. " aktif=".$data->is_active." afkir=".$data->afkir."  </span> <span id='col-status'> ".$migration." </span> <span id='col-persen'> ".$persen. "% </span> <span id='col-message'> ".$message_result."</span>";
        			    //echo $message."<br />";
                        $progress = number_format($persen,2,",",".");;
        			    $progressor=$persen;
                        echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "'.$progress.'% Eksekusi :'.$j.' dari '.$jumlah_data.' (sisa : '.$sisa_data.') | Berhasil :'.$jumlah_berhasil.', Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                        
                        $j++;
                    }
                     
                    sleep(1);
        			$message = date("d/m/Y H:i:s").' : Complete migrasi data';
        			$progressor = 100;
        			echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";window.parent.document.getElementById("persen_id").innerHTML = "100% | Gagal :'.$jumlah_gagal.'" + "<br />";window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                    if(!empty($hasil_failed)){
                      $path_web =dirname($_SERVER['DOCUMENT_ROOT'].$_SERVER['SCRIPT_NAME']);//."/files/data_gagal_migrasi_sapi_".$sekarang.".xls";exit;
                        //echo "<pre>";print_r($hasil_failed);echo "</pre>";exit;
                        /**   Data gagal export to excel    **/
                        ob_start();
                        require_once 'plugins/PHPExcel/Classes/PHPExcel.php';
            	        $excel = new PHPExcel();
         	            $a=$master->col_excel(80);
            	       	//echo "<pre>";print_r($a);echo "</pre>";exit;
                         $excel->getProperties()->setCreator("KPBS-Pangalengan")
	            				   ->setLastModifiedBy("Hasan")
	            				   ->setTitle("Format Cetak")
	            				   ->setSubject("Format Cetak")
	            				   ->setDescription("Rekap Kegiatan")
	            				   ->setKeywords("Pelayanan");
                                   
                        $excel->createSheet();
            
                        $excel->setActiveSheetIndex(0)->mergeCells('A1:K1')->setCellValue('A1', "Data Gagal Migrasi Sapi");
                       
                        $excel->setActiveSheetIndex(0)->setCellValue($a[0].'2', 'No')
                            ->setCellValue($a[1].'2', 'id_sapi')
                          	->setCellValue($a[2].'2', 'no_eartag')
                            ->setCellValue($a[3].'2', 'active')
                            ->setCellValue($a[4].'2', 'afkir')
                            ->setCellValue($a[5].'2','induk')
                            ->setCellValue($a[6].'2','bapak')
                         	->setCellValue($a[7].'2', 'anggota_id')
                            ->setCellValue($a[8].'2', 'anggota_no ')
                            ->setCellValue($a[9].'2', 'anggota_nama')
                            ->setCellValue($a[10].'2', 'message');
                            
                        $i=3;
                        $no=1;
                        while($data1 = current($hasil_failed))
                        {
                            $excel->setActiveSheetIndex(0)
                          	->setCellValue($a[0].$i, $no)
                          	->setCellValue($a[1].$i, $data1['id_sapi'])
                           	->setCellValueExplicit($a[2].$i,$data1['no_eartag'], PHPExcel_Cell_DataType::TYPE_STRING)
                          	->setCellValue($a[3].$i,$data1['active'])
                            ->setCellValue($a[4].$i,$data1['afkir'])
                           	->setCellValue($a[5].$i,$data1['induk'])
                            ->setCellValue($a[6].$i,$data1['bapak'])
                            ->setCellValue($a[7].$i,$data1['anggota_id'])
                            ->setCellValueExplicit($a[8].$i,$data1['anggota_no'], PHPExcel_Cell_DataType::TYPE_STRING)                  	
                            ->setCellValue($a[9].$i,$data1['anggota_nama'])
                            ->setCellValue($a[10].$i,$data1['message']);
                            $i++;  
                            $no++;
                            next($hasil_failed);
                        }
                        $excel->getActiveSheet(0)->setTitle('Gagal Migrasi');
                        $excel->setActiveSheetIndex(0);
                        $excel->createSheet();
                        $sekarang=date("dmY_His");
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="data_gagal_migrasi_sapi_'.$sekarang.'.xls"');
                        header('Cache-Control: max-age=0');
                       
                        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
                       
                        $objWriter->save($path_web."/files/data_gagal_migrasi_sapi_".$sekarang.".xls"); 
                        
                         
                        exit;
                        /** =============================== */
                        
                     }
                }else{// if($jumlah_data>0){
                    sleep(1);
                    $message="Tidak ada data yang perlu dimigrasi";
                    echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'!" + "<br />";</script>';
                }
            } catch (Exception $e){
                $message = 'Error : ' .$e->getMessage();
                echo '<script ">window.parent.document.getElementById("divProgress").innerHTML += "'.$message.'" + "<br />";</script>';
            }
      
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