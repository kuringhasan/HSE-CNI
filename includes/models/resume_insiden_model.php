<?php
/**
 * @package Resume Insiden
 * 
 * @author Abdiiwan <abdiiwan1841@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Resume_Insiden_Model extends Model {
	
	public function __construct() {
		
	}

	public function getInsiden($id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($id)=="" or  $id == null){
			return array();
		}else{
		    $referensi	= $_SESSION["referensi"];
			$filter="id_resume=".$id."";
            $list_qry=$db->select("*","resume_insiden")
    		->where($filter)->lim(0);//->orderBy($order)
           
                $data = $db->fetchObject($list_qry);
                $hasil=array();
                if(!empty($data)){
                
                    $rec    	   		= new stdClass;
                    $rec->id            = $data->id_resume;
					$rec->tanggal       = $data->tanggal;
					$rec->no_register   = $data->no_register;
					$rec->tanggal		= $data->tanggal;
					$rec->nik			= $data->nik;
					$rec->nama			= $data->nama_pelaku_korban;
					$rec->jabatan		= $data->kode_jabatan;
					$rec->atasan		= $data->atasan_langsung;
					$rec->departemen	= $data->kode_departemen;
					$rec->masakerja		= $data->kode_masa_kerja;
					$rec->umur			= $data->kode_umur;
					$rec->shiftkerja	= $data->kode_shift;
					$rec->areakerja		= $data->kode_area_kerja;
					$rec->alat			= $data->kode_alat_terlibat;
					$rec->luka			= $data->kode_bagian_luka;
					$rec->jenisinsiden	= $data->kode_insiden;
					$rec->carakerja		= $data->kode_cara_kerja;
					$rec->kondisikerja	= $data->kode_tidak_standar;
					$rec->faktorkerja	= $data->kode_faktor_pekerjaan;
					$rec->faktorpribadi	= $data->kode_faktor_personil;
					$rec->perbaikan		= $data->kode_tindakan_perbaikan;
					$rec->sanksi		= $data->kode_sanksi;
					$rec->harihilang	= $data->kode_hari_kerja_hilang;
					$rec->perkiraanbiaya= $data->kode_biaya_perbaikan_unit;
					$rec->keterangan	= $data->deskripsi;
                    
                    if(trim($format)=="array"){
    					$hasil = (array) $rec;
    				}else{
    					$hasil	= $rec;
    				}
                }
					
			return $hasil;
		}
	}
}
?>