<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Barcode Helper
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class barcode {

	public static function build($barcode, $file = false, $anticopy = false, $height = 50) {
		$barcode = text::filter($barcode, "ucase num space *$%/+");
		if(!$file) {
			Header("Content-Type: image/png;");
		}
		if($height < 30) {
			$height = 30;
		}
		$w     = 16*(strlen($barcode)+2)+10 ;
		$h     = $height;
		$im    = imagecreate($w,$h);
		$white = imagecolorallocate($im,255,255,255);
		$black = imagecolorallocate($im,0,0,0);
		$red   = imagecolorallocate($im,255,0,0);
		imagefill($im,0,0,($anticopy ? $red : $white));

		$code = "*".$barcode."*";
		$x = 5;
		for($i=0;$i<strlen($code);$i++) {
			$bar = self::getPatern($code[$i]);
			if($bar<>"") {
				for($j=0;$j<strlen($bar);$j++) {
					switch($bar[$j]) {
						case "b" :
							$s = 1;	$color = "black";
							break 1;
						case "w" :
							$s = 1;	$color = ($anticopy ? "red" : "white");
							break 1;
						case "B" :
							$s = 3;	$color = "black";
							break 1;
						case "W" :
							$s = 3;	$color = ($anticopy ? "red" : "white");
							break 1;
					}
					for($k=0;$k<$s;$k++) {
						imageline($im,$x,5,$x,$height - 5,$$color);
						$x++;
					}
				}
				$x++;
			}
		}
		imagefilledrectangle($im, ($w - 5 * strlen($code)) / 2, $h - 8 , ($w + 5 * strlen($code)) / 2, $h, ($anticopy ? $red : $white));
		imagestring($im, 1, ($w - 5 * strlen($barcode)) / 2, $h - 8, $barcode, $black);
		$path = "";
		if($file) {
			global $dcistem;
			$path = $dcistem->getOption("system/dir/temp")."barcode_".md5(serialize(func_get_args())).".png";
		}
		imagepng($im,$path,0);
		return $path;
	}

	public static function getPatern($char) {
		$c["*"]="bWbwBwBwb";
		$c["-"]="bWbwbwBwB";
		$c["$"]="bWbWbWbwb";
		$c["%"]="bwbWbWbWb";
		$c[" "]="bWBwbwBwb";
		$c["."]="BWbwbwBwb";
		$c["/"]="bWbWbwbWb";
		$c["+"]="bWbwbWbWb";
		$c["0"]="bwbWBwBwb";
		$c["1"]="BwbWbwbwB";
		$c["2"]="bwBWbwbwB";
		$c["3"]="BwBWbwbwb";
		$c["4"]="bwbWBwbwB";
		$c["5"]="BwbWBwbwb";
		$c["6"]="bwBWBwbwb";
		$c["7"]="bwbWbwBwB";
		$c["8"]="BwbWbwBwb";
		$c["9"]="bwBWbwBwb";
		$c["A"]="BwbwbWbwB";
		$c["B"]="bwBwbWbwB";
		$c["C"]="BwBwbWbwb";
		$c["D"]="bwbwBWbwB";
		$c["E"]="BwbwBWbwb";
		$c["F"]="bwBwBWbwb";
		$c["G"]="bwbwbWBwB";
		$c["H"]="BwbwbWBwb";
		$c["I"]="bwBwbWBwb";
		$c["J"]="bwbwBWBwb";
		$c["K"]="BwbwbwbWB";
		$c["L"]="bwBwbwbWB";
		$c["M"]="BwBwbwbWb";
		$c["N"]="bwbwBwbWB";
		$c["O"]="BwbwBwbWb";
		$c["P"]="bwBwBwbWb";
		$c["Q"]="bwbwbwBWB";
		$c["R"]="BwbwbwBWb";
		$c["S"]="bwBwbwBWb";
		$c["T"]="bwbwBwBWb";
		$c["U"]="BWbwbwbwB";
		$c["V"]="bWBwbwbwB";
		$c["W"]="BWBwbwbwb";
		$c["X"]="bWbwBwbwB";
		$c["Y"]="BWbwBwbwb";
		$c["Z"]="bWBwBwbwb";
		return $c[$char];
	}

}
?>