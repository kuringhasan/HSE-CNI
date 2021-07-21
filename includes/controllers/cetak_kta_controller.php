<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Cetak_Kta_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        global $dcistem;
        $this->UnitID=$dcistem->getOption("system/web/unit_id");
	}
	
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("cetak_kta");
        $db   = $dcistem->getOption("framework/db");
        $login=new Adm_Login_Model();
        $KodeDosen=$_SESSION["framework"]["ref_id"] ;
        $Username=$_SESSION["framework"]["current_user"]->Username ;
        
        $tpl->ListTPK= Model::getOptionList("mcp","id","name","id ASC","id<>19"); 
        $tpl->ListStatus = Model::getOptionList("anggota_status","status_id","status_name","status_id ASC"); 
        
       	$url_form = url::current("add");     
       	$TombolTambah=$login->privilegeInputForm("link","","btn-tambah-data","<i class=\"fa fa-fw fa-plus-circle\"></i>",$this->page->PageID,"add","title='Tambah Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_form."\" class=\"btn btn-primary btn-xs\" data-target=\"#largeModal\"");
       	$tpl->TombolTambah      = $TombolTambah; 
         $tpl->url_listdata      = url::current("listdata");
         $tpl->url_jsonData		= url::current("jsonData");
         $tpl->url_comboAjax=url::current("comboAjax");
         $tpl->url_refresh      = url::current("refresh");
         $tpl->url_print		= url::current("cetak","123")."/print";
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
        $no_anggota = $requestData['columns'][0]['search']['value'];;
        $nama       = $requestData['columns'][1]['search']['value'];;
        $status      = $requestData['columns'][2]['search']['value'];
        $kelompok     = $requestData['columns'][4]['search']['value'];
        $tpk     = $requestData['columns'][7]['search']['value'];
        $nik      = $requestData['columns'][8]['search']['value'];
        $keriteria[]=" (ifnull(STATUS_AKTIF,0)=1 and ifnull(status,0)=1)";
        if( trim($no_anggota)<>"" ){   //name
            $keriteria[]="C_ANGGOTA like'%".$no_anggota."%' or C_ANGGOTA='".$no_anggota."'";
        }
        if( trim($status)<>"" ){   //name
            $keriteria[]="status=".$status."";
        }
        if( trim($nik)<>"" ){   //name
             $keriteria[]="( NIK like'%".$nik."%' or NIK='".$nik."' )" ;
        }
        if( trim($tpk)<>"" ){   //name
            $keriteria[]="mcp_id=".$tpk."";
        }
        if( trim($kelompok)<>"" ){   //name
            $keriteria[]="ID_KELOMPOK=".$kelompok."";
        }
        if(trim($nama)<>""){
            $keriteria[]="( NAMA like'%".$nama."%' or NAMA='".$nama."' )" ;
        }
      //echo "<pre>";print_r($_REQUEST);echo "</pre>";exit;
        $filter=$modelsortir->fromFormcari($keriteria,"and");
       $draw=$_REQUEST['draw'];
       /*Jumlah baris yang akan ditampilkan pada setiap page*/
		$length=$_REQUEST['length'];

		/*Offset yang akan digunakan untuk memberitahu database
		dari baris mana data yang harus ditampilkan untuk masing masing page
		*/
        
		$start=$_REQUEST['start'];
        
        $cols=array(0=>"ID_ANGGOTA",
                    2=>"C_ANGGOTA",
                    3=>"NAMA",
                    4=>"mcp_id",
                    9=>"ang.NIK");
        $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
            
        $list_qry=$db->select("SQL_CALC_FOUND_ROWS ID_ANGGOTA, C_ANGGOTA as NoAnggota,NAMA,JENIS_KELAMIN,status,
        STATUS_AKTIF, NIK,BARCODE,BARCODE_LOGISTIK,
        date_format(TGL_MASUK,'%d/%m/%Y') as TanggalMasuk, kel.id,mcp_id, m.name as NamaTPK,kel.name as NamaKelompok,
        ang.sync,ang.odoo_id","anggota ang
        inner join kelompok kel on kel.id=ang.ID_KELOMPOK
        inner join mcp m on m.id=kel.mcp_id")
		->where($filter)->orderby($order)->lim($start,$length);
        $no=$start+1;
        $i=0;
        $ListData=array();
        $jml_filtered=0;
        $referensi	= $_SESSION["referensi"];
        while($data = $db->fetchObject($list_qry))
        {
            if($i==0){
                $filtered_qry=$db->query("SELECT FOUND_ROWS() jml_filtered;");
                $filtered_data=$db->fetchObject($filtered_qry);
                //print_r($filtered_data);
                $jml_filtered= $filtered_data->jml_filtered;
            }
            $ListData[$i]['No']=$no;
            $ListData[$i]['ID']=$data->ID_ANGGOTA;
            $ListData[$i]['CheckBox']='<input name="list_anggota[]" class="list_anggota" type="checkbox" value="'.$data->ID_ANGGOTA.'" />';
            
            
             /*$img_file    = "http://111.223.254.6/anggota/".$data->ID_ANGGOTA."/photo";//.$data->ID_ANGGOTA."/photo/";
            $imgData     = "";
            if(file_get_contents($img_file)){
                $img_content = file_get_contents($img_file);
               $imageData = base64_decode($img_content);
              $extension = $matchings['extension'];
               $filename = sprintf("image.%s", $extension);
                //$imgData = base64_encode(file_get_contents($img_file));
                  $imgData = base64_encode(file_get_contents($img_file));
              file_put_contents("foto/members/6994.jpg", $imgData);
            }*/
            $ListData[$i]['Foto']="";//<img src="data:image/gif;base64,{0},'.$imgData.'" />';//$imageData;
            $ListData[$i]['NoAnggota']=$data->NoAnggota;
            $ListData[$i]['Nama']=$data->NAMA;
            $sex="";
            if(trim($data->JENIS_KELAMIN)=="L"){
                $sex="Laki-Laki";
            }elseif(trim($data->JENIS_KELAMIN)=="P"){
                $sex="Perempuan";
            }else{
                $sex="-";
            }
            $ListData[$i]['Gender']=$sex;
            $ListData[$i]['Status']=$referensi['status_keanggotaan'][$data->status];;
            $ListData[$i]['Aktif']=$data->STATUS_AKTIF==1?"Aktif":"Tidak";
            $ListData[$i]['TanggalMasuk']=$data->TanggalMasuk;
            
             $ListData[$i]['TPK']=$data->NamaTPK."<br />".$data->NamaKelompok;
             $ListData[$i]['BarcodeProduksi']=$data->BARCODE;
             $ListData[$i]['BarcodeLogistik']=$data->BARCODE_LOGISTIK;
              $ListData[$i]['OdooID']="<span id=\"odoo_id".$data->ID_ANGGOTA."\">".$data->odoo_id."</span>";
            $syncr=$data->sync==1?"Sudah":"Belum";
            $ListData[$i]['Sync']="<span id=\"sync_label".$data->ID_ANGGOTA."\">".$syncr."</span>";
            $url_del      = url::current("del",$data->ID_ANGGOTA);
			$url_edit =url::current("edit",$data->ID_ANGGOTA);
            $url_sync =url::current("sync",$data->ID_ANGGOTA);
            $url_detail =url::current("detail",$data->ID_ANGGOTA);
            $url_capture		=url::current("capture",$data->ID_ANGGOTA);
			$url_print			=url::current("cetak",$data->ID_ANGGOTA);
            $tombol=$login->privilegeInputForm("link","","","<i class=\"fa fa-user\"></i>",$this->page->PageID,"detail","title='Detail Data' href=\"".$url_detail."\" class=\"btn btn-primary btn-xs btn-detail-data\" target=\"_blank\"  ");
            $tombol=$tombol.$login->privilegeInputForm("link","","","<i class=\"fa  fa-photo\"></i>",$this->page->PageID,"capture","title='Cetak KTM' data-toggle=\"modal\" data-remote=\"true\" href=\"".$url_capture."\" class=\"btn btn-primary btn-xs btn-cetak-ktm\" data-target=\"#largeModal\" role=\"".$data->NAMA."\"");
				
				
            //$tombol=$login->privilegeInputForm("link","","","<i class=\"fa fa-upload\"></i>",$this->page->PageID,"sync","title='Sync Data' href=\"".$url_sync."\" class=\"btn btn-primary btn-xs btn-sync-data\"  role=\"".$data->ID_ANGGOTA."\" onclick=\"return false;\"");
			$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"del","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->kabupatenKode."\"");
			$control=$tombol;  
            $ListData[$i]['Tombol']=$control;
            $i++;
            $no++;
        }
       /* $jml_filtered=$db->select("count(ID_ANGGOTA) as jml_data","anggota ang
        left join kelompok kel on kel.id=ang.ID_KELOMPOK
        left join mcp m on m.id=kel.mcp_id
        left join kelompok_harga kh on kh.id=ang.ID_KELOMPOK_HARGA")->where($filter)->get(0);
        $jml=$db->select("count(ID_ANGGOTA) as jml_data","anggota")->get(0);*/
        $hasil['draw']=$draw;
        $hasil['recordsTotal']=$jml_filtered;//$jml->jml_data;
        $hasil['recordsFiltered']=$jml_filtered;//$jml_filtered->jml_data;
	    $hasil['data']=$ListData;
         //echo $hasil;
        echo json_encode($hasil);exit;
         // echo "<pre>"; print_r($hasil);echo "</pre>";
	}
 public function detail($id){     
     global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $tpl  = new View("detail_anggota");
        $master=new Master_Ref_Model();
        $member=new List_Members_Model();
        date_default_timezone_set("Asia/Jakarta");
        
       	
        
        $detail=$member->getBiodata($id);
       //echo "<pre>"; print_r($detail);echo "</pre>";
       $tpl->detail=$detail;
       $this->tpl->content_title = "Detail Anggota";
		$this->tpl->content = $tpl;
		$this->tpl->render();     
  } 
  public function capture($member_id,$with_webcam=false) {
    	global $dcistem;
        $view="preview_ktm2";
        if($with_webcam==true){
            $view="preview_ktm";
        }
    	$tpl  = new View($view);
    	if(trim($member_id)<>""){
	        
	        $biodata	= new List_Members_Model();
			$detail=$biodata->getBiodata($member_id);
	//	echo "<pre>";print_r($detail);echo "</pre>";
			$tpl->detail=$detail;
            $tpl->url_foto="http://111.223.254.6/anggota/".$member_id."/photo";//.$data->ID_ANGGOTA."/photo/"
		}
    	$tpl->url_upload=url::current("cetak",$member_id);
    	$tpl->url_print=url::current("cetak",$member_id,"print");
    	$tpl->url_foto_tmp=url::base()."tmp/tmp_".$npm.".jpg";
    	$tpl->content = $tpl;
		$tpl->render();
	}
    
   public function cetak($member_id,$aksi="") {
    	global $dcistem;
    	$db       = $dcistem->getOption("framework/db");
    	date_default_timezone_set("Asia/Jakarta");
		$master=new Master_Ref_Model();
		$tgl_skrg	= date("Y-m-d H:i:s");
		$tgl_skrg_val=$master->scurevaluetable($tgl_skrg,"string");
    	if(trim($aksi)=="preview"){
    		$tpl  = new View("preview");
    
	    	$tpl->url_foto_tmp=url::base()."tmp/".$npm.".jpg";
	    	$tpl->content = $tpl;
			$tpl->render();
		}elseif(trim($aksi)=="upload"){
			$tpl  = new View("preview");
			
			ini_set("memory_limit","256M");
			ini_set("gd.jpeg_ignore_warning", 1);
		
			$targ_w = 147;
	        $targ_h = 197;
	        $jpeg_quality = 90;
	        
	        $base_name=basename(url::base()) ;
			$src = $_SERVER['DOCUMENT_ROOT']."/$base_name/tmp/tmp_".$npm.".jpg";
			$nama_file	=$npm.".jpg";
	        $filecrop = $_SERVER['DOCUMENT_ROOT']."/$base_name/tmp/".$nama_file;
	        $image=new Image_Processing_Model();
	        $x1		= $_POST['x'];
	        $y1		= $_POST['y'];
	       	$width	= $_POST['w'];
			$height	= $_POST['h'];
			$x2		= $x1+$width;
			$y2		= $y1+$height;
			//echo "<br>x1:".$x1.", y1:".$y1.", x2:".$x2.", y2:".$y2.", w:".$width." h:".$height;
			$crp=$image->load($src)->crop($x1,$y1,$x2,$y2);
			$crp->save($filecrop,"90");
			//echo $this->settings['host_mahasiswa'];
			$pecah_url	= explode("/",$this->settings['host_mahasiswa']);
			$jml_array=count($pecah_url);
			$dir_mahasiswa= $_SERVER['DOCUMENT_ROOT']."/".$pecah_url[$jml_array-1];
			if (copy($filecrop,$dir_mahasiswa."/foto/".$nama_file)){
				$tpl->url_foto_ktm=$this->settings['host_mahasiswa']."/foto/".$nama_file;
				$mhs	=$db->select("mhsRegNomorIdentitas,mhsRegNPM,mhsRegKelasProdiKode,
				kpNama as KelasProdiNama,mhsRegProdiKode,prodiNama,prodiJenjangKode,jenjangNama,jenjangNamaPendek,
				mhsRegNoPeserta,
				mhsNISN,mhsNama,mhsTempatLahir,mhsTempatLahirKode,mhsTanggalLahir,mhsJenisTandaPengenal,
				mhsKewarganegaraan,mhsJenisKelamin,mhsGolonganDarah,
				mhsAgama",$this->settings['database_siat'].".tbtMahasiswaReg mrg
				inner join ".$this->settings['database_siat'].".tbmMahasiswa mhs on mhs.mhsNomorIdentitas=
				mrg.mhsRegNomorIdentitas
				inner join ".$this->settings['database_siat'].".tbrKelasProdi kp on kp.kpKode=mrg.mhsRegKelasProdiKode
				inner join ".$this->settings['database_siat'].".tbrProdi pro on pro.prodiKode=mrg.mhsRegProdiKode
				inner join ".$this->settings['database_siat'].".tbrJenjangDidik jd on jd.jenjangKode=pro.prodiJenjangKode")
				->where("mhsRegNPM='".$npm."'")->get(0);
				$no_identitas	= $mhs->mhsRegNomorIdentitas;
				$db->query("UPDATE ".$this->settings['database_siat'].".tbmMahasiswa SET mhsFileFoto='".$nama_file."' 
				WHERE mhsNomorIdentitas='".$no_identitas."'");
				
			
				$tpl->profil = $mhs;
			}
			
			
			$tpl->content = $tpl;
			$tpl->render();
        }elseif(trim($aksi)=="print"){  
            $view="print";
            $jenis="produksi";
            if (isset($_GET['kta'])==true and $_GET['kta']=="logistik"){
                $view="print_logistik";
                $jenis="logistik";
            }
            $tpl  = new View($view);
           //echo $_GET['kta']." ".$view; 
            require_once 'plugins/phpqrcode/qrlib.php';
            $tmp_web_dir = url::base()."tmp/";
            $path_tmp=dirname($_SERVER['DOCUMENT_ROOT'].$_SERVER['SCRIPT_NAME'])."/tmp";
            if (!file_exists($path_tmp))
                mkdir($path_tmp);
            
            $list_id= implode(",",$_POST['list_anggota']);
            $filter="ID_ANGGOTA IN ($list_id)";
            $list_qry=$db->select("ID_ANGGOTA,NAMA,C_ANGGOTA,ang.BARCODE,ang.BARCODE_LOGISTIK,mcp_id, m.name as NamaTPK,
            kel.name as NamaKelompok","anggota ang
            left join anggota_kta kta on kta.anggota_id=ang.ID_ANGGOTA and jenis_kta='".$jenis."' 
            left join kelompok kel on kel.id=ang.ID_KELOMPOK
            left join mcp m on m.id=kel.mcp_id")->where($filter)->lim();
            $list_data=array();
            
            $errorCorrectionLevel = 'L';//array('L','M','Q','H')
            $matrixPointSize = 4;
            while($data=$db->fetchObject($list_qry)){
                $rec= new stdClass;
                $rec->ID=$data->ID_ANGGOTA;
                $rec->NoAnggota=$data->C_ANGGOTA;
                $rec->NAMA=$data->NAMA;
                $rec->TPK=$data->NamaTPK;
                $rec->Kelompok=$data->NamaKelompok;
                $filename = $path_tmp.'/qr_code_'.$data->BARCODE.'.png';
               // $tmp_web_dir.basename($filename)
               
                QRcode::png($data->BARCODE, $filename, $errorCorrectionLevel, $matrixPointSize, 1); 
                $rec->Barcode=$tmp_web_dir.basename($filename);
                $filename2 = $path_tmp.'/qr_code_'.$data->BARCODE_LOGISTIK.'.png';
                QRcode::png($data->BARCODE_LOGISTIK, $filename2, $errorCorrectionLevel, $matrixPointSize,0.2); 
                $rec->BARCODE_LOGISTIK=$tmp_web_dir.basename($filename2);
                $rec->url_foto="http://111.223.254.6/anggota/".$data->ID_ANGGOTA."/photo";
                
                $list_data[]=$rec;
            }
            //echo "<pre>";print_r($list_data);echo "</pre>";
            $tpl->list_data = $list_data;
            $tpl->content = $tpl;
		   $tpl->render();
            /*$mhs	=$db->select("mhsRegNPM,mhsRegNoPeserta",$this->settings['database_siat'].".tbtMahasiswaReg")
				->where("mhsRegNPM='".$npm."'")->get(0);
    		$no_peserta	= $mhs->mhsRegNoPeserta;
			$filter_proses="no_peserta='".$no_peserta."' and kode_proses='0305'";
    		$cek_proses=$db->select("no_peserta","tbtPesertaRegistrasiProses")->where($filter_proses)->get(0);
    		$sqlp	="";
    		if(empty($cek_proses)){
    			$cols	="no_peserta,kode_proses,status,tanggal";
				$values	="'".$no_peserta."','0305','1',$tgl_skrg_val";
			    $sqlp	="INSERT INTO tbtPesertaRegistrasiProses ($cols) VALUES($values);";
    		}else{
        		$cols_and_vals	="status='1',tanggal=$tgl_skrg_val";
			    $sqlp	="UPDATE tbtPesertaRegistrasiProses SET $cols_and_vals WHERE $filter_proses";
            }
			$rsl=$db->query($sqlp);
			if(isset($rsl->error) and $rsl->error===true){
				$hasil['success']=false;
                $hasil['pesan']="Error, ".$rsl->query_last_message." ". $sqlp;
			}else{
				$hasil['success']	=true;
				$hasil['pesan']	= "Refresh berhasil";
            }
            echo json_encode($hasil);  
    		exit;*/
    	}else{
		    if (file_exists("tmp/tmp_".$npm.".jpg")) {
				//echo "ada";
				unlink("tmp/tmp_".$npm.".jpg");
			}
		    $nama_file = "tmp_".$npm.'.jpg';
			// kita akan menyimpan gambar di folder 'uploads', pastikan anda telah membuat folder uploads
			$direktori = 'tmp/';
			$target = $direktori.$nama_file;
			 
			move_uploaded_file($_FILES['webcam']['tmp_name'], $target);
		     ob_end_flush();
		}
    }
  public function refresh(){     
     global $dcistem;
     $db   = $dcistem->getOption("framework/db");
     $list_qry=$db->select("SQL_CALC_FOUND_ROWS ang1.ID_ANGGOTA id1,ang1.C_ANGGOTA no_anggota1,ang1.BARCODE_LOGISTIK,
        ang1.NAMA nama1","kpbs_db.anggota ang1
        left join kpbs_db_dev.anggota ang2 on ang2.C_ANGGOTA=ang1.C_ANGGOTA")
		->where("ifnull(ang2.C_ANGGOTA,'')=''")->lim(0,2);
        $jml_data=$db->numRow($list_qry);
        $result=array();
        if($jml_data>0){
            $array_hasil=array();
            $i=0;
            $jml_success=0;
            $html_data="";
            while($data = $db->fetchObject($list_qry))
            {
                $html_data=$html_data.$data->no_anggota1." | ".$data->nama1;
                $array_hasil[$i]['id']=$data->id1;
                $array_hasil[$i]['no_anggota']=$data->no_anggota1;
                $sql_insert="INSERT INTO kpbs_db_dev.anggota (C_ANGGOTA,C_ANGGOTA_LAMA,BARCODE,NAMA,BARCODE_LOGISTIK,
                nama_panggilan,ID_KELOMPOK,ID_KELOMPOK_HARGA,DIAWASI,STATUS_AKTIF,STATUS_NEXT_PERIOD,PATH_FOTO,TGL_MASUK,
                ALAMAT1,ALAMAT2,NO_TELP,NO_HP,LOKASI,FARM_RECORD,TGL_LAHIR,JENIS_KELAMIN,KETERANGAN,I_ENTRY,D_ENTRY,
                NOREK,sample_mq,sample_tpc,jml_sapi_laktasi,jml_sapi_laktasi_kering,jml_sapi_dara_bunting,password,
                access_token,NIK,NoKK,tempat_lahir,agama,alamat_kabupaten,alamat_kecamatan,alamat_desa,alamat_rt,alamat_rw,
                alamat,kodepos,status,email,odoo_id,sync,updated_time,updated_by,reg_step,reg_date,reg_lastupdate) 
                (SELECT C_ANGGOTA,C_ANGGOTA_LAMA,BARCODE,NAMA,BARCODE_LOGISTIK,
                nama_panggilan,ID_KELOMPOK,ID_KELOMPOK_HARGA,DIAWASI,STATUS_AKTIF,STATUS_NEXT_PERIOD,PATH_FOTO,TGL_MASUK,
                ALAMAT1,ALAMAT2,NO_TELP,NO_HP,LOKASI,FARM_RECORD,TGL_LAHIR,JENIS_KELAMIN,KETERANGAN,I_ENTRY,D_ENTRY,
                NOREK,sample_mq,sample_tpc,jml_sapi_laktasi,jml_sapi_laktasi_kering,jml_sapi_dara_bunting,password,
                access_token,NIK,NoKK,tempat_lahir,agama,alamat_kabupaten,alamat_kecamatan,alamat_desa,alamat_rt,alamat_rw,
                alamat,kodepos,status,email,odoo_id,sync,updated_time,updated_by,reg_step,reg_date,reg_lastupdate
                FROM kpbs_db.anggota WHERE C_ANGGOTA='".$data->no_anggota1."');";
                $rslc=$db->query($sql_insert);
                if(isset($rslc->error) and $rslc->error===true){
                    $array_hasil[$i]['success']=false;
                    $html_data=$html_data." | Gagal | ".$rslc->query_last_message;
                    $array_hasil[$i]['message']="Error, ".$rslc->query_last_message." ".$sql_insert;
        	    }else{
        	        $html_data=$html_data." | Success | Anggota berhasil disync";
                    $array_hasil[$i]['success']=true;
                    $array_hasil[$i]['message']="Data anggota sudah disync";
                    $jml_success++;
                }
                $html_data=$html_data."<br />";
                //$array_hasil[$i]['message']=$sql_insert;
                $i++;
                
            }
            $result['jumlah_success']=$jml_success;
            $result['jumlah_gagal']=($jml_data-$jml_success);
            $result['data']=$array_hasil;
            $result['html_data']=$html_data;
        }
    
        echo json_encode($result);  
        exit;
           
  } 
public function edit($kode_lama,$proses=""){     
    global $dcistem;
    $db   = $dcistem->getOption("framework/db");
    $master=new Master_Ref_Model();
    date_default_timezone_set("Asia/Jakarta");
    if(trim($proses)=="save")
	{    
	    if(trim($kode_lama)<>"")
	    { 
	        $prov	=trim($_POST['frmProvinsi']);
            $kode	=trim($_POST['frmKodeKota']);
	        $nama	=trim($_POST['frmNama']);
	        $status	=trim($_POST['frmStatusKota']);
	        $hide=(isset($_POST['frmHide']) and $_POST['frmHide']=="on")?"1":"";
	        
	        $validasi=$this->validasiform("edit",$kode_lama); 
	        if(count($validasi['arrayerror'])==0){
	           $TglSkrg=date("Y-m-d H:i:s");
		        $sqlin="";
		        $prov_val	=$master->scurevaluetable($prov,"string");
		        $kode_val	=$master->scurevaluetable($kode,"string");
		        $nama_val	=$master->scurevaluetable($nama,"string");
		        $status_val	=$master->scurevaluetable($status,"string");
		        $hide_val	=$master->scurevaluetable($hide,"string");
				
   	            $cols_and_values="kabupatenKode=$kode_val,kabupatenPropinsiKode=$prov_val,kabupatenJenis=$status_val,
                   kabupatenJenisSingkat=null,kabupatenNamaSaja=$nama_val,
                kabupatenNama=$nama_val";
			
	            $sqlin="UPDATE tbrkabupaten SET $cols_and_values WHERE kabupatenKode='".$kode_lama."';";
	            $rsl=$db->query($sqlin);
				if(isset($rsl->error) and $rsl->error===true){
			   	 		$msg['success']=false;
	                	$msg['message']="Error, ".$rsl->query_last_message;
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
	    	$tpl  = new View("ref_kota_input");
	    	
	    	 $list_prov=Model::getOptionList("tbrpropinsi","propinsiKode","propinsiNama","propinsiKode ASC",""); 
	    
	        $tpl->ListProv =$list_prov;
	        $wil=new Ref_Wilayah_Model();
			$tpl->detail = $wil->getKota($kode_lama);
	    	$tpl->url_edit = url::current("edit",$kode_lama);
	    	$tpl->url_jsonData		= url::current("jsonData");
        	$tpl->url_comboAjax		=url::current("comboAjax");
	    	$tpl->content = $tpl;
	        $tpl->render(); 
    }
}  
public function del($kode_lama=""){     
     global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        date_default_timezone_set("Asia/Jakarta");
        
         //VALIDASI FORM DULU
         $msg=array();
        if(trim($kode_lama)<>"")
        {
	        $Nama=$_POST['nama'];
	        $sqlin="DELETE FROM   tbrkabupaten  WHERE kabupatenKode='".$kode_lama."';";
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
    	if(trim($_POST['frmProvinsi'])==''){
            $pesan["frmProvinsi"]="Provinsi harus dipilih!";   
            $msg[]="Provinsi harus dipilih!";
        }
	    if ($aksi=="add"){
	        if(trim($_POST['frmKodeKota'])==''){
                $pesan["frmKodeKota"]="Kode Kota/Kabupaten tidak boleh kosong!";   
                $msg[]="Kode Kota/Kabupaten tidak boleh kosong!";
	        }else{
	        	if(strlen(trim($_POST['frmKodeKota']))<>5){
	        		$pesan["frmKodeKota"]="Kode Kota/Kabupaten harus 5 dijit!";   
                	$msg[]="Kode Kota/Kabupaten harus 5 dijit!";
	        	}else{
		        	$cekkode=$db->select("kabupatenKode","tbrkabupaten")->where("kabupatenKode='".trim($_POST['frmKodeKota'])."'")->get();
				    if(count($cekkode)>0){
			    		$pesan['frmKodeKota']="Kode sudah digunakan, silahkan gunakan yang lain!";
			    		$msg[]="Kode sudah digunakan, silahkan gunakan yang lain!";
			    	}
		    	}
	        }
	    }
		if ($aksi=="edit" and trim($kode_lama)<>""){
			if(trim($_POST['frmKodeKota'])<>trim($kode_lama)){
				if(trim($_POST['frmKodeKota'])==''){
	                $pesan["frmKodeKota"]="Kode Kota/Kabupaten tidak boleh kosong!";   
	                $msg[]="Kode Kota/Kabupaten tidak boleh kosong!";
		        }else{
		        	if(strlen(trim($_POST['frmKodeKota']))<>5){
		        		$pesan["frmKodeKota"]="Kode Kota/Kabupaten harus 4 dijit!";   
	                	$msg[]="Kode Kota/Kabupaten harus 5 dijit!";
		        	}else{
			        	$cekkode=$db->select("kabupatenKode","tbrkabupaten")->where("kabupatenKode='".trim($_POST['frmKodeKota'])."'")->get();
					    if(count($cekkode)>0){
				    		$pesan['frmKodeKota']="Kode sudah digunakan, silahkan gunakan yang lain!";
				    		$msg[]="Kode sudah digunakan, silahkan gunakan yang lain!";
				    	}
			    	}
		        }
				
			}
			$cekkode=$db->select("kabupatenKode","tbrkabupaten")->where("kabupatenKode='".$kode_lama."'")->get();
	        if(count($cekkode)==0){
	                $pesan["frmKodeKota"]="Gagal perubahan data, kode lama tidak terindentifikasi!";   
	                $msg[]="Gagal perubahan data, kode lama tidak terindentifikasi!";
	        }
        }
        
        if(trim($_POST['frmNama'])==''){
                $pesan["frmNama"]="Nama Kota/Kabupaten harus diisi!";   
                $msg[]="Nama Kota/Kabupaten harus diisi!";
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
  public function Export() {
	    global $dcistem;
	    $db       = $dcistem->getOption("framework/db");
	    $modelsortir	= new Adm_Sortir_Model();
	    $master=new Master_Ref_Model();
	 	$admin=new Core_Admin_Model();
	 	$nilai_model	= new Adm_Nilai_Model();
	 	date_default_timezone_set("Asia/Jakarta");
	 	
	    set_time_limit(1200);
	    ini_set("memory_limit","256M"); 
	
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
	    	
	       
	
	        $excel->getProperties()->setCreator("SIAT-Stiepar")
	            				   ->setLastModifiedBy("Hasan")
	            				   ->setTitle("Format Nilai")
	            				   ->setSubject("Format Nilai")
	            				   ->setDescription("Format isian nilai mata kuliah")
	            				   ->setKeywords("Nilai");
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
        	$prodi      	= $_POST['crProdi'];
	        $kelas_prodi    = $_POST['crKelasKuliah'];
	        $tahun_akademik = $_POST['crTahunAkademik'];
	        $kode_mk	 	=$_POST['crMataKuliah'];
	        $kode_kelas	 	=$_POST['crKelasKuliah'];
	       // $tpl  = new View("upload_listmahasiswa");
			 $komposisi_nilai= $nilai_model->rumusKomposisiNilai($this->DataUmum->KodeDosen,$kode_mk);
			 $total=$a[6].'4'."+".$a[7].'4'."+".$a[8].'4'."+".$a[9].'4';
	         $excel->setActiveSheetIndex(0)->mergeCells('A2:G2')->setCellValue('A2', 'Format Upload Nilai');
	         $excel->getActiveSheet()->getStyle('A2')->applyFromArray($style_judul);
	         $key=30;
	         $excel->setActiveSheetIndex(0)->mergeCells($a[0].'3:'.$a[0].'4')->setCellValue($a[0].'3', 'No.')
				  	->mergeCells($a[1].'3:'.$a[1].'4')->setCellValue($a[1].'3', 'Nama TPS')
	              	->mergeCells($a[2].'3:'.$a[2].'4')->setCellValue($a[2].'3', 'RT')
	             	->mergeCells($a[3].'3:'.$a[3].'4')->setCellValue($a[3].'3', 'RW');
			 $excel->getActiveSheet()->getStyle($a[0]."3")->applyFromArray($style_header);
				$excel->getActiveSheet()->getColumnDimension($a[0])->setWidth(5);
				$excel->getActiveSheet()->getStyle($a[0]."4")->applyFromArray($style_header2);
			$excel->getActiveSheet()->getStyle($a[1]."3")->applyFromArray($style_header);
				$excel->getActiveSheet()->getColumnDimension($a[1])->setWidth(15);
				$excel->getActiveSheet()->getStyle($a[1]."4")->applyFromArray($style_header2);
			$excel->getActiveSheet()->getStyle($a[2]."3")->applyFromArray($style_header);
				$excel->getActiveSheet()->getColumnDimension($a[2])->setWidth(30);
				$excel->getActiveSheet()->getStyle($a[2]."4")->applyFromArray($style_header2);
			$excel->getActiveSheet()->getStyle($a[3]."3")->applyFromArray($style_header);
				$excel->getActiveSheet()->getColumnDimension($a[3])->setWidth(12);
				$excel->getActiveSheet()->getStyle($a[3]."4")->applyFromArray($style_header2);
		
			
	        $lengkap=false;
	    
	        if(trim($tahun_akademik)<>"" and trim($kode_mk)<>""){
	        	
	     
	        	
	        	$lengkap=true;
	        	$sort	= $_POST['crUrutkan'];
	        	$urutkan="";
	        	switch($sort){
	        		case "npm":
	        			$urutkan="mhsRegNPM asc";
	        		break;
	        		case "nama":
	        			$urutkan="mhsNama asc";
	        		break;
	        	}
	        
	        	$detail	= $this->krs->getMahasiswaKelasByMataKuliahDosen($this->DataUmum->KodeDosen,$tahun_akademik,$kode_mk,$kode_kelas,$urutkan);
	        //'=IF(AND('.$a[10].$i.'>='.$bobot['A']['dari'].','.$a[10].$i.'<='.$bobot['A']['sampai'].'),"A",IF(AND('.$a[10].$i.'>='.$bobot['B']['dari'].','.$a[10].$i.'<'.$bobot['B']['sampai'].'),"B",IF(AND('.$a[10].$i.'>='.$bobot['C']['dari'].','.$a[10].$i.'<'.$bobot['C']['sampai'].'),"C",IF(AND('.$a[10].$i.'>='.$bobot['D']['dari'].','.$a[10].$i.'<'.$bobot['D']['sampai'].'),"D","E"))))'
	        $ref=$master->referensi_session();
	        $bobot=$ref['bobot_nilai'];
		//	echo "<pre>";print_r($ref['bobot_nilai']);echo "</pre>";exit;
	        	$i=5;
	        	if (count($detail['data'])) {
					$no=1;
					$awal=$i;
					while($data = current($detail['data'])) {
					 	 $sigma=$a[6].'4'."*".$a[6].$i."+".$a[7].'4'."*".$a[7].$i."+".$a[8].'4'."*".$a[8].$i."+".$a[9].'4'."*".$a[9].$i;
					 	$rumus=$this->rumusBobotExcel($a[10].$i,$a[8].$i,$a[9].$i,$a[7].$i,$a[6].$i);
			//echo $rumus;exit;
		                	$excel->setActiveSheetIndex(0)
		                      	->setCellValue($a[0].$i, $no)
		                       	->setCellValueExplicit($a[1].$i,$detail['KodeMK'], PHPExcel_Cell_DataType::TYPE_STRING)
		                      	->setCellValueExplicit($a[2].$i,$detail['NamaMK'], PHPExcel_Cell_DataType::TYPE_STRING)
		                      	->setCellValue($a[3].$i,$detail['NamaKelas']);
		                      
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
		                     
							  
		                $i++;   
		                $no++;
						next($detail['data']);
					}
					$akhir=$i-1;
				//	$jml="COUNTIF(".$a[11].$awal.":".$a[11].$akhir.";'A')";
		          
				}
			   
        }
     // exit;
        $i=$i+1;
	//	$excel->setPreCalculateFormulas(true);
         $excel->setActiveSheetIndex(0)
                        ->setCellValue('C'.($i), 'Jumlah Nilai A =')
                        ->setCellValue('C'.($i+1), 'Jumlah Nilai B =')
                        ->setCellValue('C'.($i+2), 'Jumlah Nilai C =')
                        ->setCellValue('C'.($i+3), 'Jumlah Nilai D =')
                        ->setCellValue('C'.($i+4), 'Jumlah Nilai E =')
                        ->setCellValue('C'.($i+5), 'Jumlah Nilai T =')
                        ->setCellValue('C'.($i+6), 'Jumlah Nilai K =')
                        ->setCellValue('D'.($i), '=COUNTIF(L'.$awal.':L'.$akhir.',"A")')
                        ->setCellValue('D'.($i+1), '=COUNTIF(L'.$awal.':L'.$akhir.',"B")')
                        ->setCellValue('D'.($i+2), '=COUNTIF(L'.$awal.':L'.$akhir.',"C")')
                        ->setCellValue('D'.($i+3), '=COUNTIF(L'.$awal.':L'.$akhir.',"D")')
                        ->setCellValue('D'.($i+4), '=COUNTIF(L'.$awal.':L'.$akhir.',"E")')
						->setCellValue('D'.($i+5), '=COUNTIF(L'.$awal.':L'.$akhir.',"T")')
						->setCellValue('D'.($i+6), '=COUNTIF(L'.$awal.':L'.$akhir.',"K")');   
        $excel->getActiveSheet()->setTitle('Format Upload');
        $excel->setActiveSheetIndex(0);
        $sekarang=date("dmY_His");
		$kode_dosen=$this->DataUmum->KodeDosen;
		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="format_upload_nilai_'.$kode_dosen.'_'.$sekarang.'.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $objWriter->save('php://output');
        exit;
	
    
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
        switch($kategori){
            case "listkelompok":
                $tpk=new List_Tpk_Model();
                $hasil=$tpk->comboAjax($kategori,$parentcode,$aVars['nilai']);
            
            break;
            case "wilayah":
                $wilayah=new Ref_Wilayah_Model();
                $hasil=$wilayah->comboAjax($kategori,$parentcode,$aVars['nilai']);
            break;
            
        }
        
        echo $hasil;
   }
 
}
?>