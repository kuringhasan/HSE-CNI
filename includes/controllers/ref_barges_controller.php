<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Ref_Barges_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        global $dcistem;
       
	}
	
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("ref_barges");
        $db   = $dcistem->getOption("framework/db");
        $login=new Adm_Login_Model();
        $KodeDosen=$_SESSION["framework"]["ref_id"] ;
        $Username=$_SESSION["framework"]["current_user"]->Username ;
        $list_contractor=Model::getOptionList("partner","id","name","name ASC","ifnull(is_contractor,0)=1"); 
	    
        $tpl->list_contractor =$list_contractor;
     
       	$url_form = url::current("add");     
       	$TombolTambah=$login->privilegeInputForm("link","","btn-tambah-data","<i class=\"fa fa-fw fa-plus-circle\"></i>",$this->page->PageID,"add","title='Tambah Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_form."\" class=\"btn btn-primary btn-xs\" data-target=\"#largeModal\"");
       	$tpl->TombolTambah      = $TombolTambah; 
         $tpl->url_listdata      = url::current("listdata");
         $tpl->url_jsonData		= url::current("jsonData");
         $tpl->url_comboAjax=url::current("comboAjax");
		$this->tpl->content = $tpl;
		$this->tpl->render();        
    }
    public function listdata() {
 	    global $dcistem;
 	  	
       $db   = $dcistem->getOption("framework/db");
      
        $login=new Adm_Login_Model();
        $master=new Master_Ref_Model();
        $ref=$master->referensi_session();
                
        $modelsortir	= new Adm_Sortir_Model();
        $login_as = $_SESSION["framework"]["login_as"]; 
        
        $requestData= $_REQUEST;
        
        $nama       = $requestData['columns'][1]['search']['value'];
        //$category    = $requestData['columns'][1]['search']['value'];
        
		$contractor    = $requestData['columns'][2]['search']['value'];
		$aktif    = $requestData['columns'][4]['search']['value'];
       
        if( trim($aktif)<>"" ){   //name
            $keriteria[]="ifnull(is_active,0) =".$aktif."";
        }
        if(trim($nama)<>""){
            $keriteria[]="( name like'%".$nama."%' )" ;
        }
        $filter=$modelsortir->fromFormcari($keriteria,"and");
       $draw=$_REQUEST['draw'];
		$length=$_REQUEST['length'];
		$start=$_REQUEST['start'];
        
        $cols=array(0=>"id",
                    1=>"name");
        $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
            
        $list_qry=$db->select("SQL_CALC_FOUND_ROWS id,name,capacity,description,rgb_color,is_active","barges")
		->where($filter)->orderby($order)->lim($start,$length);
        $no=$start+1;
        $i=0;
        $ListData=array();
        $jml_filtered=0;
        while($data = $db->fetchObject($list_qry))
        {
            if($i==0){
                    $filtered_qry=$db->query("SELECT FOUND_ROWS() jml_filtered;");
                    $filtered_data=$db->fetchObject($filtered_qry);
                    //print_r($filtered_data);
                    $jml_filtered= $filtered_data->jml_filtered;
                }
            $ListData[$i]['No']=$no;
			$ListData[$i]['ID']=$data->id;
			$ListData[$i]['Color']="<span class=\"label\" style=\"background-color:".$data->rgb_color.";color:#000;\">".$data->rgb_color."</span>";
            $ListData[$i]['Nama']=$data->name;
            $ListData[$i]['Kapasitas']=number_format($data->capacity,2,",",".");
            $ListData[$i]['Aktif']=$data->is_active==1?"Ya":"Tidak";
            $url_del      = url::current("del",$data->id);
			$url_edit =url::current("edit",$data->id);
            	$url_detail =url::current("detail",$data->id);
           	$tombol=$login->privilegeInputForm("link","","","<i class=\"fa fa-gear\"></i>",$this->page->PageID,"edit","title='Edit Data' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs btn-edit-data\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\"");
           // $tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-fw fa-trash-o\"></i>",$this->page->PageID,"del","title='Hapus Data Pelanggan' data-toggle=\"modal\" data-remote=\"true\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->PartnerNama."\" ");
			$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"del","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->KasusID."\"");
			$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-file-text-o\"></i>",$this->page->PageID,"detail","title='Detail' href=\"".$url_detail."\" class=\"btn btn-primary btn-xs btn-detail-data\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\"");

            $control=$tombol;  
            $ListData[$i]['Tombol']=$control;
            $i++;
            $no++;
        }
      
        $hasil['draw']=$draw;
        $hasil['title']=strtoupper($judul);;
        $hasil['recordsTotal']=$jml_filtered;
        $hasil['recordsFiltered']=$jml_filtered;//
	    $hasil['data']=$ListData;
         //echo $hasil;
        echo json_encode($hasil);exit;
         // echo "<pre>"; print_r($hasil);echo "</pre>";
	}
  public function add($proses=""){     
     global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        $barge=new Ref_Barge_Model();
        date_default_timezone_set("Asia/Jakarta");
        if(trim($proses)=="save")
		{
	        $nama	     =trim($_POST['name']);
	        $capacity	 =trim($_POST['capacity']);
	        $description =trim($_POST['description']);
            $rgb_color	 =trim($_POST['rgb_color']);
            $is_active	 =trim($_POST['is_active']);
            
	        $validasi=$this->validasiform("add");   
	        if(count($validasi['arrayerror'])==0){
		       $rslt=$barge->insert($nama,$capacity,$description,$rgb_color,$is_active);
                $msg=$rslt;
			
	           
	        }else{
	             $msg['success']	=false;
	             $msg['message']	=	"Terjadi kesalahan pengisian form";
	             $msg['form_error']=$validasi['arrayerror'];
	        }
	        echo json_encode($msg);   
	    }else{
			
	    	$tpl  = new View("form_barge");
    	    $list_contractor=Model::getOptionList("partner","id","name","name ASC","ifnull(is_contractor,0)=1"); 
	    
        	$tpl->list_contractor =$list_contractor;
			
	    	$tpl->url_add = url::current("add");
	    	$tpl->url_jsonData		= url::current("jsonData");
        	$tpl->url_comboAjax		=url::current("comboAjax");
	    	$tpl->content = $tpl;
	        $tpl->render(); 
	    }
  } 
	public function edit($id,$proses=""){     
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		$master=new Master_Ref_Model();
        $barge=new Ref_Barge_Model();
		date_default_timezone_set("Asia/Jakarta");
		if(trim($proses)=="save")
		{    
			if(trim($id)<>"")
			{ 
    			$nama	     =trim($_POST['name']);
    	        $capacity	 =trim($_POST['capacity']);
    	        $description =trim($_POST['description']);
                $rgb_color	 =trim($_POST['rgb_color']);
                $is_active	 =trim($_POST['is_active']);
				
				$validasi=$this->validasiform("edit");   
				if(count($validasi['arrayerror'])==0){
					$sqlin="";
				    $msg=$barge->update($id,$nama,$capacity,$description,$rgb_color,$is_active);
				
				}else{
					$msg['success']	=false;
					$msg['message']	=	"Terjadi kesalahan pengisian form";
					$msg['form_error']=$validasi['arrayerror'];
				}
			}else{
				$msg['success']=false;
				$msg['message']="Gagal menggambil data yang akan diubah, silahkan ulangi!";
			}
			echo json_encode($msg);   
		}else{
			$tpl  = new View("form_barge");
			$brg = new Ref_Barge_Model();
			
			$detail=$brg->getBarge($id);
			$tpl->detail = $detail;
			$tpl->url_add = url::current("add");
			$tpl->url_jsonData		= url::current("jsonData");
			$tpl->url_comboAjax		=url::current("comboAjax");
			$tpl->content = $tpl;
			$tpl->render(); 
		}
	}  
public function del($id){     
     global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        date_default_timezone_set("Asia/Jakarta");
        $barge=new Ref_Barge_Model();
         //VALIDASI FORM DULU
         $msg=$barge->delete($id);
        echo json_encode($msg);   
  } 
  public function validasiform($aksi="add",$kode_lama="") 
    {
        global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $msg=array();
      	$pesan=array();
    	if(trim($_POST['name'])==''){
            $pesan["name"]="Nama barge harus diisi!";   
            $msg[]="Nama barge harus diisi!";
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
  public function detail($id){
     global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $tpl  = new View("detail_barge");
        $master=new Master_Ref_Model();
        $detail=new stdClass;
        if(trim($id)=="" or  $id == null){
			$detail=new stdClass;
		}else{

			
            $detail= $db->select("*","barges")->where("id=$id")->get(0);

			
		}
        $tpl->detail=$detail;
       
        $this->tpl->content_title = "Detail Barge";
        $tpl->content = $tpl;
        $tpl->render();
  }
 
}
?>