<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage PDF Helper
 * 
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class htmlpdf {
	
	public static function build($orientation='P', $format='A4', $margin=array(15, 5, 15, 5)) {
		$path = "plugins/html2pdf/html2pdf.class.php";
		if(file_exists($path)) {
		    require_once('plugins/html2pdf/_mypdf/mypdf.class.php');	// classe mypdf
	        require_once('plugins/html2pdf/parsingHTML.class.php');	// classe de parsing HTML
	        require_once('plugins/html2pdf/styleHTML.class.php');		// classe de gestion des styles  
			include $path;
			return new HTML2PDF($orientation,$format, 'en', false, 'ISO-8859-15',$margin);
		} else {
			Core::fatalError("HTML FPDF Plugin not found!");
		}
	}
	
}
?>