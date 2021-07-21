<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Damas Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Ref_Kasus_Model extends Model {
  
	public function __construct() {
		
	}
    public function insert($no_eartag,$posisi_eartag,$koloni_name,$tanggal_identifikasi,$tanggal_lahir,$anggota_id,$tipe,$cow_induk,$cow_bapak,$jenis_kelamin,$metode_perolehan,$active,$need_verification="") {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master	    = new  Master_Ref_Model();
        $referensi      = $master->referensi_session();
        $hasil      =array();
        $TglSkrg	=date("Y-m-d H:i:s");
        $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
        
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
        
        $colsc="name,koloni_name,birthdate,barcode,is_active,anggota_id,
        type,tipe,metode_perolehan,posisi_eartag,tanggal_identifikasi,induk,
        bapak,gender,created_time,last_update,is_need_verification";
	    $valuesc="$no_eartag_val,$koloni_name_val,$tanggal_lahir_val,$no_eartag_val,$active_val,$anggota_id_val,
        $tipe_name_val,$tipe_val,$metode_perolehan_val,$posisi_eartag_val,$tanggal_identifikasi_val,$cow_induk_val,$cow_bapak_val,$jenis_kelamin_val,
        $tgl_skrg_val,$tgl_skrg_val,$need_verification_val";
        $sqlinc="INSERT INTO cow ($colsc) VALUES ($valuesc);";
        $rslc=$db->query($sqlinc);
        if(isset($rslc->error) and $rslc->error===true){
   	 		$hasil['success']=false;
        	$hasil['message']="Error, ".$rslc->query_last_message;
	    }else{
	        $sapi   =$db->query("SELECT LAST_INSERT_ID() as new_id;");
            $new_sapi    =$db->fetchArray($sapi);
            $hasil['success']=true;
            $hasil['new_id']=$new_sapi['new_id'];
           	$hasil['message']="Data sapi sudah ditambahkan ";
        }
        return $hasil;
    }
    public function getKasus($id) {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($id)=="" or  $id == null){
			return array();
		}else{
		      $filter="KasusID=".$id."";
            $data= $db->select("KasusID,KasusPenyakit,KasusSubsistem,KasusInactive","keswan_kasus_penyakit")
            ->where($filter)->get(0);

					
			if(!empty($data)){
			
				if(trim($format)=="array"){
					$result = (array) $data;
					return $result;
				}else{
					$result	= (object) $data;
					return $result;
				}				
			
			}else{
				return array();
			}
		}
	}
    
	public function json($category="list_obat",$query="",$array_value=array()) {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        $List   = array();
        $filter = "";
        switch($category){
            case "list_kasus":
                if(trim($query)<>""){
            	   $filter="(KasusPenyakit like '%".$query."%' or KasusPenyakit='".$query."' or SubNama like '%".$query."%' or SubNama = '".$query."')";
                }
                if(!empty($array_value)){
                   
                    if(isset($array_value['inactive']) and trim($array_value['inactive'])<>""){
                        $filter=trim($filter)<>""?$filter." and ifnull(ksp.KasusInactive,0)=".$array_value['inactive']."":"ifnull(ksp.KasusInactive,0)=".$array_value['inactive']."";
                    }
                   
                }
                $listdata= $db->select("KasusID,KasusPenyakit,KasusSubsistem KodeSubsistem,SubNama NamSubSistem,KasusInactive","keswan_kasus_penyakit ksp
                left join keswan_kasus_subsistem kks on kks.SubID=ksp.KasusSubsistem")->where($filter)->get();
        		$i=0;
                if(!empty($listdata)){
            		while($data=current($listdata)){
      		            $lengkap=trim($data->NamSubSistem)==""?$data->KasusPenyakit:$data->KasusPenyakit." - ".$data->NamSubSistem;
            		    $List[$i]['ID']=$data->KasusID;
            		    $List[$i]['Nama']=$data->KasusPenyakit;
            		    $List[$i]['Lengkap']=$lengkap;
            		    $i++;
            		    next($listdata);
            		}
                }
               
          break;
          
      }
      return $List;
    } 

 
 
}
?>