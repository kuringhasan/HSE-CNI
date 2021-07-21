<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Rekap_Pelayanan_Kasus_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        global $dcistem;
        $this->rekap=new Adm_Rekap_Model();
       
	}
	
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("rekap_pelayanan_kasus");
        $db   = $dcistem->getOption("framework/db");
        $login=new Adm_Login_Model();
        $master=new Master_Ref_Model();
        $KodeDosen=$_SESSION["framework"]["ref_id"] ;
        $Username=$_SESSION["framework"]["current_user"]->Username ;
        $master->referensi_session("sub_sistem");
        $sub_sistem= $_SESSION["referensi"]['sub_sistem'];
       
       $last_update=$db->select("max(rkpLastUpdate) last_update","tbmrekappelayananbydate")->get(0);
        //print_r($last_update);
        $tpl->last_update      = $last_update;
        $list_bulan=$master->listarraybulan();
       	$tpl->list_bulan  = $list_bulan;
        $tpl->ListSubsistem = Model::getOptionList("keswan_kasus_subsistem","SubID","SubNama","SubNama ASC"); 
        $admin  = new Core_Admin_Model();
        $search=$admin->SearchDependingLevel("events","kp.pID");
         $tpl->ListTPK= Model::getOptionList("mcp","id","name","id ASC"); 
        $ListPetugas=Model::getOptionList("petugas_wilayah pw inner join keswan_pegawai kp on kp.pID=pw.pegawai_id","distinct pw.pegawai_id","case when ifnull(kp.pGelarDepan,'')='' and ifnull(kp.pGelarBelakang,'')='' then kp.pNama
            when ifnull(kp.pGelarDepan,'')<>'' and ifnull(kp.pGelarBelakang,'')='' then CONCAT(kp.pGelarDepan,' ',kp.pNama) 
            when ifnull(kp.pGelarDepan,'')='' and ifnull(kp.pGelarBelakang,'')<>'' then CONCAT(kp.pNama,', ',kp.pGelarBelakang) 
            else  CONCAT(kp.pGelarDepan,'. ',kp.pNama,', ',kp.pGelarBelakang)  end petugas_nama","kp.pNama ASC",$search['string']); 
        $tpl->ListPetugas =$ListPetugas;
         $ExportExcel=$login->privilegeInputForm("button","","btn-download-excel","<i class=\"fa fa-file-excel-o\"></i> Excel",$this->page->PageID,"Export","title='Download Excel'class=\"btn btn-primary btn-xs btn-export-excel\" ");
         $tpl->TombolDownload=$ExportExcel;
         $tpl->url_listdata      = url::current("listdata");
         $tpl->url_jsonData		= url::current("jsonData");
         $tpl->url_comboAjax=url::current("comboAjax");
          $tpl->url_export=url::current("Export");
          $tpl->url_refresh      = url::current("refresh");
		$this->tpl->content = $tpl;
		$this->tpl->render();        
    }
    public function listdata($action="listdata") {
 	    global $dcistem;
 	  	
       $db   = $dcistem->getOption("framework/db");
        //$this->rekap->refreshRekap("pelayanan_by_kasus");
        $login=new Adm_Login_Model();
        $master=new Master_Ref_Model();
        $modelsortir	= new Adm_Sortir_Model();
        $login_as = $_SESSION["framework"]["login_as"]; 
        $sub_sistem= $_SESSION["referensi"]['sub_sistem'];
        
        $keriteria      = array();
        $requestData    = $_REQUEST;
        $bulan      = $requestData['columns'][5]['search']['value'];
        $tahun      = $requestData['columns'][6]['search']['value'];
        $judul="REKAP PENGOBATAN BERDASARKAN KASUS";
        
        if( trim($tahun)<>"" ){   //name
            $keriteria[]="rkpTahun ='".$tahun."'";
            $judul=$judul."<br />Tahun ".$tahun;
        } 
        if( trim($bulan)<>"" ){   //name
                $keriteria[]="rkpBulan =".(int)$bulan."";
                $nama_bln=$master->namabulanIN((int)$bulan);
                if( trim($tahun)<>"" ){
                    $judul=$judul."<br />".$nama_bln." ".$tahun;
                }
        }
       
      //  echo "cek".$judul;
        
        if(trim($action)=="listdata"){
                        
            $subsistem       = $requestData['columns'][0]['search']['value'];
            $petugas    = $requestData['columns'][1]['search']['value'];
           
            if( trim($petugas)<>"" ){   //name
                $keriteria[]="rkpPetugas =".$petugas."";
            }
            if( trim($subsistem)<>"" ){   //name
                $keriteria[]="KasusSubsistem =".$subsistem."";
            }
            if(trim($nama)<>""){
                $keriteria[]="( pelayanan_nama like'%".$nama."%' )" ;
            }
            $filter=$modelsortir->fromFormcari($keriteria,"and");
            $draw=$_REQUEST['draw'];
    		$length=$_REQUEST['length'];
    		$start=$_REQUEST['start'];
            
            $cols=array(0=>"rkpTahun",
                        1=>"rkpBulan");
            $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
                
            $list_qry=$db->select("SQL_CALC_FOUND_ROWS rkpID,rkpTahun,rkpBulan,rkpKasus,KasusPenyakit,KasusSubsistem,rkpJenisPelayanan,
            rkpPetugas,kp.pNama,kp.pAlias,kp.pGelarDepan,kp.pGelarBelakang,rkpJumlah,rkpLastUpdate","tbmrekappelayananbykasus rkp
            inner join keswan_kasus_penyakit kkp on kkp.KasusID=rkp.rkpKasus
            inner join keswan_pegawai kp on kp.pID=rkp.rkpPetugas")
    		->where($filter)->lim($start,$length);//->orderby($order)
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
                $ListData[$i]['ID']=$data->rkpID;
                $ListData[$i]['SubSistem']=$sub_sistem[$data->KasusSubsistem];
                $ListData[$i]['Kasus']=$data->KasusPenyakit;
                $ListData[$i]['PelayananID']=$data->rkpJenisPelayanan;
                $ListData[$i]['Jumlah']=$data->rkpJumlah;
                $ListData[$i]['NomorBulan']=$data->rkpBulan;
                
                $nama_bulan=$master->namabulanIN($data->rkpBulan);
                $ListData[$i]['NamaBulan']=$nama_bulan;
                $ListData[$i]['Tahun']=$data->rkpTahun;
                
                $nama_lengkap=$master->nama_dan_gelar($data->pGelarDepan,$data->pNama,$data->pGelarBelakang);
                
                $ListData[$i]['Petugas']= trim($data->pAlias)<>""?$data->pAlias." - ".$nama_lengkap:$nama_lengkap;
                
               	$tombol=$login->privilegeInputForm("link","","","<i class=\"fa fa-gear\"></i>",$this->page->PageID,"edit","title='Edit Data' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs btn-edit-data\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\"");
               // $tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-fw fa-trash-o\"></i>",$this->page->PageID,"del","title='Hapus Data Pelanggan' data-toggle=\"modal\" data-remote=\"true\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->PartnerNama."\" ");
    			$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"del","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->pelayanan_id."\"");
    			$control=$tombol;  
                $ListData[$i]['Tombol']=$control;
                $i++;
                $no++;
            }
           
            /*$jml=$db->select("count(rkpID) as jml_data","tbmrekappelayananbykasus rkp
            inner join keswan_kasus_penyakit kkp on kkp.KasusID=rkp.rkpKasus
            inner join keswan_pegawai kp on kp.pID=rkp.rkpPetugas
            left join keswan_kasus_subsistem kks on kks.SubID=kkp.KasusSubsistem")->get(0);*/
            
            $hasil['draw']=$draw;
            $hasil['title']=strtoupper($judul);
            $hasil['recordsTotal']=$jml_filtered;
            $hasil['recordsFiltered']=$jml_filtered;//$jml->jml_data;//
    	    $hasil['data']=$ListData;
            echo json_encode($hasil);
            exit;
         }
         if(trim($action)=="rekap"){
            
            $filter=$modelsortir->fromFormcari($keriteria,"and");
            $draw=$_REQUEST['draw'];
    	
            $cols=array(0=>"rkpTahun",
                        1=>"rkpBulan");
            $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
            $group="group by rkpTahun, rkpKasus";
            if(trim($filter)<>""){
                $filter=$filter." ".$group;
                $group="";
            }    
            $list_qry2=$db->select("SQL_CALC_FOUND_ROWS rkpTahun, rkpKasus,KasusPenyakit,KasusSubsistem,
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
            sum(ifnull(rkpJumlah,0)) sub_total","tbmrekappelayananbykasus rkp
            inner join keswan_kasus_penyakit kkp on kkp.KasusID=rkp.rkpKasus $group")
    		->where($filter)->orderby("KasusSubsistem asc")->lim();//
            $no=1;
            $i=0;
            $ListRekap=array();
            $jml_filtered=0;
            $tmp_subsistem="";
            //print_r($sub_sistem);
            $jml_nop=0;
            while($rekap = $db->fetchObject($list_qry2))
            {
                //echo "<pre>";print_r($rekap);echo "</pre>";
                //echo $sub_sistem[$rekap->KasusSubsistem];
                if($i==0){
                    $tmp_subsistem=$sub_sistem[$rekap->KasusSubsistem];//$rekap->SubNama;
                    $filtered_qry=$db->query("SELECT FOUND_ROWS() jml_filtered;");
                    $filtered_data=$db->fetchObject($filtered_qry);
                    //print_r($filtered_data);
                    $jml_filtered= $filtered_data->jml_filtered;
                }
                $judul_sub=$sub_sistem[$rekap->KasusSubsistem];
                
                if(trim($tmp_subsistem)==trim($rekap->KasusSubsistem) ){
                    $jml_nop=$jml_nop+(int)$rekap->jml_nop;
                }
                /*if(trim($tmp_subsistem)<>trim($rekap->SubNama) and $i<>0){
                    $judul_sub="Sub Total";
                    $tmp_subsistem=$rekap->SubNama;
                    $ListRekap[$i]['No']="";
                    $ListRekap[$i]['SusSistem']="";               
                    $ListRekap[$i]['Kasus']=$judul_sub;
                    //$ListRekap[$i]['jml_jan']=$ListRekap[$i]['jml_jan']+$rekap->jml_jan;
                    $ListRekap[$i]['jml_nop']=$jml_nop;
                    $jml_nop=0;
                    $i++;
                }*/
                
                $ListRekap[$i]['No']=$no;
                $ListRekap[$i]['SubSistem']=$sub_sistem[$rekap->KasusSubsistem];//$rekap->SubNama;
                $ListRekap[$i]['SubID']=$rekap->KasusSubsistem;               
                $ListRekap[$i]['Kasus']=$rekap->KasusPenyakit;
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
                $ListRekap[$i]['sub_total']=$rekap->sub_total;
                $tmp_subsistem=$sub_sistem[$rekap->KasusSubsistem];
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
    
        $hasil= $this->rekap->refreshRekap("pelayanan_by_kasus","last_update");
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
		
			$tahun      	= $_POST['dw_tahun'];
            $bulan    = $_POST['dw_bulan'];
	    
	        if(trim($tahun)<>""){
	        	
                $judul1="REKAP PENGOBATAN BERDASARKAN KASUS";
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
                $excel->setActiveSheetIndex(0)->mergeCells('A2:P2')->setCellValue('A2', $judul1);
                $excel->setActiveSheetIndex(0)->mergeCells('A3:P3')->setCellValue('A3', $judul2);
                $excel->getActiveSheet()->getStyle('A2')->applyFromArray($style_judul);
                $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_judul);
                $key=30;
                $excel->setActiveSheetIndex(0)->mergeCells($a[0].'4:'.$a[0].'5')->setCellValue($a[0].'4', 'ID')
                  	->mergeCells($a[1].'4:'.$a[1].'5')->setCellValue($a[1].'4', 'Sub-Sistem')
                    ->mergeCells($a[2].'4:'.$a[2].'5')->setCellValue($a[2].'4', 'Kasus')
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
                $excel->getActiveSheet()->getStyle($a[2].'4:'.$a[15].'4')->applyFromArray($style_header);//
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
                $excel->getActiveSheet()->getStyle($a[15].'4:'.$a[15].'4')->applyFromArray($style_header);
                $excel->getActiveSheet()->getColumnDimension($a[15])->setWidth(9);
                $excel->getActiveSheet()->getStyle($a[15]."5")->applyFromArray($style_header);
                
                
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
		          
				}
			   
        }
     // exit;
        $i=$i+1;

        $excel->getActiveSheet()->setTitle('Rekap Kegiatan');
        $excel->setActiveSheetIndex(0);
        $sekarang=date("dmY_His");
		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="rekap_kegiatan_kasus_'.$sekarang.'.xls"');
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