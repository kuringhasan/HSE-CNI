<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Karyawan Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Ref_Kabupaten_Model extends Model {
	
	public function __construct() {
	
	}

    public function getKabupaten($id){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
       	if(trim($id)=="" or  $id == null){
			return array();
		}else{
            $data=$db->select("kabupatenKode,kabupatenPropinsiKode,kabupatenNamaSaja,KabupatenNama,kabupatenJenis,kabupatenJenisSingkat","tbrkabupaten")
    		->where("kabupatenKode=$id")->get(0);//
            return $data;
        }	 
	}
    
}

?>