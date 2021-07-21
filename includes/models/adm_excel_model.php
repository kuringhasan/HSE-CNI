<?php
/**
 * @package Mahasiswa
 * @subpackage Excel Model
 * 
 * @author ALan RIdwan
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Header_Excel_Model extends Model {
	
	public function __construct() {
		
	}
   public function cek_headerPesertaDiterima($format="",$sheetData="") {
      $result = false;
       //echo "cek".$sheetData[3]['G'];echo "<pre>";print_r($sheetData);echo "</pre>";exit;
      if ($format=="") {
          
            $result =  (strtoupper($sheetData[3]['A'])=='NO')?true:false;
           
            $result =  (strtoupper($sheetData[3]['B'])=='NO_PESERTA')?true:false;
            $result =  (strtoupper($sheetData[3]['C'])=='NAMA_PESERTA') ?true:false;
            $result =  (strtoupper($sheetData[3]['D'])=='KODE_PRODIDITERIMA')?true:false;
            $result =  (strtoupper($sheetData[3]['E'])=='NAMA_PRODIDITERIMA')?true:false;            
            $result =  (strtoupper($sheetData[3]['F'])=='KD_KONSENTRASI')?true:false;
            $result =  (strtoupper($sheetData[3]['G'])=='NM_KONSENTRASI')?true:false;
            $result =  (strtoupper($sheetData[3]['H'])=='PRODIKELAS')?true:false; //echo $result."cek".$sheetData[3]['G'];
            $result =  (strtoupper($sheetData[3]['I'])=='KODE_JALURMASUK')?true:false;
            $result =  (strtoupper($sheetData[3]['J'])=='TAHUN_MASUK')?true:false;
            $result =  (strtoupper($sheetData[3]['K'])=='GELOMBANG')?true:false;
      }
    
     
      return $result;
   }
   public function cek_buat_tagihan($sheetData="") {
      $result = false;
      $result =  (strtoupper($sheetData[3]['A'])=='NO')?true:false;
      $result =  (strtoupper($sheetData[3]['B'])=='NAMA')?true:false;
      $result =  (strtoupper($sheetData[3]['C'])=='NO_IDENTITAS')?true:false;
      $result =  (strtoupper($sheetData[3]['D'])=='TAHUN') ?true:false;
      $result =  (strtoupper($sheetData[3]['E'])=='KODE_JADWAL')?true:false;
      return $result;
   } 
}
?>