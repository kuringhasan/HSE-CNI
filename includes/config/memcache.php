<?php
/**
 * @package Pandora PHP Framework
 * @subpackage Memcache Config File
 * 
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

$config = array(
	"use_memcache" => false,
	"host"         => "localhost",
	"port"         => 11211,
	"expires"      => 3600
);
?>