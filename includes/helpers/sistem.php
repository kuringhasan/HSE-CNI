<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Text Helper
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class sistem {	
	public function getOS() { 
	    $user_agent= $_SERVER['HTTP_USER_AGENT'];	
	    $os_platform    =   array('os'=>'Unknown',"platform"=>"Unknown");	
	    $os_array       =   array(
                            '/windows nt 10/i'      => array('os'=>'Windows 10',"platform"=>"Win"),
                            '/windows nt 6.3/i'     => array('os'=>'Windows 8.1',"platform"=>"Win"),
                            '/windows nt 6.2/i'     =>  array('os'=>'Windows 8',"platform"=>"Win"),
                            '/windows nt 6.1/i'     =>  array('os'=>'Windows 7',"platform"=>"Win"),
                            '/windows nt 6.0/i'     =>  array('os'=>'Windows Vista',"platform"=>"Win"),
                            '/windows nt 5.2/i'     =>  array('os'=>'Windows Server 2003/XP x64',"platform"=>"Win"),
                            '/windows nt 5.1/i'     =>  array('os'=>'Windows XP',"platform"=>"Win"),
                            '/windows xp/i'         =>  array('os'=>'Windows XP',"platform"=>"Win"),
                            '/windows nt 5.0/i'     =>  array('os'=>'Windows 2000',"platform"=>"Win"),
                            '/windows me/i'         =>  array('os'=>'Windows ME',"platform"=>"Win"),
                            '/win98/i'              =>  array('os'=>'Windows 98',"platform"=>"Win"),
                            '/win95/i'              =>  array('os'=>'Windows 95',"platform"=>"Win"),
                            '/win16/i'              =>  array('os'=>'Windows 3.11',"platform"=>"Win"),
                            '/macintosh|mac os x/i' =>  array('os'=>'Mac OS X',"platform"=>"Mac"),
                            '/mac_powerpc/i'        =>  array('os'=>'Mac OS 9',"platform"=>"Mac"),
                            '/linux/i'              =>  array('os'=>'Linux',"platform"=>"Linux"),
                            '/ubuntu/i'             =>  array('os'=>'Ubuntu',"platform"=>"Ubuntu"),
                            '/iphone/i'             =>  array('os'=>'iPhone',"platform"=>"iPhone"),
                            '/ipod/i'               =>  array('os'=>'iPod',"platform"=>"iPod"),
                            '/ipad/i'               =>  array('os'=>'iPad',"platform"=>"iPad"),
                            '/android/i'            =>  array('os'=>'Android',"platform"=>'Android'),
                            '/blackberry/i'         =>  array('os'=>'BlackBerry',"platform"=>'BlackBerry'),
                            '/webos/i'              =>  array('os'=>'Mobile',"platform"=>'Mobile')
                        );

	    foreach ($os_array as $regex => $value) { 
	        if (preg_match($regex, $user_agent)) {
	            $os_platform    =   $value;
	        }
	    }   

	    return $os_platform;
	}
	
	public function getBrowser() {
	
	    $user_agent= $_SERVER['HTTP_USER_AGENT'];
	    $browser        =   "Unknown Browser";
	    $browser_array  =   array(
	                            '/msie/i'       =>  'Internet Explorer',
	                            '/firefox/i'    =>  'Firefox',
	                            '/safari/i'     =>  'Safari',
	                            '/chrome/i'     =>  'Chrome',
	                            '/edge/i'       =>  'Edge',
	                            '/opera/i'      =>  'Opera',
	                            '/netscape/i'   =>  'Netscape',
	                            '/maxthon/i'    =>  'Maxthon',
	                            '/konqueror/i'  =>  'Konqueror',
	                            '/mobile/i'     =>  'Handheld Browser'
	                        );
	
	    foreach ($browser_array as $regex => $value) { 
	
	        if (preg_match($regex, $user_agent)) {
	            $browser    =   $value;
	        }
	
	    }
	
	    return $browser;
	
	}
}
?>