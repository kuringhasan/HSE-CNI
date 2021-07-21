<?php
 
defined("PANDORA") OR die("No direct access allowed.");

class Ref_Kontraktor_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
		global $dcistem;
		
	}
	

	public function index() {
        global $dcistem;
 
        $tpl  = new View("ref_kontraktor");
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
		// $requestData	= $_REQUEST;

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
		$draw=$_REQUEST['draw'];
		$length=$_REQUEST['length'];
		$start=$_REQUEST['start'];

		// var_dump($start);
		
		$ListData      = array();
		$jml_filtered  = 0;
		$jml_data      = 0;
		$filter=$modelsortir->fromFormcari($keriteria,"and");
		
		$cols=array(0=>"id",
					1=>"name",
					2=>"nik",
					3=>"is_company",
					4=>"is_contractor",
					5=>"code",
					6=>"alias",
					7=>"alamat");
		$order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
		if ($order == " ")
		{
			$order= "name desc";
		}

		

		$list_qry=$db->select("SQL_CALC_FOUND_ROWS a.id, a.*
		","partner a")
		->where($filter)
		->orderBy($order)
		->lim($start,$length)
		;
	   
		$no=$start+1;
		$i=0;

		while($data = $db->fetchObject($list_qry))
		{
			if($i==0){
				$filtered_qry	= $db->query("SELECT FOUND_ROWS() jml_filtered;");
				$filtered_data	= $db->fetchObject($filtered_qry);
				$jml_filtered	= $filtered_data->jml_filtered;
			}
			
			$ListData[$i]['No']=$no;
			$ListData[$i]['name']=$data->name;
			$ListData[$i]['nik']=$data->nik;
			$ListData[$i]['is_company']=$data->is_company;
			$ListData[$i]['is_contractor']=$data->is_contractor;
			$ListData[$i]['code']=$data->code;
			$ListData[$i]['alias']=$data->alias;
			$ListData[$i]['alamat']=$data->alamat;
		   
			$url_proses   = url::base()."insiden/resume?id=$data->id_insiden";
			$url_del      = url::current("del",$data->id);
			$url_edit     =	url::current("edit",$data->id);
			$url_detail   = url::current("detail",$data->id);

			$tombol          = "";
			//$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-pencil\"></i>",$this->page->PageID,"edit","title='Ubah Data' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs\" ");  
			$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-pencil\"></i>",$this->page->PageID,"edit","title='Ubah Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_edit."\" class=\"btn btn-primary btn-xs btn-edit-data\" data-target=\"#largeModal\"");
			$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-file-text-o\"></i>",$this->page->PageID,"detail","title='Detail' data-toggle=\"modal\" data-remote=\"true\" href=\"".$url_detail."\" class=\"btn btn-primary btn-xs btn-detail-data\" data-target=\"#largeModal\" role=\"".$data->id."\"");  
			$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"del","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->id."\"");  

		  
			$control=$tombol;  
			$ListData[$i]['Aksi']=$control;

		   $i++;
		   $no++;
	   }
	   	$hasil['draw']=$draw;
		$hasil['recordsTotal']=$jml_filtered;
		$hasil['recordsFiltered']=$jml_filtered;
		$hasil['data']=$ListData;
		// var_dump($hasil);
		echo json_encode($hasil);exit;
	}

    public function add($proses=""){     
        global $dcistem;
           $db   = $dcistem->getOption("framework/db");
           $master=new Master_Ref_Model();
           $login=new Adm_Login_Model();
           date_default_timezone_set("Asia/Jakarta");
           if(trim($proses)=="save")
           {
            //    echo json_encode("tes");  
               $nik		=trim($_POST['nik']);
               $name	=trim($_POST['name']);
               $is_company	=trim($_POST['is_company']);
               $is_contractor	=trim($_POST['is_contractor']);
               $code	=trim($_POST['code']);
               $no_kk	=trim($_POST['no_kk']);
               $alias	=trim($_POST['alias']);
               $gelar_depan	=trim($_POST['gelar_depan']);
               $gelar_belakang	=trim($_POST['gelar_belakang']);
               $tempat_lahir	=trim($_POST['tempat_lahir']);
               $tempat_lahir_lain	=trim($_POST['tempat_lahir_lain']);
               $tanggal_lahir	=trim($_POST['tanggal_lahir']);
               $agama	=trim($_POST['agama']);
               $gender	=trim($_POST['gender']);
               $kewarganegaraan	=trim($_POST['kewarganegaraan']);
               $golongan_darah	=trim($_POST['golongan_darah']);
               $pJenisTandaPengenal	=trim($_POST['pJenisTandaPengenal']);
               $alamat	=trim($_POST['alamat']);
               $alamat_rt	=trim($_POST['alamat_rt']);
               $alamat_rw	=trim($_POST['alamat_rw']);
               $phone	=trim($_POST['phone']);
               $alamat_kecamatan	=trim($_POST['alamat_kecamatan']);
               $alamat_desa	=trim($_POST['alamat_desa']);
               $alamat_kabupaten	=trim($_POST['alamat_kabupaten']);
               $email	=trim($_POST['email']);
               $kodepos	=trim($_POST['kodepos']);
               $telepon	=trim($_POST['telepon']);
               $npwp	=trim($_POST['npwp']);
               $step	=trim($_POST['step']);
               $last_update	=trim($_POST['last_update']);
               $reg_step	=trim($_POST['reg_step']);
               $reg_date	=trim($_POST['reg_date']);
               $reg_lastupdate	=trim($_POST['reg_lastupdate']);
               $status_pernikahan	=trim($_POST['status_pernikahan']);
               $nama_pasangan	=trim($_POST['nama_pasangan']);
               $created	=trim($_POST['created']);
               $odoo_id	=trim($_POST['odoo_id']);
               $rgb_color	=trim($_POST['rgb_color']);
               $active	=trim($_POST['active']);
				if($is_company!=null){
					$is_company =1;
				}else{
					$is_company =0;
				}

				if($is_contractor!=null){
					$is_contractor =1;
				}else{
					$is_contractor =0;
				}
               

               $validasi=$this->validasiform();   
               if(count($validasi['arrayerror'])==0){
                   $cek=$db->select("nik","partner")->where("nik='".$nik."'")->get(0);
                   if(empty($cek)){
						$TglSkrg=date("Y-m-d H:i:s");
						$sqlin="";
						$nik_val		=$master->scurevaluetable($nik);
						$name_val	=$master->scurevaluetable($name);
						$is_company_val	=  $master->scurevaluetable($is_company);
						$is_contractor_val	=  $master->scurevaluetable($is_contractor);
						$code_val	=  $master->scurevaluetable($code);
						$no_kk_val	=  $master->scurevaluetable($no_kk);
						$alias_val	=  $master->scurevaluetable($alias);
						$gelar_depan_val	=  $master->scurevaluetable($gelar_depan);
						$gelar_belakang_val	=  $master->scurevaluetable($gelar_belakang);
						$tempat_lahir_val	=  $master->scurevaluetable($tempat_lahir);
						$tempat_lahir_lain_val	=  $master->scurevaluetable($tempat_lahir_lain);
						$tanggal_lahir_val	=  $master->scurevaluetable($tanggal_lahir);
						$agama_val	=  $master->scurevaluetable($agama);
						$gender_val	=  $master->scurevaluetable($gender);
						$kewarganegaraan_val	=  $master->scurevaluetable($kewarganegaraan);
						$golongan_darah_val	=  $master->scurevaluetable($golongan_darah);
						$pJenisTandaPengenal_val	=  $master->scurevaluetable($pJenisTandaPengenal);
						$alamat_val	=  $master->scurevaluetable($alamat);
						$alamat_rt_val	=  $master->scurevaluetable($alamat_rt);
						$alamat_rw_val	=  $master->scurevaluetable($alamat_rw);
						$phone_val	=  $master->scurevaluetable($phone);
						$alamat_kecamatan_val	=  $master->scurevaluetable($alamat_kecamatan);
						$alamat_desa_val	=  $master->scurevaluetable($alamat_desa);
						$alamat_kabupaten_val	=  $master->scurevaluetable($alamat_kabupaten);
						$email_val	=  $master->scurevaluetable($email);
						$kodepos_val	=  $master->scurevaluetable($kodepos);
						$telepon_val	=  $master->scurevaluetable($telepon);
						$npwp_val	=  $master->scurevaluetable($npwp);
						$step_val	=  $master->scurevaluetable($step);
						$last_update_val	=  $master->scurevaluetable($last_update);
						$reg_step_val	=  $master->scurevaluetable($reg_step);
						$reg_date_val	=  $master->scurevaluetable($reg_date);
						$reg_lastupdate_val	=  $master->scurevaluetable($reg_lastupdate);
						$status_pernikahan_val	=  $master->scurevaluetable($status_pernikahan);
						$nama_pasangan_val	=  $master->scurevaluetable($nama_pasangan);
						$created_val	=  $master->scurevaluetable($TglSkrg);
						$odoo_id_val	=  $master->scurevaluetable($odoo_id);
						$rgb_color_val	=  $master->scurevaluetable($rgb_color);
						$active_val	=  $master->scurevaluetable($active);
                       
                       $cols="nik,name,is_company,is_contractor,code,no_kk,alias,gelar_depan,gelar_belakang,tempat_lahir,tempat_lahir_lain,tanggal_lahir,agama,gender,kewarganegaraan,golongan_darah,pJenisTandaPengenal,alamat,alamat_rt,alamat_rw,phone,alamat_kecamatan,alamat_desa,alamat_kabupaten,email,kodepos,telepon,npwp,step,last_update,reg_step,reg_date,reg_lastupdate,status_pernikahan,nama_pasangan,created,odoo_id,rgb_color,active";
                       $values="$nik_val,$name_val,$is_company_val,$is_contractor_val,$code_val,$no_kk_val,$alias_val,$gelar_depan_val,$gelar_belakang_val,$tempat_lahir_val,$tempat_lahir_lain_val,$tanggal_lahir_val,$agama_val,$gender_val,$kewarganegaraan_val,$golongan_darah_val,$pJenisTandaPengenal_val,$alamat_val,$alamat_rt_val,$alamat_rw_val,$phone_val,$alamat_kecamatan_val,$alamat_desa_val,$alamat_kabupaten_val,$email_val,$kodepos_val,$telepon_val,$npwp_val,$step_val,$last_update_val,$reg_step_val,$reg_date_val,$reg_lastupdate_val,$status_pernikahan_val,$nama_pasangan_val,$created_val,$odoo_id_val,$rgb_color_val,$active_val";
                       $sqlin="INSERT INTO partner ($cols) VALUES ($values);";
                       
           
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
                            $msg['message']="Data dengan nik $nik sudah ada "; 
                    }
                  
               }else{
                    $msg['success']	=false;
                    $msg['message']	="Terjadi kesalahan pengisian form";
                    $msg['form_error']=$validasi['arrayerror'];
               }
			//    var_dump("");
               echo json_encode($msg);   
		}else{
			
			$tpl  = new View("form_ref_kontraktor");
			
			$tpl->url_add            = url::current("add");
			$tpl->url_jsonData		= url::current("jsonData");
			$tpl->url_comboAjax		= url::current("comboAjax");
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
				$nik		=trim($_POST['nik']);
				$name	=trim($_POST['name']);
				$is_company	=trim($_POST['is_company']);
				$is_contractor	=trim($_POST['is_contractor']);
				$code	=trim($_POST['code']);
				$no_kk	=trim($_POST['no_kk']);
				$alias	=trim($_POST['alias']);
				$gelar_depan	=trim($_POST['gelar_depan']);
				$gelar_belakang	=trim($_POST['gelar_belakang']);
				$tempat_lahir	=trim($_POST['tempat_lahir']);
				$tempat_lahir_lain	=trim($_POST['tempat_lahir_lain']);
				$tanggal_lahir	=trim($_POST['tanggal_lahir']);
				$agama	=trim($_POST['agama']);
				$gender	=trim($_POST['gender']);
				$kewarganegaraan	=trim($_POST['kewarganegaraan']);
				$golongan_darah	=trim($_POST['golongan_darah']);
				$pJenisTandaPengenal	=trim($_POST['pJenisTandaPengenal']);
				$alamat	=trim($_POST['alamat']);
				$alamat_rt	=trim($_POST['alamat_rt']);
				$alamat_rw	=trim($_POST['alamat_rw']);
				$phone	=trim($_POST['phone']);
				$alamat_kecamatan	=trim($_POST['alamat_kecamatan']);
				$alamat_desa	=trim($_POST['alamat_desa']);
				$alamat_kabupaten	=trim($_POST['alamat_kabupaten']);
				$email	=trim($_POST['email']);
				$kodepos	=trim($_POST['kodepos']);
				$telepon	=trim($_POST['telepon']);
				$npwp	=trim($_POST['npwp']);
				$step	=trim($_POST['step']);
				$last_update	=trim($_POST['last_update']);
				$reg_step	=trim($_POST['reg_step']);
				$reg_date	=trim($_POST['reg_date']);
				$reg_lastupdate	=trim($_POST['reg_lastupdate']);
				$status_pernikahan	=trim($_POST['status_pernikahan']);
				$nama_pasangan	=trim($_POST['nama_pasangan']);
				$created	=trim($_POST['created']);
				$odoo_id	=trim($_POST['odoo_id']);
				$rgb_color	=trim($_POST['rgb_color']);
				$active	=trim($_POST['active']);
				if($is_company!=null){
					$is_company =1;
				}else{
					$is_company =0;
				}

				if($is_contractor!=null){
					$is_contractor =1;
				}else{
					$is_contractor =0;
				}

                // $cek=$db->select("propinsiKode","tbrpropinsi")->where("propinsiKode='".$code."'")->get(0);
                // if(empty($cek)){

                    $validasi=$this->validasiform(); 
                    if(count($validasi['arrayerror'])==0){
                        $TglSkrg=date("Y-m-d H:i:s");
						$sqlin="";
						$nik_val		=$master->scurevaluetable($nik);
						$name_val	=   $master->scurevaluetable($name);
						$is_company_val	=  $master->scurevaluetable($is_company);
						$is_contractor_val	=  $master->scurevaluetable($is_contractor);
						$code_val	=  $master->scurevaluetable($code);
						$no_kk_val	=  $master->scurevaluetable($no_kk);
						$alias_val	=  $master->scurevaluetable($alias);
						$gelar_depan_val	=  $master->scurevaluetable($gelar_depan);
						$gelar_belakang_val	=  $master->scurevaluetable($gelar_belakang);
						$tempat_lahir_val	=  $master->scurevaluetable($tempat_lahir);
						$tempat_lahir_lain_val	=  $master->scurevaluetable($tempat_lahir_lain);
						$tanggal_lahir_val	=  $master->scurevaluetable($tanggal_lahir);
						$agama_val	=  $master->scurevaluetable($agama);
						$gender_val	=  $master->scurevaluetable($gender);
						$kewarganegaraan_val	=  $master->scurevaluetable($kewarganegaraan);
						$golongan_darah_val	=  $master->scurevaluetable($golongan_darah);
						$pJenisTandaPengenal_val	=  $master->scurevaluetable($pJenisTandaPengenal);
						$alamat_val	=  $master->scurevaluetable($alamat);
						$alamat_rt_val	=  $master->scurevaluetable($alamat_rt);
						$alamat_rw_val	=  $master->scurevaluetable($alamat_rw);
						$phone_val	=  $master->scurevaluetable($phone);
						$alamat_kecamatan_val	=  $master->scurevaluetable($alamat_kecamatan);
						$alamat_desa_val	=  $master->scurevaluetable($alamat_desa);
						$alamat_kabupaten_val	=  $master->scurevaluetable($alamat_kabupaten);
						$email_val	=  $master->scurevaluetable($email);
						$kodepos_val	=  $master->scurevaluetable($kodepos);
						$telepon_val	=  $master->scurevaluetable($telepon);
						$npwp_val	=  $master->scurevaluetable($npwp);
						$step_val	=  $master->scurevaluetable($step);
						$last_update_val	=  $master->scurevaluetable($TglSkrg);
						$reg_step_val	=  $master->scurevaluetable($reg_step);
						$reg_date_val	=  $master->scurevaluetable($reg_date);
						$reg_lastupdate_val	=  $master->scurevaluetable($reg_lastupdate);
						$status_pernikahan_val	=  $master->scurevaluetable($status_pernikahan);
						$nama_pasangan_val	=  $master->scurevaluetable($nama_pasangan);
						$created_val	=  $master->scurevaluetable($created);
						$odoo_id_val	=  $master->scurevaluetable($odoo_id);
						$rgb_color_val	=  $master->scurevaluetable($rgb_color);
						$active_val	=  $master->scurevaluetable($active);

                        $cols_and_vals="nik=$nik_val,name=$name_val,is_company=$is_company_val,is_contractor=$is_contractor_val,code=$code_val,no_kk=$no_kk_val,alias=$alias_val,gelar_depan=$gelar_depan_val,gelar_belakang=$gelar_belakang_val,
						tempat_lahir=$tempat_lahir_val,tempat_lahir_lain=$tempat_lahir_lain_val,tanggal_lahir=$tanggal_lahir_val,agama=$agama_val,gender=$gender_val,kewarganegaraan=$kewarganegaraan_val,
						golongan_darah=$golongan_darah_val,pJenisTandaPengenal=$pJenisTandaPengenal_val,alamat=$alamat_val,alamat_rt=$alamat_rt_val,alamat_rw=$alamat_rw_val,phone=$phone_val,
						alamat_kecamatan=$alamat_kecamatan_val,alamat_desa=$alamat_desa_val,alamat_kabupaten=$alamat_kabupaten_val,email=$email_val,kodepos=$kodepos_val,telepon=$telepon_val,
						npwp=$npwp_val,step=$step_val,last_update=$last_update_val,reg_step=$reg_step_val,reg_date=$reg_date_val,reg_lastupdate=$reg_lastupdate_val,status_pernikahan=$status_pernikahan_val,
						nama_pasangan=$nama_pasangan_val,created=$created_val,odoo_id=$odoo_id_val,rgb_color=$rgb_color_val,active=$active_val";
                    
                        $sqlin="UPDATE partner SET $cols_and_vals WHERE id=$id;";
                        
                        
            
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
                // }else{
                //     $msg['success']=false;
                //     $msg['message']="Data dengan kode $code sudah ada "; 
                // }
            }
            echo json_encode($msg);   
        }else{
            $partner=new Ref_Kontraktor_Model();

            $tpl  = new View("form_ref_kontraktor");
            $detail=$partner->getDetailRefKontraktor($id);
            $tpl->detail = $detail;
            
            $tpl->url_add           = url::current("add");
            $tpl->url_jsonData		= url::current("jsonData");
            $tpl->url_comboAjax		= url::current("comboAjax");
            $tpl->content = $tpl;
            $tpl->render(); 
        }
    }
    public function detail($id){     
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		$tpl  = new View("detail_ref_kontraktor");
		$insidenMdl    = new Ref_Kontraktor_Model();
		date_default_timezone_set("Asia/Jakarta");
		$detail=$insidenMdl->getDetailRefKontraktor($id);
		// $tpl->list_contractor =Model::getOptionList("partner","id","name","name ASC","ifnull(is_contractor,0)=1"); 
		// $tpl->list_tingkat_keparahan =Model::getOptionList("ref_tingkat_keparahan","kode","keterangan","kode ASC",""); 
		// $tpl->list_jenis_kecelakaan =Model::getOptionList("ref_jenis_kecelakaan_kerja","kode","nama_kecelakaan","kode ASC",""); 

		$tpl->id            		= $detail->id;
		$tpl->nik       			= $detail->nik;
		$tpl->is_company   		    = $detail->is_company;
		$tpl->is_contractor			= $detail->is_contractor;
		$tpl->name				    = $detail->name;
		$tpl->code		            = $detail->code;
		$tpl->no_kk			        = $detail->no_kk;
		$tpl->alias		            = $detail->alias;
		$tpl->gelar_depan		    = $detail->gelar_depan;
		$tpl->gelar_belakang		= $detail->gelar_belakang;
		$tpl->tempat_lahir		    = $detail->tempat_lahir;
		$tpl->tempat_lahir_lain		= $detail->tempat_lahir_lain;
		$tpl->tanggal_lahir		    = $detail->tanggal_lahir;
		$tpl->agama		            = $detail->agama;
		$tpl->gender		        = $detail->gender;
		$tpl->kewarganegaraan		= $detail->kewarganegaraan;
		$tpl->golongan_darah		= $detail->golongan_darah;
		$tpl->pJenisTandaPengenal	= $detail->pJenisTandaPengenal;
		$tpl->alamat		        = $detail->alamat;
		$tpl->alamat_rt		        = $detail->alamat_rt;
		$tpl->alamat_rw		        = $detail->alamat_rw;
		$tpl->phone		            = $detail->phone;
		$tpl->alamat_kecamatan		= $detail->alamat_kecamatan;
		$tpl->alamat_desa		    = $detail->alamat_desa;
		$tpl->alamat_kabupaten		= $detail->alamat_kabupaten;
		$tpl->email		            = $detail->email;
		$tpl->file_foto		        = $detail->file_foto;
		$tpl->kodepos		        = $detail->kodepos;
		$tpl->telepon		        = $detail->telepon;
		$tpl->npwp		            = $detail->npwp;
		$tpl->step		            = $detail->step;
		$tpl->last_update		    = $detail->last_update;
		$tpl->reg_step		        = $detail->reg_step;
		$tpl->reg_lastupdate		= $detail->reg_lastupdate;
		$tpl->status_pernikahan		= $detail->status_pernikahan;
		$tpl->nama_pasangan		    = $detail->nama_pasangan;
		$tpl->created		        = $detail->created;
		$tpl->odoo_id		        = $detail->odoo_id;
		$tpl->rgb_color		        = $detail->rgb_color;
		$tpl->active		        = $detail->active;

		// var_dump($tpl);

		// $foto = $db->query("SELECT * FROM foto_insiden WHERE id_data_insiden=$id");
		// // var_dump($foto);
		// $fto="";
		// while($data = $db->fetchObject($foto))
		// {
		// 	$fto = $fto."<a href='/files/hse/".$data->namafile."' target='_blank'><img src='/files/hse/".$data->namafile."' width='80%'></a><br>";
		// }

		// // var_dump($fto);
		// $tpl->foto	= $fto;

		$this->tpl->content_title = "Detail Ref Kontraktor";
		$tpl->detail = $tpl;
		$tpl->render();   
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
            $sqlin="DELETE FROM  partner  WHERE id=$id;";
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

	public function validasiform() 
    {
        global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $msg=array();
      	$pesan=array();
    	if(trim($_POST['name'])==''){
            $pesan["name"]="Nama harus diisi!";   
            $msg[]="Nama harus diisi!";
        }
		// if(trim($_POST['nik'])==''){
        //     $pesan["nik"]="NIK harus diisi!";   
        //     $msg[]="NIK harus diisi!";
        // }
        
        
		if(count($msg)==1){
            $msj=$msg[0];
        }elseif(count($msg)>1){
            foreach($msg as $key=>$value){
                $msj=$msj."- ".$value."<br>";
            }
        }
        return array("arrayerror"=>$pesan,"msg"=>$msj);
         
    }  

}