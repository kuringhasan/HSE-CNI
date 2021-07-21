<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage PDF Helper
 * 
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class excelwriter {
	 
   public function build($namafile)
    {
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
         
        // header untuk nama file
        header("Content-Disposition: attachment;filename=".$namafile."");
        header("Content-Transfer-Encoding: binary ");
    }
    
    
   public function xlsBOF() {
        echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
        return;
    }
   public function xlsEOF() {
        echo pack("ss", 0x0A, 0x00);
        return;
    }
  public  function xlsWriteLabel($Row, $Col, $Value ) {
        $L = strlen($Value);
        echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
        echo $Value;
        return;
    }
	
}
?>