<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Shipment_Ore_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        global $dcistem;
      
	}
	
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("shipment_ore");
        $db   = $dcistem->getOption("framework/db");
        $login=new Adm_Login_Model();
       $master=new Master_Ref_Model();
        $KodeDosen=$_SESSION["framework"]["ref_id"] ;
        $Username=$_SESSION["framework"]["current_user"]->Username ;
        $list_bulan=$master->listarraybulan();
      	$tpl->list_bulan  = $list_bulan;
        $list_kontraktor=Model::getOptionList("partner","id","name","","ifnull(is_contractor,0)=1"); 
            $tpl->list_kontraktor =$list_kontraktor;
       	$url_form_step = url::current("create");     
       	$TombolTambah=$login->privilegeInputForm("link","","btn-tambah-data","<i class=\"fa fa-plus-circle\"></i>",$this->page->PageID,"create","title='Buat Laporan' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_form_step."\" data-backdrop=\"static\" class=\"btn btn-primary btn-xs\" data-target=\"#largeModal\"");
       	$tpl->TombolTambah      = $TombolTambah; 
        $tpl->url_form_step      = $url_form_step;
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
        $shipment   =new Shipment_Ore_Model();
        $referensi      = $master->referensi_session();
        //$admin  = new Core_Admin_Model();
       // $search=$admin->SearchDependingLevel("events","kps.petugas");
        
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
            $keriteria[]="year(completed_time)  ='".$tahun."'";
            $judul=$judul."<br />Tahun ".$tahun;
        } 
        if( trim($bulan)<>"" ){   //name
                $nama_bln=$master->namabulanIN((int)$bulan);
                if( trim($tahun)<>"" ){
                    
                    $keriteria[]="DATE_FORMAT(completed_time,'%Y-%m')='".$tahun."-".$bulan."'";
                    $judul=$judul."<br />".$nama_bln." ".$tahun;
                }
        }
        if( trim($petugas)<>"" ){   //name
            $keriteria[]="kps.petugas=".$petugas."";
        }
        if( trim($kontraktor)<>"" ){   //name
            $keriteria[]="contractor_id=".$kontraktor."";
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
            $cols=array(0=>"sh.id",
                        1=>"barge_id",
                        2=>"commenced_time");
            $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
                
            $list_qry=$db->select("SQL_CALC_FOUND_ROWS sh.id,transaction_id,sh.urutan_pengiriman,barge_id,bg.name barge_name,created_time,
            final_time,sent_time,received_time,commenced_time,completed_time,total_ritase,total_quantity,final_draugh_survey,
            verification,verification_date,verifikator,lastupdate,ifnull(state,'draft') state,step","shipment sh
            inner join barges bg on bg.id=sh.barge_id")
    		->where($filter)->orderBy($order)->lim($start,$length);//
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
                $ListData[$i]['barge_id']=$data->barge_id;
                $ListData[$i]['barge_name']=$data->barge_name;
                $ListData[$i]['urutan']=$data->urutan_pengiriman;
                $ListData[$i]['created_time']=$data->created_time;                
                $ListData[$i]['commenced_time']=$data->commenced_time;
                $ListData[$i]['completed_time']=$data->completed_time;
                $ListData[$i]['received_time']=$data->received_time;
                $state=trim($data->state)==""?"draft":$data->state;
                $ListData[$i]['state']      =$state;
                $ritase=$data->total_ritase ." (".$data->total_quantity." MT)";
                $ListData[$i]['total_ritase']=$ritase;
                $ListData[$i]['total_quantity']=$data->total_quantity;
                $ListData[$i]['total_draught_survey']=$data->final_draugh_survey;
                $ListData[$i]['verification_date']=$data->verification_date;
                $ListData[$i]['Detail']=$shipment->getShipmentDetail($data->id,"array");;
                $url_del      = url::current("del",$data->id);
    			$url_edit =url::current("edit",$data->id);
                $url_detail =url::current("detail",$data->id);
                $url_verifikasi  = url::current("verifikasi",$data->id);
               	$tombol          = "";
                if($data->verification==0){
                    $tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-check-square-o\"></i>",$this->page->PageID,"verifikasi","title='Verifikasi' href=\"".$url_verifikasi."\" class=\"btn btn-primary btn-xs btn-verifikasi-data\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\" role=\"".$data->id."\"");
                    $tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","btn-edit-data","<i class=\"fa fa-gear\"></i>",$this->page->PageID,"edit","title='Edit Data' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs btn-edit-data\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\" role=\"".$data->id."\" ");
                }  
                if($data->step>=6){
                    $url_daily =url::current("cetak","daily/".$data->id);
                    $tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-file-pdf-o\"></i>",$this->page->PageID,"cetak","title='Daily Report' href=\"".$url_daily."\" class=\"btn btn-primary btn-xs btn-detail-data\" target=\"_blank\"  role=\"".$data->id."\" ");
                }
                $tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-file-text-o\"></i>",$this->page->PageID,"detail","title='Detail' href=\"".$url_detail."\" class=\"btn btn-primary btn-xs btn-detail-data\" target=\"_blank\"  role=\"".$data->id."\" ");
                $control=$tombol;  
                $ListData[$i]['Aksi']='<div class="btn-group">'.$control.'</div>';
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
 public function create($proses="") {
		global $dcistem;
		$db = $dcistem->getOption("framework/db");
		date_default_timezone_set("Asia/Jakarta");
		$master   =new Master_Ref_Model();
        $shipment  = new Shipment_Ore_Model();
        $aVars=array();
        if (sizeof($_POST) > 0) {
		    $aVars = $_POST;
		} else {
		    if (sizeof($_GET) > 0) {
		        $aVars = $_GET;
		    } else {
		        $aVars = array();
		    }
		}
        if ((sizeof($_POST) > 0) and (sizeof($_GET) > 0)) {
            $aVars = array_merge($_POST, $_GET);
        }
        
        
       // print_r($aVars);
		$msg      =array();
        $step=1;
        /** 1=Inisialisasi Laporan, 2-> Entri Ritase, 3-> Finalisasi Laporan */
        $param=isset($aVars['id'])?"?id=".$aVars['id']:"";
        $shipment_id=isset($aVars['shipment_id'])?$aVars['shipment_id']:"";
        //echo $shipment_id;
        $detail=new stdClass;
        $url_upload = url::current("upload");
        $action="add";
        if(trim($shipment_id)<>"" ){
            //echo $shipment_id;
            //echo $shipment_id;
             $url_upload = url::current("upload",$shipment_id);
             //$profil		= $member->getBiodata($member_id);
             $detail		= $shipment->getShipment($shipment_id);
            // echo "<pre>";print_r($detail);echo "</pre>";
             
            $step       = $detail->step==""?1:$detail->step;
            $action="edit";
        } 
        $next_step=$step+1;
		if(trim($proses)=="save")
		{
            //VALIDASI FORM DULU      
            $msg=array();   
    		// echo $id_anggota; 
    	 //echo "<pre>";print_r($_POST);echo "</pre>";
           //  echo "<pre>";print_r($_FILES);echo "</pre>";exit;
    		$LangkahKe=isset($_POST['current_step'])?$_POST['current_step']:1;
    		$skip     =isset($_POST['skip'])?$_POST['skip']:"";
          
    		$validasi=$this->validasiform($LangkahKe,$skip);
    		$Final=false;
            if(count($validasi['arrayerror'])==0){
            	$sqlin="";
            	$TglSkrg		=date("Y-m-d H:i:s");
            	$tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
            	$username		=$_SESSION["framework"]["current_user"]->Username;
            	$username_val	=$master->scurevaluetable($username,"string");
            //	echo $LangkahKe;
            	if ($LangkahKe==1){
            	   $pre_notrx		=date("YmdHis");
            	   $get=$db->select("ifnull(max(CAST(right(transaction_id,2)  AS SIGNED)),0) as max_id","shipment")->where("left(transaction_id,14)='".$pre_notrx."'")->get(0);
                   $max_id=(int)$get->max_id+1;
                   $id_tmp="0".$max_id;
                   $no_transaction=$pre_notrx.substr($id_tmp,(strlen($id_tmp)-2),2);
                   $no_transaction_val		=$master->scurevaluetable($no_transaction);
                   
               	    $barge_id			=$_POST['barge_id'];
    	        	$barge_id_val		=$master->scurevaluetable($barge_id,"number");
                    
                    $rencana_muat			=$_POST['rencana_muat'];
    	        	$rencana_muat_val		=$master->scurevaluetable($rencana_muat,"number");
                    
                    $lay_time_target			=$_POST['lay_time_target'];
    	        	$lay_time_target_val		=$master->scurevaluetable($lay_time_target,"number");
                    
                    
                    $jetty_id			=$_POST['jetty_id'];
    	        	$jetty_id_val		=$master->scurevaluetable($jetty_id,"number");
                    $gate_id			=$_POST['gate_id'];
    	        	$gate_id_val		=$master->scurevaluetable($gate_id,"number");
                    
                    $berth_time=  $master->konversi_tanggal_otomatis($_POST["berth_time"]);
                    $berth_time_val	= $master->scurevaluetable($berth_time,"string");
                    $commenced_time=  $master->konversi_tanggal_otomatis($_POST["commenced_time"]);
                    $commenced_time_val	= $master->scurevaluetable($commenced_time,"string");
                    
                    $jumlah_truck			=$_POST['jumlah_truck'];
    	        	$jumlah_truck_val		=$master->scurevaluetable($jumlah_truck,"number");
                     
    		        $next_step_val		=$master->scurevaluetable((int)$LangkahKe+1,"number");
                    $cols="transaction_id,barge_id,pre_stowage_plan,jetty_id,gate_id,created_time,sent_time,received_time,
                    berth_time,commenced_time,jumlah_truck,lay_time_plan,state,step";
                    $values="$no_transaction_val,$barge_id_val,$rencana_muat_val,$jetty_id_val,$gate_id_val,$tgl_skrg_val,$tgl_skrg_val,$tgl_skrg_val,
                    $berth_time_val,$commenced_time_val,$jumlah_truck_val,$lay_time_target_val,'draft',$next_step_val";
    				
    				$sqlin ="INSERT INTO  shipment ($cols) VALUES($values);";
       	            $rsl_cust=$db->query($sqlin);
    				if(isset($rsl_cust->error) and $rsl_cust->error===true){
    					$msg['success']=false;
    			        $msg['message']="Error create report, ".$rsl_cust->query_last_message;
    				}else{			
    				    $last   =$db->query("SELECT LAST_INSERT_ID() as new_id;");
                        $new    =$db->fetchArray($last);
                        $msg['id']=$new['new_id'];
                        $msg['success']=true;
                        $msg['next_action']="edit";
                        $msg['next_step'] =(int)$LangkahKe+1;
                        $msg['message']="Berhasil";
                       
    	            }
       	        }
	        }else{
	             $msg['success']=false;
	             $msg['message']="Terjadi kesalahan pengisian form";
	             $msg['form_error']=$validasi['arrayerror'];
	        }
	    	echo json_encode($msg);  
            exit;
	    }elseif($proses=="form"){
    	   $tpl  = new View("form_shipment");
  	    	$login=new Adm_Login_Model();
            $current_level=$_SESSION["framework"]['user_level'];
            $list_barge=Model::getOptionList("barges","id","case when ifnull(capacity,0)<>0 then CONCAT(name,' (',capacity,')') else name end  name","","ifnull(is_active,0)=1"); 
            $tpl->list_barge =$list_barge;
            $list_jetty=Model::getOptionList("jetty","id","name","","ifnull(is_active,0)=1"); 
            $tpl->list_jetty =$list_jetty;
            $tpl->detail  = $detail;
            $tpl->shipment_id  = $shipment_id;
           
            
         
	        $tpl->step  = $step;
            $url_ritase  = url::current("ritase",$shipment_id);
            $tombol_ritase=$login->privilegeInputForm("link","","btn-add-ritase","<i class=\"fa fa-plus\"></i>",$this->page->PageID,"ritase","title='Tambah Data' href=\"".$url_ritase."\" class=\"btn btn-primary btn-xs btn-add-ritase\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModalChild\"");  
            $tpl->tombol_ritase = $tombol_ritase;
            $tpl->url_ritase  = $url_ritase;
            $url_gangguan  = url::current("gangguan",$shipment_id);
            $tombol_gangguan=$login->privilegeInputForm("link","","btn-add-gangguan","<i class=\"fa fa-plus\"></i>",$this->page->PageID,"gangguan","title='Tambah Data' href=\"".$url_gangguan."\" class=\"btn btn-primary btn-xs btn-add-gangguan\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModalChild\"");  
            $tpl->tombol_gangguan = $tombol_gangguan;
     	    $tpl->url_gangguan  = $url_gangguan;
            
            $tpl->url_upload = $url_upload;
            $urutan_pengiriman="";
            if($step==5){
                $urutan=$shipment->generateUrutanPengiriman($detail->barge_id);
               // print_r($urutan);
                $urutan_pengiriman=trim($detail->urutan_pengiriman)==""?$urutan['urutan_baru']:$detail->urutan_pengiriman;
            }
            $tpl->urutan_pengiriman=$urutan_pengiriman;
            
            $tpl->url_action  = url::current("edit",$shipment_id);
           $tpl->url_jsonData		= url::current("jsonData");
            $tpl->url_comboAjax     = url::current("comboAjax");
             
    	   $tpl->content = $tpl;
            $tpl->render(); 
		  
	    }else{
	     
     	    $page=new Core_Page_Model();
            $modelsortir=new Adm_Sortir_Model();
    		$master=new Master_Ref_Model();
    		$user_manage=new User_Manage_Model();
    		$auth     = new Auth();
     	    $login_as=$_SESSION['framework']['login_as'];
            $template_header="form_shipment_header";
            /*if(){
                
            }*/
            
     	    $tpl             = new View($template_header);
            
            $tpl->url_detail = url::current("detail");
            $tpl->url_upload = $url_upload;
            $tpl->url_action = url::current("create").$param;
     	    $tpl->url_form  = url::current("create")."/form";
            
            $tpl->url_action_edit  = url::current("edit",$shipment_id);
     	    //$tpl->url_current = url::current("pendaftaran",$member_id);
          $tpl->shipment_id  = $shipment_id;
     	  $tpl->aksi		= $action;
          $tpl->step		= $step;
   	      $tpl->content = $tpl;
            $tpl->render(); 
	    }
	}
    public function upload($id="") {
		global $dcistem;
		$db = $dcistem->getOption("framework/db");
		date_default_timezone_set("Asia/Jakarta");
		$master   =new Master_Ref_Model();
        $shipment  = new Shipment_Ore_Model();
        if (sizeof($_POST) > 0) {
		    $aVars = $_POST;
		} else {
		    if (sizeof($_GET) > 0) {
		        $aVars = $_GET;
		    } else {
		        $aVars = array();
		    }
		}
        $member_id=$aVars['member_id'];
		$msg      =array();
        $step=1;
        /** 1=Inisialisasi Laporan, 2-> Entri Ritase, 3-> Finalisasi Laporan */
        $param=isset($aVars['id'])?"?id=".$aVars['id']:"";
        $shipment_id=isset($aVars['id'])?$aVars['id']:"";
        $detail=new stdClass;
        if(trim($shipment_id)<>"" ){
             //$profil		= $member->getBiodata($member_id);
             $detail		= $shipment->getShipment($shipment_id);
             //echo "<pre>";print_r($detail);echo "</pre>";
             
            $step       = $detail->step==""?1:$detail->step;
            
        } 
        $next_step=$step+1;
            //VALIDASI FORM DULU      
            $msg=array();   
    		// echo $id_anggota; 
    	 echo "<pre>";print_r($_POST);echo "</pre>";
             echo "<pre>";print_r($_FILES);echo "</pre>";exit;
    		
	   
	}
public function edit($shipment_id,$proses=""){     
    global $dcistem;
		$db = $dcistem->getOption("framework/db");
		date_default_timezone_set("Asia/Jakarta");
		$master=new Master_Ref_Model();
        $shipment  = new Shipment_Ore_Model();
         $login=new Adm_Login_Model();
		$msg=array();
        $step   = 1;
        $detail = new stdClass;
        $nama_lengkap="";
        if(trim($shipment_id)<>"" and $shipment_id <> null){
             //$detail		= $member->getBiodata($member_id);
             $detail		= $shipment->getShipment($shipment_id);
             //echo "<pre>";print_r($detail);echo "</pre>";
            $nama_lengkap=$detail->NAMA;
            $step       = $detail->reg_step==""?1:$detail->reg_step;
        } 
        //echo "<pre>";print_r($_POST);echo "</pre>";exit;
        $current_level=$_SESSION["framework"]['user_level'];
		if(trim($proses)=="save")
		{
            //VALIDASI FORM DULU      
            
             
    		$LangkahKe=isset($_POST['current_step'])?$_POST['current_step']:1;
    		$skip     =isset($_POST['skip'])?$_POST['skip']:"";
            
    		$validasi=$this->validasiform($LangkahKe,$skip);
    		$Final=false;
            if(count($validasi['arrayerror'])==0){
                
            	$sqlin="";
            	$TglSkrg		=date("Y-m-d H:i:s");
            	$tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
            	$username		=$_SESSION["framework"]["current_user"]->Username;
            	$username_val	=$master->scurevaluetable($username,"string");
                
            //	echo $LangkahKe;
                if ($LangkahKe==1){
            	    
               	    $barge_id			=$_POST['barge_id'];
    	        	$barge_id_val		=$master->scurevaluetable($barge_id,"number");
                    
                    $rencana_muat			=$_POST['rencana_muat'];
    	        	$rencana_muat_val		=$master->scurevaluetable($rencana_muat,"number");
                    
                    $lay_time_target			=$_POST['lay_time_target'];
    	        	$lay_time_target_val		=$master->scurevaluetable($lay_time_target,"number");
                    
                    
                    $jetty_id			=$_POST['jetty_id'];
    	        	$jetty_id_val		=$master->scurevaluetable($jetty_id,"number");
                    $gate_id			=$_POST['gate_id'];
    	        	$gate_id_val		=$master->scurevaluetable($gate_id,"number");
                    
                    $berth_time=  $master->konversi_tanggal_otomatis($_POST["berth_time"]);
                    $berth_time_val	= $master->scurevaluetable($berth_time,"string");
                    $commenced_time=  $master->konversi_tanggal_otomatis($_POST["commenced_time"]);
                    $commenced_time_val	= $master->scurevaluetable($commenced_time,"string");
                    
                    $jumlah_truck			=$_POST['jumlah_truck'];
    	        	$jumlah_truck_val		=$master->scurevaluetable($jumlah_truck,"number");$barge_id			=$_POST['barge_id'];
    	        	$barge_id_val		=$master->scurevaluetable($barge_id,"number");
                    
                    $rencana_muat			=$_POST['rencana_muat'];
    	        	$rencana_muat_val		=$master->scurevaluetable($rencana_muat,"number");
                    
                    $lay_time_target			=$_POST['lay_time_target'];
    	        	$lay_time_target_val		=$master->scurevaluetable($lay_time_target,"number");
                    
                    
                    $jetty_id			=$_POST['jetty_id'];
    	        	$jetty_id_val		=$master->scurevaluetable($jetty_id,"number");
                    $gate_id			=$_POST['gate_id'];
    	        	$gate_id_val		=$master->scurevaluetable($gate_id,"number");
                    
                    $berth_time=  $master->konversi_tanggal_otomatis($_POST["berth_time"]);
                    $berth_time_val	= $master->scurevaluetable($berth_time,"string");
                    $commenced_time=  $master->konversi_tanggal_otomatis($_POST["commenced_time"]);
                    $commenced_time_val	= $master->scurevaluetable($commenced_time,"string");
                    
                    $jumlah_truck			=$_POST['jumlah_truck'];
    	        	$jumlah_truck_val		=$master->scurevaluetable($jumlah_truck,"number");
                     
    		        $next_step_val		=$master->scurevaluetable((int)$LangkahKe+1,"number");
                    $cols_and_vals="barge_id=$barge_id_val,pre_stowage_plan=$rencana_muat_val,jetty_id=$jetty_id_val,
                    gate_id=$gate_id_val,sent_time=$tgl_skrg_val,received_time=$tgl_skrg_val,
                    berth_time=$berth_time_val,commenced_time=$commenced_time_val,jumlah_truck=$jumlah_truck_val,
                    lay_time_plan=$lay_time_target_val,state='draft',step=$next_step_val,lastupdate=$tgl_skrg_val";
                   
    				$sqlup ="UPDATE shipment SET $cols_and_vals WHERE id=$shipment_id;";
       	            $rsl_cust=$db->query($sqlup);
    				if(isset($rsl_cust->error) and $rsl_cust->error===true){
    					$msg['success']=false;
    			        $msg['message']="Error edit data, ".$rsl_cust->query_last_message;
    				}else{	
                        $msg['id']          =$shipment_id;
                        $msg['success']     =true;
                        $msg['next_action'] ="edit";
                        $msg['message']     ="Berhasil";
                         
    	            }
       	        }
                if ($LangkahKe==2){
                    
                    
                    $lanjut=false;
                   	if(isset($_POST['lewati_dulu']) and $_POST['lewati_dulu']=="on"){
                   	    $lanjut=true;
                   	    
                   	}else{
                   	    $lanjut=true;
                        $cek_ritase=$db->select("id","shipment_detail")->where("shipment_id=$shipment_id")->get();
                        if(count($cek_ritase)==0){
                            $lanjut=false;
        			        $msg_error="Anda belum mengisi data ritase, bila tidak ingin dilewati dulu, silahkan klik centang Lewati Dulu";
                        }
                    }// end tidak ada tanggungan
                    if($lanjut==true){
                        $next_step_val		=$master->scurevaluetable((int)$LangkahKe+1,"number");
                  
    				
                        $next_step_val		=$master->scurevaluetable((int)$LangkahKe+1,"number");                        
                        $cols_and_vals="state='draft',step=$next_step_val,lastupdate=$tgl_skrg_val";                      
        				$sqlup ="UPDATE shipment SET $cols_and_vals WHERE id=$shipment_id;";
                        $rsl_cust=$db->query($sqlup);
        				if(isset($rsl_cust->error) and $rsl_cust->error===true){
        					$msg['success']=false;
        			        $msg['message']="Error edit data, ".$rsl_cust->query_last_message;
        				}else{	
                            $msg['id']          =$shipment_id;
                            $msg['success']     =true;
                            $msg['next_action'] ="edit";
                            $msg['message']     ="Berhasil";
                             
        	            }
                        
                    }else{
                        $msg['success']=false;
     			        $msg['message']=$msg_error;
                    }
                    //echo "<pre>";print_r($msg);echo "</pre>";exit;
    				
                }
                if ($LangkahKe==3){
                    //echo "<pre>";print_r($_POST);echo "</pre>";
                    $lanjut=false;
                   	if(isset($_POST['tidak_ada_gangguan']) and $_POST['tidak_ada_gangguan']=="on"){
                   	    $lanjut=true;
                   	    
                   	}else{
                   	    $lanjut=true;
                        $cek_gangguan=$db->select("id","shipment_gangguan")->where("shipment_id=$shipment_id")->get();
                        if(count($cek_gangguan)==0){
                            $lanjut=false;
        			        $msg_error="Anda belum mengisi data gangguan, bila ingin dilewati, silahkan klik centang Tidak Ada Gangguan/Lewati Dulu";
                        }
                    }// end tidak ada tanggungan
                    if($lanjut==true){
                        $next_step_val		=$master->scurevaluetable((int)$LangkahKe+1,"number");
                  
    				
                        $next_step_val		=$master->scurevaluetable((int)$LangkahKe+1,"number");                        
                        $cols_and_vals="state='draft',step=$next_step_val,lastupdate=$tgl_skrg_val";                      
        				$sqlup ="UPDATE shipment SET $cols_and_vals WHERE id=$shipment_id;";
                        $rsl_cust=$db->query($sqlup);
        				if(isset($rsl_cust->error) and $rsl_cust->error===true){
        					$msg['success']=false;
        			        $msg['message']="Error edit data, ".$rsl_cust->query_last_message;
        				}else{	
                            $msg['id']          =$shipment_id;
                            $msg['success']     =true;
                            $msg['next_action'] ="edit";
                            $msg['message']     ="Berhasil";
                             
        	            }
                        
                    }else{
                        $msg['success']=false;
     			        $msg['message']=$msg_error;
                    }
    				
                }
            	if ($LangkahKe==5){
            	    
                    
                    $completed_time=  $master->konversi_tanggal_otomatis($_POST['completed_time']);
                    $completed_time_val	= $master->scurevaluetable($completed_time,"string");
                    
                    
                    $waktuawal  = date_create($detail->commenced_time);
                    $waktuakhir = date_create($completed_time);
                    
                    $diff  = date_diff($waktuawal, $waktuakhir);
                   
                    $lay_time_real   =($diff->m * 30 * 24) +$diff->d * 24 + $diff->h + round(($diff->i/60),2);;
                    $lay_time_real_val		=$master->scurevaluetable($lay_time_real,"number",false);
                    
                    
                    $jumlah_truck			=$_POST['jumlah_truck'];
    	        	$jumlah_truck_val		=$master->scurevaluetable($jumlah_truck,"number");
                    $urutan_pengiriman			=$_POST['urutan_pengiriman'];
    	        	$urutan_pengiriman_val		=$master->scurevaluetable($urutan_pengiriman,"number");
                    
                    
                    $final_draugh_survey			=$_POST['final_draugh_survey'];
    	        	$final_draugh_survey_val		=$master->scurevaluetable($final_draugh_survey,"number");
                    $cek_ritase=$db->select("id,jumlah_jam,ritase","shipment_detail")->where("shipment_id=$shipment_id")->get();
                    if(count($cek_ritase)==0){
                        $msg['success']=false;
    			        $msg['message']="Error edit data, untuk melakukan finalisasi laporan, minimal ada satu data ritase";
                    }else{
        	            $msg_err="";
                        $cek_gangguan=$db->select("sum(case when (ifnull(start_time,'')<>'' and ifnull(end_time,'')<>'') then jumlah_jam else 0 end) total_jml_jam,
                        count(case when (ifnull(start_time,'')<>'' and ifnull(end_time,'')<>'') then id else 0 end) jml_data,
                        count(case when (ifnull(start_time,'')<>'' and ifnull(end_time,'')='') then id else null end) jml_jam_blm_lengkap ","shipment_gangguan")->where("shipment_id=$shipment_id")->get(0);
                       // print_r($cek_gangguan);
                        $total_uneffective_time="";
                        if(!empty($cek_gangguan)){
                            $total_uneffective_time=$cek_gangguan->total_jml_jam;
                            if($cek_gangguan->jml_jam_blm_lengkap>0){
                                $msg_err="Ada data gangguan/uneffective time yang belum lengkap waktu akhir nya";
                            }
                            
                        }
                        if(trim($msg_err)==""){
                            $total_ritase           =0;
                            $total_effective_time   =0;
                            foreach($cek_ritase as $key=>$value){
                                $total_ritase=$total_ritase+$value->ritase;
                                $total_effective_time=$total_effective_time+$value->jumlah_jam;
                                
                            }
                            $total_ritase_val		=$master->scurevaluetable($total_ritase,"number",false);
                            $total_effective_time_val		=$master->scurevaluetable($total_effective_time,"number",false);
                            $total_uneffective_time_val		=$master->scurevaluetable($total_uneffective_time,"number",false);
            		        $next_step_val		=$master->scurevaluetable((int)$LangkahKe+1,"number");
                            
                            $cols_and_vals="urutan_pengiriman=$urutan_pengiriman_val,completed_time=$completed_time_val,jumlah_truck=$jumlah_truck_val,
                            total_effective_time=$total_effective_time_val,total_uneffective_time=$total_uneffective_time_val,
                            total_ritase=$total_ritase_val,final_draugh_survey=$final_draugh_survey_val,
                            lay_time_real=$lay_time_real_val,state='done',step=$next_step_val,lastupdate=$tgl_skrg_val";
                           
                            
            				$sqlup ="UPDATE shipment SET $cols_and_vals WHERE id=$shipment_id;";
                            //echo $sqlup;exit;
               	            $rsl_cust=$db->query($sqlup);
            				if(isset($rsl_cust->error) and $rsl_cust->error===true){
            					$msg['success']=false;
            			        $msg['message']="Error edit data, ".$rsl_cust->query_last_message;
            				}else{	
                                $msg['id']          =$shipment_id;
                                $msg['success']     =true;
                                $msg['next_action'] ="edit";
                                $msg['message']     ="Berhasil";
            	            }
                         }else{
                            $msg['success']=false;
     			            $msg['message']=$msg_err;
                         }
                     }//end if ritase<>0
       	        }
               
                
                
                
                $er_msj="";
    			$pesan_sukes="";
    			if ($LangkahKe==4){
    			     $berkas=new Adm_File_Model();
    			     $next_step_val		=$master->scurevaluetable((int)$LangkahKe+1,"number");
                 
             // echo "<pre>";print_r($_FILES);echo "</pre>";
   	   //echo "<pre>";print_r($_POST);echo "</pre>";//exit;
                    $pilih_nama_berkas=$_POST['pilih_nama_berkas'];
                    $hasil_upload=array();
    				if(isset($_FILES['file_dokumen'])){
    				    $nama_succes="";
                         $nama_gagal="";
    				    foreach($_POST['berita_acara_for'] as $key=>$value){
    				        $referensi_id=$_POST['id'][$key];
                            $hasil_upload[$key]['id']=$detail_id;
                            $hasil_upload[$key]['nama']=$detail_id;
                            $berita_acara_for=$_POST['berita_acara_for'][$key];
                            
        				    $pathfile=$_FILES['file_dokumen']["tmp_name"][$key];
        			        $login_as=	$_SESSION['framework']['login_as'];      	
        			        $ref_id=$_SESSION["framework"]["ref_id"] ;
        			        $type = $_FILES['file_dokumen']['type'][$key];
        			        $extension = pathinfo($_FILES['file_dokumen']['name'][$key],PATHINFO_EXTENSION);  
        			       // $type = $_FILES['file_foto']['type'][$key];
        			        $Tanggal=date("YmdHis");
                            $category= $_POST['pilih_category'][$key];
        			        $namafile=$pilih_nama_berkas[$key].".".$extension;
                            $nmfile = "files/shipment/".$namafile;   
                            /*if(trim($value)=="child"){                                
                                $namafile="shipment_detail_".$detail_id.".".$extension;
        			             $nmfile = "files/shipment/".$namafile;   
        			        }*/
                            if($_FILES['file_dokumen']['name'][$key]<>""){
                               // $nama_berkas=$_POST['pilih_nama_berkas'][$key];
                               
                               // if(trim($value)<>""){
                			        if(file_exists($nmfile))
                			        {
                			        	unlink($nmfile);
                			        }
                                    $upload=false;
                					if(move_uploaded_file($_FILES['file_dokumen']["tmp_name"][$key],$nmfile))
                		        	{   
                		        	    
                	        			$result=$berkas->update_record_file($category,$nmfile,$namafile,$referensi_id,$value);
                                       // print_r($result);
                                        if($result['success']===false){
                                            $hasil_upload[$key]['success']=false;
                                            $hasil_upload[$key]['message']=$result['message'];
                                        }else{
                                            $hasil_upload[$key]['success']=true;
                                            $nama_succes=trim($nama_succes)<>""?$nama_succes.", ".$berita_acara_for:$berita_acara_for;
                                            $hasil_upload[$key]['message']=$result['message'];
                                           
                                        }
                		        	}else{
                		        		$hasil_upload[$key]['success']=false;
                                        $hasil_upload[$key]['message']="Gagal upload";
                                        $nama_gagal=trim($nama_gagal)<>""?$nama_gagal.", ".$berita_acara_for:$berita_acara_for;
                		        	}
                                /*}else{
                                    $hasil_upload[$key]['success']=false;
                                    $hasil_upload[$key]['message']="Nama berkas harus diisi";
                                    $nama_gagal=trim($nama_gagal)<>""?$nama_gagal.", $berita_acara_for (nama berkas tidak diisi)":"$berita_acara_for (nama berkas tidak diisi)";
                                }*/
                            }else{// jika file kosong
                                $hasil_upload[$key]['success']=false;
                                $s="Tidak ada file yang diupload";
                                $hasil_upload[$key]['message']=$s;
                                $nama_gagal=trim($nama_gagal)<>""?$nama_gagal.", ".$berita_acara_for." (".$s.")":$berita_acara_for." (".$s.")";
                            }
                        }//end for
                       // echo "<pre>";print_r($hasil_upload);echo "</pre>";
                        
                        if(trim($nama_succes)=="" and trim($nama_gagal)<>""){
                            $msg['success']     =false;
                            $msg['message']     ="Gagal upload : $nama_gagal";
                        }
                        if(trim($nama_succes)<>"" and trim($nama_gagal)<>""){
                            $msg['success']     =true;
                            $msg['message']     ="Sukses upload : $nama_succes; Gagal untuk upload : $nama_gagal";
                        }
                        if(trim($nama_succes)<>"" and trim($nama_gagal)==""){
                            $msg['success']     =true;
                            $msg['message']     ="Sukses upload ";
                        }
                        if(trim($nama_succes)=="" and trim($nama_gagal)==""){
                            $msg['success']     =true;
                            $msg['message']     ="Tidak ada file yang diupload";
                        }
                        
    		        }else{
    		           $next_step_val		=$master->scurevaluetable((int)$LangkahKe+1,"number");                        
                        $cols_and_vals="state='draft',step=$next_step_val,lastupdate=$tgl_skrg_val";                      
        				$sqlup ="UPDATE shipment SET $cols_and_vals WHERE id=$shipment_id;";
                        $rsl_cust=$db->query($sqlup);
        				if(isset($rsl_cust->error) and $rsl_cust->error===true){
        					$msg['success']=false;
        			        $msg['message']="Error edit data, ".$rsl_cust->query_last_message;
        				}else{	
                            $msg['id']          =$shipment_id;
                            $msg['success']     =true;
                            $msg['next_action'] ="edit";
                            $msg['message']     ="Berhasil";
                             
        	            }
                        
    		        }
    		        
    			}
       
              
	        }else{
	             $msg['success']=false;
	             $msg['message']="Terjadi kesalahan pengisian form";
	             $msg['form_error']=$validasi['arrayerror'];
	        }
	    	echo json_encode($msg);  
            exit;
	    }//end of save
    	if(trim($proses)=="prev")
		{
		    $LangkahKe=isset($_POST['current_step'])?$_POST['current_step']:1;
            $next_step_val	=$master->scurevaluetable((int)$LangkahKe-1,"number");
            $cols_and_values="step=$next_step_val";
            $sqlin ="UPDATE shipment SET  $cols_and_values WHERE id=$shipment_id;";
            
            $rsl_cust=$db->query($sqlin);
            if(isset($rsl_cust->error) and $rsl_cust->error===true){
            	$msg['success']=false;
                $msg['message']="Error edit data, ".$rsl_cust->query_last_message;
            }else{	
                $msg['id']          =$member_id;
                $msg['success']     =true;
                $msg['next_action'] ="edit";
                $msg['message']     ="Berhasil";
              
            }
            echo json_encode($msg);  
            exit;
        }//end of prev
  }
 
 public function add($proses=""){     
     global $dcistem;
    $db   = $dcistem->getOption("framework/db");
    $master=new Master_Ref_Model();
    $pro=new List_Production_Model();
    date_default_timezone_set("Asia/Jakarta");
    if(trim($proses)=="save")
	{    
	        $validasi=$this->validasiform("add");
	        if(count($validasi['arrayerror'])==0){
	            $TglSkrg		     =date("Y-m-d H:i:s");
            	$tgl_skrg_val		 =$master->scurevaluetable($TglSkrg,"string");
                
                $tgl  = explode("/",$_POST["frm_tanggal"]);
		       
		        $tanggal	= $tgl[2]."-".$tgl[1]."-".$tgl[0];
                $tanggal_val		 =$master->scurevaluetable($tanggal,"string");
                $week         = $_POST['frm_week'];
                $week_val		 =$master->scurevaluetable($week,"number");
                $kontraktor         = $_POST['kontraktor'];
                $kontraktor_val		 =$master->scurevaluetable($kontraktor,"number");
                $qty          = $_POST['frm_qty'];
                $qty_val		 =$master->scurevaluetable($qty,"number");
               
                
                $cols="tanggal,week,partner_id,qty,created";
                
                $vals="$tanggal_val,$week_val,$kontraktor_val,$qty_val,$tgl_skrg_val";
                $sqlup ="INSERT INTO production ($cols) VALUES($vals);";
                $rsl_cust=$db->query($sqlup);
    				
				if(isset($rsl_cust->error) and $rsl_cust->error===true){
			   	 		$msg['success']=false;
	                	$msg['message']="Error, ".$rsl_cust->query_last_message;
				}else{
				   
	                $msg['success']=true;
	                $msg['message']="Data sudah disimpan"; 
	            }
	        }else{
	             $msg['success']	=false;
	             $msg['message']	="Terjadi kesalahan pengisian form";
	             $msg['form_error']=$validasi['arrayerror'];
	        }
	    echo json_encode($msg);   
	}else{
		    $ip=core::get_ip();
	    	$tpl  = new View("form_shipment");
            $list_barge=Model::getOptionList("barges","id","case when ifnull(capacity,0)<>0 then CONCAT(name,' (',capacity,')') else name end  name","","ifnull(is_active,0)=1"); 
            $tpl->list_barge =$list_barge;
            $list_jetty=Model::getOptionList("jetty","id","name","","ifnull(is_active,0)=1"); 
            $tpl->list_jetty =$list_jetty;
     
            $detail= $pro->getProduction($id);
           //echo "<pre>";print_r($detail);echo "</pre>";
           
            $tpl->detail =$detail;
	    	$tpl->url_edit = url::current("edit",$kode_lama);
            $tpl->url_checkdata =url::current("checkdata");
	    	$tpl->url_jsonData		= url::current("jsonData");
        	$tpl->url_comboAjax		=url::current("comboAjax");
	    	$tpl->content = $tpl;
	        $tpl->render(); 
    }
  } 
public function ritase($shipment_id,$aksi){     
    global $dcistem;
		$db = $dcistem->getOption("framework/db");
		date_default_timezone_set("Asia/Jakarta");
		$master=new Master_Ref_Model();
        $login=new Adm_Login_Model();
        $shipment = new Shipment_Ore_Model();
        $detail=$shipment->getShipment($shipment_id);
        $referensi	= $_SESSION["referensi"];
        $current_level=$_SESSION["framework"]['user_level'];
        $sqlin  = "";
        switch($aksi){
            case "add":
                $judul="Ritase";
                
                $validasi=$this->validasiform(2,true);
                //echo "<pre>";print_r($validasi);echo "</pre>";exit;
        		$Final=false;
                if(count($validasi['arrayerror'])==0){
                   
                    $TglSkrg=date("Y-m-d H:i:s");
                    $TglSkrg_val		=$master->scurevaluetable($TglSkrg);
                    $pre_notrx		=$detail->transaction_id;
            	    $get=$db->select("ifnull(max(CAST(right(detail_transaction_id,2)  AS SIGNED)),0) as max_id","shipment_detail")->where("left(detail_transaction_id,16)='".$pre_notrx."'")->get(0);
                    $max_id=(int)$get->max_id+1;
                    $id_tmp="0".$max_id;
                    $no_transaction_detail=$pre_notrx.substr($id_tmp,(strlen($id_tmp)-2),2);
                    $no_transaction_detail_val		=$master->scurevaluetable($no_transaction_detail);
                    
                    
                    
                    $contractor_id  =trim($_POST['contractor_id']);
                    $contractor_id_val		=$master->scurevaluetable($contractor_id,"number");
                    
        	        $dome_id	        =trim($_POST['dome_id']);
                    $dome_id_val		=$master->scurevaluetable($dome_id,"number");
                    
        	        $dome_distance	   =trim($_POST['dome_distance']);
                    $dome_distance_val		=$master->scurevaluetable($dome_distance,"number");
                    $shift		=$_POST['shift'];
                    $shift_val		=$master->scurevaluetable($shift,"number");
                    
                    $start_time=  $master->konversi_tanggal_otomatis($_POST["start_time"]);
                    $start_time_val	= $master->scurevaluetable($start_time,"string");
                    
                    $end_time=  $master->konversi_tanggal_otomatis($_POST["end_time"]);
                    $end_time_val	= $master->scurevaluetable($end_time,"string");
                    
                    $waktuawal  = date_create($start_time);

                    $waktuakhir = date_create($end_time);
                    
                    $diff  = date_diff($waktuawal, $waktuakhir);
                   
                    $jumlah_jam	   =($diff->m * 30 * 24) +$diff->d * 24 + $diff->h + round(($diff->i/60),2);;
                    $jumlah_jam_val		=$master->scurevaluetable($jumlah_jam,"number",false);
                    
 	                $ritase      =$_POST['ritase'];
                    $ritase_val		=$master->scurevaluetable($ritase,"number");
                    
                    $qty= 18*$ritase;
                    $qty_val		=$master->scurevaluetable($qty,"number");
    	        	$intermediate_draugh_survey			=$_POST['intermediate_draugh_survey'];
                    $intermediate_draugh_survey_val		=$master->scurevaluetable($intermediate_draugh_survey,"number");
                    
                   
                    $cols="detail_transaction_id,shipment_id,contractor_id,shift,ritase,quantity,
                    intermediate_draugh_survey,recap_contractor_monthly_id,start_time,end_time,dome_id,
                    jumlah_jam,dome_distance,created_time";
            		$values="$no_transaction_detail_val,$shipment_id,$contractor_id_val,$shift_val,$ritase_val,$qty_val,
                    $intermediate_draugh_survey_val,null,$start_time_val,$end_time_val,$dome_id_val,
                    $jumlah_jam_val,$dome_distance_val,$TglSkrg_val";
            		$sqlin ="INSERT INTO  shipment_detail ($cols) VALUES($values);";
                    $rsl_cust=$db->query($sqlin);
            		if(isset($rsl_cust->error) and $rsl_cust->error===true){
            			$msg['success']=false;
            	        $msg['message']="Error create activity, ".$rsl_cust->query_last_message." ".$sqlin;
            		}else{	
                        $msg['success']=true;
                        $msg['message']="Data ".strtolower($judul)." sudah ditambahkan";
                        
                    }
                 }else{
    	             $msg['success']=false;
    	             $msg['message']="Terjadi kesalahan pengisian form";
    	             $msg['form_error']=$validasi['arrayerror'];
    	        }
                 echo json_encode($msg);exit;
            
            break;
            case "edit":
                $judul="Activity & Ritase";
                $validasi=$this->validasiform(2,true);
        		$Final=false;
                if(count($validasi['arrayerror'])==0){
                    $ritase_id      =$_POST['ritase_id'];
                    $TglSkrg=date("Y-m-d H:i:s");
                    $TglSkrg_val		=$master->scurevaluetable($TglSkrg);
                    $contractor_id  =trim($_POST['contractor_id']);
                    $contractor_id_val		=$master->scurevaluetable($contractor_id,"number");
                    
        	        $dome_id	        =trim($_POST['dome_id']);
                    $dome_id_val		=$master->scurevaluetable($dome_id,"number");
                    
        	        $dome_distance	   =trim($_POST['dome_distance']);
                    $dome_distance_val		=$master->scurevaluetable($dome_distance,"number");
                    $shift		=$_POST['shift'];
                    $shift_val		=$master->scurevaluetable($shift,"number");
                    
                    $start_time=  $master->konversi_tanggal_otomatis($_POST["start_time"]);
                    $start_time_val	= $master->scurevaluetable($start_time,"string");
                    
                    $end_time=  $master->konversi_tanggal_otomatis($_POST["end_time"]);
                    $end_time_val	= $master->scurevaluetable($end_time,"string");
                    
                    $waktuawal  = date_create($start_time);

                    $waktuakhir = date_create($end_time);
                    
                    $diff  = date_diff($waktuawal, $waktuakhir);
                   
                    $jumlah_jam	   =($diff->m * 30 * 24) +$diff->d * 24 + $diff->h + round(($diff->i/60),2);;
                    $jumlah_jam_val		=$master->scurevaluetable($jumlah_jam,"number",false);
                    
 	                $ritase      =$_POST['ritase'];
                    $ritase_val		=$master->scurevaluetable($ritase,"number");
                    
                    $qty= 18*$ritase;
                    $qty_val		=$master->scurevaluetable($qty,"number");
    	        	$intermediate_draugh_survey			=$_POST['intermediate_draugh_survey'];
                    $intermediate_draugh_survey_val		=$master->scurevaluetable($intermediate_draugh_survey,"number");
                    
                   
                    $cols_and_vals="shipment_id=$shipment_id,contractor_id=$contractor_id_val,shift=$shift_val,
                    ritase=$ritase_val,quantity=$qty_val,
                    intermediate_draugh_survey=$intermediate_draugh_survey_val,recap_contractor_monthly_id=null,
                    start_time=$start_time_val,end_time=$end_time_val,dome_id=$dome_id_val,
                    jumlah_jam=$jumlah_jam_val,dome_distance=$dome_distance_val,lastupdate=$TglSkrg_val";
            	
            		
            		$sqlin ="UPDATE shipment_detail SET $cols_and_vals WHERE id=".$ritase_id.";";
                    $rsl_cust=$db->query($sqlin);
            		if(isset($rsl_cust->error) and $rsl_cust->error===true){
            			$msg['success']=false;
            	        $msg['message']="Error edit ritase, ".$rsl_cust->query_last_message." ".$sqlin;
            		}else{	
                        $msg['success']=true;
                        $msg['message']="Perubahan data ".strtolower($judul)." sudah disimpan";
                        
                    }
                 }else{
    	             $msg['success']=false;
    	             $msg['message']="Terjadi kesalahan pengisian form";
    	             $msg['form_error']=$validasi['arrayerror'];
    	        }
                 echo json_encode($msg);exit;
            
            break;
            case "del":
                $ritase_id=$_POST['child_id'];
                if(trim($ritase_id)<>"")
                {
        	        $Nama=$_POST['nama'];
        	        $sqlin="DELETE FROM shipment_detail WHERE id=".$ritase_id.";";
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
                echo json_encode($msg);   exit;
                
            break;
            case "form":
                $tpl  = new View("form_ritase_shipment");
              // print_r($_GET);
                $ritase_id=isset($_GET['ritase_id'])?$_GET['ritase_id']:"";
                if(trim($ritase_id)<>""){
                    
                     $detail		= $shipment->getShipmentDetailByID($ritase_id);
                    //echo "<pre>";print_r($detail);echo "</pre>";
                    $tpl->detail = $detail;
                  
                } 
      	    	$tahun_akhir    =(int)date("Y")-18;
            	$tahun_awal    =(int)date("Y")-26;
                
            	$tpl->list_tahun_lahir=$master->listarraytahun($tahun_awal,$tahun_akhir);
            
            	$list_bulan=$master->listarraybulan();
            	$tpl->list_bulan  = $list_bulan;
            	$list_tanggal=$master->listarraytanggal();
            	$tpl->list_tanggal  = $list_tanggal;  
                
                $list_shift  = Model::getOptionList("work_shifts","shift","shift","shift asc");   
    	        $tpl->list_shift =$list_shift;
                
                $list_contractor  = Model::getOptionList("partner","id","name","id asc","ifnull(is_contractor,0)=1");   
    	        $tpl->list_contractor =$list_contractor;
                
                $list_dome  = Model::getOptionList("domes","id","name","id asc","status='shipping'");   
    	        $tpl->list_dome =$list_dome;
                
                $tpl->url_comboAjax=url::current("comboAjax");
                $tpl->content = $tpl;
                $tpl->render(); 
            break;
            case "listdata":
               
                $shipment_detail=$shipment->getShipmentDetail($shipment_id);
               // echo "<pre>";print_r($shipment_detail);echo "</pre>";
                $draw=$_REQUEST['draw'];
              
                $no=1;
                $i=0;
                $ListData=array();
                if(!empty($shipment_detail)){
                    while($data = current($shipment_detail))
                    {
                        $rec    	   = new stdClass;
                        $ListData[$i]['No']=$no;
                        $ListData[$i]['ID']     = $data->id;
                        $ListData[$i]['shipment_id']    = $data->shipment_id;
                        $ListData[$i]['dome_name']    = $data->dome_name;
                        $ListData[$i]['shift']    = $data->shift;
                        $ListData[$i]['start_time']  = $data->start_time;
                        $ListData[$i]['end_time']  = $data->end_time;
                        $ListData[$i]['contractor_id']  = $data->contractor_id;
                        $ListData[$i]['contractor_name']           = $data->name;
                        $ListData[$i]['contractor_alias']          = $data->alias;
                        $ListData[$i]['ritase']         = number_format($data->ritase,2,",",".");
                        $ListData[$i]['quantity']         = number_format($data->quantity,2,",",".");
                        $ListData[$i]['intermediate_draugh_survey']   = number_format($data->intermediate_draugh_survey,2,",",".");
                        
                        $tombol = "";
            			$url_ritase    =url::current("ritase",$data->shipment_id);
                        
                       	$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-gear\"></i>",$this->page->PageID,"ritase","title='Edit Data' href=\"".$url_ritase."\" class=\"btn btn-primary btn-xs btn-edit-ritase\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModalChild\" role=\"".$data->id."\"");             
            			$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"ritase","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_ritase."\" class=\"btn btn-primary btn-xs btn-del-ritase\" data-target=\"#largeModalChild\" role=\"".$data->id."\"");
                       
                        $control=$tombol;  
                        $ListData[$i]['Tombol']=$control;
                        $i++;
                        $no++;
                        next($shipment_detail);
                    }
               }
               
                $hasil['draw']=$draw;
                $hasil['recordsTotal']=count($shipment_detail);
                $hasil['recordsFiltered']=count($shipment_detail);
        	    $hasil['data']=$ListData;
                
                echo json_encode($hasil);exit;
            
            break;
        }
        
        
       
}
public function gangguan($shipment_id,$aksi){     
    global $dcistem;
		$db = $dcistem->getOption("framework/db");
		date_default_timezone_set("Asia/Jakarta");
		$master=new Master_Ref_Model();
        $login=new Adm_Login_Model();
        $shipment = new Shipment_Ore_Model();
        $detail=$shipment->getShipment($shipment_id);
        $referensi	= $_SESSION["referensi"];
        $current_level=$_SESSION["framework"]['user_level'];
        $sqlin  = "";
        switch($aksi){
            case "add":
                $judul="Gangguan/Uneffective Time";
                
                $validasi=$this->validasiform(3,true);
                //echo "<pre>";print_r($validasi);echo "</pre>";exit;
        		$Final=false;
                if(count($validasi['arrayerror'])==0){
                   
                    $TglSkrg=date("Y-m-d H:i:s");
                    $TglSkrg_val		=$master->scurevaluetable($TglSkrg);
                    
                    
                    $uneffective_id  =$_POST['uneffective_id'];
                    $uneffective_id_val		=$master->scurevaluetable($uneffective_id,"number",false);
                    $shift		=$_POST['shift'];
                    $shift_val		=$master->scurevaluetable($shift,"number");
                    
                    $start_time=  $master->konversi_tanggal_otomatis($_POST["start_time"]);
                    $start_time_val	= $master->scurevaluetable($start_time,"string");
                    
                    $end_time=  $master->konversi_tanggal_otomatis($_POST["end_time"]);
                    $end_time_val	= $master->scurevaluetable($end_time,"string");
                    
                    $waktuawal  = date_create($start_time);

                    $waktuakhir = date_create($end_time);
                    
                    $diff  = date_diff($waktuawal, $waktuakhir);
                   
                    $jumlah_jam	   =($diff->m * 30 * 24) +$diff->d * 24 + $diff->h + round(($diff->i/60),2);;
                    $jumlah_jam_val		=$master->scurevaluetable($jumlah_jam,"number",false);
                    
                    $description  =trim($_POST['description']);
                    $description_val		=$master->scurevaluetable($description);
                    
                    $cols="shipment_id,gangguan_id,shift,start_time,end_time,jumlah_jam,description,created_time";
            		$values="$shipment_id,$uneffective_id_val,$shift_val,$start_time_val,$end_time_val,$jumlah_jam_val,
                    $description_val,$TglSkrg_val";
            		$sqlin ="INSERT INTO  shipment_gangguan ($cols) VALUES($values);";
                    $rsl_cust=$db->query($sqlin);
            		if(isset($rsl_cust->error) and $rsl_cust->error===true){
            			$msg['success']=false;
            	        $msg['message']="Error create activity, ".$rsl_cust->query_last_message." ".$sqlin;
            		}else{	
                        $msg['success']=true;
                        $msg['message']="Data ".strtolower($judul)." sudah ditambahkan";
                        
                    }
                 }else{
    	             $msg['success']=false;
    	             $msg['message']="Terjadi kesalahan pengisian form";
    	             $msg['form_error']=$validasi['arrayerror'];
    	        }
                 echo json_encode($msg);exit;
            
            break;
            case "edit":
                $judul="Gangguan";
                $validasi=$this->validasiform(3,true);
        		$Final=false;
                if(count($validasi['arrayerror'])==0){
                    $gangguan_id      =$_POST['gangguan_id'];
                    $TglSkrg=date("Y-m-d H:i:s");
                    $TglSkrg_val		=$master->scurevaluetable($TglSkrg);
                    
                   // echo "<pre>";print_r($_POST);echo "</pre>";exit;
                    $uneffective_id  =$_POST['uneffective_id'];
                    $uneffective_id_val		=$master->scurevaluetable($uneffective_id,"number",false);
        	        
                    $shift		=$_POST['shift'];
                    $shift_val		=$master->scurevaluetable($shift,"number");
                    
                    $start_time=  $master->konversi_tanggal_otomatis($_POST["start_time"]);
                    $start_time_val	= $master->scurevaluetable($start_time,"string");
                    
                    $end_time=  $master->konversi_tanggal_otomatis($_POST["end_time"]);
                    $end_time_val	= $master->scurevaluetable($end_time,"string");
                    
                    $waktuawal  = date_create($start_time);

                    $waktuakhir = date_create($end_time);
                    
                    $diff  = date_diff($waktuawal, $waktuakhir);
                   
                    $jumlah_jam	   =($diff->m * 30 * 24) +$diff->d * 24 + $diff->h + round(($diff->i/60),2);;
                    $jumlah_jam_val		=$master->scurevaluetable($jumlah_jam,"number",false);
                    
                    $description  =trim($_POST['description']);
                    $description_val		=$master->scurevaluetable($description);
                    
                    $cols_and_vals="shipment_id=$shipment_id,gangguan_id=$uneffective_id_val,shift=$shift_val,start_time=$start_time_val,
                    end_time=$end_time_val,jumlah_jam=$jumlah_jam_val,description=$description_val,lastupdate=$TglSkrg_val";
            	
            		
            		$sqlin ="UPDATE shipment_gangguan SET $cols_and_vals WHERE id=".$gangguan_id.";";
                    $rsl_cust=$db->query($sqlin);
            		if(isset($rsl_cust->error) and $rsl_cust->error===true){
            			$msg['success']=false;
            	        $msg['message']="Error edit gangguan, ".$rsl_cust->query_last_message." ".$sqlin;
            		}else{	
                        $msg['success']=true;
                        $msg['message']="Perubahan data ".strtolower($judul)." sudah disimpan ";
                        
                    }
                 }else{
    	             $msg['success']=false;
    	             $msg['message']="Terjadi kesalahan pengisian form";
    	             $msg['form_error']=$validasi['arrayerror'];
    	        }
                 echo json_encode($msg);exit;
            
            break;
            case "del":
                $gangguan_id=$_POST['child_id'];
                if(trim($gangguan_id)<>"")
                {
        	        $Nama=$_POST['nama'];
        	        $sqlin="DELETE FROM shipment_gangguan WHERE id=$gangguan_id;";
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
                echo json_encode($msg);   exit;
                
            break;
            case "form":
                $tpl  = new View("form_gangguan_shipment");
               
                $gangguan_id=isset($_GET['gangguan_id'])?$_GET['gangguan_id']:"";
                if(trim($gangguan_id)<>""){
                    $detail		= $shipment->getShipmentGangguanByID($gangguan_id);
                    //echo "<pre>";print_r($detail);echo "</pre>";
                    $tpl->detail = $detail;
                  
                } 
      	    	$tahun_akhir    =(int)date("Y")-18;
            	$tahun_awal    =(int)date("Y")-26;
                
            	$tpl->list_tahun_lahir=$master->listarraytahun($tahun_awal,$tahun_akhir);
            
            	$list_bulan=$master->listarraybulan();
            	$tpl->list_bulan  = $list_bulan;
            	$list_tanggal=$master->listarraytanggal();
            	$tpl->list_tanggal  = $list_tanggal;  
                
                $list_uneffective  = Model::getOptionList("ref_uneffective_time","id","name","id asc");   
    	        $tpl->list_uneffective =$list_uneffective;
                $list_shift  = Model::getOptionList("work_shifts","shift","shift","shift asc");   
    	        $tpl->list_shift =$list_shift;
                
                $list_contractor  = Model::getOptionList("partner","id","name","id asc","ifnull(is_contractor,0)=1");   
    	        $tpl->list_contractor =$list_contractor;
                
                $list_dome  = Model::getOptionList("domes","id","name","id asc","status='shipping'");   
    	        $tpl->list_dome =$list_dome;
                
                $tpl->url_jsonData		= url::current("jsonData");
                $tpl->url_comboAjax=url::current("comboAjax");
                $tpl->content = $tpl;
                $tpl->render(); 
            break;
            case "listdata":
               
                $shipment_gangguan=$shipment->getShipmentGangguan($shipment_id);
               // echo "<pre>";print_r($shipment_gangguan);echo "</pre>";
                $draw=$_REQUEST['draw'];
                /*$list_qry=$db->select("id,detail_transaction_id,shipment_id,contractor_id,p.name,p.alias,shift,tanggal,ritase,
                quantity,survey_quantity,created_time,lastupdate,recap_contractor_monthly_id","shipment_detail shd
                inner join partner p on p.id=shd.contractor_id and ifnull(p.is_contractor,0)=1")
                ->where("tgIDAnggota=$member_id")->orderby("tgTanggalLahir desc")->lim();*/
                $no=1;
                $i=0;
                $ListData=array();
                if(!empty($shipment_gangguan)){
                    while($data = current($shipment_gangguan))
                    {
                        $rec    	   = new stdClass;
                        $ListData[$i]['No']=$no;
                        $ListData[$i]['id']     = $data->id;
                        $ListData[$i]['shipment_id']    = $data->shipment_id;
                        $ListData[$i]['name']    = $data->name;
                        $ListData[$i]['shift']    = $data->shift;
                        $ListData[$i]['start_time']  = $data->start_time;
                        $ListData[$i]['end_time']  = $data->end_time;
                        $ListData[$i]['jumlah_jam']  = $data->jumlah_jam;
                        $ListData[$i]['description']           = $data->description;
                      
                        $tombol = "";
            			$url_gangguan    =url::current("gangguan",$data->shipment_id);
                        
                       	$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-gear\"></i>",$this->page->PageID,"gangguan","title='Edit Data' href=\"".$url_gangguan."\" class=\"btn btn-primary btn-xs btn-edit-gangguan\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModalChild\" role=\"".$data->id."\"");             
            			$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"gangguan","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_gangguan."\" class=\"btn btn-primary btn-xs btn-del-gangguan\" data-target=\"#largeModalChild\" role=\"".$data->id."\"");
                       
                        $control=$tombol;  
                        $ListData[$i]['Tombol']=$control;
                        $i++;
                        $no++;
                        next($shipment_gangguan);
                    }
               }
               
                $hasil['draw']=$draw;
                $hasil['recordsTotal']=count($shipment_gangguan);
                $hasil['recordsFiltered']=count($shipment_gangguan);
        	    $hasil['data']=$ListData;
                
                echo json_encode($hasil);exit;
            
            break;
        }
        
        
       
}
  public function verifikasi($id,$proses=""){     
     global $dcistem;
    $db   = $dcistem->getOption("framework/db");
    $master=new Master_Ref_Model();
    $pro=new List_Production_Model();
    $username=$_SESSION["framework"]["current_user"]->Username ;
    date_default_timezone_set("Asia/Jakarta");
    if(trim($proses)=="save")
	{    
	    if(trim($id)<>"")
	    { 
	        $validasi=$this->validasiform("verifikasi");
	        if(count($validasi['arrayerror'])==0){
	            $TglSkrg		     =date("Y-m-d H:i:s");
            	$tgl_skrg_val		 =$master->scurevaluetable($TglSkrg,"string");
                $username_val    =$master->scurevaluetable($username,"string");
                $tgl  = explode("/",$_POST["frm_tanggal"]);
		       
		        $tanggal	    = $tgl[2]."-".$tgl[1]."-".$tgl[0];
                $tanggal_val    =$master->scurevaluetable($tanggal,"string");
                $qty            = $_POST['frm_qty'];
                $qty_val		=$master->scurevaluetable($qty,"number",false);
             
                $cols_and_vals="tanggal=$tanggal_val,qty=$qty_val,verifikator=$username_val,verification=1,verification_date=$tgl_skrg_val";
                $sqlup ="UPDATE production SET $cols_and_vals WHERE id=$id;";
                $rsl_cust=$db->query($sqlup);
    				
				if(isset($rsl_cust->error) and $rsl_cust->error===true){
			   	 		$msg['success']=false;
	                	$msg['message']="Error, ".$rsl_cust->query_last_message;
				}else{
				   
	                $msg['success']=true;
	                $msg['message']="Data sudah diubah"; 
	            }
	        }else{
	             $msg['success']	=false;
	             $msg['message']	="Terjadi kesalahan pengisian form";
	             $msg['form_error']=$validasi['arrayerror'];
	        }
	    }else{
	        $msg['success']=false;
	        $msg['message']="Gagal menggambil data yang akan diubah, silahkan ulangi!";
	    }
	    echo json_encode($msg);   
	}else{
		    $ip=core::get_ip();
	    	$tpl  = new View("form_verifikasi_shipment");
            echo "<strong>Fitur ini masih dalam pengembangan</strong>";exit;
             $list_kontraktor=Model::getOptionList("partner","id","name","","ifnull(is_contractor,0)=1"); 
            $tpl->list_kontraktor =$list_kontraktor;
     
            $detail= $pro->getProduction($id);
           //echo "<pre>";print_r($detail);echo "</pre>";
           
            $tpl->detail =$detail;
	    	$tpl->url_edit = url::current("edit",$kode_lama);
            $tpl->url_checkdata =url::current("checkdata");
	    	$tpl->url_jsonData		= url::current("jsonData");
        	$tpl->url_comboAjax		=url::current("comboAjax");
	    	$tpl->content = $tpl;
	        $tpl->render(); 
    }
  } 
  public function validasiform($step=1,$child=false) 
    {
        global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        $msg=array();
      	$pesan=array();
        $msg=array();
        $msj="";
        
        
        switch($step){
            case 1:
                if(trim($_POST['barge_id'])==''){
                    $pesan["barge_id"]="Barge harus diisi!";   
                    $msg[]="Barge harus diisi!";
                }
                if(trim($_POST['jetty_id'])==''){
                    $pesan["jetty_id"]="Jetty harus diisi!";   
                    $msg[]="Jetty harus diisi!";
                }
                if(trim($_POST['gate_id'])==''){
                    $pesan["gate_id"]="Gate harus diisi!";   
                    $msg[]="Gate harus diisi!";
                }
                if(trim($_POST['berth_time'])<>""){
                    $waktu=explode(" ",$_POST['berth_time']);
    		        if((strlen(trim($waktu[0]))<>10) or  (substr_count(trim($waktu[0]),"/")<>2)){
    		            $pesan["berth_time"]="Terjadi kesalahan format Tanggal";   
    		            $msg[]="Terjadi kesalahan format Tanggal";
    		        }
                    if((strlen(trim($waktu[1]))<4 or  strlen(trim($waktu[1]))>5) or  (substr_count(trim($waktu[1]),":")<>1)){
    		            $pesan["berth_time"]="Terjadi kesalahan format jam".$waktu[1];   
    		            $msg[]="Terjadi kesalahan format jam".$waktu[1];
    		        }
    	        }else{
    	        	$pesan["berth_time"]="Berth Time tidak boleh kosong";   
    		        $msg[]="Berth Time tidak boleh kosong";
    	        }
                if(trim($_POST['commenced_time'])<>""){
                    $commenced_time=explode(" ",$_POST['commenced_time']);
    		        if((strlen(trim($commenced_time[0]))<>10) or  (substr_count(trim($commenced_time[0]),"/")<>2)){
    		            $pesan["commenced_time"]="Terjadi kesalahan format Tanggal";   
    		            $msg[]="Terjadi kesalahan format Tanggal";
    		        }
                    if(( strlen(trim($commenced_time[1]))<>5) or  (substr_count(trim($commenced_time[1]),":")<>1)){
    		            $pesan["commenced_time"]="Terjadi kesalahan format jam";   
    		            $msg[]="Terjadi kesalahan format jam";
    		        }
    	        }else{
    	        	$pesan["commenced_time"]="Commenced Time tidak boleh kosong";   
    		        $msg[]="Commenced Time tidak boleh kosong";
    	        }
               
                
            break;
            case 2:
                if($child==true){
                    if(trim($_POST['contractor_id'])==''){
                        $pesan["contractor_id"]="Kontraktor harus diisi!";   
                        $msg[]="Kontraktor harus diisi!";
                    }
                    if(trim($_POST['dome_id'])==''){
                        $pesan["dome_id"]="Asal dome harus diisi!";   
                        $msg[]="Asal dome harus diisi!";
                    }
                    if(trim($_POST['shift'])==''){
                        $pesan["shift"]="shift harus diisi";   
                        $msg[]="shift harus diisi";
                    }
                    if(trim($_POST['start_time'])==''){
                        $pesan["start_time"]="start_time harus diisi";   
                        $msg[]="start_time harus diisi";
                    }
                    if(trim($_POST['start_time'])<>""){
                        $waktu=explode(" ",$_POST['start_time']);
        		        if((strlen(trim($waktu[0]))<>10) or  (substr_count(trim($waktu[0]),"/")<>2)){
        		            $pesan["start_time"]="Terjadi kesalahan format Tanggal";   
        		            $msg[]="Terjadi kesalahan format Tanggal";
        		        }
                        if((strlen(trim($waktu[1]))<4 or  strlen(trim($waktu[1]))>5) or  (substr_count(trim($waktu[1]),":")<>1)){
        		            $pesan["start_time"]="Terjadi kesalahan format jam".$waktu[1];   
        		            $msg[]="Terjadi kesalahan format jam".$waktu[1];
        		        }
        	        }else{
        	        	$pesan["start_time"]="Start Time tidak boleh kosong";   
        		        $msg[]="Start Time tidak boleh kosong";
        	        }
                    
                    if(trim($_POST['ritase'])==''){
                        $pesan["ritase"]="ritase harus diisi";   
                        $msg[]="ritase harus diisi";
                    }
                     
                }
                
            break;
        
            
            case 5:
               
                if(trim($_POST['completed_time'])<>""){
                    $waktu=explode(" ",$_POST['completed_time']);
    		        if((strlen(trim($waktu[0]))<>10) or  (substr_count(trim($waktu[0]),"/")<>2)){
    		            $pesan["completed_time"]="Terjadi kesalahan format Tanggal";   
    		            $msg[]="Terjadi kesalahan format Tanggal";
    		        }
                    if((strlen(trim($waktu[1]))<4 or  strlen(trim($waktu[1]))>5) or  (substr_count(trim($waktu[1]),":")<>1)){
    		            $pesan["completed_time"]="Terjadi kesalahan format jam".$waktu[1];   
    		            $msg[]="Terjadi kesalahan format jam".$waktu[1];
    		        }
    	        }else{
    	        	$pesan["completed_time"]="Berth Time tidak boleh kosong";   
    		        $msg[]="Berth Time tidak boleh kosong";
    	        }
                
                
                if(trim($_POST['jumlah_truck'])==''){
                    $pesan["jumlah_truck"]="jumlah truck harus diisi";   
                    $msg[]="jumlah truck harus diisi";
                }
            break;
           
            case 7:
            
                if(trim($_POST['tgl_masuk'])=='' and $_POST['status_before']==3){
                    $pesan["tgl_masuk"]="Tanggal masuk harus diisi";   
                    $msg[]="Tanggal masuk harus diisi";
                }
                
                if($_POST['status_before']==""){
                    $simpanan_pokok=$master->konversi2angka($_POST['simpanan_pokok']);
                    if($simpanan_pokok<1000000){
                        $pesan["simpanan_pokok"]="Simpanan pokok harus Rp. 1.000.000";   
                        $msg[]="Simpanan pokok harus Rp. 1.000.000";
                    }
                }
                if($_POST['status_before']=="" or $_POST['status_before']==3){
                    $simpanan_penyetaraan=$master->konversi2angka($_POST['simpanan_penyetaraan']);
                    if($simpanan_penyetaraan<500000){
                        $pesan["simpanan_penyetaraan"]="Simpanan penyetaraan minimal harus Rp. 500.000";   
                        $msg[]="Simpanan penyetaraan minimal harus Rp. 500.000";
                    }
                }
                if(!isset($_POST['konfirmasi'])){
                    $pesan["Pernyataan"]="Harus memberikan tanda centang sebagai bentuk pernyataan Anda";   
                    $msg[]="Harus memberikan tanda centang sebagai bentuk pernyataan Anda";
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
        $tpl  = new View("detail_shipment");
        $master=new Master_Ref_Model();
        $login=new Adm_Login_Model();
         $shipment    =new Shipment_Ore_Model();
        date_default_timezone_set("Asia/Jakarta");
        $detail=$shipment->getShipment($id);
         //echo "<pre>"; print_r($detail);echo "</pre>";
        $tpl->detail=$detail;
       // $tpl->url_base=url::base();
        $url_daily =url::current("cetak","daily/".$id);
        $tpl->tombol_cetak_pdf="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-file-pdf-o\"></i>",$this->page->PageID,"cetak","title='Download Daily Report' href=\"".$url_daily."\" class=\"btn btn-primary btn-xs btn-detail-data\" target=\"_blank\"  role=\"".$id."\" ");
        
        $tpl->url_cetak      = url::current("cetak");
        $this->tpl->content_title = "Detail Shipment";
        $this->tpl->content = $tpl;
        $this->tpl->render();   
  } 
   public function cetak($jenis,$id) {
	   global $dcistem;
	    $db       = $dcistem->getOption("framework/db");
	    $master		= new Master_Ref_Model();
        $shipment=new Shipment_Ore_Model();
        $TglSkrg		=date("Y-m-d H:i:s");
	    //ob_start();
	   // $master->kopsurat("pdf");
	    //$kopsurat 	= ob_get_clean();
	    set_time_limit(1800);
   		ini_set("memory_limit","512M");
	   $detail=$shipment->getShipmentByShift($id);
	   switch($jenis){
	       case "daily":
        	    ob_start();
        		$tpl  = new View("cetak/shipment_daily");
        		$ref_id=$_SESSION["framework"]["ref_id"] ;
        		// $tpl->kop_surat = $kopsurat;
        		
        	 
        	    
        		$tpl->detail=$detail;
        		$tpl->title ="Activity Daily Report : ".strtoupper($detail->barge_name);
        		$tpl->content = $tpl;
        		$tpl->render(); 
        	    $content 	= ob_get_clean();
        	
        		$pdf		=new mpdf60();
        		$mpdf		= $pdf->build("L","A4",array(15,15,25,15));
                $mpdf->charset_in = 'iso-8859-4';
        	  
        		$mpdf->WriteHTML($content);
        		$mpdf->Output('shipment_daily_'.$id.'.pdf','I');
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
        $hasil  = array();
        if(trim($kategori)=="list_gangguan"){
            $shipment   = new Shipment_Ore_Model();
            $hasil=$shipment->json_gangguan($nama);
        }
        if(trim($kategori)=="kordinator"){
            $coord  = new  List_Kordinator_Model();
            $hasil  =$coord->json($nama);
        }
         
         echo json_encode($hasil);  
    }   
    public function comboAjax($kategori) {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		
	    
		if (sizeof($_POST) > 0) {
		    $aVars = $_POST;
		} else {
		    if (sizeof($_GET) > 0) {
		        $aVars = $_GET;
		    } else {
		        $aVars = array();
		    }
		}
        //echo $kategori;
      //  echo "<pre>";print_r($aVars);echo "</pre>";
        $parentcode=$aVars['parentkode'];
        $hasil      = array();
        if(trim($kategori)=="listgate"){
            $jetty    = new Ref_Jetty_Model();
            $hasil  =$jetty->comboAjax($kategori,$parentcode,$aVars['nilai']);
        }elseif(trim($kategori)=="list_trucks"){
            $truck    = new Ref_Equipment_Model();
            $hasil  =$truck->comboAjax($kategori,$parentcode,$aVars['nilai']);
        }elseif(trim($kategori)=="list_domes"){
            $dome    = new Ref_Dome_Model();
            $condition="";
            if(isset($aVars['status']) and trim($aVars['status'])<>""){
                $condition="status='".$aVars['status']."'";
            }
            //echo $condition;
            $hasil  =$dome->comboAjax($kategori,$parentcode,$aVars['nilai'],$condition);
        }else{
            $wilayah=new Ref_Wilayah_Model();
            $hasil=$wilayah->comboAjax($kategori,$parentcode,$aVars['nilai']);
        }
        
        echo $hasil;
   }
 
}
?>