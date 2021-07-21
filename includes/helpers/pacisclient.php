<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Pacis Client untuk Api
 *
 * @author Marindra Dhani <marindra@unpad.ac.id>
*/

defined("PANDORA") OR die("No direct access allowed.");

class pacisclient {

	public static function build($config) {
		$path = "plugins/pacisclient/index.php";
		if(file_exists($path)) {
			include $path;
			$config['debug'] = false;
	//$config['url'] = "http://dapur.unpad.ac.id/~marindra/appapi/pacis/";
	$config['url'] = "http://localhost/devmarin/appapi/admin/";
	$config['api'] = array(
				'url' => "http://doc.unpad.ac.id/api/pacis/json/"
				,'username' => "apipacis"
				,'password' => "apipacis"						
			);
			return new pacis_client($config);
		} else {
			Core::fatalError("Pacis Client Plugin not found!");
		}
	}

}
?>