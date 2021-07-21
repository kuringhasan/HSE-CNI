<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Hrd_Employees_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        global $dcistem;
      
	}
	
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("hrd_employees");
        $db   = $dcistem->getOption("framework/db");
        $login=new Adm_Login_Model();
       $master=new Master_Ref_Model();
        $KodeDosen=$_SESSION["framework"]["ref_id"] ;
        $Username=$_SESSION["framework"]["current_user"]->Username ;
        $list_bulan=$master->listarraybulan();
      	$tpl->list_bulan  = $list_bulan;
        $list_kota=Model::getOptionList("tbrkabupaten","kabupatenPropinsiKode","kabupatenNama",""); 
            $tpl->list_kota =$list_kota;
       	$url_form = url::current("add");    
         
       	$TombolTambah=$login->privilegeInputForm("link","","btn-tambah-data","<i class=\"fa fa-plus-circle\"></i>",$this->page->PageID,"add","title='Tambah Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_form."\" class=\"btn btn-primary btn-xs\" data-target=\"#largeModal\"");
       	$tpl->TombolTambah      = $TombolTambah; 
        
        $url_import = url::current("import");    
        $TombolImport=$login->privilegeInputForm("link","","btn-import-data","<i class=\"fa fa-upload\"></i> Import",$this->page->PageID,"import","title='Import Data'  href=\"".$url_import."\" class=\"btn btn-primary btn-xs\" ");
       	$tpl->TombolImport      = $TombolImport;
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
            $cols=array(0=>"id",
                        1=>"em.unit",
                        2=>"p.name");
            $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
                
            $list_qry=$db->select("SQL_CALC_FOUND_ROWS em.partner_id, em.id,em.no_induk,em.unit,p.gender,DATE_FORMAT(tanggal_lahir,'%d/%m/%Y') tanggal_lahir,
            DATE_FORMAT(tanggal_mulai_kerja,'%d/%m/%Y') tanggal_mulai_kerja,partner_id,p.name,p.alias,gelar_depan,
            gelar_belakang,p.alamat,kab.kabupatenNama,
            job_title_id,jenis_kontrak_id","employees em
            inner join partner p on p.id=em.partner_id
            left join tbrkabupaten kab on kab.kabupatenKode=p.alamat_kabupaten
            left join job_title jab on jab.id=em.job_title_id")
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
                $ListData[$i]['No']=$no;
                $ListData[$i]['ID']=$data->id;
                $ListData[$i]['NoInduk']=$data->no_induk; 
                $nama_lengkap=$master->nama_dan_gelar($data->gelar_depan,$data->name,$data->gelar_belakang);
                $ListData[$i]['Nama']=$nama_lengkap;       
                $ListData[$i]['TanggalLahir']=$data->tanggal_lahir;
                $ListData[$i]['JenisKelamin']	= $referensi['sex'][$data->gender];
                $ListData[$i]['Alamat']=$data->alamat;
                $ListData[$i]['Kota']=$data->kabupatenNama;
                $ListData[$i]['Unit_Jabatan']="";
                $ListData[$i]['TMK']=$data->tanggal_mulai_kerja;
                //$ListData[$i]['Detail']=$shipment->getShipmentDetail($data->id,"array");;
                $url_del      = url::current("del",$data->partner_id);
    			$url_edit =url::current("edit",$data->partner_id);
                $url_detail =url::current("detail",$data->partner_id);
                $url_verifikasi  = url::current("verifikasi",$data->partner_id);
               	$tombol          = "";
                $btn_group="<div class=\"btn-group\">";
                $btn_group=$btn_group.$login->privilegeInputForm("link","","","<i class=\"fa fa-check-square-o\"></i>",$this->page->PageID,"checklist","title='Checklist Persyaratan' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_checklist."\" class=\"btn btn-primary btn-xs btn-checklist-data\" data-target=\"#largeModal\" role=\"".$data->partner_id."\"");
                //if($data->reg_step<8){
               	    $btn_group=$btn_group.$login->privilegeInputForm("link","","","<i class=\"fa fa-gear\"></i>",$this->page->PageID,"edit","href=\"".$url_edit."\" title='Edit Pegawai' class=\"btn btn-primary btn-xs btn-edit-data\" target=\"_blank\"  ");
                //}
    			$btn_group=$btn_group.$login->privilegeInputForm("link","","","<i class=\"fa fa-user\"></i>",$this->page->PageID,"detail","title='Detail Data' href=\"".$url_detail."\" class=\"btn btn-primary btn-xs btn-detail-data\" target=\"_blank\"  role=\"".$data->partner_id."\" ");
      	         $btn_group=$btn_group."</div>";
                $ListData[$i]['Aksi']=$btn_group;
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
	    	$tpl  = new View("form_transit_ore");
            $list_kontraktor=Model::getOptionList("partner","id","name","","ifnull(is_contractor,0)=1"); 
            $tpl->list_kontraktor =$list_kontraktor;
            $list_truck=Model::getOptionList("equipment","id","nomor","","ifnull(category,'')='HA'"); 
            $tpl->list_truck =$list_truck;
     
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
  public function edit($partner_id,$proses=""){     
    global $dcistem;
	$db = $dcistem->getOption("framework/db");
	date_default_timezone_set("Asia/Jakarta");
	$master=new Master_Ref_Model();
    $pegawai  = new List_Pegawai_Model();
     $login=new Adm_Login_Model();
	$msg=array();
    $step   = 1;
    $nama_lengkap="";
    $error_msg="";
    if(trim($partner_id)=="" or  $partner_id == null){
		
        $error_msg="Error, partner_id harus diisi";
	}
    $current_level=$_SESSION["framework"]['user_level'];
	if(trim($proses)=="save")
	{
        //VALIDASI FORM DULU      
        if(trim($error_msg)=="")
	    {
	        $profil		= $pegawai->getBiodata($partner_id);
            // echo "<pre>";print_r($profil);echo "</pre>";
            $nama_lengkap=$profil->name;
            $step       = $profil->reg_step==""?1:$profil->reg_step;
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
            	    
               	    $wasta			=$_POST['nama'];
    	        	$wasta_val		=$master->scurevaluetable($wasta);
                    $gelar_depan			=$_POST['gelar_depan'];
    	        	$gelar_depan_val		=$master->scurevaluetable($gelar_depan);
                    $gelar_belakang			=$_POST['gelar_belakang'];
    	        	$gelar_belakang_val		=$master->scurevaluetable($gelar_belakang);
                    $panggilan			=$_POST['panggilan'];
    	        	$panggilan_val		=$master->scurevaluetable($panggilan);
                    $nik			=$_POST['nik'];
    	        	$nik_val		=$master->scurevaluetable($nik);
                    $no_kk			=$_POST['no_kk'];
    	        	$no_kk_val		=$master->scurevaluetable($no_kk);
    	        	$sex				=$_POST['gender'];
    	        	$sex_val			=$master->scurevaluetable($sex);
    	        	$marital				=$_POST['status_pernikahan'];
    	        	$marital_val			=$master->scurevaluetable($marital);
                    $nama_pasangan				=$_POST['nama_pasangan'];
    	        	$nama_pasangan_val			=$master->scurevaluetable($nama_pasangan);
    	        	$kota_lahir			=$_POST['tempat_lahir'];
    	        	$kota_lahir_val		=$master->scurevaluetable($kota_lahir);
    	        	$tahun_lahir		=$_POST['tahun_lahir'];
    	        	$bulan_lahir		=$_POST['bulan_lahir'];
    	        	$tgl_lahir			=$_POST['tanggal_lahir'];
                    $tanggal_lahir      ="";
                    if(trim($tahun_lahir)<>"" and trim($bulan_lahir)<>"" and trim($tgl_lahir)<>"" ){
                        $tanggal_lahir		=$tahun_lahir."-".$bulan_lahir."-".$tgl_lahir;
                    }
    	        	
    	        	$tanggal_lahir_val		=$master->scurevaluetable($tanggal_lahir);
    	        	$agama			=$_POST['agama'];
    	        	$agama_val		=$master->scurevaluetable($agama);
    	        
    	            $provinsi			=$_POST['provinsi'];
    				$provinsi_val		=$master->scurevaluetable($provinsi);
    				$kota			=$_POST['kota'];
    				$kota_val		=$master->scurevaluetable($kota);
    				$alamat			=$_POST['alamat'];
    				$alamat_val		=$master->scurevaluetable($alamat);
    	        	$rt				=$_POST['rt'];
    	        	$rt_val		=$master->scurevaluetable($rt);
    	        	$rw				=$_POST['rw'];
    	        	$rw_val		=$master->scurevaluetable($rw);
    	        	$desa			=$_POST['desa'];
    	        	$desa_val		=$master->scurevaluetable($desa);
    	        	$kec			=$_POST['kecamatan'];
    	        	$kec_val		=$master->scurevaluetable($kec);
    	        	$kodepos			=$_POST['kodepos'];
    	        	$kodepos_val		=$master->scurevaluetable($kodepos);
                    $hp			=$_POST['hp'];
    	        	$hp_val		=$master->scurevaluetable($hp);
                    $telp			=$_POST['telp'];
    	        	$telp_val		=$master->scurevaluetable($telp);
                    $email			=$_POST['email'];
    	        	$email_val		=$master->scurevaluetable($email);
                    
                    
    		        $next_step_val		=$master->scurevaluetable((int)$LangkahKe+1,"number");
                    
                    
                    
                    
                    $cols_and_vals="name=$wasta_val,gelar_depan=$gelar_depan_val,gelar_belakang=$gelar_belakang_val,alias=$panggilan_val,
                    nik=$nik_val,no_kk=$no_kk_val,tempat_lahir=$kota_lahir_val,
                    tanggal_lahir=$tanggal_lahir_val,agama=$agama_val,gender=$sex_val,status_pernikahan=$marital_val,
                    nama_pasangan=$nama_pasangan_val,alamat=$alamat_val,
                    alamat_rt=$rt_val,alamat_rw=$rw_val,alamat_kecamatan=$kec_val,alamat_desa=$desa_val,
                    alamat_kabupaten=$kota_val,phone=$hp_val,telepon=$telp_val,email=$email_val,
                    kodepos=$kodepos_val,reg_step=$next_step_val,reg_lastupdate=$tgl_skrg_val";
    			
                    
    				$sqlup ="UPDATE partner SET $cols_and_vals WHERE id=$partner_id;";
       	            $rsl_cust=$db->query($sqlup);
    				if(isset($rsl_cust->error) and $rsl_cust->error===true){
    					$msg['success']=false;
    			        $msg['message']="Error edit data, ".$rsl_cust->query_last_message;
    				}else{	
                        $msg['id']          =$partner_id;
                        $msg['success']     =true;
                        $msg['next_action'] ="edit";
                        $msg['message']     ="Berhasil";
                         
    	            }
       	        }
                if ($LangkahKe==2){
                    //echo "<pre>";print_r($_POST);echo "</pre>";
                    $lanjut=false;
                   	if(isset($_POST['tidak_ada_pendidikan']) and $_POST['tidak_ada_pendidikan']=="on"){
                   	    $lanjut=true;
                   	    
                   	}else{
                   	    $lanjut=true;
                        $cek_education=$db->select("id","history_education")->where("partner_id=$partner_id")->get();
                        if(count($cek_education)==0){
                            $lanjut=false;
        			        $msg_error="Anda belum mengisi data pendidikan, bila tidak ada silahkan centang : Tidak ada tanggungan";
                        }
                    }// end tidak ada tanggungan
                    if($lanjut==true){
                        $next_step_val		=$master->scurevaluetable((int)$LangkahKe+1,"number");                        
                        $cols_and_vals="reg_step=$next_step_val,reg_lastupdate=$tgl_skrg_val";                      
        				$sqlup ="UPDATE partner SET $cols_and_vals WHERE id=$partner_id;";
                        $rsl_cust=$db->query($sqlup);
        				if(isset($rsl_cust->error) and $rsl_cust->error===true){
        					$msg['success']=false;
        			        $msg['message']="Error edit data, ".$rsl_cust->query_last_message;
        				}else{	
                            $msg['id']          =$partner_id;
                            $msg['success']     =true;
                            $msg['next_action'] ="edit";
                            $msg['message']     ="Berhasil";
                             
        	            }
                        
                    }else{
                        $msg['success']=false;
     			        $msg['message']=$msg_error;
                    }
    				
                }
                if ($LangkahKe==3){
                    //echo "<pre>";print_r($_POST);echo "</pre>";
                    $lanjut=false;
                   	if(isset($_POST['tidak_ada_working']) and $_POST['tidak_ada_working']=="on"){
                   	    $lanjut=true;
                   	    
                   	}else{
                   	    $lanjut=true;
                        $cek_working=$db->select("id","history_working")->where("partner_id=$partner_id")->get();
                        if(count($cek_working)==0){
                            $lanjut=false;
        			        $msg_error="Anda belum mengisi data pekerjaan, bila tidak ada silahkan centang : Tidak ada tanggungan";
                        }
                    }// end tidak ada tanggungan
                    if($lanjut==true){
                        $next_step_val		=$master->scurevaluetable((int)$LangkahKe+1,"number");                        
                        $cols_and_vals="reg_step=$next_step_val,reg_lastupdate=$tgl_skrg_val";                      
        				$sqlup ="UPDATE partner SET $cols_and_vals WHERE id=$partner_id;";
                        $rsl_cust=$db->query($sqlup);
        				if(isset($rsl_cust->error) and $rsl_cust->error===true){
        					$msg['success']=false;
        			        $msg['message']="Error edit data, ".$rsl_cust->query_last_message." ".$sqlup;
        				}else{	
                            $msg['id']          =$partner_id;
                            $msg['success']     =true;
                            $msg['next_action'] ="edit";
                            $msg['message']     ="Berhasil";
                             
        	            }
                        
                    }else{
                        $msg['success']=false;
     			        $msg['message']=$msg_error;
                    }
    				
                }
            	if ($LangkahKe==4){
            	    
               	    //echo "<pre>";print_r($_POST);echo "</pre>";
                    $lanjut=false;
                   	if(isset($_POST['tidak_ada_working']) and $_POST['tidak_ada_working']=="on"){
                   	    $lanjut=true;
                   	    
                   	}else{
                   	    $lanjut=true;
                        $cek_jabatan=$db->select("id","history_job_title")->where("partner_id=$partner_id")->get();
                        if(count($cek_jabatan)==0){
                            $lanjut=false;
        			        $msg_error="Anda belum mengisi data jabatan, bila tidak ada silahkan centang : Tidak ada tanggungan";
                        }
                    }// end tidak ada tanggungan
                    if($lanjut==true){
                        $next_step_val		=$master->scurevaluetable((int)$LangkahKe+1,"number");                        
                        $cols_and_vals="reg_step=$next_step_val,reg_lastupdate=$tgl_skrg_val";                      
        				$sqlup ="UPDATE partner SET $cols_and_vals WHERE id=$partner_id;";
                        $rsl_cust=$db->query($sqlup);
        				if(isset($rsl_cust->error) and $rsl_cust->error===true){
        					$msg['success']=false;
        			        $msg['message']="Error edit data, ".$rsl_cust->query_last_message." ".$sqlup;
        				}else{	
        				    // insert jabatan skrg ke tabel employees
                            $filter_current="partner_id=$partner_id and ifnull(current_job_title,0)=1";
                            $cek_current=$db->select("id,job_title_id","history_job_title")->where($filter_current)->get(0);
                            
                            //===========================
                            $msg['id']          =$partner_id;
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
                    
                    $two_digit_year=date("y");
                    $no_anggota			="X".$_POST['no_anggota'];
                    $periode_aktif			=$_POST['periode_aktif'];
                   // echo "<pre>";print_r($_POST);echo "</pre>";exit;
                    $no_anggota_val			=$master->scurevaluetable($no_anggota);
                    $barcode_mcpm			=$no_anggota."P01M".$two_digit_year;
                    $barcode_mcpm_val		=$master->scurevaluetable($barcode_mcpm);
                    $barcode_logistik		=$no_anggota."L01M".$two_digit_year;
                    $barcode_logistik_val	=$master->scurevaluetable($barcode_logistik);
                    $next_step_val		=$master->scurevaluetable((int)$LangkahKe+1,"number");
    				$cols_and_values="C_ANGGOTA=$no_anggota_val,BARCODE=$barcode_mcpm_val,BARCODE_LOGISTIK=$barcode_logistik_val,
                    periode_mulai_aktif=$periode_aktif,reg_step=$next_step_val,reg_lastupdate=$tgl_skrg_val,status_pendaftaran='confirmed_biodata'";
    				$sqlin ="UPDATE anggota SET  $cols_and_values WHERE ID_ANGGOTA=$member_id;";
     
                    $rsl_cust=$db->query($sqlin);
    				if(isset($rsl_cust->error) and $rsl_cust->error===true){
    					$msg['success']=false;
    			        $msg['message']="Error edit data, ".$rsl_cust->query_last_message;
    				}else{	
    			        /*$get=$db->select("max(CAST(REPLACE(C_ANGGOTA,'X','')  AS SIGNED)) as max_id","anggota")->where("left(C_ANGGOTA,1)='X'")->get(0);
                       $max_id=(int)$get->max_id+1;
                       $id_tmp="000".$max_id;
                       $id_sementara="X".substr($id_tmp,(strlen($id_tmp)-3),3);
                       $id_sementara_val		=$master->scurevaluetable($id_sementara);*/
                       
                        $msg['id']          =$member_id;
                        $msg['success']     =true;
                        $msg['next_action'] ="edit";
                        
                        $notification=new List_Notification_Model();
                        $periode=new List_Periode_Model();
                        $msj="Atas nama <strong>$nama_lengkap</strong> mendaftar sebagai anggota KPBS dan sudah melakukan pengisian biodata secara lengkap.
                        Berikutnya adalah survey kepemilikan sapi langsung ke kandang.";
                        $notification->insert("Survey kepemilikan sapi",$msj,"administrator_keswan");
                        $member->update_status_member($member_id,0,$TglSkrg,"Pendaftaran anggota baru KPBS");
                        /** get current periode and nect periode */
                        $pilihan_periode=$periode->getCurrentAndNextPeriodebyDate(date("Y-m-d"));
                        /** ================================================= */
                        //$insrt=$member->insert_anggota_periode_placement($member_id,$getperiode['id'],$profil->ID_KELOMPOK_HARGA,$profil->NAMA,$profil->NIK,$profil->NoKK,0);
                        if($pilihan_periode['current']['periode_id']==$periode_aktif){
                            $insrt=$member->insert_anggota_periode_placement($member_id,$periode_aktif,$profil->ID_KELOMPOK_HARGA,$profil->NAMA,$profil->NIK,$profil->NoKK,0);
                        }
                        if($pilihan_periode['next']['periode_id']==$periode_aktif){
                            $insrt=$member->insert_anggota_periode_placement($member_id,$pilihan_periode['current']['periode_id'],$profil->ID_KELOMPOK_HARGA,$profil->NAMA,$profil->NIK,$profil->NoKK,0);
                            $insrt=$member->insert_anggota_periode_placement($member_id,$periode_aktif,$profil->ID_KELOMPOK_HARGA,$profil->NAMA,$profil->NIK,$profil->NoKK,0);
                        }
                        // GENERATE TAGIHAN/KEWAJIBAN
                        $member->generateTagihanAnggota($member_id);
                        
                       
                        $msg_odoo="";
                        $odoo=new Odoo_Api_Model();
                        $data_calon=$member->getBiodataCalon($member_id);
                        $sync_odoo=$odoo->sync_calon_member($data_calon);
                        
                        if($sync_odoo['sync']== false){
                            $msg_odoo=$sync_odoo['message'];
                        }
                        $msg['message']     ="Berhasil. ".$msg_odoo;
                         
    	            }
                    
                }
                if ($LangkahKe==6){
                   
                   $cek_unverified_cow=$db->select("id","cow")->where("anggota_id=".$member_id." and ifnull(is_need_verification,0)=1")->get();
                    if(empty($cek_unverified_cow)){
                        $petugas			=$_POST['petugas'];
        	        	$petugas_val		=$master->scurevaluetable($petugas,"number");
                        $alamat_kandang				=$_POST['alamat_kandang'];
        	        	$alamat_kandang_val			=$master->scurevaluetable($alamat_kandang);
        	        	
        	        	$koordinat_kandang		=$_POST['koordinat_kandang'];
        	        	$koordinat_kandang_val		=$master->scurevaluetable($koordinat_kandang);
                        $next_step_val		=$master->scurevaluetable((int)$LangkahKe+1,"number");
    					$cols_and_values="petugas_survey=$petugas_val,ALAMAT2=$alamat_kandang_val,LOKASI=$koordinat_kandang_val,
                                    status_pendaftaran='confirmed_population',reg_step=$next_step_val,reg_lastupdate=$tgl_skrg_val";
    				    $sqlin ="UPDATE anggota SET  $cols_and_values WHERE ID_ANGGOTA=$member_id;";
         
                        $rsl_cust=$db->query($sqlin);
        				if(isset($rsl_cust->error) and $rsl_cust->error===true){
        					$msg['success']=false;
        			        $msg['message']="Error edit data, ".$rsl_cust->query_last_message;
        				}else{	
    			             
                            $msg['id']          =$member_id;
                            $msg['success']     =true;
                            $msg['next_action'] ="edit";
                            $msg['message']     ="Berhasil. ".$msg_odoo;
                            $notification=new List_Notification_Model();
                            $title="Validasi simpanan";
                            $msj="Atas nama <strong>$nama_lengkap</strong> mendaftar sebagai anggota KPBS dan sudah disurvey kepemilikan sapinya.
                            Berikutnya adalah validasi simpanan-simpanan.";
                           
                            
                            
                            /**  =========== cek setoran simpanan ============ 
                            * 1. jiga sudah ada setoran, maka langkah validasi langsung terflag
                            * 2. jika belum ada setoran maka  langkah validasi keuangan masih menunggu eksekusi
                            */
                            $total_setoran= $profil->tagihan->total_setoran;
                            //if($total_setoran>=500000){
                                $aktivasi= $member->aktivasi_anggota($member_id,$profil->NoAnggota,$profil->NAMA,$profil->periode_mulai_aktif,$profil->ID_KELOMPOK_HARGA,$profil->NIK,$profil->NoKK,$LangkahKe,$TglSkrg);
                                $title="Pembayaran sudah divalidasi";
                                $msj="Atas nama <strong>$nama_lengkap</strong> sudah disurvey kepemilikan sapinya.
                                Dan sudah divalidasi simpanan-simpanan dengan total Rp. ".number_format($total_setoran,0,",",".");
                                
                            //}
                            $notification->insert($title,$msj,"administrator_keanggotaan");
                            $notification->insert($title,$msj,"administrator_keuangan");
                            /** =============================================== */
                            
                            //$notification->insert("Validasi simpanan",$msj,"administrator_keuangan");
                            $msg_odoo="";
                            
                            
                            /*$odoo=new Odoo_Api_Model();
                             $data_calon=$member->getBiodataCalon($member_id);
                             $sync_odoo=$odoo->sync_calon_member($data_calon);
                             
                            if($sync_odoo['faultCode']==1){
                                $msg_odoo="Gagal sync to odoo";
                            }
                             */
        	            }
                     }else{
                        $msg['success']=false;
      			        $msg['message']="Error, masih ada sapi yang belum diverifikasi";
                     }
                }
                
                if ($LangkahKe==7){
                    
                    $two_digit_year=date("y");
                    $tanggal_masuk=date("Y-m-d");
                    $aktivasi= $member->aktivasi_anggota($member_id,$profil->NoAnggota,$profil->NAMA,$profil->periode_mulai_aktif,$profil->ID_KELOMPOK_HARGA,$profil->NIK,$profil->NoKK,$LangkahKe,$TglSkrg);
                    $msg=$aktivasi;
                   
                    
                }
                
                $er_msj="";
    			$pesan_sukes="";
    			if ($LangkahKe==5){
    			     $next_step_val		=$master->scurevaluetable((int)$LangkahKe+1,"number");
                 
                // echo "<pre>";print_r($_FILES['file_foto']);echo "</pre>";
    	   //echo "<pre>";print_r($_POST['pilih_nama']);echo "</pre>";
                    $pilih_nama=$_POST['pilih_nama'];
                    $hasil_upload=array();
    				if(isset($_FILES['file_foto'])){
    				    $nama_succes="";
                         $nama_gagal="";
    				    foreach($_POST['pilih_upload'] as $key=>$value){
    				        $tanggungan_id=$_POST['tanggungan_id'][$key];
                            $hasil_upload[$key]['id']=$tanggungan_id;
                            $hasil_upload[$key]['nama']=$tanggungan_id;
                            
                            
        				    $pathfile=$_FILES['file_foto']["tmp_name"][$key];
        			        $login_as=	$_SESSION['framework']['login_as'];      	
        			        $ref_id=$_SESSION["framework"]["ref_id"] ;
        			        $type = $_FILES['file_foto']['type'][$key];
        			        $extension = pathinfo($_FILES['file_foto']['name'][$key],PATHINFO_EXTENSION);  
        			       // $type = $_FILES['file_foto']['type'][$key];
        			        $Tanggal=date("YmdHis");
        			        $namafile=$member_id.".".$extension;
                            $nmfile = "foto/members/".$namafile;   
                            if(trim($value)=="child"){                                
                                $namafile="tanggungan_".$tanggungan_id.".".$extension;
        			             $nmfile = "foto/tanggungan/".$namafile;   
        			        }
                            if($_FILES['file_foto']['name'][$key]<>""){
            			        if(file_exists($nmfile))
            			        {
            			        	unlink($nmfile);
            			        }
                                $upload=false;
            					if(move_uploaded_file($_FILES['file_foto']["tmp_name"][$key],$nmfile))
            		        	{   
            		        	    
            	        			$filefoto_val		=$master->scurevaluetable($namafile);
            						$cols_and_values="file_foto=$filefoto_val,reg_lastupdate=$tgl_skrg_val";
                                    $hasil_up=array();
                                    if(trim($value)<>"child"){
                                        //upload to erp
                                        $mime = mime_content_type($nmfile);
                                        $info = pathinfo($nmfile);
                                        $name = $info['basename'];
                                        $output = new CURLFile(realpath($nmfile), $mime, $name);
                                        
                                        //new CurlFile(drupal_realpath($file->uri),$file->filemime,$file->filename)
                                        //echo "<pre>"; print_r($output);echo "</pre>";
                                        $url_upload_erp="http://111.223.254.6:8080/anggota/photo/upload";
                                        //echo realpath($nmfile);
                                        // initialise the curl request
                                        $postfields["photo"] =$output;//$output;
                                        $headers = array("Content-Type" => "multipart/form-data");
    
                                        $ch = curl_init($url_upload_erp);
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                        curl_setopt($ch, CURLOPT_POST, TRUE);
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                                        
                                        $response = curl_exec($ch);
                                        if(!curl_errno($ch))
                                        {
                                            $info = curl_getinfo($ch);
                                            if ($info['http_code'] == 200) {
                                              // Files uploaded successfully.
                                                $hasil=json_decode($response);
                                                if($hasil->success==true){
                                                    $cols_and_values=$cols_and_values.",PATH_FOTO='".$hasil->data."'";
                                                }
                                            }
                                        }
                                        else
                                        {
                                          // Error happened
                                          $error_message = curl_error($ch);
                                        }
                                        curl_close($ch);
                                        
                                        //echo "<pre>"; print_r($hasil);echo "</pre>";
                                    }
            						$sqlin ="UPDATE anggota SET $cols_and_values WHERE ID_ANGGOTA=$member_id;";
                                   // echo $sqlin;
                                    if(trim($value)=="child"){
                                      $cols_and_values="tgFileFoto=$filefoto_val,tgLastupdate=$tgl_skrg_val";
    		                          $sqlin ="UPDATE  anggota_tanggungan SET  $cols_and_values WHERE tgID=$tanggungan_id;";
                			        }
                                    //$hasil_upload[$key]['upload']=$hasil_up;
                                   
                                    $result =$db->query($sqlin);
                                    if(isset($result->error) and $result->error===true){
                                        $hasil_upload[$key]['success']=false;
                                        $hasil_upload[$key]['message']="Error, ".$rsl_cust->query_last_message;
                                    }else{
                                        $nama_succes=trim($nama_succes)<>""?$nama_succes.", ".$pilih_nama[$key]:$pilih_nama[$key];
                                        $hasil_upload[$key]['message']="Berhasil upload";
                                        if(trim($value)=="child"){
                                            //upload foto tanggungan ke erp
                                        }else{
                                            //upload foto anggota ke erp
                                            /*$dfiles=$_FILES;
                                            $data_files = array_map(function($dfiles) {
                                                return array(
                                                    'name' => $tag['name'],
                                                    'value' => $tag['url']
                                                );
                                            }, $tags);
                                            */
                                        }
                                        
                                        
                                    }
            		        	}else{
            		        		$hasil_upload[$key]['success']=false;
                                    $hasil_upload[$key]['message']="Gagal upload";
                                    $nama_gagal=trim($nama_gagal)<>""?$nama_gagal.", ".$pilih_nama[$key]:$pilih_nama[$key];
            		        	}
                            }else{// jika file kosong
                                $hasil_upload[$key]['success']=false;
                                $s="Tidak ada file yang diupload";
                                $hasil_upload[$key]['message']=$s;
                                $nama_gagal=trim($nama_gagal)<>""?$nama_gagal.", ".$pilih_nama[$key]." (".$s.")":$pilih_nama[$key]." (".$s.")";
                            }
                        }//end for
                        //echo "<pre>";print_r($hasil_upload);echo "</pre>";
                        
                        if(trim($nama_succes)=="" and trim($nama_gagal)<>""){
                            $msg['success']     =false;
                            $msg['message']     ="Gagal upload : $nama_gagal";
                        }
                        if(trim($nama_succes)<>"" and trim($nama_gagal)<>""){
                            $msg['success']     =true;
                            $msg['message']     ="Sukses upload dan gagal untuk $nama_gagal";
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
                        $cols_and_vals="status=0,reg_step=$next_step_val,reg_lastupdate=$tgl_skrg_val";                      
        				$sqlup ="UPDATE anggota SET $cols_and_vals WHERE ID_ANGGOTA=$member_id;";
                        $rsl_cust=$db->query($sqlup);
        				if(isset($rsl_cust->error) and $rsl_cust->error===true){
        					$msg['success']=false;
        			        $msg['message']="Error edit data, ".$rsl_cust->query_last_message;
        				}else{	
                            $msg['id']          =$member_id;
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
         }else{
             $msg['success']=false;
             $msg['message']=$error_msg;
        }
    	echo json_encode($msg);  
        exit;
    }elseif($proses=="prev")
	{
	    $LangkahKe=isset($_POST['current_step'])?$_POST['current_step']:1;
        $next_step_val	=$master->scurevaluetable((int)$LangkahKe-1,"number");
        $cols_and_values="reg_step=$next_step_val";
        $sqlin ="UPDATE partner SET  $cols_and_values WHERE id=$partner_id;";
        
        $rsl_cust=$db->query($sqlin);
        if(isset($rsl_cust->error) and $rsl_cust->error===true){
        	$msg['success']=false;
            $msg['message']="Error edit data, ".$rsl_cust->query_last_message;
        }else{	
            $msg['id']          =$partner_id;
            $msg['success']     =true;
            $msg['next_action'] ="edit";
            $msg['message']     ="Berhasil";
          
        }
        echo json_encode($msg);  
        exit;
    }elseif($proses=="form"){
        if(trim($error_msg)=="")
	    {
            $status_pendaftaran = "draft_biodata";
            $step               = 1;
            $profil             = new stdClass;
            $url_populasi       = "";
            $url_tanggungan     = "";
            $profil		= $pegawai->getBiodata($partner_id);
                
            $step       = $profil->reg_step==""?1:$profil->reg_step;
            $status_pendaftaran=trim($profil->status_pendaftaran)==""?"draft_population":$profil->status_pendaftaran;
            $url_working = url::current("work",$partner_id);
            $url_education = url::current("education",$partner_id);
            //$url_job = url::current("job",$partner_id);
            $url_jabatan = url::current("jabatan",$partner_id);
         
            $punya_akses=false;
            $hak_akses= $this->access_step[$current_level->Unit];
            //echo "<pre>";print_r($profil);echo "</pre>";exit;
           
            if($current_level->LevelID=="administrator"){
                $punya_akses=true;
            }else{
                if(in_array($step,$hak_akses['step'])){                
                    $punya_akses=true;
                }
            }
            $form_template="form_pegawai";
            if(trim($status_pendaftaran)=="confirmed_population" and $punya_akses==false){
                $form_template="confirmed_population"; //file confirmed_population.php
            }
            if(trim($status_pendaftaran)=="confirmed_biodata" and $punya_akses==false){
                $form_template="confirmed_biodata"; //file confirmed_biodata.php
            }
            if(trim($form_template)<>"form_pegawai"){// start of form_pendaftaran
                $tpl  = new View($form_template);
                $tpl->profil = $profil;
                $tpl->url_working= $url_working;
                $tpl->url_education= $url_education;
               //echo "<pre>";print_r($profil);echo "</pre>";
            }else{// start of form_pendaftaran
                $tpl  = new View($form_template);
                
              
                $tpl->profil = $profil;
                
                $tombol_working=$login->privilegeInputForm("link","","btn-add-working","<i class=\"fa fa-plus\"></i>",$this->page->PageID,"work","title='Tambah Data' href=\"".$url_working."\" class=\"btn btn-primary btn-xs btn-add-working\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModalChild\"");  
                $tpl->TombolTambahWorking = $tombol_working;	
                
                $tpl->url_working= $url_working;
                
                $tombol_education=$login->privilegeInputForm("link","","btn-add-education","<i class=\"fa fa-plus\"></i>",$this->page->PageID,"education","title='Tambah Data' href=\"".$url_education."\" class=\"btn btn-primary btn-xs btn-add-education\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModalChild\"");  
                //echo "ce ".$tombol_education;
                $tpl->TombolTambahEducation = $tombol_education;	
                $tpl->url_education= $url_education;
                
                $tombol_jabatan=$login->privilegeInputForm("link","","btn-add-jabatan","<i class=\"fa fa-plus\"></i>",$this->page->PageID,"work","title='Tambah Data' href=\"".$url_jabatan."\" class=\"btn btn-primary btn-xs btn-add-jabatan\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModalChild\"");  
                $tpl->TombolTambahJabatan = $tombol_jabatan;
                 $tpl->url_jabatan= $url_jabatan;
               
                $next_step=$step+1;
               
            
                $tahun_akhir=(int)date("Y")-18;
                $tahun_awal=(int)date("Y")-26;
                $tpl->list_tahun_lahir=$master->listarraytahun($tahun_awal,$tahun_akhir);
                $listprovinsi  = Model::getOptionList("tbrpropinsi","propinsiKode","propinsiNama","propinsiKode asc","");   
                $tpl->list_provinsi =$listprovinsi;
                //$tpl->ListTPK= Model::getOptionList("mcp","id","name","id ASC","id<>19"); 
                $list_agama			=Model::getOptionList("tbragama", "agamaKode","agamaNama","agamaUrut ASC"); 
                $tpl->list_agama =$list_agama;
                
                $list_marital			=Model::getOptionList("status_pernikahan", "id","status","id ASC"); 
                $tpl->list_marital =$list_marital;
                
                $list_bulan=$master->listarraybulan();
                $tpl->list_bulan  = $list_bulan;
                $list_tanggal=$master->listarraytanggal();
                $tpl->list_tanggal  = $list_tanggal;  
            }// end of form_pendaftaran
           
            $tpl->punya_akses=$punya_akses;
            $tpl->step  = $step;
              
            $url_action = url::current("edit",$member_id);
                    $tpl->url_action= $url_action;
            
            $url_cetak		= url::current("cetak",$member_id);
            $TombolCetak=$login->privilegeInputForm("link","","btn-cetak-data","<i class=\"fa fa-file-pdf-o\"></i> Cetak Formulir",$this->page->PageID,"cetak","title='Cetak'  href=\"".$url_cetak."\"  target=\"_blank\" ");
           	$tpl->TombolCetak      = $TombolCetak; 
            $tpl->url_checklist		= url::current("checklist",$member_id);
            $tpl->url_jsonData		= url::current("jsonData");
            $tpl->url_comboAjax     = url::current("comboAjax");
        }else{	
            $msg['success']     =false;
            $msg['message']     =$error_msg;
        }
        $tpl->msg = $msg;
        $tpl->content = $tpl;
        $tpl->render(); 
	  
    }else{
        if(trim($error_msg)=="")
	    {
 	    
            $page=new Core_Page_Model();
            $modelsortir=new Adm_Sortir_Model();
    		$user_manage=new User_Manage_Model();
            $login=new Adm_Login_Model();
           
    		$auth     = new Auth();
            $current_level=$_SESSION["framework"]['user_level'];
            
            $step=1;
            $url_action = url::current("daftar");
            $url_populasi="";
            $url_tanggungan = "";
            $label_tombol_next="Daftar";
            $status_pendaftaran = "draft_biodata";
            $profil		= $pegawai->getBiodata($partner_id);
           // echo "<pre>";print_r($profil);echo "</pre>";
            $step       = $profil->reg_step==""?1:$profil->reg_step;
            $status_pendaftaran=trim($profil->status_pendaftaran)==""?"draft_biodata":$profil->status_pendaftaran;
            $url_action = url::current("edit",$partner_id);
            $label_tombol_next="Lanjut";
            
            if(trim($status_pendaftaran)=="confirmed_biodata"){
                $TglSkrg		=date("Y-m-d H:i:s");
            	$tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
				$cols_and_values3="reg_lastupdate=$tgl_skrg_val,status_pendaftaran='draft_population'";
				$sqlin3 ="UPDATE anggota SET  $cols_and_values3 WHERE ID_ANGGOTA=$partner_id;";
                $rsl_cust=$db->query($sqlin3);
                $status_pendaftaran="draft_population";
                
            }
            $url_populasi = url::current("populasi",$partner_id);
            $url_tanggungan = url::current("child",$partner_id);
             
            
            $punya_akses=false;
            $hak_akses= $this->access_step[$current_level->Unit];
            //echo "<pre>";print_r($profil);echo "</pre>";exit;
           
            if($current_level->LevelID=="administrator"){
                $punya_akses=true;
            }else{
                if(in_array($step,$hak_akses['step'])){                
                    $punya_akses=true;
                }
            }
            //echo "step: $status_pendaftaran ".$current_level->Unit;
            $form_template="confirmed_biodata";
            if($step==6 and (trim($status_pendaftaran)=="draft_population" and $current_level->Unit=="05" or  $current_level->LevelID=="administrator")  ){
                $form_template="verifikasi_population_calon"; //file confirmed_population.php
               // echo "<pre>";print_r($profil);echo "</pre>";
                
            }elseif($step>=6 and  (in_array($status_pendaftaran,array("confirmed_biodata","confirmed_population")) or $current_level->LevelID=="administrator") ){
                $form_template="confirmed_biodata"; //file confirmed_biodata.php
                
                
            }elseif($step<=5 and ($current_level->Unit=="04" or $current_level->LevelID=="administrator") ){  
                $form_template="form_pegawai_header";
            }
            
            $tpl             = new View($form_template);
            $show_validasi_keuangan=false;
            if(in_array ($current_level->Unit, array("04","03")) and $step==7){//jika bagian keanggotaan
                $show_validasi_keuangan=true;
            }
            $tpl->show_validasi_keuangan = $show_validasi_keuangan;
    		$tpl->profil = $profil;
            $tpl->url_populasi = $url_populasi;
            $tpl->url_tanggungan = $url_tanggungan;
           
            $current_level=$_SESSION["framework"]['user_level'];
            
            if (sizeof($_POST) > 0) {
    		    $aVars = $_POST;
    		} else {
    		    if (sizeof($_GET) > 0) {
    		        $aVars = $_GET;
    		    } else {
    		        $aVars = array();
    		    }
    		}
    		$msg      =array();
            
            $tpl->label_tombol_next		= $label_tombol_next;
            $next_step=$step+1;
            $login_as=$_SESSION['framework']['login_as'];
            $tpl->url_current = url::current("edit",$partner_id);
            
            $tpl->url_action = $url_action;
            $tpl->url_form  = url::current("edit",$partner_id."/form");
            $tpl->url_next_form  = url::current("edit");
            
            $tpl->step		= $step;
            $tpl->next_step		= $next_step;
            $punya_akses=false;
            $hak_akses= $this->access_step[$current_level->Unit];
           //  echo "<pre>";print_r($this->access_step);echo "</pre>";
             // echo "<pre>";print_r($hak_akses['step']);echo "</pre>";
            //echo $current_level->Unit;
            if($current_level->LevelID=="administrator"){
                $punya_akses=true;
            }else{
                if(in_array($step,$hak_akses['step'])){                
                    $punya_akses=true;
                }
            }
            
           
            
            $tpl->ref_steps=$this->steps;
            $tpl->punya_akses=$punya_akses;
            $url_cetak		= url::current("cetak",$member_id);
            $TombolCetak=$login->privilegeInputForm("link","","btn-cetak-data","<i class=\"fa fa-file-pdf-o\"></i> Cetak Formulir",$this->page->PageID,"cetak","title='Cetak'  href=\"".$url_cetak."\"  target=\"_blank\" class=\"btn btn-primary btn-xs\"");
           	$tpl->TombolCetak      = $TombolCetak; 
            $tpl->url_checklist		= url::current("checklist",$member_id);
        }else{	
            $msg['success']     =false;
            $msg['message']     =$error_msg;
        }
        $tpl->msg = $msg;
        $this->tpl->content_title = "Edit Data Karyawan";
        $this->tpl->content = $tpl;
        $this->tpl->render(); 
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
 public function education($partner_id,$aksi){     
    global $dcistem;
		$db = $dcistem->getOption("framework/db");
		date_default_timezone_set("Asia/Jakarta");
		$master=new Master_Ref_Model();
        $login=new Adm_Login_Model();
        $member = new List_Members_Model();
        $referensi	= $_SESSION["referensi"];
        $current_level=$_SESSION["framework"]['user_level'];
        $sqlin  = "";
        $judul="Riwayat Pendidikan";
        switch($aksi){
            case "add":
                
                $validasi=$this->validasiform(2,true);
        		$Final=false;
                if(count($validasi['arrayerror'])==0){
                    $TglSkrg		=date("Y-m-d H:i:s");
            	    $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
        	        $jenjang	        =trim($_POST['jenjang']);
        	        $institusi	   =trim($_POST['institusi']);
                    $prodi		=$_POST['prodi'];
    	        	$lokasi		=$_POST['lokasi'];
    	        	$gelar			=$_POST['gelar'];
                    $posisi_gelar			=$_POST['posisi_gelar'];
                   
                    $last_education	=(isset($_POST['last_education']) and $_POST['last_education']=='on')?"1":"";
        	        $tahun		=$_POST['tahun'];
    		        $partner_id_val	=$master->scurevaluetable($partner_id,"number");
                    $jenjang_val	=$master->scurevaluetable($jenjang,"string");
    		        $institusi_val=$master->scurevaluetable($institusi,"string");
    		        $prodi_val	       =$master->scurevaluetable($prodi,"string");
                    $lokasi_val	    =$master->scurevaluetable($lokasi);
    		        $gelar_val	   =$master->scurevaluetable($gelar,"string");
                    $posisi_gelar_val	   =$master->scurevaluetable($posisi_gelar,"string");
                    $last_education_val	=$master->scurevaluetable($last_education,"string");
                    $tahun_val	=$master->scurevaluetable($tahun,"string");
                    if($last_education=="1"){
                        $cek=$db->select("id","history_education")->where("partner_id=$partner_id")->get();
                        if(!empty($cek)){
                            $sqlinu ="UPDATE history_education SET last_education=null WHERE partner_id=$partner_id;";
                            $db->query($sqlinu);
                        }
                    }
                    
                    $cols="partner_id,jenjang,institusi,jurusan,location,graduation_year,
                    last_education,pendidikan,gelar,posisi_gelar,created";
            		$values="$partner_id_val,$jenjang_val,$institusi_val,$prodi_val,$lokasi_val,$tahun_val,
                    $last_education_val,null,$gelar_val,$posisi_gelar_val,$tgl_skrg_val";
            		$sqlin ="INSERT INTO  history_education ($cols) VALUES($values);";
                    $rsl_cust=$db->query($sqlin);
            		if(isset($rsl_cust->error) and $rsl_cust->error===true){
            			$msg['success']=false;
            	        $msg['message']="Error edit data pendidikan, ".$rsl_cust->query_last_message." ".$sqlin;
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
                $validasi=$this->validasiform(2,true);
        		$Final=false;
                if(count($validasi['arrayerror'])==0){
                    $education_id=$_POST['education_id'];
                    $TglSkrg		=date("Y-m-d H:i:s");
            	    $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
        	        $jenjang	        =trim($_POST['jenjang']);
        	        $institusi	   =trim($_POST['institusi']);
                    $prodi		=$_POST['prodi'];
    	        	$lokasi		=$_POST['lokasi'];
    	        	$gelar			=$_POST['gelar'];
                    $posisi_gelar			=$_POST['posisi_gelar'];
                    $last_education	=(isset($_POST['last_education']) and $_POST['last_education']=='on')?"1":"";
        	        $tahun		=$_POST['tahun'];
    		        $partner_id_val	=$master->scurevaluetable($partner_id,"number");
                    $jenjang_val	=$master->scurevaluetable($jenjang,"string");
    		        $institusi_val=$master->scurevaluetable($institusi,"string");
    		        $prodi_val	       =$master->scurevaluetable($prodi,"string");
                    $lokasi_val	    =$master->scurevaluetable($lokasi);
    		        $gelar_val	   =$master->scurevaluetable($gelar,"string");
                    $posisi_gelar_val	   =$master->scurevaluetable($posisi_gelar,"string");
                    $last_education_val	=$master->scurevaluetable($last_education,"string");
                    $tahun_val	=$master->scurevaluetable($tahun,"string");
                    if($last_education=="1"){
                        $cek=$db->select("id","history_education")->where("partner_id=$partner_id")->get();
                        if(!empty($cek)){
                            $sqlinu ="UPDATE history_education SET last_education=null WHERE partner_id=$partner_id;";
                            $db->query($sqlinu);
                        }
                    }
                    
                    $cols_and_vals="partner_id=$partner_id_val,jenjang=$jenjang_val,institusi=$institusi_val,
                    jurusan=$prodi_val,location=$lokasi_val,graduation_year=$tahun_val,last_education=$last_education_val,
                    pendidikan=null,gelar=$gelar_val,posisi_gelar=$posisi_gelar_val,last_update=$tgl_skrg_val";
            		
            	
            		
            		$sqlin ="UPDATE history_education SET $cols_and_vals WHERE id=".$education_id.";";
                    $rsl_cust=$db->query($sqlin);
            		if(isset($rsl_cust->error) and $rsl_cust->error===true){
            			$msg['success']=false;
            	        $msg['message']="Error edit ".strtolower($judul).", ".$rsl_cust->query_last_message." ".$sqlin;
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
                $education_id=$_POST['child_id'];
                if(trim($education_id)<>"")
                {
        	        $Nama=$_POST['nama'];
        	        $sqlin="DELETE FROM history_education WHERE id=".$education_id.";";
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
                $tpl  = new View("form_education");
               
                $education_id=isset($_GET['education_id'])?$_GET['education_id']:"";
                //print_r($_POST);
                if(trim($education_id)<>""){
                   $detail=$db->select("he.id,partner_id,he.jenjang,jp.jenjang jenjang_name,institusi,jurusan,location,graduation_year,
                    last_education,pendidikan,gelar,posisi_gelar,created","history_education he
                    inner join jenjang_pendidikan jp on jp.kode=he.jenjang")->where("id=$education_id")->get(0);
                    //echo "cek";
                    //echo "<pre>";print_r($detail);"</pre>";
                    $tpl->detail = $detail;
                  
                } 
      	    
                $list_jenjang  = Model::getOptionList("jenjang_pendidikan","kode","jenjang","urutan asc","");   
    	        $tpl->list_jenjang =$list_jenjang;
               
                $tpl->content = $tpl;
                $tpl->render(); 
            break;
            case "listdata":
                $punya_akses=false;
                $hak_akses= $this->access_step[$current_level->Unit];
                $profil= $member->getBiodataCalon($member_id);
                //echo "<pre>";print_r($hak_akses);echo "</pre>";exit;
               
                if($current_level->LevelID=="administrator"){
                    $punya_akses=true;
                }else{
                    if(in_array($profil->reg_step,$hak_akses['step'])){                
                        $punya_akses=true;
                    }
                }
                $draw=$_REQUEST['draw'];
                $list_qry=$db->select("he.id,partner_id,he.jenjang,jp.jenjang jenjang_name,institusi,jurusan,location,graduation_year,
                    last_education,pendidikan,gelar,posisi_gelar,created","history_education he
                    inner join jenjang_pendidikan jp on jp.kode=he.jenjang")->where("partner_id=$partner_id")->orderby("jp.urutan asc")->lim();
                $no=1;
                $i=0;
                $ListData=array();
                while($data = $db->fetchObject($list_qry))
                {
                    $ListData[$i]['No']=$no;
                    $ListData[$i]['ID']=$data->id;
                    $ListData[$i]['Jenjang']=$data->jenjang_name;
                    $ListData[$i]['Institusi']=$data->institusi;
                    $ListData[$i]['Jurusan']=$data->jurusan;
                    $ListData[$i]['Lokasi']=$data->location;
                    $ListData[$i]['Tahun']=$data->graduation_year;
                    $ListData[$i]['Terakhir']=$data->last_education;
                    $ListData[$i]['Gelar']=$data->gelar;
                    $ListData[$i]['PosisiGelar']=$data->posisi_gelar;
                    $tombol = "";
        			$url_education    =url::current("education",$data->partner_id);
                    //$url_del     = url::current("child",$data->tgIDAnggota."/del");
                    
                   	$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-gear\"></i>",$this->page->PageID,"education","title='Edit Data' href=\"".$url_education."\" class=\"btn btn-primary btn-xs btn-edit-education\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModalChild\" role=\"".$data->id."\"");             
        			$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"education","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_education."\" class=\"btn btn-primary btn-xs btn-del-education\" data-target=\"#largeModalChild\" role=\"".$data->id."\"");
                
                    $control=$tombol;  
                    $ListData[$i]['Tombol']=$control;
                    $i++;
                    $no++;
                }
               
               
                $hasil['draw']=$draw;
                $hasil['recordsTotal']=$db->numRow($list_qry);
                $hasil['recordsFiltered']=$db->numRow($list_qry);
        	    $hasil['data']=$ListData;
                
                echo json_encode($hasil);exit;
            
            break;
        }
        
        
       
}
 public function work($partner_id,$aksi){     
    global $dcistem;
		$db = $dcistem->getOption("framework/db");
		date_default_timezone_set("Asia/Jakarta");
		$master=new Master_Ref_Model();
        $login=new Adm_Login_Model();
        $member = new List_Members_Model();
        $referensi	= $_SESSION["referensi"];
        $current_level=$_SESSION["framework"]['user_level'];
        $sqlin  = "";
        $judul="Riwayat Pekerjaan";
        switch($aksi){
            case "add":
                
                $validasi=$this->validasiform(3,true);
        		$Final=false;
                if(count($validasi['arrayerror'])==0){
                    $TglSkrg		=date("Y-m-d H:i:s");
            	    $tgl_skrg_val   =$master->scurevaluetable($TglSkrg,"string");
        	        $company	    =trim($_POST['company']);
                    $location		=$_POST['location'];
        	        $bidang_garapan	=trim($_POST['bidang_garapan']);
                    $jabatan		=$_POST['jabatan'];
    	        	
    	        	$start_year		=$_POST['start_year'];
                    $end_year		=$_POST['end_year'];
                    
                    
    		        $partner_id_val	=$master->scurevaluetable($partner_id,"number");
                    $company_val	=$master->scurevaluetable($company,"string");
    		        $location_val=$master->scurevaluetable($location,"string");
    		        $bidang_garapan_val	       =$master->scurevaluetable($bidang_garapan,"string");
                    $jabatan_val	    =$master->scurevaluetable($jabatan);
    		        $start_year_val	   =$master->scurevaluetable($start_year,"string");
                    $end_year_val	   =$master->scurevaluetable($end_year,"string");
                    
                    
                    $cols="partner_id,company,location,jabatan,bidang_garapan,start_year,end_year,created";
            		$values="$partner_id_val,$company_val,$location_val,$jabatan_val,$bidang_garapan_val,
                    $start_year_val,$end_year_val,$tgl_skrg_val";
            		$sqlin ="INSERT INTO  history_working ($cols) VALUES($values);";
                    $rsl_cust=$db->query($sqlin);
            		if(isset($rsl_cust->error) and $rsl_cust->error===true){
            			$msg['success']=false;
            	        $msg['message']="Error edit data ".strtolower($judul).", ".$rsl_cust->query_last_message." ".$sqlin;
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
                $validasi=$this->validasiform(3,true);
        		$Final=false;
                if(count($validasi['arrayerror'])==0){
                    $working_id=$_POST['working_id'];
                    $TglSkrg		=date("Y-m-d H:i:s");
            	    $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
        	        $company	    =trim($_POST['company']);
                    $location		=$_POST['location'];
        	        $bidang_garapan	=trim($_POST['bidang_garapan']);
                    $jabatan		=$_POST['jabatan'];
    	        	
    	        	$start_year		=$_POST['start_year'];
                    $end_year		=$_POST['end_year'];
                    
                    
    		        $partner_id_val	=$master->scurevaluetable($partner_id,"number");
                    $company_val	=$master->scurevaluetable($company,"string");
    		        $location_val=$master->scurevaluetable($location,"string");
    		        $bidang_garapan_val	       =$master->scurevaluetable($bidang_garapan,"string");
                    $jabatan_val	    =$master->scurevaluetable($jabatan);
    		        $start_year_val	   =$master->scurevaluetable($start_year,"string");
                    $end_year_val	   =$master->scurevaluetable($end_year,"string");
                    
                    
                    $cols_and_vals="partner_id=$partner_id_val,company=$company_val,location=$location_val,
                    jabatan=$jabatan_val,bidang_garapan=$bidang_garapan_val,start_year=$start_year_val,
                    end_year=$end_year_val,last_update=$tgl_skrg_val";
            	
            	
            		
            		$sqlin ="UPDATE history_working SET $cols_and_vals WHERE id=".$working_id.";";
                    $rsl_cust=$db->query($sqlin);
            		if(isset($rsl_cust->error) and $rsl_cust->error===true){
            			$msg['success']=false;
            	        $msg['message']="Error edit ".strtolower($judul).", ".$rsl_cust->query_last_message." ".$sqlin;
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
                $working_id=$_POST['child_id'];
                if(trim($working_id)<>"")
                {
        	        $Nama=$_POST['nama'];
        	        $sqlin="DELETE FROM history_working WHERE id=".$working_id.";";
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
                $tpl  = new View("form_working");
               
                $working_id=isset($_GET['working_id'])?$_GET['working_id']:"";
                //print_r($_POST);
                if(trim($working_id)<>""){
                   $detail=$db->select("id,partner_id,company,jabatan,bidang_garapan,start_year,end_year,
                location,last_update,created","history_working")->where("id=$working_id")->get(0);
                    //echo "cek";
                    //echo "<pre>";print_r($detail);"</pre>";
                    $tpl->detail = $detail;
                  
                } 
      	    
                $tpl->content = $tpl;
                $tpl->render(); 
            break;
            case "listdata":
                $punya_akses=false;
                $hak_akses= $this->access_step[$current_level->Unit];
                $profil= $member->getBiodataCalon($member_id);
                //echo "<pre>";print_r($hak_akses);echo "</pre>";exit;
               
                if($current_level->LevelID=="administrator"){
                    $punya_akses=true;
                }else{
                    if(in_array($profil->reg_step,$hak_akses['step'])){                
                        $punya_akses=true;
                    }
                }
                $draw=$_REQUEST['draw'];
                $list_qry=$db->select("id,partner_id,company,jabatan,bidang_garapan,start_year,end_year,
                location,last_update,created","history_working")->where("partner_id=$partner_id")->orderby("start_year asc")->lim();
                $no=1;
                $i=0;
                $ListData=array();
                while($data = $db->fetchObject($list_qry))
                {
                    $ListData[$i]['No']=$no;
                    $ListData[$i]['ID']=$data->id;
                    $ListData[$i]['Company']=$data->company;
                    $ListData[$i]['Jabatan']=$data->jabatan;
                    $ListData[$i]['Garapan']=$data->bidang_garapan;
                    $ListData[$i]['Lokasi']=$data->location;
                    $tahun="";
                    if(trim($data->start_year)<>"" and trim($data->end_year)<>""){
                        $tahun=$data->start_year." - ".$data->end_year;
                    }
                    if(trim($data->start_year)<>"" and trim($data->end_year)==""){
                        $tahun=$data->start_year;
                    }
                    if(trim($data->start_year)=="" and trim($data->end_year)<>""){
                        $tahun=$data->end_year;
                    }
                    $ListData[$i]['Tahun']=$tahun;
                    $ListData[$i]['start_year']=$data->start_year;
                    $ListData[$i]['end_year']=$data->end_year;
                    $tombol = "";
        			$url_working    =url::current("work",$data->partner_id);
                    //$url_del     = url::current("child",$data->tgIDAnggota."/del");
                    
                   	$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-gear\"></i>",$this->page->PageID,"work","title='Edit Data' href=\"".$url_working."\" class=\"btn btn-primary btn-xs btn-edit-working\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModalChild\" role=\"".$data->id."\"");             
        			$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"work","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_working."\" class=\"btn btn-primary btn-xs btn-del-working\" data-target=\"#largeModalChild\" role=\"".$data->id."\"");
                
                    $control=$tombol;  
                    $ListData[$i]['Tombol']=$control;
                    $i++;
                    $no++;
                }
               
               
                $hasil['draw']=$draw;
                $hasil['recordsTotal']=$db->numRow($list_qry);
                $hasil['recordsFiltered']=$db->numRow($list_qry);
        	    $hasil['data']=$ListData;
                
                echo json_encode($hasil);exit;
            
            break;
        }
        
        
       
}
public function jabatan($partner_id,$aksi){     
        global $dcistem;
		$db = $dcistem->getOption("framework/db");
		date_default_timezone_set("Asia/Jakarta");
		$master=new Master_Ref_Model();
        $login=new Adm_Login_Model();
        $member = new List_Members_Model();
        $referensi	= $_SESSION["referensi"];
        $current_level=$_SESSION["framework"]['user_level'];
        $sqlin  = "";
        $judul="Riwayat Jabatan CNI";
        switch($aksi){
            case "add":
                
                $validasi=$this->validasiform(4,true);
        		$Final=false;
                if(count($validasi['arrayerror'])==0){
                    $TglSkrg		=date("Y-m-d H:i:s");
            	    $tgl_skrg_val   =$master->scurevaluetable($TglSkrg,"string");
        	        $jabatan_id	    =trim($_POST['jabatan_id']);
                    $bidang_garapan	=trim($_POST['bidang_garapan']);
                    $tql_mulai		=$_POST['tanggal_mulai'];
                    $tanggal_mulai  = "";
                    if(trim($tql_mulai)<>""){
                        $tm  = explode("/",$tql_mulai);		       
        		        $tanggal_mulai	= $tm[2]."-".$tm[1]."-".$tm[0];                    
    				}
        	        
                    $tgl_akhir		= $_POST['tanggal_akhir'];
                    $tanggal_akhir  = "";
                    if(trim($tgl_akhir)<>""){
                        $ta  = explode("/",$tgl_akhir);	
        		        $tanggal_akhir	= $ta[2]."-".$ta[1]."-".$ta[0];  
                    }
                    $current_job_title	=(isset($_POST['current_job_title']) and $_POST['current_job_title']=='on')?"1":"";
                    $keterangan		       =$_POST['keterangan'];
                    
                    
    		        $partner_id_val	=$master->scurevaluetable($partner_id,"number");
                    $jabatan_id_val	=$master->scurevaluetable($jabatan_id,"number");
                    $bidang_garapan_val	       =$master->scurevaluetable($bidang_garapan,"string");
                    $tanggal_mulai_val	= $master->scurevaluetable($tanggal_mulai,"string");
    		        $tanggal_akhir_val	= $master->scurevaluetable($tanggal_akhir,"string");
    		        
                    $current_job_title_val	    =$master->scurevaluetable($current_job_title);
    		        $start_year_val	   =$master->scurevaluetable($start_year,"string");
                    $keterangan_val	   =$master->scurevaluetable($keterangan,"string");
                    
                    
                    $cols="partner_id,job_title_id,bidang_garapan,tanggal_mulai,tanggal_akhir,current_job_title,
                    keterangan,created";
            		$values="$partner_id_val,$jabatan_id_val,$bidang_garapan_val,$tanggal_mulai_val,$tanggal_akhir_val,
                    $current_job_title_val,$keterangan_val,$tgl_skrg_val";
            		$sqlin ="INSERT INTO  history_job_title ($cols) VALUES($values);";
                    $rsl_cust=$db->query($sqlin);
            		if(isset($rsl_cust->error) and $rsl_cust->error===true){
            			$msg['success']=false;
            	        $msg['message']="Error edit data ".strtolower($judul).", ".$rsl_cust->query_last_message." ".$sqlin;
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
                $validasi=$this->validasiform(4,true);
        		$Final=false;
                if(count($validasi['arrayerror'])==0){
                    $history_jabatan_id=$_POST['history_jabatan_id'];
                    $TglSkrg		=date("Y-m-d H:i:s");
            	    $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
        	        $jabatan_id	    =trim($_POST['jabatan_id']);
                    $bidang_garapan	=trim($_POST['bidang_garapan']);
                    $tql_mulai		=$_POST['tanggal_mulai'];
                    $tanggal_mulai  = "";
                    if(trim($tql_mulai)<>""){
                        $tm  = explode("/",$tql_mulai);		       
        		        $tanggal_mulai	= $tm[2]."-".$tm[1]."-".$tm[0];                    
    				}
        	        
                    $tgl_akhir		= $_POST['tanggal_akhir'];
                    $tanggal_akhir  = "";
                    if(trim($tgl_akhir)<>""){
                        $ta  = explode("/",$tgl_akhir);	
        		        $tanggal_akhir	= $ta[2]."-".$ta[1]."-".$ta[0];  
                    }
                    $current_job_title	=(isset($_POST['current_job_title']) and $_POST['current_job_title']=='on')?"1":"";
                    $keterangan		       =$_POST['keterangan'];
                    
                    
    		        $partner_id_val	=$master->scurevaluetable($partner_id,"number");
                    $jabatan_id_val	=$master->scurevaluetable($jabatan_id,"number");
                    $bidang_garapan_val	       =$master->scurevaluetable($bidang_garapan,"string");
                    $tanggal_mulai_val	= $master->scurevaluetable($tanggal_mulai,"string");
    		        $tanggal_akhir_val	= $master->scurevaluetable($tanggal_akhir,"string");
    		        
                    $current_job_title_val	    =$master->scurevaluetable($current_job_title);
    		        $start_year_val	   =$master->scurevaluetable($start_year,"string");
                    $keterangan_val	   =$master->scurevaluetable($keterangan,"string");
                    
                    
                    $cols_and_vals="partner_id=$partner_id_val,job_title_id=$jabatan_id_val,bidang_garapan=$bidang_garapan_val,
                    tanggal_mulai=$tanggal_mulai_val,tanggal_akhir=$tanggal_akhir_val,
                    current_job_title=$current_job_title_val,keterangan=$keterangan_val,last_update=$tgl_skrg_val";
            		
            	
            		
            		$sqlin ="UPDATE history_job_title SET $cols_and_vals WHERE id=".$history_jabatan_id.";";
                    $rsl_cust=$db->query($sqlin);
            		if(isset($rsl_cust->error) and $rsl_cust->error===true){
            			$msg['success']=false;
            	        $msg['message']="Error edit ".strtolower($judul).", ".$rsl_cust->query_last_message." ".$sqlin;
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
                $jabatan_id=$_POST['child_id'];
                if(trim($jabatan_id)<>"")
                {
        	        $Nama=$_POST['nama'];
        	        $sqlin="DELETE FROM history_job_title WHERE id=".$jabatan_id.";";
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
                $tpl  = new View("form_history_jobtitle");
               
                $jabatan_id=isset($_GET['jabatan_id'])?$_GET['jabatan_id']:"";
                //print_r($_POST);
                if(trim($jabatan_id)<>""){
                   $detail=$db->select("hj.id,hj.partner_id,job_title_id,jt.name,bidang_garapan,
                 (CASE WHEN ifnull(tanggal_mulai,'')='' or REPLACE(tanggal_mulai,'-','')='00000000' THEN '' ELSE DATE_FORMAT(tanggal_mulai,'%d/%m/%Y') END) tanggal_mulai,
                 (CASE WHEN ifnull(tanggal_akhir,'')='' or REPLACE(tanggal_akhir,'-','')='00000000' THEN '' ELSE DATE_FORMAT(tanggal_akhir,'%d/%m/%Y') END) tanggal_akhir,
                 current_job_title,keterangan","history_job_title hj
                 inner join job_title jt on jt.id=hj.job_title_id")->where("hj.id=$jabatan_id")->get(0);
                    //echo "cek";
                    //echo "<pre>";print_r($detail);"</pre>";
                    $tpl->detail = $detail;
                  
                } 
      	        $tpl->url_jsonData		= url::current("jsonData");
                $tpl->content = $tpl;
                $tpl->render(); 
            break;
            case "listdata":
               
                $draw=$_REQUEST['draw'];
                $list_qry=$db->select("hj.id,hj.partner_id,job_title_id,jt.name,bidang_garapan,
                 (CASE WHEN ifnull(tanggal_mulai,'')='' or REPLACE(tanggal_mulai,'-','')='00000000' THEN '' ELSE DATE_FORMAT(tanggal_mulai,'%d/%m/%Y') END) tanggal_mulai,
                 (CASE WHEN ifnull(tanggal_akhir,'')='' or REPLACE(tanggal_akhir,'-','')='00000000' THEN '' ELSE DATE_FORMAT(tanggal_akhir,'%d/%m/%Y') END) tanggal_akhir,
                 current_job_title,keterangan","history_job_title hj
                 inner join job_title jt on jt.id=hj.job_title_id")->where("partner_id=$partner_id")->orderby("tanggal_mulai asc")->lim();
                $no=1;
                $i=0;
                $ListData=array();
                while($data = $db->fetchObject($list_qry))
                {
                    $ListData[$i]['No']=$no;
                    $ListData[$i]['ID']=$data->id;
                    $ListData[$i]['Jabatan']=$data->name;
                    $ListData[$i]['BidangGarapan']=$data->bidang_garapan;
                    $ListData[$i]['MulaiBerlaku']=$data->tanggal_mulai;
                    $ListData[$i]['AkhirBerlaku']=$data->tanggal_akhir;
                    $ListData[$i]['SedangMenjabat']=$data->current_job_title;
                    $ListData[$i]['Keterangan']=$data->keterangan;
                    $tahun="";
                    if(trim($data->start_year)<>"" and trim($data->end_year)<>""){
                        $tahun=$data->start_year." - ".$data->end_year;
                    }
                    if(trim($data->start_year)<>"" and trim($data->end_year)==""){
                        $tahun=$data->start_year;
                    }
                    if(trim($data->start_year)=="" and trim($data->end_year)<>""){
                        $tahun=$data->end_year;
                    }
                    $ListData[$i]['Tahun']=$tahun;
                    $ListData[$i]['start_year']=$data->start_year;
                    $ListData[$i]['end_year']=$data->end_year;
                    $tombol = "";
        			$url_jabatan    =url::current("jabatan",$data->partner_id);
                    //$url_del     = url::current("child",$data->tgIDAnggota."/del");
                    
                   	$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-gear\"></i>",$this->page->PageID,"jabatan","title='Edit Data' href=\"".$url_jabatan."\" class=\"btn btn-primary btn-xs btn-edit-jabatan\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModalChild\" role=\"".$data->id."\"");             
        			$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"jabatan","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_jabatan."\" class=\"btn btn-primary btn-xs btn-del-jabatan\" data-target=\"#largeModalChild\" role=\"".$data->id."\"");
                
                    $control=$tombol;  
                    $ListData[$i]['Tombol']=$control;
                    $i++;
                    $no++;
                }
               
               
                $hasil['draw']=$draw;
                $hasil['recordsTotal']=$db->numRow($list_qry);
                $hasil['recordsFiltered']=$db->numRow($list_qry);
        	    $hasil['data']=$ListData;
                
                echo json_encode($hasil);exit;
            
            break;
        }
        
        
       
}
 public function validasiform($step=1,$child=false) 
    {
        global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $msg=array();
      	$pesan=array();
        $msg=array();
        $msj="";
        
        
        switch($step){
            case 1:
                if(trim($_POST['nama'])==''){
                    $pesan["nama"]="Nama  harus diisi!";   
                    $msg[]="Nama harus diisi!";
                }
                if(trim($_POST['nik'])==''){
                    $pesan["nik"]="NIK harus diisi!";   
                    $msg[]="NIK harus diisi!";
                }
                if(trim($_POST['no_kk'])==''){
                    $pesan["no_kk"]="No. KK harus diisi!";   
                    $msg[]="No. KK harus diisi!";
                }
               if(trim($_POST['tempat_lahir'])==''){
                    $pesan["tempat_lahir"]="Tempat lahir harus diisi!";   
                    $msg[]="Tempat lahir harus diisi!";
                }
                if(trim($_POST['tahun_lahir'])==''){
                    $pesan["tahun_lahir"]="Tahun lahir harus diisi";   
                    $msg[]="Tahun lahir harus diisi";
                }
                if(trim($_POST['bulan_lahir'])==''){
                    $pesan["bulan_lahir"]="Bulan lahir harus diisi";   
                    $msg[]="Bulan lahir harus diisi";
                }
                if(trim($_POST['tanggal_lahir'])==''){
                    $pesan["tanggal_lahir"]="Tanggal lahir harus diisi";   
                    $msg[]="Tanggal lahir harus diisi";
                }
                if(trim($_POST['gender'])==''){
                    $pesan["genderl"]="Jenis kelamin harus diisi";   
                    $msg[]="Jenis kelamin harus diisi";
                }
                if(trim($_POST['status_pernikahan'])==''){
                    $pesan["status_pernikahan"]="Status pernikahan harus diisi";   
                    $msg[]="Status pernikahan harus diisi";
                }
               /* if(trim($_POST['hp'])==''){
                    $pesan["hp"]="Minimal mengisi satu No. HP";   
                    $msg[]="Minimal mengisi satu No. HP";
                }*/
                if(trim($_POST['kota'])==''){
                    $pesan["kota"]="Alamat Kota harus diisi";   
                    $msg[]="Alamat Kota harus diisi";
                }
                /*if(trim($_POST['kecamatan'])==''){
                    $pesan["kecamatan"]="Alamat kecamatan harus diisi";   
                    $msg[]="Alamat kecamatan harus diisi";
                }
                if(trim($_POST['alamat'])==''){
                    $pesan["alamat"]="Alamat harus diisi";   
                    $msg[]="Alamat harus diisi";
                }*/
               
                
            break;
            case 2:
                if($child==true){
                    if(trim($_POST['jenjang'])==''){
                        $pesan["jenjang"]="jenjang harus diisi!";   
                        $msg[]="jenjang harus diisi!";
                    }
                    if(trim($_POST['institusi'])==''){
                        $pesan["institusi"]="institusi  harus diisi!";   
                        $msg[]="institusi harus diisi!";
                    }
                   /* if(trim($_POST['prodi'])==''){
                        $pesan["prodi"]="prodi harus diisi";   
                        $msg[]="prodi harus diisi";
                    }*/
                    if(trim($_POST['lokasi'])==''){
                        $pesan["lokasi"]="lokasi harus diisi";   
                        $msg[]="lokasi harus diisi";
                    }
                   
                }
                
            break;
        	case 3:
                if($child==true){
                    if(trim($_POST['company'])==''){
                        $pesan["company"]="Instituasi/Perusahaan harus diisi";   
                        $msg[]="Instituasi/Perusahaan harus diisi";
                    }
                    if(trim($_POST['location'])==''){
                        $pesan["location"]="location harus diisi";   
                        $msg[]="location harus diisi";
                    }
                    if(trim($_POST['bidang_garapan'])==''){
                        $pesan["bidang_garapan"]="Bidang garapan harus diisi";   
                        $msg[]="Bidang garapan harus diisi";
                    }
                }
            break;
            case 4:
                if($child==true){
                    if(trim($_POST['jabatan_id'])==''){
                        $pesan["jabatan"]="Jabatan harus diisi";   
                        $msg[]="Jabatan harus diisi";
                    }
                }
                if(trim($_POST['tanggal_mulai'])<>""){
    		        if((strlen(trim($_POST['tanggal_mulai']))<>10) or  (substr_count(trim($_POST['tanggal_mulai']),"/")<>2)){
    		            $pesan["tanggal_mulai"]="Terjadi kesalahan format Tanggal mulai";   
    		            $msg[]="Terjadi kesalahan format Tanggal mulai";
    		        }
    	        }
                if(trim($_POST['tanggal_akhir'])<>""){
    		        if((strlen(trim($_POST['tanggal_akhir']))<>10) or  (substr_count(trim($_POST['tanggal_akhir']),"/")<>2)){
    		            $pesan["tanggal_akhir"]="Terjadi kesalahan format Tanggal akhir";   
    		            $msg[]="Terjadi kesalahan format Tanggal akhir";
    		        }
    	        }
               
            break;
            
            case 5:
                if(trim($_POST['periode_aktif'])==''){
                    $pesan["periode_aktif0"]="Periode mulai aktif harus ditentukan";   
                    $msg[]="Periode mulai aktif harus ditentukan";
                }
                if(trim($_POST['no_anggota'])==''){
                    $pesan["no_anggota"]="No Anggota harus diisi";   
                    $msg[]="No Anggota harus diisi";
                }else{
                    $cek_nomor=$db->select("ID_ANGGOTA","anggota")->where("C_ANGGOTA='".trim($_POST['no_anggota'])."' and ID_ANGGOTA<>".$_POST['id']."")->get();
                    if(!empty($cek_nomor)){
                        $pesan["no_anggota"]="No Anggota sudah digunakan";   
                        $msg[]="No Anggota sudah digunakan";
                    }
                }
                if(!isset($_POST['konfirmasi'])){
                    $pesan["Pernyataan"]="Harus memberikan tanda centang sebagai bentuk pernyataan Anda";   
                    $msg[]="Harus memberikan tanda centang sebagai bentuk pernyataan Anda";
                }
            break;
            case 6:
                if(trim($_POST['petugas'])==''){
                    $pesan["petugas"]="Petugas survey harus diisi";   
                    $msg[]="Petugas survey harus diisi";
                }
                if(trim($_POST['alamat_kandang'])==''){
                    $pesan["alamat_kandang"]="Alamat kandang harus diisi";   
                    $msg[]="Alamat kandang harus diisi";
                }
                if(!isset($_POST['konfirmasi'])){
                    $pesan["Pernyataan"]="Harus memberikan tanda centang sebagai bentuk pernyataan Anda";   
                    $msg[]="Harus memberikan tanda centang sebagai bentuk pernyataan Anda";
                }
            break;
            case 7:
               
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
         $shipment    =new Shipment_Ore_Model();
        date_default_timezone_set("Asia/Jakarta");
        $detail=$shipment->getShipment($id);
         //echo "<pre>"; print_r($detail);echo "</pre>";
        $tpl->detail=$detail;
       // $tpl->url_base=url::base();
        $tpl->url_cetak      = url::current("cetak");
        $this->tpl->content_title = "Detail Shipment";
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
  public function import($proses="") {
		global $dcistem;
		$db = $dcistem->getOption("framework/db");
		date_default_timezone_set("Asia/Jakarta");
		$master=new Master_Ref_Model();
		$referensi=$master->referensi_session();
        $periode=new List_Periode_Model();
		$history	=new App_History_Model();
		//$tpl  		= new View("konfirmasi_upload");
		$msg=array();
		$new_id="";
		$Pesan	= "";
		if(trim($proses)=="upload")
		{
		//	echo "<pre>";print_r($_FILES);echo "</pre>";exit;
	        //$tpl  = new View("hasil_uploadsmup");
	        $master=new Master_Ref_Model();
	       // $modxls = new Adm_Excel_Model();
	        $ref_id=$_SESSION["framework"]["ref_id"];
	       //$username=$_SESSION["framework"]['current_user']->Username;
	        $Operator=$_SESSION["framework"]['current_user']->Username;
	   
	        $msj="";
	        $msg=array();
	    	$pathfile		=$_FILES['file_excel']["tmp_name"];
	        $AwalRowData	= isset($_POST['awal_row_data'])?$_POST['awal_row_data']:2;
	        $login_as		=	$_SESSION['framework']['login_as'];      	
	        $ref_id			=$_SESSION["framework"]["ref_id"] ;
	        $size 			= $_FILES['file_excel']['size'];
		  
	        $extension 		= pathinfo($_FILES['file_excel']['name'],PATHINFO_EXTENSION);   
	        $psn_error="";
	        if($size==0){
	        	$psn_error="Tidak ada file yang diupload";
	        }else{
		         if(!in_array($extension,array("xls","xlsx"))){
		        	$psn_error="Format file harus xls atau xlsx";
		        }	
	        }
	       
	       
       		
	        $Tanggal		=date("YmdHis");
	        $nmfile 		= "files/format_upload_production".$ref_id."_".$Tanggal.".".$extension;    
			if(trim($psn_error)==""){
		        if(move_uploaded_file($_FILES['file_excel']["tmp_name"],$nmfile))
		        {
	        
	            require_once 'plugins/PHPExcel/Classes/PHPExcel.php';
	                
	            if ($extension == 'xls') $xlsReader = new PHPExcel_Reader_Excel5();
	            else	$xlsReader = new PHPExcel_Reader_Excel2007();
	    
	            $objPHPExcel = $xlsReader->load($nmfile);
	            
	            //$sheets = $objPHPExcel->getActiveSheet(2)->toArray(null,true,true,true);  
	           //echo "<pre>";print_r($sheets);echo "</pre>";
               $msg_err="";
               $listhasilch=array();
                /*if(trim($_POST['jml_sheet'])==""){
                    $msg_err="Isi jumlah sheet, paling tidak satu";
                }*/
                if(trim($msg_err)==""){
                    
                        
                        $sheets =$objPHPExcel->setActiveSheetIndex(0)->toArray(null,true,true,true);
                        $AwalRowData=trim($AwalRowData)==""?2:(int)$AwalRowData-1;
        	            $sheetData=array_slice($sheets,$AwalRowData,8);
                        //echo "<pre>";print_r($sheetData);echo "</pre>";
                        
                        
                        $week=(int)$sheets[1]['C'];
                        
                        //echo  "key skrg :".$key_sebelum." - Key sblm :".ord($key_sebelum)."<br />";
                        if (!empty($sheetData)) 
                        {
                          $TglSkrg		=date("Y-m-d H:i:s");
                          $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
                           while($data=current($sheetData))
	                       {
 	                            $employee_id     =$data['B'];
                                $employee_id_val		=$master->scurevaluetable($employee_id);
                                
                                $name           =$data['C'];
                                $name_val		=$master->scurevaluetable($name);
                                $nik            =$data['D'];
                                $nik_val		=$master->scurevaluetable($nik);
                                $partner_id     ="";
                                if(trim($nik)<>""){
                                    
                                    $cek_partner=$db->select("id","partner")->where("nik='".$nik."'")->get(0);
                                    $partner_id=$cek_partner->id;
                                }
                                
                                
                                $no_kk          =$data['E'];
                                $no_kk_val		=$master->scurevaluetable($no_kk);
                                $alias          =$data['F'];
                                $alias_val		=$master->scurevaluetable($alias);
                                
                                $gelar_depan        =$data['G'];
                                $gelar_depan_val	=$master->scurevaluetable($gelar_depan);
                                $gelar_belakang     =$data['H'];
                                $gelar_belakang_val	=$master->scurevaluetable($gelar_belakang);                                    
                              
                                $tempat_lahir   =$data['I'];
                                $tempat_lahir_val	=$master->scurevaluetable($tempat_lahir); 
                                
                                $date_of_birth   =$data['J'];
                                $date_of_birth_val	=$master->scurevaluetable($date_of_birth);   
                                $agama           =$data['K'];
                                $agama_id="";
                                if(trim($agama)<>""){
                                    if(trim(strtolower($agama))=="katolik" or trim(strtolower($agama))=="katholik"){
                                        $agama="Katholik";
                                    }
                                    if(trim(strtolower($agama))=="budha" or trim(strtolower($agama))=="buda"){
                                        $agama="Budha";
                                    }
                                    $filter_agama="LOWER(agamaNama)='".strtolower($agama)."'";
                                    $agm=$db->select("agamaKode,agamaNama","tbragama")->where($filter_agama)->get(0);
                                    $agama_id=$agm->agamaKode;
                                }
                                $agama_id_val		=$master->scurevaluetable($agama_id,"number");                                    
                                
                                $gender          =$data['L'];
                                $kode_gender="";
                                if(trim($gender)<>""){
                                    if(trim(strtolower($gender))=="lakilaki" or trim(strtolower($gender))=="laki-laki" or trim(strtolower($gender))=="pria"){
                                        $kode_gender="L";
                                    }
                                    if(trim(strtolower($gender))=="perempuan" or trim(strtolower($gender))=="wanita"){
                                        $kode_gender="P";
                                    }
                                }
                                $kode_gender_val	=$master->scurevaluetable($kode_gender);  
                                
                                $hp             =$data['M'];
                                $hp_val	=$master->scurevaluetable($hp);  
                                $prov           =$data['N'];
                                $kab            =$data['O'];
                                $kec            =$data['P'];
                                $desa           =$data['Q'];
                                $alamat         =$data['R'];
                                if(trim($desa)<>""){
                                    $alamat=trim($alamat)<>""?$alamat." ".$desa:$desa;
                                }
                                if(trim($kec)<>""){
                                    $alamat=trim($alamat)<>""?$alamat." ".$kec:$kec;
                                }
                                if(trim($kab)<>""){
                                    $alamat=trim($alamat)<>""?$alamat." ".$kab:$kab;
                                }
                                if(trim($prov)<>""){
                                    $alamat=trim($alamat)<>""?$alamat." ".$prov:$prov;
                                }
                                $alamat_val	=$master->scurevaluetable($alamat);  
                                $email          =$data['S'];
                                $email_val	=$master->scurevaluetable($email);  
                                
                                $date_of_join   =$data['T'];
                                $date_of_join_val		=$master->scurevaluetable($date_of_join);
                                
                                $jenis_kontrak          =$data['U'];
                                //echo $jenis_kontrak;
                                $jenis_kontrak_id="";
                                if(trim($jenis_kontrak)<>""){
                                    if(trim(strtolower($jenis_kontrak))=="permanen" or trim(strtolower($jenis_kontrak))=="karyawan tetap"){
                                        $jenis_kontrak_id="1";
                                    }
                                    if(trim(strtolower($jenis_kontrak))=="kontrak" or trim(strtolower($jenis_kontrak))=="karyawan tidak tetap"){
                                        $jenis_kontrak_id="2";
                                    }
                                }
                                
                                $jenis_kontrak_id_val		=$master->scurevaluetable($jenis_kontrak_id,"number"); 
                                // create partner 
                               // echo $jenis_kontrak_id_val ."<br>";
                                $filter_cek="no_induk='".$employee_id."'";
                                if(trim($partner_id)<>""){
                                    $filter_cek="(".$filter_cek." or partner_id=".$partner_id.")";
                                }
                                $cek=$db->select("id,partner_id","employees")->where($filter_cek)->get(0);
                                //print_r($cek);
                                //echo "cek";
                                if(empty($cek)){
                                    $sqlin="";
                                    if(trim($partner_id)==""){
                                        $cols="nik,no_kk,name,alias,gelar_depan,gelar_belakang,tempat_lahir,
                                        tanggal_lahir,agama,gender,phone,email,alamat";
                                   	    $values="$nik_val,$no_kk_val,$name_val,$alias_val,$gelar_depan_val,$gelar_belakang_val,$tempat_lahir_val,
                                           $date_of_birth_val,$agama_id_val,$kode_gender_val,$hp_val,$email_val,$alamat_val";
                                        $sqlin="INSERT INTO partner ($cols) VALUES ($values);";
                                    }else{
                                        $cols_and_vals="nik=$nik_val,no_kk=$no_kk_val,name=$name_val,alias=$alias_val,gelar_depan=$gelar_depan_val,
                                        gelar_belakang=$gelar_belakang_val,tempat_lahir=$tempat_lahir_val,
                                        tanggal_lahir=$date_of_birth_val,agama=$agama_id_val,gender=$kode_gender_val,
                                        phone=$hp_val,email=$email_val,alamat=$alamat_val";
                                   	   
                                        $sqlin="UPDATE partner SET $cols_and_vals WHERE id=$partner_id;";
                                        
                                    } 
                                    $rsl_cust=$db->query($sqlin);
                    				if(isset($rsl_cust->error) and $rsl_cust->error===true){
                    					$msg['success']=false;
                    			        $msg['message']="Error insert partner, ".$rsl_cust->query_last_message;
                    				}else{
                    				    if(trim($partner_id)==""){
                        				    $last       =$db->query("SELECT LAST_INSERT_ID() as new_id;");
                                            $new        =$db->fetchArray($last);
                                            $partner_id =$new['new_id'];
                                        }
                                        $cols2="partner_id,no_induk,unit,tanggal_mulai_kerja,job_title_id,
                                        jenis_kontrak_id,inactive";
                                   	    $values2="$partner_id,$employee_id_val,null,$date_of_join_val,null,
                                           $jenis_kontrak_id_val,1";
                                        $sqlin2="INSERT INTO employees ($cols2) VALUES ($values2);";
                                        $db->query($sqlin2);
                    	            }
                                         
                                }else{
                                    $partner_id =$cek->partner_id;
                                    $cols_and_vals="nik=$nik_val,no_kk=$no_kk_val,name=$name_val,alias=$alias_val,gelar_depan=$gelar_depan_val,
                                        gelar_belakang=$gelar_belakang_val,tempat_lahir=$tempat_lahir_val,
                                        tanggal_lahir=$date_of_birth_val,agama=$agama_id_val,gender=$kode_gender_val,
                                         phone=$hp_val,email=$email_val,alamat=$alamat_val";
                                    $sqlup="UPDATE partner SET $cols_and_vals WHERE id=$partner_id;";
                                    $rsl_cust=$db->query($sqlup);
                    				if(isset($rsl_cust->error) and $rsl_cust->error===true){
                    					$msg['success']=false;
                    			        $msg['message']="Error update partner, ".$rsl_cust->query_last_message;
                    				}else{			
                    				    $cols_and_vals2="partner_id=$partner_id,no_induk=$employee_id_val,unit=null,
                                        tanggal_mulai_kerja=$date_of_join_val,job_title_id=null,
                                        jenis_kontrak_id=$jenis_kontrak_id_val,inactive=1";
                                   	    
                                        $sqlup2="UPDATE employees SET $cols_and_vals2 WHERE partner_id=$partner_id;";
                                        echo $sqlup2;
                                        $db->query($sqlup2);
                    	            }    
                                    
                                }  
                              
                                next($sheetData);
                            }
                        }//jika data tidak kosong
                        
                        
                         echo "<pre>";print_r($listhasilch);echo "</pre>";
                    
                }else{
                    echo $msg_err;
                }
                   
        
	        } else{
	            $Pesan = "File yang diupload tidak ada";
	        } 
        } else{
            $Pesan = $psn_error;
        }  
        echo $psn_error;
		echo "<pre>";print_r($listhasilch);echo "</pre>";exit;
       
      
         
    }else{
				
		    	$tpl  = new View("import_employees");
		    	
		    	
		        $tpl->msg = $msg;
		    	$tpl->url_import = url::current("import","upload");
		    	$tpl->url_jsonData		= url::current("jsonData");
	        	$tpl->url_comboAjax		=url::current("comboAjax");
	        	$this->tpl->content_title = "Import Karyawan";
		    	$this->tpl->content = $tpl;
				$this->tpl->render();
		  
	    }
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
        if(trim($kategori)=="job_title"){
            $job=new Job_Title_Model();
            $hasil=$job->json($nama,$aVars);
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
        
        $parentcode=$aVars['parentkode'];
        $hasil      = array();
        if(trim($kategori)=="listtps"){
            $tps    = new List_Tps_Model();
            $hasil  =$tps->combo($kategori,$parentcode,$aVars['nilai']);
        }else{
            $wilayah=new Ref_Wilayah_Model();
            $hasil=$wilayah->comboAjax($kategori,$parentcode,$aVars['nilai']);
        }
        
        echo $hasil;
   }
 
}
?>