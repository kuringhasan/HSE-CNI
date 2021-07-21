<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Karyawan Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Adm_Imei_Model extends Model {
	
	public function __construct() {
	
	}
   

    public function checkImei($imei) {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        
		if(trim($imei)=="" or  $imei == null){
			return array();
		}else{
			$filter="imei='".$imei."'";
          
            $list_qry=$db->select("imei,desciption,created_time","device_imei")->where($filter)->lim(0);//->orderBy($order)
           
            $hasil = $db->fetchObject($list_qry);
          
			return $hasil;
		}
	}
    
	
}
?>