<?php
/**
 * @package Pandora Framework
 * @subpackage Index
 * 
 * 
*/
define("PANDORA", 1);
$errorlevel=error_reporting();
error_reporting($errorlevel & ~E_NOTICE);
//error_reporting($error_level);
include "includes/libraries/core.php";
Core::init();
?>