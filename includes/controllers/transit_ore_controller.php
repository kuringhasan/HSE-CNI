<?php
/**
 * @package Admin
 * @subpackage Daftar Kota Controller
 *
 * @author Hasan <san2_1981@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class Transit_Ore_Controller extends Admin_Template_Controller {

	public function __construct() {
		parent::__construct();
        global $dcistem;

	}

	public function index() {
	   global $dcistem;
     //
     // $view  = new View("transit_ore_new");
     //
     // $this->tpl->content = $view;
     // $this->tpl->render();

		$tpl  = new View("transit_ore");
        $db   = $dcistem->getOption("framework/db");
        $login=new Adm_Login_Model();
       $master=new Master_Ref_Model();
        $KodeDosen=$_SESSION["framework"]["ref_id"] ;
        $Username=$_SESSION["framework"]["current_user"]->Username ;
        $list_bulan=$master->listarraybulan();
      	$tpl->list_bulan  = $list_bulan;
        $admin  = new Core_Admin_Model();
        $search=$admin->SearchDependingLevel("ref_contractor","id");
        //echo "<pre>";print_r($search);echo "</pre>";
        $filter="ifnull(is_contractor,0)=1";
        if(trim($search['string'])<>""){
            $filter="ifnull(is_contractor,0)=1 and ".$search['string'];
        }
        $list_kontraktor=Model::getOptionList("partner","id","name","",$filter);
            $tpl->list_kontraktor =$list_kontraktor;
       	$url_form = url::current("add");
       	$TombolTambah=$login->privilegeInputForm("link","","btn-tambah-data","<i class=\"fa fa-plus-circle\"></i>",$this->page->PageID,"add","title='Tambah Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_form."\" class=\"btn btn-primary btn-xs\" data-target=\"#largeModal\"");
       	$tpl->TombolTambah      = $TombolTambah;
         $tpl->url_listdata      = url::current("listdata");
         $tpl->url_jsonData		= url::current("jsonData");
         $tpl->url_comboAjax=url::current("comboAjax");
		$this->tpl->content = $tpl;
		$this->tpl->render();
    }

    public function data()
    {
      global $dcistem;

      $db = $dcistem->getOption("framework/db");

      $selectQuery = "
      dto.tanggal,
    	dto.shift,
    	dto.entry_time,
    	dto.sent_time,
    	dto.received_time,
    	lp.block_name,
    	p.name,
    	dto.total_ritase,
    	dto.total_quantity";

      $fromQuery = "
        	daily_transit_ore dto
        JOIN partner p ON
        	dto.contractor_id = p.id
        JOIN lokasi_pit lp ON
        	dto.lokasi_pit_id = lp.id";

      $orderQuery = "dto.tanggal DESC";
      $daily = $db->select($selectQuery, $fromQuery)->orderBy($orderQuery);

      echo json_encode([
        'daily' => $daily->get()
      ]);
    }

		private function detailFormData($dto_id)
		{
			global $dcistem;
			$db = $dcistem->getOption("framework/db");

			$selectQuery =  "
				eq.id,
				CONCAT(eq.nomor, ' (', pt.alias, ')') AS name
			";
			$fromQuery = "
			equipment eq
			JOIN partner pt ON eq.partner_id = pt.id
			";
			// "WHERE
			// eq.id NOT IN (
			// 	SELECT
			// 		dtod.equipment_id
			// 	FROM
			// 		daily_transit_ore dto
			// 	JOIN daily_transit_ore_detail dtod ON dtod.transit_ore_id = dto.id
			// 	WHERE
			// 		dto.id = '".$dto_id."'
			// )
			// ";
			$orderQuery = "pt.alias,
				eq.name";
			$trucks = $db->select($selectQuery, $fromQuery)->orderBy($orderQuery)->get();

			$selectQuery =  "
			brg.id,
			CONCAT(
				brg. NAME,
				CASE
			WHEN brg.capacity IS NOT NULL
			AND brg.capacity > 0 THEN
				CONCAT(' (', brg.capacity, ')')
			ELSE
				''
			END
			) AS name
			";
			$fromQuery = 'barges brg';
			$barges = $db->select($selectQuery, $fromQuery)->get();

			$selectQuery = "dms.id,
			dms.location_name,
			dms.eto_efo";
			$fromQuery = 'dome_locations dms';
			$domeLocations = $db->select($selectQuery, $fromQuery)->get();

			$selectQuery = "dm.id, dm.name, dm.location_id";
			$fromQuery = "domes dm WHERE dm.status = 'open'";
			$domes = $db->select($selectQuery, $fromQuery)->get();

			return [
				'trucks' => $trucks,
				'barges' => $barges,
				'domeLocations' => $domeLocations,
				'domes' => $domes
			];
		}

    public function detailCreateForm()
    {
        global $dcistem;

        $view  = new View("transit_ore_detail_create");
        $data = $this->detailFormData($_GET['dto_id']);

        $view->trucks = $data['trucks'];
        $view->barges = $data['barges'];
        $view->domeLocations = $data['domeLocations'];
        $view->domes = $data['domes'];
        $view->transit_ore_id = $_GET['dto_id'];
      

        $view->render();
    }

		public function detailCreateSubmit()
		{
			global $dcistem;
			$db = $dcistem->getOption("framework/db");

			$requiredColumns = [
				'transit_ore_id', 'equipment_id', 'ritase', 'tujuan_pengangkutan'
			];

			$optionalColumns = [
				'dome_id', 'barge_id', 'quantity'
			];

			$columns = [];
			$values = [];
			foreach ($_POST as $key => $value) {
				if (in_array($key, $requiredColumns) || in_array($key, $optionalColumns)) {
					$columns[] = $key;
					$values[] = ($value!="")?$value:null;
				}
			}
            // var_dump($values);
            $transit_ore_id = $_POST['transit_ore_id'];
			try {
				$cols = implode($columns, ', ');
				// $vals = "'".implode($values, '\', \'') . "'";
                $barge = ($values[2]!=null)?$values[2]:null;
                $dome = ($values[3]!=null)?$values[3]:null;
                if($barge == null){
                    $vals = $values[0].",'".$values[1]."',null,".$dome.",".$values[4].",".$values[5].",".$values[6];

                }else{
                    $vals = $values[0].",'".$values[1]."',".$barge.",null,".$values[4].",".$values[5].",".$values[6];

                }
                var_dump($vals);
				$sql ="INSERT INTO daily_transit_ore_detail ($cols) VALUES($vals);";
				var_dump($db->query($sql));

                $sql ="SELECT SUM(ritase) as tot_ritase, SUM(quantity) as tot_quantity FROM daily_transit_ore_detail WHERE transit_ore_id =$transit_ore_id";
                $sum = $db->query($sql);

                $total_ritase = 0;
                $total_quantity = 0;
                while( $row = mysql_fetch_assoc( $sum)){
                    $total_ritase = $row['tot_ritase'];
                    $total_quantity = $row['tot_quantity'];
                }

                $sql ="UPDATE daily_transit_ore SET total_ritase=$total_ritase, total_quantity=$total_quantity WHERE id =$transit_ore_id";
                $db->query($sql);
			} catch (\Exception $e) {
				LogToDiscord::send($e->getMessage());
			}

		}

		public function detailUpdateSubmit()
		{
			global $dcistem;
			$db = $dcistem->getOption("framework/db");

			$requiredColumns = [
				'id', 'transit_ore_id', 'equipment_id', 'ritase', 'tujuan_pengangkutan'
			];

			$optionalColumns = [
				'dome_id', 'barge_id'
			];

			$values = [];
			foreach ($_POST as $key => $value) {
				if (in_array($key, $requiredColumns) || in_array($key, $optionalColumns)) {
					$values[] = $key.'="'.$value.'"';
				}
			}

			if ($_POST['tujuan_pengangkutan'] == 'BRG') {
					$values[] = 'dome_id=NULL';
			}

			if ($_POST['tujuan_pengangkutan'] == 'ETO' || $_POST['tujuan_pengangkutan'] == 'EFO') {
					$values[] = 'barge_id=NULL';
			}

			$id = $_POST['id'];

			try {
				$sql = 'UPDATE daily_transit_ore_detail SET '.implode(",", $values).' WHERE id='.$id.';';
				$db->query($sql);
			} catch (\Exception $e) {
				LogToDiscord::send($e->getMessage());
			}
		}

		public function detailDeleteSubmit()
		{
			global $dcistem;
			$db = $dcistem->getOption("framework/db");

			$id = $_POST['id'];

			if (!empty($id)) {
				try {
					$sql = "DELETE FROM daily_transit_ore_detail WHERE id = '$id'";
                    $s ="SELECT transit_ore_id FROM daily_transit_ore_detail  WHERE id = '$id'";
                    $transit_ore = $db->query($s);
                    $li = mysql_fetch_array($transit_ore);
                    $transit_ore_id = $li['transit_ore_id'];
                    // var_dump($transit_ore_id);
					if($db->query($sql)){
                        $sql ="SELECT SUM(ritase) as tot_ritase, SUM(quantity) as tot_quantity FROM daily_transit_ore_detail WHERE transit_ore_id =$transit_ore_id";
                        $sum = $db->query($sql);

                        $total_ritase = 0;
                        $total_quantity = 0;
                        if(mysql_num_rows($sum)==0){
                            while( $row = mysql_fetch_array($sum)){
                                $total_ritase = $row['tot_ritase'];
                                $total_quantity = $row['tot_quantity'];
                            }
                            $sql ="UPDATE daily_transit_ore SET total_ritase=$total_ritase, total_quantity=$total_quantity WHERE id =$transit_ore_id";
                            $db->query($sql);
                        }else{
                            $sql ="UPDATE daily_transit_ore SET total_ritase=0, total_quantity=0 WHERE id =$transit_ore_id";
                            $db->query($sql);
                        }
                        
                    };

				} catch (\Exception $e) {
					LogToDiscord::send($e->getMessage());
				}

			}
		}

    public function deleteSubmit()
    {
        global $dcistem;
        $db = $dcistem->getOption("framework/db");

        $id = $_POST['id'];
        
        if (!empty($id)) {
            try {
                $sql = "DELETE FROM daily_transit_ore WHERE id = '$id'";
                if($db->query($sql)){
                    $sql = "DELETE FROM daily_transit_ore_detail WHERE transit_ore_id = '$id'";
                    $db->query($sql);
                };

            } catch (\Exception $e) {
                LogToDiscord::send($e->getMessage());
            }

        }
    }

    public function listdata() {
 	    global $dcistem;

        $db   = $dcistem->getOption("framework/db");

        $login=new Adm_Login_Model();
        $master=new Master_Ref_Model();
        $modelsortir	= new Adm_Sortir_Model();
        $transit_ore    =new Transit_Ore_Model();
        $referensi      = $master->referensi_session();
        $admin  = new Core_Admin_Model();
        $search=$admin->SearchDependingLevel("report_production","dto.contractor_id");
       // echo "<pre>";print_r($search);echo "</pre>";
        $keriteria      = array();
        $keriteria      = $search['array'];
        $login_as = $_SESSION["framework"]["login_as"];

        $requestData= $_REQUEST;


        $tanggal       = $requestData['columns'][1]['search']['value'];;

        $id_anggota     = $requestData['columns'][0]['search']['value'];
        $kontraktor     = $requestData['columns'][4]['search']['value'];
        $checker       = $requestData['columns'][5]['search']['value'];
        $bulan    = $requestData['columns'][7]['search']['value'];
        $tahun    = $requestData['columns'][8]['search']['value'];
         if( trim($tahun)<>"" ){   //name
            $keriteria[]="year(tanggal)  ='".$tahun."'";
            $judul=$judul."<br />Tahun ".$tahun;
        }
        if( trim($bulan)<>"" ){   //name
                $nama_bln=$master->namabulanIN((int)$bulan);
                if( trim($tahun)<>"" ){

                    $keriteria[]="DATE_FORMAT(tanggal,'%Y-%m')='".$tahun."-".$bulan."'";
                    $judul=$judul."<br />".$nama_bln." ".$tahun;
                }
        }
        if( trim($tanggal)<>"" ){   //name
            $keriteria[]="DATE_FORMAT(dto.tanggal,'%d/%m/%Y') like '%".$tanggal."%'";
        }
        if( trim($checker)<>"" ){   //name
            $keriteria[]="dto.operator like '%".$checker."%'";
        }
        if( trim($kontraktor)<>"" ){   //name
            $keriteria[]="dto.contractor_id=".$kontraktor."";
        }
        if( trim($id_anggota)<>"" ){   //name
            $keriteria[]="(a.C_ANGGOTA like'%".$id_anggota."%' or a.NAMA like'%".$id_anggota."%') ";
        }
        if(trim($eartag)<>""){
            $keriteria[]="( c.name like'%".$eartag."%' or  c.name ='".$eartag."' )" ;
        }

        $draw=$_REQUEST['draw'];
       /*Jumlah baris yang akan ditampilkan pada setiap page*/
		$length=$_REQUEST['length'];

		/*Offset yang akan digunakan untuk memberitahu database
		dari baris mana data yang harus ditampilkan untuk masing masing page
		*/

        $start=$_REQUEST['start'];
         $ListData      = array();
         $jml_filtered  = 0;
         $jml_data      = 0;
        // if(trim($tahun)<>"" and trim($bulan)<>""){
            $bulan_tahun=$tahun."-".$bulan;

            $filter=$modelsortir->fromFormcari($keriteria,"and");
            $cols=array(0=>"dto.id",
                        1=>"tanggal",
                        2=>"dto.lokasi_pit_id",
                        3=>"contractor_id");
            $order= $cols[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir'];

            $list_qry=$db->select("SQL_CALC_FOUND_ROWS dto.id,transaction_id,dto.contractor_id,dm.name as dome_name,p.name,p.alias,lokasi_pit_id,block_name,tanggal,DATE_FORMAT(tanggal,'%d/%m/%Y')  tgl,
            entry_time,shift,dto.state,operator,sent_time,received_time,total_ritase,total_quantity","daily_transit_ore dto
            inner join partner p on p.id=dto.contractor_id
            inner join lokasi_pit pit on pit.id=dto.lokasi_pit_id
            left join domes dm on dm.id=dto.dome_id")
    		->where($filter)
            ->orderBy($order)
            ->lim($start,$length);//->orderBy($order)
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
                $ListData[$i]['ID']=$data->id;
                $ListData[$i]['Tanggal']=$data->tgl;
                $ListData[$i]['shift']=$data->shift;
                $ListData[$i]['entry_time']=$data->entry_time;

                $ListData[$i]['sent_time']=$data->sent_time;
                $ListData[$i]['received_time']=$data->received_time;
                $ListData[$i]['pit']=$data->block_name;
                $kontraktor=trim($data->alias)<>""?$data->name." (".$data->alias.")":$data->name;
                $ListData[$i]['Kontraktor']=$kontraktor;
                $ListData[$i]['dome_name']=$data->dome_name;
                $ListData[$i]['total_ritase']=$data->total_ritase;
                $ListData[$i]['total_quantity']=$data->total_quantity;
                $ListData[$i]['state']=$data->state;
		        $ListData[$i]['checker']=$data->operator;
                $ListData[$i]['Detail']=$transit_ore->getTransitOreDetail($data->id,"array");;
                $url_delete      = url::current("deleteSubmit",$data->id);
    			$url_edit =url::current("edit",$data->id);
                $url_detail =url::current("detail",$data->id);
                $url_verifikasi  = url::current("verifikasi",$data->id);
               	$tombol          = "";
                if($data->verification==0){
                   // $tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-check-square-o\"></i>",$this->page->PageID,"verifikasi","title='Verifikasi EX-PIT Ore' href=\"".$url_verifikasi."\" class=\"btn btn-primary btn-xs btn-verifikasi-data\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\" role=\"".$data->id."\"");
                }

                //$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-file-text-o\"></i>",$this->page->PageID,"detail","title='Detail' href=\"".$url_detail."\" class=\"btn btn-primary btn-xs btn-detail-data\" target=\"_blank\"  role=\"".$data->id."\" data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\"");

               	//$tombol=$login->privilegeInputForm("link","","","<i class=\"fa fa-gear\"></i>",$this->page->PageID,"edit","title='Edit Data' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs btn-edit-data\" target=\"_blank\"  data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\"");
               // $tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-fw fa-trash-o\"></i>",$this->page->PageID,"del","title='Hapus Data Pelanggan' data-toggle=\"modal\" data-remote=\"true\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->PartnerNama."\" ");
    			//$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"del","title='Hapus Data' data-toggle=\"modal\" data-remote=\"false\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->pegID."\"");

                // $control=$tombol;
                // $ListData[$i]['Aksi']=$control;

                $tbldraft = "";
                $tbldraft=$tbldraft.$login->privilegeInputForm("link","","","<i class=\"fa fa-file-text-o\"></i>",$this->page->PageID,"detail","title='Detail' href=\"".$url_detail."\" class=\"btn btn-primary btn-xs btn-detail-data\" target=\"_blank\"  role=\"".$data->id."\" data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\"");
                $tbldraft=$tbldraft.$login->privilegeInputForm("link","","","<i class=\"fa fa-edit\"></i>",$this->page->PageID,"edit","title='Edit' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs btn-edit-data\" role=\"".$data->id."\" data-toggle=\"modal\" data-remote=\"false\" data-target=\"#largeModal\"");
                $tbldraft=$tbldraft.$login->privilegeInputForm("link","","","<i class=\"fa fa-trash\"></i>",$this->page->PageID,"deleteSubmit","title='Delete' href='javascript:void(0)' onclick='deleteSubmit(".$data->id.")' class=\"btn btn-danger btn-xs\" role=\"".$data->id."\" ");
                
                
                $ListData[$i]['Aksi']=$tbldraft;
                $i++;
                $no++;
            }

           //$filter_count=$modelsortir->fromFormcari($keriteria_count,"and");

       // }
       $hasil['filter']=$tahun."-".$bulan;
        $hasil['draw']=$draw;
        $hasil['recordsTotal']=$jml_filtered;
        $hasil['recordsFiltered']=$jml_filtered;//$db->numRow($list_qry);
	    $hasil['data']=$ListData;

        echo json_encode($hasil);exit;
         // echo "<pre>"; print_r($hasil);echo "</pre>";
	}
 public function add($proses=""){
    global $dcistem;
    $db   = $dcistem->getOption("framework/db");
    $master=new Master_Ref_Model();
    $pro=new Transit_Ore_Model();
    date_default_timezone_set("Asia/Jakarta");
    if(trim($proses)=="save")
	{

	        $validasi=$this->validasiform("add");
	        if(count($validasi['arrayerror'])==0){
	            $TglSkrg		     =date("Y-m-d H:i:s");
            	// $tgl_skrg_val		 =$master->scurevaluetable($TglSkrg,"string");

                $tgl  = $_POST['datas'][0]['value'];
		        // $tanggal	= $tgl[2]."-".$tgl[1]."-".$tgl[0];
                $tanggal_val  =$master->scurevaluetable($tgl,"string");

                $shift         = $_POST['datas'][1]['value'];
                $shift_val		 =$master->scurevaluetable($shift,"number");

                $lokasi_pit         = $_POST['datas'][2]['value'];
                $lokasi_pit_val		 =$master->scurevaluetable($lokasi_pit,"number");

                $kontraktor         = $_POST['datas'][3]['value'];
                $kontraktor_val		 =$master->scurevaluetable($kontraktor,"number");

                $domes         = explode("/",$_POST['datas'][7]['value']);
                $domes_val		 =$master->scurevaluetable($domes[0],"number");

                // $waktu_masuk         = date("Y-m-d H:i:s",strtotime($_POST["datas"]['waktu_masuk']));
                $waktu_masuk_val		 =$master->scurevaluetable($TglSkrg,"string");

                // $waktu_kirim         = date("Y-m-d H:i:s",strtotime($_POST["datas"]['waktu_kirim']));
                // $waktu_kirim_val		 =$master->scurevaluetable($waktu_kirim,"string");

                // $waktu_terima         = date("Y-m-d H:i:s",strtotime($_POST["datas"]['waktu_terima']));
                // $waktu_terima_val		 =$master->scurevaluetable($waktu_terima,"string");

                $waktumasuk = explode(' ', $TglSkrg);
                $tglmasuk =  explode('-', $waktumasuk[0]);
                $jammasuk =  explode(':', $waktumasuk[1]);
                $transaction_val  = implode('', $tglmasuk).implode('', $jammasuk).$kontraktor;

                $ritases = $_POST["ritases"];

                $total_ritase = 0;
                $total_quantity = 0;
                for ($i=0; $i < count($ritases); $i++) { 
                    $total_ritase += $ritases[$i]['ritase'];
                    $total_quantity += $ritases[$i]['quantity'];
                }
                // $cols="tanggal,transaction_id,shift,lokasi_pit_id,contractor_id,entry_time,sent_time,received_time,total_ritase,total_quantity";

                // $vals="$tanggal_val,$transaction_val,$shift_val,$lokasi_pit_val,$kontraktor_val,$waktu_masuk_val,$waktu_kirim_val,$waktu_terima_val,$total_ritase,$total_quantity";
                $cols="tanggal,transaction_id,shift,lokasi_pit_id,contractor_id,dome_id,total_ritase,total_quantity,entry_time,state,sent_time,received_time";

                if($_GET['type'] == 'simpan'){
                    $vals="$tanggal_val,$transaction_val,$shift_val,$lokasi_pit_val,$kontraktor_val,$domes_val,$total_ritase,$total_quantity,$waktu_masuk_val,'draft',null,null";
                }else{
                    $vals="$tanggal_val,$transaction_val,$shift_val,$lokasi_pit_val,$kontraktor_val,$domes_val,$total_ritase,$total_quantity,$waktu_masuk_val,null,$waktu_masuk_val,$waktu_masuk_val";
                }
                $sqlup ="INSERT INTO daily_transit_ore ($cols) VALUES($vals);";
                $rsl_cust=$db->query($sqlup);

                // var_dump($db->insert_id);
                $sqlup ="SELECT id FROM daily_transit_ore WHERE transaction_id = $transaction_val";
                $lastinsert=$db->query($sqlup);
                $li = mysql_fetch_array($lastinsert);
                $insert_id = $li['id'];
                if(count($ritases) != 0){
                    for ($i=0; $i < count($ritases); $i++) { 
                        $equipment_id = $ritases[$i]['truck_id'];
                        $ritase = $ritases[$i]['ritase'];
                        $quantity = $ritases[$i]['quantity'];
                        $tujuan_pengangkutan = $ritases[$i]['tujuan_pengangkutan'];
                        $dome_id = ($ritases[$i]['dome_id'] != "")? $ritases[$i]['dome_id'] : null;
                        $barge_id = ($ritases[$i]['barge_id'] != "")? $ritases[$i]['barge_id'] : null;

                        $cols="transit_ore_id,equipment_id,ritase,quantity,tujuan_pengangkutan,dome_id,barge_id,state";
                        if($barge_id == null){
                            if($_GET['type'] == 'simpan'){
                                $vals="$insert_id,$equipment_id,$ritase,$quantity,'$tujuan_pengangkutan',$dome_id,null,'draft'";
                            }else{
                                $vals="$insert_id,$equipment_id,$ritase,$quantity,'$tujuan_pengangkutan',$dome_id,null,null";
                            }
                        }else{
                            if($_GET['type'] == 'simpan'){
                                $vals="$insert_id,$equipment_id,$ritase,$quantity,'$tujuan_pengangkutan',null,$barge_id,'draft'";
                            }else{
                                $vals="$insert_id,$equipment_id,$ritase,$quantity,'$tujuan_pengangkutan',null,$barge_id,null";
                            }
                        }
                        $sqlup ="INSERT INTO daily_transit_ore_detail ($cols) VALUES($vals);";
                        $db->query($sqlup);
                    }
                }
                

				if(isset($rsl_cust->error) and $rsl_cust->error===true){
			   	 		$msg['success']=false;
	                	$msg['message']="Error, ".$rsl_cust->query_last_message;
				}else{

	                $msg['success']=true;
	                $msg['message']="Data sudah disimpan";
	            }
	        }else{
	             $msg['success']	=false;
	             $msg['message']	="Terjadi kesalahan pengisian form";
	             $msg['form_error']=$validasi['arrayerror'];
	        }
	    echo json_encode($msg);
	}else{
		    $ip=core::get_ip();
	    	$tpl  = new View("form_transit_ore");
            $list_kontraktor=Model::getOptionList("partner","id","name","","ifnull(is_contractor,0)=1");
            $tpl->list_kontraktor =$list_kontraktor;
            $list_lokasi_pit=Model::getOptionList("lokasi_pit","id","block_name");
            $tpl->list_lokasi_pit =$list_lokasi_pit;

            // $detail= $pro->getTransitOre($id);
           //echo "<pre>";print_r($detail);echo "</pre>";
            // $tpl->detail =$detail;
            $data = $this->detailFormData($_GET['dto_id']);

            $tpl->trucks = $data['trucks'];
            $tpl->barges = $data['barges'];
            $tpl->domeLocations = $data['domeLocations'];
            $tpl->domes = $data['domes'];

            $tpl->url_add            = url::current("add");
            $tpl->url_checkdata =url::current("checkdata");
	    	$tpl->url_jsonData		= url::current("jsonData");
        	$tpl->url_comboAjax		=url::current("comboAjax");
	    	$tpl->content = $tpl;
	        $tpl->render();
    }
  }

  public function edit($id,$proses = ""){
    global $dcistem;
    $db   = $dcistem->getOption("framework/db");
    $tpl  = new View("form_transit_ore");
    $master=new Master_Ref_Model();
    if(trim($proses)=="save")
	{
        $validasi=$this->validasiform("add");
	        if(count($validasi['arrayerror'])==0){
	            $TglSkrg		     =date("Y-m-d H:i:s");
            	// $tgl_skrg_val		 =$master->scurevaluetable($TglSkrg,"string");

                $tgl  = $_POST['datas'][0]['value'];
		        // $tanggal	= $tgl[2]."-".$tgl[1]."-".$tgl[0];
                $tanggal_val  =$master->scurevaluetable($tgl,"string");

                $shift         = $_POST['datas'][1]['value'];
                $shift_val		 =$master->scurevaluetable($shift,"number");

                $lokasi_pit         = $_POST['datas'][2]['value'];
                $lokasi_pit_val		 =$master->scurevaluetable($lokasi_pit,"number");

                $kontraktor         = $_POST['datas'][3]['value'];
                $kontraktor_val		 =$master->scurevaluetable($kontraktor,"number");

                $domes         = explode("/",$_POST['datas'][7]['value']);
                $domes_val		 =$master->scurevaluetable($domes[0],"number");
                // $waktu_masuk         = date("Y-m-d H:i:s",strtotime($_POST["datas"]['waktu_masuk']));
                // $waktu_masuk_val		 =$master->scurevaluetable($TglSkrg,"string");

                // $waktu_kirim         = date("Y-m-d H:i:s",strtotime($_POST["datas"]['waktu_kirim']));
                // $waktu_kirim_val		 =$master->scurevaluetable($waktu_kirim,"string");

                // $waktu_terima         = date("Y-m-d H:i:s",strtotime($_POST["datas"]['waktu_terima']));
                // $waktu_terima_val		 =$master->scurevaluetable($waktu_terima,"string");

                $waktumasuk = explode(' ', $TglSkrg);
                $tglmasuk =  explode('-', $waktumasuk[0]);
                $jammasuk =  explode(':', $waktumasuk[1]);
                $transaction_val  = implode('', $tglmasuk).implode('', $jammasuk).$kontraktor;

                $ritases = $_POST["ritases"];

                $total_ritase = 0;
                $total_quantity = 0;
                for ($i=0; $i < count($ritases); $i++) { 
                    $total_ritase += $ritases[$i]['ritase'];
                    $total_quantity += $ritases[$i]['quantity'];
                }
                // $cols="tanggal,transaction_id,shift,lokasi_pit_id,contractor_id,entry_time,sent_time,received_time,total_ritase,total_quantity";

                // $vals="$tanggal_val,$transaction_val,$shift_val,$lokasi_pit_val,$kontraktor_val,$waktu_masuk_val,$waktu_kirim_val,$waktu_terima_val,$total_ritase,$total_quantity";
                // $cols="tanggal,transaction_id,shift,lokasi_pit_id,contractor_id,total_ritase,total_quantity,entry_time,state";


                $set="tanggal=$tanggal_val,transaction_id=$transaction_val,shift=$shift_val,lokasi_pit_id=$lokasi_pit_val,contractor_id=$kontraktor_val,total_ritase=$total_ritase,total_quantity=$total_quantity,dome_id=$domes_val";

                $sqlup ="UPDATE daily_transit_ore SET $set  WHERE id = $id;";
                $rsl_cust=$db->query($sqlup);

                // var_dump($db->insert_id);
                $sqlup ="DELETE FROM daily_transit_ore_detail WHERE transit_ore_id = $id;";
                $db->query($sqlup);

                if(count($ritases) != 0){
                    for ($i=0; $i < count($ritases); $i++) { 
                        $equipment_id = $ritases[$i]['truck_id'];
                        $ritase = $ritases[$i]['ritase'];
                        $quantity = $ritases[$i]['quantity'];
                        $tujuan_pengangkutan = $ritases[$i]['tujuan_pengangkutan'];
                        $dome_id = ($ritases[$i]['dome_id'] != "")? $ritases[$i]['dome_id'] : null;
                        $barge_id = ($ritases[$i]['barge_id'] != "")? $ritases[$i]['barge_id'] : null;

                        $cols="transit_ore_id,equipment_id,ritase,quantity,tujuan_pengangkutan,dome_id,barge_id,state";
                        if($barge_id == null){
                                $vals="$id,$equipment_id,$ritase,$quantity,'$tujuan_pengangkutan',$dome_id,null,'draft'";
                                
                        }else{
                                $vals="$id,$equipment_id,$ritase,$quantity,'$tujuan_pengangkutan',null,$barge_id,'draft'";
                                
                        }
                        $sqlup ="INSERT INTO daily_transit_ore_detail ($cols) VALUES($vals);";
                        $db->query($sqlup);
                    }
                }
                

				if(isset($rsl_cust->error) and $rsl_cust->error===true){
			   	 		$msg['success']=false;
	                	$msg['message']="Error, ".$rsl_cust->query_last_message;
				}else{

	                $msg['success']=true;
	                $msg['message']="Data sudah disimpan";
	            }
	        }else{
	             $msg['success']	=false;
	             $msg['message']	="Terjadi kesalahan pengisian form";
	             $msg['form_error']=$validasi['arrayerror'];
	        }
	    echo json_encode($msg);
    }elseif(trim($proses)=="kirim")
	{
        $validasi=$this->validasiform("add");
        if(count($validasi['arrayerror'])==0){
            $TglSkrg		     =date("Y-m-d H:i:s");
            // $tgl_skrg_val		 =$master->scurevaluetable($TglSkrg,"string");

            $tgl  = $_POST['datas'][0]['value'];
            // $tanggal	= $tgl[2]."-".$tgl[1]."-".$tgl[0];
            $tanggal_val  =$master->scurevaluetable($tgl,"string");

            $shift         = $_POST['datas'][1]['value'];
            $shift_val		 =$master->scurevaluetable($shift,"number");

            $lokasi_pit         = $_POST['datas'][2]['value'];
            $lokasi_pit_val		 =$master->scurevaluetable($lokasi_pit,"number");

            $kontraktor         = $_POST['datas'][3]['value'];
            $kontraktor_val		 =$master->scurevaluetable($kontraktor,"number");

            // $waktu_masuk         = date("Y-m-d H:i:s",strtotime($_POST["datas"]['waktu_masuk']));
            // $waktu_masuk_val		 =$master->scurevaluetable($TglSkrg,"string");
            $waktu_kirim		 =$master->scurevaluetable($TglSkrg,"string");
            $waktu_terima		 =$master->scurevaluetable($TglSkrg,"string");

            // $waktu_kirim         = date("Y-m-d H:i:s",strtotime($_POST["datas"]['waktu_kirim']));
            // $waktu_kirim_val		 =$master->scurevaluetable($waktu_kirim,"string");

            // $waktu_terima         = date("Y-m-d H:i:s",strtotime($_POST["datas"]['waktu_terima']));
            // $waktu_terima_val		 =$master->scurevaluetable($waktu_terima,"string");

            $waktumasuk = explode(' ', $TglSkrg);
            $tglmasuk =  explode('-', $waktumasuk[0]);
            $jammasuk =  explode(':', $waktumasuk[1]);
            $transaction_val  = implode('', $tglmasuk).implode('', $jammasuk).$kontraktor;

            $ritases = $_POST["ritases"];

            $total_ritase = 0;
            $total_quantity = 0;
            for ($i=0; $i < count($ritases); $i++) { 
                $total_ritase += $ritases[$i]['ritase'];
                $total_quantity += $ritases[$i]['quantity'];
            }
            // $cols="tanggal,transaction_id,shift,lokasi_pit_id,contractor_id,entry_time,sent_time,received_time,total_ritase,total_quantity";

            // $vals="$tanggal_val,$transaction_val,$shift_val,$lokasi_pit_val,$kontraktor_val,$waktu_masuk_val,$waktu_kirim_val,$waktu_terima_val,$total_ritase,$total_quantity";
            // $cols="tanggal,transaction_id,shift,lokasi_pit_id,contractor_id,total_ritase,total_quantity,entry_time,state";


            $set="tanggal=$tanggal_val,transaction_id=$transaction_val,shift=$shift_val,lokasi_pit_id=$lokasi_pit_val,contractor_id=$kontraktor_val,total_ritase=$total_ritase,total_quantity=$total_quantity,state=null,sent_time=$waktu_kirim,received_time=$waktu_terima";

            $sqlup ="UPDATE daily_transit_ore SET $set  WHERE id = $id;";
            $rsl_cust=$db->query($sqlup);

            // var_dump($db->insert_id);
            $sqlup ="DELETE FROM daily_transit_ore_detail WHERE transit_ore_id = $id;";
            $db->query($sqlup);

            if(count($ritases) != 0){
                for ($i=0; $i < count($ritases); $i++) { 
                    $equipment_id = $ritases[$i]['truck_id'];
                    $ritase = $ritases[$i]['ritase'];
                    $quantity = $ritases[$i]['quantity'];
                    $tujuan_pengangkutan = $ritases[$i]['tujuan_pengangkutan'];
                    $dome_id = ($ritases[$i]['dome_id'] != "")? $ritases[$i]['dome_id'] : null;
                    $barge_id = ($ritases[$i]['barge_id'] != "")? $ritases[$i]['barge_id'] : null;

                    $cols="transit_ore_id,equipment_id,ritase,quantity,tujuan_pengangkutan,dome_id,barge_id,state";
                    if($barge_id == null){
                            $vals="$id,$equipment_id,$ritase,$quantity,'$tujuan_pengangkutan',$dome_id,null,'draft'";
                            
                    }else{
                            $vals="$id,$equipment_id,$ritase,$quantity,'$tujuan_pengangkutan',null,$barge_id,'draft'";
                            
                    }
                    $sqlup ="INSERT INTO daily_transit_ore_detail ($cols) VALUES($vals);";
                    $db->query($sqlup);
                }
            }
            

            if(isset($rsl_cust->error) and $rsl_cust->error===true){
                        $msg['success']=false;
                    $msg['message']="Error, ".$rsl_cust->query_last_message;
            }else{

                $msg['success']=true;
                $msg['message']="Data sudah disimpan";
            }
        }else{
             $msg['success']	=false;
             $msg['message']	="Terjadi kesalahan pengisian form";
             $msg['form_error']=$validasi['arrayerror'];
        }
        echo json_encode($msg);
    }else{

     $transit_ore    =new Transit_Ore_Model();
    date_default_timezone_set("Asia/Jakarta");
    $detail=$transit_ore->getTransitOre($id);
    
     $list_kontraktor=Model::getOptionList("partner","id","name","","ifnull(is_contractor,0)=1");
    $tpl->list_kontraktor =$list_kontraktor;
    $list_lokasi_pit=Model::getOptionList("lokasi_pit","id","block_name");
    $tpl->list_lokasi_pit =$list_lokasi_pit;

    $data = $this->detailFormData($id);

    $tpl->trucks = $data['trucks'];
    $tpl->barges = $data['barges'];
    $tpl->domeLocations = $data['domeLocations'];
    $tpl->domes = $data['domes'];

    $tpl->detail=$detail;
    $tpl->content = $tpl;
    $tpl->render();
    }
  }

  public function verifikasi($id,$proses=""){
     global $dcistem;
    $db   = $dcistem->getOption("framework/db");
    $master=new Master_Ref_Model();
    $pro=new List_Production_Model();
    $username=$_SESSION["framework"]["current_user"]->Username ;
    date_default_timezone_set("Asia/Jakarta");
    if(trim($proses)=="save")
	{
	    if(trim($id)<>"")
	    {
	        $validasi=$this->validasiform("verifikasi");
	        if(count($validasi['arrayerror'])==0){
	            $TglSkrg		     =date("Y-m-d H:i:s");
            	$tgl_skrg_val		 =$master->scurevaluetable($TglSkrg,"string");
                $username_val    =$master->scurevaluetable($username,"string");
                $tgl  = explode("/",$_POST["frm_tanggal"]);

		        $tanggal	    = $tgl[2]."-".$tgl[1]."-".$tgl[0];
                $tanggal_val    =$master->scurevaluetable($tanggal,"string");
                $qty            = $_POST['frm_qty'];
                $qty_val		=$master->scurevaluetable($qty,"number",false);

                $cols_and_vals="tanggal=$tanggal_val,qty=$qty_val,verifikator=$username_val,verification=1,verification_date=$tgl_skrg_val";
                $sqlup ="UPDATE production SET $cols_and_vals WHERE id=$id;";
                $rsl_cust=$db->query($sqlup);

				if(isset($rsl_cust->error) and $rsl_cust->error===true){
			   	 		$msg['success']=false;
	                	$msg['message']="Error, ".$rsl_cust->query_last_message;
				}else{

	                $msg['success']=true;
	                $msg['message']="Data sudah diubah";
	            }
	        }else{
	             $msg['success']	=false;
	             $msg['message']	="Terjadi kesalahan pengisian form";
	             $msg['form_error']=$validasi['arrayerror'];
	        }
	    }else{
	        $msg['success']=false;
	        $msg['message']="Gagal menggambil data yang akan diubah, silahkan ulangi!";
	    }
	    echo json_encode($msg);
	}else{
		    $ip=core::get_ip();
	    	$tpl  = new View("form_verifikasi_production");
             $list_kontraktor=Model::getOptionList("partner","id","name","","ifnull(is_contractor,0)=1");
            $tpl->list_kontraktor =$list_kontraktor;

            $detail= $pro->getProduction($id);
           //echo "<pre>";print_r($detail);echo "</pre>";

            $tpl->detail =$detail;
	    	$tpl->url_edit = url::current("edit",$kode_lama);
            $tpl->url_checkdata =url::current("checkdata");
	    	$tpl->url_jsonData		= url::current("jsonData");
        	$tpl->url_comboAjax		=url::current("comboAjax");
	    	$tpl->content = $tpl;
	        $tpl->render();
    }
  }
  public function validasiform($jenis)
    {
        global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $msg=array();
      	$pesan=array();
        $msg=array();
        $msj="";

        switch($jenis){
        	case "add":
                if(trim($_POST['datas'][0]['value'])==""){
    		        $pesan["tanggal"]="Tanggal harus diisi";
    		        $msg[]="Tanggal harus diisi";
    		    }
                if(trim($_POST['datas'][1]['value'])==""){
    		        $pesan["shift"]="Shift harus diisi";
    		        $msg[]="Shift harus diisi";
    		    }
                if(trim($_POST['datas'][2]['value'])==""){
    		        $pesan["lokasi_pit"]="Lokasi PIT harus diisi";
    		        $msg[]="Lokasi PIT harus diisi";
    		    }
                if(trim($_POST['datas'][3]['value'])==""){
    		        $pesan["kontraktor"]="Kontraktor harus diisi";
    		        $msg[]="Kontraktor harus diisi";
    		    }
                // if(trim($_POST['datas']->waktu_masuk)==""){
    		    //     $pesan["waktu_masuk"]="Entry Time harus diisi";
    		    //     $msg[]="Entry Time harus diisi";
    		    // }
                // if(trim($_POST['datas']->waktu_kirim)==""){
    		    //     $pesan["waktu_kirim"]="Sent Time harus diisi";
    		    //     $msg[]="Sent Time harus diisi";
    		    // }
                // if(trim($_POST['datas']->waktu_terima)==""){
    		    //     $pesan["waktu_terima"]="Received Time harus diisi";
    		    //     $msg[]="Received Time harus diisi";
    		    // }

            break;
            case "verifikasi":
                 if(!isset($_POST['verifikasi'])){
                    $pesan["verifikasi"]="Silahkan ceklist verifikasi!";
                    $msg[]="Silahkan ceklist verifikasi";
                }
                if(trim($_POST['frm_tanggal'])<>""){
    		        if((strlen(trim($_POST['frm_tanggal']))<>10) or  (substr_count(trim($_POST['frm_tanggal']),"/")<>2)){
    		            $pesan["frm_tanggal"]="Terjadi kesalahan format Tanggal";
    		            $msg[]="Terjadi kesalahan format Tanggal";
    		        }
    	        }else{
    	        	$pesan["frm_tanggal"]="Tanggal tidak boleh kosong";
    		        $msg[]="Tanggal tidak boleh kosong";
    	        }


            break;

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
 public function detail($id){
     global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        $tpl  = new View("detail_transit_ore");
        $master=new Master_Ref_Model();
         $transit_ore    =new Transit_Ore_Model();
        date_default_timezone_set("Asia/Jakarta");
        $detail=$transit_ore->getTransitOre($id);
         //echo "<pre>"; print_r($detail);echo "</pre>";
        $tpl->detail=$detail;
        $tpl->url_cetak      = url::current("cetak");
        $this->tpl->content_title = "Detail EX-PIT Ore";
        $tpl->content = $tpl;
        $tpl->render();
  }
   public function cetak($jenis,$id) {
	   global $dcistem;
	    $db       = $dcistem->getOption("framework/db");
	    $master		= new Master_Ref_Model();
        $event=new List_Pelayanan_Model();
        $TglSkrg		=date("Y-m-d H:i:s");
	    //ob_start();
	   // $master->kopsurat("pdf");
	    //$kopsurat 	= ob_get_clean();
	    set_time_limit(1800);
   		ini_set("memory_limit","512M");
	   $detail=$event->getMutasi($id);
	   switch($jenis){
	       case "skks":
        	    ob_start();
        		$tpl  = new View("cetak/skks");
        		$ref_id=$_SESSION["framework"]["ref_id"] ;
        		// $tpl->kop_surat = $kopsurat;



        		$tpl->detail=$detail;
        		$tpl->title ="SKKS : ".$detail->NoEartag;
        		$tpl->content = $tpl;
        		$tpl->render();
        	    $content 	= ob_get_clean();

        		$pdf		=new mpdf60();
        		$mpdf		= $pdf->build("L","A4",array(15,15,25,15));
                $mpdf->charset_in = 'iso-8859-4';

        		$mpdf->WriteHTML($content);
        		$mpdf->Output('skks'.$id.'.pdf','I');
          break;
           case "pskt":
        	   ob_start();
        		$tpl  = new View("cetak/pskt");
        		$ref_id=$_SESSION["framework"]["ref_id"] ;
        		// $tpl->kop_surat = $kopsurat;

        		$tpl->detail=$detail;

                $tanggal_sekarang=$master->detailtanggal($TglSkrg,2);
                //print_r($tanggal_sekarang);
                //exit;
                $tpl->get_pelayanan=$event->getListPalayananByCow($detail->cow_id);
        		$tpl->title ="Rekam Pelayanan : ".$detail->NoEartag;
        		$tpl->content = $tpl;
        		$tpl->render();
        	    $content 	= ob_get_clean();

        		$pdf		=new mpdf60();
        		$mpdf		= $pdf->build("L","A4",array(15,15,20,20));
                $stylesheet = file_get_contents('templates/siat/theme/spring/css/print.css'); // external css
                $mpdf->WriteHTML($stylesheet,1);
        		$mpdf->SetHTMLFooter('<table width="100%" class="tbl-footer"><tr><td>'.$tanggal_sekarang['IndoHari'].', '.$tanggal_sekarang['Lengkap'].' </td><td class="pdf-footer-right" >Hal. {PAGENO} dari {nb}</td></tr></table>');
        	   //$mpdf->setFooter('Testing {PAGENO}');
                $mpdf->allow_charset_conversion = true;
        	    $mpdf->charset_in = 'iso-8859-4';
        	   // $PDFContent = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
        		//$mpdf->WriteHTML($PDFContent);
        		$mpdf->WriteHTML($content);
        		$mpdf->Output('rekam_pelayanan_'.$id.'.pdf','I');
          break;
           case "rekam_pelayanan":
        	    ob_start();
        		$tpl  = new View("cetak/rekam_pelayanan");
        		$ref_id=$_SESSION["framework"]["ref_id"] ;
        		// $tpl->kop_surat = $kopsurat;

        		$tpl->detail=$detail;
                $tanggal_sekarang=$master->detailtanggal($TglSkrg,2);
                //print_r($tanggal_sekarang);
                //exit;
                $tpl->get_pelayanan=$event->getListPalayananByCow($detail->cow_id);
        		$tpl->title ="Rekam Pelayanan : ".$detail->NoEartag;
        		$tpl->content = $tpl;
        		$tpl->render();
        	    $content 	= ob_get_clean();

        		$pdf		=new mpdf60();
        		$mpdf		= $pdf->build("L","A4",array(15,15,20,20));
                $stylesheet = file_get_contents('templates/siat/theme/spring/css/print.css'); // external css
                $mpdf->WriteHTML($stylesheet,1);
        		$mpdf->SetHTMLFooter('<table width="100%" class="tbl-footer"><tr><td>'.$tanggal_sekarang['IndoHari'].', '.$tanggal_sekarang['Lengkap'].' </td><td class="pdf-footer-right" >Hal. {PAGENO} dari {nb}</td></tr></table>');
        	   //$mpdf->setFooter('Testing {PAGENO}');
                $mpdf->allow_charset_conversion = true;
        	    $mpdf->charset_in = 'iso-8859-4';
        	   // $PDFContent = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
        		//$mpdf->WriteHTML($PDFContent);
        		$mpdf->WriteHTML($content);
        		$mpdf->Output('rekam_pelayanan_'.$id.'.pdf','I');
          break;
		}
        // PDF footer content

		/*$stylesheet = file_get_contents('templates/siat/theme/spring/css/print.css'); // external css
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->SetHTMLFooter('<div class="pdf-footer">
	                        '.$pro['tahun'].' Copyright &copy; By Sentra Teknologi Polimer      F 009A/Rev0 - ISO 9001: 2008
	                      </div>');
	    $mpdf->setFooter('{PAGENO}');*/



   }

  public function Export($format="excel") {
	    global $dcistem;
	    $db       = $dcistem->getOption("framework/db");
	    $modelsortir	= new Adm_Sortir_Model();
	    $master=new Master_Ref_Model();
	 	$modelsortir	= new Adm_Sortir_Model();
	 	date_default_timezone_set("Asia/Jakarta");
	    set_time_limit(4800);
	    ini_set("memory_limit","512M");
        if (sizeof($_POST) > 0) {
            $aVars = $_POST;
        } else {
            if (sizeof($_GET) > 0) {
                $aVars = $_GET;
            } else {
                $aVars = array();
            }
        }
        $eartag         = $aVars['eartag'];
        $tanggal        = $aVars['tanggal'];
        $id_anggota     = $aVars['anggota_id'];
        $metode         = $aVars['metode'];
        $bulan          = $aVars['bulan'];
        $tahun          = $aVars['tahun'];
        if( trim($bulan)<>"" ){   //name
            $keriteria[]="LPAD(MONTH(tanggal_pelayanan), 2, '0')='".$bulan."'";
        }
        if( trim($tahun)<>"" ){   //name
            $keriteria[]="year(tanggal_pelayanan)='".$tahun."'";
        }/* */
        if( trim($tanggal)<>"" ){   //name
            //$tgl = $master->detailtanggal($tanggal,1);
            $keriteria[]="DATE_FORMAT(tanggal_pelayanan,'%d/%m/%Y')='".$tanggal."'";

        }
        if( trim($metode)<>"" ){   //name
            $keriteria[]="metode_perkawinan='".$metode."'";
        }
        if( trim($id_anggota)<>"" ){   //name
            $keriteria[]="(a.C_ANGGOTA like'%".$id_anggota."%' or a.NAMA like'%".$id_anggota."%') ";
        }
        if(trim($eartag)<>""){
            $keriteria[]="( c.name like'%".$eartag."%' or  c.name ='".$eartag."' )" ;
        }
        $filter=$modelsortir->fromFormcari($keriteria,"and");
        $order="anggota_id asc,tanggal_pelayanan desc";
	    switch($format){
	       case "excel":
                /* set_time_limit(0);
                ob_implicit_flush(true);
           		ob_end_flush();*/
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



                $excel->getProperties()->setCreator("erp-cni")
                    				   ->setLastModifiedBy("admin")
                    				   ->setTitle("expit-report")
                    				   ->setSubject("expit-report")
                    				   ->setDescription("expit-report")
                    				   ->setKeywords("expit-report");
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

                //echo "<pre>";print_r($aVars);echo "</pre>";exit;

                $partner=new List_Partner_Model();
                
                $list_contractor=$partner->getListActiveContractor();
               // print_r($list_contractor);exit;
                 $excel->setActiveSheetIndex(0)->mergeCells('A2:G2')->setCellValue('A2', 'Laporan EXPIT');
                 $excel->getActiveSheet()->getStyle('A2')->applyFromArray($style_judul);
                 $key=30;
                 $excel->setActiveSheetIndex(0)->mergeCells($a[0].'3:'.$a[0].'4')->setCellValue($a[0].'3', 'No.')
                	  	->mergeCells($a[1].'3:'.$a[1].'4')->setCellValue($a[1].'3', 'Tanggal');
                  $excel->getActiveSheet()->getStyle($a[0].'3:'.$a[0].'4')->applyFromArray($style_header);
               	  $excel->getActiveSheet()->getColumnDimension($a[0])->setWidth(5);
                  $excel->getActiveSheet()->getStyle($a[1].'3:'.$a[1].'4')->applyFromArray($style_header);
               	  $excel->getActiveSheet()->getColumnDimension($a[0])->setWidth(15);
                 $col=2;
                 $pivot="";
                 foreach($list_contractor as $key=>$value){
                    $pivot=trim($pivot)==""?"sum(case when dto.contractor_id=".$value['id']." then total_ritase end) ritase_".$value['id']."":$pivot.",sum(case when dto.contractor_id=".$value['id']." then total_ritase end) ritase_".$value['id']."";
                    $pivot=$pivot.",sum(case when dto.contractor_id=".$value['id']." then total_quantity end) weight_".$value['id']."";
                    $excel->setActiveSheetIndex(0)->mergeCells($a[$col].'3:'.$a[$col+1].'3')->setCellValue($a[$col].'3', $value['alias'])
                            ->setCellValue($a[$col].'4', 'Ritase')->setCellValue($a[$col+1].'4', 'Weight');
                    $excel->getActiveSheet()->getStyle($a[$col].'3:'.$a[$col+1].'3')->applyFromArray($style_header);
                    $excel->getActiveSheet()->getStyle($a[$col].'4')->applyFromArray($style_header);
               	    $excel->getActiveSheet()->getColumnDimension($a[$col])->setWidth(8);
                    $excel->getActiveSheet()->getStyle($a[$col+1].'4')->applyFromArray($style_header);
                    $excel->getActiveSheet()->getColumnDimension($a[$col+1])->setWidth(13);
                    $col=$col+2;
                 }
                    //echo   $pivot;  exit;
                $group=" GROUP BY tanggal";
                $group1="";
                if(trim($filter)==""){
                    $group1=$group;
                    $group="";
                   
                }else{
                    $filter=$filter." $group";
                }
                
                $list_qry=$db->select("tanggal, $pivot","daily_transit_ore dto
                inner join partner p on p.id=dto.contractor_id $group1")
                ->where($filter)->orderBy("tanggal asc")->lim();//
                 $no=1;
                $i=5;
                $jumlah_data=$db->numRow($list_qry);
                $ListData=array();
                while($data = $db->fetchObject($list_qry))
                {

                    	$excel->setActiveSheetIndex(0)
                          	->setCellValue($a[0].$i, $no)
                           	->setCellValueExplicit($a[1].$i,$data->tanggal, PHPExcel_Cell_DataType::TYPE_STRING);
                        
                        $excel->getActiveSheet()->getStyle($a[0].$i)->applyFromArray($style_row)
                		   ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                		   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $excel->getActiveSheet()->getStyle($a[1].$i)->applyFromArray($style_row)
                		   ->getAlignment()->setWrapText(true)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                		   ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);;
                         $col=2;
                         foreach($list_contractor as $key1=>$value2){
                           $kolom_ritase="ritase_".$value2['id'];
                           $kolom_weight="weight_".$value2['id'];
                           $excel->setActiveSheetIndex(0)->setCellValueExplicit($a[$col].$i,$data->$kolom_ritase, PHPExcel_Cell_DataType::TYPE_NUMERIC );//
                           $excel->setActiveSheetIndex(0)->setCellValueExplicit($a[$col+1].$i,$data->$kolom_weight, PHPExcel_Cell_DataType::TYPE_NUMERIC);//
                            
                            
                            $col=$col+2;
                         }
                             
                    $i++;
                    $no++;
                }
             
                $excel->getActiveSheet()->setTitle('Laporan EXPIT');
                $excel->setActiveSheetIndex(0);
                $sekarang=date("dmY_His");
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="laporan_expit_'.$sekarang.'.xls"');
                header('Cache-Control: max-age=0');

                $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
                $objWriter->save('php://output');

                exit;
            break;
            case "pdf":
                ob_start();

        		$tpl  = new View("cetak/laporan_perkawinan");
        		$ref_id=$_SESSION["framework"]["ref_id"] ;
        		$bulan_name=$master->namabulanIN((int)$aVars['bulan']);

                $tpl->data_bulan=trim($bulan_name)==""?$aVars['tahun']:$bulan_name." ".$aVars['tahun'];
                //$tanggal_sekarang=$master->detailtanggal($TglSkrg,2);

                $list_qry=$db->select("kpk.id,cow_id,c.name,a.C_ANGGOTA,case when ifnull(anggota_id,'')<>'' then concat( C_ANGGOTA,' - ',a.NAMA)
                else null end pemilik,tanggal_pelayanan,DATE_FORMAT(tanggal_pelayanan,'%d/%m/%Y') TanggalPelayanan,
                jenis_pelayanan,pelayanan_nama,pejantan pejantan_id,jtn.no_pejantan no_pejantan,jtn.nama pejantan_nama,
                metode_perkawinan,no_batch,pengamat_birahi,lama_birahi,kawin_ke,dosis,biaya,last_action,breeding_status,
                kps.laktasi_ke,kps.petugas,kp.pNama,kp.pAlias,kp.pGelarDepan,kp.pGelarBelakang","keswan_perkawinan kpk
                inner join keswan_pejantan jtn on jtn.id=kpk.pejantan
                inner join keswan_pelayanan_sapi kps on kps.id=kpk.pelayanan_id
                inner join keswan_pelayanan_jenis kpj on kpj.pelayanan_id=jenis_pelayanan
                inner join cow c on c.id=kps.cow_id
                inner join anggota a on a.ID_ANGGOTA=c.anggota_id
                inner join keswan_pegawai kp on kp.pID=kps.petugas")
                ->where($filter)->orderBy($order)->lim();//
                $no=1;
                $i=4;
                $jumlah_data=$db->numRow($list_qry);
                $ListData=array();
                while($data = $db->fetchObject($list_qry))
                {

                    $rec = new stdClass;
                    $rec->No    =$no;
                    $rec->Tanggal    =$data->TanggalPelayanan;
                    $rec->NoEartag    =$data->name;
                    $rec->KawinKe    =$data->kawin_ke;
                    $rec->NoBulls    =$data->no_pejantan;
                    $rec->Pemilik    =$data->pemilik;
                    $ListData[]=$rec;

                    $i++;
                    $no++;
                }

              // echo "<pre>";print_r($ListData);echo "</pre>";exit;


                $tpl->list_data=$ListData;
        		$tpl->title ="Laporan Perkawinan";
        		$tpl->content = $tpl;
        		$tpl->render();
        	    $content 	= ob_get_clean();

        		$pdf		=new mpdf60();
        		$mpdf		= $pdf->build("L","A4",array(15,15,20,20));
                $stylesheet = file_get_contents(url::base().'themes/default/css/print.css'); // external css
                $mpdf->WriteHTML($stylesheet,1);
        		$mpdf->SetHTMLFooter('<table width="100%" class="tbl-footer"><tr><td>'.$tanggal_sekarang['IndoHari'].', '.$tanggal_sekarang['Lengkap'].' </td><td class="pdf-footer-right" >Hal. {PAGENO} dari {nb}</td></tr></table>');
        	   //$mpdf->setFooter('Testing {PAGENO}');
                $mpdf->allow_charset_conversion = true;
        	    $mpdf->charset_in = 'iso-8859-4';
        	   // $PDFContent = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
        		//$mpdf->WriteHTML($PDFContent);
        		$mpdf->WriteHTML($content);

        		$mpdf->Output('laporan_perkawinan'.$TglSkrg.'.pdf','D');
            break;

        }
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
		$pilihan=$aVars['pilih'];
        $nama=$aVars['nama'];
        $hasil  = array();
        if(trim($kategori)=="personal"){
            $person   = new  Adm_Personal_Model();
            $hasil=$person->json($nama);
        }
        if(trim($kategori)=="kordinator"){
            $coord  = new  List_Kordinator_Model();
            $hasil  =$coord->json($nama);
        }

         echo json_encode($hasil);
    }
    public function comboAjax($kategori) {
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

        $parentcode=$aVars['parentkode'];
        $hasil      = array();
        if(trim($kategori)=="listtps"){
            $tps    = new List_Tps_Model();
            $hasil  =$tps->combo($kategori,$parentcode,$aVars['nilai']);
        }else{
            $wilayah=new Ref_Wilayah_Model();
            $hasil=$wilayah->comboAjax($kategori,$parentcode,$aVars['nilai']);
        }

        echo $hasil;
   }

}
?>
