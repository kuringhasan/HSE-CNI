<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Karyawan Model
 * 
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
 * @author Alan Ridwan M <alanrm82@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Biodata_Pegawai_Model extends Model {
	
	public function __construct($NIP = "") {
		global $dcistem;
		if($NIP) {
			$db   = $dcistem->getOption("framework/db");
			$data = $db->select(array(
						"kNIP",
                        "kKodePusat",
                        "kGelarSebelumNama",
                        "kNama",
                        "kGelarSetelahNama",
                        "kTempatLahir",
                        "kTanggalLahir",
                        "kJenisKelamin",
                        "kAgama",
                        "kGolDarah",
                        "kStatusKawin",
                        "kKewarganegaraan",
                        "kBagian",
                        "kAlamat",
                        "kKecamatan",
                        "kKota",
                        "kPropinsi",
                        "kTelepon",
                        "khp",
                        "kKodePos",
                        "kJenisKepegawaian",
                        "kTupoksi",
                        "kStatusKepegawaian",
                        "kSTATUS"						
					), "tbmKaryawan")->where(array("kNIP = '".$NIP."'"))->get(0);
			$this->appendVariable($data);
			list($date, $time)     = split(" ", $this->kTanggalLahir);
			list($thn, $bln, $tgl) = split("-", $date);
			$this->kTanggalLahir = $tgl."-".$bln."-".$thn;
		
		}
	}
	

	
}
?>