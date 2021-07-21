<?php
/**
 * @package Mahasiswa
 * @subpackage Fakultas Model
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Nomor_Otomatis_Model extends Model {
	
	public function __construct() {
	   }
   public function idBaru($nmFieldID="",$nmTabel="") {
	global $dcistem;
		$db   = $dcistem->getOption("framework/db");
	   $max_ID  = $db-> select("MAX($nmFieldID) AS maxID",$nmTabel)->get(0);		
		 $IDBaru=$max_ID->maxID +1;
       
		return $IDBaru;
		
	
	}
 
   public function cekKode($fields="",$kondisi="",$nmTabel="") {
	global $dcistem;
		$db   = $dcistem->getOption("framework/db");
	   $cekKode  = $db-> select($fields,$nmTabel,"row")->where($kondisi)->get();		
		 if ($cekKode>=1)
         {
            $keterangan="Kode sudah digunakan";
         }
         else
         {
            $keterangan="Kode belum digunakan";
         }
        
        
		return $keterangan;
		
	
	}
 public function maxNoUrut3fromRight($fields="",$kondisi="",$nmTabel="") {
	global $dcistem;
		$db   = $dcistem->getOption("framework/db");
	    $nodaftar=$db->select("max(RIGHT(".$fields.",3)) as max",$nmTabel)->where($kondisi)->Get(0);
                    
                    $max=(int)$nodaftar->max;
                    $next=(int)$nodaftar->max+1;
                    $nomax=substr("000".(string)$max,-3,3);
                    $nonext=substr("000".(string)$next,-3,3);
                   
        
        
		return array("NoMax"=>$nomax,"NoNext"=>$nonext);
		
	
	}
	
 	
}
?>