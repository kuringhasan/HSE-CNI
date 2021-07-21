<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 * 
//  * @author 
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Ref_Provinsi_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        global $dcistem;
       
	}
    public function index() {
        global $dcistem;
        $tpl = new View("ref_provinsi");
        $db = $dcistem->getOption("framework/db");
        $login=new Adm_Login_Model();

        $url_form = url::current("add");   
        $TombolTambah=$login->privilegeInputForm("link","","btn-tambah-data","<i class=\"fa fa-fw fa-plus-circle\"></i>",$this->page->PageID,"add","title='Tambah Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_form."\" class=\"btn btn-primary btn-xs\" data-target=\"#largeModal\"");
        $tpl->TombolTambah = $TombolTambah; 
        $tpl->url_listdata = url::current("listdata");


        $this->tpl->content = $tpl;
        $this->tpl->render();
    }

    public function listdata() {
        global $dcistem;
        $db = $dcistem->getOption("framework/db");
        $login=new Adm_Login_Model();
        $master=new Master_Ref_Model();


        $modelsortir	= new Adm_Sortir_Model();
        $login_as = $_SESSION["framework"]["login_as"]; 
        
        $requestData= $_REQUEST;
        
        $code = $requestData['columns'][1]['search']['value'];
        $name = $requestData['columns'][2]['search']['value'];

        if(trim($code)<>""){
            $keriteria[]="( propinsiKode = '".$code."')" ;
        }
        if(trim($name)<>""){
            $keriteria[]="( propinsiNama like'%".$name."%')" ;
        }
        $filter=$modelsortir->fromFormcari($keriteria,"and");

        $draw=$_REQUEST['draw'];
		$length=$_REQUEST['length'];
		$start=$_REQUEST['start'];
        
        $cols=array(0=>"propinsiKode",
                    1=>"propinsiKode",
                    2=>"propinsiNama");
        $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];


        $list_qry = $db->select("SQL_CALC_FOUND_ROWS propinsiKode,propinsiNama","tbrpropinsi")
        ->where($filter)
        ->orderby($order)
        ->lim($start,$length);

        // var_dump($list_qry);


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
            $ListData[$i]['code']=$data->propinsiKode;
            $ListData[$i]['name']=$data->propinsiNama;
            
            $url_del  =url::current("del",$data->propinsiKode);
			$url_edit =url::current("edit",$data->propinsiKode);
           	$tombol=$login->privilegeInputForm("link","","","<i class=\"fa fa-gear\"></i>",$this->page->PageID,"edit","title='Edit Data' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs btn-edit-data\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\"");
           // $tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-fw fa-trash-o\"></i>",$this->page->PageID,"del","title='Hapus Data Pelanggan' data-toggle=\"modal\" data-remote=\"true\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->PartnerNama."\" ");
			$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"del","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->KasusID."\"");
			$control=$tombol;  
            $ListData[$i]['Tombol']=$control;
            $i++;
            $no++;
        }
      
        $hasil['draw']=$draw;
        // $hasil['title']=strtoupper($judul);
        $hasil['recordsTotal']=$jml_filtered;
        $hasil['recordsFiltered']=$jml_filtered;//
	    $hasil['data']=$ListData;
         //echo $hasil;
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
               $code	=trim($_POST['code']);
               $name	=trim($_POST['name']);
               
               $validasi=$this->validasiform();   
               if(count($validasi['arrayerror'])==0){
                   $cek=$db->select("propinsiKode","tbrpropinsi")->where("propinsiKode='".$code."'")->get(0);
                   if(empty($cek)){
                       $TglSkrg=date("Y-m-d H:i:s");
                       $sqlin="";
                       $code_val	=$master->scurevaluetable($code);
                       $name_val	=$master->scurevaluetable($name);
                       
                       $cols="propinsiKode,propinsiNama";
                       $values="$code_val,$name_val";
                       $sqlin="INSERT INTO tbrpropinsi ($cols) VALUES ($values);";
                       
           
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
                            $msg['message']="Data dengan kode $code sudah ada "; 
                    }
                  
               }else{
                    $msg['success']	=false;
                    $msg['message']	="Terjadi kesalahan pengisian form";
                    $msg['form_error']=$validasi['arrayerror'];
               }
               echo json_encode($msg);   
           }else{
               
               $tpl  = new View("form_provinsi");
               
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
                $code	=trim($_POST['code']);
                $name	=trim($_POST['name']);

                // $cek=$db->select("propinsiKode","tbrpropinsi")->where("propinsiKode='".$code."'")->get(0);
                // if(empty($cek)){
                    
                    $validasi=$this->validasiform(); 
                    if(count($validasi['arrayerror'])==0){
                        $TglSkrg=date("Y-m-d H:i:s");
                        $sqlin="";
                        $code_val	=$master->scurevaluetable($code);
                        $name_val	=$master->scurevaluetable($name);

                        $cols_and_vals="propinsiKode=$code_val,propinsiNama=$name_val";
                    
                        $sqlin="UPDATE tbrpropinsi SET $cols_and_vals WHERE propinsiKode=$id;";
                        
                        
            
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
            $kabupaten=new Ref_Provinsi_Model();

            $tpl  = new View("form_provinsi");
            $detail=$kabupaten->getProvinsi($id);
            $tpl->detail = $detail;
            
            $tpl->url_add           = url::current("add");
            $tpl->url_jsonData		= url::current("jsonData");
            $tpl->url_comboAjax		= url::current("comboAjax");
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
            $sqlin="DELETE FROM  tbrpropinsi  WHERE propinsiKode=$id;";
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
        if(trim($_POST['code'])==''){
            $pesan["nomor"]="Kode Provinsi harus diisi!";   
            $msg[]="Kode Kabupaeten harus diisi!";
        }
        if(trim($_POST['name'])==''){
            $pesan["nomor"]="Nama Provinsi harus diisi!";   
            $msg[]="Nama Kabupaeten harus diisi!";
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



}

?>