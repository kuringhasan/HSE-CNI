<?php
 
defined("PANDORA") OR die("No direct access allowed.");

class Ref_sanksi_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
		global $dcistem;
		
	}
	

	public function index() {
        global $dcistem;
 
        $tpl  = new View("ref_sanksi");
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
		$requestData= $_REQUEST;

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
		
		$length=$_REQUEST['length'];

		$start=$_REQUEST['start'];
		$ListData      = array();
		$jml_filtered  = 0;
		$jml_data      = 0;
		$filter=$modelsortir->fromFormcari($keriteria,"and");
		
		$cols=array(0=>"kode",
					1=>"kode",
					2=>"nama_sanksi");
		$order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
		if ($order == " ")
		{
			$order= "kode desc";
		}

		

		$list_qry=$db->select("SQL_CALC_FOUND_ROWS a.kode, a.*
		","ref_sanksi a")
		->where($filter)
		->orderBy($order)
		->lim($start,$length)
		;
	   
		$no=$start+1;
		$i=0;

		while($data = $db->fetchObject($list_qry))
		{
			if($i==0){
				$filtered_qry=$db->query("SELECT FOUND_ROWS() jml_filtered;");
				$filtered_data=$db->fetchObject($filtered_qry);
				$jml_filtered= $filtered_data->jml_filtered;
			}
			$ListData[$i]['No']=$no;
			$ListData[$i]['kode']=$data->kode;
			$ListData[$i]['nama_sanksi']=$data->nama_sanksi;

		   
			$url_del      = url::current("del",$data->kode);
			$url_edit =	url::current("edit",$data->kode);
			$url_detail =url::current("detail",$data->kode);


			$tombol          = "";
			//$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-pencil\"></i>",$this->page->PageID,"edit","title='Ubah Data' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs\" ");  
			$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-pencil\"></i>",$this->page->PageID,"edit","title='Ubah Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_edit."\" class=\"btn btn-primary btn-xs btn-edit-data\" data-target=\"#largeModal\"");
			$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-file-text-o\"></i>",$this->page->PageID,"detail","title='Detail' data-toggle=\"modal\" data-remote=\"true\" href=\"".$url_detail."\" class=\"btn btn-primary btn-xs btn-detail-data\" data-target=\"#largeModal\" role=\"".$data->id_insiden."\"");  
			$tombol .="&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"del","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->id_insiden."\"");  

		  
			$control=$tombol;  
			$ListData[$i]['Aksi']=$control;

		   $i++;
		   $no++;
	   }
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
               $kode		=trim($_POST['kode']);
               $nama_sanksi	=trim($_POST['nama_sanksi']);

               

               $validasi=$this->validasiform();   
               if(count($validasi['arrayerror'])==0){
                   $cek=$db->select("kode","ref_sanksi")->where("kode='".$kode."'")->get(0);
                   if(empty($cek)){
						// $TglSkrg=date("Y-m-d H:i:s");
						$sqlin				="";
						$kode_val			=$master->scurevaluetable($kode);
						$nama_sanksi_val	=$master->scurevaluetable($nama_sanksi);

                       
                       $cols="kode,nama_sanksi";
                       $values="$kode_val,$nama_sanksi_val";
                       $sqlin="INSERT INTO ref_sanksi ($cols) VALUES ($values);";
                       
           
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
                            $msg['message']="Data dengan kode $kode sudah ada "; 
                    }
                  
               }else{
                    $msg['success']	=false;
                    $msg['message']	="Terjadi kesalahan pengisian form";
                    $msg['form_error']=$validasi['arrayerror'];
               }
			//    var_dump("");
               echo json_encode($msg);   
		}else{
			
			$tpl  = new View("form_ref_sanksi");
			
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
				$kode		=trim($_POST['kode']);
				$nama_sanksi	=trim($_POST['nama_sanksi']);

                // $cek=$db->select("propinsiKode","tbrpropinsi")->where("propinsiKode='".$code."'")->get(0);
                // if(empty($cek)){

                    $validasi=$this->validasiform(); 
                    if(count($validasi['arrayerror'])==0){
                        $cek=$db->select("kode","ref_sanksi")->where("kode='".$kode."'")->get(0);
                   		if(empty($cek) || $kode == $id){
							$TglSkrg=date("Y-m-d H:i:s");
							$sqlin="";
							$kode_val		=$master->scurevaluetable($kode);
							$nama_sanksi_val	=$master->scurevaluetable($nama_sanksi);


							$cols_and_vals="kode=$kode_val,nama_sanksi=$nama_sanksi_val";
						
							$sqlin="UPDATE ref_sanksi SET $cols_and_vals WHERE kode=$id;";
							
							
				
							$rsl=$db->query($sqlin);
							if(isset($rsl->error) and $rsl->error===true){
									$msg['success']=false;
									$msg['message']="Error, ".$rsl->query_last_message;
							}else{
								$msg['success']=true;
								$msg['message']="Perubahan data sudah disimpan"; 
							
							}
						}else{
							$msg['success']=false;
                            $msg['message']="Data dengan kode $kode sudah ada ";
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
            $partner=new Ref_sanksi_Model();

            $tpl  = new View("form_ref_sanksi");
            $detail=$partner->getDetailRefSanksi($id);
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
		$tpl  = new View("detail_ref_sanksi");
		$insidenMdl    = new Ref_sanksi_Model();
		date_default_timezone_set("Asia/Jakarta");
		$detail=$insidenMdl->getDetailRefSanksi($id);
		// $tpl->list_contractor =Model::getOptionList("partner","id","name","name ASC","ifnull(is_contractor,0)=1"); 

		$tpl->kode            		= $detail->kode;
		$tpl->nama_sanksi       	= $detail->nama_sanksi;
		

		// var_dump($tpl);

		$this->tpl->content_title = "Detail Ref tingkat_keparahan";
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
            $sqlin="DELETE FROM  ref_sanksi  WHERE kode=$id;";
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
    	if(trim($_POST['kode'])==''){
            $pesan["kode"]="Kode harus diisi!";   
            $msg[]="Kode harus diisi!";
        }
		if(trim($_POST['nama_sanksi'])==''){
            $pesan["nama_sanksi"]="Nama harus diisi!";   
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
