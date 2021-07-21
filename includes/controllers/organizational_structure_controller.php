<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Organizational_Structure_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        global $dcistem;
       
	}
	
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("organizational_structure");
        $db   = $dcistem->getOption("framework/db");
        $login=new Adm_Login_Model();
        $KodeDosen=$_SESSION["framework"]["ref_id"] ;
        $Username=$_SESSION["framework"]["current_user"]->Username ;
        $list_classification=Model::getOptionList("job_title_classification","id","name","id ASC"); 
	    
        $tpl->list_classification =$list_classification;
     
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
        
        $name      = $requestData['columns'][1]['search']['value'];
        //$category    = $requestData['columns'][1]['search']['value'];
        //$status    = $requestData['columns'][5]['search']['value'];
        $classification   = $requestData['columns'][4]['search']['value'];
        if( trim($classification)<>"" ){   //name
            $keriteria[]="jt.classification_id =".$classification."";
        }
        if( trim($status)<>"" ){   //name
            $keriteria[]="ifnull(state,'prospect') ='".$status."'";
        }
        if(trim($name)<>""){
            $keriteria[]="( jt.name like'%".$name."%' or  jt.name = '".$name."')" ;
        }
        $filter=$modelsortir->fromFormcari($keriteria,"and");
       $draw=$_REQUEST['draw'];
		$length=$_REQUEST['length'];
		$start=$_REQUEST['start'];
        
        $cols=array(0=>"os.parent_id",
                    1=>"os.id");
        $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
            
        $list_qry=$db->select("SQL_CALC_FOUND_ROWS os.id,os.parent_id,parent.name parent_name,os.name,
        os.description,os.urutan,os.active,os.job_title_id,jt.name job_title_name","organizational_structure os 
        left join organizational_structure parent on parent.id=os.parent_id
        left join job_title jt on jt.id=os.job_title_id")
		->where($filter)->orderby($order)->lim($start,$length);//
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
            $ListData[$i]['id']=$data->id;
            $ListData[$i]['name']=$data->name;
            $ListData[$i]['parent_id']=$data->parent_id;
            $ListData[$i]['parent_name']=$data->parent_name;
            $ListData[$i]['unit']="";
            $ListData[$i]['urutan']=$data->urutan;
            //$ListData[$i]['vendor_name']=$data->vendor_name;
            $ListData[$i]['active']=$data->active=="1"?"<i class=\"fa fa-check\"></i>":"";
            $ListData[$i]['job_title_id']=$data->job_title_id;
            $ListData[$i]['job_title_name']=$data->job_title_name;
            //$ListData[$i]['State']=$ref['state_pit'][$data->state];
            $url_del      = url::current("del",$data->id);
			$url_edit =url::current("edit",$data->id);
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
            //$classification	=trim($_POST['classification']);
		    $name	        =trim($_POST['name']);
            $description	=trim($_POST['description']);
	        $parent_id	    =trim($_POST['parent_id']);
            $order	        =trim($_POST['order']);
	        $active         =isset($_POST['active'])?"1":"" ;
            $job_title_id	    =trim($_POST['job_title_id']);
            
	        $validasi=$this->validasiform();   
	        if(count($validasi['arrayerror'])==0){
                $cek=$db->select("id","equipment")->where("nomor='".$nomor."'")->get(0);
                if(empty($cek)){
                    $TglSkrg=date("Y-m-d H:i:s");
    		        $sqlin="";
                    //$classification_val	=$master->scurevaluetable($classification,"number");
                    $name_val	=$master->scurevaluetable($name);
                    $description_val	=$master->scurevaluetable($description);
                    $parent_id_val	=$master->scurevaluetable($parent_id,"number");    
                    $job_title_id_val	=$master->scurevaluetable($job_title_id,"number");    			        
    		        $active_val	=$master->scurevaluetable($active);
                    $order_val	=$master->scurevaluetable($order);
    		        
    				$cols="parent_id,name,description,urutan,active,job_title_id";
    				$values="$parent_id_val,$name_val,$description_val,$order_val,$active_val,$job_title_id_val";
    				$sqlin="INSERT INTO organizational_structure ($cols) VALUES ($values);";
                    
        
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
   	                $msg['message']="Data dengan nomor body $nomor sudah ada "; 
                 }
	           
	        }else{
	             $msg['success']	=false;
	             $msg['message']	=	"Terjadi kesalahan pengisian form";
	             $msg['form_error']=$validasi['arrayerror'];
	        }
	        echo json_encode($msg);   
	    }else{
			
	    	$tpl  = new View("form_organizational");
    	    $list_classification=Model::getOptionList("job_title_classification","id","name","id ASC"); 
	    
           $tpl->list_classification =$list_classification;
			
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
             $name	        =trim($_POST['name']);
            $description	=trim($_POST['description']);
	        $parent_id	    =trim($_POST['parent_id']);
            $order	        =trim($_POST['order']);
	        $active         =isset($_POST['active'])?"1":"" ;
            $job_title_id	    =trim($_POST['job_title_id']);
            
	        $vendor_id	=trim($_POST['vendor_id']);
	        if(count($validasi['arrayerror'])==0){
                $TglSkrg=date("Y-m-d H:i:s");
                $sqlin="";
                $name_val	=$master->scurevaluetable($name);
                $description_val	=$master->scurevaluetable($description);
                $parent_id_val	=$master->scurevaluetable($parent_id,"number");    
                $job_title_id_val	=$master->scurevaluetable($job_title_id,"number");    			        
		        $active_val	=$master->scurevaluetable($active);
                $order_val	=$master->scurevaluetable($order);
		        
				$cols_and_vals="parent_id=$parent_id_val,name=$name_val,description=$description_val,urutan=$order_val,
                active=$active_val,job_title_id=$job_title_id_val";
			
                
                $sqlin="UPDATE organizational_structure SET $cols_and_vals WHERE id=$id;";

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
	       $tpl  = new View("form_organizational");
           
    	    $data=new Structure_Organizational_Model();
           
			$tpl->detail = $data->get_structure_organizational($id);
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
	        $sqlin="DELETE FROM  organizational_structure  WHERE id=$id;";
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
        
    	if(trim($_POST['name'])==''){
            $pesan["name"]="Nama struktur organisasi harus diisi!";   
            $msg[]="Nama struktur organisasi  harus diisi!";
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
        if(trim($kategori)=="structure_organizational"){
            $so=new Structure_Organizational_Model();
            $hasil=$so->json($nama,$aVars);
        }
        if(trim($kategori)=="job_title"){
            $job=new Job_Title_Model();
            $hasil=$job->json($nama,$aVars);
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