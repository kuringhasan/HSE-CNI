<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Reports_Weekly_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        global $dcistem;
        $this->rekap=new Adm_Rekap_Model();
       
	}
	
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("reports_weekly");
        $db   = $dcistem->getOption("framework/db");
        $login=new Adm_Login_Model();
        $master=new Master_Ref_Model();
        $KodeDosen=$_SESSION["framework"]["ref_id"] ;
        $Username=$_SESSION["framework"]["current_user"]->Username ;
       
        
        $list_bulan=$master->listarraybulan();
       	$tpl->list_bulan  = $list_bulan;
        
         $tpl->url_listdata      = url::current("listdata");
         $tpl->url_jsonData		= url::current("jsonData");
         $tpl->url_comboAjax=url::current("comboAjax");
          $tpl->url_export=url::current("Export");
		$this->tpl->content = $tpl;
		$this->tpl->render();        
    }
    public function listdata($action="listdata") {
 	    global $dcistem;
 	  	
       $db   = $dcistem->getOption("framework/db");
        //$this->rekap->refreshRekap("pelayanan_by_petugas");
        $login=new Adm_Login_Model();
        $master=new Master_Ref_Model();
        $modelsortir	= new Adm_Sortir_Model();
        $login_as = $_SESSION["framework"]["login_as"]; 
        
        $keriteria  = array();
        $requestData= $_REQUEST;
        
        $bulan    = $requestData['columns'][9]['search']['value'];
        $tahun    = $requestData['columns'][10]['search']['value'];
        
        if(trim($action)=="listdata"){
            if( trim($tahun)<>"" ){   //name
                $keriteria[]="tahun  ='".$tahun."'";
                $judul=$judul."<br />Tahun ".$tahun;
            } 
            if( trim($bulan)<>"" ){   //name
                    $keriteria[]="LPAD(MONTH(wp.end_date), 2, '0')='".$bulan."'";
                    $nama_bln=$master->namabulanIN((int)$bulan);
                    if( trim($tahun)<>"" ){
                        $judul=$judul."<br />".$nama_bln." ".$tahun;
                    }
            }
            $filter=$modelsortir->fromFormcari($keriteria,"and");
            $draw=$_REQUEST['draw'];
    		$length=$_REQUEST['length'];
    		$start=$_REQUEST['start'];
            $group_by="GROUP BY tahun,wp.week";
            if(trim($filter)<>""){
                $filter=$filter." $group_by";
                $group_by="";
                
            }
            $cols=array(0=>"rkpTahun",
                        1=>"rkpBulan");
            $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
                
            $list_qry=$db->select("SQL_CALC_FOUND_ROWS wp.tahun,wp.week,
            sum(case when rwp.partner_id=157 then qty end) qty_hjs,
            sum(case when rwp.partner_id=157 then cumulative_qty end) total_hjs,
            sum(case when rwp.partner_id=158 then qty end) qty_lcp,
            sum(case when rwp.partner_id=158 then cumulative_qty end) total_lcp,
            sum(case when rwp.partner_id=159 then qty end) qty_pl,
            sum(case when rwp.partner_id=159 then cumulative_qty end) total_pl,
            sum(case when rwp.partner_id=160 then qty end) qty_bkm,
            sum(case when rwp.partner_id=160 then cumulative_qty end) total_bkm","report_weekly_production rwp
            inner join week_periode wp on wp.id=rwp.periode_id $group_by")
    		->where($filter)->orderby("wp.tahun desc,wp.week desc")->lim($start,$length);//
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
                $ListData[$i]['week']=$data->week;
                $ListData[$i]['tahun']=$data->tahun;
                $ListData[$i]['qty_hjs']=$data->qty_hjs;
               $ListData[$i]['qty_lcp']=$data->qty_lcp;
               $ListData[$i]['qty_pl']=$data->qty_pl;
               $ListData[$i]['qty_bkm']=$data->qty_bkm;
               $ListData[$i]['total_hjs']=$data->total_hjs;
               $ListData[$i]['total_lcp']=$data->total_lcp;
               $ListData[$i]['total_pl']=$data->total_pl;
               $ListData[$i]['total_bkm']=$data->total_bkm;
               $ListData[$i]['plan']="";
               $ListData[$i]['total_plan']="";
               
                
                $i++;
                $no++;
            }
           
          //echo "<pre>";print_r($ListData);echo "</pre>";
            
            $hasil['draw']=$draw;
            $hasil['title']="Weekly Report";
            $hasil['recordsTotal']=$jml_filtered;
            $hasil['recordsFiltered']=$jml_filtered;//$jml->jml_data;//
    	    $hasil['data']=$ListData;
            echo json_encode($hasil);exit;
         }
         if(trim($action)=="bulan"){
            if( trim($tahun)<>"" ){   //name
                $keriteria[]="  left(month,4)='".$tahun."'";
                $judul=$judul."<br />Tahun ".$tahun;
            } 
            if( trim($bulan)<>"" and trim($tahun)<>"" ){   //name
                    $keriteria[]="month='".$tahun."-".$bulan."'";
                    $nama_bln=$master->namabulanIN((int)$bulan);
                    $judul=$judul."<br />".$nama_bln." ".$tahun;
                   
            }
            $filter=$modelsortir->fromFormcari($keriteria,"and");
            $draw=$_REQUEST['draw'];
    		$length=$_REQUEST['length'];
    		$start=$_REQUEST['start'];
            $group_by="GROUP BY month";
            if(trim($filter)<>""){
                $filter=$filter." $group_by";
                $group_by="";
                
            }
            $cols=array(0=>"rkpTahun",
                        1=>"rkpBulan");
            $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
                
            $list_qry=$db->select("SQL_CALC_FOUND_ROWS month,
            sum(case when partner_id=157 then qty end) qty_hjs,
            sum(case when partner_id=157 then cumulative_qty end) total_hjs,
            sum(case when partner_id=158 then qty end) qty_lcp,
            sum(case when partner_id=158 then cumulative_qty end) total_lcp,
            sum(case when partner_id=159 then qty end) qty_pl,
            sum(case when partner_id=159 then cumulative_qty end) total_pl,
            sum(case when partner_id=160 then qty end) qty_bkm,
            sum(case when partner_id=160 then cumulative_qty end) total_bkm","report_monthly_production  $group_by")
    		->where($filter)->orderby("month desc")->lim($start,$length);//
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
                list($thn,$bln)=explode("-",$data->month);
                $nama_bulan=$master->namabulanIN((int)$bln);
                $ListData[$i]['bulan']=$nama_bulan." ".$thn;
                $ListData[$i]['qty_hjs']=$data->qty_hjs;
               $ListData[$i]['qty_lcp']=$data->qty_lcp;
               $ListData[$i]['qty_pl']=$data->qty_pl;
               $ListData[$i]['qty_bkm']=$data->qty_bkm;
               $ListData[$i]['total_hjs']=$data->total_hjs;
               $ListData[$i]['total_lcp']=$data->total_lcp;
               $ListData[$i]['total_pl']=$data->total_pl;
               $ListData[$i]['total_bkm']=$data->total_bkm;
               $ListData[$i]['plan']="";
               $ListData[$i]['total_plan']="";
               
                
                $i++;
                $no++;
            }
           
          //echo "<pre>";print_r($ListData);echo "</pre>";
            
            $hasil['draw']=$draw;
            $hasil['title']="Weekly Report";
            $hasil['recordsTotal']=$jml_filtered;
            $hasil['recordsFiltered']=$jml_filtered;//$jml->jml_data;//
    	    $hasil['data']=$ListData;
            echo json_encode($hasil);exit;
         }
         //echo $hasil;
        
         // echo "<pre>"; print_r($hasil);echo "</pre>";
	}
  
  public function Export() {
	    global $dcistem;
	    $db       = $dcistem->getOption("framework/db");
	    $modelsortir	= new Adm_Sortir_Model();
	    $master=new Master_Ref_Model();
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
		
			$tahun      	= $_POST['dw_tahun'];
            $bulan    = $_POST['dw_bulan'];
	    
	        if(trim($tahun)<>""){
	        	
                $judul1="REKAP KEGIATAN";
                if( trim($tahun)<>"" ){   //name
                    $keriteria[]="rkpTahun ='".$tahun."'";
                    $judul2="Tahun ".$tahun;
                } 
                if( trim($bulan)<>"" ){   //name
                        $keriteria[]="rkpBulan =".(int)$bulan."";
                        $nama_bln=$master->namabulanIN((int)$bulan);
                        if( trim($tahun)<>"" ){
                            $judul2=$nama_bln." ".$tahun;
                        }
                }
                $excel->setActiveSheetIndex(0)->mergeCells('A2:M2')->setCellValue('A2', $judul1);
                $excel->setActiveSheetIndex(0)->mergeCells('A3:M3')->setCellValue('A3', $judul2);
                $excel->getActiveSheet()->getStyle('A2')->applyFromArray($style_judul);
                $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_judul);
                $key=30;
                $excel->setActiveSheetIndex(0)->mergeCells($a[0].'4:'.$a[0].'5')->setCellValue($a[0].'4', 'No.')
                  	->mergeCells($a[1].'4:'.$a[1].'5')->setCellValue($a[1].'4', 'Nama Petugas')
                  	->mergeCells($a[2].'4:'.$a[12].'4')->setCellValue($a[2].'4', 'Kegiatan/Pelayanan')
                 	->setCellValue($a[2].'5', 'Kawin/IB')
                    ->setCellValue($a[3].'5', 'Bunting')
                    ->setCellValue($a[4].'5', 'Kosong')
                    ->setCellValue($a[5].'5', 'Kelahiran')
                    ->setCellValue($a[6].'5', 'Ganti Eartag')
                    ->setCellValue($a[7].'5', 'Ganti Pemilik')
                    ->setCellValue($a[8].'5', 'Pengobatan')
                    ->setCellValue($a[9].'5', 'Sapi Baru')
                    ->setCellValue($a[10].'5', 'Mutasi/ Kematian')
                    ->setCellValue($a[11].'5', 'Lainnya')
                    ->setCellValue($a[12].'5', 'Sub Total');
                $excel->getActiveSheet()->getStyle($a[0]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[0])->setWidth(5);
                $excel->getActiveSheet()->getStyle($a[0]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getStyle($a[1]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[1])->setWidth(30);
                $excel->getActiveSheet()->getStyle($a[1]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getStyle($a[2].'4:'.$a[12].'4')->applyFromArray($style_header);//
                $excel->getActiveSheet()->getStyle($a[2]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[2])->setWidth(12);
                $excel->getActiveSheet()->getStyle($a[3]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[3])->setWidth(12);
                $excel->getActiveSheet()->getStyle($a[4]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[4])->setWidth(12);
                $excel->getActiveSheet()->getStyle($a[5]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[5])->setWidth(12);
                $excel->getActiveSheet()->getStyle($a[6]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[6])->setWidth(12);
                $excel->getActiveSheet()->getStyle($a[7]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[7])->setWidth(12);
                $excel->getActiveSheet()->getStyle($a[8]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[8])->setWidth(16);
                $excel->getActiveSheet()->getStyle($a[9]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[9])->setWidth(13);
                $excel->getActiveSheet()->getStyle($a[10]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[10])->setWidth(13);
                $excel->getActiveSheet()->getStyle($a[11]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[11])->setWidth(13);
                $excel->getActiveSheet()->getStyle($a[12]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[12])->setWidth(13);
                
                
	           $ListRekap=$rekap->getRekapTahunanByPetugas($tahun,$bulan);
	           //echo "<pre>";print_r($ListRekap['data']);echo "</pre>";exit;
	        
	        	$i=6;
	        	if (count($ListRekap['data'])) {
					$no=1;
					$awal=$i;
					while($data = current($ListRekap['data'])) {
					 	 
		                	$excel->setActiveSheetIndex(0)
		                      	->setCellValue($a[0].$i, $no)
		                       	->setCellValueExplicit($a[1].$i,$data['Petugas'], PHPExcel_Cell_DataType::TYPE_STRING)
		                      	->setCellValue($a[2].$i,$data['jml_ib'])
		                      	->setCellValue($a[3].$i,$data['jml_bunting'])
                                ->setCellValue($a[4].$i,$data['jml_kosong'])
                                ->setCellValue($a[5].$i,$data['jml_kelahiran'])
                                ->setCellValue($a[6].$i,$data['jml_ganti_eartag'])
                                ->setCellValue($a[7].$i,$data['jml_ganti_pemilik'])
                                ->setCellValue($a[8].$i,$data['jml_pengobatan'])
                                ->setCellValue($a[9].$i,$data['jml_sapi_baru'])
                                ->setCellValue($a[10].$i,$data['jml_mutasi'])
                                ->setCellValue($a[11].$i,$data['jml_lainnya'])
                                ->setCellValue($a[12].$i,$data['sub_total']);
		                      
		                $excel->getActiveSheet()->getStyle($a[0].$i)->applyFromArray($style_row)
							   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
							   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					  	$excel->getActiveSheet()->getStyle($a[1].$i)->applyFromArray($style_row)
							   ->getAlignment()->setWrapText(true)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);;
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
		                     
							  
		                $i++;   
		                $no++;
						next($ListRekap['data']);
					}
                   // $i=$i+1;
                    $akhir=($i-1);
                    $excel->setActiveSheetIndex(0)->setCellValue($a[0].$i, '.')
                      	->setCellValue($a[1].$i, 'Grand Total')
                     //	->setCellValue($a[2].$i, "'=SUM(".$a[2].$awal.":".$a[2].$akhir.")")
                        ->setCellValue($a[2].$i,(string)"=SUM(".$a[2].$awal.":".$a[2].$akhir.")")
                        ->setCellValue($a[3].$i,(string)"=SUM(".$a[3].$awal.":".$a[3].$akhir.")")
                        ->setCellValue($a[4].$i,(string)"=SUM(".$a[4].$awal.":".$a[4].$akhir.")")
                        ->setCellValue($a[5].$i, (string)"=SUM(".$a[5].$awal.":".$a[5].$akhir.")")
                        ->setCellValue($a[6].$i, (string)"=SUM(".$a[6].$awal.":".$a[6].$akhir.")")
                        ->setCellValue($a[7].$i, (string)"=SUM(".$a[7].$awal.":".$a[7].$akhir.")")
                        ->setCellValue($a[8].$i, (string)"=SUM(".$a[8].$awal.":".$a[8].$akhir.")")
                        ->setCellValue($a[9].$i, (string)"=SUM(".$a[9].$awal.":".$a[9].$akhir.")")
                        ->setCellValue($a[10].$i, (string)"=SUM(".$a[10].$awal.":".$a[10].$akhir.")")
                        ->setCellValue($a[11].$i, (string)"=SUM(".$a[11].$awal.":".$a[11].$akhir.")")
                        ->setCellValue($a[12].$i, (string)"=SUM(".$a[12].$awal.":".$a[12].$akhir.")");
                     $excel->getActiveSheet()->getCell($a[2].$i)->getValue();;
                    $excel->getActiveSheet()->getStyle($a[0].$i)->applyFromArray($style_header);
                    $excel->getActiveSheet()->getStyle($a[1].$i)->applyFromArray($style_header);
                    $excel->getActiveSheet()->getStyle($a[2].$i)->applyFromArray($style_header);//
                    $excel->getActiveSheet()->getStyle($a[3].$i)->applyFromArray($style_header);
                    $excel->getActiveSheet()->getStyle($a[4].$i)->applyFromArray($style_header);
                    $excel->getActiveSheet()->getStyle($a[5].$i)->applyFromArray($style_header);
                    $excel->getActiveSheet()->getStyle($a[6].$i)->applyFromArray($style_header);
                    $excel->getActiveSheet()->getStyle($a[7].$i)->applyFromArray($style_header);
                    $excel->getActiveSheet()->getStyle($a[8].$i)->applyFromArray($style_header);
                    $excel->getActiveSheet()->getStyle($a[9].$i)->applyFromArray($style_header);
                    $excel->getActiveSheet()->getStyle($a[10].$i)->applyFromArray($style_header);
                    $excel->getActiveSheet()->getStyle($a[11].$i)->applyFromArray($style_header);
                    $excel->getActiveSheet()->getStyle($a[12].$i)->applyFromArray($style_header);
					$akhir=$i-1;
				//	$jml="COUNTIF(".$a[11].$awal.":".$a[11].$akhir.";'A')";
		          
				}
			   
        }
     // exit;
        $i=$i+1;

        $excel->getActiveSheet()->setTitle('Rekap Kegiatan');
        $excel->setActiveSheetIndex(0);
        $sekarang=date("dmY_His");
		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="rekap_kegiatan_petugas_'.$sekarang.'.xls"');
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