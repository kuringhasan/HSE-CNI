<?php
/**
 * @package Admin
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Upload_Production_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
	  	global $dcistem;
		$tpl  = new View("upload_production");
        $db   = $dcistem->getOption("framework/db"); 
        $master=new Master_Ref_Model();
        $login=new Adm_Login_Model();
        
		$url_excel      = url::current("ExportExcel"); 
		$btn_download=$login->privilegeInputForm("link","","link-excel","<i class=\"fa fa-fw fa-file-excel-o\"></i>",$this->page->PageID,"ExportExcel","title='Excel' target=\"_blank\" href=\"".$url_excel."\" class=\"btn btn-primary btn-xs\"");
        if(trim($tombol)<>""){
      		$tombol=$tombol."&nbsp;".$btn_excel;
      	}
      	
      
	    $tpl->url_upload     = url::current("upload","upload");
        $tpl->url_listdata      = url::current("listdata");
        $tpl->url_action_default      = url::current("index");
        $tpl->url_jsonData		= url::current("jsonData");
	    $tpl->url_comboAjax		=url::current("comboAjax");
		$this->tpl->content = $tpl;
		$this->tpl->render();
	    
   }
  public function listdata($proses="") {
 	    global $dcistem;
 	  
        $db   = $dcistem->getOption("framework/db");
        $page=new Core_Page_Model();
        
        $login=new Adm_Login_Model();
        $modelpage		= new Adm_Paging_Model();
        $master			=new Master_Ref_Model();
        $modelsortir	= new Adm_Sortir_Model();
        $adm			= new Core_Admin_Model();
		$referensi		=$master->referensi_session();
        $ref_id			= $_SESSION["framework"]["ref_id"];
        $login_as 	= $_SESSION["framework"]["login_as"]; 
        $prodi      = $_POST['crProdi'];
        $kelas_prodi      = $_POST['crKelasKuliah'];
        $tahun_akademik     = $_POST['crTahunAkademik'];
        $kode_mk	 	=$_POST['crMataKuliah'];
        $kode_kelas	 	=$_POST['crKelasKuliah'];
        $tpl  = new View("upload_listmahasiswa");
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
        	$data	= $this->krs->getMahasiswaKelasByMataKuliahDosen($this->DataUmum->KodeDosen,$tahun_akademik,$kode_mk,$kode_kelas,$urutkan);
        	//echo "<pre>";print_r($data);echo "</pre>";
		    $tpl->detail = $data;
        }else{
        	$lengkap=false;
        }
      
        $tpl->lengkap=$lengkap;
        $tpl->url_listdata      = url::current("listdata");
        $tpl->url_jsonData		= url::current("jsonData");
		$tpl->content   = $tpl;
		$tpl->render();
		
	}  
   public function upload($proses="") {
		global $dcistem;
		$db = $dcistem->getOption("framework/db");
		date_default_timezone_set("Asia/Jakarta");
		$master=new Master_Ref_Model();
		$referensi=$master->referensi_session();
        $periode=new List_Periode_Model();
		$history	=new App_History_Model();
		$tpl  		= new View("konfirmasi_upload");
		$msg=array();
		$new_id="";
		$Pesan	= "";
		if(trim($proses)=="upload")
		{
		//	echo "<pre>";print_r($_FILES);echo "</pre>";exit;
	        //$tpl  = new View("hasil_uploadsmup");
	        $master=new Master_Ref_Model();
	       // $modxls = new Adm_Excel_Model();
	        $ref_id=$_SESSION["framework"]["ref_id"];
	       //$username=$_SESSION["framework"]['current_user']->Username;
	        $Operator=$_SESSION["framework"]['current_user']->Username;
	   
	        $msj="";
	        $msg=array();
	    	$pathfile		=$_FILES['file_excel']["tmp_name"];
	        $AwalRowData	=$_POST['awal_row_data'];
	        $login_as		=	$_SESSION['framework']['login_as'];      	
	        $ref_id			=$_SESSION["framework"]["ref_id"] ;
	        $size 			= $_FILES['file_excel']['size'];
		  
	        $extension 		= pathinfo($_FILES['file_excel']['name'],PATHINFO_EXTENSION);   
	        $psn_error="";
	        if($size==0){
	        	$psn_error="Tidak ada file yang diupload";
	        }else{
		         if(!in_array($extension,array("xls","xlsx"))){
		        	$psn_error="Format file harus xls atau xlsx";
		        }	
	        }
	       
	       
       		
	        $Tanggal		=date("YmdHis");
	        $nmfile 		= "files/format_upload_production".$ref_id."_".$Tanggal.".".$extension;    
			if(trim($psn_error)==""){
		        if(move_uploaded_file($_FILES['file_excel']["tmp_name"],$nmfile))
		        {
	        
	            require_once 'plugins/PHPExcel/Classes/PHPExcel.php';
	                
	            if ($extension == 'xls') $xlsReader = new PHPExcel_Reader_Excel5();
	            else	$xlsReader = new PHPExcel_Reader_Excel2007();
	    
	            $objPHPExcel = $xlsReader->load($nmfile);
	            
	            //$sheets = $objPHPExcel->getActiveSheet(2)->toArray(null,true,true,true);  
	           //echo "<pre>";print_r($sheets);echo "</pre>";
               $msg_err="";
               $listhasilch=array();
               switch($_POST['category']){
                case "daily":
                    if(trim($_POST['jml_sheet'])==""){
                        $msg_err="Isi jumlah sheet, paling tidak satu";
                    }
                    if(trim($msg_err)==""){
                        $jml_sheet=$_POST['jml_sheet'];
                        $i=0;
                        for($j=0;$j<$jml_sheet;$j++){
                            
                            $sheets =$objPHPExcel->setActiveSheetIndex($j)->toArray(null,true,true,true);
                            $AwalRowData=trim($AwalRowData)==""?6:(int)$AwalRowData-1;
            	            $sheetData=array_slice($sheets,$AwalRowData,8);
                            //echo "<pre>";print_r($sheetData);echo "</pre>";
                            
                            
                            $week=(int)$sheets[1]['C'];
                            $tanggal_mulai_stamp=strtotime($sheets[2]['D']);
                            $tanggal_mulai= date("Y-m-d",$tanggal_mulai_stamp);
                            $bulan_mulai=date("m",$tanggal_mulai_stamp);
                            $tahun_mulai=date("Y",$tanggal_mulai_stamp);
                            
                            $tanggal_akhir_stamp=strtotime($sheets[2]['F']);
                            $tanggal_akhir= date("Y-m-d",$tanggal_akhir_stamp);
                            $bulan_akhir=date("m",$tanggal_akhir_stamp);
                            $tahun_akhir=date("Y",$tanggal_akhir_stamp);
                            
                            $tanggal_array=$sheets[5];                           
                            $tanggal_lengkap=array();
                            foreach($tanggal_array as $key=>$value){
                                $key_sebelum=chr(ord($key)-1);
                                if($key>="C" and $key<="I"){
                                     
                                     $convert_tanggal="00".$tanggal_array[$key];
                                     $tgl_val= substr($convert_tanggal,strlen($convert_tanggal)-2,2);
                                     $tanggal_asli=$tahun_mulai."-".$bulan_mulai."-".$tgl_val;
                                     if($bulan_mulai<>$bulan_akhir){
                                         if($tanggal_array[$key_sebelum]>$tanggal_array[$key]){
                                            //ganti bulan
                                            $tanggal_asli=$tahun_akhir."-".$bulan_akhir."-".$tgl_val;
                                            
                                         }
                                     }
                                     $tanggal_lengkap[$key]=$tanggal_asli;
                                }
                              
                                
                            } 
                          
                            //echo  "key skrg :".$key_sebelum." - Key sblm :".ord($key_sebelum)."<br />";
                            if (!empty($sheetData)) 
 	                        {
 	                          $TglSkrg		=date("Y-m-d H:i:s");
                              $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
 	                           while($data=current($sheetData))
    	                       {
     	                            $partner_id=$data['B'];
                                    
                                    if(trim($partner_id)<>""){
                                        foreach($data as $key1=>$value1){
                                            if($key1>="C" and $key1<="I"){
                                                $listhasilch[$i]['week']			=$week;
                                                $listhasilch[$i]['kontraktor']	=$partner_id;
                                                $tanggal_laporan=$tanggal_lengkap[$key1];
                                                $listhasilch[$i]['tanggal']			=$tanggal_laporan;
                                                $tanggal_laporan_val		=$master->scurevaluetable($tanggal_laporan,"string");
                                                $week_val		=$master->scurevaluetable($week,"number");
                                                $qty            =$data[$key1];
                                                $qty_val		=$master->scurevaluetable($qty,"number");
                                                $listhasilch[$i]['qty']			=$qty;
                                                $cek_data=$db->select("id","production")->where("tanggal='".$tanggal_laporan."' and partner_id=".$partner_id."")->get(0);
                                                $sql="";
                                                $psn="";
                                                if(empty($cek_data)){//insert
                                                    $cols="tanggal,week,partner_id,qty,created";
                                               	    $values="$tanggal_laporan_val,$week_val,$partner_id,$qty_val,$tgl_skrg_val";
                                                    $sql="INSERT INTO production ($cols) VALUES ($values);";
                                                    $psn="Data productioan sudah diinsert";
                                                }else{//update
                                                    $cols_and_vals="tanggal=$tanggal_laporan_val,week=$week_val,partner_id=$partner_id,
                                                        qty=$qty_val,lastupdate=$tgl_skrg_val";
                                                    
                                                    $sql="UPDATE production SET $cols_and_vals WHERE tanggal='".$tanggal_laporan."' and partner_id=".$partner_id."";
                                                    $psn="Data productioan sudah diupdate";
                                                }
                                                $rslc=$db->query($sql);
                                                if(isset($rslc->error) and $rslc->error===true){
                                           	 		//$hasil['success']=false;
                                                	$listhasilch[$i]['message']="Error, ".$rslc->query_last_message;//." ".$sql;
                                                    $listhasilch[$i]['success']=false;
                                        	    }else{
                                                    //$hasil['success']=true;
                                                   	$listhasilch[$i]['message']=$psn;//." ".$sql;
                                                    $listhasilch[$i]['success']=true;
                                                }
                                            }
                                          
                                            $i++;
                                        } //end foreach
                                    }
                                    
                                    
                                    next($sheetData);
                                }
                            }//jika data tidak kosong
                            
                            
                             echo "<pre>";print_r($listhasilch);echo "</pre>";
                            
                        }
                    }else{
                        echo $msg_err;
                    }
                    exit;
                break;//end daily
                case "weekly":
                    $sheets =$objPHPExcel->setActiveSheetIndex(0)->toArray(null,true,true,true);
    	            $AwalRowData=trim($AwalRowData)==""?1:(int)$AwalRowData-1;
    	            $sheetData=array_slice($sheets,$AwalRowData);
    	           
    	            $kom=$sheets[4];
    	            $_SESSION['sukses']=false;
    	            if (count($sheetData)>0) 
    	            {
    	            
    	            	
    	                $i=1; 
    	                $listhasilch=array();
    	                while($data=current($sheetData))
    	                {
    	                	
    	                   /** 1. Cek KRS
    	                    *  2. cek apakah dosen bersangkutan
    	                    * ================================================================ */
                       		$week		=text::filter(trim($data['A']),"lcase ucase num");
                       		$tahun			=text::filter(trim($data['B']),"lcase ucase num");
                       		$kontraktor_id	=text::filter(trim($data['C']),"lcase ucase num");
                     
                            if (trim($week)<>"" and trim($tahun)<>"" and trim($kontraktor_id)<>"") {
                                $get_periode=$periode->get_periode($week,$tahun);
                                //print_r($get_periode);
                                $periode_id=$get_periode->id;
                            	$listhasilch[$i]['week']			=$week;
    	                    	$listhasilch[$i]['tahun']			=$tahun;
    	                    	$listhasilch[$i]['kontraktor']	=$kontraktor_id;
    	                    	
    	                    	
                                $tgl_skrg=date("Y-m-d H:i:s");
    	        				$tgl_skrg_val	=$master->scurevaluetable($tgl_skrg,"string");
                                $qty        =TEXT::filter($data['D'],"num");
                                $listhasilch[$i]['qty']		=$qty;
                                $filter="partner_id=".$kontraktor_id." and periode_id=".$periode_id." ";
                               
    							$cekdata= $db->select("id","report_weekly_production")->where($filter)->get(0);
                                if(!empty($cekdata)){
                                	//update production
                                	$msg_err="";
                                	
    								if(trim($msg_err)==""){
    									$cols_and_vals="";
    									if(trim($data['D'])<>""){
    							        	$qty_val	=$master->scurevaluetable($qty,"number",false);
    							        	$cols_and_vals=trim($cols_and_vals)<>""?$cols_and_vals.",qty=$qty_val":"qty=$qty_val";
    							        }
                                        
    							        $pre_total=$db->select("sum(qty) total","report_weekly_production rwp
                                        inner join week_periode wp on wp.id=rwp.periode_id")->where("partner_id=".$kontraktor_id." and wp.week<".$week."")->get(0);
                                       // print_r($pre_total);
                                        $total  = $pre_total->total+$qty;
                                        $listhasilch[$i]['total']	=$total;
                                        $total_val	=$master->scurevaluetable($total,"number",false);
    						        	$cols_and_vals=trim($cols_and_vals)<>""?$cols_and_vals.",total=$total_val":"total=$total_val";
                                        
                                        
    							        if(trim($cols_and_vals)<>""){
    							        	//$cols_and_vals=$cols_and_vals.",krsTanggalUpdate=$tgl_skrg_val";
    									    $sqlin	="UPDATE report_weekly_production SET $cols_and_vals WHERE $filter";								
    										//echo $sqlin."<br />";
                                            $rsl=$db->query($sqlin);
    										if(isset($rsl->error) and $rsl->error===true){
    							                $listhasilch[$i]['success']=false;
    						        			$listhasilch[$i]['message']="Error, ".$rsl->query_last_message." ".$sqlin;
    										}else{
    											
    											$listhasilch[$i]['success']=true;
    						        			$listhasilch[$i]['message']="Nilai berhasil diupload";
    							            }
    							        }else{
    							        	$listhasilch[$i]['success']=false;
    						        		$listhasilch[$i]['message']="Query kosong";
    							        }
    									$i=$i+1;
    								}else{
    									$listhasilch[$i]['success']=false;
    						        	$listhasilch[$i]['message']=$msg_err;
    								}
                                	
                                }else{
                                		//insert production
                                    $msg_err="";
                                	
    								if(trim($msg_err)==""){
    									$cols   ="partner_id,periode_id";
                                        $vals   ="$kontraktor_id,$periode_id";
    									if(trim($data['D'])<>""){
    							        	$qty_val	=$master->scurevaluetable($qty,"number",false);
    							        	$cols=trim($cols)<>""?$cols.",qty":"qty";
                                            $vals=trim($vals)<>""?$vals.",$qty_val":"$qty_val";
    							        }
                                        
    							        $pre_total=$db->select("sum(qty) total","report_weekly_production rwp
                                        inner join week_periode wp on wp.id=rwp.periode_id")->where("partner_id=".$kontraktor_id." and wp.week<".$week."")->get(0);
                                        $total  = $pre_total->total+$qty;
                                        $listhasilch[$i]['total']	=$total;
                                        $total_val	=$master->scurevaluetable($total,"number",false);
    						        	$cols=trim($cols)<>""?$cols.",total":"total";
                                        $vals=trim($vals)<>""?$vals.",$total_val":"$total_val";
                                        
                                        
    							        if(trim($cols)<>""){
    							        
    									    $sqlin	="INSERT INTO report_weekly_production ($cols) VALUES($vals);";								
    										$rsl=$db->query($sqlin);
    									//	echo $sqlin."<br />";
                                            if(isset($rsl->error) and $rsl->error===true){
    							                $listhasilch[$i]['success']=false;
    						        			$listhasilch[$i]['message']="Error, ".$rsl->query_last_message." ".$sqlin;
    										}else{
    											
    											$listhasilch[$i]['success']=true;
    						        			$listhasilch[$i]['message']="Weekly report berhasil diupload";
    							            }
    							        }else{
    							        	$listhasilch[$i]['success']=false;
    						        		$listhasilch[$i]['message']="Query kosong";
    							        }
    									$i=$i+1;
    								}else{
    									$listhasilch[$i]['success']=false;
    						        	$listhasilch[$i]['message']=$msg_err;
    								}
                                    //echo "<pre>";print_r($listhasilch);echo "</pre>";
                                }
                            	
                            }
                            $i++;
    	                    next($sheetData);
    	                }
    	                // update komponen nilai
    	                
    	            
    	            }
                    break;//end weekly
                }//end switch
        
	        } else{
	            $Pesan = "File yang diupload tidak ada";
	        } 
        } else{
            $Pesan = $psn_error;
        }  
		//echo "<pre>";print_r($listhasilch);echo "</pre>";exit;
       
      
        $tpl->list_hasil=$listhasilch;
        $tpl->Pesan=$Pesan;
		 
        $this->tpl->content = $tpl;
		$this->tpl->render();   
    }else{
				
		    	$tpl  = new View("upload_report");
		    	
		    	
		        $tpl->msg = $msg;
		    	$tpl->url_upload = url::current("upload","upload");
		    	$tpl->url_jsonData		= url::current("jsonData");
	        	$tpl->url_comboAjax		=url::current("comboAjax");
	        	$this->tpl->content_title = "Upload Status Bayar";
		    	$this->tpl->content = $tpl;
				$this->tpl->render();
		  
	    }
	}
  
    public function ExportExcel() {
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
				  	->mergeCells($a[1].'3:'.$a[1].'4')->setCellValue($a[1].'3', 'Kode MK')
	              	->mergeCells($a[2].'3:'.$a[2].'4')->setCellValue($a[2].'3', 'Nama MK')
	             	->mergeCells($a[3].'3:'.$a[3].'4')->setCellValue($a[3].'3', 'Kelas')
				 	->mergeCells($a[4].'3:'.$a[4].'4')->setCellValue($a[4].'3', 'NPM')
	              	->mergeCells($a[5].'3:'.$a[5].'4')->setCellValue($a[5].'3', 'Nama')
					->setCellValue($a[6].'3', 'Tugas')->setCellValue($a[6].'4', $komposisi_nilai->jkomTugas)
					->setCellValue($a[7].'3', 'Sikap')->setCellValue($a[7].'4', $komposisi_nilai->jkomSikap)
					->setCellValue($a[8].'3', 'UTS')->setCellValue($a[8].'4', $komposisi_nilai->jkomUTS)
					->setCellValue($a[9].'3', 'UAS')->setCellValue($a[9].'4', $komposisi_nilai->jkomUAS)
					->setCellValue($a[10].'3', 'Total')->setCellValue($a[10].'4', "=$total")
					->mergeCells($a[11].'3:'.$a[11].'4')->setCellValue($a[11].'3', 'Huruf Mutu')
					->mergeCells($a[12].'3:'.$a[12].'4')->setCellValue($a[12].'3', 'Tahun Akademik');
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
			$excel->getActiveSheet()->getStyle($a[4]."3")->applyFromArray($style_header);
				$excel->getActiveSheet()->getColumnDimension($a[4])->setWidth(15);
				$excel->getActiveSheet()->getStyle($a[4]."4")->applyFromArray($style_header2);
			$excel->getActiveSheet()->getStyle($a[5]."3")->applyFromArray($style_header);
				$excel->getActiveSheet()->getColumnDimension($a[5])->setWidth(35);
				$excel->getActiveSheet()->getStyle($a[5]."4")->applyFromArray($style_header2);
			$excel->getActiveSheet()->getStyle($a[6]."3")->applyFromArray($style_header1);
				$excel->getActiveSheet()->getColumnDimension($a[6])->setWidth(11);
				$excel->getActiveSheet()->getStyle($a[6]."4")->applyFromArray($style_header2);
				$excel->getActiveSheet()->getStyle($a[6]."4")->getNumberFormat()->applyFromArray($persen);
			
			$excel->getActiveSheet()->getStyle($a[7]."3")->applyFromArray($style_header1);
				$excel->getActiveSheet()->getColumnDimension($a[7])->setWidth(11);
				$excel->getActiveSheet()->getStyle($a[7]."4")->getNumberFormat()->applyFromArray($persen);
				$excel->getActiveSheet()->getStyle($a[7]."4")->applyFromArray($style_header2);
			
			$excel->getActiveSheet()->getStyle($a[8]."3")->applyFromArray($style_header1);
				$excel->getActiveSheet()->getColumnDimension($a[8])->setWidth(11);
				$excel->getActiveSheet()->getStyle($a[8]."4")->applyFromArray($style_header2);
				$excel->getActiveSheet()->getStyle($a[8]."4")->getNumberFormat()->applyFromArray($persen);
			$excel->getActiveSheet()->getStyle($a[9]."3")->applyFromArray($style_header1);
				$excel->getActiveSheet()->getColumnDimension($a[9])->setWidth(11);
				$excel->getActiveSheet()->getStyle($a[9]."4")->applyFromArray($style_header2);
				$excel->getActiveSheet()->getStyle($a[9]."4")->getNumberFormat()->applyFromArray($persen);
			$excel->getActiveSheet()->getStyle($a[10]."3")->applyFromArray($style_header1);
				$excel->getActiveSheet()->getColumnDimension($a[10])->setWidth(11);
				$excel->getActiveSheet()->getStyle($a[10]."4")->applyFromArray($style_header2);
				$excel->getActiveSheet()->getStyle($a[10]."4")->getNumberFormat()->applyFromArray($persen);
				
			$excel->getActiveSheet()->getStyle($a[11]."3")->applyFromArray($style_header);
				$excel->getActiveSheet()->getColumnDimension($a[11])->setWidth(11);
				$excel->getActiveSheet()->getStyle($a[11]."4")->applyFromArray($style_header2);
			$excel->getActiveSheet()->getStyle($a[12]."3")->applyFromArray($style_header);
				$excel->getActiveSheet()->getColumnDimension($a[12])->setWidth(11);
				$excel->getActiveSheet()->getStyle($a[12]."4")->applyFromArray($style_header2);
				
			
		
			
	        $lengkap=false;
	    
	        if(trim($tahun_akademik)<>"" and trim($kode_mk)<>""){
	        	
	        	
	        	//=IF(L8>=80;"A";IF(AND(L8>=67,5;L8<80);"B";IF(AND(L8>=50;L8<67,65);"C";IF(AND(L8>=25;L8<50);"D";"E"))))
	        	
	        	//'=IF(K'.$j.'>=80,"A",IF(AND(K'.$j.'>=68,K'.$j.'<80),"B",IF(AND(K'.$j.'>=56,K'.$j.'<68),"C",IF(AND(K'.$j.'>=45,K'.$j.'<56),"D","E"))))'
	        	
	        	
	        	///
	        	
	        //	echo $rumus;exit;
	        	
	        	
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
		                      	->setCellValue($a[3].$i,$detail['NamaKelas'])
		                      	->setCellValueExplicit($a[4].$i, $data['NPM'], PHPExcel_Cell_DataType::TYPE_STRING)
		                      	->setCellValue($a[5].$i, $data['Nama'])
		                      	->setCellValue($a[6].$i,"")
								->setCellValue($a[7].$i,"")
								->setCellValue($a[8].$i,"")
								->setCellValue($a[9].$i,"")
								->setCellValueExplicit($a[10].$i,"=$sigma",PHPExcel_Cell_DataType::TYPE_FORMULA)
								->setCellValue($a[11].$i,'='.$rumus)
								->setCellValueExplicit($a[12].$i, $tahun_akademik, PHPExcel_Cell_DataType::TYPE_STRING);
		                      
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
		                      
		                      $excel->getActiveSheet()->getStyle($a[4].$i)->applyFromArray($style_row)
							  	->getAlignment()->setWrapText(true)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
								  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		                      $excel->getActiveSheet()->getStyle($a[5].$i)->applyFromArray($style_row)
							  	->getAlignment()->setWrapText(true)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
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
								  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);;
							$excel->getActiveSheet()->getStyle($a[12].$i)->applyFromArray($style_row)
								->getAlignment()->setWrapText(true)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
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

	public function comboAjax($kategori,$model="") {
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
        $hasil=array();
        $parentcode=$aVars['parentkode'];
        if($kategori=="prodi"){
        	$prodi=new Ref_Prodi_Model();
        	$hasil=$prodi->combo($parentcode,$aVars['nilai']);
        }
        if($kategori=="kelas_kuliah"){
        	$kelas	= new Kelas_Kuliah_Model();
        	
        	$hasil=$kelas->getComboKelasMataKuliah($parentcode,$aVars['crProdi'],$aVars['crTahunAkademik'],$this->DataUmum->KodeDosen,$aVars['nilai']);
        }
        if($kategori=="mata_kuliah"){
        	$mk	= new Ref_Matakuliah_Model();
        	
        	$hasil=$mk->comboMataKuliahDosenUnik($this->DataUmum->KodeDosen,$aVars['crTahunAkademik'],$aVars['nilai']);
        }
        echo $hasil;
   }
   public function rumusBobotExcel($posisi_kolom_excel,$posisi_kolom_uts="",$posisi_kolom_uas="",$posisi_kolom_sikap="",$posisi_kolom_tugas="") {
       	global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		$ksn=$db->select("ksnNilaiAkhirDari,ksnNlaiAkhirSampai,ksnHurufMutu,ksnBobotIP","tbrKesetaraanNilai")
		->orderby("ksnBobotIP asc")->get();
		$rumus="";
		$i=1;
    	while($kn=current($ksn)){
    	//	echo "<pre>";print_r($kn);echo "</pre>";
    	//	$rumus="(L8>=".$kn->kksnNilaiAkhirDari.";L8<".$kn->ksnNlaiAkhirSampai.");".$kn->ksnHurufMutu;
    		if($kn->ksnBobotIP==0){
    			$rumus='"E"';
    			//$rumus='IF(AND('.$posisi_kolom_excel.'>=0,'.$posisi_kolom_excel.'<'.$kn->ksnNlaiAkhirSampai.'),"E","")';
    		}elseif($kn->ksnBobotIP==1){
    			$rumus='IF(AND('.$posisi_kolom_excel.'>='.$kn->ksnNilaiAkhirDari.','.$posisi_kolom_excel.'<'.$kn->ksnNlaiAkhirSampai.'),"D",'.$rumus.')';
    		}elseif((int)$kn->ksnBobotIP==4){
    		
    			$rumus='IF(AND('.$posisi_kolom_excel.'>='.$kn->ksnNilaiAkhirDari.','.$posisi_kolom_excel.'<=100),"A",'.$rumus.')';
    		}else{
    				$rumus='IF(AND('.$posisi_kolom_excel.'>='.$kn->ksnNilaiAkhirDari.','.$posisi_kolom_excel.'<'.$kn->ksnNlaiAkhirSampai.'),"'.$kn->ksnHurufMutu.'",'.$rumus.')';
    		}
    	
    		$i++;
    		next($ksn);
    	}
    	$rumus='IF(OR(ISBLANK('.$posisi_kolom_uts.'),ISBLANK('.$posisi_kolom_uas.')),"K",'.$rumus.')';
    	$rumus='IF(AND(ISBLANK('.$posisi_kolom_uts.'),ISBLANK('.$posisi_kolom_uas.'),ISBLANK('.$posisi_kolom_sikap.'),ISBLANK('.$posisi_kolom_tugas.')),"",'.$rumus.')';
    	return $rumus;

             
    }  
    public function jsonData($pilih) {
       	global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		$mk	= new Ref_Matakuliah_Model();
        if (sizeof($_POST) > 0) {
		    $aVars = $_POST;
		 } else {
		    if (sizeof($_GET) > 0) {
		        $aVars = $_GET;
		    } else {
		        $aVars = array();
		    }
		 }
		$name	=$aVars['name'];
		$hsl=array();
		if($pilih=="mata_kuliah"){
		    $hsl=$mk->jsonMKDitawarkan($aVars['crTahunAkademik'],$aVars['crProdi'],$name);
	    }
		echo json_encode($hsl);

             
    }  
}
 

?>