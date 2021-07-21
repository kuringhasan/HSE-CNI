<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage PDF Helper
 * 
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class mpdf60 {
	
public static function build($orientation='P', $format='A4', $margin=array(15, 5, 15, 5)) {
		$path = "plugins/mpdf60/mpdf.php";
		if(file_exists($path)) {
			include $path;
			return new mPDF('c',$format,'','',$margin[0],$margin[1],$margin[2],$margin[3],0,$orientation);
		} else {
			Core::fatalError("HTML MPDF Plugin not found!");
		}
	}
	
}
?>