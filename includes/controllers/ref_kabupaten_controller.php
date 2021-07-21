<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 * 
//  * @author 
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Ref_Kabupaten_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        global $dcistem;
       
	}

    public function index() {
        global $dcistem;
        $tpl = new View("ref_kabupaten");
        $db = $dcistem->getOption("framework/db");
        $login=new Adm_Login_Model();

        $url_form = url::current("add"); 
        $list_provinsi=Model::getOptionList("tbrpropinsi","propinsiKode","propinsiNama","propinsiNama ASC"); 
        $tpl->list_provinsi =$list_provinsi;    
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
        
        $prov = $requestData['columns'][1]['search']['value'];
        $code = $requestData['columns'][2]['search']['value'];
        $name = $requestData['columns'][3]['search']['value'];
        $jenis = $requestData['columns'][4]['search']['value'];


        if(trim($prov)<>""){
            $keriteria[]="( kb.kabupatenPropinsiKode = '".$prov."')" ;
        }
        if(trim($code)<>""){
            $keriteria[]="( kb.kabupatenKode = '".$code."')" ;
        }
        if(trim($name)<>""){
            $keriteria[]="( kb.kabupatenNamaSaja like'%".$name."%')" ;
        }
        if(trim($jenis)<>""){
            $keriteria[]="( kb.kabupatenJenis like'%".$jenis."%')" ;
        }
        $filter=$modelsortir->fromFormcari($keriteria,"and");

        $draw=$_REQUEST['draw'];
		$length=$_REQUEST['length'];
		$start=$_REQUEST['start'];
        
        $cols=array(0=>"kabupatenKode",
                    1=>"kabupatenKode",
                    2=>"kabupatenJenis",
                    3=>"kabupatenNamaSaja",
                    4=>"provinsi_name");
        $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];


        $list_qry = $db->select("SQL_CALC_FOUND_ROWS kb.kabupatenKode, kb.kabupatenJenis, kb.kabupatenNamaSaja, kb.kabupatenPropinsiKode, pv.propinsiNama provinsi_name ","tbrkabupaten kb inner join tbrpropinsi pv on kb.kabupatenPropinsiKode = pv.propinsiKode")
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
            $ListData[$i]['code']=$data->kabupatenKode;
            $ListData[$i]['jenis']=$data->kabupatenJenis;
            $ListData[$i]['name']=$data->kabupatenNamaSaja;
            $ListData[$i]['provinsi_name']=$data->provinsi_name;
            
            $url_del  =url::current("del",$data->kabupatenKode);
			$url_edit =url::current("edit",$data->kabupatenKode);
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
               $provinsi=trim($_POST['provinsi']);
               $code	=trim($_POST['code']);
               $name	=trim($_POST['name']);
               $jenis	=trim($_POST['jenis']);
               $jenis_singkat	=trim($_POST['jenis_singkat']);
               
               $validasi=$this->validasiform();   
               if(count($validasi['arrayerror'])==0){
                   $cek=$db->select("kabupatenKode","tbrkabupaten")->where("kabupatenKode='".$code."'")->get(0);
                   if(empty($cek)){
                       $TglSkrg=date("Y-m-d H:i:s");
                       $sqlin="";
                       $provinsi_val=$master->scurevaluetable($provinsi);
                       $code_val	=$master->scurevaluetable($code);
                       $name_val	=$master->scurevaluetable($name);
                       $jenis_val	=$master->scurevaluetable($jenis);
                       $jenis_singkat_val	=$master->scurevaluetable($jenis_singkat);
                      
                       $namelengkap_val	= "'".$jenis_singkat." ".$name."'";
                       
                       $cols="kabupatenPropinsiKode,kabupatenKode,kabupatenNamaSaja,kabupatenNama,kabupatenJenis,kabupatenJenisSingkat";
                       $values="$provinsi_val,$code_val,$name_val,$namelengkap_val,$jenis_val,$jenis_singkat_val";
                       $sqlin="INSERT INTO tbrkabupaten ($cols) VALUES ($values);";
                       
           
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
               
               $tpl  = new View("form_kabupaten");
               $list_provinsi=Model::getOptionList("tbrpropinsi","propinsiKode","propinsiNama","propinsiNama ASC"); 
               $tpl->list_provinsi =$list_provinsi;
               
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
                $provinsi	=trim($_POST['provinsi']);
                $code	=trim($_POST['code']);
                $name	=trim($_POST['name']);
                $jenis	=trim($_POST['jenis']);
                $jenis_singkat	=trim($_POST['jenis_singkat']);

                // $cek=$db->select("kabupatenKode","tbrkabupaten")->where("kabupatenKode='".$code."'")->get(0);
                // if(empty($cek)){
                    
                    $validasi=$this->validasiform(); 
                    if(count($validasi['arrayerror'])==0){
                        $TglSkrg=date("Y-m-d H:i:s");
                        $sqlin="";
                        $provinsi_val	=$master->scurevaluetable($provinsi);
                        $code_val	=$master->scurevaluetable($code);
                        $name_val	=$master->scurevaluetable($name);
                        $jenis_val	=$master->scurevaluetable($jenis);
                        $jenis_singkat_val	=$master->scurevaluetable($jenis_singkat);
                        $namelengkap_val	= "'".$jenis_singkat." ".$name."'";

                        $cols_and_vals="kabupatenPropinsiKode=$provinsi_val,kabupatenKode=$code_val,kabupatenNamaSaja=$name_val,kabupatenNama=$namelengkap_val, kabupatenJenis=$jenis_val, kabupatenJenisSingkat=$jenis_singkat_val";
                    
                        $sqlin="UPDATE tbrkabupaten SET $cols_and_vals WHERE kabupatenKode=$id;";
                        
                        
            
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
            $kabupaten=new Ref_Kabupaten_Model();

            $tpl  = new View("form_kabupaten");
            $list_provinsi=Model::getOptionList("tbrpropinsi","propinsiKode","propinsiNama","propinsiNama ASC"); 
            $tpl->list_provinsi =$list_provinsi;
            $detail=$kabupaten->getKabupaten($id);
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
            $sqlin="DELETE FROM  tbrkabupaten  WHERE kabupatenKode=$id;";
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
        if(trim($_POST['provinsi'])==''){
            $pesan["category"]="Provinsi harus diisi!";   
            $msg[]="Provinsi harus diisi!";
        }
        if(trim($_POST['code'])==''){
            $pesan["nomor"]="Kode Kabupaten harus diisi!";   
            $msg[]="Kode Kabupaeten harus diisi!";
        }
        if(trim($_POST['name'])==''){
            $pesan["nomor"]="Nama Kabupaten harus diisi!";   
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