<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Rekap_Barangpakan_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        global $dcistem;
        
        $this->rekap=new Adm_Rekap_Model();
        
       
	}
	
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("rekap_barangpakan");
        $db   = $dcistem->getOption("framework/db");
        $login=new Adm_Login_Model();
        $master=new Master_Ref_Model();
        $KodeDosen=$_SESSION["framework"]["ref_id"] ;
        $Username=$_SESSION["framework"]["current_user"]->Username ;
        $list_bulan=$master->listarraybulan();
      	$tpl->list_bulan  = $list_bulan;
        $master->referensi_session("tpk");
        
       
        $last_update=$db->select("max(rkpLastUpdate) last_update","tbmrekappelayananbydate")->get(0);
        //print_r($last_update);
        $tpl->last_update      = $last_update;
        $tpl->ListJenisPelayanan = Model::getOptionList("keswan_pelayanan_jenis","pelayanan_id","case when ifnull(pelayanan_alias,'')<>'' then concat( pelayanan_nama,' (',pelayanan_alias,')') 
         else pelayanan_nama end nama","pelayanan_nama ASC"); 
        $tpl->ListTPK= Model::getOptionList("mcp","id","name","id ASC"); 
       	 $tpl->default_tahun  = date("Y");;
         $bulan_sekarang  = date("m");
         $tpl->bulan_lalu= $master->namabulanIN((int)$bulan_sekarang-1);
         $tpl->bulan_lalu_id=(int)$bulan_sekarang-1;
         $ExportExcel=$login->privilegeInputForm("button","","btn-download-excel","<i class=\"fa fa-file-excel-o\"></i> Excel",$this->page->PageID,"Export","title='Download Excel'class=\"btn btn-primary btn-xs btn-export-excel\" ");
         $tpl->TombolDownload=$ExportExcel;
         $tpl->url_listdata      = url::current("listdata");
         $tpl->url_jsonData		= url::current("jsonData");
         $tpl->url_comboAjax=url::current("comboAjax");
         $tpl->url_generate      = url::page(2310,"rekap_logistik");
         $tpl->url_export=url::current("Export");
		$this->tpl->content = $tpl;
		$this->tpl->render();        
    }
    public function listdata($action="kelompok") {
 	    global $dcistem;
 	  	
       $db   = $dcistem->getOption("framework/db");
      
        $login=new Adm_Login_Model();
        $admin  = new Core_Admin_Model();
        $master=new Master_Ref_Model();
        $modelsortir	= new Adm_Sortir_Model();
        $periode        = new List_Periode_Model();
        $login_as = $_SESSION["framework"]["login_as"]; 
        $ref_tpk= $_SESSION["referensi"]['tpk'];
        
        
        $keriteria      = array();
        
        $keriteria  = array();
        $requestData= $_REQUEST;
        
       
        $tpk       = $requestData['columns'][0]['search']['value'];
        if( trim($tpk)<>"" ){   //name
            $keriteria[]="mcp_id =".$tpk."";
        }
        
        if(trim($action)=="harian"){
            $search=$admin->SearchDependingLevel("logistik","rbh.pegawai_id");
            //print_r($search);
            if(!empty($search['array'])){
                $keriteria[]      = $search['array'];
            }
            
            $judul="REKAP TRANSAKSI LOGISTIK BERDASARKAN KELOMPOK";
            $tahun       = $requestData['columns'][6]['search']['value'];
            if( trim($tahun)<>"" ){   //name
            
                $judul=$judul." Tahun ".$tahun;
            }            
            if( trim($tpk)<>"" ){   //name
                /*$ref=$master->referensi_session();
                $nama_tpk=$ref['tpk'][$tpk];
                if( trim($tahun)<>"" ){
                    $judul1=$judul1."<br />TPK ".$nama_tpk;
                }*/
            }
            $tanggal       = $requestData['columns'][1]['search']['value'];
            if( trim($tanggal)<>"" ){   //name
                list($tgl,$bln,$tahun)=explode("/",$tanggal);
                $value_tgl=$tahun."-".$bln."-".$tgl;
                $keriteria[]=" DATE_FORMAT(trx_date,'%Y-%m-%d')='".$value_tgl."'";
            }
            
            $bulan       = $requestData['columns'][5]['search']['value'];
            if( trim($tahun)<>"" ){   //name
                $keriteria_bln=" year(trx_date)='".$tahun."'";
               if( trim($bulan)<>"" ){   //name
                    $keriteria_bln=" DATE_FORMAT(trx_date,'%Y-%m')='".$tahun."-".$bulan."'";
               }
               $keriteria[]=$keriteria_bln;
            }
            
            $filter=$modelsortir->fromFormcari($keriteria,"and");
            $draw=$_REQUEST['draw'];
   	        $length=$_REQUEST['length'];
            
            $start=$_REQUEST['start'];
            $cols=array(0=>"id",
                        1=>"trx_date",
                        2=>"barang_id");
            $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
           
            $list_qry2=$db->select("SQL_CALC_FOUND_ROWS rbh.id,DATE_FORMAT(trx_date,'%d/%m/%Y') trx_date,kelompok_id,periode_id,p.start_date,
            kel.name nama_kelompok,barang_id,brg.name brg_name,harga_satuan,jml_package,jml_qty,jml_harga,last_update,
            created_time,pegawai_id,kp.pNama,kp.pAlias,kp.pGelarDepan,kp.pGelarBelakang,locked","rekap_barangpakan_harian rbh
            inner join kelompok kel on kel.id=rbh.kelompok_id
            inner join barang brg on brg.id=rbh.barang_id
            inner join periode p on p.id=rbh.periode_id
            inner join keswan_pegawai kp on kp.pID=rbh.pegawai_id")
    		->where($filter)->orderby("trx_date desc,mcp_id asc,kelompok_id asc, barang_id asc")->lim($start,$length);//
    		//->where($filter)->orderby("rkpTahun desc, rkpTPK asc")->lim($start,$length);//
            $no=1;
            $i=0;
            $ListRekap=array();
            $jml_filtered=0;
            $tmp_tpk="";
            
            while($data = $db->fetchObject($list_qry2))
            {
                if($i==0){
                    $filtered_qry=$db->query("SELECT FOUND_ROWS() jml_filtered;");
                    $filtered_data=$db->fetchObject($filtered_qry);
                    //print_r($filtered_data);
                    $jml_filtered= $filtered_data->jml_filtered;
                }
                 
                $lbl_periode=$periode->convertPeriode($data->start_date);
                $ListRekap[$i]['No']=$no;
                $ListRekap[$i]['tanggal']=$data->trx_date;               
                $ListRekap[$i]['kelompok_id']=$data->kelompok_id;
                $ListRekap[$i]['periode']= $lbl_periode['label'];// $data->periode_id;
                $ListRekap[$i]['kelompok_name']=$data->nama_kelompok;
                $ListRekap[$i]['barang_id']=$data->barang_id;
                $ListRekap[$i]['brg_name']=$data->brg_name;
                $ListRekap[$i]['harga_satuan']=number_format($data->harga_satuan,0,",",".");
                $ListRekap[$i]['jml_package']=$data->jml_package;
                $ListRekap[$i]['jml_qty']=number_format($data->jml_qty,0,",",".");                
                $ListRekap[$i]['jml_harga']=number_format($data->jml_harga,0,",",".");
                $ListRekap[$i]['last_update']=$data->last_update;
                $nama_lengkap=$master->nama_dan_gelar($data->pGelarDepan,$data->pNama,$data->pGelarBelakang);
                $ListRekap[$i]['petugas']=$nama_lengkap;                
                $ListRekap[$i]['created']=$data->created_time;
                $ListRekap[$i]['locked']=$data->locked==true?"<i class='fa fa-fw fa-lock'></i>":"<i class='fa fa-fw fa-unlock'></i>";
                
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
         if(trim($action)=="bulanan"){
          
            $judul="REKAP TRANSAKSI BARANG PAKAN PER BULAN";
            if( trim($tahun)<>"" ){   //name
                $judul1=$judul." Tahun ".$tahun;
            }            
            /*if( trim($tpk)<>"" ){   //name
                $ref=$master->referensi_session();
                $nama_tpk=$ref['tpk'][$tpk];
                if( trim($tahun)<>"" ){
                    $judul=$judul."<br />TPK ".$nama_tpk;
                }
            }*/
            $tahun       = $requestData['columns'][6]['search']['value'];
            $bulan       = $requestData['columns'][5]['search']['value'];
            if( trim($tahun)<>"" ){   //name
                $keriteria_bln=" left(bulan,4)='".$tahun."'";
               if( trim($bulan)<>"" ){   //name
                    $keriteria_bln=" bulan='".$tahun."-".$bulan."'";
                }
                $keriteria[]=$keriteria_bln;
            }
            $filter=$modelsortir->fromFormcari($keriteria,"and");
            $draw=$_REQUEST['draw'];
    	
            $cols=array(0=>"trx_date",
                        1=>"mcp_id");
            $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
            
            $list_qry2=$db->select("SQL_CALC_FOUND_ROWS rbb.id,bulan,kelompok_id,mcp_id,
            kel.name nama_kelompok,barang_id,brg.name brg_name,harga_satuan,jml_package,jml_qty,jml_harga,last_update,
            created_time,pegawai_id,kp.pNama,kp.pAlias,kp.pGelarDepan,kp.pGelarBelakang,locked","rekap_barangpakan_bulanan rbb
            inner join kelompok kel on kel.id=rbb.kelompok_id
            inner join barang brg on brg.id=rbb.barang_id
            inner join keswan_pegawai kp on kp.pID=rbb.pegawai_id")
    		->where($filter)->orderby("bulan desc,mcp_id asc,kelompok_id asc, pegawai_id asc, barang_id asc")->lim();//
            $no=1;
            $i=0;
            $ListRekap=array();
            $jml_filtered=0;
            $tmp_tpk="";
            while($rekap = $db->fetchObject($list_qry2))
            {
                if($i==0){
                    $tmp_tpk=$ref_tpk[$rekap->mcp_id];
                    $filtered_qry=$db->query("SELECT FOUND_ROWS() jml_filtered;");
                    $filtered_data=$db->fetchObject($filtered_qry);
                    $jml_filtered= $filtered_data->jml_filtered;
                }
                if(trim($tmp_tpk)==trim($ref_tpk[$rekap->mcp_id]) ){
                    //$jml_nop=$jml_nop+(int)$rekap->jml_nop;
                }
                list($tahun,$bln)=explode("-",$rekap->bulan);
                $nama_bulan=$master->namabulanIN((int)$bln);
               
                $ListRekap[$i]['No']=$no;
                $ListRekap[$i]['bulan']=$nama_bulan." ".$tahun;               
                $ListRekap[$i]['kelompok_id']=$rekap->kelompok_id;
                $ListRekap[$i]['kelompok_name']=$rekap->nama_kelompok;
                $ListRekap[$i]['barang_id']=$rekap->barang_id;
                $ListRekap[$i]['brg_name']=$rekap->brg_name;
                $ListRekap[$i]['harga_satuan']=number_format($rekap->harga_satuan,0,",",".");
                $ListRekap[$i]['jml_package']=$rekap->jml_package;
                $ListRekap[$i]['jml_qty']=number_format($rekap->jml_qty,0,",",".");                
                $ListRekap[$i]['jml_harga']=number_format($rekap->jml_harga,0,",",".");
                $ListRekap[$i]['last_update']=$rekap->last_update;
                $nama_lengkap=$master->nama_dan_gelar($rekap->pGelarDepan,$rekap->pNama,$rekap->pGelarBelakang);
                $ListRekap[$i]['petugas']=$nama_lengkap;                
                $ListRekap[$i]['created']=$rekap->created_time;
                $ListRekap[$i]['locked']=$rekap->locked==true?"<i class='fa fa-fw fa-lock'></i>":"<i class='fa fa-fw fa-unlock'></i>";
               
                $tmp_tpk=$ref_tpk[$rekap->mcp_id];
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
	 	$periode        = new List_Periode_Model();
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
	            				   ->setTitle("Rekap Transaski Logistik")
	            				   ->setSubject("Rekap Harian")
	            				   ->setDescription("Rekap Harian")
	            				   ->setKeywords("Rekap Logistik");
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
	   
	            $keriteria_harian=array();
                $keriteria_bulanan=array();
	        	if( trim($tahun)<>"" ){   //name
                    $keriteria_harian[]="rkpTahun ='".$tahun."'";
                    $keriteria_bulanan[]="rkpTahun ='".$tahun."'";
                }
                if(trim($tpk)<>"" ){   //name
                    $keriteria_harian[]="mcp_id =".$tpk."";
                    $keriteria_bulanan[]="mcp_id =".$tpk."";
                }
                $admin  = new Core_Admin_Model();
                $search=$admin->SearchDependingLevel("logistik","rbh.pegawai_id");
                //print_r($search);
                if(!empty($search['array'])){
                    $keriteria_harian[]      = $search['array'];
                }
                
                $filter_harian=$modelsortir->fromFormcari($keriteria_harian,"and");
                $judul="REKAP TRANSASKI LOGISTIK HARIAN";
                if( trim($tahun)<>"" ){   //name
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
                
              
                $excel->createSheet();
                $excel->setActiveSheetIndex(0)->mergeCells('A2:O2')->setCellValue('A2', $judul);
                $excel->setActiveSheetIndex(0)->mergeCells('A3:O3')->setCellValue('A3', $judul2);
                $excel->getActiveSheet()->getStyle('A2')->applyFromArray($style_judul);
                $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_judul);
                $key=30;
                $excel->setActiveSheetIndex(0)->mergeCells($a[0].'4:'.$a[0].'5')->setCellValue($a[0].'4', 'No')
                  	->mergeCells($a[1].'4:'.$a[1].'5')->setCellValue($a[1].'4', 'Tanggal')
                    ->mergeCells($a[2].'4:'.$a[2].'5')->setCellValue($a[2].'4', 'Periode')
                    ->mergeCells($a[3].'4:'.$a[3].'5')->setCellValue($a[3].'4', 'Kelompok')
                    ->mergeCells($a[4].'4:'.$a[4].'5')->setCellValue($a[4].'4', 'Barang')
                    ->mergeCells($a[5].'4:'.$a[5].'5')->setCellValue($a[5].'4', 'Harga Satuan')
                  	->mergeCells($a[6].'4:'.$a[8].'4')->setCellValue($a[6].'4', 'Rekap')
                 	->setCellValue($a[6].'5', 'Package')
                    ->setCellValue($a[7].'5', 'Qty')
                    ->setCellValue($a[8].'5', 'Harga')
                    ->mergeCells($a[9].'4:'.$a[9].'5')->setCellValue($a[9].'4', 'Petugas')
                    ->mergeCells($a[10].'4:'.$a[10].'5')->setCellValue($a[10].'4', 'Last Update')
                    ->mergeCells($a[11].'4:'.$a[11].'5')->setCellValue($a[11].'4', 'Dibuat');
                
                $excel->getActiveSheet()->getStyle($a[0]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[0])->setWidth(5);
                $excel->getActiveSheet()->getStyle($a[0]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getStyle($a[1]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[1])->setWidth(12);
                $excel->getActiveSheet()->getStyle($a[1]."5")->applyFromArray($style_header);
                
               $excel->getActiveSheet()->getStyle($a[2]."4")->applyFromArray($style_header);
               $excel->getActiveSheet()->getStyle($a[2]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[2])->setWidth(15);
                $excel->getActiveSheet()->getStyle($a[3]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getStyle($a[3]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[3])->setWidth(18);
                $excel->getActiveSheet()->getStyle($a[4]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getStyle($a[4]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[4])->setWidth(15);
                $excel->getActiveSheet()->getStyle($a[5]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getStyle($a[5]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[5])->setWidth(9);
                
                $excel->getActiveSheet()->getStyle($a[6].'4:'.$a[8].'4')->applyFromArray($style_header);//
                $excel->getActiveSheet()->getStyle($a[6]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[6])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[7]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[7])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[8]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[8])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[9]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getStyle($a[9]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[9])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[10]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getStyle($a[10]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[10])->setWidth(17);
                $excel->getActiveSheet()->getStyle($a[11]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getStyle($a[11]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[11])->setWidth(17);
               
              
	           $list_qry2=$db->select("SQL_CALC_FOUND_ROWS rbh.id,DATE_FORMAT(trx_date,'%d/%m/%Y') trx_date,
                    mcp_id,kelompok_id,periode_id,p.start_date,
                    kel.name nama_kelompok,barang_id,brg.name brg_name,harga_satuan,jml_package,jml_qty,jml_harga,last_update,
                    created_time,pegawai_id,kp.pNama,kp.pAlias,kp.pGelarDepan,kp.pGelarBelakang,locked","rekap_barangpakan_harian rbh
                inner join kelompok kel on kel.id=rbh.kelompok_id
                inner join barang brg on brg.id=rbh.barang_id
                inner join periode p on p.id=rbh.periode_id
                left join keswan_pegawai kp on kp.pID=rbh.pegawai_id")
        		->where($filter_harian)->orderby("trx_date desc, kel.mcp_id asc,kelompok_id asc,barang_id asc")->lim();//
                 $no         =1;
                    $row_span   =0;
                    $i          =6;
                    $awal       =$i;
                    $tmp_tpk    ="";
                    $row_span   =1;
                    $akumulasi  =array();
                    $first      =0;
                    $index_row_span=0;
                   // $span   = array();
                    $span[$index_row_span]=1;
                    while($rekap = $db->fetchArray($list_qry2))
                    {
    	         //echo "<pre>";print_r($rekap);echo "</pre>";exit;
    	               if(trim($tmp_tpk)<>trim($rekap['mcp_id'])){
   					 	   $first=$i;//harus paling atas
                            if($i<>$awal){
                                 $excel->setActiveSheetIndex(0)
                                   	->mergeCells($a[0].$i.':'.$a[5].$i)->setCellValueExplicit($a[0].$i,"Sub-Total ", PHPExcel_Cell_DataType::TYPE_STRING)                                  
                                    ->setCellValue($a[6].$i,(string)"=SUM(".$a[6].($i-$row_span).":".$a[6].($i-1).")")                               
                                    ->setCellValue($a[7].$i,(string)"=SUM(".$a[7].($i-$row_span).":".$a[7].($i-1).")")
                                    ->setCellValue($a[8].$i,(string)"=SUM(".$a[8].($i-$row_span).":".$a[8].($i-1).")");
                                    
                                    
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
                                     
                                     /** ========= bagian untuk membantu dalam perhitungan Grand Total ========== */
                                    
                                    $akumulasi[6]=trim($akumulasi[6])<>""?$akumulasi[6]."+".$a[6].$i:$a[6].$i;
                                    $akumulasi[7]=trim($akumulasi[7])<>""?$akumulasi[7]."+".$a[7].$i:$a[7].$i;
                                    $akumulasi[8]=trim($akumulasi[8])<>""?$akumulasi[8]."+".$a[8].$i:$a[8].$i;
                                    
                                     /** ==== ahir bagian untuk membantu dalam perhitungan Grand Total ========== */
                                    
                                    $i++;
                            }
                            
                           
                            $lbl_periode=$periode->convertPeriode($rekap['start_date']);
                       // print_r($lbl_periode);exit;
                            $nama_lengkap=$master->nama_dan_gelar($rekap['pGelarDepan'],$rekap['pNama'],$rekap['pGelarBelakang']);
                            $excel->setActiveSheetIndex(0)->setCellValue($a[0].$i, $no)
                            ->setCellValueExplicit($a[1].$i,$rekap['trx_date'], PHPExcel_Cell_DataType::TYPE_STRING)
	                      	->setCellValue($a[2].$i,$lbl_periode['label'])
	                      	->setCellValue($a[3].$i,$rekap['nama_kelompok'])
                            ->setCellValue($a[4].$i,$rekap['brg_name'])                               
                            ->setCellValue($a[5].$i,$rekap['harga_satuan'])
                            ->setCellValue($a[6].$i,$rekap['jml_package'])
                            ->setCellValue($a[7].$i,$rekap['jml_qty'])
                            ->setCellValue($a[8].$i,$rekap['jml_harga'])
                            ->setCellValue($a[9].$i,$nama_lengkap)
                            ->setCellValue($a[10].$i,$rekap['last_update'])
                            ->setCellValue($a[11].$i,$rekap['created_time']);
                            
                            
                            $tmp_tpk=$rekap['mcp_id']; 
                            $row_span=1;  
                            $span[$index_row_span]=1;
                            $index_row_span++;
                            $no++;
                      
                       }else{
                            $row_span++;
                            $span[$index_row_span]=$row_span;
                            
                            $lbl_periode=$periode->convertPeriode($rekap['start_date']);
                       // print_r($lbl_periode);exit;
                            $nama_lengkap=$master->nama_dan_gelar($rekap['pGelarDepan'],$rekap['pNama'],$rekap['pGelarBelakang']);
                            $excel->setActiveSheetIndex(0)->setCellValue($a[0].$i, $no)
                                ->setCellValueExplicit($a[1].$i,$rekap['trx_date'], PHPExcel_Cell_DataType::TYPE_STRING)
    	                      	->setCellValue($a[2].$i,$lbl_periode['label'])
    	                      	->setCellValue($a[3].$i,$rekap['nama_kelompok'])
                                ->setCellValue($a[4].$i,$rekap['brg_name'])                               
                                ->setCellValue($a[5].$i,$rekap['harga_satuan'])
                                ->setCellValue($a[6].$i,$rekap['jml_package'])
                                ->setCellValue($a[7].$i,$rekap['jml_qty'])
                                ->setCellValue($a[8].$i,$rekap['jml_harga'])
                                ->setCellValue($a[9].$i,$nama_lengkap)
                                ->setCellValue($a[10].$i,$rekap['last_update'])
                                ->setCellValue($a[11].$i,$rekap['created_time']);
                        }       
    		                $excel->getActiveSheet()->getStyle($a[0].$i)->applyFromArray($style_row)
    							   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    							   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    					  	$excel->getActiveSheet()->getStyle($a[1].$i)->applyFromArray($style_row)
    							   ->getAlignment()->setWrapText(true)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);;
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
                           
    		                     
    							  
    		                $i++;   
    		                $no++;
    					//	next($ListRekap['data']);
    					}
                        /** =============== sub total bagian akhir ===================== */
                        $excel->setActiveSheetIndex(0)
                       	->mergeCells($a[0].$i.':'.$a[5].$i)->setCellValueExplicit($a[0].$i,"Sub-Total ", PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue($a[6].$i,(string)"=SUM(".$a[6].($i-$row_span).":".$a[6].($i-1).")")                               
                        ->setCellValue($a[7].$i,(string)"=SUM(".$a[7].($i-$row_span).":".$a[7].($i-1).")")
                        ->setCellValue($a[8].$i,(string)"=SUM(".$a[8].($i-$row_span).":".$a[8].($i-1).")");
                        
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
                         /** =============== end sub total bagian akhir ===================== */
                        
                        /** ========= bagian untuk membantu dalam perhitungan Grand Total ========== */
                      
                        $akumulasi[6]=trim($akumulasi[6])<>""?$akumulasi[6]."+".$a[6].$i:$a[6].$i;
                        $akumulasi[7]=trim($akumulasi[7])<>""?$akumulasi[7]."+".$a[7].$i:$a[7].$i;
                        $akumulasi[8]=trim($akumulasi[8])<>""?$akumulasi[8]."+".$a[8].$i:$a[8].$i;
                       
                         /** ==== ahir bagian untuk membantu dalam perhitungan Grand Total ========== */
            
                        
                        $akhir=($i-1);
                        //exit;
                        $i++;
                         $excel->setActiveSheetIndex(0)
                       	->mergeCells($a[0].$i.':'.$a[2].$i)->setCellValueExplicit($a[0].$i,"Grand Total ", PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue($a[6].$i,(string)"=".$akumulasi[6]."")
                        ->setCellValue($a[7].$i,(string)"=".$akumulasi[7]."")
                        ->setCellValue($a[8].$i,(string)"=".$akumulasi[8]."");
                      
                         
                       
                        
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
                  
                    $excel->getActiveSheet(0)->setTitle('Rekap Harian');
                    $excel->setActiveSheetIndex(0);
                    $excel->createSheet();
                    /** ================ list data ============= **/
                    $filter_bulanan=$modelsortir->fromFormcari($keriteria_bulanan,"and");
                    
                    $judul="REKAP TRANSAKSI LOGISTIK BULANAN";
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
                  	->mergeCells($a[1].'4:'.$a[1].'5')->setCellValue($a[1].'4', 'Tanggal')
                    ->mergeCells($a[2].'4:'.$a[2].'5')->setCellValue($a[2].'4', 'Periode')
                    ->mergeCells($a[3].'4:'.$a[3].'5')->setCellValue($a[3].'4', 'Kelompok')
                    ->mergeCells($a[4].'4:'.$a[4].'5')->setCellValue($a[4].'4', 'Barang')
                    ->mergeCells($a[5].'4:'.$a[5].'5')->setCellValue($a[5].'4', 'Harga Satuan')
                  	->mergeCells($a[6].'4:'.$a[8].'4')->setCellValue($a[6].'4', 'Rekap')
                 	->setCellValue($a[6].'5', 'Package')
                    ->setCellValue($a[7].'5', 'Qty')
                    ->setCellValue($a[8].'5', 'Harga')
                    ->mergeCells($a[9].'4:'.$a[9].'5')->setCellValue($a[9].'4', 'Petugas')
                    ->mergeCells($a[10].'4:'.$a[10].'5')->setCellValue($a[10].'4', 'Last Update')
                    ->mergeCells($a[11].'4:'.$a[11].'5')->setCellValue($a[11].'4', 'Dibuat');
                    
                    $excel->getActiveSheet()->getStyle($a[0]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[0])->setWidth(5);
                $excel->getActiveSheet()->getStyle($a[0]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getStyle($a[1]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[1])->setWidth(12);
                $excel->getActiveSheet()->getStyle($a[1]."5")->applyFromArray($style_header);
                
               $excel->getActiveSheet()->getStyle($a[2]."4")->applyFromArray($style_header);
               $excel->getActiveSheet()->getStyle($a[2]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[2])->setWidth(15);
                $excel->getActiveSheet()->getStyle($a[3]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getStyle($a[3]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[3])->setWidth(18);
                $excel->getActiveSheet()->getStyle($a[4]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getStyle($a[4]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[4])->setWidth(15);
                $excel->getActiveSheet()->getStyle($a[5]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getStyle($a[5]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[5])->setWidth(9);
                
                $excel->getActiveSheet()->getStyle($a[6].'4:'.$a[8].'4')->applyFromArray($style_header);//
                $excel->getActiveSheet()->getStyle($a[6]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[6])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[7]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[7])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[8]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[8])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[9]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getStyle($a[9]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[9])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[10]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getStyle($a[10]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[10])->setWidth(17);
                $excel->getActiveSheet()->getStyle($a[11]."4")->applyFromArray($style_header);
                $excel->getActiveSheet()->getStyle($a[11]."5")->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[11])->setWidth(17);
                    
                
                $list_qry3=$db->select("rbb.id,bulan,kelompok_id,mcp_id,
            kel.name nama_kelompok,barang_id,brg.name brg_name,harga_satuan,jml_package,jml_qty,jml_harga,last_update,
            created_time,pegawai_id,kp.pNama,kp.pAlias,kp.pGelarDepan,kp.pGelarBelakang,locked","rekap_barangpakan_bulanan rbb
            inner join kelompok kel on kel.id=rbb.kelompok_id
            inner join barang brg on brg.id=rbb.barang_id
            inner join keswan_pegawai kp on kp.pID=rbb.pegawai_id")
    		->where($filter_bulanan)->orderby("bulan desc,mcp_id asc,kelompok_id asc, barang_id asc")->lim();//
                    
    	           
                    $no         =1;
                    $row_span   =0;
                    $i          =6;
                    $awal       =$i;
                    $tmp_tpk    ="";
                    $row_span   =1;
                    $akumulasi  =array();
                    $first      =0;
                    $index_row_span=0;
                   // $span   = array();
                    $span[$index_row_span]=1;
                    while($rekap = $db->fetchArray($list_qry3))
                    {
    	         //echo "<pre>";print_r($rekap);echo "</pre>";exit;
                    list($tahun,$bln)=explode("-",$rekap['bulan']);
                    $nama_bulan=$master->namabulanIN((int)$bln);
                    $bulan   =$nama_bulan." ".$tahun;        
    	               if(trim($tmp_tpk)<>trim($rekap['mcp_id'])){
   					 	   $first=$i;//harus paling atas
                            if($i<>$awal){
                                 $excel->setActiveSheetIndex(1)
                                   	->mergeCells($a[0].$i.':'.$a[5].$i)->setCellValueExplicit($a[0].$i,"Sub-Total ", PHPExcel_Cell_DataType::TYPE_STRING)                                  
                                    ->setCellValue($a[6].$i,(string)"=SUM(".$a[6].($i-$row_span).":".$a[6].($i-1).")")                               
                                    ->setCellValue($a[7].$i,(string)"=SUM(".$a[7].($i-$row_span).":".$a[7].($i-1).")")
                                    ->setCellValue($a[8].$i,(string)"=SUM(".$a[8].($i-$row_span).":".$a[8].($i-1).")");
                                    
                                    
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
                                     
                                     /** ========= bagian untuk membantu dalam perhitungan Grand Total ========== */
                                    
                                    $akumulasi[6]=trim($akumulasi[6])<>""?$akumulasi[6]."+".$a[6].$i:$a[6].$i;
                                    $akumulasi[7]=trim($akumulasi[7])<>""?$akumulasi[7]."+".$a[7].$i:$a[7].$i;
                                    $akumulasi[8]=trim($akumulasi[8])<>""?$akumulasi[8]."+".$a[8].$i:$a[8].$i;
                                    
                                     /** ==== ahir bagian untuk membantu dalam perhitungan Grand Total ========== */
                                    
                                    $i++;
                            }
                            
                           
                            $lbl_periode=$periode->convertPeriode($rekap['start_date']);
                       // print_r($lbl_periode);exit;
                            $nama_lengkap=$master->nama_dan_gelar($rekap['pGelarDepan'],$rekap['pNama'],$rekap['pGelarBelakang']);
                            $excel->setActiveSheetIndex(1)->setCellValue($a[0].$i, $no)
                            ->setCellValueExplicit($a[1].$i,$bulan, PHPExcel_Cell_DataType::TYPE_STRING)
	                      	->setCellValue($a[2].$i,$lbl_periode['label'])
	                      	->setCellValue($a[3].$i,$rekap['nama_kelompok'])
                            ->setCellValue($a[4].$i,$rekap['brg_name'])                               
                            ->setCellValue($a[5].$i,$rekap['harga_satuan'])
                            ->setCellValue($a[6].$i,$rekap['jml_package'])
                            ->setCellValue($a[7].$i,$rekap['jml_qty'])
                            ->setCellValue($a[8].$i,$rekap['jml_harga'])
                            ->setCellValue($a[9].$i,$nama_lengkap)
                            ->setCellValue($a[10].$i,$rekap['last_update'])
                            ->setCellValue($a[11].$i,$rekap['created_time']);
                            
                            
                            $tmp_tpk=$rekap['mcp_id']; 
                            $row_span=1;  
                            $span[$index_row_span]=1;
                            $index_row_span++;
                            $no++;
                      
                       }else{
                            $row_span++;
                            $span[$index_row_span]=$row_span;
                            
                            $lbl_periode=$periode->convertPeriode($rekap['start_date']);
                       // print_r($lbl_periode);exit;
                            $nama_lengkap=$master->nama_dan_gelar($rekap['pGelarDepan'],$rekap['pNama'],$rekap['pGelarBelakang']);
                            $excel->setActiveSheetIndex(1)->setCellValue($a[0].$i, $no)
                                ->setCellValueExplicit($a[1].$i,$bulan, PHPExcel_Cell_DataType::TYPE_STRING)
    	                      	->setCellValue($a[2].$i,$lbl_periode['label'])
    	                      	->setCellValue($a[3].$i,$rekap['nama_kelompok'])
                                ->setCellValue($a[4].$i,$rekap['brg_name'])                               
                                ->setCellValue($a[5].$i,$rekap['harga_satuan'])
                                ->setCellValue($a[6].$i,$rekap['jml_package'])
                                ->setCellValue($a[7].$i,$rekap['jml_qty'])
                                ->setCellValue($a[8].$i,$rekap['jml_harga'])
                                ->setCellValue($a[9].$i,$nama_lengkap)
                                ->setCellValue($a[10].$i,$rekap['last_update'])
                                ->setCellValue($a[11].$i,$rekap['created_time']);
                        }       
    		                $excel->getActiveSheet()->getStyle($a[0].$i)->applyFromArray($style_row)
    							   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    							   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    					  	$excel->getActiveSheet()->getStyle($a[1].$i)->applyFromArray($style_row)
    							   ->getAlignment()->setWrapText(true)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);;
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
                           
    		                     
    							  
    		                $i++;   
    		                $no++;
    					//	next($ListRekap['data']);
    					}
                        /** =============== sub total bagian akhir ===================== */
                        $excel->setActiveSheetIndex(1)
                       	->mergeCells($a[0].$i.':'.$a[5].$i)->setCellValueExplicit($a[0].$i,"Sub-Total ", PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue($a[6].$i,(string)"=SUM(".$a[6].($i-$row_span).":".$a[6].($i-1).")")                               
                        ->setCellValue($a[7].$i,(string)"=SUM(".$a[7].($i-$row_span).":".$a[7].($i-1).")")
                        ->setCellValue($a[8].$i,(string)"=SUM(".$a[8].($i-$row_span).":".$a[8].($i-1).")");
                        
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
                         /** =============== end sub total bagian akhir ===================== */
                        
                        /** ========= bagian untuk membantu dalam perhitungan Grand Total ========== */
                      
                        $akumulasi[6]=trim($akumulasi[6])<>""?$akumulasi[6]."+".$a[6].$i:$a[6].$i;
                        $akumulasi[7]=trim($akumulasi[7])<>""?$akumulasi[7]."+".$a[7].$i:$a[7].$i;
                        $akumulasi[8]=trim($akumulasi[8])<>""?$akumulasi[8]."+".$a[8].$i:$a[8].$i;
                       
                         /** ==== ahir bagian untuk membantu dalam perhitungan Grand Total ========== */
            
                        
                        $akhir=($i-1);
                        //exit;
                        $i++;
                         $excel->setActiveSheetIndex(1)
                       	->mergeCells($a[0].$i.':'.$a[2].$i)->setCellValueExplicit($a[0].$i,"Grand Total ", PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue($a[6].$i,(string)"=".$akumulasi[6]."")
                        ->setCellValue($a[7].$i,(string)"=".$akumulasi[7]."")
                        ->setCellValue($a[8].$i,(string)"=".$akumulasi[8]."");
                      
                         
                       
                        
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
                       
                  
                    $excel->getActiveSheet(1)->setTitle('Rekap Bulanan');
                    $excel->setActiveSheetIndex(0);
			
        $sekarang=date("dmY_His");
		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="rekap_transaksi_logistik_'.$sekarang.'.xls"');
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