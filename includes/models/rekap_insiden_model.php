<?php
/**
 * @package List Insiden
 * 
 * @author Abdiiwan <abdiiwan1841@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Rekap_Insiden_Model extends Model {
	
	public function __construct() {
		
	}

	public function getRekapInsidenByMonth($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		$modelsortir=new Adm_Sortir_Model();
		
		$list_qry = $db->query("
		SELECT t2.id,t2.name,t2.alias,t1.bulan,
		t2.md,
		COALESCE(SUM(t1.amount+t2.amount), 0) AS qty,
		(
		SELECT IFNULL(COUNT(*),0) FROM resume_insiden
		WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
		AND DATE_FORMAT(tanggal, '%m-%Y')=t2.md
		) 
		AS total
		FROM
		(
			SELECT DATE_FORMAT(STR_TO_DATE(kode, '%m'),'%b') AS bulan,
			DATE_FORMAT(STR_TO_DATE(CONCAT(kode,'-',YEAR(CURDATE())), '%m-%y'), '%m-%Y') AS md,
			'0' AS  amount
			FROM ref_kode_bulan  
		)t1
		INNER JOIN
		(
			SELECT p.id,p.name,p.alias,DATE_FORMAT(tanggal, '%b') AS MONTH, COUNT(*) AS amount ,DATE_FORMAT(tanggal, '%m-%Y') AS md
			FROM resume_insiden ri
			INNER JOIN partner p ON ri.kode_company=p.id
			WHERE YEAR(ri.tanggal)=$year
			GROUP BY p.id,md
		)t2
		ON t2.md = t1.md 
		GROUP BY t2.id,t1.md
		ORDER BY t1.md");
		$list_data=array();

		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->id]['partner_id']=$data->id;
			$list_data[$data->id]['partner_name']=$data->name;
			$list_data[$data->id]['partner_alias']=$data->alias;
			$list_data[$data->id]['label'][]=$data->bulan;
			$list_data[$data->id]['qty'][]=$data->total>0?round( (($data->qty/$data->total)*100),2):0;
			//$list_data[$data->id]['qty'][]=$data->qty;

			$i++;
		
		}
	
		return $list_data;
	}
	
	public function getRekapInsidenByCompany($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
				SELECT p.id,p.name,p.alias,
				(
				SELECT IFNULL(COUNT(*),0) FROM resume_insiden
				WHERE kode_company=p.id AND YEAR(tanggal)=$year
				) 
				AS qty,
				(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE YEAR(tanggal)=2020 AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
					) 
					AS total
				FROM partner p
				WHERE is_company='1'");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->id]['partner_id']=$data->id;
			$list_data[$data->id]['partner_name']=$data->name;
			$list_data[$data->id]['partner_alias']=$data->alias;
			$list_data[$data->id]['label'][]=$data->alias;
			$list_data[$data->id]['qty']=$data->qty;
			$list_data[$data->id]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		//$hasil=array("data"=>$list_data,"data_cumulative"=>$list_data_cum,"cumulative_target"=>$list_data_cum_target,"label"=>$label_array,"partner"=>$partner);
		// echo "<pre>";print_r($hasil);echo "</pre>";
		return $list_data;
	}

	// public function getRekapInsidenByJenis($year) {
	// 	global $dcistem;
	// 	$db   = $dcistem->getOption("framework/db");
		
	// 	$list_qry = $db->query("
	// 				SELECT kode,nama_insiden,
	// 				(
	// 				SELECT IFNULL(COUNT(*),0) FROM resume_insiden
	// 				WHERE kode_insiden=p.kode AND YEAR(tanggal)=$year
	// 				) 
	// 				AS qty,
	// 				(
	// 					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
	// 					WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
	// 					) 
	// 					AS total
	// 				FROM ref_jenis_insiden p
	// 				ORDER BY kode
	// 	");
	// 	$list_data=array();
	
	// 	$i=0;
	// 	while($data=$db->fetchObject($list_qry)){
			
	// 		$list_data[$data->kode]['id']=$data->kode;
	// 		$list_data[$data->kode]['nama']=$data->nama_insiden;
	// 		$list_data[$data->kode]['label'][]=$data->nama_insiden;
	// 		$list_data[$data->kode]['qty']=$data->qty;
	// 		$list_data[$data->kode]['ttl']=$data->total;
	
	// 		$i++;
		
	// 	}
		
	// 	//echo "<pre>";print_r($list_data);echo "</pre>";
	// 	return $list_data;
	// }

	public function getRekapInsidenByJenis($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
					SELECT kode,nama_insiden,
					(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE kode_insiden=p.kode AND YEAR(tanggal)=$year
					) 
					AS qty,
					(
						SELECT IFNULL(COUNT(*),0) FROM resume_insiden
						WHERE YEAR(tanggal)=$year
						) 
						AS total
					FROM ref_jenis_insiden p
					ORDER BY kode
		");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->kode]['id']=$data->kode;
			$list_data[$data->kode]['nama']=$data->nama_insiden;
			$list_data[$data->kode]['label'][]=$data->nama_insiden;
			$list_data[$data->kode]['qty']=$data->qty;
			$list_data[$data->kode]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		return $list_data;
	}

	public function getRekapInsidenByKontraktor($year){
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
					SELECT id,nik,name,
					(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE nik=p.nik AND YEAR(tanggal)=$year
					) 
					AS qty,
					(
						SELECT IFNULL(COUNT(*),0) FROM resume_insiden
						WHERE YEAR(tanggal)=$year
						) 
						AS total
					FROM partner p
					WHERE is_contractor=1
					ORDER BY nik
		");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->id]['id']=$data->id;
			$list_data[$data->id]['nama']=$data->name;
			$list_data[$data->id]['label'][]=$data->name;
			$list_data[$data->id]['qty']=$data->qty;
			$list_data[$data->id]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		return $list_data;
	}

	// public function getRekapInsidenByDepatemen($year) {
	// 	global $dcistem;
	// 	$db   = $dcistem->getOption("framework/db");
		
	// 	$list_qry = $db->query("
	// 				SELECT kode,nama_departemen,
	// 				(
	// 				SELECT IFNULL(COUNT(*),0) FROM resume_insiden
	// 				WHERE kode_departemen=p.kode AND YEAR(tanggal)=$year
	// 				) 
	// 				AS qty,
	// 				(
	// 					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
	// 					WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
	// 					) 
	// 					AS total
	// 				FROM ref_departemen p
	// 				ORDER BY kode
	// 	");
	// 	$list_data=array();
	
	// 	$i=0;
	// 	while($data=$db->fetchObject($list_qry)){
			
	// 		$list_data[$data->kode]['id']=$data->kode;
	// 		$list_data[$data->kode]['nama']=$data->nama_departemen;
	// 		$list_data[$data->kode]['label'][]=$data->nama_departemen;
	// 		$list_data[$data->kode]['qty']=$data->qty;
	// 		$list_data[$data->kode]['ttl']=$data->total;
	
	// 		$i++;
		
	// 	}
		
	// 	//echo "<pre>";print_r($list_data);echo "</pre>";
	// 	return $list_data;
	// }

	public function getRekapInsidenByDepatemen($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
					SELECT id,name,
					(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE kode_departemen=p.id AND YEAR(tanggal)=$year
					) 
					AS qty,
					(
						SELECT IFNULL(COUNT(*),0) FROM resume_insiden
						WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
						) 
						AS total
					FROM organizational_structure p
					ORDER BY id
		");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->id]['id']=$data->id;
			$list_data[$data->id]['nama']=$data->name;
			$list_data[$data->id]['label'][]=$data->name;
			$list_data[$data->id]['qty']=$data->qty;
			$list_data[$data->id]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		return $list_data;
	}


	public function getRekapInsidenByJamKerja($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
					SELECT kode,nama_jam,
					(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE kode_jam=p.kode AND YEAR(tanggal)=$year
					) 
					AS qty,
					(
						SELECT IFNULL(COUNT(*),0) FROM resume_insiden
						WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
						) 
						AS total
					FROM ref_kode_jam p
					ORDER BY kode
		");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->kode]['id']=$data->kode;
			$list_data[$data->kode]['nama']=$data->nama_jam;
			$list_data[$data->kode]['label'][]=$data->nama_jam;
			$list_data[$data->kode]['qty']=$data->qty;
			$list_data[$data->kode]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		return $list_data;
	}

	public function getRekapInsidenByShiftKerja($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
					SELECT kode,nama_shift,
					(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE kode_shift=p.kode AND YEAR(tanggal)=$year
					) 
					AS qty,
					(
						SELECT IFNULL(COUNT(*),0) FROM resume_insiden
						WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
						) 
						AS total
					FROM ref_shift p
					ORDER BY kode
		");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->kode]['id']=$data->kode;
			$list_data[$data->kode]['nama']=$data->nama_shift;
			$list_data[$data->kode]['label'][]=$data->nama_shift;
			$list_data[$data->kode]['qty']=$data->qty;
			$list_data[$data->kode]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		return $list_data;
	}

	public function getRekapInsidenByHariKerja($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
					SELECT kode,nama_hari,
					(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE kode_hari=p.kode AND YEAR(tanggal)=$year
					) 
					AS qty,
					(
						SELECT IFNULL(COUNT(*),0) FROM resume_insiden
						WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
						) 
						AS total
					FROM ref_kode_hari p
					ORDER BY kode
		");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->kode]['id']=$data->kode;
			$list_data[$data->kode]['nama']=$data->nama_hari;
			$list_data[$data->kode]['label'][]=$data->nama_hari;
			$list_data[$data->kode]['qty']=$data->qty;
			$list_data[$data->kode]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		return $list_data;
	}

	public function getRekapInsidenByJabatan($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
					SELECT kode,nama_jabatan,
					(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE kode_jabatan=p.kode AND YEAR(tanggal)=$year
					) 
					AS qty,
					(
						SELECT IFNULL(COUNT(*),0) FROM resume_insiden
						WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
						) 
						AS total
					FROM ref_jabatan p
					ORDER BY kode
		");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->kode]['id']=$data->kode;
			$list_data[$data->kode]['nama']=$data->nama_jabatan;
			$list_data[$data->kode]['label'][]=$data->nama_jabatan;
			$list_data[$data->kode]['qty']=$data->qty;
			$list_data[$data->kode]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		return $list_data;
	}

	public function getRekapInsidenByMasaKerja($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
					SELECT kode,nama_masa_kerja,
					(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE kode_masa_kerja=p.kode AND YEAR(tanggal)=$year
					) 
					AS qty,
					(
						SELECT IFNULL(COUNT(*),0) FROM resume_insiden
						WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
						) 
						AS total
					FROM ref_masa_kerja p
					ORDER BY kode
		");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->kode]['id']=$data->kode;
			$list_data[$data->kode]['nama']=$data->nama_masa_kerja;
			$list_data[$data->kode]['label'][]=$data->nama_masa_kerja;
			$list_data[$data->kode]['qty']=$data->qty;
			$list_data[$data->kode]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		return $list_data;
	}

	public function getRekapInsidenByUmur($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
					SELECT kode,nama_umur,
					(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE kode_umur=p.kode AND YEAR(tanggal)=$year
					) 
					AS qty,
					(
						SELECT IFNULL(COUNT(*),0) FROM resume_insiden
						WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
						) 
						AS total
					FROM ref_umur p
					ORDER BY kode
		");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->kode]['id']=$data->kode;
			$list_data[$data->kode]['nama']=$data->nama_umur;
			$list_data[$data->kode]['label'][]=$data->nama_umur;
			$list_data[$data->kode]['qty']=$data->qty;
			$list_data[$data->kode]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		return $list_data;
	}

	public function getRekapInsidenByArea($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
					SELECT kode,nama_area,
					(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE kode_area_kerja=p.kode AND YEAR(tanggal)=$year
					) 
					AS qty,
					(
						SELECT IFNULL(COUNT(*),0) FROM resume_insiden
						WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
						) 
						AS total
					FROM ref_area_kerja p
					ORDER BY kode
		");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->kode]['id']=$data->kode;
			$list_data[$data->kode]['nama']=$data->nama_area;
			$list_data[$data->kode]['label'][]=$data->nama_area;
			$list_data[$data->kode]['qty']=$data->qty;
			$list_data[$data->kode]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		return $list_data;
	}

	public function getRekapInsidenByBulan($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
					SELECT kode,nama_bulan,
					(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE kode_bulan=p.kode AND YEAR(tanggal)=$year
					) 
					AS qty,
					(
						SELECT IFNULL(COUNT(*),0) FROM resume_insiden
						WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
						) 
						AS total
					FROM ref_kode_bulan p
					ORDER BY kode
		");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->kode]['id']=$data->kode;
			$list_data[$data->kode]['nama']=$data->nama_bulan;
			$list_data[$data->kode]['label'][]=$data->nama_bulan;
			$list_data[$data->kode]['qty']=$data->qty;
			$list_data[$data->kode]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		return $list_data;
	}

	public function getRekapInsidenByAlat($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
					SELECT kode,nama_alat_terlibat as nama,
					(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE kode_alat_terlibat=p.kode AND YEAR(tanggal)=$year
					) 
					AS qty,
					(
						SELECT IFNULL(COUNT(*),0) FROM resume_insiden
						WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
						) 
						AS total
					FROM ref_alat_terlibat p
					ORDER BY kode
		");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->kode]['id']=$data->kode;
			$list_data[$data->kode]['nama']=$data->nama;
			$list_data[$data->kode]['label'][]=$data->nama;
			$list_data[$data->kode]['qty']=$data->qty;
			$list_data[$data->kode]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		return $list_data;
	}

	public function getRekapInsidenByLuka($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
					SELECT kode,nama_bagian_luka as nama,
					(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE kode_bagian_luka=p.kode AND YEAR(tanggal)=$year
					) 
					AS qty,
					(
						SELECT IFNULL(COUNT(*),0) FROM resume_insiden
						WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
						) 
						AS total
					FROM ref_bagian_luka p
					ORDER BY kode
		");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->kode]['id']=$data->kode;
			$list_data[$data->kode]['nama']=$data->nama;
			$list_data[$data->kode]['label'][]=$data->nama;
			$list_data[$data->kode]['qty']=$data->qty;
			$list_data[$data->kode]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		return $list_data;
	}

	public function getRekapInsidenByCaraKerja($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
					SELECT kode,nama_cara_kerja as nama,
					(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE kode_cara_kerja=p.kode AND YEAR(tanggal)=$year
					) 
					AS qty,
					(
						SELECT IFNULL(COUNT(*),0) FROM resume_insiden
						WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
						) 
						AS total
					FROM ref_cara_kerja_tidak_standar p
					ORDER BY kode
		");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->kode]['id']=$data->kode;
			$list_data[$data->kode]['nama']=$data->nama;
			$list_data[$data->kode]['label'][]=$data->nama;
			$list_data[$data->kode]['qty']=$data->qty;
			$list_data[$data->kode]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		return $list_data;
	}
	public function getRekapInsidenByKondisiKerja($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
					SELECT kode,nama_kondisi as nama,
					(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE kode_tidak_standar=p.kode AND YEAR(tanggal)=$year
					) 
					AS qty,
					(
						SELECT IFNULL(COUNT(*),0) FROM resume_insiden
						WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
						) 
						AS total
					FROM ref_kondisi_tidak_standar p
					ORDER BY kode
		");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->kode]['id']=$data->kode;
			$list_data[$data->kode]['nama']=$data->nama;
			$list_data[$data->kode]['label'][]=$data->nama;
			$list_data[$data->kode]['qty']=$data->qty;
			$list_data[$data->kode]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		return $list_data;
	}

	public function getRekapInsidenByFaktorKerja($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
					SELECT kode,nama_faktor_pekerjaan as nama,
					(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE kode_faktor_pekerjaan=p.kode AND YEAR(tanggal)=$year
					) 
					AS qty,
					(
						SELECT IFNULL(COUNT(*),0) FROM resume_insiden
						WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
						) 
						AS total
					FROM ref_faktor_pekerjaan p
					ORDER BY kode
		");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->kode]['id']=$data->kode;
			$list_data[$data->kode]['nama']=$data->nama;
			$list_data[$data->kode]['label'][]=$data->nama;
			$list_data[$data->kode]['qty']=$data->qty;
			$list_data[$data->kode]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		return $list_data;
	}

	public function getRekapInsidenByFaktorPribadi($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
					SELECT kode,nama_faktor_personal as nama,
					(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE kode_faktor_personil=p.kode AND YEAR(tanggal)=$year
					) 
					AS qty,
					(
						SELECT IFNULL(COUNT(*),0) FROM resume_insiden
						WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
						) 
						AS total
					FROM ref_faktor_personal p
					ORDER BY kode
		");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->kode]['id']=$data->kode;
			$list_data[$data->kode]['nama']=$data->nama;
			$list_data[$data->kode]['label'][]=$data->nama;
			$list_data[$data->kode]['qty']=$data->qty;
			$list_data[$data->kode]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		return $list_data;
	}

	public function getRekapInsidenByTindakan($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
					SELECT kode,nama_tindakan as nama,
					(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE kode_tindakan_perbaikan=p.kode AND YEAR(tanggal)=$year
					) 
					AS qty,
					(
						SELECT IFNULL(COUNT(*),0) FROM resume_insiden
						WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
						) 
						AS total
					FROM ref_tindakan_perbaikan p
					ORDER BY kode
		");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->kode]['id']=$data->kode;
			$list_data[$data->kode]['nama']=$data->nama;
			$list_data[$data->kode]['label'][]=$data->nama;
			$list_data[$data->kode]['qty']=$data->qty;
			$list_data[$data->kode]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		return $list_data;
	}

	public function getRekapInsidenBySanksi($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
					SELECT kode,nama_sanksi as nama,
					(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE kode_sanksi=p.kode AND YEAR(tanggal)=$year
					) 
					AS qty,
					(
						SELECT IFNULL(COUNT(*),0) FROM resume_insiden
						WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
						) 
						AS total
					FROM ref_sanksi p
					ORDER BY kode
		");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->kode]['id']=$data->kode;
			$list_data[$data->kode]['nama']=$data->nama;
			$list_data[$data->kode]['label'][]=$data->nama;
			$list_data[$data->kode]['qty']=$data->qty;
			$list_data[$data->kode]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		return $list_data;
	}

	public function getRekapInsidenByHilang($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
					SELECT kode,nama_lost_workday as nama,
					(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE kode_hari_kerja_hilang=p.kode AND YEAR(tanggal)=$year
					) 
					AS qty,
					(
						SELECT IFNULL(COUNT(*),0) FROM resume_insiden
						WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
						) 
						AS total
					FROM ref_lost_workday p
					ORDER BY kode
		");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->kode]['id']=$data->kode;
			$list_data[$data->kode]['nama']=$data->nama;
			$list_data[$data->kode]['label'][]=$data->nama;
			$list_data[$data->kode]['qty']=$data->qty;
			$list_data[$data->kode]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		return $list_data;
	}

	public function getRekapInsidenByBiaya($year) {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
		$list_qry = $db->query("
					SELECT kode,nama_biaya_perbaikan as nama,
					(
					SELECT IFNULL(COUNT(*),0) FROM resume_insiden
					WHERE kode_biaya_perbaikan_unit=p.kode AND YEAR(tanggal)=$year
					) 
					AS qty,
					(
						SELECT IFNULL(COUNT(*),0) FROM resume_insiden
						WHERE YEAR(tanggal)=$year AND EXISTS (SELECT id FROM partner WHERE resume_insiden.kode_company=id)
						) 
						AS total
					FROM ref_biaya_perbaikan p
					ORDER BY kode
		");
		$list_data=array();
	
		$i=0;
		while($data=$db->fetchObject($list_qry)){
			
			$list_data[$data->kode]['id']=$data->kode;
			$list_data[$data->kode]['nama']=$data->nama;
			$list_data[$data->kode]['label'][]=$data->nama;
			$list_data[$data->kode]['qty']=$data->qty;
			$list_data[$data->kode]['ttl']=$data->total;
	
			$i++;
		
		}
		
		//echo "<pre>";print_r($list_data);echo "</pre>";
		return $list_data;
	}
	

}
?>