<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Rekap_Populasi_Pertpk_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        global $dcistem;
       
	}
	
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("rekap_populasi_pertpk");
        $db   = $dcistem->getOption("framework/db");
        $login=new Adm_Login_Model();
        $master= new Master_Ref_Model();
        $KodeDosen=$_SESSION["framework"]["ref_id"] ;
        $Username=$_SESSION["framework"]["current_user"]->Username ;
       // $setings=$master->settings();
        
      //  echo "<pre>"; print_r($setings);echo "</pre>";
        
        $tpl->ListTPK= Model::getOptionList("mcp","id","name","id ASC"); 
        
        
        
        
       	$url_form = url::current("add");     
       	$TombolTambah=$login->privilegeInputForm("link","","btn-tambah-data","<i class=\"fa fa-fw fa-plus-circle\"></i>",$this->page->PageID,"add","title='Tambah Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_form."\" class=\"btn btn-primary btn-xs\" data-target=\"#largeModal\"");
       	$tpl->TombolTambah      = $TombolTambah; 
         $tpl->url_listdata      = url::current("listdata");
         $tpl->url_jsonData		= url::current("jsonData");
         $tpl->url_comboAjax=url::current("comboAjax");
         $tpl->url_export=url::current("Export");
		$this->tpl->content = $tpl;
		$this->tpl->render();        
    }
    public function listdata($action="tpk") {
 	    global $dcistem;
 	  	
       $db   = $dcistem->getOption("framework/db");
        $rekap   = new Adm_Rekap_Model();
        $login=new Adm_Login_Model();
        $master=new Master_Ref_Model();
        $modelsortir	= new Adm_Sortir_Model();
        $login_as = $_SESSION["framework"]["login_as"]; 
        $setings=$master->settings();
        
        $requestData= $_REQUEST;
        
        $tpk       = $requestData['columns'][0]['search']['value'];
        $alias    = $requestData['columns'][1]['search']['value'];
        
        if( trim($tpk)<>"" ){   //name
            $keriteria[]=" tpk_id='".$tpk."' ";
        }
        if(trim($action)=="tpk"){
             $judul="REKAP POPULASI BERDASARKAN TPK";
            $filter=$modelsortir->fromFormcari($keriteria,"and");
            $draw=$_REQUEST['draw'];
    		$length=$_REQUEST['length'];
    		$start=$_REQUEST['start'];
            
            $cols=array(0=>"tpk_id",
                        1=>"nama_tpk");
            $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
                
            $list_qry=$db->select("SQL_CALC_FOUND_ROWS tpk_id,nama_tpk,ifnull(jml_jd,0) jml_jantan_dewasa,ifnull(jml_induk,0) jml_induk,
            ifnull(jml_bm,0) jml_betina_muda,ifnull(jml_dara,0) jml_dara,
            ifnull(jml_pedet_btn,0) jml_pedet_btn,ifnull(jml_pedet_jtn,0) jml_pedet_jtn,
            ifnull(jml_undefined,0) jml_undefined","rekap_populasi_per_tpk")
    		->where($filter)->orderby($order)->lim($start,$length);
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
                $ListData[$i]['ID']=$data->tpk_id;
                $ListData[$i]['NamaTPK']=$data->nama_tpk;
                $ListData[$i]['JantanDewasa']=$data->jml_jantan_dewasa;
                $ListData[$i]['Induk']=$data->jml_induk;
                $ListData[$i]['BetinaMuda']=$data->jml_betina_muda;
                $ListData[$i]['Dara']=$data->jml_dara;
                $ListData[$i]['PedetBetina']=$data->jml_pedet_btn;
                $ListData[$i]['PedetJantan']=$data->jml_pedet_jtn;
                $ListData[$i]['Undefined']=$data->jml_undefined;
                
                $rekap_cow= $rekap->hitungPopulasi($data->jml_induk,$data->jml_dara,$data->jml_betina_muda,$data->jml_pedet_btn,$data->jml_pedet_jtn,$data->jml_jantan_dewasa);
                
                //$jumlah_populasi=(int)$data->jml_induk+(int)$data->jml_dara+(int)$data->jml_pedet_btn+(int)$data->jml_pedet_jtn+(int)$data->jml_undefined;;
                $ListData[$i]['Populasi']=$rekap_cow['populasi'];
               // $jumlah_sapi=$jumlah_populasi+(int)$data->jml_jantan_dewasa;
                $ListData[$i]['Sapi']=$rekap_cow['total_sapi']+(int)$data->jml_undefined;
                $i++;
                $no++;
            }
            $hasil['draw']=$draw;
            $hasil['title']=$judul;   
            $hasil['recordsTotal']=$jml_filtered;
            $hasil['recordsFiltered']=$jml_filtered;//$jml->jml_data;//
    	    $hasil['data']=$ListData;
       }//end rekap tpk
       if(trim($action)=="kelompok"){
         $judul="REKAP POPULASI BERDASARKAN KELOMPOK";
            $filter=$modelsortir->fromFormcari($keriteria,"and");
            $draw=$_REQUEST['draw'];
    		$length=$_REQUEST['length'];
    		$start=$_REQUEST['start'];
            
            $cols=array(0=>"tpk_id",
                        1=>"nama_tpk");
            $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
                
            $list_qry=$db->select("SQL_CALC_FOUND_ROWS kel_id,nama_kelompok,tpk_id,nama_tpk,ifnull(jml_jd,0) jml_jantan_dewasa,ifnull(jml_induk,0) jml_induk,
            ifnull(jml_bm,0) jml_betina_muda,ifnull(jml_dara,0) jml_dara,
            ifnull(jml_pedet_btn,0) jml_pedet_btn,ifnull(jml_pedet_jtn,0) jml_pedet_jtn,
            ifnull(jml_undefined,0) jml_undefined","rekap_populasi_per_kelompok")
    		->where($filter)->orderby("tpk_id asc, kel_id asc")->lim($start,$length);//->orderby($order)
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
                $ListData[$i]['KelompokID']=$data->kel_id;
                $ListData[$i]['KelompokNama']=$data->nama_kelompok;
                $ListData[$i]['TPKID']=$data->tpk_id;
                $ListData[$i]['NamaTPK']=$data->nama_tpk;
                $ListData[$i]['JantanDewasa']=$data->jml_jantan_dewasa;
                $ListData[$i]['Induk']=$data->jml_induk;
                $ListData[$i]['BetinaMuda']=$data->jml_betina_muda;
                $ListData[$i]['Dara']=$data->jml_dara;
                $ListData[$i]['PedetBetina']=$data->jml_pedet_btn;
                $ListData[$i]['PedetJantan']=$data->jml_pedet_jtn;
                $ListData[$i]['Undefined']=$data->jml_undefined;
                $jumlah_populasi=(int)$data->jml_induk+(int)$data->jml_dara+(int)$data->jml_pedet_btn+(int)$data->jml_pedet_jtn+(int)$data->jml_undefined;;
                $ListData[$i]['Populasi']=$jumlah_populasi;
                $jumlah_sapi=$jumlah_populasi+(int)$data->jml_jantan_dewasa;
                $ListData[$i]['Sapi']=$jumlah_sapi;
                $i++;
                $no++;
            }
            $hasil['draw']=$draw;
            $hasil['title']=$judul;   
            $hasil['recordsTotal']=$jml_filtered;
            $hasil['recordsFiltered']=$jml_filtered;//$jml->jml_data;//
    	    $hasil['data']=$ListData;
       }//end rekap kelompok
         //echo $hasil;
        echo json_encode($hasil);exit;
         // echo "<pre>"; print_r($hasil);echo "</pre>";
	}
 public function Export() {
	    global $dcistem;
	    $db       = $dcistem->getOption("framework/db");
	    $modelsortir	= new Adm_Sortir_Model();
	    $master=new Master_Ref_Model();
        $referensi      = $master->referensi_session();
	 	$admin=new Core_Admin_Model();
	 	$rekap   = new Adm_Rekap_Model();
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
	    	
	       
	
	        $excel->getProperties()->setCreator("KPBS-Pangalengan")
	            				   ->setLastModifiedBy("Hasan")
	            				   ->setTitle("Format Cetak")
	            				   ->setSubject("Format Cetak")
	            				   ->setDescription("Rekap Kegiatan")
	            				   ->setKeywords("Pelayanan");
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
	            ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'DDDDDD')
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
        	
            $tpk      	= $_POST['dw_tpk'];
                
			$judul1="REKAP POPULASI BERDASARKAN TPK";
            if( trim($tpk)<>"" ){   //name
                $keriteria[]="tpk_id ='".$tpk."'";
                $judul2="TPK  ".$tpk;
            } 
            $filter=$modelsortir->fromFormcari($keriteria,"and");
            $excel->createSheet();
            $excel->setActiveSheetIndex(0)->mergeCells('A2:K2')->setCellValue('A2', $judul1);
            $excel->setActiveSheetIndex(0)->mergeCells('A3:K3')->setCellValue('A3', $judul2);
            $excel->getActiveSheet()->getStyle('A2')->applyFromArray($style_judul);
            $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_judul);
            $key=30;
            $excel->setActiveSheetIndex(0)->mergeCells($a[0].'4:'.$a[0].'5')->setCellValue($a[0].'4', 'ID')
                ->mergeCells($a[1].'4:'.$a[1].'5')->setCellValue($a[1].'4', 'TPK')
              	->mergeCells($a[2].'4:'.$a[8].'4')->setCellValue($a[2].'4', 'REKAP')
             	->setCellValue($a[2].'5', 'Induk')
                ->setCellValue($a[3].'5', 'Dara')
                ->setCellValue($a[4].'5', 'Betina Muda')
                ->setCellValue($a[5].'5', 'Pedet Betina')
                ->setCellValue($a[6].'5', 'Pedet Jantan')
                ->setCellValue($a[7].'5', 'Jantan Dewasa')
                ->setCellValue($a[8].'5', 'Undefined')
                ->mergeCells($a[9].'4:'.$a[10].'4')->setCellValue($a[9].'4', 'Jumlah')
                ->setCellValue($a[9].'5', 'Populasi')
                ->setCellValue($a[10].'5', 'Sapi');
            $excel->getActiveSheet()->getStyle($a[0]."4")->applyFromArray($style_header);
            $excel->getActiveSheet()->getColumnDimension($a[0])->setWidth(5);
            $excel->getActiveSheet()->getStyle($a[0]."5")->applyFromArray($style_header);
            $excel->getActiveSheet()->getStyle($a[1]."4")->applyFromArray($style_header);
            $excel->getActiveSheet()->getColumnDimension($a[1])->setWidth(30);
            $excel->getActiveSheet()->getStyle($a[1]."5")->applyFromArray($style_header);
            $excel->getActiveSheet()->getStyle($a[2].'4:'.$a[10].'4')->applyFromArray($style_header);//
            $excel->getActiveSheet()->getStyle($a[2]."5")->applyFromArray($style_header);
            $excel->getActiveSheet()->getColumnDimension($a[2])->setWidth(10);
            $excel->getActiveSheet()->getStyle($a[3]."5")->applyFromArray($style_header);
            $excel->getActiveSheet()->getColumnDimension($a[3])->setWidth(10);
            $excel->getActiveSheet()->getStyle($a[4]."5")->applyFromArray($style_header);
            $excel->getActiveSheet()->getColumnDimension($a[4])->setWidth(10);
            $excel->getActiveSheet()->getStyle($a[5]."5")->applyFromArray($style_header);
            $excel->getActiveSheet()->getColumnDimension($a[5])->setWidth(10);
            $excel->getActiveSheet()->getStyle($a[6]."5")->applyFromArray($style_header);
            $excel->getActiveSheet()->getColumnDimension($a[6])->setWidth(10);
            $excel->getActiveSheet()->getStyle($a[7]."5")->applyFromArray($style_header);
            $excel->getActiveSheet()->getColumnDimension($a[7])->setWidth(10);
            $excel->getActiveSheet()->getStyle($a[8]."5")->applyFromArray($style_header);
            $excel->getActiveSheet()->getColumnDimension($a[8])->setWidth(11);
            $excel->getActiveSheet()->getStyle($a[9]."5")->applyFromArray($style_header);
            $excel->getActiveSheet()->getColumnDimension($a[9])->setWidth(10);
            $excel->getActiveSheet()->getStyle($a[10]."5")->applyFromArray($style_header);
            $excel->getActiveSheet()->getColumnDimension($a[10])->setWidth(10);
            
                
            $list_qry=$db->select("tpk_id,nama_tpk,ifnull(jml_jd,0) jml_jantan_dewasa,ifnull(jml_induk,0) jml_induk,
            ifnull(jml_bm,0) jml_betina_muda,ifnull(jml_dara,0) jml_dara,
            ifnull(jml_pedet_btn,0) jml_pedet_btn,ifnull(jml_pedet_jtn,0) jml_pedet_jtn,
            ifnull(jml_undefined,0) jml_undefined","rekap_populasi_per_tpk")
    		->where($filter)->orderby($order)->lim();
            $i=6;
            $no=1;
            $awal=$i;
            while($data1 = $db->fetchArray($list_qry))
            {
                $jumlah_populasi=(int)$data1['jml_induk']+(int)$data1['jml_dara']+(int)$data1['jml_betina_muda']+(int)$data1['jml_pedet_btn']+(int)$data1['jml_pedet_jtn'];;
               
                $jumlah_sapi=$jumlah_populasi+(int)$data1['jml_jantan_dewasa']+(int)$data1['jml_undefined'];
                
            	$excel->setActiveSheetIndex(0)
                  	->setCellValue($a[0].$i, $no)
                   	->setCellValueExplicit($a[1].$i,$data1['nama_tpk'], PHPExcel_Cell_DataType::TYPE_STRING)
                  	->setCellValue($a[2].$i,$data1['jml_induk'])
                  	->setCellValue($a[3].$i,$data1['jml_dara'])
                    ->setCellValue($a[4].$i,$data1['jml_betina_muda'])
                    ->setCellValue($a[5].$i,$data1['jml_pedet_btn'])
                    ->setCellValue($a[6].$i,$data1['jml_pedet_jtn'])
                    ->setCellValue($a[7].$i,$data1['jml_jantan_dewasa'])
                    ->setCellValue($a[8].$i,$data1['jml_undefined'])
                    ->setCellValue($a[9].$i,$jumlah_populasi)
                    ->setCellValue($a[10].$i,$jumlah_sapi);
		        $i++;  
                $no++;  
            }
            $akhir=$i-1;
            $excel->setActiveSheetIndex(0)->setCellValue($a[0].$i, "")
               	->mergeCells($a[0].$i.':'.$a[1].$i)->setCellValueExplicit($a[0].$i,"Total ", PHPExcel_Cell_DataType::TYPE_STRING)
              	->setCellValue($a[2].$i,(string)"=SUM(".$a[2].$awal.":".$a[2].$akhir.")")
                ->setCellValue($a[3].$i,(string)"=SUM(".$a[3].$awal.":".$a[3].$akhir.")")
              	->setCellValue($a[4].$i,(string)"=SUM(".$a[4].$awal.":".$a[4].$akhir.")")
                ->setCellValue($a[5].$i,(string)"=SUM(".$a[5].$awal.":".$a[5].$akhir.")")                               
                ->setCellValue($a[6].$i,(string)"=SUM(".$a[6].$awal.":".$a[6].$akhir.")")
                ->setCellValue($a[7].$i,(string)"=SUM(".$a[7].$awal.":".$a[7].$akhir.")")
                ->setCellValue($a[8].$i,(string)"=SUM(".$a[8].$awal.":".$a[8].$akhir.")")
                ->setCellValue($a[9].$i,(string)"=SUM(".$a[9].$awal.":".$a[9].$akhir.")")
                ->setCellValue($a[10].$i,(string)"=SUM(".$a[10].$awal.":".$a[10].$akhir.")");
            
            $excel->getActiveSheet(0)->setTitle('Rekap Populasi Per TPK');
            $excel->setActiveSheetIndex(0);
            
            $excel->createSheet();
            
            $excel->setActiveSheetIndex(1)->mergeCells('A2:K2')->setCellValue('A2', "test");
            $excel->setActiveSheetIndex(1)->mergeCells('A3:K3')->setCellValue('A3',"" );
            
           
            
	    
                $judul3="REKAP POPULASI BERDASARKAN KELOMPOK";
                
            
                $excel->setActiveSheetIndex(1)->mergeCells('A2:L2')->setCellValue('A2', $judul3);
                $excel->setActiveSheetIndex(1)->mergeCells('A3:L3')->setCellValue('A3', $judul2);
                $excel->getActiveSheet()->getStyle('A2')->applyFromArray($style_judul);
                $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_judul);
                $key=30;
                $excel->setActiveSheetIndex(1)->mergeCells($a[0].'4:'.$a[0].'5')->setCellValue($a[0].'4', "No.")
                  	->mergeCells($a[1].'4:'.$a[1].'5')->setCellValue($a[1].'4', 'TPK')
                    ->mergeCells($a[2].'4:'.$a[2].'5')->setCellValue($a[2].'4', 'Kelompok')
                  	->mergeCells($a[3].'4:'.$a[9].'4')->setCellValue($a[3].'4', 'Rekap')
                    ->setCellValue($a[3].'5', 'Induk')
                    ->setCellValue($a[4].'5', 'Dara')
                    ->setCellValue($a[5].'5', 'Betina Muda')
                    ->setCellValue($a[6].'5', 'Pedet Betina')
                    ->setCellValue($a[7].'5', 'Pedet Jantan')
                    ->setCellValue($a[8].'5', 'Jantan Dewasa')
                    ->setCellValue($a[9].'5', 'Undefined')
                    ->mergeCells($a[10].'4:'.$a[11].'4')->setCellValue($a[10].'4', 'Jumlah')
                    ->setCellValue($a[10].'5', 'Populasi')
                    ->setCellValue($a[11].'5', 'Sapi');
                
                $excel->getActiveSheet()->getStyle($a[0]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[0])->setWidth(5);
                $excel->getActiveSheet()->getStyle($a[0]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getStyle($a[1]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[1])->setWidth(30);
                $excel->getActiveSheet()->getStyle($a[1]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getStyle($a[2].'4:'.$a[11].'4')->applyFromArray($style_header);//
                $excel->getActiveSheet()->getStyle($a[2]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[2])->setWidth(30);
                $excel->getActiveSheet()->getStyle($a[3]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[3])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[4]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[4])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[5]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[5])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[6]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[6])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[7]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[7])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[8]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[8])->setWidth(11);
                $excel->getActiveSheet()->getStyle($a[9]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[9])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[10]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[10])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[11]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[11])->setWidth(9);
            $list_qry2=$db->select("kel_id,nama_kelompok,tpk_id,nama_tpk,ifnull(jml_jd,0) jml_jantan_dewasa,ifnull(jml_induk,0) jml_induk,
                ifnull(jml_bm,0) jml_betina_muda,ifnull(jml_dara,0) jml_dara,
                ifnull(jml_pedet_btn,0) jml_pedet_btn,ifnull(jml_pedet_jtn,0) jml_pedet_jtn,
                ifnull(jml_undefined,0) jml_undefined","rekap_populasi_per_kelompok")
        		->where($filter)->orderby("tpk_id asc, kel_id asc")->lim();//->orderby($order)
            $i=6;
            $no=1;
            $awal=$i;
            $tmp_tpk="";
            $row_span=1;
            $pengurang  =array();
            $first=0;
            $index_row_span=0;
           // $span   = array();
            $span[$index_row_span]=1;
            while($data = $db->fetchArray($list_qry2))
            {
                $jumlah_populasi=(int)$data['jml_induk']+(int)$data['jml_dara']+(int)$data['jml_betina_muda']+(int)$data['jml_pedet_btn']+(int)$data['jml_pedet_jtn'];;
                $jumlah_sapi=$jumlah_populasi+(int)$data['jml_jantan_dewasa']+(int)$data['jml_undefined'];
                if(trim($tmp_tpk)<>trim($data['tpk_id'])){
                    $first=$i;//harus paling atas
                    if($i<>$awal){
                        $excel->setActiveSheetIndex(1)->setCellValue($a[0].$i, "")
	                       	->mergeCells($a[1].$i.':'.$a[2].$i)->setCellValueExplicit($a[1].$i,"Sub-Total ", PHPExcel_Cell_DataType::TYPE_STRING)
	                      	->setCellValue($a[3].$i,(string)"=SUM(".$a[3].($i-$row_span).":".$a[3].($i-1).")")
                              ->setCellValue($a[4].$i,(string)"=SUM(".$a[4].($i-$row_span).":".$a[4].($i-1).")")
                            ->setCellValue($a[5].$i,(string)"=SUM(".$a[5].($i-$row_span).":".$a[5].($i-1).")")                               
                            ->setCellValue($a[6].$i,(string)"=SUM(".$a[6].($i-$row_span).":".$a[6].($i-1).")")
                            ->setCellValue($a[7].$i,(string)"=SUM(".$a[7].($i-$row_span).":".$a[7].($i-1).")")
                            ->setCellValue($a[8].$i,(string)"=SUM(".$a[8].($i-$row_span).":".$a[8].($i-1).")")
                            ->setCellValue($a[9].$i,(string)"=SUM(".$a[9].($i-$row_span).":".$a[9].($i-1).")")
                            ->setCellValue($a[10].$i,(string)"=SUM(".$a[10].($i-$row_span).":".$a[10].($i-1).")")
                            ->setCellValue($a[11].$i,(string)"=SUM(".$a[11].($i-$row_span).":".$a[11].($i-1).")");
                            
                            $pengurang[3]=trim($pengurang[3])<>""?$pengurang[3]."+".$a[3].$i:$a[3].$i;
                            $pengurang[4]=trim($pengurang[4])<>""?$pengurang[4]."+".$a[4].$i:$a[4].$i;
                            $pengurang[5]=trim($pengurang[5])<>""?$pengurang[5]."+".$a[5].$i:$a[5].$i;
                            $pengurang[6]=trim($pengurang[6])<>""?$pengurang[6]."+".$a[6].$i:$a[6].$i;
                            $pengurang[7]=trim($pengurang[7])<>""?$pengurang[7]."+".$a[7].$i:$a[7].$i;
                            $pengurang[8]=trim($pengurang[8])<>""?$pengurang[8]."+".$a[8].$i:$a[8].$i;
                            $pengurang[9]=trim($pengurang[9])<>""?$pengurang[9]."+".$a[9].$i:$a[9].$i;
                            $pengurang[10]=trim($pengurang[10])<>""?$pengurang[10]."+".$a[10].$i:$a[10].$i;
                            $pengurang[11]=trim($pengurang[11])<>""?$pengurang[11]."+".$a[11].$i:$a[11].$i;
                            $i++;
                    }
                    
                    
                     $excel->setActiveSheetIndex(1)->setCellValue($a[0].$i,$no)
	                       	->setCellValueExplicit($a[1].$i,$data['nama_tpk'].$row_span, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValueExplicit($a[2].$i,$data['nama_kelompok'], PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValue($a[3].$i,$data['jml_induk'])
                          	->setCellValue($a[4].$i,$data['jml_dara'])
                            ->setCellValue($a[5].$i,$data['jml_betina_muda'])
                            ->setCellValue($a[6].$i,$data['jml_pedet_btn'])
                            ->setCellValue($a[7].$i,$data['jml_pedet_jtn'])
                            ->setCellValue($a[8].$i,$data['jml_jantan_dewasa'])
                            ->setCellValue($a[9].$i,$data['jml_undefined'])
                             ->setCellValue($a[10].$i,$jumlah_populasi)
                            ->setCellValue($a[11].$i,$jumlah_sapi);
                     
                      $tmp_tpk=$data['tpk_id']; 
                      $row_span=1;  
                      $span[$index_row_span]=1;
                      $index_row_span++;
                      $no++;
                         
                }else{
                    $row_span++;
                    $span[$index_row_span]=$row_span;
                    if($first==$awal){
                        $excel->setActiveSheetIndex(1)->mergeCells($a[0].$first.':'.$a[0].($first+$row_span-1))->setCellValue($a[0].$first,$no)
	                       	->mergeCells($a[1].$first.':'.$a[1].($first+$row_span-1))->setCellValueExplicit($a[1].$first,$data['nama_tpk'].$row_span, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValueExplicit($a[2].$i,$data['nama_kelompok'], PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValue($a[3].$i,$data['jml_induk'])
                      	->setCellValue($a[4].$i,$data['jml_dara'])
                        ->setCellValue($a[5].$i,$data['jml_betina_muda'])
                        ->setCellValue($a[6].$i,$data['jml_pedet_btn'])
                        ->setCellValue($a[7].$i,$data['jml_pedet_jtn'])
                        ->setCellValue($a[8].$i,$data['jml_jantan_dewasa'])
                        ->setCellValue($a[9].$i,$data['jml_undefined'])
                         ->setCellValue($a[10].$i,$jumlah_populasi)
                        ->setCellValue($a[11].$i,$jumlah_sapi);
                            ;
                    }else{
                    
                    
                    
                        $excel->setActiveSheetIndex(1)
                                    ->setCellValueExplicit($a[2].$i,$data['nama_kelompok'], PHPExcel_Cell_DataType::TYPE_STRING)
                                    ->setCellValue($a[3].$i,$data['jml_induk'])
                      	->setCellValue($a[4].$i,$data['jml_dara'])
                        ->setCellValue($a[5].$i,$data['jml_betina_muda'])
                        ->setCellValue($a[6].$i,$data['jml_pedet_btn'])
                        ->setCellValue($a[7].$i,$data['jml_pedet_jtn'])
                        ->setCellValue($a[8].$i,$data['jml_jantan_dewasa'])
                        ->setCellValue($a[9].$i,$data['jml_undefined'])
                         ->setCellValue($a[10].$i,$jumlah_populasi)
                         ->setCellValue($a[11].$i,$jumlah_sapi);;
                        
                    }
                }
                
                
                
                
                $i++;   
                
                
            }
          //  echo "<pre>";print_r($span);echo "</pre>";exit;
            $akhir=$i;
              $excel->setActiveSheetIndex(1)->setCellValue($a[0].$i, "")
               	->mergeCells($a[1].$i.':'.$a[2].$i)->setCellValueExplicit($a[1].$i,"Sub-Total ", PHPExcel_Cell_DataType::TYPE_STRING)
              	->setCellValue($a[3].$i,(string)"=SUM(".$a[3].($i-$row_span).":".$a[3].($i-1).")")
                  ->setCellValue($a[4].$i,(string)"=SUM(".$a[4].($i-$row_span).":".$a[4].($i-1).")")
                ->setCellValue($a[5].$i,(string)"=SUM(".$a[5].($i-$row_span).":".$a[5].($i-1).")")                               
                ->setCellValue($a[6].$i,(string)"=SUM(".$a[6].($i-$row_span).":".$a[6].($i-1).")")
                ->setCellValue($a[7].$i,(string)"=SUM(".$a[7].($i-$row_span).":".$a[7].($i-1).")")
                ->setCellValue($a[8].$i,(string)"=SUM(".$a[8].($i-$row_span).":".$a[8].($i-1).")")
                ->setCellValue($a[9].$i,(string)"=SUM(".$a[9].($i-$row_span).":".$a[9].($i-1).")")
                ->setCellValue($a[10].$i,(string)"=SUM(".$a[10].($i-$row_span).":".$a[10].($i-1).")")
                ->setCellValue($a[11].$i,(string)"=SUM(".$a[11].($i-$row_span).":".$a[11].($i-1).")");
                
                $pengurang[3]=trim($pengurang[3])<>""?$pengurang[3]."+".$a[3].$i:$a[3].$i;
                $pengurang[4]=trim($pengurang[4])<>""?$pengurang[4]."+".$a[4].$i:$a[4].$i;
                $pengurang[5]=trim($pengurang[5])<>""?$pengurang[5]."+".$a[5].$i:$a[5].$i;
                $pengurang[6]=trim($pengurang[6])<>""?$pengurang[6]."+".$a[6].$i:$a[6].$i;
                $pengurang[7]=trim($pengurang[7])<>""?$pengurang[7]."+".$a[7].$i:$a[7].$i;
                $pengurang[8]=trim($pengurang[8])<>""?$pengurang[8]."+".$a[8].$i:$a[8].$i;
                $pengurang[9]=trim($pengurang[9])<>""?$pengurang[9]."+".$a[9].$i:$a[9].$i;
                $pengurang[10]=trim($pengurang[10])<>""?$pengurang[10]."+".$a[10].$i:$a[10].$i;
                $pengurang[11]=trim($pengurang[11])<>""?$pengurang[11]."+".$a[11].$i:$a[11].$i;  
                
                $i++;
                
                 $excel->setActiveSheetIndex(1)
               	->mergeCells($a[0].$i.':'.$a[2].$i)->setCellValueExplicit($a[0].$i,"Grand Total ", PHPExcel_Cell_DataType::TYPE_STRING)
              	->setCellValue($a[3].$i,(string)"=SUM(".$a[3].$awal.":".$a[3].$akhir.")-(".$pengurang[3].")")
              	->setCellValue($a[4].$i,(string)"=SUM(".$a[4].$awal.":".$a[4].$akhir.")-(".$pengurang[4].")")
                ->setCellValue($a[5].$i,(string)"=SUM(".$a[5].$awal.":".$a[5].$akhir.")-(".$pengurang[5].")")                               
                ->setCellValue($a[6].$i,(string)"=SUM(".$a[6].$awal.":".$a[6].$akhir.")-(".$pengurang[6].")")
                ->setCellValue($a[7].$i,(string)"=SUM(".$a[7].$awal.":".$a[7].$akhir.")-(".$pengurang[7].")")
                ->setCellValue($a[8].$i,(string)"=SUM(".$a[8].$awal.":".$a[8].$akhir.")-(".$pengurang[8].")")
                ->setCellValue($a[9].$i,(string)"=SUM(".$a[9].$awal.":".$a[9].$akhir.")-(".$pengurang[9].")")
                ->setCellValue($a[10].$i,(string)"=SUM(".$a[10].$awal.":".$a[10].$akhir.")-(".$pengurang[10].")")
                ->setCellValue($a[11].$i,(string)"=SUM(".$a[11].$awal.":".$a[11].$akhir.")-(".$pengurang[11].")");
          /*  
	           $ListRekap=$rekap->getRekapPengobatanByKasus($tahun,$bulan);
	         // echo "<pre>";print_r($ListRekap);echo "</pre>";exit;
	        
	        	$i=6;
	        	if (count($ListRekap['data'])) {
					$no=1;
					$awal       =$i;
                    $row_span   =0;
                    $sub_sistem = array();
                    $first      = 0;
                    $pengurang  =array();
					while($data = current($ListRekap['data'])) {
					 	   $row_span=$ListRekap['jumlah_row'][$data['SubID']];
                           
                           if($data['first']==true){
                                $sub_sistem[$first]=$data['SubSistem'];
                                
                                
                                if($i<>$awal){
                                    //echo $first;
                                    $excel->setActiveSheetIndex(0)->setCellValue($a[0].$i, "")
    		                       	->mergeCells($a[0].$i.':'.$a[2].$i)->setCellValueExplicit($a[0].$i,"Sub-Total ".$sub_sistem[($first-1)], PHPExcel_Cell_DataType::TYPE_STRING)
    		                      	->setCellValue($a[3].$i,(string)"=SUM(".$a[3].($i-$row_span).":".$a[3].($i-1).")")
    		                      	->setCellValue($a[4].$i,(string)"=SUM(".$a[4].($i-$row_span).":".$a[4].($i-1).")")
                                    ->setCellValue($a[5].$i,(string)"=SUM(".$a[5].($i-$row_span).":".$a[5].($i-1).")")                               
                                    ->setCellValue($a[6].$i,(string)"=SUM(".$a[6].($i-$row_span).":".$a[6].($i-1).")")
                                    ->setCellValue($a[7].$i,(string)"=SUM(".$a[7].($i-$row_span).":".$a[7].($i-1).")")
                                    ->setCellValue($a[8].$i,(string)"=SUM(".$a[8].($i-$row_span).":".$a[8].($i-1).")")
                                    ->setCellValue($a[9].$i,(string)"=SUM(".$a[9].($i-$row_span).":".$a[9].($i-1).")")
                                    ->setCellValue($a[10].$i,(string)"=SUM(".$a[10].($i-$row_span).":".$a[10].($i-1).")")
                                    ->setCellValue($a[11].$i,(string)"=SUM(".$a[11].($i-$row_span).":".$a[11].($i-1).")")
                                    ->setCellValue($a[12].$i,(string)"=SUM(".$a[12].($i-$row_span).":".$a[12].($i-1).")")
                                    ->setCellValue($a[13].$i,(string)"=SUM(".$a[13].($i-$row_span).":".$a[13].($i-1).")")
                                    ->setCellValue($a[14].$i,(string)"=SUM(".$a[14].($i-$row_span).":".$a[14].($i-1).")")
                                    ->setCellValue($a[15].$i,(string)"=SUM(".$a[15].($i-$row_span).":".$a[15].($i-1).")");
                                    
                                    $pengurang[3]=trim($pengurang[3])<>""?$pengurang[3]."+".$a[3].$i:$a[3].$i;
                                    $pengurang[4]=trim($pengurang[4])<>""?$pengurang[4]."+".$a[4].$i:$a[4].$i;
                                    $pengurang[5]=trim($pengurang[5])<>""?$pengurang[5]."+".$a[5].$i:$a[5].$i;
                                    $pengurang[6]=trim($pengurang[6])<>""?$pengurang[6]."+".$a[6].$i:$a[6].$i;
                                    $pengurang[7]=trim($pengurang[7])<>""?$pengurang[7]."+".$a[7].$i:$a[7].$i;
                                    $pengurang[8]=trim($pengurang[8])<>""?$pengurang[8]."+".$a[8].$i:$a[8].$i;
                                    $pengurang[9]=trim($pengurang[9])<>""?$pengurang[9]."+".$a[9].$i:$a[9].$i;
                                    $pengurang[10]=trim($pengurang[10])<>""?$pengurang[10]."+".$a[10].$i:$a[10].$i;
                                    $pengurang[11]=trim($pengurang[11])<>""?$pengurang[11]."+".$a[11].$i:$a[11].$i;
                                    $pengurang[12]=trim($pengurang[12])<>""?$pengurang[12]."+".$a[12].$i:$a[12].$i;
                                    $pengurang[13]=trim($pengurang[13])<>""?$pengurang[13]."+".$a[13].$i:$a[13].$i;
                                    $pengurang[14]=trim($pengurang[14])<>""?$pengurang[14]."+".$a[14].$i:$a[14].$i;
                                    $pengurang[15]=trim($pengurang[15])<>""?$pengurang[15]."+".$a[15].$i:$a[15].$i;
                                    
                                    $excel->getActiveSheet()->getStyle($a[0].$i)->applyFromArray($style_header1)
					                   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                                   
                                    $excel->getActiveSheet()->getStyle($a[3].$i)->applyFromArray($style_header1)
        							   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        							   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                    $excel->getActiveSheet()->getStyle($a[4].$i)->applyFromArray($style_header1)
        							   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        							   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                    $excel->getActiveSheet()->getStyle($a[5].$i)->applyFromArray($style_header1)
        							   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        							   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                    $excel->getActiveSheet()->getStyle($a[6].$i)->applyFromArray($style_header1)
        							   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        							   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                    $excel->getActiveSheet()->getStyle($a[7].$i)->applyFromArray($style_header1)
        							   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        							   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                    $excel->getActiveSheet()->getStyle($a[8].$i)->applyFromArray($style_header1)
        							   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        							   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                    $excel->getActiveSheet()->getStyle($a[9].$i)->applyFromArray($style_header1)
        							   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        							   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                    $excel->getActiveSheet()->getStyle($a[10].$i)->applyFromArray($style_header1)
        							   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        							   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                    $excel->getActiveSheet()->getStyle($a[11].$i)->applyFromArray($style_header1)
        							   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        							   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                    $excel->getActiveSheet()->getStyle($a[12].$i)->applyFromArray($style_header1)
        							   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        							   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                    $excel->getActiveSheet()->getStyle($a[13].$i)->applyFromArray($style_header1)
        							   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        							   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                    $excel->getActiveSheet()->getStyle($a[14].$i)->applyFromArray($style_header1)
        							   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        							   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                    $excel->getActiveSheet()->getStyle($a[15].$i)->applyFromArray($style_header1)
        							   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        							   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                                       
                                       
                                    $i++;
                                    
                                     $excel->getActiveSheet()->getCell($a[3].$i)->getValue();
                                     $excel->getActiveSheet()->getCell($a[4].$i)->getValue();
                                     $excel->getActiveSheet()->getCell($a[5].$i)->getValue();
                                     $excel->getActiveSheet()->getCell($a[6].$i)->getValue();
                                     $excel->getActiveSheet()->getCell($a[7].$i)->getValue();
                                     $excel->getActiveSheet()->getCell($a[8].$i)->getValue();
                                     $excel->getActiveSheet()->getCell($a[9].$i)->getValue();
                                     $excel->getActiveSheet()->getCell($a[10].$i)->getValue();
                                     $excel->getActiveSheet()->getCell($a[11].$i)->getValue();
                                     $excel->getActiveSheet()->getCell($a[12].$i)->getValue();
                                     $excel->getActiveSheet()->getCell($a[13].$i)->getValue();
                                     $excel->getActiveSheet()->getCell($a[14].$i)->getValue();
                                     $excel->getActiveSheet()->getCell($a[15].$i)->getValue();
                                     
                                     
                                }
                                $first++;
                                $excel->setActiveSheetIndex(0)->mergeCells($a[0].$i.':'.$a[0].($i+$row_span-1))->setCellValue($a[0].$i,$data['SubID'])
		                       	->mergeCells($a[1].$i.':'.$a[1].($i+$row_span-1))->setCellValueExplicit($a[1].$i,$data['SubSistem'], PHPExcel_Cell_DataType::TYPE_STRING)
                                ->setCellValueExplicit($a[2].$i,$data['Kasus'], PHPExcel_Cell_DataType::TYPE_STRING)
		                      		->setCellValue($a[3].$i,$data['jml_jan'])
		                      	->setCellValue($a[4].$i,$data['jml_feb'])
                                ->setCellValue($a[5].$i,$data['jml_mar'])                               
                                ->setCellValue($a[6].$i,$data['jml_apr'])
                                ->setCellValue($a[7].$i,$data['jml_mei'])
                                ->setCellValue($a[8].$i,$data['jml_jun'])
                                ->setCellValue($a[9].$i,$data['jml_jul'])
                                ->setCellValue($a[10].$i,$data['jml_agu'])
                                ->setCellValue($a[11].$i,$data['jml_sep'])
                                ->setCellValue($a[12].$i,$data['jml_okt'])
                                ->setCellValue($a[13].$i,$data['jml_nop'])
                                ->setCellValue($a[14].$i,$data['jml_des'])
                                ->setCellValue($a[15].$i,$data['sub_total']);
                         }else{
                            $excel->setActiveSheetIndex(0)->setCellValue($a[0].$i, $no)
                                ->setCellValueExplicit($a[2].$i,$data['Kasus'], PHPExcel_Cell_DataType::TYPE_STRING)
		                      	->setCellValue($a[3].$i,$data['jml_jan'])
		                      	->setCellValue($a[4].$i,$data['jml_feb'])
                                ->setCellValue($a[5].$i,$data['jml_mar'])                               
                                ->setCellValue($a[6].$i,$data['jml_apr'])
                                ->setCellValue($a[7].$i,$data['jml_mei'])
                                ->setCellValue($a[8].$i,$data['jml_jun'])
                                ->setCellValue($a[9].$i,$data['jml_jul'])
                                ->setCellValue($a[10].$i,$data['jml_agu'])
                                ->setCellValue($a[11].$i,$data['jml_sep'])
                                ->setCellValue($a[12].$i,$data['jml_okt'])
                                ->setCellValue($a[13].$i,$data['jml_nop'])
                                ->setCellValue($a[14].$i,$data['jml_des'])
                                ->setCellValue($a[15].$i,$data['sub_total']);
                         }
		                      
		                $excel->getActiveSheet()->getStyle($a[0].$i)->applyFromArray($style_row)
							   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
							   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
					  	$excel->getActiveSheet()->getStyle($a[1].$i)->applyFromArray($style_row)
							   ->getAlignment()->setWrapText(true)->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);;
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
					  	    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $excel->getActiveSheet()->getStyle($a[6].$i)->applyFromArray($style_row)
					  	    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $excel->getActiveSheet()->getStyle($a[7].$i)->applyFromArray($style_row)
					  	    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $excel->getActiveSheet()->getStyle($a[8].$i)->applyFromArray($style_row)
					  	    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $excel->getActiveSheet()->getStyle($a[9].$i)->applyFromArray($style_row)
					  	    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $excel->getActiveSheet()->getStyle($a[10].$i)->applyFromArray($style_row)
					  	    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $excel->getActiveSheet()->getStyle($a[11].$i)->applyFromArray($style_row)
					  	    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $excel->getActiveSheet()->getStyle($a[12].$i)->applyFromArray($style_row)
					  	    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $excel->getActiveSheet()->getStyle($a[13].$i)->applyFromArray($style_row)
						   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $excel->getActiveSheet()->getStyle($a[14].$i)->applyFromArray($style_row)
						   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $excel->getActiveSheet()->getStyle($a[15].$i)->applyFromArray($style_row)
						   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		                     
							  
		                $i++;   
		                $no++;
						next($ListRekap['data']);
					}
                    //echo $first;
                    
                    $excel->setActiveSheetIndex(0)
                   	->mergeCells($a[0].$i.':'.$a[2].$i)->setCellValueExplicit($a[0].$i,"Sub-Total ".$sub_sistem[$first-1], PHPExcel_Cell_DataType::TYPE_STRING)
                  	->setCellValue($a[3].$i,(string)"=SUM(".$a[3].($i-$row_span).":".$a[3].($i-1).")")
                  	->setCellValue($a[4].$i,(string)"=SUM(".$a[4].($i-$row_span).":".$a[4].($i-1).")")
                    ->setCellValue($a[5].$i,(string)"=SUM(".$a[5].($i-$row_span).":".$a[5].($i-1).")")                               
                    ->setCellValue($a[6].$i,(string)"=SUM(".$a[6].($i-$row_span).":".$a[6].($i-1).")")
                    ->setCellValue($a[7].$i,(string)"=SUM(".$a[7].($i-$row_span).":".$a[7].($i-1).")")
                    ->setCellValue($a[8].$i,(string)"=SUM(".$a[8].($i-$row_span).":".$a[8].($i-1).")")
                    ->setCellValue($a[9].$i,(string)"=SUM(".$a[9].($i-$row_span).":".$a[9].($i-1).")")
                    ->setCellValue($a[10].$i,(string)"=SUM(".$a[10].($i-$row_span).":".$a[10].($i-1).")")
                    ->setCellValue($a[11].$i,(string)"=SUM(".$a[11].($i-$row_span).":".$a[11].($i-1).")")
                    ->setCellValue($a[12].$i,(string)"=SUM(".$a[12].($i-$row_span).":".$a[12].($i-1).")")
                    ->setCellValue($a[13].$i,(string)"=SUM(".$a[13].($i-$row_span).":".$a[13].($i-1).")")
                    ->setCellValue($a[14].$i,(string)"=SUM(".$a[14].($i-$row_span).":".$a[14].($i-1).")")
                    ->setCellValue($a[15].$i,(string)"=SUM(".$a[15].($i-$row_span).":".$a[15].($i-1).")");
                    
                    $pengurang[3]=trim($pengurang[3])<>""?$pengurang[3]."+".$a[3].$i:$a[3].$i;
                    $pengurang[4]=trim($pengurang[4])<>""?$pengurang[4]."+".$a[4].$i:$a[4].$i;
                    $pengurang[5]=trim($pengurang[5])<>""?$pengurang[5]."+".$a[5].$i:$a[5].$i;
                    $pengurang[6]=trim($pengurang[6])<>""?$pengurang[6]."+".$a[6].$i:$a[6].$i;
                    $pengurang[7]=trim($pengurang[7])<>""?$pengurang[7]."+".$a[7].$i:$a[7].$i;
                    $pengurang[8]=trim($pengurang[8])<>""?$pengurang[8]."+".$a[8].$i:$a[8].$i;
                    $pengurang[9]=trim($pengurang[9])<>""?$pengurang[9]."+".$a[9].$i:$a[9].$i;
                    $pengurang[10]=trim($pengurang[10])<>""?$pengurang[10]."+".$a[10].$i:$a[10].$i;
                    $pengurang[11]=trim($pengurang[11])<>""?$pengurang[11]."+".$a[11].$i:$a[11].$i;
                    $pengurang[12]=trim($pengurang[12])<>""?$pengurang[12]."+".$a[12].$i:$a[12].$i;
                    $pengurang[13]=trim($pengurang[13])<>""?$pengurang[13]."+".$a[13].$i:$a[13].$i;
                    $pengurang[14]=trim($pengurang[14])<>""?$pengurang[14]."+".$a[14].$i:$a[14].$i;
                    $pengurang[15]=trim($pengurang[15])<>""?$pengurang[15]."+".$a[15].$i:$a[15].$i;
                    
                    $excel->getActiveSheet()->getStyle($a[0].$i)->applyFromArray($style_header1)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                     $excel->getActiveSheet()->getStyle($a[1].$i)->applyFromArray($style_header1);
                                    $excel->getActiveSheet()->getStyle($a[2].$i)->applyFromArray($style_header1);
                     $excel->getActiveSheet()->getStyle($a[3].$i)->applyFromArray($style_header1)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                       
                    $excel->getActiveSheet()->getStyle($a[3].$i)->applyFromArray($style_header1)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[4].$i)->applyFromArray($style_header1)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[5].$i)->applyFromArray($style_header1)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[6].$i)->applyFromArray($style_header1)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[7].$i)->applyFromArray($style_header1)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[8].$i)->applyFromArray($style_header1)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[9].$i)->applyFromArray($style_header1)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[10].$i)->applyFromArray($style_header1)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[11].$i)->applyFromArray($style_header1)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[12].$i)->applyFromArray($style_header1)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[13].$i)->applyFromArray($style_header1)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[14].$i)->applyFromArray($style_header1)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[15].$i)->applyFromArray($style_header1)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $i++;
                    
                    $excel->getActiveSheet()->getCell($a[3].$i)->getValue();
                     $excel->getActiveSheet()->getCell($a[4].$i)->getValue();
                     $excel->getActiveSheet()->getCell($a[5].$i)->getValue();
                     $excel->getActiveSheet()->getCell($a[6].$i)->getValue();
                     $excel->getActiveSheet()->getCell($a[7].$i)->getValue();
                     $excel->getActiveSheet()->getCell($a[8].$i)->getValue();
                     $excel->getActiveSheet()->getCell($a[9].$i)->getValue();
                     $excel->getActiveSheet()->getCell($a[10].$i)->getValue();
                     $excel->getActiveSheet()->getCell($a[11].$i)->getValue();
                     $excel->getActiveSheet()->getCell($a[12].$i)->getValue();
                     $excel->getActiveSheet()->getCell($a[13].$i)->getValue();
                     $excel->getActiveSheet()->getCell($a[14].$i)->getValue();
                     $excel->getActiveSheet()->getCell($a[15].$i)->getValue();
                   $akhir=$i-1;
                   
                  
                   $excel->setActiveSheetIndex(0)
                   	->mergeCells($a[0].$i.':'.$a[2].$i)->setCellValueExplicit($a[0].$i,"Grand Total ", PHPExcel_Cell_DataType::TYPE_STRING)
                  	->setCellValue($a[3].$i,(string)"=SUM(".$a[3].$awal.":".$a[3].$akhir.")-(".$pengurang[3].")")
                  	->setCellValue($a[4].$i,(string)"=SUM(".$a[4].$awal.":".$a[4].$akhir.")-(".$pengurang[4].")")
                    ->setCellValue($a[5].$i,(string)"=SUM(".$a[5].$awal.":".$a[5].$akhir.")-(".$pengurang[5].")")                               
                    ->setCellValue($a[6].$i,(string)"=SUM(".$a[6].$awal.":".$a[6].$akhir.")-(".$pengurang[6].")")
                    ->setCellValue($a[7].$i,(string)"=SUM(".$a[7].$awal.":".$a[7].$akhir.")-(".$pengurang[7].")")
                    ->setCellValue($a[8].$i,(string)"=SUM(".$a[8].$awal.":".$a[8].$akhir.")-(".$pengurang[8].")")
                    ->setCellValue($a[9].$i,(string)"=SUM(".$a[9].$awal.":".$a[9].$akhir.")-(".$pengurang[9].")")
                    ->setCellValue($a[10].$i,(string)"=SUM(".$a[10].$awal.":".$a[10].$akhir.")-(".$pengurang[10].")")
                    ->setCellValue($a[11].$i,(string)"=SUM(".$a[11].$awal.":".$a[11].$akhir.")-(".$pengurang[11].")")
                    ->setCellValue($a[12].$i,(string)"=SUM(".$a[12].$awal.":".$a[12].$akhir.")-(".$pengurang[12].")")
                    ->setCellValue($a[13].$i,(string)"=SUM(".$a[13].$awal.":".$a[13].$akhir.")-(".$pengurang[13].")")
                    ->setCellValue($a[14].$i,(string)"=SUM(".$a[14].$awal.":".$a[14].$akhir.")-(".$pengurang[14].")")
                    ->setCellValue($a[15].$i,(string)"=SUM(".$a[15].$awal.":".$a[15].$akhir.")-(".$pengurang[15].")");
                   
                   $excel->getActiveSheet()->getStyle($a[0].$i)->applyFromArray($style_header)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                     $excel->getActiveSheet()->getStyle($a[1].$i)->applyFromArray($style_header);
                     $excel->getActiveSheet()->getStyle($a[2].$i)->applyFromArray($style_header);
                     $excel->getActiveSheet()->getStyle($a[3].$i)->applyFromArray($style_header)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                       
                    $excel->getActiveSheet()->getStyle($a[3].$i)->applyFromArray($style_header)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[4].$i)->applyFromArray($style_header)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[5].$i)->applyFromArray($style_header)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[6].$i)->applyFromArray($style_header)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[7].$i)->applyFromArray($style_header)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[8].$i)->applyFromArray($style_header)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[9].$i)->applyFromArray($style_header)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[10].$i)->applyFromArray($style_header)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[11].$i)->applyFromArray($style_header)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[12].$i)->applyFromArray($style_header)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[13].$i)->applyFromArray($style_header)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[14].$i)->applyFromArray($style_header)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $excel->getActiveSheet()->getStyle($a[15].$i)->applyFromArray($style_header)
					   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		          
				}*/
	       $excel->getActiveSheet(1)->setTitle('Rekap Populasi Per Kelompok');
            $excel->setActiveSheetIndex(1);

       
        $sekarang=date("dmY_His");
		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="rekap_populasi_'.$sekarang.'.xls"');
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