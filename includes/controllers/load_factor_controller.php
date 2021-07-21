<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Load_Factor_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        global $dcistem;
       
	}
	
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("load_factor");
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
        $category    = $requestData['columns'][1]['search']['value'];
        $status    = $requestData['columns'][5]['search']['value'];
        $contractor    = $requestData['columns'][4]['search']['value'];
        if( trim($contractor)<>"" ){   //name
            $keriteria[]="pit.contractor_id =".$contractor."";
        }
        if( trim($status)<>"" ){   //name
            $keriteria[]="ifnull(state,'prospect') ='".$status."'";
        }
        if(trim($nama)<>""){
            $keriteria[]="( block_name like'%".$nama."%' )" ;
        }
        $filter=$modelsortir->fromFormcari($keriteria,"and");
       $draw=$_REQUEST['draw'];
		$length=$_REQUEST['length'];
		$start=$_REQUEST['start'];
        
        $cols=array(0=>"id",
                    1=>"berlaku_mulai");
        $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
            
        $list_qry=$db->select("SQL_CALC_FOUND_ROWS id,load_factor_expit,load_factor_barging,berlaku_mulai,berlaku_sampai,
        ifnull(closed,0) closed","load_factor")->where($filter)->lim($start,$length);//->orderby($order)
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
            $ListData[$i]['no']=$no;
            $ListData[$i]['id']=$data->id;
            $ListData[$i]['load_factor_expit']=$data->load_factor_expit;
            $ListData[$i]['load_factor_barging']=$data->load_factor_barging;
            $ListData[$i]['berlaku_mulai']=$data->berlaku_mulai;
            $ListData[$i]['berlaku_sampai']=$data->berlaku_sampai;
            $ListData[$i]['closed']=$data->closed=="1"?"Ya":"Belum";
            $url_del      = url::current("del",$data->id);
			$url_edit =url::current("edit",$data->id);
            $url_recompute =url::current("recompute",$data->id);
           	$tombol=$login->privilegeInputForm("link","","","<i class=\"fa fa-gear\"></i>",$this->page->PageID,"edit","title='Edit Data' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs btn-edit-data\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\"");
            $tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-fw fa-refresh\"></i>",$this->page->PageID,"recompute","title='Recompute Data' href=\"".$url_recompute."\" class=\"btn btn-primary btn-xs btn-reconcile-data\" ");
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
	        $berlaku_mulai	=$_POST['berlaku_mulai'];
	        $berlaku_sampai	=$_POST['berlaku_sampai'];
            $load_factor_expit	=$_POST['load_factor_expit'];
	        $load_factor_barging	=$_POST['load_factor_barging'];
	        $closed	=$_POST['closed'];
            
	        $validasi=$this->validasiform("add");   
	        if(count($validasi['arrayerror'])==0){
		        $TglSkrg=date("Y-m-d H:i:s");
		        $sqlin="";
                
                $tgl_mulai  = explode("/",$berlaku_mulai);
		        $tanggal_mulai	= $tgl_mulai[2]."-".$tgl_mulai[1]."-".$tgl_mulai[0];
				$tanggal_mulai_val	= $master->scurevaluetable($tanggal_mulai,"string");
                $tgl_sampai  = explode("/",$berlaku_sampai);
		        $tanggal_akhir	= $tgl_sampai[2]."-".$tgl_sampai[1]."-".$tgl_sampai[0];
				$tanggal_akhir_val	= $master->scurevaluetable($tanggal_akhir,"string");
                
                $load_factor_expit_val	=$master->scurevaluetable($load_factor_expit,"number",false);                
		        $load_factor_barging_val	=$master->scurevaluetable($load_factor_barging,"number",false); 
		       
		        $closed_val	=$master->scurevaluetable($closed,"string");
		        
				$cols="berlaku_mulai,berlaku_sampai,load_factor_expit,load_factor_barging,closed";
				$values="$tanggal_mulai_val,$tanggal_akhir_val,$load_factor_expit_val,$load_factor_barging_val,$closed_val";
				$sqlin="INSERT INTO load_factor ($cols) VALUES ($values);";
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
			
	    	$tpl  = new View("form_load_factor");
    	    //$list_contractor=Model::getOptionList("partner","id","name","name ASC","ifnull(is_contractor,0)=1"); 
	    
       // $tpl->list_contractor =$list_contractor;
			
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
	    if(trim($id)<>"" and  $id == null){
	       $msg['success']=false;
	        $msg['message']="Gagal menggambil data yang akan diubah, silahkan ulangi!";
        }else{
             $berlaku_mulai	=$_POST['berlaku_mulai'];
	        $berlaku_sampai	=$_POST['berlaku_sampai'];
            $load_factor_expit	=$_POST['load_factor_expit'];
	        $load_factor_barging	=$_POST['load_factor_barging'];
	        $closed	=$_POST['closed'];
            
	        $validasi=$this->validasiform("edit");   
	        if(count($validasi['arrayerror'])==0){
		        $TglSkrg=date("Y-m-d H:i:s");
		        $sqlin="";
                 $tgl_mulai  = explode("/",$berlaku_mulai);
		        $tanggal_mulai	= $tgl_mulai[2]."-".$tgl_mulai[1]."-".$tgl_mulai[0];
				$tanggal_mulai_val	= $master->scurevaluetable($tanggal_mulai,"string");
                $tgl_sampai  = explode("/",$berlaku_sampai);
		        $tanggal_akhir	= $tgl_sampai[2]."-".$tgl_sampai[1]."-".$tgl_sampai[0];
				$tanggal_akhir_val	= $master->scurevaluetable($tanggal_akhir,"string");
                
                $load_factor_expit_val	=$master->scurevaluetable($load_factor_expit,"number",false);                
		        $load_factor_barging_val	=$master->scurevaluetable($load_factor_barging,"number",false); 
		       
		        $closed_val	=$master->scurevaluetable($closed,"string");
		        
				$cols_and_vals="berlaku_mulai=$tanggal_mulai_val,berlaku_sampai=$tanggal_akhir_val,load_factor_expit=$load_factor_expit_val,
                load_factor_barging=$load_factor_barging_val,closed=$closed_val";
				$sqlin="UPDATE load_factor SET $cols_and_vals WHERE id=$id;";
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
	       $tpl  = new View("form_load_factor");
           $lf = new Load_Factor_Model();
    	   $list_contractor=Model::getOptionList("partner","id","name","name ASC","ifnull(is_contractor,0)=1"); 
	    
        $tpl->list_contractor =$list_contractor;
            $detail=$lf->getLoadFactor($id);
			$tpl->detail = $detail;
	    	$tpl->url_add = url::current("add");
	    	$tpl->url_jsonData		= url::current("jsonData");
        	$tpl->url_comboAjax		=url::current("comboAjax");
	    	$tpl->content = $tpl;
	        $tpl->render(); 
    }
}
public function recompute($id,$proses=""){     
    global $dcistem;
    $db   = $dcistem->getOption("framework/db");
    $master=new Master_Ref_Model();
    date_default_timezone_set("Asia/Jakarta");   
    $lf = new Load_Factor_Model();
    $detail=$lf->getLoadFactor($id);
    if(trim($proses)=="save")
	{    
	   $msg=array();
	    if(trim($id)=="" or  $id == null){
            $msg['success']=false;
           	$msg['message']="Error, ID Load Factor tidak boleh kosong";
                
        }else{
            $filter="tanggal BETWEEN '".$detail->berlaku_mulai."' and '".$detail->berlaku_sampai."'";	       
            $list_qry=$db->select("SQL_CALC_FOUND_ROWS id,tanggal,shift","daily_transit_ore")->where($filter)->lim();//->orderBy($order) 
            $list_data=array();
            $i=0;
            $total_ritase   =0;
            $total_quantity =0;
            $jml_gagal      =0;
            $jml_data       =0;
            $jml_filtered   = 0;
            while($data = $db->fetchObject($list_qry))
            {    
                if($i==0){
                    $filtered_qry=$db->query("SELECT FOUND_ROWS() jml_filtered;");
                    $filtered_data=$db->fetchObject($filtered_qry);
                    //print_r($filtered_data);
                    $jml_filtered= $filtered_data->jml_filtered;
                }
                $sqlup="UPDATE daily_transit_ore_detail SET quantity= (ritase * ".$detail->load_factor_expit.") WHERE transit_ore_id=".$data->id."";
              
                $rsl1=$db->query($sqlup);
    			if(isset($rsl1->error) and $rsl1->error===true){
    		   	 		//$msg['success']=false;
                    	//$msg['message']="Error, ".$rsl->query_last_message;
                    $jml_gagal++;
    			}else{
                    //$dto=$db->select("sum(ritase) jml_ritase,sum(quantity) jml_qty","daily_transit_ore_detail")->where("transit_ore_id=".$data->id."")->get(0);
                    //if(!empty($dto)){
                        $sqld="UPDATE daily_transit_ore SET total_quantity=(total_ritase * ".$detail->load_factor_expit."),load_factor=".$detail->load_factor_expit." WHERE id=".$data->id."";
                       
                        $rsl2=$db->query($sqld);
                        if(isset($rsl2->error) and $rsl2->error===true){
                            //echo $rsl2->query_last_message;
                        }
                    //}
    			 
    			}
                 $i++;     
            }
            $jml_data= $jml_filtered;
             
            $msg_err="";
            $msg['success']=false;
            if($jml_data==0){                
                $msg_err="Tidak ditemukan data";
            }else{
                if($jml_data==$jml_gagal){
                    $msg_err="Semua data gagal diupdate";
                }else{                    
                    $msg['success']=true;
                    $msg_err="Dari sejumlah $jml_data data, gagal sebanyak $jml_gagal";
                    if($jml_gagal==0){
                        $msg_err="Sebanyak $jml_data data berhasil diupdate";
                    }
                }
            }
            $msg['message']=$msg_err;
	           
	        
	    }
	    echo json_encode($msg);   
        exit;
	}else{
	       $tpl  = new View("form_recompute");
           $list_contractor=Model::getOptionList("partner","id","name","name ASC","ifnull(is_contractor,0)=1"); 
	    
            $tpl->list_contractor =$list_contractor;
           
    	   $tpl->detail=$detail;
	    	$tpl->url_recompute = url::current("recompute",$id);
            $tpl->load_factor_id = $id;
	    	$tpl->url_jsonData		= url::current("jsonData");
     	    $tpl->url_ritase		= url::current("list_ritase");
        	$tpl->url_comboAjax		=url::current("comboAjax");
	    	$this->tpl->content = $tpl;
	        $this->tpl->render(); 
    }
}    
public function del($id){     
     global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        date_default_timezone_set("Asia/Jakarta");
        
         //VALIDASI FORM DULU
         $msg=array();
        if(trim($id)<>"")
        {
	        $Nama=$_POST['nama'];
	        $sqlin="DELETE FROM load_factor  WHERE id=$id;";
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
  public function validasiform($aksi="add",$kode_lama="") 
    {
        global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $msg=array();
      	$pesan=array();
  	     if(trim($_POST['berlaku_mulai'])<>""){
	        if((strlen(trim($_POST['berlaku_mulai']))<>10) or  (substr_count(trim($_POST['berlaku_mulai']),"/")<>2)){
	            $pesan["berlaku_mulai"]="Terjadi kesalahan format Tanggal";   
	            $msg[]="Terjadi kesalahan format Tanggal";
	        }
        }else{
        	$pesan["berlaku_mulai"]="Tanggal mulai berlaku tidak boleh kosong";   
	        $msg[]="Tanggal mulai berlaku tidak boleh kosong";
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
 public function list_ritase($category,$load_factor_id){     
     global $dcistem;
 	  	
       $db   = $dcistem->getOption("framework/db");
       $verification=new List_Verification_Model();
        $login=new Adm_Login_Model();
        $master=new Master_Ref_Model();
        $modelsortir	= new Adm_Sortir_Model();
        $transit_ore    =new Transit_Ore_Model();
        //echo "<pre>";print_r($_POST);echo "</pre>";exit;
        
        $referensi      = $master->referensi_session();
        $admin  = new Core_Admin_Model();
        $search=$admin->SearchDependingLevel("report_production","dto.contractor_id");
       // echo "<pre>";print_r($search);echo "</pre>";
        $keriteria      = array();
        $keriteria      = $search['array'];
        $login_as = $_SESSION["framework"]["login_as"]; 
        
        $requestData= $_REQUEST;
        
        $msg_error="";
        $lf=$db->select("id,load_factor_expit,load_factor_barging,berlaku_mulai,berlaku_sampai,closed","load_factor")->where("id=$load_factor_id")->get(0);
        if(empty($lf)){   //name
            $msg_error="Load Factor tidak ditemukan";
        }
        /*if( trim($category_id)=="" ){   //name
            $msg_error="Kategori tidak boleh kososng";
        }
        $contractor_id      = $_POST['contractor_id'];
        if( trim($contractor_id)<>"" ){   //name
            $keriteria[]="dto.contractor_id=".$contractor_id."";
        }else{
            $msg_error="Kontraktor harus diisi";
        }*/
        if( trim($lf->berlaku_mulai)<>"" and   trim($lf->berlaku_sampai)<>""){   //name
            $keriteria[]="dto.tanggal BETWEEN '".$lf->berlaku_mulai."' AND '".$lf->berlaku_sampai."'";
        }else{
            $msg_error="Tanggal berlaku load factor tidak lengkap";
        }
        
        $draw=$_REQUEST['draw'];
		$length=$_REQUEST['length'];
        $ListData      = array();
        $jml_filtered  = 0;
        $jml_data      = 0;
    
        if(trim($msg_error)==""){
            
            
            switch($category){
                case "stockpiling":
                    //echo $category;
                    $start=$_REQUEST['start'];
                    
                // if(trim($tahun)<>"" and trim($bulan)<>""){
                    $bulan_tahun=$tahun."-".$bulan;
                    
                    $filter=$modelsortir->fromFormcari($keriteria,"and");
                    $cols=array(0=>"dtod.transit_ore_id",
                                1=>"dto.tanggal",
                                2=>"dto.shift");
                    $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
                        //echo $filter;
                    $list_qry=$db->select("SQL_CALC_FOUND_ROWS dto.id,transaction_id,dto.contractor_id,p.name,p.alias,lokasi_pit_id,block_name,tanggal,DATE_FORMAT(tanggal,'%d/%m/%Y')  tgl,
                    entry_time,shift,dto.state,operator,sent_time,received_time,total_ritase,load_factor,total_quantity","daily_transit_ore dto
                    inner join partner p on p.id=dto.contractor_id
                    inner join lokasi_pit pit on pit.id=dto.lokasi_pit_id")
            		->where($filter)->orderBy("dto.tanggal asc")->lim($start,$length);//
                    $no=$start+1;
                    $i=0;
        
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
                        $ListData[$i]['Tanggal']=$data->tgl;
                        $ListData[$i]['shift']=$data->shift;
                        $ListData[$i]['entry_time']=$data->entry_time;
        
                        $ListData[$i]['sent_time']=$data->sent_time;
                        $ListData[$i]['received_time']=$data->received_time;
                        $ListData[$i]['pit']=$data->block_name;
                        $kontraktor=trim($data->alias)<>""?$data->name." (".$data->alias.")":$data->name;
                        $ListData[$i]['tujuan']="";
                        $ListData[$i]['contractor']=$data->alias;
                        $ListData[$i]['total_ritase']=$data->total_ritase;
                        $ListData[$i]['load_factor']=$data->load_factor;
                        $ListData[$i]['total_quantity']=$data->total_quantity;
        		          $ListData[$i]['checker']=$data->operator;
                        $ListData[$i]['Detail']=$transit_ore->getTransitOreDetail($data->id,"array");;
                        $url_del      = url::current("del",$data->id);
            			$url_edit =url::current("edit",$data->id);
                        $url_detail =url::current("detail",$data->id);
                       	$tombol          = "";
                       
        
                        $tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-file-text-o\"></i>",$this->page->PageID,"detail","title='Detail' href=\"".$url_detail."\" class=\"btn btn-primary btn-xs btn-detail-data\" target=\"_blank\"  role=\"".$data->id."\" data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\"");
        
                       	//$tombol=$login->privilegeInputForm("link","","","<i class=\"fa fa-gear\"></i>",$this->page->PageID,"edit","title='Edit Data' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs btn-edit-data\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\"");
                       // $tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-fw fa-trash-o\"></i>",$this->page->PageID,"del","title='Hapus Data Pelanggan' data-toggle=\"modal\" data-remote=\"true\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->PartnerNama."\" ");
            			//$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"del","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->pegID."\"");
        
                        $control=$tombol;
                        $ListData[$i]['Aksi']=$control;
                        $i++;
                        $no++;
                    }
                break;
                case 2: //rehandling
                     $start=$_REQUEST['start'];
                    
                // if(trim($tahun)<>"" and trim($bulan)<>""){
                    $bulan_tahun=$tahun."-".$bulan;
                    
                    $filter=$modelsortir->fromFormcari($keriteria,"and");
                    $cols=array(0=>"dtod.rehandling_ore_id",
                                1=>"dto.tanggal",
                                2=>"dto.shift");
                    $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
                        
                    $list_qry=$db->select("SQL_CALC_FOUND_ROWS dtod.id,dtod.rehandling_ore_id,dto.transaction_id,
                    dto.contractor_id,p.name contractor_name,p.alias contractor_alias,dto.barge_id,bg.name barge_name,
                    DATE_FORMAT(dto.tanggal,'%d/%m/%Y')  tgl,dto.tanggal,dto.entry_time,
                    dto.shift, ifnull(dtod.state,'') state,dto.sent_time,dto.received_time, dtod.equipment_id,eq.nomor no_dump_truck,dtod.ritase,
                    dtod.quantity,dtod.dome_asal,dm.name dome_asal_name,dl.location_name,
                    verified_time","daily_rehandling_ore_detail dtod
                    inner join daily_rehandling_ore dto on dto.id=dtod.rehandling_ore_id                   
                    inner join partner p on p.id=dto.contractor_id
                    inner join barges bg on bg.id=dto.barge_id
                    inner join equipment eq on eq.id=dtod.equipment_id
                    left join domes dm on dm.id=dtod.dome_asal
                    left join dome_locations dl on dl.id=dm.location_id")
            		->where($filter)->lim($start,$length);//->orderBy($order)
                    $no=$start+1;
                    $i=0;
                   
                    while($data = $db->fetchObject($list_qry))
                    {
                        if($i==0){
                            $filtered_qry=$db->query("SELECT FOUND_ROWS() jml_filtered;");
                            $filtered_data=$db->fetchObject($filtered_qry);
                            //print_r($filtered_data);
                            $jml_filtered= $filtered_data->jml_filtered;
                        }
                        
                        $ListData[$i]['No']=$no;//'<input type="checkbox" name="list_verification['.$data->id.']" id="list_verification'.$data->id.'" />';
                        $ListData[$i]['ID']=$data->id;
                        $kontraktor=trim($data->contractor_alias)<>""?$data->contractor_name." (".$data->contractor_alias.")":$data->contractor_name;
                        $ListData[$i]['Kontraktor']=$kontraktor;                
                        $ListData[$i]['Tanggal']=$data->tgl;
                        $ListData[$i]['shift']=$data->shift;
                        $ListData[$i]['barge_id']=$data->barge_id;     
                        $ListData[$i]['barge_name']=$data->barge_name;      
                         
                        $ListData[$i]['entry_time']=$data->entry_time;                    
                        $ListData[$i]['sent_time']=$data->sent_time;
                        $ListData[$i]['received_time']=$data->received_time;
                        $ListData[$i]['dump_truck']=$data->no_dump_truck;            
                        
                        $dome_asal=$data->dome_asal_name;
                        
                        if (trim($data->location_name)<>""){
                            $dome_asal=$dome_asal." (".$data->location_name.")";
                        }
                        $ListData[$i]['dome_asal']=$dome_asal;
                        $ListData[$i]['ritase']=$data->ritase;
                        $ListData[$i]['state']=$data->state;
                        $verifikasi=$data->state;
                        if (trim($data->verified_time)<>""){
                            $verifikasi=$verifikasi."<br /><small>(".$data->verified_time.")</small>";
                        }
                        $ListData[$i]['verifikasi']=$verifikasi;
                        $ListData[$i]['quantity']=$data->quantity;
                        $url_del      = url::current("del",$data->id);
            			$url_edit =url::current("update_ritase",$data->id);
                        $url_detail_ritase =url::current("detail_ritase",$data->id);
                        $url_verifikasi_ritase  = url::current("verification_ritase",$data->id);
                       	$tombol          = "<div class=\"btn-group\">";
                        //if($verification->privilegeVerification($data->category_id,$data->state)){
                        if($data->state=="draft" or $data->state==""){
                            $tombol=$tombol.$login->privilegeInputForm("button","","","<i class=\"fa fa-check-square-o\"></i>",$this->page->PageID,"verification_ritase","title='Verifikasi Ritase EX-PIT Ore' class=\"btn btn-primary btn-xs\" onclick=\"form_verifikasi('".$url_verifikasi_ritase."',".$category_id.");return false;\" role=\"".$data->id."\"");
                            //$tombol=$tombol.$login->privilegeInputForm("link","","","<i class=\"fa fa-check-square-o\"></i>",$this->page->PageID,"verification_ritase","title='Verifikasi Ritase EX-PIT Ore' href=\"".$url_verifikasi_ritase."\" class=\"btn btn-primary btn-xs btn-verifikasi-ritase\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\" role=\"".$data->id."\"");
                            $tombol=$tombol.$login->privilegeInputForm("button","","","<i class=\"fa fa-gear\"></i>",$this->page->PageID,"update_ritase","title='Edit Data' class=\"btn btn-primary btn-xs\" target=\"_blank\"  onclick=\"update('".$url_edit."',".$category_id.");return false;\" role=\"".$data->id."\"");
                            //$tombol=$tombol.$login->privilegeInputForm("link","","","<i class=\"fa fa-gear\"></i>",$this->page->PageID,"update","title='Edit Data' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs btn-update-data\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModalChild\" role=\"".$data->id."\"");
                        }   
                        $tombol=$tombol.$login->privilegeInputForm("button","","","<i class=\"fa fa-file-text-o\"></i>",$this->page->PageID,"detail_ritase","title='Detail Ritase EX-PIT Ore' class=\"btn btn-primary btn-xs\" onclick=\"detail_ritase('".$url_detail_ritase."',".$category_id.");return false;\" role=\"".$data->id."\"");
                        //$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-file-text-o\"></i>",$this->page->PageID,"detail","title='Detail' href=\"".$url_detail."\" class=\"btn btn-primary btn-xs btn-detail-data\" target=\"_blank\"  role=\"".$data->id."\" data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\"");
                       
                        $control=$tombol."</div>";  
                        $ListData[$i]['Aksi']=$control;
                        $i++;
                        $no++;
                    }
                
                break;
            }
        }
        $hasil['filter']=$tahun."-".$bulan;
        $hasil['draw']=$draw;
        $hasil['recordsTotal']=$jml_filtered;
        $hasil['recordsFiltered']=$jml_filtered;//$db->numRow($list_qry);
	    $hasil['data']=$ListData;
       
        echo json_encode($hasil);exit;
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