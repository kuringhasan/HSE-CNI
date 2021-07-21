<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Adm_Imei_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        global $dcistem;
       
	}
	
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("adm_imei");
        $db   = $dcistem->getOption("framework/db");
        $login=new Adm_Login_Model();
        $KodeDosen=$_SESSION["framework"]["ref_id"] ;
        $Username=$_SESSION["framework"]["current_user"]->Username ;
       /* $list_category=Model::getOptionList("keswan_obat_category","code","case when ifnull(category,'')='' 
        then code else concat(code,'-',category) end  category","category ASC"); 
	    
        $tpl->list_category =$list_category;*/
     
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
        $modelsortir	= new Adm_Sortir_Model();
        $login_as = $_SESSION["framework"]["login_as"]; 
        
        $requestData= $_REQUEST;
        
        $imei       = $requestData['columns'][0]['search']['value'];
        $ket    = $requestData['columns'][1]['search']['value'];
       
        if( trim($imei)<>"" ){   //name
            
            $keriteria[]="( imei like'%".$imei."%' or imei ='".$imei."' )" ;
        }
       
        if(trim($ket)<>""){
            $keriteria[]="( desciption like'%".$ket."%' or desciption ='".$ket."' )" ;
        }
        $filter=$modelsortir->fromFormcari($keriteria,"and");
        $draw=$_REQUEST['draw'];
		$length=$_REQUEST['length'];
		$start=$_REQUEST['start'];
        
        $cols=array(0=>"imei",
                    1=>"desciption");
        $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
            
        $list_qry=$db->select("SQL_CALC_FOUND_ROWS imei,desciption,created_time","device_imei")
		->where($filter)->lim($start,$length);//orderby($order)->
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
            $ListData[$i]['Imei']=$data->imei;
            $ListData[$i]['Desciption']=$data->desciption;
            $ListData[$i]['Created']=$data->created_time;
            $url_del      = url::current("del",$data->imei);
			$url_edit =url::current("edit",$data->imei);
           	$tombol=$login->privilegeInputForm("link","","","<i class=\"fa fa-gear\"></i>",$this->page->PageID,"edit","title='Edit Data' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs btn-edit-data\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\"");
           // $tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-fw fa-trash-o\"></i>",$this->page->PageID,"del","title='Hapus Data Pelanggan' data-toggle=\"modal\" data-remote=\"true\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->PartnerNama."\" ");
			$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"del","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->KasusID."\"");
			$control=$tombol;  
            $ListData[$i]['Tombol']=$control;
            $i++;
            $no++;
        }
       
         $hasil['draw']=$draw;
        $hasil['recordsTotal']=$jml_filtered;
        $hasil['recordsFiltered']=$jml_filtered;
	    $hasil['data']=$ListData;
         //echo $hasil;
        echo json_encode($hasil);exit;
         // echo "<pre>"; print_r($hasil);echo "</pre>";
	}
  public function add($proses=""){     
     global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        $login=new Adm_Login_Model();
        date_default_timezone_set("Asia/Jakarta");
        if(trim($proses)=="save")
		{
	        $imei	     =trim($_POST['imei']);
	        $description   =trim($_POST['description']);
	        
	        $validasi=$this->validasiform("add");   
	        if(count($validasi['arrayerror'])==0){
		        $TglSkrg=date("Y-m-d H:i:s");
                $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
                $imei_val	=$master->scurevaluetable($imei,"string");
		        $description_val	=$master->scurevaluetable($description);
		       
				$cols="imei,desciption,created_time";
				$values="$imei_val,$description_val,$tgl_skrg_val";
				$sqlin="INSERT INTO device_imei ($cols) VALUES ($values);";
				$rsl=$db->query($sqlin);
				if(isset($rsl->error) and $rsl->error===true){
			   	 		$msg['success']=false;
	                	$msg['message']="Error, ".$rsl->query_last_message;
				}else{
	                $msg['success']=true;
	                $msg['message']="Data sudah ditambahkan"; 
                   
	            }
	           
	        }else{
	             $msg['success']	=false;
	             $msg['message']	=	"Terjadi kesalahan pengisian form";
	             $msg['form_error']=$validasi['arrayerror'];
	        }
	        echo json_encode($msg);   
	    }else{
			
	    	$tpl  = new View("form_imei");
    	    
	    	$tpl->url_add = url::current("add");
	    	$tpl->url_jsonData		= url::current("jsonData");
        	$tpl->url_comboAjax		=url::current("comboAjax");
	    	$tpl->content = $tpl;
	        $tpl->render(); 
	    }
  } 
public function edit($imei_lama,$proses=""){     
    global $dcistem;
    $db   = $dcistem->getOption("framework/db");
    $master=new Master_Ref_Model();
    date_default_timezone_set("Asia/Jakarta");
    if(trim($proses)=="save")
	{    
	    if(trim($imei_lama)=="" or  $imei_lama == null){
            $msg['success']=false;
	        $msg['message']="Gagal menggambil data yang akan diubah, silahkan ulangi!";
                
        }else{
            $imei	     =trim($_POST['imei']);
	        $description   =trim($_POST['description']);
            
	        $validasi=$this->validasiform("edit");   
	        if(count($validasi['arrayerror'])==0){
		        $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
                $imei_val	=$master->scurevaluetable($imei,"string");
		        $description_val	=$master->scurevaluetable($description);
		       
				$cols_and_vals="imei=$imei_val,desciption=$description_val";
			
				$sqlin="UPDATE device_imei SET $cols_and_vals WHERE imei='".$imei_lama."';";
                
    
				$rsl=$db->query($sqlin);
				if(isset($rsl->error) and $rsl->error===true){
			   	 		$msg['success']=false;
	                	$msg['message']="Error, ".$rsl->query_last_message;
				}else{
	                $msg['success']=true;
	                $msg['message']="Perubahan data sudah disimpan"; 
                   
	            }
	           
	        }else{
	             $msg['success']	=false;
	             $msg['message']	=	"Terjadi kesalahan pengisian form";
	             $msg['form_error']=$validasi['arrayerror'];
	        }
	    }
	    echo json_encode($msg);   
	}else{
	       $tpl  = new View("form_imei");
            $detail=$db->select("imei,desciption,created_time","device_imei")->where("imei='".$imei_lama."'")->get(0);
           
			$tpl->detail = $detail;
	    	$tpl->url_add = url::current("add");
	    	$tpl->url_jsonData		= url::current("jsonData");
        	$tpl->url_comboAjax		=url::current("comboAjax");
	    	$tpl->content = $tpl;
	        $tpl->render(); 
    }
}  
public function del($imei){     
     global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        date_default_timezone_set("Asia/Jakarta");
        
         //VALIDASI FORM DULU
         $msg=array();
        if(trim($imei)=="" or  $imei == null)
        {
        	$msg['success']=false;
            $msg['message']="Gagal menggambil data yang akan dihapus, silahkan ulangi!";
        }else{
	        $Nama=$_POST['nama'];
	        $sqlin="DELETE FROM  device_imei  WHERE imei='".$imei."';";
	        $rsl=$db->query($sqlin);
			if(isset($rsl->error) and $rsl->error===true){
		   	 		$msg['success']=false;
                	$msg['message']="Error, ".$rsl->query_last_message;
			}else{
                $msg['success']=true;
                $msg['message']="Data sudah dihapus"; 
            }
        }
        echo json_encode($msg);   
  } 
  public function validasiform($aksi="add",$kode_lama="") 
    {
        global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $msg=array();
      	$pesan=array();
    	if(trim($_POST['imei'])==''){
            $pesan["imei"]="IMEI harus diisi!";   
            $msg[]="IMEI harus diisi!";
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
 
public function jsonData() {
 	global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $master                = new  Master_Ref_Model();
         
		if (sizeof($_POST) > 0) {
		    $aVars = $_POST;
		} else {
		    if (sizeof($_GET) > 0) {
		        $aVars = $_GET;
		    } else {
		        $aVars = array();
		    }
		}
		$pilihan=$aVars['pilih'];
        $nama=$aVars['nama'];
      
         $hasil=$master->jsonData($pilihan,$nama,$aVars);
         echo json_encode($hasil);  
    }   
    public function comboAjax($kategori) {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		$wilayah=new Ref_Wilayah_Model();
	
		if (sizeof($_POST) > 0) {
		    $aVars = $_POST;
		} else {
		    if (sizeof($_GET) > 0) {
		        $aVars = $_GET;
		    } else {
		        $aVars = array();
		    }
		}
        
        $parentcode=$aVars['parentkode'];
        $hasil=$wilayah->comboAjax($kategori,$parentcode,$aVars['nilai']);
        
        echo $hasil;
   }
 
}
?>