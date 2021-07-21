<?php
/**
 * @package Insiden
 * 
 * @author Abdiiwan <abdiiwan1841@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Form_Insiden_Model extends Model {
	
	public function __construct() {
		
	}

	public function getFormInsiden($id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");

		if(trim($id)=="" or  $id == null){
			return array();
		}else{
		    $referensi	= $_SESSION["referensi"];
			$filter="id_insiden=".$id."";
            $list_qry=$db->select("*","data_insiden")
    		->where($filter)->lim(0);//->orderBy($order)
           
			$data = $db->fetchObject($list_qry);
			$hasil=array();
			if(!empty($data)){
			
				$rec    	   				= new stdClass;
				$rec->id_insiden            = $data->id_insiden;
				$rec->tanggal_insiden       = $data->tanggal_insiden;
				$rec->nama_pelapor   		= $data->nama_pelapor;
				$rec->kode_company			= $data->kode_company;
				$rec->lokasi				= $data->lokasi;
				$rec->jenis_kecelakaan		= $data->jenis_kecelakaan;
				$rec->jumlah_korban			= $data->jumlah_korban;
				$rec->tingkat_keparahan		= $data->tingkat_keparahan;
				$rec->bantuan				= $data->bantuan;
				$rec->namafile				= $data->namafile;
				$rec->created				= $data->created;
				$rec->created_by				= $data->created_by;
				$rec->updated				= $data->updated;
				$rec->updated_by				= $data->updated_by;
				
				
				if(trim($format)=="array"){
					$hasil = (array) $rec;
				}else{
					$hasil	= $rec;
				}
			}
					
			return $hasil;
		}
	}

	public function getDetailInsiden($id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");

		if(trim($id)=="" or  $id == null){
			return array();
		}else{
		    $referensi	= $_SESSION["referensi"];
			$filter="id_insiden=".$id."";
            $list_qry=$db->select("id_insiden,nama_pelapor,lokasi,
			tanggal_insiden,DATE_FORMAT(tanggal_insiden,'%d/%m/%Y') tanggal_absen, DATE_FORMAT(tanggal_insiden,'%d/%m/%Y %H:%i:%s') check_in,
			jenis_kecelakaan,jumlah_korban,tingkat_keparahan,bantuan,nama_kecelakaan,keterangan,namafile
			","data_insiden a
			left join ref_jenis_kecelakaan_kerja b on b.kode=a.jenis_kecelakaan
			left join ref_tingkat_keparahan c on c.kode=a.tingkat_keparahan")
    		->where($filter)->lim(0);//->orderBy($order)
           
			$data = $db->fetchObject($list_qry);
			$hasil=array();
			if(!empty($data)){
			
				$rec    	   				= new stdClass;
				$rec->tanggal_insiden       = $data->tanggal_insiden;
				$rec->nama_pelapor   		= $data->nama_pelapor;
				$rec->nama_company			= $data->nama_company;
				$rec->lokasi				= $data->lokasi;
				$rec->jenis_kecelakaan		= $data->nama_kecelakaan;
				$rec->jumlah_korban			= $data->jumlah_korban;
				$rec->tingkat_keparahan		= $data->keterangan;
				$rec->bantuan				= $data->bantuan;
				$rec->namafile				= $data->namafile;
				
				
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