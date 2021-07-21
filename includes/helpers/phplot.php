<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage PDF Helper
 * 
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class phplot {

	public static function build($lebar=600, $tinggi=400, $which_output_file=NULL, $which_input_file=NULL) {	
	
		$path = "plugins/phplot/phplot.php";
	
		if(file_exists($path)) {
			include "plugins/phplot/phplot.php";
			
			return new PHPlot($lebar, $tinggi, $which_output_file, $which_input_file);
		} else {
			Core::fatalError("Phplot Plugin not found!");
		}
	}
	
}
?>