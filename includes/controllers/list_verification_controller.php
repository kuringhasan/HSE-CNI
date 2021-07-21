<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class List_Verification_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        global $dcistem;
      
	}
	
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("list_verification");
        $db   = $dcistem->getOption("framework/db");
        $login=new Adm_Login_Model();
       $master=new Master_Ref_Model();
       $verification=new List_Verification_Model();
        $KodeDosen=$_SESSION["framework"]["ref_id"] ;
        $Username=$_SESSION["framework"]["current_user"]->Username ;
        $list_bulan=$master->listarraybulan();
      	$tpl->list_bulan  = $list_bulan;
        $admin  = new Core_Admin_Model();
        $search=$admin->SearchDependingLevel("ref_contractor","id");
        //echo "<pre>";print_r($search);echo "</pre>";
        $filter="ifnull(is_contractor,0)=1";
        if(trim($search['string'])<>""){
            $filter="ifnull(is_contractor,0)=1 and ".$search['string'];
        }
        $list_kontraktor=Model::getOptionList("partner","id","name","",$filter); 
            $tpl->list_kontraktor =$list_kontraktor;
       	$url_form = url::current("create");  
        $url_verified = url::current("verification");     
       	$TombolTambah=$login->privilegeInputForm("link","","btn-create-data","<i class=\"fa fa-plus-circle\"></i>",$this->page->PageID,"create","title='Tambah Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_form."\" role=\"".$url_verified."\" class=\"btn btn-primary btn-xs\" data-target=\"#largeModal\"");
        //$TombolTambah=$TombolTambah.$login->privilegeInputForm("link","","","<i class=\"fa fa-plus-circle\"></i>",$this->page->PageID,"create","title='Tambah Data'  href=\"".$url_form."\" class=\"btn btn-primary btn-xs\" ");
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
       $verification=new List_Verification_Model();
        $login=new Adm_Login_Model();
        $master=new Master_Ref_Model();
        $modelsortir	= new Adm_Sortir_Model();
        $transit_ore    =new Transit_Ore_Model();
        $referensi      = $master->referensi_session();
        $admin  = new Core_Admin_Model();
        $search=$admin->SearchDependingLevel("report_production","dto.contractor_id");
       // echo "<pre>";print_r($search);echo "</pre>";
        $keriteria      = array();
        $keriteria      = $search['array'];
        $login_as = $_SESSION["framework"]["login_as"]; 
        
        $requestData= $_REQUEST;
        
        
        $eartag       = $requestData['columns'][1]['search']['value'];;
       
        $id_anggota     = $requestData['columns'][0]['search']['value'];
        $kontraktor     = $requestData['columns'][4]['search']['value'];
        //$petugas       = $requestData['columns'][8]['search']['value'];
        $bulan    = $requestData['columns'][7]['search']['value'];
        $tahun    = $requestData['columns'][8]['search']['value'];
        if( trim($tahun)<>"" ){   //name
            $keriteria[]="year(date_report)  ='".$tahun."'";
            $judul=$judul."<br />Tahun ".$tahun;
        }
        if( trim($bulan)<>"" ){   //name
                $nama_bln=$master->namabulanIN((int)$bulan);
                if( trim($tahun)<>"" ){
                    
                    $keriteria[]="DATE_FORMAT(date_report,'%Y-%m')='".$tahun."-".$bulan."'";
                    $judul=$judul."<br />".$nama_bln." ".$tahun;
                }
        }
        if( trim($petugas)<>"" ){   //name
            $keriteria[]="kps.petugas=".$petugas."";
        }
        if( trim($kontraktor)<>"" ){   //name
            $keriteria[]="dto.contractor_id=".$kontraktor."";
        }
        if( trim($id_anggota)<>"" ){   //name
            $keriteria[]="(a.C_ANGGOTA like'%".$id_anggota."%' or a.NAMA like'%".$id_anggota."%') ";
        }
        if(trim($eartag)<>""){
            $keriteria[]="( c.name like'%".$eartag."%' or  c.name ='".$eartag."' )" ;
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
        // if(trim($tahun)<>"" and trim($bulan)<>""){
            $bulan_tahun=$tahun."-".$bulan;
            
            $filter=$modelsortir->fromFormcari($keriteria,"and");
            $cols=array(0=>"vp.id",
                        1=>"date_report",
                        2=>"vp.contractor_id");
            $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
                
            $list_qry=$db->select("SQL_CALC_FOUND_ROWS vp.id,vp.contractor_id ,p.name contractor_name,p.alias contractor_alias,
            date_report,shift,vp.category_id,ca.name category_name,
            vp.current_verification,vp.current_verification,vpm.matrix_state,vpm.matrix_name,matrix_step,matrix_role,
            vp.created_time,vp.lastupdated_time,vpm.matrix_id,vpm.matrix_state","verification_production vp
            inner join partner p on p.id=vp.contractor_id
            inner join category_approval ca on ca.id=vp.category_id
            left join verification_production_matrix vpm on vpm.id=vp.current_verification and vpm.verification_id=vp.id")
    		->where($filter)->orderBy($order)->lim($start,$length);//->orderBy($order)
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
                $ListData[$i]['no']=$no;
                $ListData[$i]['id']=$data->id;                
                $ListData[$i]['tanggal']=$data->date_report;
                $ListData[$i]['shift']=$data->shift;
                $ListData[$i]['entry_time']=$data->entry_time;
                
                $ListData[$i]['sent_time']=$data->sent_time;
                $ListData[$i]['received_time']=$data->received_time;
                $ListData[$i]['pit']=$data->block_name;
                $kontraktor=trim($data->contractor_alias)<>""?$data->contractor_name." (".$data->contractor_alias.")":$data->contractor_name;
                $ListData[$i]['kontraktor']=$kontraktor;
                $ListData[$i]['kategori']=$data->category_name;
                $ListData[$i]['created']="<small>".$data->created_time."</small>";
                $ListData[$i]['lastupdated']="<small>".$data->lastupdated_time."</small>";
                $ListData[$i]['current_verification']=$data->matrix_name;
                $ListData[$i]['matrix_state']=$data->matrix_state;
                $ListData[$i]['Detail']=$transit_ore->getTransitOreDetail($data->id,"array");;
                $ListData[$i]['MatrixVerification']=$verification->getTrxVerificationMatrix($data->id,"array");;
                $url_del      = url::current("del",$data->id);
    			$url_edit =url::current("edit",$data->id);
                $url_detail =url::current("detail",$data->id);
                $url_verifikasi  = url::current("verification",$data->id);
               	$tombol          = "";
                
               $cek_ver= $verification->privilegeVerification($data->category_id,$data->matrix_id);
                
                if($cek_ver==true and ($data->matrix_state=="draft" or $data->matrix_state=="")){
                    $tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-check-square-o\"></i>",$this->page->PageID,"verification","title='Verifikasi EX-PIT Ore' href=\"".$url_verifikasi."\" class=\"btn btn-primary btn-xs btn-verifikasi-data\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\" role=\"".$data->id."\"");
                   
                }   
            
                $tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-file-text-o\"></i>",$this->page->PageID,"detail","title='Detail' href=\"".$url_detail."\" class=\"btn btn-primary btn-xs btn-detail-data\" target=\"_blank\"  role=\"".$data->id."\" data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\"");
               
               	//$tombol=$login->privilegeInputForm("link","","","<i class=\"fa fa-gear\"></i>",$this->page->PageID,"edit","title='Edit Data' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs btn-edit-data\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\"");
               // $tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-fw fa-trash-o\"></i>",$this->page->PageID,"del","title='Hapus Data Pelanggan' data-toggle=\"modal\" data-remote=\"true\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->PartnerNama."\" ");
    			//$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"del","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->pegID."\"");
             
                $control=$tombol;  
                $ListData[$i]['Aksi']=$control;
                $i++;
                $no++;
            }
            
           //$filter_count=$modelsortir->fromFormcari($keriteria_count,"and");
           
       // }
       $hasil['filter']=$tahun."-".$bulan;
        $hasil['draw']=$draw;
        $hasil['recordsTotal']=$jml_filtered;
        $hasil['recordsFiltered']=$jml_filtered;//$db->numRow($list_qry);
	    $hasil['data']=$ListData;
       
        echo json_encode($hasil);exit;
         // echo "<pre>"; print_r($hasil);echo "</pre>";
	}
 public function create($proses=""){     
     global $dcistem;
    $db   = $dcistem->getOption("framework/db");
    $master=new Master_Ref_Model();
    
    $ver=new  List_Verification_Model();
    date_default_timezone_set("Asia/Jakarta");
    if(trim($proses)=="draft")
	{   
	   $msg=array();
	    //create verification record
        $category_id    = $_POST['category_id'];
        $contractor_id  = $_POST['contractor_id'];        
        $tgl  = explode("/",$_POST["tanggal"]);		       
        $tanggal	= $tgl[2]."-".$tgl[1]."-".$tgl[0];		      
       
        $shift_id      =$_POST['shift_id'];
        
      // echo "<pre>";print_r($_POST);echo "</pre>";
        $create_ver= $ver->createProductionVerification($category_id,$contractor_id,$tanggal,$shift_id);
        //echo "<pre>";print_r($create_ver);echo "</pre>";
        $verification_id=$create_ver['verification_id'];
        $msg_err="";
        $msg['verification_id']="";
        if(trim($verification_id)<>""){
            $msg['verification_id']=$verification_id;
            switch($category_id){
                case 1:   //expit ore (table daily_transit_ore)             
                    $filter="dto.contractor_id=$contractor_id and dto.shift=$shift_id and DATE_FORMAT(dto.tanggal,'%Y-%m-%d')='".$tanggal."'";	       
                    $list_qry=$db->select("dtod.id,dtod.transit_ore_id","daily_transit_ore_detail dtod
                    inner join daily_transit_ore dto on dto.id=dtod.transit_ore_id")
            		->where($filter)->lim();//->orderBy($order) 
                    while($data = $db->fetchObject($list_qry))
                    {                        
                        $sqlup="UPDATE daily_transit_ore SET verification_id=$verification_id WHERE id=".$data->transit_ore_id;
                        $db->query($sqlup);                        
                    }
                break;
                case 2:   //expit ore (table daily_rehandling_ore)  
                    $filter="dro.contractor_id=$contractor_id and dro.shift=$shift_id and DATE_FORMAT(dro.tanggal,'%Y-%m-%d')='".$tanggal."'";	       
                    $list_qry=$db->select("dro.id,drod.rehandling_ore_id","daily_rehandling_ore_detail drod
                    inner join daily_rehandling_ore dro on dro.id=drod.rehandling_ore_id")
            		->where($filter)->lim();//->orderBy($order)   
                    while($data = $db->fetchObject($list_qry))
                    {                        
                        $sqlup="UPDATE daily_rehandling_ore SET verification_id=$verification_id WHERE id=".$data->rehandling_ore_id;
                        $db->query($sqlup);                        
                    }
                break;
            }//end switch
            
            $cur_step= $ver->getCurrentTrxVerificationMatrix($verification_id);
            $current_trx_verification_matrix_id=$cur_step->id;
            $hsl= $ver->setToDraft($verification_id,$current_trx_verification_matrix_id);            
            
            if($hsl['success']==true){
                $msg_err="Draft verifikasi sudah dibuat ";
            }else{
                $msg_err=$hsl['message'];
            }       
        }
        
        $msg =$create_ver;
        $msg['message']=$create_ver['message'].". ".$msg_err;
	    echo json_encode($msg);   
	   
    }else{
		   
	    	$tpl  = new View("form_verification_production");
            $list_category=Model::getOptionList("category_approval","id","name"); 
            $tpl->list_category =$list_category;
            
           
            $list_shift=Model::getOptionList("work_shifts","shift","shift","shift asc",""); 
            $tpl->list_shift =$list_shift;
            $default_contractor=isset($_POST['contractor_id'])?$_POST['contractor_id']:"";
            $admin=new Core_Admin_Model();
            $list_kontraktor=$admin->optionListContractorDependingLevel("Kontraktor",$default_contractor,"","class=\"input\""); 
            //print_r($list_kontraktor);
            $tpl->list_kontraktor =$list_kontraktor;
            $tpl->url_list_ritase = url::current("list_ritase");
	    	$tpl->url_edit = url::current("edit",$kode_lama);
            $tpl->url_checkdata =url::current("checkdata");
	    	$tpl->url_jsonData		= url::current("jsonData");
        	$tpl->url_comboAjax		=url::current("comboAjax");
            $tpl->content = $tpl;
	        $tpl->render(); 
	    /*	$this->tpl->content = $tpl;
	        $this->tpl->render(); */
    }
  } 
  public function verification($verification_id,$proses=""){     
     global $dcistem;
    $db   = $dcistem->getOption("framework/db");
    $master=new Master_Ref_Model();
    $admin=new Core_Admin_Model();
    $ver=new  List_Verification_Model();
    date_default_timezone_set("Asia/Jakarta");
    if(trim($proses)=="draft")
	{
	    $msg=array();
	    //create verification record
        $msg['action']="draft"; 
        $category_id    = $_POST['category_id'];
        $contractor_id  = $_POST['contractor_id'];        
        $tgl  = explode("/",$_POST["tanggal"]);		       
        $tanggal	= $tgl[2]."-".$tgl[1]."-".$tgl[0];		      
       
        $shift_id      =$_POST['shift_id'];
        
      
        $msg_err="";
        if(trim($verification_id)<>""){
            switch($category_id){
                case 1:   //expit ore (table daily_transit_ore)             
                    $filter="dto.contractor_id=$contractor_id and dto.shift=$shift_id and DATE_FORMAT(dto.tanggal,'%Y-%m-%d')='".$tanggal."'";	       
                    $list_qry=$db->select("dtod.id,dtod.transit_ore_id","daily_transit_ore_detail dtod
                    inner join daily_transit_ore dto on dto.id=dtod.transit_ore_id")
            		->where($filter)->lim();//->orderBy($order) 
                    while($data = $db->fetchObject($list_qry))
                    {                        
                        $sqlup="UPDATE daily_transit_ore SET verification_id=$verification_id WHERE id=".$data->transit_ore_id;
                        $db->query($sqlup);                        
                    }
                break;
                case 2:   //expit ore (table daily_rehandling_ore)  
                    $filter="dro.contractor_id=$contractor_id and dro.shift=$shift_id and DATE_FORMAT(dro.tanggal,'%Y-%m-%d')='".$tanggal."'";	       
                    $list_qry=$db->select("dro.id,drod.rehandling_ore_id","daily_rehandling_ore_detail drod
                    inner join daily_rehandling_ore dro on dro.id=drod.rehandling_ore_id")
            		->where($filter)->lim();//->orderBy($order)   
                    while($data = $db->fetchObject($list_qry))
                    {                        
                        $sqlup="UPDATE daily_rehandling_ore SET verification_id=$verification_id WHERE id=".$data->rehandling_ore_id;
                        $db->query($sqlup);                        
                    }
                break;
            }//end switch
            
            $cur_step= $ver->getCurrentTrxVerificationMatrix($verification_id);
            $current_trx_verification_matrix_id=$cur_step->id;
            $hsl= $ver->setToDraft($verification_id,$current_trx_verification_matrix_id);
            if($hsl['success']==true){
                $msg['success']	=true;
                $msg['message']	="Data verifikasi sudah diupdate ".$hsl['message'];
            }else{
                $msg['success']	=false;
                $msg['message']	=$hsl['message'];
            }   
            
             
            
        }else{
            $msg['success']	=false;
            $msg['message']	="verification_id tidak boleh kosong";
        }
	    echo json_encode($msg);   
    }elseif(trim($proses)=="verified")
	{    
        $category_id      = $_POST['category_id'];
        $contractor_id      = $_POST['contractor_id'];                
        $tgl  = explode("/",$_POST["tanggal"]);		       
        $tanggal	= $tgl[2]."-".$tgl[1]."-".$tgl[0];
        $shift_id      =$_POST['shift_id'];
        $msg['action']="verified";
        $validasi=$this->validasiform("verification");
        if(count($validasi['arrayerror'])==0){
            $TglSkrg		     =date("Y-m-d H:i:s");
        	$tgl_skrg_val		 =$master->scurevaluetable($TglSkrg,"string");
            /** cek apakah semua ritase sudah diveridikasi */
            $vm=$ver->getProductionVerification($verification_id);
            // echo "<pre>";print_r($vm);echo "</pre>";exit;
            if(!empty($vm)){
                $current_trx_verification_matrix_id=$vm->current_verification;
                switch($category_id){
                    case 1:
                        $filter=" dto.contractor_id=".$contractor_id." and DATE_FORMAT(dto.tanggal,'%Y-%m-%d')='".$tanggal."' 
                        and dto.shift=".$shift_id."";
                        $cek=$db->select("count(dtod.id) jml_data,sum(case when ifnull(dtod.state,'draft')='verified' then 1 else 0 end) jml_verified","daily_transit_ore_detail dtod 
                        inner join daily_transit_ore dto on dto.id=dtod.transit_ore_id")->where($filter)->get(0);
                        if($cek->jml_data==$cek->jml_verified){
                            // ubah ke step lanjut, dengan state=draft
                           // $cur_step= $ver->getCurrentTrxVerificationMatrix($verification_id);
                           $msg= $ver->setVerified($verification_id,$current_trx_verification_matrix_id);
                            
                        }else{
                            $msg['success']=false;
                            
                            $msg['message']="Error, belum bisa mem-verifikasi shift sebelum semua data ritse diverifikasi";
                        }
                    break;
                    case 2:
                        $filter=" dto.contractor_id=".$contractor_id." and DATE_FORMAT(dto.tanggal,'%Y-%m-%d')='".$tanggal."' 
                        and dto.shift=".$shift_id."";
                        $cek=$db->select("count(dtod.id) jml_data,sum(case when ifnull(dtod.state,'draft')='verified' then 1 else 0 end) jml_verified","daily_rehandling_ore_detail dtod
                inner join daily_rehandling_ore dto on dto.id=dtod.rehandling_ore_id")->where($filter)->get(0);
                        if($cek->jml_data==$cek->jml_verified){
                            // ubah ke step lanjut, dengan state=draft
                           // $cur_step= $ver->getCurrentTrxVerificationMatrix($verification_id);
                           $msg= $ver->setVerified($verification_id,$current_trx_verification_matrix_id);
                            
                        }else{
                            $msg['success']=false;
                            $msg['message']="Error, belum bisa mem-verifikasi shift sebelum semua data ritse diverifikasi";
                        }
                    break;
                }
            }else{
                $msg['success']	=false;
                $msg['message']	="Silahkan buat dulu draft verifikasi";
            }
        }else{
             $msg['success']	=false;
             $msg['message']	="Terjadi kesalahan pengisian form";
             $msg['form_error']=$validasi['arrayerror'];
        }
	    echo json_encode($msg);   
	}else{
		   
	    	$tpl  = new View("form_verification_production");
            $list_category=Model::getOptionList("category_approval","id","name"); 
            $tpl->list_category =$list_category;
            
           
            
            $list_shift=Model::getOptionList("work_shifts","shift","shift","shift asc",""); 
            $tpl->list_shift =$list_shift;
            
            
     
            $detail= $ver->getProductionVerification($verification_id);
           //echo "<pre>";print_r($detail);echo "</pre>";
           
            $tpl->detail =$detail;
            $default_contractor=isset($detail->contractor_id)?$detail->contractor_id:$_POST['contractor_id'];
            $list_kontraktor=$admin->optionListContractorDependingLevel("Kontraktor",$default_contractor,"","class=\"input\""); 
            //print_r($list_kontraktor);
            $tpl->list_kontraktor =$list_kontraktor;
            $tpl->url_list_ritase = url::current("list_ritase");
	    	$tpl->url_edit = url::current("edit",$kode_lama);
            $tpl->url_checkdata =url::current("checkdata");
	    	$tpl->url_jsonData		= url::current("jsonData");
        	$tpl->url_comboAjax		=url::current("comboAjax");
            $tpl->content = $tpl;
	        $tpl->render(); 
	    /*	$this->tpl->content = $tpl;
	        $this->tpl->render(); */
    }
  } 
  public function list_ritase($category_id){     
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
        if( trim($category_id)=="" ){   //name
            $msg_error="Kategori tidak boleh kososng";
        }
        $contractor_id      = $_POST['contractor_id'];
        if( trim($contractor_id)<>"" ){   //name
            $keriteria[]="dto.contractor_id=".$contractor_id."";
        }else{
            $msg_error="Kontraktor harus diisi";
        }
        //$tanggal      = $_POST['tanggal'];
        $tgl  = explode("/",$_POST["tanggal"]);		       
        $tanggal	= $tgl[2]."-".$tgl[1]."-".$tgl[0];		      
        if( trim($tanggal)<>"" and   trim($tanggal)<>"--"){   //name
            $keriteria[]="DATE_FORMAT(dto.tanggal,'%Y-%m-%d')='".$tanggal."'";
        }else{
            $msg_error="Tanggal harus diisi";
        }
        $shift_id      =$_POST['shift_id'];
        if( trim($shift_id)<>"" ){   //name
            $keriteria[]="dto.shift=".$shift_id."";
        }else{
            $msg_error="Shift harus diisi";
        }
        $draw=$_REQUEST['draw'];
		$length=$_REQUEST['length'];
        $ListData      = array();
        $jml_filtered  = 0;
        $jml_data      = 0;
      
        if(trim($msg_error)==""){
           
            switch($category_id){
                case 1:
                    
                    $start=$_REQUEST['start'];
                    
                // if(trim($tahun)<>"" and trim($bulan)<>""){
                    $bulan_tahun=$tahun."-".$bulan;
                    
                    $filter=$modelsortir->fromFormcari($keriteria,"and");
                    $cols=array(0=>"dtod.transit_ore_id",
                                1=>"dto.tanggal",
                                2=>"dto.shift");
                    $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
                        
                    $list_qry=$db->select("SQL_CALC_FOUND_ROWS dtod.id,dtod.transit_ore_id,dto.transaction_id,
                    dto.contractor_id,p.name contractor_name,p.alias contractor_alias,dto.lokasi_pit_id,pit.block_name pit_name,
                    DATE_FORMAT(dto.tanggal,'%d/%m/%Y')  tgl,dto.tanggal,dto.entry_time,
                    dto.shift, ifnull(dtod.state,'') state,dto.sent_time,dto.received_time, dtod.equipment_id,eq.nomor no_dump_truck,dtod.ritase,
                    dtod.quantity,dtod.tujuan_pengangkutan,dtod.dome_id,dm.name dome_name,dl.location_name,
                    dtod.barge_id,bg.name barge_name,verified_time","daily_transit_ore_detail dtod
                    inner join daily_transit_ore dto on dto.id=dtod.transit_ore_id
                    inner join equipment eq on eq.id=dtod.equipment_id
                    inner join partner p on p.id=dto.contractor_id
                    inner join lokasi_pit pit on pit.id=dto.lokasi_pit_id
                    left join domes dm on dm.id=dtod.dome_id
                    left join dome_locations dl on dl.id=dm.location_id
                    left join barges bg on bg.id=dtod.barge_id")
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
                        $ListData[$i]['pit_id']=$data->lokasi_pit_id;     
                        $ListData[$i]['pit']=$data->pit_name;       
                         
                        $ListData[$i]['entry_time']=$data->entry_time;                    
                        $ListData[$i]['sent_time']=$data->sent_time;
                        $ListData[$i]['received_time']=$data->received_time;
                        $ListData[$i]['dump_truck']=$data->no_dump_truck;            
                        
                        $tujuan=$data->tujuan_pengangkutan;
                        if (trim($data->dome_id)<>""){
                            $tujuan=$data->dome_name." (".$tujuan." ".$data->location_name.")";
                        }
                        if (trim($data->barge_id)<>""){
                            $tujuan=$data->barge_name." (".$tujuan.")";
                        }
                        $ListData[$i]['tujuan']=$tujuan;
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
  public function update_ritase($ritase_id,$category_id,$proses=""){     
     global $dcistem;
    $db   = $dcistem->getOption("framework/db");
    $master=new Master_Ref_Model();
    $settings= $master->settings();
    date_default_timezone_set($settings['production_time_zone']);
    switch($category_id){
        case 1:
            $expit=new Transit_Ore_Model();
            if(trim($proses)=="save")
        	{    
        	        $validasi=$this->validasiform("update_ritase");
        	        if(count($validasi['arrayerror'])==0){
        	           $expit_id         = $_POST['expit_id'];
        	            $TglSkrg		     =date("Y-m-d H:i:s");
                    	$tgl_skrg_val		 =$master->scurevaluetable($TglSkrg,"string");
                        
                        $tgl  = explode("/",$_POST["frm_tanggal"]);
        		       
        		        $tanggal	= $tgl[2]."-".$tgl[1]."-".$tgl[0];
                        $tanggal_val		 =$master->scurevaluetable($tanggal,"string");
                        $shift_id         = $_POST['shift_id'];
                        $shift_id_val		 =$master->scurevaluetable($shift_id,"number",false);
                        $pit_id         = $_POST['pit_id'];
                        $pit_id_val		 =$master->scurevaluetable($pit_id,"number",false);
                        $truck_id         = $_POST['truck_id'];
                        $truck_id_val		 =$master->scurevaluetable($truck_id,"number",false);
                        $tujuan         = $_POST['tujuan'];
                        $tujuan_val		 =$master->scurevaluetable($tujuan,"string");
                        $lokasi_dome         = $_POST['lokasi_dome'];
                        $lokasi_dome_val		 =$master->scurevaluetable($lokasi_dome,"number",false);
                        $dome_id         = $_POST['dome_id'];
                        $dome_id_val		 =$master->scurevaluetable($dome_id,"number",false);
                        $barge_id         = $_POST['barge_id'];
                        $barge_id_val		 =$master->scurevaluetable($barge_id,"number",false);
                        
                        $kontraktor         = $_POST['kontraktor'];
                        $kontraktor_val		 =$master->scurevaluetable($kontraktor,"number");
                        $ritase          = $_POST['ritase'];
                        $ritase_val		 =$master->scurevaluetable($ritase,"number",false);
                       
                        $cols_and_vals="lokasi_pit_id=$pit_id_val,tanggal=$tanggal_val,shift=$shift_id_val";
                        $sqlup ="UPDATE daily_transit_ore SET $cols_and_vals WHERE id=$expit_id;";
                        $rsl_cust=$db->query($sqlup);   
                        $psn="";
                        $success=false;         				
        				if(isset($rsl_cust->error) and $rsl_cust->error===true){
        			   	 		
        	                	$psn="Error, ".$rsl_cust->query_last_message;
        				}else{        				   
        	                $success=true;
        	                $psn="Data transaksi berhasil disimpan"; 
        	            }
                        $old_ritase=$db->select("id,ritase,dome_id","daily_transit_ore_detail")->where("id=$ritase_id")->get(0);
                        $cols_and_vals_detail="equipment_id=$truck_id_val,tujuan_pengangkutan=$tujuan_val,dome_id=$dome_id_val,
                        barge_id=$barge_id_val,ritase=$ritase_val,lastupdated=$tgl_skrg_val";
                        $sqlup_detail ="UPDATE daily_transit_ore_detail SET $cols_and_vals_detail WHERE id=$ritase_id;";
                        $rsl_detail=$db->query($sqlup_detail);            				
        				if(isset($rsl_detail->error) and $rsl_detail->error===true){
        			   	 		$success=$success==false?false:$success;
        	                	$psn=$psn.". Gagal update ritase, ".$rsl_detail->query_last_message;
        				}else{        				   
        	                $jumlah_ritase_asal=isset($old_ritase->ritase)?$old_ritase->ritase:0;
                            $dome_asal=$old_ritase->dome_id;
                            
                            if(($dome_asal==$dome_id) or ($dome_id=="")){
                                $sql_udome="UPDATE domes SET ritase_charge=(ritase_charge-".$jumlah_ritase_asal."+".$ritase_val."),
                                            ritase_tersisa_real=(ritase_tersisa_real-".$jumlah_ritase_asal."+".$ritase_val.") WHERE id=$dome_asal;";
                                $db->query($sql_udome);
                            }else{
                                // dome asal dikurangi data lama
                                $sql_udome_asal="UPDATE domes SET ritase_charge=(ritase_charge-".$jumlah_ritase_asal."),
                                            ritase_tersisa_real=(ritase_tersisa_real-".$jumlah_ritase_asal.") WHERE id=$dome_asal;";
                                $db->query($sql_udome_asal);
                                //dome baru ditambah dengan data baru
                                $sql_udome_baru="UPDATE domes SET ritase_charge=(ritase_charge+".$ritase_val."),
                                            ritase_tersisa_real=(ritase_tersisa_real+".$ritase_val.") WHERE id=$dome_id;";
                                $db->query($sql_udome_baru);
                            }
                            
        	                $psn= $success==false?$psn.". Update ritase berhasil disimpan":"Berhasil update transaksi dan ritase";
                            $success=$success==false?false:$success;
        	            }
                        $msg['success']	=$success;
       	                $msg['message']	=$psn;
                        
        	        }else{
        	             $msg['success']	=false;
        	             $msg['message']	="Terjadi kesalahan pengisian form";
        	             $msg['form_error']=$validasi['arrayerror'];
        	        }
        	    echo json_encode($msg);   
        	}else{
        		   
        	    	$tpl  = new View("form_expit_ritase");
                    $list_category=Model::getOptionList("category_approval","id","name"); 
                    $tpl->list_category =$list_category;
                    
                    $list_kontraktor=Model::getOptionList("partner","id","name","","ifnull(is_contractor,0)=1"); 
                    $tpl->list_kontraktor =$list_kontraktor;
                    
                    $list_shift=Model::getOptionList("work_shifts","shift","shift","shift asc",""); 
                    $tpl->list_shift =$list_shift;
                    
                    $list_barges =Model::getOptionList("barges","id","name","name asc","ifnull(is_active,0)=1");
                    $tpl->list_barges =$list_barges;
                    
                    $detail=$expit->getTransitOreDetailByID($ritase_id);
             
                  // echo "<pre>";print_r($detail);echo "</pre>";
                   
                    $tpl->detail =$detail;
                    $tpl->url_list_ritase = url::current("list_ritase");
        	    	$tpl->url_edit = url::current("edit",$kode_lama);
                    $tpl->url_checkdata =url::current("checkdata");
        	    	$tpl->url_jsonData		= url::current("jsonData");
                	$tpl->url_comboAjax		=url::current("comboAjax");
                    $tpl->content = $tpl;
        	        $tpl->render(); 
        	    /*	$this->tpl->content = $tpl;
        	        $this->tpl->render(); */
            }
         break;
         case 2:
            $rehandling=new Rehandling_Ore_Model();
            if(trim($proses)=="save")
        	{    
        	        $validasi=$this->validasiform("update_ritase");
        	        if(count($validasi['arrayerror'])==0){
        	            $rehandling_id         = $_POST['rehandling_id'];
        	            $TglSkrg		     =date("Y-m-d H:i:s");
                    	$tgl_skrg_val		 =$master->scurevaluetable($TglSkrg,"string");
                        
                        $tgl  = explode("/",$_POST["frm_tanggal"]);
        		       
        		        $tanggal	= $tgl[2]."-".$tgl[1]."-".$tgl[0];
                        $tanggal_val		 =$master->scurevaluetable($tanggal,"string");
                        $shift_id         = $_POST['shift_id'];
                        $shift_id_val		 =$master->scurevaluetable($shift_id,"number",false);
                        $barge_id         = $_POST['barge_id'];
                        $barge_id_val		 =$master->scurevaluetable($barge_id,"number",false);
                        
                        $truck_id         = $_POST['truck_id'];
                        $truck_id_val		 =$master->scurevaluetable($truck_id,"number",false);
                        
                        $lokasi_dome         = $_POST['lokasi_dome'];
                        $lokasi_dome_val		 =$master->scurevaluetable($lokasi_dome,"number",false);
                        $dome_asal         = $_POST['dome_asal'];
                        $dome_asal_val		 =$master->scurevaluetable($dome_asal,"number",false);
                        
                        $kontraktor         = $_POST['kontraktor'];
                        $kontraktor_val		 =$master->scurevaluetable($kontraktor,"number");
                        $ritase          = $_POST['ritase'];
                        $ritase_val		 =$master->scurevaluetable($ritase,"number",false);
                       
                        $cols_and_vals="barge_id=$barge_id_val,tanggal=$tanggal_val,shift=$shift_id_val,lastupdated=$tgl_skrg_val";
                        $sqlup ="UPDATE daily_rehandling_ore SET $cols_and_vals WHERE id=$rehandling_id;";
                        $rsl_cust=$db->query($sqlup);   
                        $psn="";
                        $success=false;         				
        				if(isset($rsl_cust->error) and $rsl_cust->error===true){
        			   	 		
        	                	$psn="Error, ".$rsl_cust->query_last_message;
        				}else{        				   
        	                $success=true;
        	                $psn="Data transaksi berhasil disimpan"; 
        	            }
                        $old_ritase=$db->select("id,ritase,dome_asal","daily_rehandling_ore_detail")->where("id=$ritase_id")->get(0);
                        $cols_and_vals_detail="equipment_id=$truck_id_val,dome_asal=$dome_asal_val,ritase=$ritase_val,lastupdated=$tgl_skrg_val";
                        $sqlup_detail ="UPDATE daily_rehandling_ore_detail SET $cols_and_vals_detail WHERE id=$ritase_id;";
                        $rsl_detail=$db->query($sqlup_detail);            				
        				if(isset($rsl_detail->error) and $rsl_detail->error===true){
        			   	 		$success=$success==false?false:$success;
        	                	$psn=$psn.". Gagal update ritase, ".$rsl_detail->query_last_message;
        				}else{        				   
                            $jumlah_ritase_lama=isset($old_ritase->ritase)?$old_ritase->ritase:0;
                            $dome_asal_lama=$old_ritase->dome_asal;
                            
                            if(($dome_asal_lama==$dome_asal) or ($dome_asal=="")){
                                $sql_udome="UPDATE domes SET ritase_loading=(ritase_loading-".$jumlah_ritase_lama."+".$ritase_val."),
                                            ritase_tersisa_real=(ritase_tersisa_real+".$jumlah_ritase_lama."-".$ritase_val.") WHERE id=$dome_asal_lama";
                                $db->query($sql_udome);
                            }else{
                                // dome asal dikurangi data lama
                                $sql_udome_lama="UPDATE domes SET ritase_loading=(ritase_loading-".$jumlah_ritase_lama."),
                                            ritase_tersisa_real=(ritase_tersisa_real+".$jumlah_ritase_lama.") WHERE id=$dome_asal_lama";
                                $db->query($sql_udome_lama);
                                //dome baru ditambah dengan data baru
                                $sql_udome_baru="UPDATE domes SET ritase_loading=(ritase_loading+".$ritase_val."),
                                            ritase_tersisa_real=(ritase_tersisa_real-".$ritase_val.") WHERE id=$dome_asal";
                                $db->query($sql_udome_baru);
                            }
        	                $psn= $success==false?$psn.". Update ritase berhasil disimpan":"Berhasil update transaksi dan ritase";
                            $success=$success==false?false:$success;
        	            }
                        $msg['success']	=$success;
       	                $msg['message']	=$psn;
                        
        	        }else{
        	             $msg['success']	=false;
        	             $msg['message']	="Terjadi kesalahan pengisian form";
        	             $msg['form_error']=$validasi['arrayerror'];
        	        }
        	    echo json_encode($msg);   
        	}else{
        		   
        	    	$tpl  = new View("form_rehandling_ritase");
                    $list_category=Model::getOptionList("category_approval","id","name"); 
                    $tpl->list_category =$list_category;
                    
                    $list_kontraktor=Model::getOptionList("partner","id","name","","ifnull(is_contractor,0)=1"); 
                    $tpl->list_kontraktor =$list_kontraktor;
                    
                    $list_shift=Model::getOptionList("work_shifts","shift","shift","shift asc",""); 
                    $tpl->list_shift =$list_shift;
                    
                    $list_barges =Model::getOptionList("barges","id","name","name asc","ifnull(is_active,0)=1");
                    $tpl->list_barges =$list_barges;
                    
                    $detail=$rehandling->getRehandlingOreDetailByID($ritase_id);
             
                  // echo "<pre>";print_r($detail);echo "</pre>";
                   
                    $tpl->detail =$detail;
                    $tpl->url_list_ritase = url::current("list_ritase");
        	    	$tpl->url_edit = url::current("edit",$kode_lama);
                    $tpl->url_checkdata =url::current("checkdata");
        	    	$tpl->url_jsonData		= url::current("jsonData");
                	$tpl->url_comboAjax		=url::current("comboAjax");
                    $tpl->content = $tpl;
        	        $tpl->render(); 
        	    /*	$this->tpl->content = $tpl;
        	        $this->tpl->render(); */
            }
         
         break;
    }//end switch
  } 
  public function verification_ritase($ritase_id,$category_id,$proses=""){     
     global $dcistem;
    $db   = $dcistem->getOption("framework/db");
    $master=new Master_Ref_Model();
    $settings= $master->settings();
    date_default_timezone_set($settings['production_time_zone']);
    switch($category_id){
        case 1:
            $expit=new Transit_Ore_Model();
            if(trim($proses)=="save")
        	{    
        	        $validasi=$this->validasiform("verification_ritase");
        	        if(count($validasi['arrayerror'])==0){
        	            $expit_id         = $_POST['expit_id'];
        	            $TglSkrg		     =date("Y-m-d H:i:s");
                    	$tgl_skrg_val		 =$master->scurevaluetable($TglSkrg,"string");
                        
                        $tgl  = explode("/",$_POST["frm_tanggal"]);
        		       
        		      
                       
                        
                        $cols_and_vals_detail="state='verified',verified_time=$tgl_skrg_val";
                        $sqlup_detail ="UPDATE daily_transit_ore_detail SET $cols_and_vals_detail WHERE id=$ritase_id;";
                        $rsl_detail=$db->query($sqlup_detail);            				
        				if(isset($rsl_detail->error) and $rsl_detail->error===true){
        			   	 		$msg['success']=false;
        	                	$msg['success']="Gagal verifikasi, ".$rsl_detail->query_last_message;
        				}else{        
        				    // cek verifikasi trx
                            $dt=$db->select("count(dto.id) jml_data,sum(case when dtod.state='verified' then 1 else 0 end)  jml_verified ","daily_transit_ore_detail dtod
                            inner join daily_transit_ore dto on dto.id=dtod.transit_ore_id")->where("dtod.transit_ore_id=".$expit_id)->get(0);
        	                if($dt->jml_data==$dt->jml_verified){
        	                    $cols_and_vals="state='verified',verification_date=$tgl_skrg_val";
                                $sqlup ="UPDATE daily_transit_ore SET $cols_and_vals WHERE id=$expit_id;";
        	                    $db->query($sqlup);  
        	                }
                            $msg['success']	=true;
       	                    $msg['message']	="Data sudah diverifikasi";
        	            }
                        
        	        }else{
        	             $msg['success']	=false;
        	             $msg['message']	="Terjadi kesalahan pengisian form";
        	             $msg['form_error']=$validasi['arrayerror'];
        	        }
        	    echo json_encode($msg);   
        	}else{
        		   
        	    	$tpl  = new View("form_verifikasi_expit_ritase");
                   
                    
                    $detail=$expit->getTransitOreDetailByID($ritase_id);
             
                  // echo "<pre>";print_r($detail);echo "</pre>";
                   
                    $tpl->detail =$detail;
                    $tpl->url_list_ritase = url::current("list_ritase");
        	    	$tpl->url_edit = url::current("edit",$kode_lama);
                    $tpl->url_checkdata =url::current("checkdata");
        	    	$tpl->url_jsonData		= url::current("jsonData");
                	$tpl->url_comboAjax		=url::current("comboAjax");
                    $tpl->content = $tpl;
        	        $tpl->render(); 
        	    /*	$this->tpl->content = $tpl;
        	        $this->tpl->render(); */
            }
         break;
         case 2:
            $rehandling=new Rehandling_Ore_Model();
            if(trim($proses)=="save")
        	{    
        	        $validasi=$this->validasiform("verification_ritase");
        	        if(count($validasi['arrayerror'])==0){
        	            $rehandling_id         = $_POST['rehandling_id'];
        	            $TglSkrg		     =date("Y-m-d H:i:s");
                    	$tgl_skrg_val		 =$master->scurevaluetable($TglSkrg,"string");
                        
                        $cols_and_vals_detail="state='verified',verified_time=$tgl_skrg_val";
                        $sqlup_detail ="UPDATE daily_rehandling_ore_detail SET $cols_and_vals_detail WHERE id=$ritase_id;";
                        $rsl_detail=$db->query($sqlup_detail);            				
        				if(isset($rsl_detail->error) and $rsl_detail->error===true){
        			   	 		$msg['success']=false;
        	                	$msg['success']="Gagal verifikasi, ".$rsl_detail->query_last_message;
        				}else{        
        				    // cek verifikasi trx
                            $dt=$db->select("count(dto.id) jml_data,sum(case when dtod.state='verified' then 1 else 0 end)  jml_verified ","daily_rehandling_ore_detail dtod
                            inner join daily_rehandling_ore dto on dto.id=dtod.rehandling_ore_id")->where("dtod.rehandling_ore_id=".$rehandling_id)->get(0);
        	                if($dt->jml_data==$dt->jml_verified){
        	                    $cols_and_vals="state='verified',verification_date=$tgl_skrg_val";
                                $sqlup ="UPDATE daily_rehandling_ore SET $cols_and_vals WHERE id=$rehandling_id;";
        	                    $db->query($sqlup);  
        	                }
                            $msg['success']	=true;
       	                    $msg['message']	="Data sudah diverifikasi";
        	            }
                        
        	        }else{
        	             $msg['success']	=false;
        	             $msg['message']	="Terjadi kesalahan pengisian form";
        	             $msg['form_error']=$validasi['arrayerror'];
        	        }
        	    echo json_encode($msg);   
        	}else{
        		   
        	    	$tpl  = new View("form_verifikasi_rehandling_ritase");
                   
                    
                    $detail=$rehandling->getRehandlingOreDetailByID($ritase_id);
             
                  // echo "<pre>";print_r($detail);echo "</pre>";
                   
                    $tpl->detail =$detail;
                    $tpl->url_list_ritase = url::current("list_ritase");
        	    	$tpl->url_edit = url::current("edit",$kode_lama);
                    $tpl->url_checkdata =url::current("checkdata");
        	    	$tpl->url_jsonData		= url::current("jsonData");
                	$tpl->url_comboAjax		=url::current("comboAjax");
                    $tpl->content = $tpl;
        	        $tpl->render(); 
        	    /*	$this->tpl->content = $tpl;
        	        $this->tpl->render(); */
            }
         
         break;
    }//end switch
  }
  public function detail_ritase($ritase_id,$category_id){     
     global $dcistem;
    $db   = $dcistem->getOption("framework/db");
    $master=new Master_Ref_Model();
    $settings= $master->settings();
    date_default_timezone_set($settings['production_time_zone']);
    switch($category_id){
        case 1:
            $expit=new Transit_Ore_Model();        		   
	    	$tpl  = new View("form_detail_expit_ritase");
            $detail=$expit->getTransitOreDetailByID($ritase_id);
          // echo "<pre>";print_r($detail);echo "</pre>";           
            $tpl->detail =$detail;           
            $tpl->content = $tpl;
	        $tpl->render(); 
        
         break;
         case 2:
         
         break;
    }//end switch
  }
  
  public function validasiform($jenis) 
    {
        global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $msg=array();
      	$pesan=array();
        $msg=array();
        $msj="";
        
        
        switch($jenis){
        	case "update_ritase":
                
                if(trim($_POST['frm_tanggal'])<>""){
    		        if((strlen(trim($_POST['frm_tanggal']))<>10) or  (substr_count(trim($_POST['frm_tanggal']),"/")<>2)){
    		            $pesan["frm_tanggal"]="Terjadi kesalahan format Tanggal";   
    		            $msg[]="Terjadi kesalahan format Tanggal";
    		        }
    	        }else{
    	        	$pesan["frm_tanggal"]="Tanggal tidak boleh kosong";   
    		        $msg[]="Tanggal tidak boleh kosong";
    	        }
                if(trim($_POST['kontraktor'])==""){
    		        $pesan["kontraktor"]="Kontraktor harus diisi";   
    		        $msg[]="Kontraktor harus diisi";
    		    }
                if(trim($_POST['ritase'])==""){
    		        $pesan["ritase"]="Ritase harus diisi";   
    		        $msg[]="Ritase harus diisi";
    		    }
                
            break;
            case "verification_ritase":
                 if(!isset($_POST['konfirmasi_ritase'])){
                    $pesan["konfirmasi_ritase"]="Harus memberikan tanda centang sebagai tanda konfirmasi verifikasi";   
                    $msg[]="Harus memberikan tanda centang sebagai tanda konfirmasi verifikasi";
                }
               
                
            break;
            
            case "verification":
                 if(!isset($_POST['konfirmasi'])){
                    $pesan["konfirmasi"]="Harus memberikan tanda centang sebagai tanda konfirmasi verifikasi";   
                    $msg[]="Harus memberikan tanda centang sebagai tanda konfirmasi verifikasi";
                }
            break;
           
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
        $tpl  = new View("detail_verification_production");
        $master=new Master_Ref_Model();
         $ver    =new List_Verification_Model();
        date_default_timezone_set("Asia/Jakarta");
        $detail=$ver->getProductionVerification($id);
         //echo "<pre>"; print_r($detail);echo "</pre>";
        $tpl->detail=$detail;
        $tpl->url_cetak      = url::current("cetak");
        $tpl->url_list_ritase = url::current("list_ritase");
        $this->tpl->content_title = "Detail Verification";
        $tpl->content = $tpl;
        $tpl->render();   
  } 
   public function cetak($jenis,$id) {
	   global $dcistem;
	    $db       = $dcistem->getOption("framework/db");
	    $master		= new Master_Ref_Model();
        $event=new List_Pelayanan_Model();
        $TglSkrg		=date("Y-m-d H:i:s");
	    //ob_start();
	   // $master->kopsurat("pdf");
	    //$kopsurat 	= ob_get_clean();
	    set_time_limit(1800);
   		ini_set("memory_limit","512M");
	   $detail=$event->getMutasi($id);
	   switch($jenis){
	       case "skks":
        	    ob_start();
        		$tpl  = new View("cetak/skks");
        		$ref_id=$_SESSION["framework"]["ref_id"] ;
        		// $tpl->kop_surat = $kopsurat;
        		
        	 
        	    
        		$tpl->detail=$detail;
        		$tpl->title ="SKKS : ".$detail->NoEartag;
        		$tpl->content = $tpl;
        		$tpl->render(); 
        	    $content 	= ob_get_clean();
        	
        		$pdf		=new mpdf60();
        		$mpdf		= $pdf->build("L","A4",array(15,15,25,15));
                $mpdf->charset_in = 'iso-8859-4';
        	  
        		$mpdf->WriteHTML($content);
        		$mpdf->Output('skks'.$id.'.pdf','I');
          break;
           case "pskt":
        	   ob_start();
        		$tpl  = new View("cetak/pskt");
        		$ref_id=$_SESSION["framework"]["ref_id"] ;
        		// $tpl->kop_surat = $kopsurat;
        		
        		$tpl->detail=$detail;
                
                $tanggal_sekarang=$master->detailtanggal($TglSkrg,2);
                //print_r($tanggal_sekarang);
                //exit;
                $tpl->get_pelayanan=$event->getListPalayananByCow($detail->cow_id);
        		$tpl->title ="Rekam Pelayanan : ".$detail->NoEartag;
        		$tpl->content = $tpl;
        		$tpl->render(); 
        	    $content 	= ob_get_clean();
        	
        		$pdf		=new mpdf60();
        		$mpdf		= $pdf->build("L","A4",array(15,15,20,20));
                $stylesheet = file_get_contents('templates/siat/theme/spring/css/print.css'); // external css
                $mpdf->WriteHTML($stylesheet,1);
        		$mpdf->SetHTMLFooter('<table width="100%" class="tbl-footer"><tr><td>'.$tanggal_sekarang['IndoHari'].', '.$tanggal_sekarang['Lengkap'].' </td><td class="pdf-footer-right" >Hal. {PAGENO} dari {nb}</td></tr></table>'); 
        	   //$mpdf->setFooter('Testing {PAGENO}');
                $mpdf->allow_charset_conversion = true;
        	    $mpdf->charset_in = 'iso-8859-4';
        	   // $PDFContent = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
        		//$mpdf->WriteHTML($PDFContent);
        		$mpdf->WriteHTML($content);
        		$mpdf->Output('rekam_pelayanan_'.$id.'.pdf','I');
          break;
           case "rekam_pelayanan":
        	    ob_start();
        		$tpl  = new View("cetak/rekam_pelayanan");
        		$ref_id=$_SESSION["framework"]["ref_id"] ;
        		// $tpl->kop_surat = $kopsurat;
        		
        		$tpl->detail=$detail;
                $tanggal_sekarang=$master->detailtanggal($TglSkrg,2);
                //print_r($tanggal_sekarang);
                //exit;
                $tpl->get_pelayanan=$event->getListPalayananByCow($detail->cow_id);
        		$tpl->title ="Rekam Pelayanan : ".$detail->NoEartag;
        		$tpl->content = $tpl;
        		$tpl->render(); 
        	    $content 	= ob_get_clean();
        	
        		$pdf		=new mpdf60();
        		$mpdf		= $pdf->build("L","A4",array(15,15,20,20));
                $stylesheet = file_get_contents('templates/siat/theme/spring/css/print.css'); // external css
                $mpdf->WriteHTML($stylesheet,1);
        		$mpdf->SetHTMLFooter('<table width="100%" class="tbl-footer"><tr><td>'.$tanggal_sekarang['IndoHari'].', '.$tanggal_sekarang['Lengkap'].' </td><td class="pdf-footer-right" >Hal. {PAGENO} dari {nb}</td></tr></table>'); 
        	   //$mpdf->setFooter('Testing {PAGENO}');
                $mpdf->allow_charset_conversion = true;
        	    $mpdf->charset_in = 'iso-8859-4';
        	   // $PDFContent = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
        		//$mpdf->WriteHTML($PDFContent);
        		$mpdf->WriteHTML($content);
        		$mpdf->Output('rekam_pelayanan_'.$id.'.pdf','I');
          break;
		}
        // PDF footer content     
		
		/*$stylesheet = file_get_contents('templates/siat/theme/spring/css/print.css'); // external css
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->SetHTMLFooter('<div class="pdf-footer">
	                        '.$pro['tahun'].' Copyright &copy; By Sentra Teknologi Polimer      F 009A/Rev0 - ISO 9001: 2008
	                      </div>'); 
	    $mpdf->setFooter('{PAGENO}');*/
	    

	    
   }
  
  public function Export($format="excel") {
	    global $dcistem;
	    $db       = $dcistem->getOption("framework/db");
	    $modelsortir	= new Adm_Sortir_Model();
	    $master=new Master_Ref_Model();
	 	$modelsortir	= new Adm_Sortir_Model();
	 	date_default_timezone_set("Asia/Jakarta");
	    set_time_limit(4800);
	    ini_set("memory_limit","512M"); 
        if (sizeof($_POST) > 0) {
            $aVars = $_POST;
        } else {
            if (sizeof($_GET) > 0) {
                $aVars = $_GET;
            } else {
                $aVars = array();
            }
        }
        $eartag         = $aVars['eartag'];
        $tanggal        = $aVars['tanggal'];
        $id_anggota     = $aVars['anggota_id'];
        $metode         = $aVars['metode'];
        $bulan          = $aVars['bulan'];
        $tahun          = $aVars['tahun'];
        if( trim($bulan)<>"" ){   //name
            $keriteria[]="LPAD(MONTH(tanggal_pelayanan), 2, '0')='".$bulan."'";
        }
        if( trim($tahun)<>"" ){   //name
            $keriteria[]="year(tanggal_pelayanan)='".$tahun."'";
        }/* */
        if( trim($tanggal)<>"" ){   //name
            //$tgl = $master->detailtanggal($tanggal,1);
            $keriteria[]="DATE_FORMAT(tanggal_pelayanan,'%d/%m/%Y')='".$tanggal."'";
           
        }
        if( trim($metode)<>"" ){   //name
            $keriteria[]="metode_perkawinan='".$metode."'";
        }
        if( trim($id_anggota)<>"" ){   //name
            $keriteria[]="(a.C_ANGGOTA like'%".$id_anggota."%' or a.NAMA like'%".$id_anggota."%') ";
        }
        if(trim($eartag)<>""){
            $keriteria[]="( c.name like'%".$eartag."%' or  c.name ='".$eartag."' )" ;
        }
        $filter=$modelsortir->fromFormcari($keriteria,"and");
        $order="anggota_id asc,tanggal_pelayanan desc";
	    switch($format){
	       case "excel":
                /* set_time_limit(0);
                ob_implicit_flush(true);
           		ob_end_flush();*/
                require_once 'plugins/PHPExcel/Classes/PHPExcel.php';
                $excel = new PHPExcel();
                
                $alphabet="A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
                
                $ar=explode(",",$alphabet);
                //	echo "<pre>";print_r($ar);echo "</pre>";
                $jumlah_kolom=80;
                $col=0;
                $no=0;
                $a=array();
                $level=0;
                while($col<$jumlah_kolom){
                	if($col % 26==0 and $no<>0){
                		$no=0;
                		$level++;
                		reset($ar);
                	}
                	if($level==0){
                		$a[$col]=$ar[$no];
                	}else{
                		$a[$col]=$ar[$level-1].$ar[$no];
                	}
                	$no++;
                	$col++;
                }
                //echo "<pre>";print_r($a);echo "</pre>";exit;
                
                $ref_id=$_SESSION["framework"]["ref_id"] ;
                //echo $ref_id;exit;
                
                
                
                $excel->getProperties()->setCreator("erp-kpbs")
                    				   ->setLastModifiedBy("admin")
                    				   ->setTitle("Perkawinan sapi")
                    				   ->setSubject("Perkawinan sapi")
                    				   ->setDescription("Perkawinan sapi")
                    				   ->setKeywords("Perkawinan, IB");
                $style_judul = array(
                    'font' => array('bold' => true),
                    'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                );
                
                $style_header = array(
                    'font' => array('bold' => true),
                    'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                						'rotation'   => 0,
                     					'wrap'       => true),
                    'borders' => array(
                        'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                        'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                        'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                        'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
                    )
                );
                $style_header1 = array(
                    'font' => array('bold' => true),
                    'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                						'rotation'   => 0,
                     					'wrap'       => true),
                    'borders' => array(
                        'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                        'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                        'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_NONE),
                        'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
                    )
                );
                $style_header2 = array(
                    'font' => array('bold' => true),
                    'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                						'rotation'   => 0,
                     					'wrap'       => true),
                    'borders' => array(
                        'top' => array('style'  => PHPExcel_Style_Border::BORDER_NONE),
                        'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                        'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
                    )
                );
                
                
                $style_row = array(
                    'borders' => array(
                        'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                        'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                        'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                        'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
                    )
                );
                $style_row1 = array(
                    'borders' => array(
                        'top' => array('style'  => PHPExcel_Style_Border::BORDER_NONE),
                        'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                        'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
                    )
                );
                $persen = array( 
                		'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE
                	);
                
                //echo "<pre>";print_r($aVars);echo "</pre>";exit;
                
                   // $tpl  = new View("upload_listmahasiswa");
                	
                 $total=$a[6].'4'."+".$a[7].'4'."+".$a[8].'4'."+".$a[9].'4';
                 $excel->setActiveSheetIndex(0)->mergeCells('A2:G2')->setCellValue('A2', 'Laporan Perkawinan Sapi');
                 $excel->getActiveSheet()->getStyle('A2')->applyFromArray($style_judul);
                 $key=30;
                 /*$excel->setActiveSheetIndex(0)->mergeCells($a[0].'3:'.$a[0].'4')->setCellValue($a[0].'3', 'No.')
                	  	->mergeCells($a[1].'3:'.$a[1].'4')->setCellValue($a[1].'3', 'Tanggal')
                      	->mergeCells($a[2].'3:'.$a[2].'4')->setCellValue($a[2].'3', 'No. Eartag')
                     	->mergeCells($a[3].'3:'.$a[3].'4')->setCellValue($a[3].'3', 'IB Ke');*/
                $excel->setActiveSheetIndex(0)->setCellValue($a[0].'3', 'No.')
                	  	->setCellValue($a[1].'3', 'Tanggal')
                      	->setCellValue($a[2].'3', 'No. Eartag')
                     	->setCellValue($a[3].'3', 'IB Ke')
                         ->setCellValue($a[4].'3', 'No Bull')
                         ->setCellValue($a[5].'3', 'Anggota');
                 $excel->getActiveSheet()->getStyle($a[0]."3")->applyFromArray($style_header);
                	$excel->getActiveSheet()->getColumnDimension($a[0])->setWidth(5);
                	//$excel->getActiveSheet()->getStyle($a[0]."4")->applyFromArray($style_header2);
                $excel->getActiveSheet()->getStyle($a[1]."3")->applyFromArray($style_header);
                	$excel->getActiveSheet()->getColumnDimension($a[1])->setWidth(14);
                	//$excel->getActiveSheet()->getStyle($a[1]."4")->applyFromArray($style_header2);
                $excel->getActiveSheet()->getStyle($a[2]."3")->applyFromArray($style_header);
                	$excel->getActiveSheet()->getColumnDimension($a[2])->setWidth(15);
                	//$excel->getActiveSheet()->getStyle($a[2]."4")->applyFromArray($style_header2);
                $excel->getActiveSheet()->getStyle($a[3]."3")->applyFromArray($style_header);
                	$excel->getActiveSheet()->getColumnDimension($a[3])->setWidth(7);
                	//$excel->getActiveSheet()->getStyle($a[3]."4")->applyFromArray($style_header2);
                $excel->getActiveSheet()->getStyle($a[4]."3")->applyFromArray($style_header);
                	$excel->getActiveSheet()->getColumnDimension($a[4])->setWidth(10);
                	//$excel->getActiveSheet()->getStyle($a[4]."4")->applyFromArray($style_header2);
                $excel->getActiveSheet()->getStyle($a[5]."3")->applyFromArray($style_header);
                	$excel->getActiveSheet()->getColumnDimension($a[5])->setWidth(28);
                	//$excel->getActiveSheet()->getStyle($a[5]."4")->applyFromArray($style_header2);
                    
                
                
                 
                $list_qry=$db->select("kpk.id,cow_id,c.name,c.koloni_name,a.C_ANGGOTA,case when ifnull(anggota_id,'')<>'' then concat( C_ANGGOTA,' - ',a.NAMA) 
                else null end pemilik,tanggal_pelayanan,DATE_FORMAT(tanggal_pelayanan,'%d/%m/%Y') TanggalPelayanan,
                jenis_pelayanan,pelayanan_nama,case when ifnull(pelayanan_alias,'')<>'' then concat( pelayanan_nama,' (',kpj.pelayanan_alias,')') 
                 else pelayanan_nama end jenis_pelayanan_nama,pejantan pejantan_id,jtn.no_pejantan no_pejantan,
                 jtn.nama pejantan_nama,metode_perkawinan,no_batch,pengamat_birahi,lama_birahi,
                 kawin_ke,dosis,biaya,last_action,breeding_status,         
                kps.laktasi_ke,kps.petugas,kp.pNama,kp.pAlias,kp.pGelarDepan,kp.pGelarBelakang,kps.created,kps.lastupdate,operator
                ","keswan_perkawinan kpk
                inner join keswan_pejantan jtn on jtn.id=kpk.pejantan
                inner join keswan_pelayanan_sapi kps on kps.id=kpk.pelayanan_id
                inner join keswan_pelayanan_jenis kpj on kpj.pelayanan_id=jenis_pelayanan
                inner join cow c on c.id=kps.cow_id
                inner join anggota a on a.ID_ANGGOTA=c.anggota_id
                inner join keswan_pegawai kp on kp.pID=kps.petugas")
                ->where($filter)->orderBy($order)->lim();//
                $no=1;
                $i=4;
                $jumlah_data=$db->numRow($list_qry);
                $ListData=array();
                while($data = $db->fetchObject($list_qry))
                {
                    
                    	$excel->setActiveSheetIndex(0)
                          	->setCellValue($a[0].$i, $no)
                           	->setCellValueExplicit($a[1].$i,$data->TanggalPelayanan, PHPExcel_Cell_DataType::TYPE_STRING)
                          	->setCellValueExplicit($a[2].$i,$data->name, PHPExcel_Cell_DataType::TYPE_STRING)
                          	->setCellValue($a[3].$i,$data->kawin_ke)
                              ->setCellValue($a[4].$i,$data->no_pejantan)
                              ->setCellValue($a[5].$i,$data->pemilik);
                          
                        $excel->getActiveSheet()->getStyle($a[0].$i)->applyFromArray($style_row)
                		   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                		   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                		  	$excel->getActiveSheet()->getStyle($a[1].$i)->applyFromArray($style_row)
                		   ->getAlignment()->setWrapText(true)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                		   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);;
                		  	$excel->getActiveSheet()->getStyle($a[2].$i)->applyFromArray($style_row)
                		   ->getAlignment()->setWrapText(true)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                		   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                          
                          $excel->getActiveSheet()->getStyle($a[3].$i)->applyFromArray($style_row)
                		  	->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                			  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                         $excel->getActiveSheet()->getStyle($a[4].$i)->applyFromArray($style_row)
                		  	->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                			  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $excel->getActiveSheet()->getStyle($a[5].$i)->applyFromArray($style_row)
                		  	->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                			  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                          
                   // sleep(1);
                   // $persen=round(($no/$jumlah_data)*100,2);
                   // $progressor=$persen;
                   // echo '<script ">window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                    
                    $i++;
                    $no++;
                }
                 echo '<script>alert("cek");window.parent.document.getElementById("spinner_download").style.display ="none";</script>';
              //  sleep(1);
              //  $progressor=100;
               // echo '<script ">window.parent.document.getElementById("progressor").style.width = "'.$progressor.'" + "%";</script>';
                $excel->getActiveSheet()->setTitle('Laporan Perkawinan');
                $excel->setActiveSheetIndex(0);
                $sekarang=date("dmY_His");
                $kode_dosen=$this->DataUmum->KodeDosen;
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="laporan_perkawinan_'.$sekarang.'.xls"');
                header('Cache-Control: max-age=0');
               
                $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
                $objWriter->save('php://output');
                
                exit;
            break;
            case "pdf":
                ob_start();
                
        		$tpl  = new View("cetak/laporan_perkawinan");
        		$ref_id=$_SESSION["framework"]["ref_id"] ;
        		$bulan_name=$master->namabulanIN((int)$aVars['bulan']);
                
                $tpl->data_bulan=trim($bulan_name)==""?$aVars['tahun']:$bulan_name." ".$aVars['tahun'];
                //$tanggal_sekarang=$master->detailtanggal($TglSkrg,2);
               
                $list_qry=$db->select("kpk.id,cow_id,c.name,a.C_ANGGOTA,case when ifnull(anggota_id,'')<>'' then concat( C_ANGGOTA,' - ',a.NAMA) 
                else null end pemilik,tanggal_pelayanan,DATE_FORMAT(tanggal_pelayanan,'%d/%m/%Y') TanggalPelayanan,
                jenis_pelayanan,pelayanan_nama,pejantan pejantan_id,jtn.no_pejantan no_pejantan,jtn.nama pejantan_nama,
                metode_perkawinan,no_batch,pengamat_birahi,lama_birahi,kawin_ke,dosis,biaya,last_action,breeding_status,         
                kps.laktasi_ke,kps.petugas,kp.pNama,kp.pAlias,kp.pGelarDepan,kp.pGelarBelakang","keswan_perkawinan kpk
                inner join keswan_pejantan jtn on jtn.id=kpk.pejantan
                inner join keswan_pelayanan_sapi kps on kps.id=kpk.pelayanan_id
                inner join keswan_pelayanan_jenis kpj on kpj.pelayanan_id=jenis_pelayanan
                inner join cow c on c.id=kps.cow_id
                inner join anggota a on a.ID_ANGGOTA=c.anggota_id
                inner join keswan_pegawai kp on kp.pID=kps.petugas")
                ->where($filter)->orderBy($order)->lim();//
                $no=1;
                $i=4;
                $jumlah_data=$db->numRow($list_qry);
                $ListData=array();
                while($data = $db->fetchObject($list_qry))
                {
                    
                    $rec = new stdClass;
                    $rec->No    =$no;
                    $rec->Tanggal    =$data->TanggalPelayanan;
                    $rec->NoEartag    =$data->name;
                    $rec->KawinKe    =$data->kawin_ke;
                    $rec->NoBulls    =$data->no_pejantan;
                    $rec->Pemilik    =$data->pemilik;
                    $ListData[]=$rec;
                  
                    $i++;
                    $no++;
                }
                
              // echo "<pre>";print_r($ListData);echo "</pre>";exit;
               
               
                $tpl->list_data=$ListData;
        		$tpl->title ="Laporan Perkawinan";
        		$tpl->content = $tpl;
        		$tpl->render(); 
        	    $content 	= ob_get_clean();
        	
        		$pdf		=new mpdf60();
        		$mpdf		= $pdf->build("L","A4",array(15,15,20,20));
                $stylesheet = file_get_contents(url::base().'themes/default/css/print.css'); // external css
                $mpdf->WriteHTML($stylesheet,1);
        		$mpdf->SetHTMLFooter('<table width="100%" class="tbl-footer"><tr><td>'.$tanggal_sekarang['IndoHari'].', '.$tanggal_sekarang['Lengkap'].' </td><td class="pdf-footer-right" >Hal. {PAGENO} dari {nb}</td></tr></table>'); 
        	   //$mpdf->setFooter('Testing {PAGENO}');
                $mpdf->allow_charset_conversion = true;
        	    $mpdf->charset_in = 'iso-8859-4';
        	   // $PDFContent = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
        		//$mpdf->WriteHTML($PDFContent);
        		$mpdf->WriteHTML($content);
                
        		$mpdf->Output('laporan_perkawinan'.$TglSkrg.'.pdf','D');
            break;
	
        }
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
		$pilihan=$kategori;//$aVars['pilih'];
        $nama=$aVars['nama'];
        switch($pilihan){
            case "list_truck":
                $equ    = new Ref_Equipment_Model();
                $hasil=$equ->json_data($pilihan,$nama,$aVars);
            break;
        }
        echo json_encode($hasil);  
        exit;
    }   
    public function comboAjax($kategori) {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		$hasil=array();
	
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
        if(trim($kategori)=="list_pit"){
            $pit    = new Ref_Pit_Model();
            $hasil  =$pit->comboAjax($kategori,$parentcode,$aVars);
        }
        if(trim($kategori)=="list_lokasi_dome"){
            $dome    = new Ref_Dome_Model();
            $hasil  =$dome->comboAjax($kategori,$parentcode,$aVars);
        }
        if(trim($kategori)=="list_domes"){
            $dome    = new Ref_Dome_Model();
            $hasil  =$dome->comboAjax($kategori,$parentcode,$aVars['nilai']);
        }
        if(trim($kategori)=="list_barges"){
            $dome    = new Ref_Dome_Model();
            $hasil  =$dome->comboAjax($kategori,$parentcode,$aVars['nilai']);
        }
        
        echo $hasil;
   }
 
}
?>