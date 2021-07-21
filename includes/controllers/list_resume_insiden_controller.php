<?php
/**
 * @package List Insiden
 * 
 * @author Abdiiwan <abdiiwan1841@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class List_Resume_Insiden_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
		global $dcistem;
		$this->biodata=new List_Pegawai_Model();
		
	}
	
	public function index() {
	   	global $dcistem;
    
		$tpl  = new View("list_resume_insiden");
		$db   = $dcistem->getOption("framework/db"); 
		$login=new Adm_Login_Model();
		$master=new Master_Ref_Model();
		$profil= $this->biodata->getBiodata($this->ID);
		$list_bulan=$master->listarraybulan();
		$tpl->list_bulan  = $list_bulan;

		$tpl->profil  = $profil;
		$url_form = url::page(2241);     
		$TombolTambah=$login->privilegeInputForm("link","","btn-tambah-data","<i class=\"fa fa-plus-circle\"></i>",$this->page->PageID,"add","title='Tambah Data' href=\"".$url_form."\" class=\"btn btn-primary btn-xs\" ");
		$tpl->TombolTambah      = $TombolTambah; 
		$tpl->url_listdata      = url::current("listdata");
		$tpl->url_jsonData		= url::current("jsonData");
		$tpl->url_comboAjax=url::current("comboAjax");
		$tpl->list_departemen =Model::getOptionList("organizational_structure", "id","name","name ASC"); 
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
		$kontraktor    = $requestData['columns'][2]['search']['value'];
		$departemen    = $requestData['columns'][3]['search']['value'];
		$bulan    = $requestData['columns'][4]['search']['value'];
		$tahun    = $requestData['columns'][5]['search']['value'];
		if( trim($tahun)<>"" ){   //name
			$keriteria[]="year(tanggal)  ='".$tahun."'";
			$judul=$judul."<br />Tahun ".$tahun;
		} 
		if( trim($bulan)<>"" ){   //name
			$nama_bln=$master->namabulanIN((int)$bulan);
			if( trim($tahun)<>"" ){
				
				$keriteria[]="DATE_FORMAT(tanggal,'%Y-%m')='".$tahun."-".$bulan."'";
				$judul=$judul."<br />".$nama_bln." ".$tahun;
			}
		}
		if( trim($departemen)<>"" ){   //name
			$keriteria[]="resu.kode_departemen='".$departemen."'";
		}

		if( trim($kontraktor)<>"" ){   //name
			$keriteria[]="resu.kode_company='".$kontraktor."'";
		}

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
		$cols=array(0=>"id_resume",
					1=>"no_register",
					2=>"tanggal");
		$order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
		   
		$list_qry=$db->select("id_resume,no_register,deskripsi,state,DATE_FORMAT(tgl_awal_progress,'%d/%m/%Y %H:%i:%s') tgl_awal_progress,DATE_FORMAT(tgl_selesai_progress,'%d/%m/%Y %H:%i:%s') tgl_selesai_progress,
		tanggal,DATE_FORMAT(tanggal,'%d/%m/%Y') tanggal_absen, DATE_FORMAT(tanggal,'%d/%m/%Y %H:%i:%s') check_in,
		part.name as nama_company
		","resume_insiden resu
		inner join partner part on part.id=resu.kode_company
		")
		->where($filter)->lim($start,$length);
	   
		$no=$start+1;
		$i=0;
		// var_dump($list_qry);
		while($data = $db->fetchObject($list_qry))
		{
			if($i==0){
				$filtered_qry=$db->query("SELECT FOUND_ROWS() jml_filtered;");
				$filtered_data=$db->fetchObject($filtered_qry);
				$jml_filtered= $filtered_data->jml_filtered;
			}
			$ListData[$i]['No']=$no;
			$ListData[$i]['ID']=$data->id_resume;  
			$ListData[$i]['Tanggal']=$detail_tanggal['IndoHari'].", ".$data->tanggal_absen;
			$ListData[$i]['Checkin']=$data->check_in;
			$ListData[$i]['NoRegister']=$data->no_register;
			$ListData[$i]['Status']=$data->state;
			$ListData[$i]['Keterangan']=$data->deskripsi;
			$ListData[$i]['Company']=$data->nama_company;
			$ListData[$i]['TglAwalprogress']=$data->tgl_awal_progress;
			$ListData[$i]['TglSelesaiprogress']=$data->tgl_selesai_progress;
		   
			$url_del      = url::current("del",$data->id_resume);
			$url_edit =	url::current("edit",$data->id_resume);
			$url_edit = url::page(2241,"",$data->id_resume);
			$url_detail =url::current("detail",$data->id_resume);
			$tombol          = "";
			$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-pencil\"></i>",$this->page->PageID,"edit","title='Ubah Data' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs\" ");  
			$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"del","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->id_resume."\"");  
			$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-file-text-o\"></i>",$this->page->PageID,"detail","title='Detail' data-toggle=\"modal\" data-remote=\"true\" href=\"".$url_detail."\" class=\"btn btn-primary btn-xs btn-detail-data\" data-target=\"#largeModal\" role=\"".$data->id_resume."\"");  

			$tombol_done  = "";
			$tombol_done .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"del","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->id_resume."\"");  
			$tombol_done .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-file-text-o\"></i>",$this->page->PageID,"detail","title='Detail' data-toggle=\"modal\" data-remote=\"true\" href=\"".$url_detail."\" class=\"btn btn-primary btn-xs btn-detail-data\" data-target=\"#largeModal\" role=\"".$data->id_resume."\"");  
		  
			$control=$tombol;  
			$ListData[$i]['Aksi']=$control;

			$control_done=$tombol_done;  
			$ListData[$i]['AksiDone']=$control_done;

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
		$tpl  = new View("detail_resume_insiden");


		$data= $db->select("resume_insiden.*,c.name as company,d.*,e.*,f.*,g.*,h.*,i.*,j.*,k.*,l.*",
			"resume_insiden 
			left join partner c on c.id=resume_insiden.kode_company
			left join ref_shift d on d.kode=resume_insiden.kode_shift
			left join ref_area_kerja e on e.kode=resume_insiden.kode_area_kerja
			left join ref_jenis_insiden f on f.kode=resume_insiden.kode_insiden
			left join ref_cara_kerja_tidak_standar g on g.kode=resume_insiden.kode_cara_kerja
			left join ref_kondisi_tidak_standar h on h.kode=resume_insiden.kode_tidak_standar
			left join ref_faktor_personal i on i.kode=resume_insiden.kode_faktor_personil
			left join ref_faktor_pekerjaan j on j.kode=resume_insiden.kode_faktor_pekerjaan
			left join ref_tindakan_perbaikan k on k.kode=resume_insiden.kode_tindakan_perbaikan
			left join ref_biaya_perbaikan l on l.kode=resume_insiden.kode_biaya_perbaikan_unit
			")->where("id_resume=$id")
					->get(0);

		$datakorban = $db->query("SELECT a.*,b.*,c.name as name_department,d.*,e.* FROM korban_insiden a  
								LEFT JOIN ref_jabatan b ON a.kode_jabatan=b.kode
								LEFT JOIN organizational_structure c ON a.kode_department=c.id
								LEFT JOIN ref_masa_kerja d ON a.kode_masa_kerja=d.kode
								LEFT JOIN ref_bagian_luka e ON a.kode_bagian_luka=e.kode
								WHERE a.id_resume_insiden=$id");
		$dataalat = $db->query("SELECT a.*,b.* FROM alat_terlibat_insiden a  LEFT JOIN ref_alat_terlibat b ON a.kode_alat_terlibat=b.kode WHERE a.id_resume_insiden=$id");

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
		$tpl->datakorban = $listdatakorban;
		$tpl->dataalat = $listdataalat;

		$tpl->noregister= $data->no_register;
		$tpl->tanggal= $data->tanggal;
		$tpl->shiftkerja= $data->nama_shift;
		$tpl->areakerja= $data->nama_area;
		$tpl->jenisinsiden= $data->nama_insiden;
		$tpl->carakerja= $data->nama_cara_kerja;
		$tpl->kondisikerja= $data->nama_kondisi;
		$tpl->faktorkerja= $data->nama_faktor_pekerjaan;
		$tpl->faktorpribadi= $data->nama_faktor_personal;
		$tpl->perbaikan= $data->nama_tindakan;
		$tpl->harihilang= $data->kode_hari_kerja_hilang;
		$tpl->perkiraanbiaya= $data->nama_biaya_perbaikan;
		$tpl->keterangan= $data->deskripsi;
		$tpl->company= $data->company;
		$tpl->state= $data->state;
		$tpl->tgl_awal_progress= $data->tgl_awal_progress;
		$tpl->tgl_selesai_progress= $data->tgl_selesai_progress;



		$this->tpl->content_title = "Detail Resume";
		$tpl->detail = $tpl;
		$tpl->render();   
 	} 
	
	 public function del($id=""){     
		global $dcistem;
		   $db   = $dcistem->getOption("framework/db");
		   $master=new Master_Ref_Model();
		   date_default_timezone_set("Asia/Jakarta");
		   
			$msg=array();
		   if(trim($id)<>"")
		   {
			   $sqlin="DELETE FROM  resume_insiden  WHERE id_resume=".$id.";";
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
}
 

?>