<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class App_Pagelist_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        global $dcistem;
       
	}
	
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("app_pagelist");
        $db   = $dcistem->getOption("framework/db");
        $login=new Adm_Login_Model();
        $KodeDosen=$_SESSION["framework"]["ref_id"] ;
        $Username=$_SESSION["framework"]["current_user"]->Username ;
        $list_category=Model::getOptionList("equipment_category","code","category","category ASC"); 
	    
        $tpl->list_category =$list_category;
     
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
        
        $nomor       = $requestData['columns'][1]['search']['value'];
        //$category    = $requestData['columns'][1]['search']['value'];
        //$status    = $requestData['columns'][5]['search']['value'];
        $webid    = $requestData['columns'][4]['search']['value'];
        if( trim($webid)<>"" ){   //name
            $keriteria[]="pg.AppPageListWebID ='".$webid."'";
        }
        if( trim($status)<>"" ){   //name
            $keriteria[]="ifnull(state,'prospect') ='".$status."'";
        }
        if(trim($nomor)<>""){
            $keriteria[]="( eq.nomor like'%".$nomor."%' or  eq.name like'%".$nomor."%')" ;
        }
        $filter=$modelsortir->fromFormcari($keriteria,"and");
       $draw=$_REQUEST['draw'];
		$length=$_REQUEST['length'];
		$start=$_REQUEST['start'];
        
        $cols=array(0=>"pg.AppPageListPageID",
                    1=>"pg.AppPageListParentID");
        $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
            
        $list_qry=$db->select("SQL_CALC_FOUND_ROWS pg.AppPageListPageID page_id,pg.AppPageListParentID parent_id,
        pg.AppPageListWebID web_id,pg.AppPageListPageName name,pg.AppPageListPageTitle title,pg.AppPageListPageController controller,
        pg.AppPageListPageOrder urutan,pg.AppPageListIcon icon,parent.AppPageListPageTitle parent_title","tbaapppagelist pg 
        left join tbaapppagelist parent on parent.AppPageListPageID=pg.AppPageListPageID")
		->where($filter)->orderby("")->lim($start,$length);//
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
            $ListData[$i]['web_id']=$data->web_id;
            $ListData[$i]['page_id']=$data->page_id;
            $ListData[$i]['name']=$data->name;
            $ListData[$i]['title']=$data->title;
            $ListData[$i]['controller']=$data->controller;
            $ListData[$i]['urutan']=$data->urutan;
            $ListData[$i]['icon']=$data->icon;
            $page_parent="[".$data->parent_id."] ".$data->parent_title;
            $ListData[$i]['parent_page']=$page_parent;
            $url_del      = url::current("del",$data->page_id);
			$url_edit =url::current("edit",$data->page_id);
           	$tombol=$login->privilegeInputForm("link","","","<i class=\"fa fa-gear\"></i>",$this->page->PageID,"edit","title='Edit Data' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs btn-edit-data\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\"");
           // $tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-fw fa-trash-o\"></i>",$this->page->PageID,"del","title='Hapus Data Pelanggan' data-toggle=\"modal\" data-remote=\"true\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->PartnerNama."\" ");
			$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"del","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->KasusID."\"");
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
        $login=new Adm_Login_Model();
        date_default_timezone_set("Asia/Jakarta");
        if(trim($proses)=="save")
		{
		    $web_id	=trim($_POST['web_id']);
	        $page_id	=trim($_POST['page_id']);
	        $name	=trim($_POST['name']);
	        $title	=trim($_POST['title']);
            $controller	=trim($_POST['controller']);
            $order	=trim($_POST['order']);
	        $icon	=trim($_POST['icon']);
            
	        $parent_id	=trim($_POST['parent_id']);
	        //$aktif	=trim($_POST['aktif']);
            
	        $validasi=$this->validasiform();   
	        if(count($validasi['arrayerror'])==0){
                $cek=$db->select("AppPageListPageID","tbaapppagelist")->where("AppPageListPageID=$page_id")->get(0);
                if(empty($cek)){
                    $TglSkrg=date("Y-m-d H:i:s");
    		        $sqlin="";
                    $web_id_val	=$master->scurevaluetable($web_id);
                    $page_id_val	=$master->scurevaluetable($page_id,"number");
                    $name_val	=$master->scurevaluetable($name);
                    $title_val	=$master->scurevaluetable($title);
                    $controller_val	=$master->scurevaluetable($controller);
                    $icon_val	=$master->scurevaluetable($icon);
                    $order_val	=$master->scurevaluetable($order,"number");
    		        $parent_id_val	=$master->scurevaluetable($parent_id,"number");
    		        
    				$cols="AppPageListPageID,AppPageListParentID,AppPageListWebID,AppPageListPageName,
                    AppPageListPageTitle,AppPageListPageController,AppPageListPageOrder,AppPageListIcon";
    				$values="$page_id_val,$parent_id_val,$web_id_val,$name_val,$title_val,$controller_val,$order_val,$icon_val";
    				$sqlin="INSERT INTO tbaapppagelist ($cols) VALUES ($values);";
                    
        
    				$rsl=$db->query($sqlin);
    				if(isset($rsl->error) and $rsl->error===true){
    			   	 		$msg['success']=false;
    	                	$msg['message']="Error, ".$rsl->query_last_message;
    				}else{
    	                $msg['success']=true;
    	                $msg['message']="Data sudah ditambahkan"; 
                       
    	            }
                 }else{
                    $msg['success']=false;
   	                $msg['message']="Data dengan Page ID  $page_id sudah ada "; 
                 }
	           
	        }else{
	             $msg['success']	=false;
	             $msg['message']	=	"Terjadi kesalahan pengisian form";
	             $msg['form_error']=$validasi['arrayerror'];
	        }
	        echo json_encode($msg);   
	    }else{
			
	    	$tpl  = new View("form_pagelist");
    	   
			
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
    date_default_timezone_set("Asia/Jakarta");
    if(trim($proses)=="save")
	{    
        $msg=array();
	    if(trim($id)=="" or  $id == null){
			$msg['success']=false;
	        $msg['message']="Gagal menggambil data yang akan diubah, silahkan ulangi!";
		}else{
           $web_id	=trim($_POST['web_id']);
	        $page_id	=trim($_POST['page_id']);
	        $name	=trim($_POST['name']);
	        $title	=trim($_POST['title']);
            $controller	=trim($_POST['controller']);
            $order	=trim($_POST['order']);
	        $icon	=trim($_POST['icon']);
            
	        $parent_id	=trim($_POST['parent_id']);
	        if(count($validasi['arrayerror'])==0){
                $TglSkrg=date("Y-m-d H:i:s");
                $sqlin="";
               $web_id_val	=$master->scurevaluetable($web_id);
                    $page_id_val	=$master->scurevaluetable($page_id,"number");
                    $name_val	=$master->scurevaluetable($name);
                    $title_val	=$master->scurevaluetable($title);
                    $controller_val	=$master->scurevaluetable($controller);
                    $icon_val	=$master->scurevaluetable($icon);
                    $order_val	=$master->scurevaluetable($order,"number");
    		        $parent_id_val	=$master->scurevaluetable($parent_id,"number");
    		        
    				$cols_and_vals="AppPageListPageID=$page_id_val,AppPageListParentID=$parent_id_val,AppPageListWebID=$web_id_val,
                    AppPageListPageName=$name_val,AppPageListPageTitle=$title_val,AppPageListPageController=$controller_val,
                    AppPageListPageOrder=$order_val,AppPageListIcon=$icon_val";
    				
    				$sqlin="UPDATE tbaapppagelist SET $cols_and_vals WHERE AppPageListPageID=$id;";
                 
    
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
	      	$tpl  = new View("form_pagelist");
            $page= new Core_Page_Model();
            $detail=$page->getData("AppPageListPageID=$id");
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
        
         //VALIDASI FORM DULU
        $msg=array();
        if(trim($id)=="" or  $id == null){
			$msg['success']=false;
	        $msg['message']="Gagal menggambil data yang akan diubah, silahkan ulangi!";
		}else{
	        $Nama=$_POST['nama'];
	        $sqlin="DELETE FROM  tbaapppagelist  WHERE AppPageListPageID=$id;";
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
        if(trim($_POST['page_id'])==''){
            $pesan["page_id"]="Page ID harus diisi!";   
            $msg[]="Page ID harus diisi!";
        }
    	if(trim($_POST['web_id'])==''){
            $pesan["web_id"]="Web ID harus diisi!";   
            $msg[]="Web ID harus diisi!";
        }
        if(trim($_POST['name'])==''){
            $pesan["name"]="Nama harus diisi!";   
            $msg[]="Nama harus diisi!";
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
  
	public function jsonData($kategori) {
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
        $hasil=array();
        if(trim($kategori)=="page_list"){
            $page=new Core_Page_Model();
            $hasil=$page->json($nama,$aVars);
        }
       
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