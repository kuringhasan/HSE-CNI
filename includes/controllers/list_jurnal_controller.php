<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class List_Jurnal_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
        global $dcistem;
       
	}
	
	public function index() {
	   global $dcistem;
    
		$tpl  = new View("list_jurnal");
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
                
            $list_qry=$db->select("SQL_CALC_FOUND_ROWS no_urut,tanggal,no_bukti,description,kode_akun_debit,
            nama_akun_debit,debit,akun_kredit,nama_akun_kredit,kredit","trx_jurnal ")
    		->where($filter)->orderby("no_urut asc")->lim($start,$length);//
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
                 $ListData[$i]['Tanggal']=$data->tanggal;
                $ListData[$i]['NoBukti']=$data->no_bukti;
                
                $ListData[$i]['Description']=$data->description;
                $ListData[$i]['KodeAkunDebit']	=$data->kode_akun_dbit;// $referensi['sex'][$data->pKelamin];
                $ListData[$i]['NamaAkunDebit']	=$data->nama_akun_debit;// $referensi['sex'][$data->pKelamin];
                $ListData[$i]['Debet']=number_format($data->debit,2,",",".");
                $ListData[$i]['KodeAkunKredit']	=$data->akun_kredit;// $referensi['sex'][$data->pKelamin];
                $ListData[$i]['NamaAkunKredit']	=$data->nama_akun_kredit;// $referensi['sex'][$data->pKelamin];
                $ListData[$i]['Kredit']=number_format($data->kredit,2,",",".");;
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
 public function refresh($tahun="") {
    global $dcistem;
 	  	
       $db   = $dcistem->getOption("framework/db");
        set_time_limit(7200);
	    ini_set("memory_limit","1024M"); 
        $filter="id>=1103 and ifnull(penanda_awal,'')='#'";
        $filter5="id>=1103";
        if (trim($tahun)<>""){
            $filter1=$filter." and year(tanggal)='".$tahun."'";
            $filter4=trim($filter5)<>""?$filter5." and year(tanggal)='".$tahun."'":" year(tanggal)='".$tahun."'";
            if(isset($_GET['bulan']) and trim($_GET['bulan'])<>""){
                $bulan=$tahun."-".$_GET['bulan'];
                
                $filter1=$filter." and DATE_FORMAT(tanggal,'%Y-%m')='".$bulan."'";
                $filter4=trim($filter5)<>""?$filter5." and DATE_FORMAT(tanggal,'%Y-%m')='".$bulan."'":" DATE_FORMAT(tanggal,'%Y-%m')='".$bulan."'";
            }
        }
        //$filter=$filter;
        //$filter5=$filter4;
        //generate no urut by tanggal 
        
        $list_qry5=$db->select("id,penanda_awal,no_urut,tanggal,no_bukti,description,kode_akun_debit,DATE_FORMAT(tanggal,'%y%m') bulan,
        description_credit,nama_akun_debit,debit,akun_kredit,nama_akun_kredit,kredit,x_transaction_type,prefix","trx_jurnal")
		->where($filter5)->orderBy("tanggal asc,no_urut asc, penanda_awal desc")->lim();//
      
        $nomor=1;
        while($data5 = $db->fetchObject($list_qry5))
        {
             
                $update_field_odoo="";
                $cek_coa_dr=$db->select("odoo_id,name,kode_akun_lama,nama_akun_lama,currency_id,code,deprecated,
                partner_id,partner_name","account_account")->where("kode_akun_lama='".$data5->kode_akun_debit."'")->get(0);//
                if(!empty($cek_coa_dr)){
                    
                    $update_field_odoo="odoo_account_debit_id=".$cek_coa_dr->odoo_id."";
                   
                }else{
                    if(trim($data5->kode_akun_debit)<>""){
                        $update_field_odoo="message_error='Akun ".$data5->kode_akun_debit." tidak ditemukan di Oaoo'";
                    }
                }
                
                $cek_coa_cr=$db->select("odoo_id,name,kode_akun_lama,nama_akun_lama,currency_id,code,deprecated,
                partner_id,partner_name","account_account")->where("kode_akun_lama='".$data5->akun_kredit."'")->get(0);//
                if(!empty($cek_coa_cr)){
                    
                    if(trim($update_field_odoo)<>""){
                        $update_field_odoo=$update_field_odoo.",odoo_account_credit_id=".$cek_coa_cr->odoo_id."";
                    }else{
                        $update_field_odoo="odoo_account_credit_id=".$cek_coa_cr->odoo_id."";
                    }
                    
                   
                }else{
                     if(trim($data5->akun_kredit)<>""){
                        $update_field_odoo="message_error='Akun ".$data5->akun_kredit." tidak ditemukan di Oaoo'";
                    }
                }
               
                   
                if(trim($update_field_odoo)<>""){   
                    
                    $sql5="UPDATE trx_jurnal SET  $update_field_odoo WHERE id=".$data5->id;
                    echo $sql5."<br />";
                    $db->query($sql5);
                }
                $no++;
            
        }
      exit;
        
        $list_qry=$db->select("id,penanda_awal,journal_id,no_urut,tanggal,no_bukti,description,kode_akun_debit,DATE_FORMAT(tanggal,'%y%m') bulan,
        description_credit,nama_akun_debit,debit,akun_kredit,nama_akun_kredit,kredit,x_transaction_type,prefix","trx_jurnal")
		->where($filter)->orderby("tanggal asc, no_urut asc, penanda_awal desc")->lim();//
        $no=$start+1;
        $i=0;
            
        $temp_akun_kredit="";  
        $no_bukti="";  
        $no=1;
        while($data = $db->fetchObject($list_qry))
        {
            //echo "ce";exit;
            if(trim($data->akun_kredit)<>"" or  trim($data->kode_akun_debit)<>""){
                $prefix=$data->prefix.$data->bulan;
                $filter_getmax="prefix='".$data->prefix."'";
                if (trim($tahun)<>""){
                    $filter_getmax=$filter_getmax." and year(tanggal)='".$tahun."'";
                }
                //$no_bukti=$prefix.$data->bulan.$no;
                
               $get=$db->select("ifnull(max(CAST(right(no_bukti,6)  AS SIGNED)),0) as max_id","trx_jurnal")->where($filter_getmax)->get(0);
                $max_id=(int)$get->max_id+1;
                $id_tmp="000000".(string)$max_id;
                echo $id_tmp."<br />";
                $no_bukti=$prefix.substr($id_tmp,(strlen($id_tmp)-6),6);
                $jurnal_id=(trim($data->journal_id)=="" or $data->journal_id==0)?3:(int)$data->journal_id;
                //$id_sementara_val		=$master->scurevaluetable($id_sementara);
                $desc_credit="";
                /*if(trim($data->description_credit)=="" and trim($data->description)<>""){
                    
                }*/
                
                   
                   
                    
                $sql="UPDATE trx_jurnal SET  no_bukti='".$no_bukti."',journal_id=$jurnal_id  WHERE id=".$data->id;
                echo $sql."<br />";
                $db->query($sql);
                $no++;
                 
            }else{
               
                if(trim($data->akun_kredit)=="" and trim($data->kode_akun_debit)=="" and trim($data->description)==""){
                    $sql3="DELETE FROm trx_jurnal WHERE id=".$data->id;
                    $db->query($sql3);
                 }
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
                if( trim($tahun)<>"" ){   //name
                    $keriteria[]="year(j.tanggal)='".$tahun."'";
                    $judul2="Tahun ".$tahun;
                } 
                /*if( trim($bulan)<>"" ){   //name
                        
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
                    ->setCellValue($a[5].'4', 'Keterangan')//perkiraan
                    ->setCellValue($a[6].'4', 'Journal/Database ID')//ID                    
                    ->setCellValue($a[7].'4', 'Journal Items/Account/Database ID')
                    ->setCellValue($a[8].'4', 'Nama Akun')
                    ->setCellValue($a[9].'4', 'line_ids/name')
                 	->setCellValue($a[10].'4', 'line_ids/date')
                    ->setCellValue($a[11].'4', 'line_ids/debit')
                    ->setCellValue($a[12].'4', 'line_ids/credit')
                    ->setCellValue($a[13].'4', 'Journal Items/Partner/Database ID')
                     ->setCellValue($a[14].'4', 'Partner')
                    ->setCellValue($a[15].'4', 'Message');
              
                
            $filter="";//$modelsortir->fromFormcari($keriteria,"and");
        	$i=5;
				$no=1;
				$awal       =$i;
                $row_span   =0;
                $sub_sistem = array();
                $first      = 0;
                $pengurang  =array();
                    
            if(trim($filter)<>""){
                $filter=$filter." and j.id>=1103";
            }else{
                $filter=" j.id>=1103";
            }           
            $line_date="";
            $no_urut_tmp="";
            $list_qry=$db->select("SQL_CALC_FOUND_ROWS j.id, penanda_awal,no_urut,ref,tanggal,DATE_FORMAT(tanggal,'%Y-%m-%d') Tanggal,no_bukti,x_transaction_type,description,kode_akun_debit,
            nama_akun_debit,odoo_account_debit_id,
            description_credit, debit,akun_kredit,nama_akun_kredit,kredit,
            odoo_account_credit_id,journal_id","trx_jurnal j","array")
    		->where($filter)->orderby("tanggal asc,no_urut asc, penanda_awal desc")->lim();//
                    $no=$start+1;
                    $ListData=array();
                    $temp_no_bukti="";
                    while($data = $db->fetchArray($list_qry))
                    {
                        
                        if(trim($_POST['format'])=="format1"){
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
                        }
                      //  echo "<pre>";print_r($data);echo "</pre>";
                        
                        if(trim($_POST['format'])=="format2"){
                        //if(trim($data['account_id_kredit_odoo'])<>"") and trim($data['account_id_kredit_odoo'])
                           /* $keterangan_debit="";
                            if(trim($data['account_id_debit_odoo'])==""){
                                $keterangan_debit="Kode akun ".$data['kode_akun_debit']." tidak ditemukan di Odoo";
                            }
                            $keterangan_kredit="";
                            if(trim($data['account_id_kredit_odoo'])==""){
                                $keterangan_kredit="Kode akun ".$data['akun_kredit']." tidak ditemukan di Odoo";
                            }*/
                            $id         ="";
                            $tanggal    ="";// $data['Tanggal'];
                            $jenis_trx   ="";// $data['BuktiNo'];
                            $no_trx   ="";// $data['BuktiNo'];
                            $no_bukti   ="";// $data['BuktiNo'];
                            $jurnal_id      ="";
                            $ref            ="";
                            $description_trx    ="";
                            if(trim($data['penanda_awal'])=="#"){
                                $no_urut_tmp=$data['no_urut'];
                                $id             =$data['id'];
                                $tanggal    =$data['Tanggal'];
                                $jenis_trx=$data['x_transaction_type'];
                               // $no_trx     =$data['BuktiNo'];
                                $no_bukti   =$data['no_bukti'];
                                $jurnal_id=trim($data['journal_id'])==""?3:(int)$data['journal_id'];
                                $ref=$data['ref'];
                                $description_trx=$data['description'];
                                $line_date=$data['Tanggal'];
                            }
                            $tanggal_item="";
                            if($no_urut_tmp==$data['no_urut']){
                                $tanggal_item=$line_date;
                            }
                            //line debit
                            if($data['odoo_account_debit_id']<>""){
                                $odoo_account_code="";
                                $odoo_account_name="";
                                $partner_id="";
                                $partner_name="";
                                $cek_coa_dr=$db->select("odoo_id,name,kode_akun_lama,nama_akun_lama,currency_id,code,deprecated,
                                partner_id,partner_name","account_account")->where("kode_akun_lama='".$data['kode_akun_debit']."'")->get(0);//
                                if(!empty($cek_coa_dr)){
                                    $odoo_account_code  =$cek_coa_dr->code;
                                    $odoo_account_name  =$cek_coa_dr->name;;
                                    $partner_id         =$cek_coa_dr->partner_id;;
                                    $partner_name       =$cek_coa_dr->partner_name;;
                                   
                                }
                                
                                
                                $excel->setActiveSheetIndex(0)->setCellValue($a[0].$i, $id)
                                ->setCellValueExplicit($a[1].$i,$tanggal, PHPExcel_Cell_DataType::TYPE_STRING)
                                ->setCellValue($a[2].$i,$jenis_trx)
    	                      	->setCellValue($a[3].$i,$no_bukti)
                        	    ->setCellValue($a[4].$i,$ref)
                                ->setCellValue($a[5].$i,$description_trx)
    	                      	->setCellValue($a[6].$i,$jurnal_id)//Miscellaneous Operations          
                                ->setCellValue($a[7].$i,$data['odoo_account_debit_id'])
                                ->setCellValue($a[8].$i,$odoo_account_code."-".$odoo_account_name)
                                ->setCellValue($a[9].$i,$data['description'])
                                ->setCellValueExplicit($a[10].$i,$tanggal_item, PHPExcel_Cell_DataType::TYPE_STRING)
                                ->setCellValue($a[11].$i,round($data['debit'],3))//debit
                                ->setCellValue($a[12].$i,"")//round($data['kredit'],3)
                                ->setCellValueExplicit($a[13].$i,$partner_id, PHPExcel_Cell_DataType::TYPE_STRING)
                                ->setCellValue($a[14].$i, $partner_name) 
                                ->setCellValue($a[15].$i,$keterangan_kredit);//kredit
                                $i++;
                            }//end line debit
                            if($data['odoo_account_credit_id']<>""){
                                if(trim($data['penanda_awal'])=="#" and $data['odoo_account_debit_id']<>""){
                                    $id             ="";
                                    $tanggal        ="";
                                    $jenis_trx   ="";// $data['BuktiNo'];
                                    $no_bukti       ="";
                                    $jurnal_id      ="";
                                    $ref            ="";
                                    $description_trx="";
                                }
                                $odoo_account_code="";
                                $odoo_account_name="";
                                $partner_id="";
                                $partner_name="";
                                $cek_coa_cr=$db->select("odoo_id,name,kode_akun_lama,nama_akun_lama,currency_id,code,deprecated,
                                partner_id,partner_name","account_account")->where("kode_akun_lama='".$data['akun_kredit']."'")->get(0);//
                                if(!empty($cek_coa_cr)){
                                    $odoo_account_code  =$cek_coa_cr->code;
                                    $odoo_account_name  =$cek_coa_cr->name;;
                                    $partner_id         =$cek_coa_cr->partner_id;;
                                    $partner_name       =$cek_coa_cr->partner_name;;
                                   
                                }
                                $description_credit=$data['description_credit'];
                                if(trim($description_credit)==""){
                                    $description_credit=$data['description'];
                                }
                                $excel->setActiveSheetIndex(0)->setCellValue($a[0].$i, $id)
                                ->setCellValueExplicit($a[1].$i,$tanggal, PHPExcel_Cell_DataType::TYPE_STRING)
                                ->setCellValue($a[2].$i,$jenis_trx)
    	                      	->setCellValue($a[3].$i,$no_bukti)
                        	    ->setCellValue($a[4].$i,$ref)
                                ->setCellValue($a[5].$i,$description_trx)
    	                      	->setCellValue($a[6].$i,$jurnal_id)//Miscellaneous Operations          
                                ->setCellValue($a[7].$i,$data['odoo_account_credit_id'])
                                ->setCellValue($a[8].$i,$odoo_account_code."-".$odoo_account_name)
                                ->setCellValue($a[9].$i,$description_credit)
                                ->setCellValueExplicit($a[10].$i,$tanggal_item, PHPExcel_Cell_DataType::TYPE_STRING)
                                ->setCellValue($a[11].$i,"")//debit
                                ->setCellValue($a[12].$i,round($data['kredit'],3))//round($data['kredit'],3)
                                ->setCellValueExplicit($a[13].$i,$partner_id, PHPExcel_Cell_DataType::TYPE_STRING)
                                ->setCellValue($a[14].$i, $partner_name) 
                                ->setCellValue($a[15].$i,$keterangan_kredit);//kredit
                                $i++;
                            }
                            
                        }//end format2
                        
		                
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