<?php
/**
 * @package List Insiden
 * 
 * @author Abdiiwan <abdiiwan1841@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Resume_Insiden_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->insiden=new Resume_Insiden_Model();
		$this->refinsiden=new Ref_Insiden_Model();
	}
	
	public function index($id=null) {
	   global $dcistem;
	
		$tpl  = new View("resume_insiden");
        $db   = $dcistem->getOption("framework/db"); 
			$tpl->id=$id;
			$hasil=array();
			$tpl->tanggal= date("Y-m-d H:i:s");
			$tpl->noregister= $_POST['noregister'];
			$tpl->tanggal= $_POST['tanggal'];
			$tpl->shiftkerja= $_POST['shiftkerja'];
			$tpl->areakerja= $_POST['areakerja'];
			$tpl->alat= $_POST['alat'];
			$tpl->luka= $_POST['luka'];
			$tpl->jenisinsiden= $_POST['jenisinsiden'];
			$tpl->carakerja= $_POST['carakerja'];
			$tpl->kondisikerja= $_POST['kondisikerja'];
			$tpl->faktorkerja= $_POST['faktorkerja'];
			$tpl->faktorpribadi= $_POST['faktorpribadi'];
			$tpl->perbaikan= $_POST['perbaikan'];
			// $tpl->sanksi= $_POST['sanksi'];
			$tpl->harihilang= $_POST['harihilang'];
			$tpl->perkiraanbiaya= $_POST['perkiraanbiaya'];
			$tpl->keterangan= $_POST['keterangan'];
			$tpl->company= $_POST['company'];
			
			//datakorban
			$tpl->nik= $_POST['nik'];
			$tpl->nama_korban= $_POST['nama_korban'];
			$tpl->jabatan= $_POST['jabatan'];
			$tpl->atasan_langsung= $_POST['atasan_langsung'];
			$tpl->department= $_POST['department'];
			$tpl->masakerja= $_POST['masakerja'];
			$tpl->umur= $_POST['umur'];
			$tpl->luka= $_POST['luka'];
			//dataalat
			$tpl->alat= $_POST['alat'];
			

			$kodeJam=date('H:i:s', strtotime($tpl->tanggal));
			$datajam=$this->refinsiden->getKodeJam($kodeJam);
			$kodeJam = is_array($datajam)? 'NULL' : $datajam->kode;

			$kodeBulan=date('m', strtotime($tpl->tanggal));
			$kodeHari=date('w', strtotime($tpl->tanggal));
			
		if (isset($_POST['simpan'])) {
			$id_pelaporan = $_GET['id'];
			$tgll = date('Y-m-d H:i:s', strtotime($tpl->tanggal));

			$dataupdate =   "no_register = $tpl->noregister, 
							deskripsi = '$tpl->keterangan', 
							tanggal = '$tgll', 
							kode_company = '$tpl->company', 
							kode_shift = $tpl->shiftkerja,
							kode_jam = $kodeJam,
							kode_bulan = $kodeBulan,
							kode_hari =$kodeHari ,
							kode_area_kerja = $tpl->areakerja,
							kode_insiden = $tpl->jenisinsiden,
							kode_cara_kerja = $tpl->carakerja,
							kode_tidak_standar = $tpl->kondisikerja,
							kode_faktor_pekerjaan = $tpl->faktorkerja,
							kode_faktor_personil = $tpl->faktorpribadi,
							kode_tindakan_perbaikan = $tpl->perbaikan,
							kode_hari_kerja_hilang = $tpl->harihilang,
							kode_biaya_perbaikan_unit = $tpl->perkiraanbiaya";
			
			$cols="kode_company,no_register,deskripsi,tanggal,kode_shift,kode_jam,kode_bulan,kode_hari,kode_area_kerja,kode_insiden,kode_cara_kerja,kode_tidak_standar,kode_faktor_pekerjaan,kode_faktor_personil,kode_tindakan_perbaikan,kode_hari_kerja_hilang,kode_biaya_perbaikan_unit,state";
			$values="$tpl->company,$tpl->noregister,'$tpl->keterangan','$tgll',$tpl->shiftkerja,$kodeJam,$kodeBulan,$kodeHari,$tpl->areakerja,$tpl->jenisinsiden,$tpl->carakerja,$tpl->kondisikerja,$tpl->faktorkerja,$tpl->faktorpribadi,$tpl->perbaikan,$tpl->harihilang,$tpl->perkiraanbiaya,'draft'";

			$idResume=$_POST['idResume'];
			if(empty($idResume)){
				// $recdata = $db->insert("resume_insiden",$datainsert);
				$sqlin="INSERT INTO resume_insiden ($cols) VALUES ($values);";
				$rsl=$db->query($sqlin);
				$resume_id = mysql_insert_id();

				$sqlin="UPDATE data_insiden SET id_resume=$resume_id WHERE id_insiden=$id_pelaporan;";
				$rsl=$db->query($sqlin);

				for ($i=0; $i < count($tpl->nama_korban); $i++) { 					
					$cols1 = "id_resume_insiden,nama_korban,nik,atasan_langsung,umur,kode_jabatan,kode_department,kode_masa_kerja,kode_bagian_luka";
					$value1 = $resume_id.",'".$tpl->nama_korban[$i]."','".$tpl->nik[$i]."','".$tpl->atasan_langsung[$i]."',".$tpl->umur[$i].",".$tpl->jabatan[$i].",".$tpl->department[$i].",".$tpl->masakerja[$i].",".$tpl->luka[$i];
					$sqlin1="INSERT INTO korban_insiden ($cols1) VALUES ($value1);";
					$rsl1=$db->query($sqlin1);
				}

				for ($i=0; $i < count($tpl->alat); $i++) { 
					$cols2 = "id_resume_insiden,kode_alat_terlibat";
					$value2 = $resume_id.",".$tpl->alat[$i];
					$sqlin2= "INSERT INTO alat_terlibat_insiden ($cols2) VALUES ($value2);";
					$rsl2=$db->query($sqlin2);
				}
				
				$msg['informasi'] =  "Data sudah disimpan!";
				if(isset($rsl->error) and $rsl->error===true){
					$msg['informasi'] =  "Terjadi Kesalahan";
				} 
			} else {
				$msg['informasi'] =  "Data sudah diupdate!";
				// $recdata = $db->update("resume_insiden",$dataupdate,"id_resume='".$idResume."'");
				$sqlin="UPDATE resume_insiden SET $dataupdate WHERE id_resume=$idResume;";
				$rsl=$db->query($sqlin);
				// var_dump($rsl);
				$sqlin="DELETE FROM  korban_insiden  WHERE id_resume_insiden=".$idResume.";";
				$rslb=$db->query($sqlin);
				$sqlin="DELETE FROM  alat_terlibat_insiden  WHERE id_resume_insiden=".$idResume.";";
			   	$rsla=$db->query($sqlin);
				

				for ($i=0; $i < count($tpl->nama_korban); $i++) { 					
					$cols1 = "id_resume_insiden,nama_korban,nik,atasan_langsung,umur,kode_jabatan,kode_department,kode_masa_kerja,kode_bagian_luka";
					$value1 = $idResume.",'".$tpl->nama_korban[$i]."','".$tpl->nik[$i]."','".$tpl->atasan_langsung[$i]."',".$tpl->umur[$i].",".$tpl->jabatan[$i].",".$tpl->department[$i].",".$tpl->masakerja[$i].",".$tpl->luka[$i];
					$sqlin1="INSERT INTO korban_insiden ($cols1) VALUES ($value1);";
					$rsl1=$db->query($sqlin1);
				}

				for ($i=0; $i < count($tpl->alat); $i++) { 
					$cols2 = "id_resume_insiden,kode_alat_terlibat";
					$value2 = $idResume.",".$tpl->alat[$i];
					$sqlin2= "INSERT INTO alat_terlibat_insiden ($cols2) VALUES ($value2);";
					$rsl2=$db->query($sqlin2);
				}
				
				if(isset($rsl->error) and $rsl->error===true){
					$msg['informasi'] =  "Data tidak bisa diupdate!"; 
				} 
				else {
					// url::redirect(url::page(2242));
				}
			}
			
			$psn=$msg['informasi'];
			$hasil['success']=true;
			$hasil['message']=$psn;
			$hasil['form_error']=$msg;
			
		}
		if (isset($_POST['progress'])) {
			$tgll = date('Y-m-d H:i:s', strtotime($tpl->tanggal));

			$dataupdate =   "no_register = $tpl->noregister, 
							deskripsi = '$tpl->keterangan', 
							tanggal = '$tgll', 
							kode_company = '$tpl->company', 
							kode_shift = $tpl->shiftkerja,
							kode_jam = $kodeJam,
							kode_bulan = $kodeBulan,
							kode_hari =$kodeHari ,
							kode_area_kerja = $tpl->areakerja,
							kode_insiden = $tpl->jenisinsiden,
							kode_cara_kerja = $tpl->carakerja,
							kode_tidak_standar = $tpl->kondisikerja,
							kode_faktor_pekerjaan = $tpl->faktorkerja,
							kode_faktor_personil = $tpl->faktorpribadi,
							kode_tindakan_perbaikan = $tpl->perbaikan,
							kode_hari_kerja_hilang = $tpl->harihilang,
							kode_biaya_perbaikan_unit = $tpl->perkiraanbiaya,
							tgl_awal_progress = '$tgll', 
							state = 'on progress'";
			
			$idResume=$_POST['idResume'];
			$msg['informasi'] =  "Data sudah diupdate!";
			$sqlin="UPDATE resume_insiden SET $dataupdate WHERE id_resume=$idResume;";
			$recdata=$db->query($sqlin);
			
			
			$sqlin="DELETE FROM  korban_insiden  WHERE id_resume_insiden=".$idResume.";";
			$rslb=$db->query($sqlin);
			$sqlin="DELETE FROM  alat_terlibat_insiden  WHERE id_resume_insiden=".$idResume.";";
			$rsla=$db->query($sqlin);

			for ($i=0; $i < count($tpl->nama_korban); $i++) { 					
				$cols1 = "id_resume_insiden,nama_korban,nik,atasan_langsung,umur,kode_jabatan,kode_department,kode_masa_kerja,kode_bagian_luka";
				$value1 = $idResume.",'".$tpl->nama_korban[$i]."','".$tpl->nik[$i]."','".$tpl->atasan_langsung[$i]."',".$tpl->umur[$i].",".$tpl->jabatan[$i].",".$tpl->department[$i].",".$tpl->masakerja[$i].",".$tpl->luka[$i];
				$sqlin1="INSERT INTO korban_insiden ($cols1) VALUES ($value1);";
				$rsl1=$db->query($sqlin1);
			}

			for ($i=0; $i < count($tpl->alat); $i++) { 
				$cols2 = "id_resume_insiden,kode_alat_terlibat";
				$value2 = $idResume.",".$tpl->alat[$i];
				$sqlin2= "INSERT INTO alat_terlibat_insiden ($cols2) VALUES ($value2);";
				$rsl2=$db->query($sqlin2);
			}

			if (!$recdata)
			{	
				$msg['informasi'] =  "Data tidak bisa diupdate!"; 
			} 
			else {
				url::redirect(url::page(2242));
			}

			
			$psn=$msg['informasi'];
			$hasil['success']=true;
			$hasil['message']=$psn;
			$hasil['form_error']=$msg;
			
		}
		if (isset($_POST['done'])) {
			$tgll = date('Y-m-d H:i:s', strtotime($tpl->tanggal));

			$dataupdate =   "no_register = $tpl->noregister, 
							deskripsi = '$tpl->keterangan', 
							tanggal = '$tgll', 
							kode_company = '$tpl->company', 
							kode_shift = $tpl->shiftkerja,
							kode_jam = $kodeJam,
							kode_bulan = $kodeBulan,
							kode_hari =$kodeHari ,
							kode_area_kerja = $tpl->areakerja,
							kode_insiden = $tpl->jenisinsiden,
							kode_cara_kerja = $tpl->carakerja,
							kode_tidak_standar = $tpl->kondisikerja,
							kode_faktor_pekerjaan = $tpl->faktorkerja,
							kode_faktor_personil = $tpl->faktorpribadi,
							kode_tindakan_perbaikan = $tpl->perbaikan,
							kode_hari_kerja_hilang = $tpl->harihilang,
							kode_biaya_perbaikan_unit = $tpl->perkiraanbiaya,
							tgl_selesai_progress = '$tgll', 
							state = 'done'";
			
			$idResume=$_POST['idResume'];
			$msg['informasi'] =  "Data sudah diupdate!";
			$sqlin="UPDATE resume_insiden SET $dataupdate WHERE id_resume=$idResume;";
			$recdata=$db->query($sqlin);

			$sqlin="DELETE FROM  korban_insiden  WHERE id_resume_insiden=".$idResume.";";
			$rslb=$db->query($sqlin);
			$sqlin="DELETE FROM  alat_terlibat_insiden  WHERE id_resume_insiden=".$idResume.";";
			$rsla=$db->query($sqlin);
			
			for ($i=0; $i < count($tpl->nama_korban); $i++) { 					
				$cols1 = "id_resume_insiden,nama_korban,nik,atasan_langsung,umur,kode_jabatan,kode_department,kode_masa_kerja,kode_bagian_luka";
				$value1 = $idResume.",'".$tpl->nama_korban[$i]."','".$tpl->nik[$i]."','".$tpl->atasan_langsung[$i]."',".$tpl->umur[$i].",".$tpl->jabatan[$i].",".$tpl->department[$i].",".$tpl->masakerja[$i].",".$tpl->luka[$i];
				$sqlin1="INSERT INTO korban_insiden ($cols1) VALUES ($value1);";
				$rsl1=$db->query($sqlin1);
			}

			for ($i=0; $i < count($tpl->alat); $i++) { 
				$cols2 = "id_resume_insiden,kode_alat_terlibat";
				$value2 = $idResume.",".$tpl->alat[$i];
				$sqlin2= "INSERT INTO alat_terlibat_insiden ($cols2) VALUES ($value2);";
				$rsl2=$db->query($sqlin2);
			}

			if (!$recdata)
			{	
				$msg['informasi'] =  "Data tidak bisa diupdate!"; 
			} 
			else {
				url::redirect(url::page(2242));
			}

			
			$psn=$msg['informasi'];
			$hasil['success']=true;
			$hasil['message']=$psn;
			$hasil['form_error']=$msg;
			
		}

		if ($id != null)
		{
			$data= $db->select("resume_insiden.*","resume_insiden ")->where("resume_insiden.id_resume=$id")
					->get(0);

			$data_pelaporan= $db->query("SELECT  b.nama_kecelakaan as jeniskecelakaan, c.keterangan as keparahan, datainsiden.tanggal_insiden as tglpelapor, datainsiden.nama_pelapor as pelapor , datainsiden.lokasi, datainsiden.jenis_kecelakaan, datainsiden.jumlah_korban, datainsiden.tingkat_keparahan, datainsiden.id_insiden, datainsiden.bantuan, datainsiden.namafile FROM  data_insiden datainsiden
			 left join ref_jenis_kecelakaan_kerja b on b.kode=datainsiden.jenis_kecelakaan
			 left join ref_tingkat_keparahan c on c.kode=datainsiden.tingkat_keparahan
			 WHERE datainsiden.id_resume=$id");

			$datakorban = $db->query("SELECT * FROM korban_insiden WHERE id_resume_insiden=$id");
			$dataalat = $db->query("SELECT * FROM alat_terlibat_insiden WHERE id_resume_insiden=$id");

			$i=0;
			$listdatakorban      = array();
			while($dtk = $db->fetchObject($datakorban))
			{		
				$listdatakorban[$i] = $dtk;
				$i++;
			}
			$j=0;
			$listdataalat      = array();
			while($dta = $db->fetchObject($dataalat))
			{	
				$listdataalat[$j] = $dta;
				$j++;
			}
			$j=0;
			$datapelaporan      = array();
			while($dta = $db->fetchObject($data_pelaporan))
			{	
				$datapelaporan[$j] = $dta;
				$j++;
			}


			$tpl->data_pelaporan = $datapelaporan;
			$tpl->id_resume = $id;
			// $tpl->data_pelaporan = $data_pelaporan;
			$tpl->datakorban = $listdatakorban;
			$tpl->dataalat = $listdataalat;

			$tpl->noregister= $data->no_register;
			$tpl->tanggal= $data->tanggal;
			$tpl->shiftkerja= $data->kode_shift;
			$tpl->areakerja= $data->kode_area_kerja;
			$tpl->jenisinsiden= $data->kode_insiden;
			$tpl->carakerja= $data->kode_cara_kerja;
			$tpl->kondisikerja= $data->kode_tidak_standar;
			$tpl->faktorkerja= $data->kode_faktor_pekerjaan;
			$tpl->faktorpribadi= $data->kode_faktor_personil;
			$tpl->perbaikan= $data->kode_tindakan_perbaikan;
			$tpl->harihilang= $data->kode_hari_kerja_hilang;
			$tpl->perkiraanbiaya= $data->kode_biaya_perbaikan_unit;
			$tpl->keterangan= $data->deskripsi;
			$tpl->company= $data->kode_company;
			$tpl->state= $data->state;
			// $tpl->tglpelapor= $data->tglpelapor;
			// $tpl->pelapor= $data->pelapor;
			// $tpl->lokasi= $data->lokasi;
			// $tpl->jenis_kecelakaan= $data->jeniskecelakaan;
			// $tpl->tingkat_keparahan= $data->keparahan;
			// $tpl->jumlah_korban= $data->jumlah_korban;
			// $tpl->bantuan= $data->bantuan;

			// $foto = $db->query("SELECT * FROM foto_insiden WHERE id_data_insiden=$data->id_insiden");
			// // var_dump($foto);
			// $fto=[];
			// $i=0;
			// while($data = $db->fetchObject($foto))
			// {
			// 	$fto[$i]=$data->namafile;
			// 	$i++;
			// }
			// // var_dump($fto);
			// $tpl->fotos= $fto;
		}
		if (isset($_GET['id']) != null){
			$idd = $_GET['id'];
			$data= $db->select("a.*, b.nama_kecelakaan as jeniskecelakaan, c.keterangan as keparahan", "data_insiden a
			left join ref_jenis_kecelakaan_kerja b on b.kode=a.jenis_kecelakaan
			left join ref_tingkat_keparahan c on c.kode=a.tingkat_keparahan")->where("a.id_insiden=$idd")
					->get(0);
			$tpl->id_insiden = $idd;
			$tpl->tglpelapor= $data->tanggal_insiden;
			$tpl->pelapor= $data->nama_pelapor;
			$tpl->lokasi= $data->lokasi;
			$tpl->jenis_kecelakaan= $data->jeniskecelakaan;
			$tpl->tingkat_keparahan= $data->keparahan;
			$tpl->jumlah_korban= $data->jumlah_korban;
			$tpl->bantuan= $data->bantuan;
			$tpl->state= 'new';

			$foto = $db->query("SELECT * FROM foto_insiden WHERE id_data_insiden=$idd");
			// var_dump($foto);
			$fto=[];
			$i=0;
			while($data = $db->fetchObject($foto))
			{
				$fto[$i]=$data->namafile;
				$i++;
			}
			// var_dump($fto);
			$tpl->fotos= $fto;
		}

		$qwry= $db->query("SELECT no_register FROM resume_insiden ORDER BY id_resume DESC LIMIT 1");
		$data = $db->fetchObject($qwry);

		$tpl->autonumber = sprintf("%05d", $data->no_register+1);
		// $tpl->autonumber = $data->no_register;

		$tpl->list_jabatan =Model::getOptionList("ref_jabatan", "kode","nama_jabatan","kode ASC"); 
		$tpl->list_departemen =Model::getOptionList("organizational_structure", "id","name","name ASC"); 
		$tpl->list_masakerja =Model::getOptionList("ref_masa_kerja", "kode","nama_masa_kerja","kode ASC"); 
		$tpl->list_umur =Model::getOptionList("ref_umur", "kode","nama_umur","kode ASC"); 
		$tpl->list_shiftkerja =Model::getOptionList("ref_shift", "kode","nama_shift","kode ASC"); 
		$tpl->list_areakerja =Model::getOptionList("ref_area_kerja", "kode","nama_area","kode ASC"); 
		$tpl->list_alat =Model::getOptionList("ref_alat_terlibat", "kode","nama_alat_terlibat","kode ASC"); 
		$tpl->list_luka =Model::getOptionList("ref_bagian_luka", "kode","nama_bagian_luka","kode ASC"); 
		$tpl->list_insiden =Model::getOptionList("ref_jenis_insiden", "kode","nama_insiden","kode ASC"); 
		$tpl->list_carakerja =Model::getOptionList("ref_cara_kerja_tidak_standar", "kode","nama_cara_kerja","kode ASC"); 
		$tpl->list_kondisikerja =Model::getOptionList("ref_kondisi_tidak_standar", "kode","nama_kondisi","kode ASC"); 
		$tpl->list_faktorkerja =Model::getOptionList("ref_faktor_pekerjaan", "kode","nama_faktor_pekerjaan","kode ASC"); 
		$tpl->list_faktorpribadi =Model::getOptionList("ref_faktor_personal", "kode","nama_faktor_personal","kode ASC"); 
		$tpl->list_perbaikan =Model::getOptionList("ref_tindakan_perbaikan", "kode","nama_tindakan","kode ASC"); 
		// $tpl->list_sanksi =Model::getOptionList("ref_sanksi", "kode","nama_sanksi","kode ASC"); 
		$tpl->list_harihilang =Model::getOptionList("ref_lost_workday", "kode","nama_lost_workday","kode ASC"); 
		$tpl->list_perkiraanbiaya =Model::getOptionList("ref_biaya_perbaikan", "kode","nama_biaya_perbaikan","kode ASC"); 
		$tpl->list_jam =Model::getOptionList("ref_kode_jam", "kode","nama_jam","kode ASC"); 
        $tpl->list_contractor =Model::getOptionList("partner","id","name","name ASC","ifnull(is_contractor,0)=1"); 
		$tpl->Hasil=$hasil;
		$this->tpl->content = $tpl;
		$this->tpl->render();
	    
   }

}
 

?>