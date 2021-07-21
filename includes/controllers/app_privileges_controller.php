<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class App_Privileges_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        global $dcistem;
       
	}
	
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("app_privileges");
        $db   = $dcistem->getOption("framework/db");
        $login=new Adm_Login_Model();
        $KodeDosen=$_SESSION["framework"]["ref_id"] ;
        $Username=$_SESSION["framework"]["current_user"]->Username ;
        $list_level=Model::getOptionList("tbaapplevellist","AppLevelListLevelID","AppLevelListLevelName","AppLevelListLevelID ASC"); 
	    
        $tpl->list_level =$list_level;
     
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
        $modelsortir	= new Adm_Sortir_Model();
        $core_page           = new Core_Page_Model();
        $user_manage        = new User_Manage_Model();
        $login_as = $_SESSION["framework"]["login_as"]; 
        
        $requestData= $_REQUEST;
        
        $level       = $requestData['columns'][0]['search']['value'];
        $category    = $requestData['columns'][1]['search']['value'];
        $page_name    = $requestData['columns'][3]['search']['value'];//
      
        //$keriteria[]="AppPageListWebID ='erp-ceria'";
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
        
        $cols=array(0=>"AppPagePrivilegePageID",
                    1=>"AppPagePrivilegeLevelID");
        $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
            
        $list_qry=$db->select("AppPagePrivilegeID,AppPagePrivilegePageID,AppPageListPageName,AppPageListPageTitle,AppPageListPageController,
        AppPagePrivilegeLevelID,AppPagePrivilegePrivileges","tbaapppagelist pg 
        left join tbaapppageprivilege pri on pg.AppPageListPageID=pri.AppPagePrivilegePageID")
		->where($filter)->lim($start,$length);//->orderby($order)
        $no=$start+1;
        $i=0;
        $ListData=array();
        while($data = $db->fetchObject($list_qry))
        {
            $controller_name = str_replace(" ", "_", ucwords(str_replace("_", " ", $data->AppPageListPageController)))."_Controller";
			$path            		= $dcistem->getOption("system/dir/controller").strtolower($controller_name).".php";
            $ListData[$i]['No']=$no;
            $ListData[$i]['ID']=$data->AppPagePrivilegeID;
            $page=trim($data->AppPageListPageTitle)==""?$data->AppPageListPageName:$data->AppPageListPageName." (".$data->AppPageListPageTitle.")";
            $ListData[$i]['Page']=$page."<br />".$path;
            $ListData[$i]['Level']=$data->AppPagePrivilegeLevelID;
            $privileges=$user_manage->privileges($path,$data->AppPagePrivilegePrivileges);
            $html="";
            //$arr_rc=array();
			$j=0;
     		if(!empty($privileges)){
                while($pr=current($privileges)){
    		        
         		//	$rc=new stdClass;
         			$ck=$pr['check']==true?" checked='checked' ":"";
         			$input  ="<input type=\"checkbox\" name=\"privileges[".$j."]\" value=\"".$pr['method']."\" $ck  disabled=\"disabled\"/>";
                    $html   = trim($html)==""?$input." ".$pr['method']:$html."<br />".$input." ".$pr['method'];
         			//$rc->method=$pr['method'];
    			//	$rc->input=$input;
    				//$rc->priv=$pr['check'];
    				//$arr_rc[]=$rc;
    				$j++;
         			next($privileges);
         		}
            }
            $ListData[$i]['Privileges']=$html;
            $url_del      = url::current("del",$data->AppPagePrivilegeID);
			$url_edit =url::current("edit",$data->AppPagePrivilegeID);
           	$tombol=$login->privilegeInputForm("link","","","<i class=\"fa fa-gear\"></i>",$this->page->PageID,"edit","title='Edit Data' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs btn-edit-data\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\"");
           // $tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-fw fa-trash-o\"></i>",$this->page->PageID,"del","title='Hapus Data Pelanggan' data-toggle=\"modal\" data-remote=\"true\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->PartnerNama."\" ");
			$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"del","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->AppPagePrivilegeID."\"");
			$control=$tombol;  
            $ListData[$i]['Tombol']=$control;
            $i++;
            $no++;
        }
       //echo "<pre  style='text-align:left;'>"; print_r($ListData);echo "</pre>";exit;
        $filter_count=$modelsortir->fromFormcari($keriteria_count,"and");
        $jml=$db->select("count(AppPagePrivilegeID) as jml_data","tbaapppageprivilege pri
        inner join tbaapppagelist pg on pg.AppPageListPageID=pri.AppPagePrivilegePageID")->where($filter_count)->get(0);
        $jml_filtered=$db->select("count(AppPagePrivilegeID) as jml_data","tbaapppageprivilege pri
        inner join tbaapppagelist pg on pg.AppPageListPageID=pri.AppPagePrivilegePageID")->where($filter)->get(0);
      
         $hasil['draw']=$draw;
        $hasil['recordsTotal']=$jml->jml_data;
        $hasil['recordsFiltered']=$jml_filtered->jml_data;
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
	       
	        $validasi=$this->validasiform("add");   
	        if(count($validasi['arrayerror'])==0){
	           $cek_pri=$db->select("AppPagePrivilegeID","tbaapppageprivilege")
               ->where("AppPagePrivilegePageID=".$_POST['page_id']." and AppPagePrivilegeLevelID='".$_POST['level_id']."'")->get();
               if(empty($cek_pri)){
                    $page_id	=trim($_POST['page_id']);
                    $level_id	=trim($_POST['level_id']);
                    $privileges_serialize="";
                    if(!empty($_POST['privileges'])){
                        $priv   =array();
                        $i      =0;
                        foreach($_POST['privileges'] as $key=>$value){
                            $priv[$i]=$value;
                            $i++;
                        }
                        $privileges_serialize= serialize($priv);
                    }
    		        $TglSkrg=date("Y-m-d H:i:s");
                    $page_id_val	=$master->scurevaluetable($page_id,"number");
    		        $level_id_val	=$master->scurevaluetable($level_id);
    		        $privileges_val	="'".$privileges_serialize."'";
    		       
    				$cols="AppPagePrivilegePageID,AppPagePrivilegeLevelID,AppPagePrivilegePrivileges";
    				$values="$page_id_val,$level_id_val,$privileges_val";
    				$sqlin="INSERT INTO tbaapppageprivilege ($cols) VALUES ($values);";
                    
        
    				$rsl=$db->query($sqlin);
    				if(isset($rsl->error) and $rsl->error===true){
    			   	 		$msg['success']=false;
    	                	$msg['message']="Error, ".$rsl->query_last_message." ".$sqlin;
    				}else{
    	                $msg['success']=true;
    	                $msg['message']="Data sudah ditambahkan"; 
                       
    	            }
                 }else{
                    $msg['success']=false;
   	                $msg['message']="Page <strong>".$_POST['page_id']."</strong> untuk level <strong>".$_POST['level_id']."</strong> sudah ada. Jika ingin merubah privilege silahkan edit"; 
                 }
	           
	        }else{
	             $msg['success']	=false;
	             $msg['message']	=	"Terjadi kesalahan pengisian form";
	             $msg['form_error']=$validasi['arrayerror'];
	        }
	        echo json_encode($msg);   
	    }else{
			
	    	 $tpl  = new View("form_privilege");
             $list_level=Model::getOptionList("tbaapplevellist","AppLevelListLevelID","AppLevelListLevelName","AppLevelListLevelID ASC"); 
	    
        $tpl->list_level =$list_level;
     
			
	    	$tpl->url_add = url::current("add");
	    	$tpl->url_jsonData		= url::current("jsonData");
        	$tpl->url_getmethode		=url::current("get_methode");
	    	$tpl->content = $tpl;
	        $tpl->render(); 
	    }
  } 
public function edit($id,$proses=""){     
    global $dcistem;
    $db   = $dcistem->getOption("framework/db");
    $master=new Master_Ref_Model();
    $user_manage=new User_Manage_Model();
    date_default_timezone_set("Asia/Jakarta");
    if(trim($proses)=="save")
	{    
	    if(trim($id)<>"")
	    { 
	        $validasi=$this->validasiform("add");   
	        if(count($validasi['arrayerror'])==0){
		        $page_id	=trim($_POST['page_id']);
                $level_id	=trim($_POST['level_id']);
                $privileges_serialize="";
                if(!empty($_POST['privileges'])){
                    $priv   =array();
                    $i      =0;
                    foreach($_POST['privileges'] as $key=>$value){
                        $priv[$i]=$value;
                        $i++;
                    }
                    $privileges_serialize= serialize($priv);
                }
		        $TglSkrg=date("Y-m-d H:i:s");
                $page_id_val	=$master->scurevaluetable($page_id,"number");
		        $level_id_val	=$master->scurevaluetable($level_id);
		        $privileges_val	="'".$privileges_serialize."'";
		       
				$cols_and_vals="AppPagePrivilegePageID=$page_id_val,AppPagePrivilegeLevelID=$level_id_val,
                AppPagePrivilegePrivileges=$privileges_val";
				
				$sqlin="UPDATE tbaapppageprivilege SET $cols_and_vals WHERE AppPagePrivilegeID=$id;";
    
				$rsl=$db->query($sqlin);
				if(isset($rsl->error) and $rsl->error===true){
			   	 		$msg['success']=false;
	                	$msg['message']="Error, ".$rsl->query_last_message." ".$sqlin;
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
	       $tpl  = new View("form_privilege");
           $list_level=Model::getOptionList("tbaapplevellist","AppLevelListLevelID","AppLevelListLevelName","AppLevelListLevelID ASC"); 
	    
        $tpl->list_level =$list_level;
     
            $detail=$user_manage->getPagePrivilegesByID($id);
            //echo "<pre style='text-align:left;'>";print_r($detail); echo "</pre>";
			$tpl->detail = $detail;
	    	$tpl->url_add = url::current("add");
	    	$tpl->url_jsonData		= url::current("jsonData");
        	$tpl->url_getmethode		=url::current("get_methode");
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
	        $sqlin="DELETE FROM tbaapppageprivilege WHERE AppPagePrivilegeID=$id;";
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
  public function get_methode($page_id){     
     global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        $core_page=new Core_Page_Model();
        $user_manage    = new User_Manage_Model();
        date_default_timezone_set("Asia/Jakarta");
        $data=$db->select("AppPageListPageID,AppPageListParentID,AppPageListWebID,AppPageListPageName,AppPageListPageTitle,
        AppPageListPageController,AppPageListPageOrder,AppPageListPageProperties,AppPageListGroupID,AppPageListVisibility,
        AppPageListIcon","tbaapppagelist")
		->where("AppPageListPageID=$page_id")->get(0);//->orderby($order)
        if(!empty($data)){
            
            $controller_name = str_replace(" ", "_", ucwords(str_replace("_", " ", $data->AppPageListPageController)))."_Controller";
			$path            		= $dcistem->getOption("system/dir/controller").strtolower($controller_name).".php";
            $methodes= $user_manage->privileges($path,"");
            $html="<div class=\"col-lg-8\">";
            //$arr_rc=array();
			$j=0;
     		if(!empty($methodes)){
                while($pr=current($methodes)){
         			$ck=$pr['check']==true?" checked='checked' ":"";
         			$input  ="<input type=\"checkbox\" name=\"privileges[".$j."]\" value=\"".$pr['method']."\" $ck />";
                    $html   = trim($html)==""?$input." ".$pr['method']:$html."<br />".$input." ".$pr['method'];
     		        $html=($j+1)%5==0?$html."</div><div class=\"col-lg-8\">":$html;
    				$j++;
         			next($methodes);
         		}
            }
            $html=$html."</div>";
           
            echo " <div class=\"col-lg-17\" style=\"border:0px solid #999;\">".$html."</div>";   
       }
  } 
  public function validasiform($aksi="add",$kode_lama="") 
    {
        global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $msg=array();
      	$pesan=array();
    	if(trim($_POST['page_id'])==''){
            $pesan["page_name"]="page name harus diisi!";   
            $msg[]="page name harus diisi!";
        }
        if(trim($_POST['level_id'])==''){
            $pesan["level_id"]="Level harus diisi!";   
            $msg[]="Level harus diisi!";
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
        $hasil  = array();
        $pilihan=$kategori;//$aVars['pilih'];
        $nama=$aVars['search'];
        switch($pilihan){
            case "pagelist":
                $user_manage                = new  User_Manage_Model();
                $hasil=$user_manage->jsonData($pilihan,$nama,array("action"=>$aVars['action']));
            break;
        }
        echo json_encode($hasil);  
        exit;
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