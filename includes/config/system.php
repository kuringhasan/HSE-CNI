<?php
/**
 * @package Pandora PHP Framework
 * @subpackage System Config File
 *
 * @author Hasan <kuring.hasan@gmail.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

$config["timezone"]   = "Asia/Jakarta";
$config["urlrewrite"] = true;
$config["session"]    = array(
	"name"    => "erp-ceria",
	"expires" => 1800
);
$config["web"]   = array(
	"id"      => "erp-ceria",
	"name"    => "System Dashboard",
	"unit" 		=> "Ceria Nugraha Indotama",
	"unit_id" 	=> "01",
	"theme"   	=> "default",
	"debug"   	=> false
);
$config["auth"]       = array(
	"driver"  => "Web",
	"allowed" => array(
        "administrator",
        "pimpinan",
        "manager",
        "admin_production",
        "admin_finance",
        "contractor_pit",
        "contractor_rehandling",
        "admin_shipment ",
        "admin_contractor",
        "pjo_contractor",
        "admin_shipment",
        "admin_hrd",
        "operator_site",
		"guest"
	)
);
$config["dir"]        = array(
	"library"    => "includes/libraries/",
	"driver"     => "includes/drivers/",
	"model"      => "includes/models/",
	"view"       => "includes/views/",
	"controller" => "includes/controllers/",
	"helper"     => "includes/helpers/",
	"theme"      => "themes/",
	"file"       => "files/",
	"temp"       => "tmp/"
)
?>