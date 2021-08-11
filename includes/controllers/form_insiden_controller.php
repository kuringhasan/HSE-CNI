<?php
/**
 * @package List Insiden
 * 
 * @author Abdiiwan <abdiiwan1841@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Form_Insiden_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
		global $dcistem;
		$this->biodata=new List_Pegawai_Model();
		
	}
	
	public function index() {
	   	global $dcistem;
    
		$tpl  = new View("list_insiden");
		$db   = $dcistem->getOption("framework/db"); 
		$login=new Adm_Login_Model();
		$master=new Master_Ref_Model();
		$profil= $this->biodata->getBiodata($this->ID);
		$list_bulan=$master->listarraybulan();
		$tpl->list_bulan  = $list_bulan;

		$tpl->profil  = $profil;
		$url_form = url::current("add",$data->id_resume);//url::page(2241);     
		$TombolTambah=$login->privilegeInputForm("link","","btn-tambah-data","<i class=\"fa fa-fw fa-plus-circle\"></i>",$this->page->PageID,"add","title='Tambah Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_form."\" class=\"btn btn-primary btn-xs\" data-target=\"#largeModal\"");
		$tpl->TombolTambah      = $TombolTambah; 
		$tpl->url_listdata      = url::current("listdata");
		$tpl->url_jsonData		= url::current("jsonData");
		$tpl->url_comboAjax=url::current("comboAjax");
		$tpl->list_departemen =Model::getOptionList("ref_departemen", "kode","nama_departemen","kode ASC"); 
		$tpl->list_contractor =Model::getOptionList("partner","id","name","name ASC","ifnull(is_contractor,0)=1"); 
		$this->tpl->content = $tpl;
		$this->tpl->render();
	    
   }

   	public function listdata() {
		global $dcistem;

		$db   = $dcistem->getOption("framework/db");

		$login=new Adm_Login_Model();
		$master=new Master_Ref_Model();
		$modelsortir	= new Adm_Sortir_Model();
		$referensi      = $master->referensi_session();
		$ref_id			= $_SESSION["framework"]["ref_id"];	
		$login_as       = $_SESSION["framework"]["login_as"]; 

		$keriteria      = array();
		$requestData= $_REQUEST;
		/* $kontraktor    = $requestData['columns'][2]['search']['value'];
		$departemen    = $requestData['columns'][3]['search']['value']; */
		$bulan    = $requestData['columns'][4]['search']['value'];
		$tahun    = $requestData['columns'][5]['search']['value'];
		if( trim($tahun)<>"" ){   //name
			$keriteria[]="year(tanggal_insiden)  ='".$tahun."'";
			$judul=$judul."<br />Tahun ".$tahun;
		} 
		if( trim($bulan)<>"" ){   //name
			$nama_bln=$master->namabulanIN((int)$bulan);
			if( trim($tahun)<>"" ){
				
				$keriteria[]="DATE_FORMAT(tanggal_insiden,'%Y-%m')='".$tahun."-".$bulan."'";
				$judul=$judul."<br />".$nama_bln." ".$tahun;
			}
		}

		/* if( trim($kontraktor)<>"" ){   //name
			$keriteria[]="a.kode_company='".$kontraktor."'";
		} */

		$draw=$_REQUEST['draw'];
		/*Jumlah baris yang akan ditampilkan pada setiap page*/
		$length=$_REQUEST['length'];

		/*Offset yang akan digunakan untuk memberitahu database
		dari baris mana data yang harus ditampilkan untuk masing masing page
		*/

		$start=$_REQUEST['start'];
		$ListData      = array();
		$jml_filtered  = 0;
		$jml_data      = 0;
		$bulan_tahun=$tahun."-".$bulan;
	   
		$filter=$modelsortir->fromFormcari($keriteria,"and");
		$cols=array(0=>"id_insiden",
					1=>"tanggal_insiden",
					2=>"nama_pelapor",
					3=>"lokasi",
					4=>"jenis_kecelakaan",
					5=>"tingkat_keparahan",
					6=>"jumlah_korban",
					7=>"bantuan",);
		$order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
		if ($order == " ")
		{
			$order= "tanggal_insiden desc";
		}
		$list_qry=$db->select("id_insiden,nama_pelapor,lokasi,
		tanggal_insiden,DATE_FORMAT(tanggal_insiden,'%d/%m/%Y') tanggal_absen, DATE_FORMAT(tanggal_insiden,'%d/%m/%Y %H:%i:%s') check_in,
		jenis_kecelakaan,jumlah_korban,tingkat_keparahan,bantuan,nama_kecelakaan,keterangan
		","data_insiden a
		left join ref_jenis_kecelakaan_kerja b on b.kode=a.jenis_kecelakaan
		left join ref_tingkat_keparahan c on c.kode=a.tingkat_keparahan
		")
		->where($filter)
		->orderBy($order)
		->lim($start,$length)
		;
	   
		$no=$start+1;
		$i=0;

		while($data = $db->fetchObject($list_qry))
		{
			if($i==0){
				$filtered_qry=$db->query("SELECT FOUND_ROWS() jml_filtered;");
				$filtered_data=$db->fetchObject($filtered_qry);
				$jml_filtered= $filtered_data->jml_filtered;
			}
			$ListData[$i]['No']=$no;
			$ListData[$i]['ID']=$data->id_insiden;  
			$ListData[$i]['Waktu']=$data->check_in;
			$ListData[$i]['Nama']=$data->nama_pelapor;
			$ListData[$i]['Company']=$data->nama_company;
			$ListData[$i]['Lokasi']=$data->lokasi;
			$ListData[$i]['Kecelakaan']=$data->nama_kecelakaan;
			$ListData[$i]['Bantuan']=$data->bantuan;
			$ListData[$i]['Keparahan']=$data->keterangan;
			$ListData[$i]['Korban']=$data->jumlah_korban;
		   
			$url_proses 		= url::base()."insiden/resume?id=$data->id_insiden";
			$url_link_to_resume = url::current("linkToResume",$data->id_insiden);
			$url_del      		= url::current("del",$data->id_insiden);
			$url_edit 			= url::current("edit",$data->id_insiden);
			$url_detail 		= url::current("detail",$data->id_insiden);

			$resume_insiden_query=$db->query("SELECT id_resume FROM data_insiden WHERE id_insiden = $data->id_insiden;");
			$resume_insiden=$db->fetchObject($resume_insiden_query);
			$url_detail_resume = url::base()."insiden/resume/index/$resume_insiden->id_resume";

			$tombol          = "";
			//$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-pencil\"></i>",$this->page->PageID,"edit","title='Ubah Data' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs\" ");  
			$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-pencil\"></i>",$this->page->PageID,"edit","title='Ubah Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_edit."\" class=\"btn btn-primary btn-xs btn-edit-data\" data-target=\"#largeModal\"");
			$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"del","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->id_insiden."\"");  
			$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-file-text-o\"></i>",$this->page->PageID,"detail","title='Detail' data-toggle=\"modal\" data-remote=\"true\" href=\"".$url_detail."\" class=\"btn btn-primary btn-xs btn-detail-data\" data-target=\"#largeModal\" role=\"".$data->id_insiden."\"");  
			$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-tasks\"></i>",$this->page->PageID,"edit", "title='Proses Data' href='".$url_proses."' class=\"btn btn-success btn-xs\"");
			$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-link\"></i>",$this->page->PageID,"edit","title='Link Ke Resume' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_link_to_resume."\" class=\"btn btn-success btn-xs btn-link-data\" data-target=\"#largeModal\"");
		  
			if($resume_insiden->id_resume!=null || $resume_insiden->id_resume!=""){
				$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-info-circle\"></i>",$this->page->PageID,"detail", "title=''Detail Resume Insiden' href='".$url_detail_resume."' class=\"btn btn-warning btn-xs\"");
			}
			
			$control=$tombol;  
			$ListData[$i]['Aksi']=$control;

		   $i++;
		   $no++;
	    }
		$hasil['filter']=$tahun."-".$bulan;
		$hasil['draw']=$draw;
		$hasil['recordsTotal']=$jml_filtered;
		$hasil['recordsFiltered']=$jml_filtered;
		$hasil['data']=$ListData;
		
		echo json_encode($hasil);exit;
	}

	public function detail($id){     
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		$tpl  = new View("detail_insiden");
		$insidenMdl    = new Form_Insiden_Model();
		date_default_timezone_set("Asia/Jakarta");
		$detail=$insidenMdl->getDetailInsiden($id);
		$tpl->list_contractor =Model::getOptionList("partner","id","name","name ASC","ifnull(is_contractor,0)=1"); 
		$tpl->list_tingkat_keparahan =Model::getOptionList("ref_tingkat_keparahan","kode","keterangan","kode ASC",""); 
		$tpl->list_jenis_kecelakaan =Model::getOptionList("ref_jenis_kecelakaan_kerja","kode","nama_kecelakaan","kode ASC",""); 

		$tpl->id            		= $detail->id_insiden;
		$tpl->waktu       			= $detail->tanggal_insiden;
		$tpl->nama_pelapor   		= $detail->nama_pelapor;
		$tpl->nama_company			= $detail->nama_company;
		$tpl->lokasi				= $detail->lokasi;
		$tpl->jenis_kecelakaan		= $detail->jenis_kecelakaan;
		$tpl->jumlah_korban			= $detail->jumlah_korban;
		$tpl->tingkat_keparahan		= $detail->tingkat_keparahan;
		$tpl->bantuan				= $detail->bantuan;

		// var_dump($detail);

		$foto = $db->query("SELECT * FROM foto_insiden WHERE id_data_insiden=$id");
		// var_dump($foto);
		$fto="";
		while($data = $db->fetchObject($foto))
		{
			$fto = $fto."<a href='/files/hse/".$data->namafile."' target='_blank'><img src='/files/hse/".$data->namafile."' width='80%'></a><br>";
		}

		// var_dump($fto);
		$tpl->foto	= $fto;

		$this->tpl->content_title = "Detail Insiden";
		$tpl->detail = $tpl;
		$tpl->render();   
 	} 
	

	public function linkToResume($id=""){  
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		$master=new Master_Ref_Model();
		date_default_timezone_set("Asia/Jakarta");
		$tpl  = new View("link_to_resume_insiden");
		$url_link = url::current("linkToResume",$id);
		$tpl->url_link  =  $url_link;

		if(isset($_POST['no_register']) && $_POST['no_register']!=''){
			$no_register = $_POST['no_register'];
			$query = $db->query("SELECT * FROM resume_insiden WHERE no_register like '%$no_register%'");;
			$ListData = array();
			$i=0;
			while($data = $db->fetchObject($query))
			{
				$ListData[$i] = $data;
				$i++;
			}
			echo json_encode($ListData);
		}elseif(isset($_POST['id_resume'])){
			$id_resume = $_POST['id_resume'];
			$id_pelaporan = $_POST['id_pelaporan'];
			$sqlin="UPDATE data_insiden SET id_resume=$id_resume  WHERE id_insiden=$id_pelaporan;";
			if($rsl=$db->query($sqlin)){
				echo "Berhasil";
			}else{
				echo "Gagal";
			}
		}else{
			$tpl->id_pelaporan  =  $id;
			$tpl->render(); 
		}
	}


	 public function del($id=""){     
		global $dcistem;
		   $db   = $dcistem->getOption("framework/db");
		   $master=new Master_Ref_Model();
		   date_default_timezone_set("Asia/Jakarta");
		   
			$msg=array();
			if(trim($id)<>"")
			{
				$sqlin="DELETE FROM  data_insiden  WHERE id_insiden=".$id.";";
				$rsl=$db->query($sqlin);
				if(isset($rsl->error) and $rsl->error===true){
						$msg['success']=false;
						$msg['message']="Error, ".$rsl->query_last_message;
				}else{
						$msg['success']=true;
						$msg['message']="Data sudah dihapus"; 
				}
			}else{
				$msg['success']=false;
				$msg['message']="Gagal menggambil data yang akan dihapus, silahkan ulangi!";
			}
			echo json_encode($msg);   
	 } 

	 public function add($proses=""){     
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		$master=new Master_Ref_Model();
		$login=new Adm_Login_Model();
		date_default_timezone_set("Asia/Jakarta");
		if(trim($proses)=="save")
		{
			$fotos	=$_FILES['fotos'];
			
			$waktu	=trim($_POST['waktu']);
			$pelapor	=trim($_POST['pelapor']);
			$kontraktor	=trim($_POST['frm_contractor']);
			$lokasi	=trim($_POST['lokasi']);
			$jenis_kecelakaan	=trim($_POST['jenis_kecelakaan']);
			$jml_korban	=trim($_POST['jml_korban']);
			$area_kerja	=trim($_POST['area_kerja']);
			$tingkat_keparahan	=trim($_POST['tingkat_keparahan']);
			$bantuan	=trim($_POST['bantuan']);
			$filename="no-image.png";
			$validasi=$this->validasiform("add");   
			if(count($validasi['arrayerror'])==0){
				$errorUpload=FALSE;
				$msge="";
				$Username=$_SESSION["framework"]["current_user"]->Username ;

				$files = $_FILES;
				$jumlahFile = count($files['file']['name']);

				for ($i = 0; $i < $jumlahFile; $i++) {

					$validextensions = array("jpeg", "jpg", "png");
					$temporary = explode(".", $_FILES["file"]["name"][$i]);
					$file_extension = end($temporary);
					$directory = dirname(dirname(__DIR__))."/files/hse/";


					if ((($_FILES["file"]["type"][$i] == "image/png") || ($_FILES["file"]["type"][$i] == "image/jpg") || ($_FILES["file"]["type"][$i] == "image/jpeg")
					) && in_array($file_extension, $validextensions)) {
						if ($_FILES["file"]["error"][$i] > 0)
						{
							$errorUpload=TRUE;
							$msge= "Return: " . $_FILES["file"]["error"][$i] . "<br/><br/>";
						}
					}
				}
				if ($errorUpload==TRUE){
					$msg['success']=false;
					$msg['message']="Error".$msge;
				} else {
					$pelapor	=$master->scurevaluetable($pelapor);
					$kontraktor	=$master->scurevaluetable($kontraktor);
					$jenis_kecelakaan	=$master->scurevaluetable($jenis_kecelakaan,"number");
					$tingkat_keparahan	=$master->scurevaluetable($tingkat_keparahan,"number");
					$jml_korban	=$master->scurevaluetable($jml_korban,"number");
					$area_kerja	=$master->scurevaluetable($area_kerja,"number");
					$lokasi	=$master->scurevaluetable($lokasi);
					$bantuan	=$master->scurevaluetable($bantuan);
					
					
					$cols="tanggal_insiden,nama_pelapor,lokasi,jenis_kecelakaan,jumlah_korban,area_kerja,tingkat_keparahan,bantuan,created_by";
					$values="'$waktu',$pelapor,$lokasi,$jenis_kecelakaan,$jml_korban,$area_kerja,$tingkat_keparahan,$bantuan,'$Username'";
					$sqlin="INSERT INTO data_insiden ($cols) VALUES ($values);";
					
		
					$rsl=$db->query($sqlin);
					$last_id = mysql_insert_id();
					if(isset($rsl->error) and $rsl->error===true){
						$msg['success']=false;
						$msg['message']="Error, ".$rsl->query_last_message;
					}else{
						$insertFoto = true;
						for ($i = 0; $i < $jumlahFile; $i++) {
							$sourcePath = $_FILES['file']['tmp_name'][$i];
							$extension = pathinfo($_FILES["file"]["name"][$i])['extension'];
							$filename=trim($Username).'-'.date("Ymd-His")."_".rand().".".$extension;
							$targetPath = $directory.$filename;
							move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file

							$cols="id_data_insiden,namafile";
							$values="'$last_id','$filename'";
							$sqlin="INSERT INTO foto_insiden ($cols) VALUES ($values);";
							$ins = $db->query($sqlin);
							$insertFoto = $ins;
						}
						
						if($insertFoto){
							$msg['success']=true;
							$msg['message']="Data sudah ditambahkan"; 
						}else{
							$msg['success']=false;
							$msg['message']="Terjadi kesalahan insert data foto"; 
						}

						
						// $msg['message']=""; 
						
					}
				}
				
			}else{
				$msg['success']	=false;
				$msg['message']	=	"Terjadi kesalahan pengisian form";
				$msg['form_error']=$validasi['arrayerror'];
			}
			echo json_encode($msg);   
		}else{
			
			$tpl  = new View("form_insiden");
			$tpl->list_contractor =Model::getOptionList("partner","id","name","name ASC","ifnull(is_contractor,0)=1"); 
			$tpl->list_tingkat_keparahan =Model::getOptionList("ref_tingkat_keparahan","kode","keterangan","kode ASC",""); 
			$tpl->list_jenis_kecelakaan =Model::getOptionList("ref_jenis_kecelakaan_kerja","kode","nama_kecelakaan","kode ASC",""); 
			$tpl->list_area_kerja =Model::getOptionList("ref_area_kerja", "kode","nama_area","kode ASC");  
			$tpl->waktu=date('Y-m-d H:i:s');
			$tpl->id            		= 0;
			$tpl->nama_pelapor   		= $_SESSION["framework"]["current_user"]->Username;
			$tpl->kode_company			= '';
			$tpl->lokasi				= '';
			$tpl->jenis_kecelakaan		= '';
			$tpl->jumlah_korban			= 0;
			$tpl->tingkat_keparahan		= '';
			$tpl->bantuan				='';
			$tpl->url_add = url::current("add");
			$tpl->url_jsonData		= url::current("jsonData");
			$tpl->url_comboAjax		=url::current("comboAjax");
			$tpl->content = $tpl;
			$tpl->render(); 
		}
	 }
	 
	 public function edit($id="",$proses="") {     
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		$master=new Master_Ref_Model();
		$login=new Adm_Login_Model();
		$insidenMdl    = new Form_Insiden_Model();
		date_default_timezone_set("Asia/Jakarta");

		if(trim($proses)=="save")
		{
			$id	=trim($_POST['id']);
			$waktu	=trim($_POST['waktu']);
			$pelapor	=trim($_POST['pelapor']);
			/* $kontraktor	=trim($_POST['frm_contractor']); */
			$lokasi	=trim($_POST['lokasi']);
			$jenis_kecelakaan	=trim($_POST['jenis_kecelakaan']);
			$jml_korban	=trim($_POST['jml_korban']);
			$area_kerja	=trim($_POST['area_kerja']);
			$tingkat_keparahan	=trim($_POST['tingkat_keparahan']);
			$bantuan	=trim($_POST['bantuan']);
			
			$validasi=$this->validasiform("edit");   
			if(count($validasi['arrayerror'])==0){
				$errorUpload=FALSE;
				$directory = dirname(dirname(__DIR__))."/files/hse/";
				// $filename="no-image.png";
				$files = $_FILES;
				$jumlahFile = count($files['file']['name']);


				$msge="";
				$Username=$_SESSION["framework"]["current_user"]->Username ;

				for ($i = 0; $i < $jumlahFile; $i++) {
					$validextensions = array("jpeg", "jpg", "png");
					$temporary = explode(".", $_FILES["file"]["name"][$i]);
					$file_extension = end($temporary);
					

					if ((($_FILES["file"]["type"][$i] == "image/png") || ($_FILES["file"]["type"][$i] == "image/jpg") || ($_FILES["file"]["type"][$i] == "image/jpeg")
					) && in_array($file_extension, $validextensions)) {
						if ($_FILES["file"]["error"][$i] > 0)
						{
							$errorUpload=TRUE;
							$msge= "Return: " . $_FILES["file"]["error"][$i] . "<br/><br/>";
						}
					}
				}

				if ($errorUpload==TRUE){
					$msg['success']=false;
					$msg['message']="Error".$msge;
				} else {

					// $updafieldFile="";
					// if ($filename !="no-image.png")
					// {
					// 	$detail=$insidenMdl->getFormInsiden($id);
					// 	if ($detail->namafile!="no-image.png"){
					// 		unlink($directory.DIRECTORY_SEPARATOR .$detail->namafile);
					// 	}
					// 	$updafieldFile = ",namafile='$filename'";
					// }

					$pelapor	=$master->scurevaluetable($pelapor);
					/* $kontraktor	=$master->scurevaluetable($kontraktor); */
					$jenis_kecelakaan	=$master->scurevaluetable($jenis_kecelakaan,"number");
					$tingkat_keparahan	=$master->scurevaluetable($tingkat_keparahan,"number");
					$jml_korban	=$master->scurevaluetable($jml_korban,"number");
					$area_kerja	=$master->scurevaluetable($area_kerja,"number");
					$lokasi	=$master->scurevaluetable($lokasi);
					$bantuan	=$master->scurevaluetable($bantuan);
					$Username=$_SESSION["framework"]["current_user"]->Username ;
					$skrg = date('Y-m-d H:i:s');
					//kode_company=$kontraktor,
					$cols="tanggal_insiden='$waktu',nama_pelapor=$pelapor,".
							"lokasi=$lokasi,jenis_kecelakaan=$jenis_kecelakaan,".
							"jumlah_korban=$jml_korban,area_kerja=$area_kerja,tingkat_keparahan=$tingkat_keparahan,".
							"bantuan=$bantuan,updated='$skrg',updated_by='$Username'".$updafieldFile;
					$sqlin="update data_insiden set $cols where id_insiden= $id;";
					
					
					$rsl=$db->query($sqlin);
					if(isset($rsl->error) and $rsl->error===true){
						$msg['success']=false;
						$msg['message']="Error, ".$rsl->query_last_message;
					}else{
						$insertFoto = true;
						for ($i = 0; $i < $jumlahFile; $i++) {
							$sourcePath = $_FILES['file']['tmp_name'][$i];
							$extension = pathinfo($_FILES["file"]["name"][$i])['extension'];
							$filename=trim($Username).'-'.date("Ymd-His")."_".rand().".".$extension;
							$targetPath = $directory.$filename;
							move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file

							$cols="id_data_insiden,namafile";
							$values="'$id','$filename'";
							$sqlin="INSERT INTO foto_insiden ($cols) VALUES ($values);";
							$ins = $db->query($sqlin);
							$insertFoto = $ins;
						}
						
						if($insertFoto){
							$msg['success']=true;
							$msg['message']="Data sudah diperbarui"; 
						}else{
							$msg['success']=false;
							$msg['message']="Terjadi kesalahan insert data foto"; 
						}
						
					}
				}
				
			}else{
				$msg['success']	=false;
				$msg['message']	=	"Terjadi kesalahan pengisian form";
				$msg['form_error']=$validasi['arrayerror'];
			}
			echo json_encode($msg);   
		}else{
			
			$tpl  = new View("form_insiden");
			
			$detail=$insidenMdl->getFormInsiden($id);
			$foto = $db->query("SELECT * FROM foto_insiden WHERE id_data_insiden=$detail->id_insiden;");;
		   
			$no=1;
			$i=0;
			$ListData = array();
			while($data = $db->fetchObject($foto))
			{
				$ListData[$i]['No']=$no;
				$ListData[$i]['id']=$data->id;
				$ListData[$i]['namafile']=$data->namafile;
				$no++;
				$i++;
			}
			$tpl->list_foto = $ListData;
			$tpl->list_contractor =Model::getOptionList("partner","id","name","name ASC","ifnull(is_contractor,0)=1"); 
			$tpl->list_tingkat_keparahan =Model::getOptionList("ref_tingkat_keparahan","kode","keterangan","kode ASC",""); 
			$tpl->list_jenis_kecelakaan =Model::getOptionList("ref_jenis_kecelakaan_kerja","kode","nama_kecelakaan","kode ASC",""); 

			$tpl->id            		= $detail->id_insiden;
			$tpl->waktu       			= $detail->tanggal_insiden;
			$tpl->nama_pelapor   		= $detail->nama_pelapor;
			$tpl->kode_company			= $detail->kode_company;
			$tpl->lokasi				= $detail->lokasi;
			$tpl->jenis_kecelakaan		= $detail->jenis_kecelakaan;
			$tpl->jumlah_korban			= $detail->jumlah_korban;
			$tpl->area_kerja			= $detail->area_kerja;
			$tpl->tingkat_keparahan		= $detail->tingkat_keparahan;
			$tpl->bantuan				= $detail->bantuan;
			$tpl->typeform              = "edit";
				
			$tpl->url_add = url::current("edit");
			$tpl->url_jsonData		= url::current("jsonData");
			$tpl->url_comboAjax		=url::current("comboAjax");
			$tpl->content = $tpl;
			$tpl->render(); 
		}  
 	} 

	 public function deleteFoto(){
		$id = $_POST['id'];
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		$master=new Master_Ref_Model();
		date_default_timezone_set("Asia/Jakarta");
		$directory = dirname(dirname(__DIR__))."/files/hse";

		 $msg=array();
		if(trim($id)<>"")
		{
			$foto = $db->query("SELECT * FROM foto_insiden WHERE id=$id");
			while($data = $db->fetchObject($foto))
			{
				$path = $directory.DIRECTORY_SEPARATOR.$data->namafile;
				unlink($path);
			}

			$sqlin="DELETE FROM  foto_insiden  WHERE id=".$id.";";
			$rsl=$db->query($sqlin);


			if(isset($rsl->error) and $rsl->error===true){
				 $msg['success']=false;
				 $msg['message']="Error, ".$rsl->query_last_message;
			}else{
				 $msg['success']=true;
				 $msg['message']="Foto sudah dihapus"; 
			}
		}else{
			$msg['success']=false;
			$msg['message']="Gagal menggambil data yang akan dihapus, silahkan ulangi!";
		}
		echo json_encode($msg);
	 }

	public function validasiform($aksi="add",$kode_lama="") 
    {
        global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $msg=array();
      	$pesan=array();
    	if(trim($_POST['pelapor'])==''){
            $pesan["pelapor"]="Nama pelapor harus diisi!";   
            $msg[]="Nama pelapor harus diisi!";
        }
        
        
		if(count($msg)==1){
            $msj=$msg[0];
        }elseif(count($msg)>1){
            foreach($msg as $key=>$value){
                $msj=$msj."- ".$value."<br>";
            }
        }
        return array("arrayerror"=>$pesan,"msg"=>$msj);
         
    }   
}
 

?>