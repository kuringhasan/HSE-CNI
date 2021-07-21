<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage PDF Helper
 * 
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class pdf {
	
	public static function build($orientation='P', $unit='mm', $format='A4') {
		$path = "plugins/fpdf/fpdf.php";
		if(file_exists($path)) {
			include $path;
			return new FPDF($orientation, $unit, $format);
		} else {
			Core::fatalError("FPDF Plugin not found!");
		}
	}
	
}
?>