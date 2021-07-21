<?php
/**
 * @package Pandora PHP Framework
 * @subpackage Database Config File
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

$config["driver"]     = "MYSQL";
$config["mode"]       = "single";
$config["persistent"] = false;
$config["config"]     = array(
	"0" => array(
		"host" => "localhost",
		"user" => "root",
		"pass" => "",
		"name" => "db_ceria"
	),
	"1" => array(
		/*
		"host" => "dell-90462815cd",
		"user" => "sa",
		"pass" => "mufrid",
		"name" => "DB_AKADEMIK"*/

	)
);
?>