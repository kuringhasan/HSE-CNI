<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 *
 * @author Hasan <san2_1981@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class Plan_Production_Controller extends Admin_Template_Controller {

	public function __construct() {
		parent::__construct();
        global $dcistem;

	}

	public function index() {
	   global $dcistem;

		$tpl  = new View("plan_production");
        $db   = $dcistem->getOption("framework/db");
        $login=new Adm_Login_Model();
        $KodeDosen=$_SESSION["framework"]["ref_id"] ;
        $Username=$_SESSION["framework"]["current_user"]->Username ;
        $admin  = new Core_Admin_Model();
        $search=$admin->SearchDependingLevel("ref_dome","id");
        //echo "<pre>";print_r($search);echo "</pre>";
        $filter="ifnull(is_contractor,0)=1";
        if(trim($search['string'])<>""){
            $filter="ifnull(is_contractor,0)=1 and ".$search['string'];
        }
        $list_contractor=Model::getOptionList("partner","id","name","name ASC",$filter);

        $tpl->list_contractor =$list_contractor;

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
        $ref=$master->referensi_session();

        $modelsortir	= new Adm_Sortir_Model();
        $login_as = $_SESSION["framework"]["login_as"];
        $admin  = new Core_Admin_Model();
       // $search=$admin->SearchDependingLevel("ref_dome","dm.contractor_id");
        $keriteria      = array();
        //$keriteria      = $search['array'];
        $requestData= $_REQUEST;
        $nama       = $requestData['columns'][1]['search']['value'];
        //$category    = $requestData['columns'][1]['search']['value'];

		$contractor    = $requestData['columns'][2]['search']['value'];
		$status    = $requestData['columns'][3]['search']['value'];
        if( trim($contractor)<>"" ){   //name
            $keriteria[]="dm.contractor_id =".$contractor."";
        }
        if( trim($status)<>"" ){   //name
            $keriteria[]="closed ='".$status."'";
        }
        if(trim($nama)<>""){
            $keriteria[]="( dm.name like'%".$nama."%' )" ;
        }
        $filter=$modelsortir->fromFormcari($keriteria,"and");
        $draw=$_REQUEST['draw'];
		$length=$_REQUEST['length'];
		$start=$_REQUEST['start'];

        $cols=array(0=>"pp.id",
                    1=>"pp.tahun",
					2=>"pp.contractor_id");
        $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];

        $list_qry=$db->select("SQL_CALC_FOUND_ROWS pp.id,pp.tahun,pp.target_production,pp.contractor_id,
        p.name contractor_name,p.alias contractor_alias,pp.state,pp.lastupdated,pp.created_time,pp.note","plan_production pp
        inner join partner p on p.id=pp.contractor_id")
		->where($filter)->lim($start,$length);//orderby($order)->
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
			$ListData[$i]['id']=$data->id;
            $ListData[$i]['contractor_id']=$data->contractor_id;
            $ListData[$i]['contractor_name']=$data->contractor_name;
            $ListData[$i]['contractor_alias']=$data->contractor_alias;
            $ListData[$i]['tahun']=$data->tahun;
		/*	$ListData[$i]['week_periode_id']=$data->week_periode_id;
            $ListData[$i]['week']=$data->week;*/
            $ListData[$i]['target_production']=$data->target_production;
            $ListData[$i]['description']=$data->description;
            $ListData[$i]['created_time']=$data->created_time;
            $ListData[$i]['lastupdated']=$data->lastupdated;
            $url_del      = url::current("del",$data->id);
		    $urlUpadateSubmit = url::current("updateSubmit");
			$url_edit =url::current("edit",$data->id);
           	$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","btn-edit-data","<i class=\"fa fa-gear\"></i>",$this->page->PageID,"edit","title='Edit Data' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs btn-edit-data\" target=\"_blank\"   ");
            $tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-file-text-o\"></i>",$this->page->PageID,"detail","title='Detail' href=\"".$url_detail."\" class=\"btn btn-primary btn-xs btn-detail-data\" target=\"_blank\"  role=\"".$data->id."\" data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\"");
			$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"del","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->KasusID."\"");
			$control=$tombol;
            $ListData[$i]['Tombol']=$control;
            $i++;
            $no++;
        }

        $hasil['draw']=$draw;
        $hasil['title']=strtoupper($judul);;
        $hasil['recordsTotal']=$jml_filtered;
        $hasil['recordsFiltered']=$jml_filtered;//
	    $hasil['data']=$ListData;
         //echo $hasil;
        echo json_encode($hasil);exit;
         // echo "<pre>"; print_r($hasil);echo "</pre>";
	}
  public function add($proses=""){
        global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        $dome=new Ref_Dome_Model();
        date_default_timezone_set("Asia/Jakarta");
        if(trim($proses)=="save") {

	        $nama	=trim($_POST['name']);
	        $contractor	=trim($_POST['contractor_id']);
	        $closed	=trim($_POST['closed']);
            $empty	=trim($_POST['empty']);

	        $validasi=$this->validasiform("add");

	        if(count($validasi['arrayerror'])==0){
		       $rslt=$dome->insert($nama,$contractor,$closed,$empty);
                $msg=$rslt;


	        }else{
	             $msg['success']	=false;
	             $msg['message']	=	"Terjadi kesalahan pengisian form";
	             $msg['form_error']=$validasi['arrayerror'];
	        }

	        echo json_encode($msg);
	    }else{

	    	$tpl  = new View("form_dome");
            $admin  = new Core_Admin_Model();
            $search=$admin->SearchDependingLevel("ref_dome","id");
            //echo "<pre>";print_r($search);echo "</pre>";
            $filter="ifnull(is_contractor,0)=1";
            if(trim($search['string'])<>""){
                $filter="ifnull(is_contractor,0)=1 and ".$search['string'];
            }
    	    $list_contractor=Model::getOptionList("partner","id","name","name ASC",$filter);

        	$tpl->list_contractor =$list_contractor;

	    	$tpl->url_add = url::current("add");
	    	$tpl->url_jsonData		= url::current("jsonData");
        	$tpl->url_comboAjax		=url::current("comboAjax");
	    	$tpl->content = $tpl;
	        $tpl->render();
	    }
  }
	public function edit($id,$proses=""){
		global $dcistem; $db   = $dcistem->getOption("framework/db");
		$master=new Master_Ref_Model(); $dome=new Ref_Dome_Model(); date_default_timezone_set("Asia/Jakarta");
		if(trim($proses)=="save")
		{
			if(trim($id)<>"")
			{
				$nama	= $_POST['name'];
        $contractor	= $_POST['contractor_id'];
        $closed	= $_POST['closed'];
        $empty	= $_POST['empty'];

				$validasi = $this->validasiform("add");
				if(count($validasi['arrayerror'])==0){
					$sqlin="";

				    $msg=$dome->update($id,$nama,$contractor,$closed,$empty);

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
			$tpl  = new View("form_edit_plan");
			$plan = new Plan_Production_Model();
            $admin  = new Core_Admin_Model();
           $login=new Adm_Login_Model();
            $filter="ifnull(is_contractor,0)=1";
            if(trim($search['string'])<>""){
                $filter="ifnull(is_contractor,0)=1 and ifnull(active,'active')='active'";
            }
			$list_contractor=Model::getOptionList("partner","id","name","name ASC",$filter);

			$tpl->list_contractor =$list_contractor;
			$detail=$plan->getPlanProduction($id);
//echo "<pre>";print_r($detail);echo "</pre>";
			$tpl->detail = $detail;
			$tpl->url_add = url::current("add");
			$tpl->url_jsonData		= url::current("jsonData");
			$tpl->url_comboAjax		=url::current("comboAjax");
            $url_generate		=url::current("generate",$id);
            $TombolGenerate=$login->privilegeInputForm("link","","btn-generate-data","<i class=\"fa fa-fw fa-plus-circle\"></i> Generate Breakdown",$this->page->PageID,"generate","title='Generate Breakdown Plan' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_generate."\" class=\"btn btn-primary btn-xs\" data-target=\"#largeModal\"");
       	    $tpl->TombolGenerate      = $TombolGenerate;
            $this->tpl->breadcrumb_detail_title   ="Edit Plan Production";
            //$this->tpl->breadcrumb_detail_url   =url::current();
			$this->tpl->content = $tpl;
			$this->tpl->render();
		}
	}


public function del($id){
     global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        date_default_timezone_set("Asia/Jakarta");
        $dome=new Ref_Dome_Model();
         //VALIDASI FORM DULU
         $msg=$dome->delete($id);
        echo json_encode($msg);
  }
  public function generate($id){
     global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        date_default_timezone_set("Asia/Jakarta");
         $plan=new Plan_Production_Model();
         //VALIDASI FORM DULU
         $msg=$plan->generateBreakdown($id);
        echo json_encode($msg);
  }
  public function detail($id){
     global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        date_default_timezone_set("Asia/Jakarta");
        $plan=new Plan_Production_Model();
         //VALIDASI FORM DULU
         $msg=$plan->generateBreakdown($id);
        echo json_encode($msg);
  }
  public function validasiform($aksi="add",$kode_lama="")
    {
        global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $msg=array();
      	$pesan=array();

    	if(trim($_POST['name'])==''){
            $pesan["name"]="Nama dome harus diisi!";
            $msg[]="Nama dome harus diisi!";
		}

		if(trim($_POST['contractor_id'])==''){
            $pesan["contractor_id"]="Kontraktor harus diisi!";
            $msg[]="Kontraktor harus diisi!";
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
