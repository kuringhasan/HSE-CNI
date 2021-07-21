<?php
/**
 * @package List Insiden
 * 
 * @author Abdiiwan <abdiiwan1841@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Ref_Organizational_Structur_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
		global $dcistem;
		
	}
	

	public function index() {
        global $dcistem;
 
        $tpl  = new View("ref_organizational_structure");
		$login=new Adm_Login_Model();
		$db   = $dcistem->getOption("framework/db"); 
		$tpl->url_listdata      = url::current("listdata");
		$url_form = url::current("add");//url::page(2241);     
		$TombolTambah=$login->privilegeInputForm("link","","btn-tambah-data","<i class=\"fa fa-fw fa-plus-circle\"></i>",$this->page->PageID,"add","title='Tambah Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_form."\" class=\"btn btn-primary btn-xs\" data-target=\"#largeModal\"");
		$tpl->TombolTambah      = $TombolTambah; 
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

		// $bulan    = $requestData['columns'][4]['search']['value'];
		// $tahun    = $requestData['columns'][5]['search']['value'];
		// if( trim($tahun)<>"" ){   //name
		// 	$keriteria[]="year(tanggal_insiden)  ='".$tahun."'";
		// 	$judul=$judul."<br />Tahun ".$tahun;
		// } 
		// if( trim($bulan)<>"" ){   //name
		// 	$nama_bln=$master->namabulanIN((int)$bulan);
		// 	if( trim($tahun)<>"" ){
				
		// 		$keriteria[]="DATE_FORMAT(tanggal_insiden,'%Y-%m')='".$tahun."-".$bulan."'";
		// 		$judul=$judul."<br />".$nama_bln." ".$tahun;
		// 	}
		// }
		
		$length=$_REQUEST['length'];

		$start=$_REQUEST['start'];
		$ListData      = array();
		$jml_filtered  = 0;
		$jml_data      = 0;
		$filter=$modelsortir->fromFormcari($keriteria,"and");
		
		$cols=array(0=>"name",
					1=>"description");
		$order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
		if ($order == " ")
		{
			$order= "name desc";
		}

		

		$list_qry=$db->select("a.*, d.name as parent_name, b.Name as job_title_name, c.Name as structure_level_name
		","organizational_structure a
        left join job_title b on a.job_title_id = b.id
        left join structure_level c on a.structure_level_id = c.id
        left join organizational_structure d on a.parent_id = d.id
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
			$ListData[$i]['name']=$data->name;
			$ListData[$i]['parent_name']=$data->parent_name;
			$ListData[$i]['description']=$data->description;
			$ListData[$i]['urutan']=$data->urutan;
			$ListData[$i]['active']=$data->active;
			$ListData[$i]['job_title_name']=$data->job_title_name;
			$ListData[$i]['structure_level_name']=$data->structure_level_name;
			// $ListData[$i]['department_odoo']=$data->department_odoo;

		   
			$url_proses      = url::base()."insiden/resume?id=$data->id_insiden";
			$url_del      = url::current("del",$data->id_insiden);
			$url_edit =	url::current("edit",$data->id_insiden);
			$url_detail =url::current("detail",$data->id_insiden);


			$tombol          = "";
			//$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-pencil\"></i>",$this->page->PageID,"edit","title='Ubah Data' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs\" ");  
			$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-pencil\"></i>",$this->page->PageID,"edit","title='Ubah Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_edit."\" class=\"btn btn-primary btn-xs btn-edit-data\" data-target=\"#largeModal\"");
			$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"del","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->id_insiden."\"");  
			$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-file-text-o\"></i>",$this->page->PageID,"detail","title='Detail' data-toggle=\"modal\" data-remote=\"true\" href=\"".$url_detail."\" class=\"btn btn-primary btn-xs btn-detail-data\" data-target=\"#largeModal\" role=\"".$data->id_insiden."\"");  
			$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-tasks\"></i>",$this->page->PageID,"edit", "title='Proses Data' href='".$url_proses."' class=\"btn btn-success btn-xs\"");

		  
			$control=$tombol;  
			$ListData[$i]['Aksi']=$control;

		   $i++;
		   $no++;
	   }
		$hasil['recordsTotal']=$jml_filtered;
		$hasil['recordsFiltered']=$jml_filtered;
		$hasil['data']=$ListData;
		// var_dump($hasil);
		echo json_encode($hasil);exit;
	}

	
}
