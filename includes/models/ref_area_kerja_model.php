<?php
 
defined("PANDORA") OR die("No direct access allowed.");

class Ref_area_kerja_Model extends Model {
	
	public function __construct() {
		
	}
	public function getDetailRefArea($id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");

		if(trim($id)=="" or  $id == null){
			return array();
		}else{
		    $referensi	= $_SESSION["referensi"];
			$filter="kode=".$id."";
            $list_qry=$db->select("*","ref_area_kerja")
    		->where($filter)->lim(0);//->orderBy($order)
           
			$data = $db->fetchObject($list_qry);
			$hasil=array();
			if(!empty($data)){
			
				$rec    	   				= new stdClass;
				$rec->kode            		= $data->kode;
                $rec->nama_area       = $data->nama_area;
                
				
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