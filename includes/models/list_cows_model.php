<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Damas Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class List_Cows_Model extends Model {
  
	public function __construct() {
		
	}
    public function insert($petugas_id,$no_eartag,$posisi_eartag,$koloni_name,$tanggal_identifikasi,$tanggal_lahir,$anggota_id,$tipe,$cow_induk,$cow_bapak,$jenis_kelamin,$metode_perolehan,$active,$need_verification="") {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master	    = new  Master_Ref_Model();
        $referensi      = $master->referensi_session();
        
        $hasil      =array();
        if(trim($petugas_id)=="" or  $petugas_id == null){
            $hasil['success']=false;
           	$hasil['message']="Error, Petugas ID tidak boleh kosong";
                
        }else{
            $cek_pegawai=$db->select("pID","keswan_pegawai")->where("pID=".$petugas_id."")->get();
            if(empty($cek_pegawai)){
                $hasil['success']=false;
               	$hasil['message']="Error, Petugas ID tidak ada dalam data pegawai";
                
            }else{
                
                $for_id="0000".(int)$petugas_id;
                $prefix_id = (int)"1".substr($for_id,(strlen($for_id)-4),4);
                $filter_id="LEFT(CONVERT(id,CHAR(10)),5)='".$prefix_id."' and LENGTH(CONVERT(id,CHAR))=10";
                $max       =$db-> select(" (ifnull(MAX(cast( right(CONVERT(ifnull(id,0),CHAR),5) as UNSIGNED)),0)+1) as new_number ","cow")->where($filter_id)->get(0);		
                $for_suffix_id ="00000".(string)$max->new_number;
                $suffix_id = substr($for_suffix_id,(strlen($for_suffix_id)-5),5);
                
                $psn="Prefix : ".$prefix_id." suffix : ".$suffix_id;
                $new_cow_id = trim($prefix_id).trim($suffix_id);
                
                $TglSkrg	=date("Y-m-d H:i:s");
                $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
                $no_eartag          = strtoupper($no_eartag);
                $no_eartag_val		=$master->scurevaluetable($no_eartag);
                $posisi_eartag_val		=$master->scurevaluetable($posisi_eartag);
                $koloni_name_val		=$master->scurevaluetable($koloni_name);
                $tanggal_identifikasi_val		=$master->scurevaluetable($tanggal_identifikasi);
                $tanggal_lahir_val		=$master->scurevaluetable($tanggal_lahir);
                $anggota_id_val	=$master->scurevaluetable($anggota_id,"number");
                
                $cow_induk_val	=$master->scurevaluetable($cow_induk,"number");
                $cow_bapak_val	=$master->scurevaluetable($cow_bapak,"number");
                $jenis_kelamin_val	=$master->scurevaluetable($jenis_kelamin,"number");
                $metode_perolehan_val	=$master->scurevaluetable($metode_perolehan,"number");
                $active_val	=$master->scurevaluetable($active,"number");
                $need_verification_val	=$master->scurevaluetable($need_verification,"number");
                $tipe_val	=$master->scurevaluetable($tipe,"number");
                $tipe_name          = $referensi['tipe_sapi'][$tipe];
                $tipe_name_val	=$master->scurevaluetable($tipe_name);
                
                $colsc="id,name,koloni_name,birthdate,barcode,is_active,anggota_id,
                type,tipe,metode_perolehan,posisi_eartag,tanggal_identifikasi,induk,
                bapak,gender,created_time,last_update,is_need_verification";
        	    $valuesc="$new_cow_id,$no_eartag_val,$koloni_name_val,$tanggal_lahir_val,$no_eartag_val,$active_val,$anggota_id_val,
                $tipe_name_val,$tipe_val,$metode_perolehan_val,$posisi_eartag_val,$tanggal_identifikasi_val,$cow_induk_val,$cow_bapak_val,$jenis_kelamin_val,
                $tgl_skrg_val,$tgl_skrg_val,$need_verification_val";
                $sqlinc="INSERT INTO cow ($colsc) VALUES ($valuesc);";
                $rslc=$db->query($sqlinc);
                if(isset($rslc->error) and $rslc->error===true){
           	 		$hasil['success']=false;
                	$hasil['message']="Error, ".$rslc->query_last_message." ".$psn;
        	    }else{
        	        
                    $hasil['success']=true;
                    $hasil['new_id']=$new_cow_id;
                   	$hasil['message']="Data sapi sudah ditambahkan $suffix_id ".$psn;
                }
             }
        }
        return $hasil;
    }
     public function update($id,$no_eartag,$posisi_eartag,$koloni_name,$tanggal_identifikasi,$tanggal_lahir,$anggota_id,$tipe,$cow_induk,$cow_bapak,$jenis_kelamin,$metode_perolehan,$active,$need_verification="") {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master	    = new  Master_Ref_Model();
        $referensi      = $master->referensi_session();
        $hasil      =array();
        $TglSkrg	=date("Y-m-d H:i:s");
        $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
        if(trim($id)<>"" and  $id == null){
            $hasil['success']=false;
           	$hasil['message']="Error, ID Sapi tidak boleh kosong";
        }else{
            $no_eartag          = strtoupper($no_eartag);
            $no_eartag_val		=$master->scurevaluetable($no_eartag);
            $posisi_eartag_val		=$master->scurevaluetable($posisi_eartag);
            $koloni_name_val		=$master->scurevaluetable($koloni_name);
            $tanggal_identifikasi_val		=$master->scurevaluetable($tanggal_identifikasi);
            $tanggal_lahir_val		=$master->scurevaluetable($tanggal_lahir);
            $anggota_id_val	=$master->scurevaluetable($anggota_id,"number");
            
            $cow_induk_val	=$master->scurevaluetable($cow_induk,"number");
            $cow_bapak_val	=$master->scurevaluetable($cow_bapak,"number");
            $jenis_kelamin_val	=$master->scurevaluetable($jenis_kelamin,"number");
            $metode_perolehan_val	=$master->scurevaluetable($metode_perolehan,"number");
            $active_val	=$master->scurevaluetable($active,"number");
            $need_verification_val	=$master->scurevaluetable($need_verification,"number");
            $tipe_val	=$master->scurevaluetable($tipe,"number");
            $tipe_name          = $referensi['tipe_sapi'][$tipe];
            $tipe_name_val	=$master->scurevaluetable($tipe_name);
            
            $cols_and_valsc="name=$no_eartag_val,koloni_name=$koloni_name_val,birthdate=$tanggal_lahir_val,barcode=$no_eartag_val,
            is_active=$active_val,anggota_id=$anggota_id_val,type=$tipe_name_val,tipe=$tipe_val,metode_perolehan=$metode_perolehan_val,
            posisi_eartag=$posisi_eartag_val,tanggal_identifikasi=$tanggal_identifikasi_val,induk=$cow_induk_val,
            bapak=$cow_bapak_val,gender=$jenis_kelamin_val,last_update=$tgl_skrg_val,is_need_verification=$need_verification_val,sync=null";
    	    
            $sqlinc="UPDATE cow SET $cols_and_valsc WHERE id=$id;";
            $rslc=$db->query($sqlinc);
            if(isset($rslc->error) and $rslc->error===true){
       	 		$hasil['success']=false;
            	$hasil['message']="Error, ".$rslc->query_last_message;
    	    }else{
                $hasil['success']=true;
               	$hasil['message']="Data sapi sudah diupdate ";
            }
         }
        return $hasil;
    }
    public function delete($cow_id){
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $hasil=array();
        $sqlinc="DELETE FROM cow  WHERE id=$cow_id;";
        $rslc=$db->query($sqlinc);
        if(isset($rslc->error) and $rslc->error===true){
   	 		$hasil['success']=false;
        	$hasil['message']="Error, ".$rslc->query_last_message;
	    }else{
            $hasil['success']=true;
           	$hasil['message']="Data sapi sudah dihapus ";
        }
        return $hasil;
    }
    
    public function update_status_laktasi($id,$status_laktasi="",$add_laktasi=false) {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master	    = new  Master_Ref_Model();
        $hasil      =array();
        $TglSkrg	=date("Y-m-d H:i:s");
        $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
        if(trim($id)=="" or  $id == null){
            $hasil['success']=false;
           	$hasil['message']="Error, ID Sapi tidak boleh kosong";
        }else{
            if(trim($status_laktasi)<>""){
                $status_laktasi_val		=$master->scurevaluetable($status_laktasi,"number");
                $cols_and_valsc="state_lactation=$status_laktasi_val,last_update=$tgl_skrg_val,sync=null";
                if($status_laktasi==1 and $add_laktasi==true){
                    // 1 -> status_laktasi=laktasi
                    $get_cow=$this->getCow($id);
                    $laktasi_ke = (int)$get_cow->laktasi_ke+1;
                    $laktasi_ke_val	=$master->scurevaluetable($laktasi_ke,"number");
                    $cols_and_valsc   =$cols_and_valsc.",laktasi_ke=$laktasi_ke_val";
                }
                $sqlinc="UPDATE cow SET $cols_and_valsc WHERE id=$id;";
                $rslc=$db->query($sqlinc);
                if(isset($rslc->error) and $rslc->error===true){
           	 		$hasil['success']=false;
                	$hasil['message']="Error, ".$rslc->query_last_message;
        	    }else{
                    $hasil['success']=true;
                   	$hasil['message']="Data sapi sudah diupdate ";
                }
             }else{
                $hasil['success']=false;
               	$hasil['message']="Gagal, status laktasi tidak boleh kosong";
             }
         }
        return $hasil;
    }
    public function update_status_reproduksi($id,$status_reproduksi="") {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master	    = new  Master_Ref_Model();
        $hasil      =array();
        $TglSkrg	=date("Y-m-d H:i:s");
        $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
        if(trim($id)=="" or  $id == null){
            $hasil['success']=false;
           	$hasil['message']="Error, ID Sapi tidak boleh kosong";
        }else{
            if(trim($status_reproduksi)<>""){
                $status_reproduksi_val		=$master->scurevaluetable($status_reproduksi,"number");
                $cols_and_valsc="state_reproduction=$status_reproduksi_val,last_update=$tgl_skrg_val,sync=null";
               
                $sqlinc="UPDATE cow SET $cols_and_valsc WHERE id=$id;";
                $rslc=$db->query($sqlinc);
                if(isset($rslc->error) and $rslc->error===true){
           	 		$hasil['success']=false;
                	$hasil['message']="Error, ".$rslc->query_last_message;
        	    }else{
                    $hasil['success']=true;
                   	$hasil['message']="Data sapi sudah diupdate ";
                }
             }else{
                $hasil['success']=false;
               	$hasil['message']="Gagal, status reproduksi tidak boleh kosong";
             }
         }
        return $hasil;
    }
    public function update_tipe_sapi($id,$tipe_sapi="") {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master	    = new  Master_Ref_Model();
        $referensi      = $master->referensi_session();
        $hasil      =array();
        $TglSkrg	=date("Y-m-d H:i:s");
        $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
        if(trim($id)=="" or  $id == null){
            $hasil['success']=false;
           	$hasil['message']="Error, ID Sapi tidak boleh kosong";
        }else{
            if(trim($tipe_sapi)<>""){
                
                $tipe_sapi_val		=$master->scurevaluetable($tipe_sapi,"number");
                $tipe_name          = $referensi['tipe_sapi'][$tipe_sapi];
                $tipe_name_val	     =$master->scurevaluetable($tipe_name);
                $cols_and_valsc="tipe=$tipe_sapi_val,type=$tipe_name_val,last_update=$tgl_skrg_val,sync=null";
                $sqlinc="UPDATE cow SET $cols_and_valsc WHERE id=$id;";
                $rslc=$db->query($sqlinc);
                if(isset($rslc->error) and $rslc->error===true){
           	 		$hasil['success']=false;
                	$hasil['message']="Error, ".$rslc->query_last_message;
        	    }else{
                    $hasil['success']=true;
                   	$hasil['message']="Data sapi sudah diupdate ";
                }
             }else{
                $hasil['success']=false;
               	$hasil['message']="Gagal, status reproduksi tidak boleh kosong";
             }
         }
        return $hasil;
    }
    public function getCow($id,$format="object",$batas_akhir_tanggal_dipelihara="") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($id)=="" or  $id == null){
			return array();
		}else{
		  
          $batas_akhir_tanggal_dipelihara=trim($batas_akhir_tanggal_dipelihara)==""?"now()":"'".$batas_akhir_tanggal_dipelihara."'";
			$filter="c.id=".$id."";
            $data= $db->select("c.id,c.name no_eartag,koloni_name,birthplace,birthdate,DATE_FORMAT(birthdate,'%d/%m/%Y') tanggal_alhir,
            datediff(current_date(),birthdate) as usia,current_date() as tgl_sekarang,posisi_eartag,tanggal_identifikasi,
            c.barcode,photo_path,created_time,created_by,ifnull(c.is_active,0) is_active,anggota_id,ifnull(is_need_verification,0) is_need_verification,
            case when ifnull(anggota_id,'')<>'' then concat( C_ANGGOTA,' - ',a.NAMA) 
            else null end pemilik,a.NAMA, type,tipe,laktasi_ke,state_reproduction,state_lactation,metode_perolehan,gender,
            induk,bapak,afkir,a.ID_KELOMPOK kelompok_id,kel.name kelompok_name,mcp_id tpk_id,m.name tpk_name,
            case when ifnull(tanggal_identifikasi,'')<>'' then TIMESTAMPDIFF(YEAR,tanggal_identifikasi, $batas_akhir_tanggal_dipelihara) else null end jml_tahun_diperlihara, 
        case when ifnull(tanggal_identifikasi,'')<>'' then TIMESTAMPDIFF(MONTH,tanggal_identifikasi, $batas_akhir_tanggal_dipelihara) else null end jml_bulan_diperlihara,
        case when ifnull(tanggal_identifikasi,'')<>'' then DATEDIFF($batas_akhir_tanggal_dipelihara,tanggal_identifikasi) else null end jml_hari_diperlihara
            ","cow c
            inner join anggota a on a.ID_ANGGOTA=c.anggota_id
            left join kelompok kel on kel.id=a.ID_KELOMPOK
            left join mcp m on m.id=kel.mcp_id
            left join kelompok_harga kh on kh.id=a.ID_KELOMPOK_HARGA
            ")->where($filter)->get(0);

					
			if(!empty($data)){
				$referensi	= $master->referensi_session();
				$rec    	= new stdClass;
                
                $jml_tahun= $data->jml_tahun_diperlihara;
                $sisa_bulan=$data->jml_bulan_diperlihara-($jml_tahun * 12);
               // $sisa_hari=$data->jml_bulan-($jml_tahun * 12);
                $jdl_bulan=$sisa_bulan==0?"":"<br />".$sisa_bulan." bln";
                $rec->LamaDiperlihara=$jml_tahun." thn".$jdl_bulan;
                //$rec->tipe      = (trim($data->tipe)=="" and trim($data->type)<>"")?array_search($data->type,$referensi['tipe_sapi']):$data->tipe;
                $rec->tipe      = $data->tipe;//$tipe;
                $rec->tipe_nama      = $referensi['tipe_sapi'][$data->tipe];
                $rec->NamaTipeSapi	= $referensi['tipe_sapi'][$data->tipe];
                $rec->StatusAktif		= $referensi['status_sapi'][$data->is_active];
                $rec->StatusVerifikasi	= $referensi['status_verifikasi'][$data->is_need_verification];
				$rec->MetodePerolehanNama		= $referensi['metode_perolehan'][$data->metode_perolehan];
                $rec->StatusSapiNama		= $referensi['status_sapi'][$data->status_sapi];
                
                $rec->StatusReproduksiID		= $data->state_reproduction;
                $rec->StatusReproduksi		= $referensi['status_reproduksi'][$data->state_reproduction];
                $rec->StatusLaktasiID		= $data->state_lactation;
                $rec->StatusLaktasi		= $referensi['status_laktasi'][$data->state_lactation];
                 $rec->Afkir            =$data->afkir;
                $rec->Sex		= trim($data->gender)=="J"?"Jantan":(trim($data->gender)=="B"?"Betina":"");
			     $rec->TanggalIdentifikasi=$master->detailtanggal($data->tanggal_identifikasi,2);
                 $rec->TanggalLahir     =$master->detailtanggal($data->birthdate,2);
			//	$rec->url_foto= url::base()."foto/".$data->mhsFileFoto;
                if(trim($data->induk)<>""){
                    $rec->Induk=$this->getCow($data->induk);
                }
                $file_foto      = $data->photo_path;
                if(trim($data->photo_path)==""){
                    $file_foto ="cow.jpg";
                    $url_foto=url::base()."foto/sapi/".$file_foto;
                }/*else{
                    $url_foto="http://111.223.254.6/anggota/".$data->ID_ANGGOTA."/photo/";
                }*/
               
				$rec->url_foto=$url_foto;
				if(trim($format)=="array"){
					$result = array_merge((array) $data, (array) $rec);
					return $result;
				}else{
					$result	= (object) array_merge((array) $data, (array) $rec);
					return $result;
				}				
			
			}else{
				return array();
			}
		}
	}
    public function checkDataVerifikasi($id,$format="object",$batas_akhir_tanggal_dipelihara="") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($id)=="" or  $id == null){
			return array();
		}else{
		  
          $batas_akhir_tanggal_dipelihara=trim($batas_akhir_tanggal_dipelihara)==""?"now()":"'".$batas_akhir_tanggal_dipelihara."'";
			$filter="c.id=".$id."";
            $data= $db->select("c.id,c.name no_eartag,koloni_name,birthplace,birthdate,DATE_FORMAT(birthdate,'%d/%m/%Y') birthdate,
            datediff(current_date(),birthdate) as usia,current_date() as tgl_sekarang,posisi_eartag,tanggal_identifikasi,
            c.barcode,photo_path,created_time,created_by,ifnull(c.is_active,0) is_active,anggota_id,ifnull(is_need_verification,0) is_need_verification,
            case when ifnull(anggota_id,'')<>'' then concat( C_ANGGOTA,' - ',a.NAMA) 
            else null end pemilik,a.NAMA, type,laktasi_ke,metode_perolehan,type,gender,induk,
            a.ID_KELOMPOK kelompok_id,kel.name kelompok_name,mcp_id tpk_id,m.name tpk_name,
            case when ifnull(tanggal_identifikasi,'')<>'' then TIMESTAMPDIFF(YEAR,tanggal_identifikasi, $batas_akhir_tanggal_dipelihara) else null end jml_tahun_diperlihara, 
        case when ifnull(tanggal_identifikasi,'')<>'' then TIMESTAMPDIFF(MONTH,tanggal_identifikasi, $batas_akhir_tanggal_dipelihara) else null end jml_bulan_diperlihara,
        case when ifnull(tanggal_identifikasi,'')<>'' then DATEDIFF($batas_akhir_tanggal_dipelihara,tanggal_identifikasi) else null end jml_hari_diperlihara
            ","cow c
            inner join anggota a on a.ID_ANGGOTA=c.anggota_id
            left join kelompok kel on kel.id=a.ID_KELOMPOK
            left join mcp m on m.id=kel.mcp_id
            left join kelompok_harga kh on kh.id=a.ID_KELOMPOK_HARGA
            ")->where($filter)->get(0);

					
			if(!empty($data)){
				$referensi	= $master->referensi_session();
				$rec    	= new stdClass;
                $jml_tahun= $data->jml_tahun_diperlihara;
                $sisa_bulan=$data->jml_bulan_diperlihara-($jml_tahun * 12);
               // $sisa_hari=$data->jml_bulan-($jml_tahun * 12);
                $jdl_bulan=$sisa_bulan==0?"":"<br />".$sisa_bulan." bln";
                $rec->LamaDiperlihara=$jml_tahun." thn".$jdl_bulan;
                $rec->StatusAktif		= $referensi['status_sapi'][$data->is_active];
                $rec->StatusVerifikasi	= $referensi['status_verifikasi'][$data->is_need_verification];
				$rec->MetodePerolehanNama		= $referensi['metode_perolehan'][$data->metode_perolehan];
                $rec->StatusSapiNama		= $referensi['status_sapi'][$data->status_sapi];
                $rec->Sex		= trim($data->gender)=="J"?"Jantan":(trim($data->gender)=="B"?"Betina":"");
			     $rec->TanggalIdentifikasi=$master->detailtanggal($data->tanggal_identifikasi,2);
			//	$rec->url_foto= url::base()."foto/".$data->mhsFileFoto;
                if(trim($data->induk)<>""){
                    $rec->Induk=$this->getCow($data->induk);
                }
                $file_foto      = $data->photo_path;
                if(trim($data->photo_path)==""){
                    $file_foto ="cow.jpg";
                    $url_foto=url::base()."foto/sapi/".$file_foto;
                }/*else{
                    $url_foto="http://111.223.254.6/anggota/".$data->ID_ANGGOTA."/photo/";
                }*/
               
				$rec->url_foto=$url_foto;
				if(trim($format)=="array"){
					$result = array_merge((array) $data, (array) $rec);
					return $result;
				}else{
					$result	= (object) array_merge((array) $data, (array) $rec);
					return $result;
				}				
			
			}else{
				return array();
			}
		}
	}
    public function getMedicalRecord($cow_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		if(trim($member_id)=="" or  $member_id == null){
			return array();
		}else{
		    $referensi	= $_SESSION["referensi"];
			$filter="anggota_id=".$member_id."";
            $data_qry= $db->select("mr.id,mr.pelayanan_id,mr.kasus","keswan_medical_record mr
            inner join keswan_pelayanan_sapi kps on kps.id=mr.pelayanan_id
            inner join keswan_pelayanan_jenis kpj on kpj.pelayanan_id=jenis_pelayanan ")->where($filter)->lim();
            $data_sapi=array();
            while($data = $db->fetchObject($data_qry))
            {
                
                $rec    	   = new stdClass;
                $rec->NoEartag          = $data->no_eartag;
                $rec->Nama              = $data->koloni_name;
                $rec->PosisiEartag      = $data->posisi_eartag;
                $rec->CaraPerolehanNama	= $referensi['metode_perolehan'][$data->metode_perolehan];
                if(trim($format)=="array"){
					$data_sapi[] = (array) $rec;
				}else{
					$data_sapi[]	= $rec;
				}		
                
                
            }
					
			return $data_sapi;
		}
	}
    public function checkCowByEartag($no_eartag,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		if(trim($no_eartag)=="" or  $no_eartag == null){
			return array();
		}else{
		   $master = new Master_Ref_Model();
		    $referensi	= $master->referensi_session();
            $no_eartag = strtoupper($no_eartag);
			$filter="UPPER(name)='".$no_eartag."'";
            $data= $db->select("id,name no_eartag,koloni_name","cow",$format)->where($filter)->get();
           	if(!empty($data)){
           	    return $data;
			}else{
				return array();
			}
		}
	}
    public function getCowByMember($member_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		if(trim($member_id)=="" or  $member_id == null){
			return array();
		}else{
		   $master = new Master_Ref_Model();
		    $referensi	= $master->referensi_session();
          //  echo "<pre>";print_r($referensi);echo "</pre>";
			$filter="anggota_id=".$member_id." and ifnull(c.afkir,0)=0 and ifnull(c.is_active,0)=1 and ifnull(c.is_need_verification,0)=0";
            $data_qry= $db->select("c.id,c.name no_eartag,koloni_name,birthplace,birthdate,DATE_FORMAT(birthdate,'%d/%m/%Y') birthdate,
            tanggal_identifikasi,laktasi_ke,type,tipe,
            datediff(current_date(),birthdate) as usia,current_date() as tgl_sekarang,posisi_eartag,metode_perolehan,
            state_reproduction,state_lactation,
            c.barcode,photo_path,created_time,created_by,c.is_active,anggota_id","cow c")->where($filter)
            ->orderby("c.tipe desc")->lim();
            $data_sapi=array();
            while($data = $db->fetchObject($data_qry))
            {
                
                $rec    	   = new stdClass;
                $rec->NoEartag          = $data->no_eartag;
                $rec->Nama              = $data->koloni_name;
                $rec->LaktasiKe          = $data->laktasi_ke;
                $rec->PosisiEartag      = $data->posisi_eartag;
               // $tipe      = (trim($data->tipe)=="" and trim($data->type)<>"")?array_search($data->type,$referensi['tipe_sapi']):$data->tipe;
               
                $rec->tipe_nama      = $referensi['tipe_sapi'][$data->tipe];
                $rec->CaraPerolehanNama	= $referensi['metode_perolehan'][$data->metode_perolehan];
                $rec->StatusReproduksiID		= $data->state_reproduction;
                $rec->StatusReproduksi		= $referensi['status_reproduksi'][$data->state_reproduction];
                $rec->StatusLaktasiID		= $data->state_lactation;
                $rec->StatusLaktasi		= $referensi['status_laktasi'][$data->state_lactation];
                $rec->tanggal_identifikasi          = $data->tanggal_identifikasi;
                $rec->TanggalIdentifikasi      = $master->detailtanggal($data->tanggal_identifikasi,2) ;
                if(trim($format)=="array"){
                    $result = array_merge((array)$data, (array) $rec);
					$data_sapi[] = (array) $rec;
				}else{
					$data_sapi[]	= $rec;
				}		
                
                
            }
					
			return $data_sapi;
		}
	}
    
    public function getAllCowByMember($member_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		if(trim($member_id)=="" or  $member_id == null){
			return array();
		}else{
		   $master = new Master_Ref_Model();
		    $referensi	= $master->referensi_session();
          //  echo "<pre>";print_r($referensi);echo "</pre>";
			$filter="anggota_id=".$member_id."";
            $data_qry= $db->select("c.id,c.name no_eartag,koloni_name,birthplace,birthdate,DATE_FORMAT(birthdate,'%d/%m/%Y') birthdate,
            tanggal_identifikasi,laktasi_ke,type,tipe,
            datediff(current_date(),birthdate) as usia,current_date() as tgl_sekarang,posisi_eartag,metode_perolehan,
            state_reproduction,state_lactation,
            c.barcode,photo_path,created_time,created_by,c.is_active,anggota_id","cow c")->where($filter)
            ->orderby("c.tipe asc")->lim();
            $data_sapi=array();
            while($data = $db->fetchObject($data_qry))
            {
                
                $rec    	   = new stdClass;
                
                $rec->tipe_nama      = $referensi['tipe_sapi'][$data->tipe];
                $rec->CaraPerolehanNama	= $referensi['metode_perolehan'][$data->metode_perolehan];
                $rec->CaraPerolehanNama	= $referensi['metode_perolehan'][$data->metode_perolehan];
                $rec->CaraPerolehanNama	= $referensi['metode_perolehan'][$data->metode_perolehan];
                $rec->tanggal_identifikasi          = $data->tanggal_identifikasi;
                $rec->TanggalIdentifikasi      = $master->detailtanggal($data->tanggal_identifikasi,2) ;
                 $rec->StatusReproduksiID		= $data->state_reproduction;
                $rec->StatusReproduksi		= $referensi['status_reproduksi'][$data->state_reproduction];
                $rec->StatusLaktasiID		= $data->state_lactation;
                $rec->StatusLaktasi		= $referensi['status_laktasi'][$data->state_lactation];
                 $rec->Afkir            =$data->afkir;
                if(trim($format)=="array"){
                    $result = array_merge((array)$data, (array) $rec);
					$data_sapi[] = (array) $result;
				}else{
				    $result = array_merge((array)$data, (array) $rec);
					$data_sapi[]	= (object)$result;
				}		
                
                
            }
					
			return $data_sapi;
		}
	}
    public function getPejantan($id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		if(trim($id)=="" or  $id == null){
			return array();
		}else{
			$filter="id=".$id."";
            $data= $db->select("id,no_pejantan,nama,asal,birthdate,DATE_FORMAT(birthdate,'%d/%m/%Y') TanggalLahir,
                beli_dari,id_dam,nama_dam,id_sire,nama_sire,
                asal_sire,id_mgs,nama_mgs,id_ggs,nama_ggs","keswan_pejantan")->where($filter)
				->get(0);

					
			if(!empty($data)){
			     return $data;
			}else{
				return array();
			}
		}
	}
	public function json($category="list_sapi",$query="",$array_value=array()) {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        $admin  = new Core_Admin_Model();
        $List   = array();
        $filter = "";
        switch($category){
            case "list_sapi":
                
                $search=$admin->SearchDependingLevel("tpk","kp.pID");
            
                
                if(trim($query)<>""){
            	   $filter="(c.name like '%".$query."%' or c.name='".$query."' or ang.C_ANGGOTA like '%".$query."%' or ang.NAMA like '%".$query."%')";
                }
                if(!empty($array_value)){
                    if(isset($array_value['no_anggota']) and trim($array_value['no_anggota'])<>""){
                        $filter=trim($filter)<>""?$filter." and ang.C_ANGGOTA='".$array_value['no_anggota']."'":"ang.C_ANGGOTA='".$array_value['no_anggota']."'";
                    }
                    if(isset($array_value['kelompok']) and trim($array_value['kelompok'])<>""){
                        $filter=trim($filter)<>""?$filter." and ang.ID_KELOMPOK=".$array_value['kelompok']."":"ang.ID_KELOMPOK=".$array_value['kelompok']."";
                    }
                    if(isset($array_value['active']) and trim($array_value['active'])<>""){
                        $filter=trim($filter)<>""?$filter." and ifnull(is_active,'')=".$array_value['active']."":"ifnull(is_active,'')=".$array_value['active']."";
                    }
                    if(isset($array_value['afkir']) and trim($array_value['afkir'])<>""){
                        $filter=trim($filter)<>""?$filter." and ifnull(afkir,0)=".$array_value['afkir']."":"ifnull(afkir,0)=".$array_value['afkir']."";
                    }
                }
        	    $filter=trim($filter)<>""?$filter." and ifnull(afkir,0)=0":"ifnull(afkir,0)=0";
                $listdata= $db->select("c.id,c.name,ifnull(is_active,0) is_active,ang.C_ANGGOTA,ang.NAMA","cow c
                inner join anggota ang on ang.ID_ANGGOTA=c.anggota_id
                left join kelompok kel on kel.id=ang.ID_KELOMPOK")->where($filter)->get();
        		$i=0;
                if(!empty($listdata)){
            		while($data=current($listdata)){
            		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
                        $active=$data->is_active==1?"Aktif":"Non-Aktif";
            		    $List[$i]['ID']=$data->id;
            		    $List[$i]['NoEartag']=$data->name;
            		    $List[$i]['Lengkap']=$data->name."<br />\n[".$data->C_ANGGOTA."] ".$data->NAMA."";
            		    $i++;
            		    next($listdata);
            		}
                }
               
          break;
            case "list_pejantan":
                if(trim($query)<>""){
            	   $filter="nama like '%".$query."%' or no_pejantan='".$query."' or asal='".$query."'";
                }
        	  
                $listdata= $db->select("id,no_pejantan,nama,asal,birthdate,DATE_FORMAT(birthdate,'%d/%m/%Y') TanggalLahir,
                beli_dari,id_dam,nama_dam,id_sire,nama_sire,
                asal_sire,id_mgs,nama_mgs,id_ggs,nama_ggs","keswan_pejantan")->where($filter)->get();
        		$i=0;
                if(!empty($listdata)){
            		while($data=current($listdata)){
            		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
            		    $List[$i]['ID']=$data->id;
            		    $List[$i]['NoPejantan']=$data->no_pejantan;
            		    $List[$i]['Nama']=$data->nama;
            		    $List[$i]['Lengkap']="[".$data->no_pejantan."] ".$data->nama."<br />\nAsal : ".$data->asal."";
            		    $i++;
            		    next($listdata);
            		}
                }
               
          break;
          case "pemilik":
                if(trim($query)<>""){
            	   $filter="NAMA like '%".$query."%' or C_ANGGOTA='".$query."'";
                }
        	    $listdata= $db->select("ID_ANGGOTA,NAMA,C_ANGGOTA","anggota")->where($filter)->get();
        		$i=0;
                if(!empty($listdata)){
            		while($data=current($listdata)){
            		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
            		    $List[$i]['ID']=$data->ID_ANGGOTA;
            		    $List[$i]['IDAnggota']=$data->C_ANGGOTA;
            		    $List[$i]['Nama']=$data->NAMA;
            		    $List[$i]['Lengkap']="<div class='label_typeahead'>[".$data->C_ANGGOTA."] ".$data->NAMA."<br />Test aja</div>";
            		    $i++;
            		    next($listdata);
            		}
                }
                if(trim($query)<>""){
            	   $filter="name like '%".$query."%' or id='".$query."'";
                }
        	    $listdata2= $db->select("id,name","cow_ownership")->where($filter)->get();
                if(!empty($listdata2)){
        		while($data2=current($listdata2)){
        		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
        		    $List[$i]['ID']=$data2->id;
        		    //$List[$i]['IDAnggota']=$data->C_ANGGOTA;
        		    $List[$i]['name']=$data2->name;
        		    $List[$i]['Lengkap']="<div class='label_typeahead'>".$data2->name."<br />Test aja</div>";
        		    $i++;
        		    next($listdata2);
        		}
                }
          break;
      }
      return $List;
    } 

 
 
}
?>