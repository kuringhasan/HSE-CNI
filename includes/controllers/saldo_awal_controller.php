<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Saldo_Awal_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        global $dcistem;
       
	}
	
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("saldo_awal");
        $db   = $dcistem->getOption("framework/db");
        $login=new Adm_Login_Model();
        $KodeDosen=$_SESSION["framework"]["ref_id"] ;
        $Username=$_SESSION["framework"]["current_user"]->Username ;
     
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
    public function listdata() {
 	    global $dcistem;
 	  	
       $db   = $dcistem->getOption("framework/db");
      
        $login=new Adm_Login_Model();
        $master=new Master_Ref_Model();
        $modelsortir	= new Adm_Sortir_Model();
        $login_as = $_SESSION["framework"]["login_as"]; 
        $referensi  = $master->referensi_session();
        $requestData= $_REQUEST;
        
        $nama       = $requestData['columns'][1]['search']['value'];
        $alias    = $requestData['columns'][0]['search']['value'];
        $tahun    = $requestData['columns'][6]['search']['value'];
        
        
        
        $draw=$_REQUEST['draw'];
		$length=$_REQUEST['length'];
		$start=$_REQUEST['start'];
        $jml_filtered   = 0;
        $ListData=array();
       
            //$keriteria[]="year(j.tanggal)=$tahun";
            $filter=$modelsortir->fromFormcari($keriteria,"and");
            $cols=array(0=>"no_urut");
            $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];
                
            $list_qry=$db->select("SQL_CALC_FOUND_ROWS id,odoo_account_id,odoo_account_code,odoo_account_name,
            old_account_code,old_account_name,debit,kredit,amount","trx_saldo_awal ")
    		->where($filter)->orderby("old_account_code asc")->lim($start,$length);//
            $no=$start+1;
            $i=0;
            
            
            while($data = $db->fetchObject($list_qry))
            {
                if($i==0){
                    $filtered_qry=$db->query("SELECT FOUND_ROWS() jml_filtered;");
                    $filtered_data=$db->fetchObject($filtered_qry);
                    //print_r($filtered_data);
                    $jml_filtered= $filtered_data->jml_filtered;
                }
                $ListData[$i]['No']=$no;
                $ListData[$i]['ID']=$data->pID;
                 $ListData[$i]['odoo_account_id']=$data->odoo_account_id;
                $ListData[$i]['odoo_account_code']=$data->odoo_account_code;
                
                $ListData[$i]['odoo_account_name']=$data->odoo_account_name;
                $ListData[$i]['old_account_code']	=$data->old_account_code;// $referensi['sex'][$data->pKelamin];
                $ListData[$i]['old_account_name']	=$data->old_account_name;// $referensi['sex'][$data->pKelamin];
                $ListData[$i]['debit']=number_format($data->debit,2,",",".");               
                $ListData[$i]['kredit']=number_format($data->kredit,2,",",".");;
                $ListData[$i]['amount']=number_format($data->amount,2,",",".");;
                $ListData[$i]['Aksi']="";
               
                $i++;
                $no++;
            }
        //echo "<pre>"; print_r($ListData);echo "</pre>";exit;
       // $jml=$db->select("count(ID) as jml_data","jurnal")->get(0);
        
        $hasil['draw']=$draw;
       
        $hasil['recordsTotal']=$jml_filtered;//$jml->jml_data;
        $hasil['recordsFiltered']=$jml_filtered;//$jml->jml_data;//
	    $hasil['data']=$ListData;
         //echo $hasil;
        echo json_encode($hasil);exit;
         // echo "<pre>"; print_r($hasil);echo "</pre>";
	}
 public function refresh() {
    global $dcistem;
 	  	
       $db   = $dcistem->getOption("framework/db");
        set_time_limit(7200);
	    ini_set("memory_limit","1024M"); 
       
       
       $list_qry=$db->select("id,odoo_account_id,odoo_account_code,odoo_account_name,
            old_account_code,old_account_name,debit,kredit,amount","trx_saldo_awal")->lim();//
        
        $i=0;
            
        while($data = $db->fetchObject($list_qry))
        {
            $cek_coa=$db->select("odoo_id,name,kode_akun_lama,nama_akun_lama,currency_id,code,deprecated,
            partner_id,partner_name","account_account")->where("kode_akun_lama='".$data->old_account_code."'")->get(0);//
            if(!empty($cek_coa)){
                echo "<pre>";print_r($cek_coa);echo "</pre>";
                $col_and_vals="odoo_account_id=".$cek_coa->odoo_id.",odoo_account_code='".$cek_coa->code."',
                odoo_account_name='".$cek_coa->name."',label='".trim($cek_coa->nama_akun_lama)."'";
               
                if(trim($cek_coa->partner_id)<>""){
                    $col_and_vals=$col_and_vals.",partner_id=".$cek_coa->partner_id.",partner_name='".$cek_coa->partner_name."'";
                }
                $sqlu="UPDATE trx_saldo_awal SET $col_and_vals WHERE id=".$data->id."";
                echo $sqlu."<br />";
                $db->query($sqlu);
            }
            
            $i++;
            
            
        }
       
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
	 	
	    set_time_limit(7200);
	    ini_set("memory_limit","1024M"); 
	
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
	    	
	       
	
	        $excel->getProperties()->setCreator("CERIA")
	            				   ->setLastModifiedBy("Hasan")
	            				   ->setTitle("Journal Entri")
	            				   ->setSubject("Journal Entri")
	            				   ->setDescription("Transaksi")
	            				   ->setKeywords("Finance");
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
		
			$tahun     =$_POST['dw_tahun'];
            
            $bulan    =$_POST['dw_bulan'];
            //print_r($_POST);exit;
	        $keriteria   = array();
	        if(trim($tahun)<>""){
	        	
                $judul1="JURNAL ENTRI";
                /*if( trim($tahun)<>"" ){   //name
                    $keriteria[]="year(j.tanggal)='".$tahun."'";
                    $judul2="Tahun ".$tahun;
                } 
                if( trim($bulan)<>"" ){   //name
                        
                        $keriteria[]="tanggal =".(int)$bulan."";
                        $nama_bln=$master->namabulanIN((int)$bulan);
                        if( trim($tahun)<>"" ){
                            $judul2=$nama_bln." ".$tahun;
                        }
                }*/
                $excel->setActiveSheetIndex(0)->mergeCells('A2:P2')->setCellValue('A2', $judul1);
                $excel->setActiveSheetIndex(0)->mergeCells('A3:P3')->setCellValue('A3', $judul2);
                $excel->getActiveSheet()->getStyle('A2')->applyFromArray($style_judul);
                $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_judul);
                $key=30;
                $excel->setActiveSheetIndex(0)->setCellValue($a[0].'4', 'No')
                  	->setCellValue($a[1].'4', 'date')
                    ->setCellValue($a[2].'4', 'Jenis Transaksi')//no bukti
                    ->setCellValue($a[3].'4', 'name')//no bukti
                  	->setCellValue($a[4].'4', 'ref')//perkiraan
                    ->setCellValue($a[5].'4', 'Journal/Database ID')//ID                    
                    ->setCellValue($a[6].'4', 'Journal Items/Account/Database ID')
                    ->setCellValue($a[7].'4', 'Nama Akun')
                    ->setCellValue($a[8].'4', 'line_ids/name')
                 	->setCellValue($a[9].'4', 'line_ids/date')
                    ->setCellValue($a[10].'4', 'line_ids/debit')
                    ->setCellValue($a[11].'4', 'line_ids/credit')
                    ->setCellValue($a[12].'4', 'Journal Items/Partner/Database ID')
                     ->setCellValue($a[13].'4', 'Partner')
                    ->setCellValue($a[14].'4', 'Keterangan');
              
                
                $filter=$modelsortir->fromFormcari($keriteria,"and");
	        	$i=5;
					$no=1;
					$awal       =$i;
                    $row_span   =0;
                    $sub_sistem = array();
                    $first      = 0;
                    $pengurang  =array();
                    
                       
            $list_qry=$db->select("SQL_CALC_FOUND_ROWS no_urut,tanggal,no_bukti,x_transaction_type,description,kode_akun_debit,
            nama_akun_debit,ad.odoo_id account_id_debit_odoo,ad.name account_name_debit_odoo,
            ak.odoo_id account_id_kredit_odoo,ak.name account_name_kredit_odoo,
            debit,akun_kredit,nama_akun_kredit,kredit,ad.partner_id partner_id_debit,
            ak.partner_id partner_id_kredit,journal_id","trx_jurnal j
            left join account_account ad on trim(ad.kode_akun_lama)=trim(j.kode_akun_debit)
            left join account_account ak on trim(ak.kode_akun_lama)=trim(j.akun_kredit)","array")
    		->where($filter)->orderby("no_urut asc")->lim();//
                    $no=$start+1;
                    $ListData=array();
                    $temp_no_bukti="";
                    while($data = $db->fetchArray($list_qry))
                    {
                        //if(trim($data['account_id_kredit_odoo'])<>"") and trim($data['account_id_kredit_odoo'])
                            $keterangan_debit="";
                            if(trim($data['account_id_debit_odoo'])==""){
                                $keterangan_debit="Kode akun ".$data['kode_akun_debit']." tidak ditemukan di Odoo";
                            }
                            $keterangan_kredit="";
                            if(trim($data['account_id_kredit_odoo'])==""){
                                $keterangan_kredit="Kode akun ".$data['akun_kredit']." tidak ditemukan di Odoo";
                            }
                            $id         ="";
                            $tanggal    ="";// $data['Tanggal'];
                            $no_trx   ="";// $data['BuktiNo'];
                            $no_bukti   ="";// $data['BuktiNo'];
                            $jurnal_id   ="";
                            if(trim($temp_no_bukti)<>trim($data['no_bukti']) and trim($data['no_bukti'])<>""){
                                $id             =$data['no_urut'];
                                
                                $tanggal        =$data['tanggal'];
                                //$tanggal        =$data['tanggal'];
                                $no_trx         =$data['x_transaction_type'];
                                $no_bukti       =$data['no_bukti'];
                                $temp_no_bukti  =$data['no_bukti'];
                                $jurnal_id=trim($data['journal_id'])==""?3:(int)$data['journal_id'];
                                 //definisi data kredit
                                $excel->setActiveSheetIndex(0)->setCellValue($a[0].$i, $id)
                                ->setCellValueExplicit($a[1].$i,$tanggal, PHPExcel_Cell_DataType::TYPE_STRING)
                                ->setCellValue($a[2].$i,$no_trx)
    	                      	->setCellValue($a[3].$i,$no_bukti)
                        	    ->setCellValue($a[4].$i,"")
    	                      	->setCellValue($a[5].$i,$jurnal_id)//Miscellaneous Operations          
                                ->setCellValue($a[6].$i,$data['account_id_kredit_odoo'])
                                ->setCellValue($a[7].$i,$data['account_name_kredit_odoo'])
                                ->setCellValue($a[8].$i,$data['description'])
                                ->setCellValueExplicit($a[9].$i,$data['tanggal'], PHPExcel_Cell_DataType::TYPE_STRING)
                                ->setCellValue($a[10].$i,"")//kredit
                                ->setCellValue($a[11].$i,round($data['kredit'],3))
                                ->setCellValueExplicit($a[12].$i,$data['partner_id_kredit'], PHPExcel_Cell_DataType::TYPE_STRING)
                                ->setCellValue($a[13].$i, $data['no_urut']) 
                                ->setCellValue($a[14].$i,$keterangan_kredit);//kredit
                                //definisi data debit
                                $i++;
                                //if(trim($data['account_id_debit_odoo'])<>""){
                                    
                                    $id         ="";
                                     $tanggal    ="";// $data['Tanggal'];
                                    $no_bukti   ="";// $data['BuktiNo'];
                                    $no_trx="";
                                    $jurnal_id   ="";
                                    $excel->setActiveSheetIndex(0)->setCellValue($a[0].$i, $id)
                                    ->setCellValueExplicit($a[1].$i,$tanggal, PHPExcel_Cell_DataType::TYPE_STRING)
        	                      	->setCellValue($a[2].$i,$no_trx)
                                      ->setCellValue($a[3].$i,$no_bukti)
                            	    ->setCellValue($a[4].$i,"")
        	                      	->setCellValue($a[5].$i,$jurnal_id)//Miscellaneous Operations          
                                    ->setCellValue($a[6].$i,$data['account_id_debit_odoo'])
                                    ->setCellValue($a[7].$i,$data['account_name_debit_odoo'])
                                    ->setCellValue($a[8].$i,$data['description'])
                                    ->setCellValueExplicit($a[9].$i,$data['tanggal'], PHPExcel_Cell_DataType::TYPE_STRING)
                                    ->setCellValue($a[10].$i,round($data['debit'],3))//kredit
                                    ->setCellValue($a[11].$i,"")//$data['kredit']
                                    ->setCellValueExplicit($a[12].$i,$data['partner_id_debit'], PHPExcel_Cell_DataType::TYPE_STRING)
                                    ->setCellValue($a[13].$i, $data['no_urut']) 
                                    ->setCellValue($a[14].$i,$keterangan_debit);
                               // }
                            
                            }else{
                                 //definisi data debit
                                //definisi data debit
                                    $id         ="";
                                $tanggal    ="";// $data['Tanggal'];
                                $no_trx="";
                                $no_bukti   ="";// $data['BuktiNo'];
                                $jurnal_id   ="";
                                if(trim($data['account_id_kredit_odoo'])<>"" and trim($data['account_id_debit_odoo'])==""){
                                    $excel->setActiveSheetIndex(0)->setCellValue($a[0].$i, $id)
                                    ->setCellValueExplicit($a[1].$i,$tanggal, PHPExcel_Cell_DataType::TYPE_STRING)
        	                      	->setCellValue($a[2].$i,$no_trx)
                                      ->setCellValue($a[3].$i,$no_bukti)
                            	    ->setCellValue($a[4].$i,"")
        	                      	->setCellValue($a[5].$i,$jurnal_id)//Miscellaneous Operations          
                                    ->setCellValue($a[6].$i,$data['account_id_kredit_odoo'])
                                    ->setCellValue($a[7].$i,$data['account_name_kredit_odoo'])
                                    ->setCellValue($a[8].$i,$data['description'])
                                    ->setCellValueExplicit($a[9].$i,$data['tanggal'], PHPExcel_Cell_DataType::TYPE_STRING)
                                    ->setCellValue($a[10].$i,"")//kredit
                                    ->setCellValue($a[11].$i,round($data['kredit'],3))
                                    ->setCellValueExplicit($a[12].$i,$data['partner_id_kredit'], PHPExcel_Cell_DataType::TYPE_STRING)
                                    ->setCellValue($a[13].$i, "") 
                                    ->setCellValue($a[14].$i,$keterangan_kredit);//kredit
                                }else{
                                //if(trim($data['account_id_debit_odoo'])<>""){
                                    $excel->setActiveSheetIndex(0)->setCellValue($a[0].$i, $id)
                                    ->setCellValueExplicit($a[1].$i,$tanggal, PHPExcel_Cell_DataType::TYPE_STRING)
        	                      	->setCellValue($a[2].$i,$no_trx)
                                      ->setCellValue($a[3].$i,$no_bukti)
                            	    ->setCellValue($a[4].$i,"")
        	                      	->setCellValue($a[5].$i,$jurnal_id)//Miscellaneous Operations          
                                    ->setCellValue($a[6].$i,$data['account_id_debit_odoo'])
                                    ->setCellValue($a[7].$i,$data['account_name_debit_odoo'])
                                    ->setCellValue($a[8].$i,$data['description'])
                                    ->setCellValueExplicit($a[9].$i,$data['tanggal'], PHPExcel_Cell_DataType::TYPE_STRING)
                                    ->setCellValue($a[10].$i,round($data['debit'],3))//kredit
                                    ->setCellValue($a[11].$i,"")//$data['kredit']
                                    ->setCellValueExplicit($a[12].$i,$data['partner_id_debit'], PHPExcel_Cell_DataType::TYPE_STRING)
                                    ->setCellValue($a[13].$i, "") 
                                    ->setCellValue($a[14].$i,$keterangan_debit);
                               }
                            }
                        
		               
		                $i++;   
		                $no++;
					}
                    //echo $first;
                    
			   
        }
     // exit;
        $i=$i+1;

        $excel->getActiveSheet()->setTitle('Journal Entries');
        $excel->setActiveSheetIndex(0);
        $sekarang=date("dmY_His");
		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="journal_entri_'.$tahun.'_'.$sekarang.'.xls"');
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