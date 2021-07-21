<?php

 
defined("PANDORA") OR die("No direct access allowed.");

class Ref_Kontraktor_Model extends Model {
	
	public function __construct() {
		
	}
	public function getDetailRefKontraktor($id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");

		if(trim($id)=="" or  $id == null){
			return array();
		}else{
		    $referensi	= $_SESSION["referensi"];
			$filter="id=".$id."";
            $list_qry=$db->select("*","partner")
    		->where($filter)->lim(0);//->orderBy($order)
           
			$data = $db->fetchObject($list_qry);
			$hasil=array();
			if(!empty($data)){
			
				$rec    	   				= new stdClass;
				$rec->id            		= $data->id;
                $rec->nik       			= $data->nik;
                $rec->is_company   		    = $data->is_company;
                $rec->is_contractor			= $data->is_contractor;
                $rec->name				    = $data->name;
                $rec->code		            = $data->code;
                $rec->no_kk			        = $data->no_kk;
                $rec->alias		            = $data->alias;
                $rec->gelar_depan		    = $data->gelar_depan;
                $rec->gelar_belakang		= $data->gelar_belakang;
                $rec->tempat_lahir		    = $data->tempat_lahir;
                $rec->tempat_lahir_lain		= $data->tempat_lahir_lain;
                $rec->tanggal_lahir		    = $data->tanggal_lahir;
                $rec->agama		            = $data->agama;
                $rec->gender		        = $data->gender;
                $rec->kewarganegaraan		= $data->kewarganegaraan;
                $rec->golongan_darah		= $data->golongan_darah;
                $rec->pJenisTandaPengenal	= $data->pJenisTandaPengenal;
                $rec->alamat		        = $data->alamat;
                $rec->alamat_rt		        = $data->alamat_rt;
                $rec->alamat_rw		        = $data->alamat_rw;
                $rec->phone		            = $data->phone;
                $rec->alamat_kecamatan		= $data->alamat_kecamatan;
                $rec->alamat_desa		    = $data->alamat_desa;
                $rec->alamat_kabupaten		= $data->alamat_kabupaten;
                $rec->email		            = $data->email;
                $rec->file_foto		        = $data->file_foto;
                $rec->kodepos		        = $data->kodepos;
                $rec->telepon		        = $data->telepon;
                $rec->npwp		            = $data->npwp;
                $rec->step		            = $data->step;
                $rec->last_update		    = $data->last_update;
                $rec->reg_step		        = $data->reg_step;
                $rec->reg_lastupdate		= $data->reg_lastupdate;
                $rec->status_pernikahan		= $data->status_pernikahan;
                $rec->nama_pasangan		    = $data->nama_pasangan;
                $rec->created		        = $data->created;
                $rec->odoo_id		        = $data->odoo_id;
                $rec->rgb_color		        = $data->rgb_color;
                $rec->active		        = $data->active;
				
				
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