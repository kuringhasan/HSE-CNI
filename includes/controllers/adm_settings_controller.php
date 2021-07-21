<?php
/**
 * @package Admin
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Adm_Settings_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("adm_settings");
        $db   = $dcistem->getOption("framework/db"); 
        $master=new Master_Ref_Model();
        
        $list_katgori=Model::getOptionList("tbrsettings", "distinct settingKategori","settingKategori","settingKategori ASC"); 
        	
		$tpl->ListKategori =$list_katgori;
       $tpl->url_listdata      = url::current("listdata");
       $tpl->url_json		= url::current("json");
      
	   $tpl->url_comboAjax		=url::current("comboAjax");
       $tpl->url_form = url::current("add","form");
		$this->tpl->content = $tpl;
		$this->tpl->render();
	    
   }
  public function listdata() {
 	    global $dcistem;
 	  	
       $db   = $dcistem->getOption("framework/db");
      
        $login=new Adm_Login_Model();
        $master=new Master_Ref_Model();
        $modelsortir	= new Adm_Sortir_Model();
        $settings        = new Adm_Settings_Model();
        $login_as = $_SESSION["framework"]["login_as"]; 
        
        $requestData= $_REQUEST;
        
        $level       = $requestData['columns'][0]['search']['value'];
        $category    = $requestData['columns'][1]['search']['value'];
        $page_name    = $requestData['columns'][3]['search']['value'];//
      
       // $keriteria[]="AppPageListWebID ='kpbs-erp'";
        $keriteria_count=$keriteria;
        if( trim($page_name)<>"" ){   //name
            $keriteria[]="( AppPageListPageName like'%".$page_name."%' or AppPageListPageTitle like'%".$page_name."%')";
        }
        if(trim($level)<>""){
            $keriteria[]="( AppPagePrivilegeLevelID like'%".$level."%' )" ;
        }
        $filter=$modelsortir->fromFormcari($keriteria,"and");
        $draw=$_REQUEST['draw'];
		$length=$_REQUEST['length'];
		$start=$_REQUEST['start'];
        
        $cols=array(0=>"settingID",
                    1=>"settingKategori");
        $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
            
        $list_qry=$db->select("settingID,settingKey,settingValue,settingKategori","tbrsettings")
		->where($filter)->lim($start,$length);//->orderby($order)
        $no=$start+1;
        $i=0;
        $ListData=array();
        $rekap_tipe=$settings->getListReferenceCowType();
        while($data = $db->fetchObject($list_qry))
        {
            $ListData[$i]['No']=$no;
            $ListData[$i]['ID']=$data->settingID;
           
            $ListData[$i]['Key']=$data->settingKey;
            
            //$privileges=$user_manage->privileges($path,$data->AppPagePrivilegePrivileges);
            $value=$data->settingValue;
            $serialis=unserialize($data->settingValue);
            if(is_array($serialis) ){
               //echo "cek :";print_r($serialis);echo "</br>";
                $html="";
                //$arr_rc=array();
    			$j=0;
                
                    while($pr=current($rekap_tipe)){
             			$ck=in_array($pr,$serialis)?" checked='checked' ":"";
             			$input  ="<input type=\"checkbox\" name=\"privileges[".$j."]\" value=\"".$pr."\" $ck  disabled=\"disabled\"/>";
                        $html   = trim($html)==""?$input." ".$pr:$html."<br />".$input." ".$pr;
        				$j++;
             			next($rekap_tipe);
             		}
                    $value=$html;
            }
            //$ListData[$i]['Kategori']=$value;
            $ListData[$i]['Value']=$value;
            $ListData[$i]['Kategori']=$data->settingKategori;
             $url_del      = url::current("del",$data->settingID);
			$url_edit =url::current("edit",$data->settingID);
           	$tombol=$login->privilegeInputForm("link","","","<i class=\"fa fa-gear\"></i>",$this->page->PageID,"edit","title='Edit Data' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs btn-edit-data\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\"");
           // $tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-fw fa-trash-o\"></i>",$this->page->PageID,"del","title='Hapus Data Pelanggan' data-toggle=\"modal\" data-remote=\"true\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->PartnerNama."\" ");
			$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"del","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->settingID."\"");
			$control=$tombol;  
            $ListData[$i]['Tombol']=$control;
            $i++;
            $no++;
        }
       //echo "<pre  style='text-align:left;'>"; print_r($ListData);echo "</pre>";exit;
        $filter_count=$modelsortir->fromFormcari($keriteria_count,"and");
        $jml=$db->select("count(settingID) as jml_data","tbrsettings")->get(0);
      
         $hasil['draw']=$draw;
        $hasil['recordsTotal']=$jml->jml_data;
        $hasil['recordsFiltered']=$jml->jml_data;
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
	        $nama	=trim($_POST['name']);
	        $category	=trim($_POST['category']);
	        $aktif	=trim($_POST['aktif']);
            
	        $validasi=$this->validasiform("add");   
	        if(count($validasi['arrayerror'])==0){
		        $TglSkrg=date("Y-m-d H:i:s");
		        $sqlin="";
                $nama_val	=$master->scurevaluetable($nama,"string");
		        $category_val	=$master->scurevaluetable($category);
		        $aktif_val	=$master->scurevaluetable($aktif,"number");
		        //$hide_val	=$master->scurevaluetable($hide,"string");
		        
				$cols="name,category,active";
				$values="$nama_val,$category_val,$aktif_val";
				$sqlin="INSERT INTO keswan_obat ($cols) VALUES ($values);";
                
    
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
			
	    	$tpl  = new View("form_settings");
    	    $list_category=Model::getOptionList("keswan_obat_category","code","case when ifnull(category,'')='' 
            then code else concat(code,'-',category) end  category","category ASC"); 
    	    
            $tpl->list_category =$list_category;
			
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
    $settings=new Adm_Settings_Model();
    date_default_timezone_set("Asia/Jakarta");
    if(trim($proses)=="save")
	{    
	    if(trim($id)<>"")
	    { 
	       //echo "<pre>";print_r($_POST);echo "</pre>";
            $key	=trim($_POST['frm_key']);
	        $category	=trim($_POST['frm_kategori']);
	       
	        $validasi=$this->validasiform("edit");   
	        if(count($validasi['arrayerror'])==0){
		        $TglSkrg=date("Y-m-d H:i:s");
                $key_val=$master->scurevaluetable($key);
                $nilai= !is_array($_POST['frm_value'])?$_POST['frm_value']:"";
                if(trim($key)=='populasi'){
                    $value_serialize="";
                    if(!empty($_POST['frm_value'])){
                        $val   =array();
                        $i      =0;
                        foreach($_POST['frm_value'] as $key1=>$value){
                            $val[$i]=$value;
                            $i++;
                        }
                        $value_serialize= serialize($val);
                    }
                    $nilai=$value_serialize;
                }
                $nilai_val=$master->scurevaluetable($nilai);
                $category_val=$master->scurevaluetable($category);
		        
               	$cols_and_vals="settingKey=$key_val,settingValue=$nilai_val,settingKategori=$category_val";
				$sqlin="UPDATE tbrsettings SET $cols_and_vals WHERE settingID=$id;";
                
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
	    }else{
	        $msg['success']=false;
	        $msg['message']="Gagal menggambil data yang akan diubah, silahkan ulangi!";
	    }
	    echo json_encode($msg);   
	}else{
	       $tpl  = new View("form_settings");
           $detail= $settings->getSetings($id);
           //echo "<pre>";print_r($detail);echo "</pre>";
           $tpl->detail = $detail;
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
        if(trim($id)<>"")
        {
	        $Nama=$_POST['nama'];
	        $sqlin="DELETE FROM   keswan_obat  WHERE id=$id;";
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
    	if(trim($_POST['frm_key'])==''){
            $pesan["frm_key"]="Key harus diisi!";   
            $msg[]="Key harus diisi!";
        }
        if(!is_array($_POST['frm_value'])){
            if(trim($_POST['frm_value'])==''){
                $pesan["frm_value"]="Nilai harus diisi!";   
                $msg[]="Nilai harus diisi!";
            }
        }else{
            if(empty($_POST['frm_value'])){
                $pesan["frm_value"]="Nilai harus diisi!";   
                $msg[]="Nilai harus diisi!";
            }
        }
        
        if(trim($_POST['frm_kategori'])==''){
            $pesan["frm_kategori"]="Kategori harus diisi!";   
            $msg[]="Kategori harus diisi!";
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
   public function save($kategori="") {
		global $dcistem;
		$db = $dcistem->getOption("framework/db");
		date_default_timezone_set("Asia/Jakarta");
		$master=new Master_Ref_Model();
		$tgl_skrg=date("Y-m-d H:i:s");
        $tgl_skrg_val	=$master->scurevaluetable($tgl_skrg,"string");
		$login_as	=	$_SESSION['framework']['login_as'];      	
        $ref_id		=$_SESSION["framework"]["ref_id"] ;
        $user_id	=$_SESSION["framework"]['current_user']->Username;
        $user_id_val	=$master->scurevaluetable($user_id,"string");
		$msg=array();
		$hasil=array();
	//	echo "<pre>";print_r($_POST);echo "</pre>";exit;
		switch ($kategori){
        	case "krs":
        		$bsb     	= isset($_POST["block_semester_bawah"])?$_POST["block_semester_bawah"]:"";
		        $bsb_val	= $master->scurevaluetable($bsb);
				$sql		="UPDATE tbrSettings SET settingValue=$bsb_val WHERE settingKey='block_semester_bawah' 
							and settingKategori='".$kategori."'";
				$rsl=$db->query($sql);
				if(isset($rsl->error) and $rsl->error===true){
	                $msg['block_semester_bawah']="Error, ".$rsl->query_last_message;
				}
		        $bsa     	= isset($_POST["block_semester_atas"])?$_POST["block_semester_atas"]:"";
		        $bsa_val	= $master->scurevaluetable($bsa);
		        $sql		="UPDATE tbrSettings SET settingValue=$bsa_val WHERE settingKey='block_semester_atas' 
							and settingKategori='".$kategori."'";
				$rsl=$db->query($sql);
				if(isset($rsl->error) and $rsl->error===true){
	                $msg['block_semester_atas']="Error, ".$rsl->query_last_message;
				}
				$sbmk     	= isset($_POST["syarat_bayar_min_krs"])?$_POST["syarat_bayar_min_krs"]:"";
		        $sbmk_val	= $master->scurevaluetable($sbmk,"number");
		        $sql		="UPDATE tbrSettings SET settingValue=$sbmk_val WHERE settingKey='syarat_bayar_min_krs' 
							and settingKategori='".$kategori."'";
				$rsl=$db->query($sql);
				if(isset($rsl->error) and $rsl->error===true){
	                $msg['syarat_bayar_min_krs']="Error, ".$rsl->query_last_message;
				}
				$tsbmk     	= isset($_POST["toleransi_syarat_bayar_min_krs"])?$_POST["toleransi_syarat_bayar_min_krs"]:"";
		        $tsbmk_val	= $master->scurevaluetable($tsbmk,"number");
		        $sql		="UPDATE tbrSettings SET settingValue=$tsbmk_val WHERE settingKey='toleransi_syarat_bayar_min_krs' 
							and settingKategori='".$kategori."'";
				$rsl=$db->query($sql);
				if(isset($rsl->error) and $rsl->error===true){
	                $msg['toleransi_syarat_bayar_min_krs']="Error, ".$rsl->query_last_message;
				}
				$mt     	= isset($_POST["metode_transkrip"])?$_POST["metode_transkrip"]:"";
		        $mt_val	= $master->scurevaluetable($mt);
		        $sql		="UPDATE tbrSettings SET settingValue=$mt_val WHERE settingKey='metode_transkrip' 
							and settingKategori='".$kategori."'";
				$rsl=$db->query($sql);
				if(isset($rsl->error) and $rsl->error===true){
	                $msg['metode_transkrip']="Error, ".$rsl->query_last_message;
				}
				
        	break;
        	case "sistem":
        
        		$dbp     	= isset($_POST["database_pendaftaran"])?$_POST["database_pendaftaran"]:"";
		        $dbp_val	= $master->scurevaluetable($dbp);
		        $sql		="UPDATE tbrSettings SET settingValue=$dbp_val WHERE settingKey='database_pendaftaran' 
							and settingKategori='".$kategori."'";
				$rsl=$db->query($sql);
				if(isset($rsl->error) and $rsl->error===true){
	                $msg['database_pendaftaran']="Error, ".$rsl->query_last_message;
				}
				$hm     	= isset($_POST["host_mahasiswa"])?$_POST["host_mahasiswa"]:"";
		        $hm_val	= $master->scurevaluetable($hm);
		        $sql		="UPDATE tbrSettings SET settingValue=$hm_val WHERE settingKey='host_mahasiswa' 
							and settingKategori='".$kategori."'";
				$rsl=$db->query($sql);
				if(isset($rsl->error) and $rsl->error===true){
	                $msg['host_mahasiswa']="Error, ".$rsl->query_last_message;
				}
				$file     	= isset($_POST["logo_kop_surat"])?$_POST["logo_kop_surat"]:"";
		        $file_val	= $master->scurevaluetable($file);
		        $sql		="UPDATE tbrSettings SET settingValue=$file_val WHERE settingKey='logo_kop_surat' 
							and settingKategori='".$kategori."'";
				$rsl=$db->query($sql);
				if(isset($rsl->error) and $rsl->error===true){
	                $msg['logo_kop_surat']="Error, ".$rsl->query_last_message;
				}
        	break;
        	
        }
        if(count($msg)>0){
        	$hasil['success']=false;
        	$hasil['pesan']="Ada error pengisian data";
			$hasil['form_error']=$msg;
        }else{
        	$hasil['success']=true;
        	$hasil['pesan']="Perubahan Pengaturan ".strtoupper($kategori)." sudah disimpan";
        }
        echo json_encode($hasil); 
	}
 
 	 public function upload($kategori="") {
 	 	$msg_err="";
 	 	$master=new Master_Ref_Model();
 	 	$path=$_POST['logo_kop_surat'];
 		switch ($kategori){
        	case "krs":
        	
				
        	break;
        	case "logo":
        //	echo "<pre>";print_r($_FILES);echo "</pre>";exit;
				if(isset($_FILES['file_logo_kop'])){
					$ukuran =round(($_FILES['file_logo_kop']['size']/1000),2);
			    	$uk		= number_format($ukuran,2,",",".");
					if($_FILES['file_logo_kop']['size']<8000){
			    		$msg_err="Ukuran file : ".$uk."KB. Minimal yang diijinkan 8kbyte"; 
			    	}
			    	if($_FILES['file_logo_kop']['size']>500000){
			    		$msg_err="Ukuran file : ".$uk."KB. Maksimal yang dijinkan 500kbyte";  
			    	}
			    	$extension = pathinfo($_FILES['file_logo_kop']['name'],PATHINFO_EXTENSION);  
			    	$valid_ext = array('jpg','jpeg','png');
			    	
			    	if(!in_array($extension, $valid_ext)){
			    		$msg_err="Tipe file harus dalam format JPG, JPEG atau PNG";   
			    	}
					if(trim($msg_err)==""){
					
					    $pathfile=$_FILES['file_logo_kop']["tmp_name"];
				        $login_as=	$_SESSION['framework']['login_as'];      	
				        $ref_id=$_SESSION["framework"]["ref_id"] ;
				        $type = $_FILES['file_logo_kop']['type'];
				        $extension = pathinfo($_FILES['file_logo_kop']['name'],PATHINFO_EXTENSION);  
				        $type = $_FILES['file_logo_kop']['type'];
				        $Tanggal=date("YmdHis");
				        $namafile="logo_kop_surat.".$extension;
				        $nmfile = "gambar/".$namafile;   
				        $path	= url::base().$nmfile ;
				        if (is_writable("gambar/")) {
					        if(file_exists($nmfile))
					        {
					        	unlink($nmfile);
					        }
							if(move_uploaded_file($_FILES['file_logo_kop']["tmp_name"],$nmfile))
				        	{   
				        		$msg_err="";
				        	}else{
				        		$msg_err="Error, Gagal upload";
				        	}
				        }else{
				        	$msg_err="Error, destination is not writable";
				        }
			        }else{
			        	$msg_err="Error, ".$msg_err;
			        }
		        }else{
					$msg_err="Error, Tidak ada file yang diupload";
		        }
        	break;
        	
        }
        if(trim($msg_err)<>""){
        	$hasil['success']=false;
        	$hasil['pesan']=$msg_err;
        }else{
        	$hasil['success']=true;
        	$hasil['path']=$path;
        	$hasil['pesan']="Berhasil upload ".strtoupper($kategori);
        }
        echo json_encode($hasil); 
	}
}
 

?>