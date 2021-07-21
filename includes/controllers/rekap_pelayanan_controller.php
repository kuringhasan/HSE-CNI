<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Rekap_Pelayanan_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        global $dcistem;
        
        $this->rekap=new Adm_Rekap_Model();
        
       
	}
	
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("rekap_pelayanan");
        $db   = $dcistem->getOption("framework/db");
        $login=new Adm_Login_Model();
        $master=new Master_Ref_Model();
        $KodeDosen=$_SESSION["framework"]["ref_id"] ;
        $Username=$_SESSION["framework"]["current_user"]->Username ;
       
        $last_update=$db->select("max(rkpLastUpdate) last_update","tbmrekappelayananbydate")->get(0);
        //print_r($last_update);
        $tpl->last_update      = $last_update;
        $tpl->ListJenisPelayanan = Model::getOptionList("keswan_pelayanan_jenis","pelayanan_id","case when ifnull(pelayanan_alias,'')<>'' then concat( pelayanan_nama,' (',pelayanan_alias,')') 
         else pelayanan_nama end nama","pelayanan_nama ASC"); 
        $tpl->ListTPK= Model::getOptionList("mcp","id","name","id ASC"); 
        	$tpl->default_tahun  = date("Y");;
         $ExportExcel=$login->privilegeInputForm("button","","btn-download-excel","<i class=\"fa fa-file-excel-o\"></i> Excel",$this->page->PageID,"Export","title='Download Excel'class=\"btn btn-primary btn-xs btn-export-excel\" ");
         $tpl->TombolDownload=$ExportExcel;
         $tpl->url_listdata      = url::current("listdata");
         $tpl->url_jsonData		= url::current("jsonData");
         $tpl->url_comboAjax=url::current("comboAjax");
         $tpl->url_refresh      = url::current("refresh");
         $tpl->url_export=url::current("Export");
		$this->tpl->content = $tpl;
		$this->tpl->render();        
    }
    public function listdata($action="listdata") {
 	    global $dcistem;
 	  	
       $db   = $dcistem->getOption("framework/db");
      
        $login=new Adm_Login_Model();
        $master=new Master_Ref_Model();
        $modelsortir	= new Adm_Sortir_Model();
        $login_as = $_SESSION["framework"]["login_as"]; 
        
        $keriteria  = array();
        $requestData= $_REQUEST;
        
        $default_tahun  = date("Y");;
        $tahun      = $default_tahun;
        if(isset($requestData['columns'][6]['search']['value'])){
            $tahun      = $requestData['columns'][6]['search']['value'];
        }
        if( trim($tahun)<>"" ){   //name
            $keriteria[]="rkpTahun ='".$tahun."'";
        }
        $tpk       = $requestData['columns'][0]['search']['value'];
        if( trim($tpk)<>"" ){   //name
            $keriteria[]="rkpTPK =".$tpk."";
        }
        
        if(trim($action)=="listdata"){
            $judul="REKAP PELAYANAN BERDASARKAN TPK";
            if( trim($tahun)<>"" ){   //name
               
                $judul1=$judul." Tahun ".$tahun;
            }            
             if( trim($tpk)<>"" ){   //name
                $ref=$master->referensi_session();
                $nama_tpk=$ref['tpk'][$tpk];
                if( trim($tahun)<>"" ){
                    $judul1=$judul1."<br />TPK ".$nama_tpk;
                }
            }
           
            
            $filter=$modelsortir->fromFormcari($keriteria,"and");
            $draw=$_REQUEST['draw'];
   	        $length=$_REQUEST['length'];
            
            $start=$_REQUEST['start'];
            $cols=array(0=>"rkpTahun",
                        1=>"rkpTPK",
                        2=>"rkpJenisPelayanan");
            $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
            $group="group by rkpTahun, rkpTPK, rkpJenisPelayanan";
            if(trim($filter)<>""){
                $filter=$filter." ".$group;
                $group="";
            }    
            $list_qry2=$db->select("SQL_CALC_FOUND_ROWS rkpTahun, rkpTPK,m.name NamaTPK,rkpJenisPelayanan,kpj.pelayanan_nama,
            sum(case when rkpBulan=1 then rkpJumlah else 0 end) jml_jan,
            sum(case when rkpBulan=2 then rkpJumlah else 0 end) jml_feb,
            sum(case when rkpBulan=3 then rkpJumlah else 0 end) jml_mar,
            sum(case when rkpBulan=4 then rkpJumlah else 0 end) jml_apr,
            sum(case when rkpBulan=5 then rkpJumlah else 0 end) jml_mei,
            sum(case when rkpBulan=6 then rkpJumlah else 0 end) jml_jun,
            sum(case when rkpBulan=7 then rkpJumlah else 0 end) jml_jul,
            sum(case when rkpBulan=8 then rkpJumlah else 0 end) jml_agu,
            sum(case when rkpBulan=9 then rkpJumlah else 0 end) jml_sep,
            sum(case when rkpBulan=10 then rkpJumlah else 0 end) jml_okt,
            sum(case when rkpBulan=11 then rkpJumlah else 0 end) jml_nop,
            sum(case when rkpBulan=12 then rkpJumlah else 0 end) jml_des","tbmrekappelayananbydate rkp
            inner join keswan_pelayanan_jenis kpj on kpj.pelayanan_id=rkp.rkpJenisPelayanan
            inner join mcp m on m.id=rkp.rkpTPK $group")
    		->where($filter)->orderby("rkpTahun desc, rkpTPK asc")->lim($start,$length);//
            $no=1;
            $i=0;
            $ListRekap=array();
            $jml_filtered=0;
            $tmp_tpk="";
            
            while($rekap = $db->fetchObject($list_qry2))
            {
                if($i==0){
                    $tmp_tpk=$rekap->NamaTPK;
                    $filtered_qry=$db->query("SELECT FOUND_ROWS() jml_filtered;");
                    $filtered_data=$db->fetchObject($filtered_qry);
                    //print_r($filtered_data);
                    $jml_filtered= $filtered_data->jml_filtered;
                }
                if(trim($tmp_tpk)==trim($rekap->NamaTPK) ){
                    //$jml_nop=$jml_nop+(int)$rekap->jml_nop;
                }
               
                $sub_total=0;
                $ListRekap[$i]['No']=$no;
                $ListRekap[$i]['tpk_nama']=$rekap->NamaTPK;
                $ListRekap[$i]['tahun']=$rekap->rkpTahun;               
                $ListRekap[$i]['pelayanan']="[".$rekap->rkpJenisPelayanan."] ".$rekap->pelayanan_nama;
                $ListRekap[$i]['jml_jan']=$rekap->jml_jan;
                $ListRekap[$i]['jml_feb']=$rekap->jml_feb;
                $ListRekap[$i]['jml_mar']=$rekap->jml_mar;
                $ListRekap[$i]['jml_apr']=$rekap->jml_apr;                
                $ListRekap[$i]['jml_mei']=$rekap->jml_mei;
                $ListRekap[$i]['jml_jun']=$rekap->jml_jun;
                $ListRekap[$i]['jml_jul']=$rekap->jml_jul;
                $ListRekap[$i]['jml_agu']=$rekap->jml_agu;
                $ListRekap[$i]['jml_sep']=$rekap->jml_sep;
                $ListRekap[$i]['jml_okt']=$rekap->jml_okt;
                $ListRekap[$i]['jml_nop']=$rekap->jml_nop;
                $ListRekap[$i]['jml_des']=$rekap->jml_des;
                $sub_total=$rekap->jml_jan+$rekap->jml_feb+$rekap->jml_mar+$rekap->jml_apr+$rekap->jml_mei+$rekap->jml_jun+
                        $rekap->jml_jul+$rekap->jml_agu+$rekap->jml_sep+$rekap->jml_okt+$rekap->jml_nop+$rekap->jml_des;
                $ListRekap[$i]['sub_total']=$sub_total;
                $tmp_tpk=$rekap->NamaTPK;
                $i++;
                $no++;
            }
            $hasil['draw']=$draw;
            $hasil['title']=strtoupper($judul);;
            $hasil['recordsTotal']=$jml_filtered;
            $hasil['recordsFiltered']=$jml_filtered;//
    	    $hasil['data']=$ListRekap;
           // echo "<pre>"; print_r($hasil);echo "</pre>";exit;
            echo json_encode($hasil);exit;
         }
         if(trim($action)=="rekap"){
            $judul=$judul1;
            $judul="REKAP PELAYANAN";
            if( trim($tahun)<>"" ){   //name
                $keriteria[]="rkpTahun ='".$tahun."'";
                $judul1=$judul." Tahun ".$tahun;
            }            
             if( trim($tpk)<>"" ){   //name
                $ref=$master->referensi_session();
                $nama_tpk=$ref['tpk'][$tpk];
                if( trim($tahun)<>"" ){
                    $judul1=$judul1."<br />TPK ".$nama_tpk;
                }
            }
            $judul=$judul1;
            $filter=$modelsortir->fromFormcari($keriteria,"and");
            $draw=$_REQUEST['draw'];
    	
            $cols=array(0=>"rkpTahun",
                        1=>"rkpBulan");
            $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
            $group="group by rkpTahun,rkpJenisPelayanan";
            if(trim($filter)<>""){
                $filter=$filter." ".$group;
                $group="";
            }    
            $list_qry2=$db->select("SQL_CALC_FOUND_ROWS rkpTahun,rkpJenisPelayanan,kpj.pelayanan_nama,
            sum(case when rkpBulan=1 then rkpJumlah else 0 end) jml_jan,
            sum(case when rkpBulan=2 then rkpJumlah else 0 end) jml_feb,
            sum(case when rkpBulan=3 then rkpJumlah else 0 end) jml_mar,
            sum(case when rkpBulan=4 then rkpJumlah else 0 end) jml_apr,
            sum(case when rkpBulan=5 then rkpJumlah else 0 end) jml_mei,
            sum(case when rkpBulan=6 then rkpJumlah else 0 end) jml_jun,
            sum(case when rkpBulan=7 then rkpJumlah else 0 end) jml_jul,
            sum(case when rkpBulan=8 then rkpJumlah else 0 end) jml_agu,
            sum(case when rkpBulan=9 then rkpJumlah else 0 end) jml_sep,
            sum(case when rkpBulan=10 then rkpJumlah else 0 end) jml_okt,
            sum(case when rkpBulan=11 then rkpJumlah else 0 end) jml_nop,
            sum(case when rkpBulan=12 then rkpJumlah else 0 end) jml_des,
            sum(ifnull(rkpJumlah,0)) jumlah","tbmrekappelayananbydate rkp
            inner join keswan_pelayanan_jenis kpj on kpj.pelayanan_id=rkp.rkpJenisPelayanan
            inner join mcp m on m.id=rkp.rkpTPK $group")
    		->where($filter)->orderby("rkpTahun desc")->lim();//
            $no=1;
            $i=0;
            $ListRekap=array();
            $jml_filtered=0;
            $tmp_tpk="";
            while($rekap = $db->fetchObject($list_qry2))
            {
                if($i==0){
                    $tmp_tpk=$rekap->NamaTPK;
                    $filtered_qry=$db->query("SELECT FOUND_ROWS() jml_filtered;");
                    $filtered_data=$db->fetchObject($filtered_qry);
                    //print_r($filtered_data);
                    $jml_filtered= $filtered_data->jml_filtered;
                }
                if(trim($tmp_tpk)==trim($rekap->NamaTPK) ){
                    //$jml_nop=$jml_nop+(int)$rekap->jml_nop;
                }
               
                $sub_total=0;
                $ListRekap[$i]['No']=$no;
                $ListRekap[$i]['tahun']=$rekap->rkpTahun;               
                $ListRekap[$i]['pelayanan']="[".$rekap->rkpJenisPelayanan."] ".$rekap->pelayanan_nama;
                $ListRekap[$i]['jml_jan']=$rekap->jml_jan;
                $ListRekap[$i]['jml_feb']=$rekap->jml_feb;
                $ListRekap[$i]['jml_mar']=$rekap->jml_mar;
                $ListRekap[$i]['jml_apr']=$rekap->jml_apr;                
                $ListRekap[$i]['jml_mei']=$rekap->jml_mei;
                $ListRekap[$i]['jml_jun']=$rekap->jml_jun;
                $ListRekap[$i]['jml_jul']=$rekap->jml_jul;
                $ListRekap[$i]['jml_agu']=$rekap->jml_agu;
                $ListRekap[$i]['jml_sep']=$rekap->jml_sep;
                $ListRekap[$i]['jml_okt']=$rekap->jml_okt;
                $ListRekap[$i]['jml_nop']=$rekap->jml_nop;
                $ListRekap[$i]['jml_des']=$rekap->jml_des;
                $sub_total=$rekap->jml_jan+$rekap->jml_feb+$rekap->jml_mar+$rekap->jml_apr+$rekap->jml_mei+$rekap->jml_jun+
                        $rekap->jml_jul+$rekap->jml_agu+$rekap->jml_sep+$rekap->jml_okt+$rekap->jml_nop+$rekap->jml_des;
                $ListRekap[$i]['sub_total']=$sub_total;
                $tmp_tpk=$rekap->NamaTPK;
                $i++;
                $no++;
            }
            $hasil['draw']=$draw;
            $hasil['title']=strtoupper($judul);
            $hasil['recordsTotal']=$jml_filtered;
            $hasil['recordsFiltered']=$jml_filtered;//
    	    $hasil['data']=$ListRekap;
           // echo "<pre>"; print_r($hasil);echo "</pre>";exit;
            echo json_encode($hasil);exit;
         }
         //echo $hasil;
        
         // echo "<pre>"; print_r($hasil);echo "</pre>";
	}
 public function refresh(){     
     global $dcistem;
     $db   = $dcistem->getOption("framework/db");
    
        $hasil= $this->rekap->refreshRekap("pelayanan_by_month","last_update");
        $msg['success']=$hasil;
   	    $msg['message']="Selesai";
        echo json_encode($msg);  
        exit;
           
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
        	
            
			 $total=$a[6].'4'."+".$a[7].'4'."+".$a[8].'4'."+".$a[9].'4';
	         
                
				//$excel->getActiveSheet()->getStyle($a[3]."5")->applyFromArray($style_header2);
		
			$tahun  = $_POST['dw_tahun'];
            $tpk    = $_POST['dw_tpk'];
	   
	        if(trim($tahun)<>""){
	        	if( trim($tahun)<>"" ){   //name
                    $keriteria[]="rkpTahun ='".$tahun."'";
                }
                if(trim($tpk)<>"" ){   //name
                    $keriteria[]="rkpTPK =".$tpk."";
                }
                $filter=$modelsortir->fromFormcari($keriteria,"and");
                $judul="REKAP PELAYANAN";
                if( trim($tahun)<>"" ){   //name
                    $keriteria[]="rkpTahun ='".$tahun."'";
                    $judul=$judul." Tahun ".$tahun;
                }      
                $judul2="";      
                if( trim($tpk)<>"" ){   //name
                    $ref=$master->referensi_session();
                    $nama_tpk=$ref['tpk'][$tpk];
                    if( trim($tahun)<>"" ){
                        $judul2="TPK ".$nama_tpk;
                    }
                }
                $judul= strtoupper($judul);
                $judul2= strtoupper($judul2);
                $group="group by rkpTahun, rkpJenisPelayanan";
                if(trim($filter)<>""){
                    $filter=$filter." ".$group;
                    $group="";
                }    
               
                $excel->createSheet();
                $excel->setActiveSheetIndex(0)->mergeCells('A2:O2')->setCellValue('A2', $judul);
                $excel->setActiveSheetIndex(0)->mergeCells('A3:O3')->setCellValue('A3', $judul2);
                $excel->getActiveSheet()->getStyle('A2')->applyFromArray($style_judul);
                $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_judul);
                $key=30;
                $excel->setActiveSheetIndex(0)->mergeCells($a[0].'4:'.$a[0].'5')->setCellValue($a[0].'4', 'No')
                  	->mergeCells($a[1].'4:'.$a[1].'5')->setCellValue($a[1].'4', 'Pelayanan')
                  	->mergeCells($a[2].'4:'.$a[13].'4')->setCellValue($a[2].'4', 'Bulan')
                 	->setCellValue($a[2].'5', 'Jan')
                    ->setCellValue($a[3].'5', 'Feb')
                    ->setCellValue($a[4].'5', 'Mar')
                    ->setCellValue($a[5].'5', 'Apr')
                    ->setCellValue($a[6].'5', 'Mei')
                    ->setCellValue($a[7].'5', 'Jun')
                    ->setCellValue($a[8].'5', 'Jul')
                    ->setCellValue($a[9].'5', 'Agu')
                    ->setCellValue($a[10].'5', 'Sep')
                    ->setCellValue($a[11].'5', 'Okt')
                    ->setCellValue($a[12].'5', 'Nop')
                    ->setCellValue($a[13].'5', 'Des')
                    ->mergeCells($a[14].'4:'.$a[14].'5')->setCellValue($a[14].'4', 'Sub Total');
                
                $excel->getActiveSheet()->getStyle($a[0]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[0])->setWidth(5);
                $excel->getActiveSheet()->getStyle($a[0]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getStyle($a[1]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[1])->setWidth(30);
                $excel->getActiveSheet()->getStyle($a[1]."5")->applyFromArray($style_header);
                
                $excel->getActiveSheet()->getStyle($a[2].'4:'.$a[14].'4')->applyFromArray($style_header);//
               $excel->getActiveSheet()->getStyle($a[2]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[2])->setWidth(9);
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
                $excel->getActiveSheet()->getColumnDimension($a[8])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[9]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[9])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[10]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[10])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[11]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[11])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[12]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[12])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[13]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[13])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[14]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getStyle($a[14]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[14])->setWidth(9);
               
                
                
	            $list_qry2=$db->select("rkpTahun,rkpJenisPelayanan,kpj.pelayanan_nama,
                sum(case when rkpBulan=1 then rkpJumlah else 0 end) jml_jan,
                sum(case when rkpBulan=2 then rkpJumlah else 0 end) jml_feb,
                sum(case when rkpBulan=3 then rkpJumlah else 0 end) jml_mar,
                sum(case when rkpBulan=4 then rkpJumlah else 0 end) jml_apr,
                sum(case when rkpBulan=5 then rkpJumlah else 0 end) jml_mei,
                sum(case when rkpBulan=6 then rkpJumlah else 0 end) jml_jun,
                sum(case when rkpBulan=7 then rkpJumlah else 0 end) jml_jul,
                sum(case when rkpBulan=8 then rkpJumlah else 0 end) jml_agu,
                sum(case when rkpBulan=9 then rkpJumlah else 0 end) jml_sep,
                sum(case when rkpBulan=10 then rkpJumlah else 0 end) jml_okt,
                sum(case when rkpBulan=11 then rkpJumlah else 0 end) jml_nop,
                sum(case when rkpBulan=12 then rkpJumlah else 0 end) jml_des,
                sum(ifnull(rkpJumlah,0)) jumlah","tbmrekappelayananbydate rkp
                inner join keswan_pelayanan_jenis kpj on kpj.pelayanan_id=rkp.rkpJenisPelayanan
                inner join mcp m on m.id=rkp.rkpTPK $group")
        		->where($filter)->orderby("rkpTahun desc")->lim();//
                $no=1;
                $row_span   =0;
                $sub_sistem = array();
                $first      = 0;
                $pengurang  =array();
                $i=6;
                $awal       =$i;
                while($rekap = $db->fetchArray($list_qry2))
                {
	        // echo "<pre>";print_r($rekap);echo "</pre>";
	        
					 	   //$row_span=$ListRekap['jumlah_row'][$data['SubID']];
                           
                           $sub_total=$rekap['jml_jan']+$rekap['jml_feb']+$rekap['jml_mar']+$rekap['jml_apr']+$rekap['jml_mei']+$rekap['jml_jun']+
                            $rekap['jml_jul']+$rekap['jml_agu']+$rekap['jml_sep']+$rekap['jml_okt']+$rekap['jml_nop']+$rekap['jml_des'];
                           
                            $excel->setActiveSheetIndex(0)->setCellValue($a[0].$i, $no)
                                ->setCellValueExplicit($a[1].$i,$rekap['pelayanan_nama'], PHPExcel_Cell_DataType::TYPE_STRING)
		                      	->setCellValue($a[2].$i,$rekap['jml_jan'])
		                      	->setCellValue($a[3].$i,$rekap['jml_feb'])
                                ->setCellValue($a[4].$i,$rekap['jml_mar'])                               
                                ->setCellValue($a[5].$i,$rekap['jml_apr'])
                                ->setCellValue($a[6].$i,$rekap['jml_mei'])
                                ->setCellValue($a[7].$i,$rekap['jml_jun'])
                                ->setCellValue($a[8].$i,$rekap['jml_jul'])
                                ->setCellValue($a[9].$i,$rekap['jml_agu'])
                                ->setCellValue($a[10].$i,$rekap['jml_sep'])
                                ->setCellValue($a[11].$i,$rekap['jml_okt'])
                                ->setCellValue($a[12].$i,$rekap['jml_nop'])
                                ->setCellValue($a[13].$i,$rekap['jml_des'])
                                ->setCellValue($a[14].$i,$sub_total);
                           
		                $excel->getActiveSheet()->getStyle($a[0].$i)->applyFromArray($style_row)
							   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
							   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
					  	$excel->getActiveSheet()->getStyle($a[1].$i)->applyFromArray($style_row)
							   ->getAlignment()->setWrapText(true)->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);;
					  	$excel->getActiveSheet()->getStyle($a[2].$i)->applyFromArray($style_row)
							   ->getAlignment()->setWrapText(true)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
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
                        
		                     
							  
		                $i++;   
		                $no++;
					//	next($ListRekap['data']);
					}
                    $akhir=($i-1);
                    //exit;
                    //$i++;
                    $excel->setActiveSheetIndex(0)
                   	->mergeCells($a[0].$i.':'.$a[1].$i)->setCellValueExplicit($a[0].$i,"Sub-Total ", PHPExcel_Cell_DataType::TYPE_STRING)
                  	->setCellValue($a[2].$i,(string)"=SUM(".$a[2].($awal).":".$a[2].($akhir).")")
                    ->setCellValue($a[3].$i,(string)"=SUM(".$a[3].($awal).":".$a[3].($akhir).")")
                  	->setCellValue($a[4].$i,(string)"=SUM(".$a[4].($awal).":".$a[4].($akhir).")")
                    ->setCellValue($a[5].$i,(string)"=SUM(".$a[5].($awal).":".$a[5].($akhir).")")                               
                    ->setCellValue($a[6].$i,(string)"=SUM(".$a[6].($awal).":".$a[6].($akhir).")")
                    ->setCellValue($a[7].$i,(string)"=SUM(".$a[7].($awal).":".$a[7].($akhir).")")
                    ->setCellValue($a[8].$i,(string)"=SUM(".$a[8].($awal).":".$a[8].($akhir).")")
                    ->setCellValue($a[9].$i,(string)"=SUM(".$a[9].($awal).":".$a[9].($akhir).")")
                    ->setCellValue($a[10].$i,(string)"=SUM(".$a[10].($awal).":".$a[10].($akhir).")")
                    ->setCellValue($a[11].$i,(string)"=SUM(".$a[11].($awal).":".$a[11].($akhir).")")
                    ->setCellValue($a[12].$i,(string)"=SUM(".$a[12].($awal).":".$a[12].($akhir).")")
                    ->setCellValue($a[13].$i,(string)"=SUM(".$a[13].($awal).":".$a[13].($akhir).")")
                    ->setCellValue($a[14].$i,(string)"=SUM(".$a[14].($awal).":".$a[14].($akhir).")");
                     
                   
                    
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
                    $excel->getActiveSheet(0)->setTitle('Rekap Pelayanan');
                    $excel->setActiveSheetIndex(0);
                    $excel->createSheet();
                    /** ================ list data ============= **/
                    $filter2=$modelsortir->fromFormcari($keriteria,"and");
                    
                    $judul="REKAP PELAYANAN BERDASARKAN TPK";
                    if( trim($tahun)<>"" ){   
                        $judul=$judul." Tahun ".$tahun;
                    }   
                    $judul2="";         
                    if( trim($tpk)<>"" ){   //name
                        $ref=$master->referensi_session();
                        $nama_tpk=$ref['tpk'][$tpk];
                        if( trim($tahun)<>"" ){
                            $judul2="TPK ".$nama_tpk;
                        }
                    }
                    $judul= strtoupper($judul);
                    $judul2= strtoupper($judul2);
                    $group2="group by rkpTahun, rkpTPK,rkpJenisPelayanan";
                    if(trim($filter2)<>""){
                        $filter2=$filter2." ".$group2;
                        $group2="";
                    }    
                    $excel->setActiveSheetIndex(1)->mergeCells('A2:P2')->setCellValue('A2', $judul);
                    $excel->setActiveSheetIndex(1)->mergeCells('A3:P3')->setCellValue('A3', $judul2);
                    $excel->getActiveSheet()->getStyle('A2')->applyFromArray($style_judul);
                    $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_judul);
                    $key=30;
                    $excel->setActiveSheetIndex(1)->mergeCells($a[0].'4:'.$a[0].'5')->setCellValue($a[0].'4', 'No')
                        ->mergeCells($a[1].'4:'.$a[1].'5')->setCellValue($a[1].'4', 'TPK')
                      	->mergeCells($a[2].'4:'.$a[2].'5')->setCellValue($a[2].'4', 'Pelayanan')
                      	->mergeCells($a[3].'4:'.$a[14].'4')->setCellValue($a[3].'4', 'Bulan')
                     	->setCellValue($a[3].'5', 'Jan')
                        ->setCellValue($a[4].'5', 'Feb')
                        ->setCellValue($a[5].'5', 'Mar')
                        ->setCellValue($a[6].'5', 'Apr')
                        ->setCellValue($a[7].'5', 'Mei')
                        ->setCellValue($a[8].'5', 'Jun')
                        ->setCellValue($a[9].'5', 'Jul')
                        ->setCellValue($a[10].'5', 'Agu')
                        ->setCellValue($a[11].'5', 'Sep')
                        ->setCellValue($a[12].'5', 'Okt')
                        ->setCellValue($a[13].'5', 'Nop')
                        ->setCellValue($a[14].'5', 'Des')
                        ->mergeCells($a[15].'4:'.$a[15].'5')->setCellValue($a[15].'4', 'Sub Total');
                    
                    $excel->getActiveSheet()->getStyle($a[0]."4")->applyFromArray($style_header);
                    $excel->getActiveSheet()->getColumnDimension($a[0])->setWidth(5);
                    $excel->getActiveSheet()->getStyle($a[0]."5")->applyFromArray($style_header);
                    $excel->getActiveSheet()->getStyle($a[1]."4")->applyFromArray($style_header);
                    $excel->getActiveSheet()->getColumnDimension($a[1])->setWidth(30);
                    $excel->getActiveSheet()->getStyle($a[1]."5")->applyFromArray($style_header);
                    
                    $excel->getActiveSheet()->getStyle($a[2]."4")->applyFromArray($style_header);
                    $excel->getActiveSheet()->getColumnDimension($a[2])->setWidth(30);
                    $excel->getActiveSheet()->getStyle($a[2]."5")->applyFromArray($style_header);
                    
                    $excel->getActiveSheet()->getStyle($a[3].'4:'.$a[15].'4')->applyFromArray($style_header);//
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
                    $excel->getActiveSheet()->getColumnDimension($a[8])->setWidth(9);
                    $excel->getActiveSheet()->getStyle($a[9]."5")->applyFromArray($style_header);
                    $excel->getActiveSheet()->getColumnDimension($a[9])->setWidth(9);
                    $excel->getActiveSheet()->getStyle($a[10]."5")->applyFromArray($style_header);
                    $excel->getActiveSheet()->getColumnDimension($a[10])->setWidth(9);
                    $excel->getActiveSheet()->getStyle($a[11]."5")->applyFromArray($style_header);
                    $excel->getActiveSheet()->getColumnDimension($a[11])->setWidth(9);
                    $excel->getActiveSheet()->getStyle($a[12]."5")->applyFromArray($style_header);
                    $excel->getActiveSheet()->getColumnDimension($a[12])->setWidth(9);
                    $excel->getActiveSheet()->getStyle($a[13]."5")->applyFromArray($style_header);
                    $excel->getActiveSheet()->getColumnDimension($a[13])->setWidth(9);
                    $excel->getActiveSheet()->getStyle($a[14]."5")->applyFromArray($style_header);
                    $excel->getActiveSheet()->getColumnDimension($a[14])->setWidth(9);
                    $excel->getActiveSheet()->getStyle($a[15]."4")->applyFromArray($style_header);
                     $excel->getActiveSheet()->getStyle($a[15]."5")->applyFromArray($style_header);
                    $excel->getActiveSheet()->getColumnDimension($a[15])->setWidth(9);
                   
                    
                    
    	            $list_qry3=$db->select("rkpTahun,rkpTPK,m.name NamaTPK,rkpJenisPelayanan,kpj.pelayanan_nama,
                    sum(case when rkpBulan=1 then rkpJumlah else 0 end) jml_jan,
                    sum(case when rkpBulan=2 then rkpJumlah else 0 end) jml_feb,
                    sum(case when rkpBulan=3 then rkpJumlah else 0 end) jml_mar,
                    sum(case when rkpBulan=4 then rkpJumlah else 0 end) jml_apr,
                    sum(case when rkpBulan=5 then rkpJumlah else 0 end) jml_mei,
                    sum(case when rkpBulan=6 then rkpJumlah else 0 end) jml_jun,
                    sum(case when rkpBulan=7 then rkpJumlah else 0 end) jml_jul,
                    sum(case when rkpBulan=8 then rkpJumlah else 0 end) jml_agu,
                    sum(case when rkpBulan=9 then rkpJumlah else 0 end) jml_sep,
                    sum(case when rkpBulan=10 then rkpJumlah else 0 end) jml_okt,
                    sum(case when rkpBulan=11 then rkpJumlah else 0 end) jml_nop,
                    sum(case when rkpBulan=12 then rkpJumlah else 0 end) jml_des,
                    sum(ifnull(rkpJumlah,0)) jumlah","tbmrekappelayananbydate rkp
                    inner join keswan_pelayanan_jenis kpj on kpj.pelayanan_id=rkp.rkpJenisPelayanan
                    inner join mcp m on m.id=rkp.rkpTPK $group2")
            		->where($filter2)->orderby("rkpTahun desc, rkpTPK asc")->lim();//
                    $no=1;
                    $row_span   =0;
                    $i=6;
                    $awal       =$i;
                    $tmp_tpk    ="";
                    $row_span=1;
                    $akumulasi  =array();
                    $first=0;
                    $index_row_span=0;
                   // $span   = array();
                    $span[$index_row_span]=1;
                    while($data = $db->fetchArray($list_qry3))
                    {
    	        // echo "<pre>";print_r($rekap);echo "</pre>";
    	               if(trim($tmp_tpk)<>trim($data['rkpTPK'])){
   					 	   $first=$i;//harus paling atas
                            if($i<>$awal){
                                 $excel->setActiveSheetIndex(1)
                                   	->mergeCells($a[0].$i.':'.$a[2].$i)->setCellValueExplicit($a[0].$i,"Sub-Total ", PHPExcel_Cell_DataType::TYPE_STRING)
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
                                    
                                    $excel->getActiveSheet()->getStyle($a[0].$i)->applyFromArray($style_header2)
    							     ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                                     $excel->getActiveSheet()->getStyle($a[3].$i)->applyFromArray($style_header2);
                                     $excel->getActiveSheet()->getStyle($a[4].$i)->applyFromArray($style_header2);
                                     $excel->getActiveSheet()->getStyle($a[5].$i)->applyFromArray($style_header2);
                                     $excel->getActiveSheet()->getStyle($a[6].$i)->applyFromArray($style_header2);
                                     $excel->getActiveSheet()->getStyle($a[7].$i)->applyFromArray($style_header2);
                                     $excel->getActiveSheet()->getStyle($a[8].$i)->applyFromArray($style_header2);
                                     $excel->getActiveSheet()->getStyle($a[9].$i)->applyFromArray($style_header2);
                                     $excel->getActiveSheet()->getStyle($a[10].$i)->applyFromArray($style_header2);
                                     $excel->getActiveSheet()->getStyle($a[11].$i)->applyFromArray($style_header2);
                                     $excel->getActiveSheet()->getStyle($a[12].$i)->applyFromArray($style_header2);
                                     $excel->getActiveSheet()->getStyle($a[13].$i)->applyFromArray($style_header2);
                                     $excel->getActiveSheet()->getStyle($a[14].$i)->applyFromArray($style_header2);
                                     $excel->getActiveSheet()->getStyle($a[15].$i)->applyFromArray($style_header2);
                                     
                                     /** ========= bagian untuk membantu dalam perhitungan Grand Total ========== */
                                    $akumulasi[3]=trim($akumulasi[3])<>""?$akumulasi[3]."+".$a[3].$i:$a[3].$i;
                                    $akumulasi[4]=trim($akumulasi[4])<>""?$akumulasi[4]."+".$a[4].$i:$a[4].$i;
                                    $akumulasi[5]=trim($akumulasi[5])<>""?$akumulasi[5]."+".$a[5].$i:$a[5].$i;
                                    $akumulasi[6]=trim($akumulasi[6])<>""?$akumulasi[6]."+".$a[6].$i:$a[6].$i;
                                    $akumulasi[7]=trim($akumulasi[7])<>""?$akumulasi[7]."+".$a[7].$i:$a[7].$i;
                                    $akumulasi[8]=trim($akumulasi[8])<>""?$akumulasi[8]."+".$a[8].$i:$a[8].$i;
                                    $akumulasi[9]=trim($akumulasi[9])<>""?$akumulasi[9]."+".$a[9].$i:$a[9].$i;
                                    $akumulasi[10]=trim($akumulasi[10])<>""?$akumulasi[10]."+".$a[10].$i:$a[10].$i;
                                    $akumulasi[11]=trim($akumulasi[11])<>""?$akumulasi[11]."+".$a[11].$i:$a[11].$i;
                                    $akumulasi[12]=trim($akumulasi[12])<>""?$akumulasi[12]."+".$a[12].$i:$a[12].$i;
                                    $akumulasi[13]=trim($akumulasi[13])<>""?$akumulasi[13]."+".$a[13].$i:$a[13].$i;
                                    $akumulasi[14]=trim($akumulasi[14])<>""?$akumulasi[14]."+".$a[14].$i:$a[14].$i;
                                    $akumulasi[15]=trim($akumulasi[15])<>""?$akumulasi[15]."+".$a[15].$i:$a[15].$i;
                                     /** ==== ahir bagian untuk membantu dalam perhitungan Grand Total ========== */
                                    
                                    $i++;
                            }
                            
                            $sub_total=$data['jml_jan']+$data['jml_feb']+$data['jml_mar']+$data['jml_apr']+$data['jml_mei']+$data['jml_jun']+
                                $data['jml_jul']+$data['jml_agu']+$data['jml_sep']+$data['jml_okt']+$data['jml_nop']+$data['jml_des'];
                           
                            $excel->setActiveSheetIndex(1)->setCellValue($a[0].$i, $no)
                                ->setCellValueExplicit($a[1].$i,$data['NamaTPK'], PHPExcel_Cell_DataType::TYPE_STRING)
                                ->setCellValueExplicit($a[2].$i,$data['pelayanan_nama'], PHPExcel_Cell_DataType::TYPE_STRING)
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
                                ->setCellValue($a[15].$i,$sub_total);
                            
                            
                            $tmp_tpk=$data['rkpTPK']; 
                            $row_span=1;  
                            $span[$index_row_span]=1;
                            $index_row_span++;
                            $no++;
                      
                       }else{
                            $row_span++;
                            $span[$index_row_span]=$row_span;
                            $sub_total=$data['jml_jan']+$data['jml_feb']+$data['jml_mar']+$data['jml_apr']+$data['jml_mei']+$data['jml_jun']+
                            $data['jml_jul']+$data['jml_agu']+$data['jml_sep']+$data['jml_okt']+$data['jml_nop']+$data['jml_des'];
                           
                            $excel->setActiveSheetIndex(1)->setCellValue($a[0].$i, $no)
                                ->setCellValueExplicit($a[1].$i,$data['NamaTPK'], PHPExcel_Cell_DataType::TYPE_STRING)
                                ->setCellValueExplicit($a[2].$i,$data['pelayanan_nama'], PHPExcel_Cell_DataType::TYPE_STRING)
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
                                ->setCellValue($a[15].$i,$sub_total);
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
    					//	next($ListRekap['data']);
    					}
                        /** =============== sub total bagian akhir ===================== */
                        $excel->setActiveSheetIndex(1)
                       	->mergeCells($a[0].$i.':'.$a[2].$i)->setCellValueExplicit($a[0].$i,"Sub-Total ", PHPExcel_Cell_DataType::TYPE_STRING)
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
                        
                        $excel->getActiveSheet()->getStyle($a[0].$i)->applyFromArray($style_header2)
					     ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                         $excel->getActiveSheet()->getStyle($a[3].$i)->applyFromArray($style_header2);
                         $excel->getActiveSheet()->getStyle($a[4].$i)->applyFromArray($style_header2);
                         $excel->getActiveSheet()->getStyle($a[5].$i)->applyFromArray($style_header2);
                         $excel->getActiveSheet()->getStyle($a[6].$i)->applyFromArray($style_header2);
                         $excel->getActiveSheet()->getStyle($a[7].$i)->applyFromArray($style_header2);
                         $excel->getActiveSheet()->getStyle($a[8].$i)->applyFromArray($style_header2);
                         $excel->getActiveSheet()->getStyle($a[9].$i)->applyFromArray($style_header2);
                         $excel->getActiveSheet()->getStyle($a[10].$i)->applyFromArray($style_header2);
                         $excel->getActiveSheet()->getStyle($a[11].$i)->applyFromArray($style_header2);
                         $excel->getActiveSheet()->getStyle($a[12].$i)->applyFromArray($style_header2);
                         $excel->getActiveSheet()->getStyle($a[13].$i)->applyFromArray($style_header2);
                         $excel->getActiveSheet()->getStyle($a[14].$i)->applyFromArray($style_header2);
                         $excel->getActiveSheet()->getStyle($a[15].$i)->applyFromArray($style_header2);
                         /** =============== end sub total bagian akhir ===================== */
                        
                        /** ========= bagian untuk membantu dalam perhitungan Grand Total ========== */
                        $akumulasi[3]=trim($akumulasi[3])<>""?$akumulasi[3]."+".$a[3].$i:$a[3].$i;
                        $akumulasi[4]=trim($akumulasi[4])<>""?$akumulasi[4]."+".$a[4].$i:$a[4].$i;
                        $akumulasi[5]=trim($akumulasi[5])<>""?$akumulasi[5]."+".$a[5].$i:$a[5].$i;
                        $akumulasi[6]=trim($akumulasi[6])<>""?$akumulasi[6]."+".$a[6].$i:$a[6].$i;
                        $akumulasi[7]=trim($akumulasi[7])<>""?$akumulasi[7]."+".$a[7].$i:$a[7].$i;
                        $akumulasi[8]=trim($akumulasi[8])<>""?$akumulasi[8]."+".$a[8].$i:$a[8].$i;
                        $akumulasi[9]=trim($akumulasi[9])<>""?$akumulasi[9]."+".$a[9].$i:$a[9].$i;
                        $akumulasi[10]=trim($akumulasi[10])<>""?$akumulasi[10]."+".$a[10].$i:$a[10].$i;
                        $akumulasi[11]=trim($akumulasi[11])<>""?$akumulasi[11]."+".$a[11].$i:$a[11].$i;
                        $akumulasi[12]=trim($akumulasi[12])<>""?$akumulasi[12]."+".$a[12].$i:$a[12].$i;
                        $akumulasi[13]=trim($akumulasi[13])<>""?$akumulasi[13]."+".$a[13].$i:$a[13].$i;
                        $akumulasi[14]=trim($akumulasi[14])<>""?$akumulasi[14]."+".$a[14].$i:$a[14].$i;
                        $akumulasi[15]=trim($akumulasi[15])<>""?$akumulasi[15]."+".$a[15].$i:$a[15].$i;
                         /** ==== ahir bagian untuk membantu dalam perhitungan Grand Total ========== */
            
                        
                        $akhir=($i-1);
                        //exit;
                        $i++;
                         $excel->setActiveSheetIndex(1)
                       	->mergeCells($a[0].$i.':'.$a[2].$i)->setCellValueExplicit($a[0].$i,"Grand Total ", PHPExcel_Cell_DataType::TYPE_STRING)
                      	->setCellValue($a[3].$i,(string)"=".$akumulasi[3]."")
                      	->setCellValue($a[4].$i,(string)"=".$akumulasi[4]."")
                        ->setCellValue($a[5].$i,(string)"=".$akumulasi[5]."")                               
                        ->setCellValue($a[6].$i,(string)"=".$akumulasi[6]."")
                        ->setCellValue($a[7].$i,(string)"=".$akumulasi[7]."")
                        ->setCellValue($a[8].$i,(string)"=".$akumulasi[8]."")
                        ->setCellValue($a[9].$i,(string)"=".$akumulasi[9]."")
                        ->setCellValue($a[10].$i,(string)"=".$akumulasi[10]."")
                        ->setCellValue($a[11].$i,(string)"=".$akumulasi[11]."")
                        ->setCellValue($a[12].$i,(string)"=".$akumulasi[12]."")
                        ->setCellValue($a[13].$i,(string)"=".$akumulasi[13]."")
                        ->setCellValue($a[14].$i,(string)"=".$akumulasi[14]."")
                        ->setCellValue($a[15].$i,(string)"=".$akumulasi[15]."");
                      
                         
                       
                        
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
                  
                    $excel->getActiveSheet(1)->setTitle('Rekap Pelayanan By TPK');
                    $excel->setActiveSheetIndex(1);
				}

        $sekarang=date("dmY_His");
		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="rekap_kegiatan_'.$sekarang.'.xls"');
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