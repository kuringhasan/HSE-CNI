<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Damas Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class List_Pelayanan_Model extends Model {
  
	public function __construct() {
		
	}
    public function insertpelayanan($cow_id,$jenis_pelayanan,$tanggal_kejadian,$tipe_sapi="",$laktasi_ke="",$status_reproduksi="",$petugas,$status_laktasi="",$keterangan="") {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master	    = new  Master_Ref_Model();
        $cow        = new List_Cows_Model();
        $hasil      =array();
        $TglSkrg	=date("Y-m-d H:i:s");
        $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
       
        $cow_id_val	=$master->scurevaluetable($cow_id,"number");
        $tanggal_kejadian_val	=$master->scurevaluetable($tanggal_kejadian);
        $jenis_pelayanan_val	=$master->scurevaluetable($jenis_pelayanan,"number");
        $petugas_val	=$master->scurevaluetable($petugas,"number");
        $cols="cow_id,tanggal_pelayanan,jenis_pelayanan,petugas,created,lastupdate,input_from";
		$values="$cow_id_val,$tanggal_kejadian_val,$jenis_pelayanan_val,$petugas_val,$tgl_skrg_val,$tgl_skrg_val,'ws'";
        if(trim($tipe_sapi)<>""){
            $tipe_sapi_val	=$master->scurevaluetable($tipe_sapi,"number");
            $cols   =$cols.",tipe_sapi";
            $values =$values.",$tipe_sapi_val";
            //$cow->update_tipe_sapi($cow_id,$tipe_sapi);
        }
        if(trim($laktasi_ke)<>""){
            $laktasi_ke_val	=$master->scurevaluetable($laktasi_ke,"number");
            $cols   =$cols.",laktasi_ke";
            $values =$values.",$laktasi_ke_val";
        }
        if(trim($status_laktasi)<>""){
            $status_laktasi_val	=$master->scurevaluetable($status_laktasi,"number");
            $cols   =$cols.",status_laktasi";
            $values =$values.",$status_laktasi_val";
            //$cow->update_status_laktasi($cow_id,$status_laktasi,true);
        }
        if(trim($status_reproduksi)<>""){
            $status_reproduksi_val	=$master->scurevaluetable($status_reproduksi,"number");
            $cols   =$cols.",status_reproduksi";
            $values =$values.",$status_reproduksi_val";
            //$cow->update_status_reproduksi($cow_id,$status_reproduksi);
        }
		$sqlin="INSERT INTO keswan_pelayanan_sapi ($cols) VALUES ($values);";
		$rsl=$db->query($sqlin);
		if(isset($rsl->error) and $rsl->error===true){
	   	 		$hasil['success']=false;
            	$hasil['message']="Error, ".$rsl->query_last_message;
		}else{
            $last   =$db->query("SELECT LAST_INSERT_ID() as new_id;");
            $new    =$db->fetchArray($last);
            $hasil['success']=true;
            $hasil['new_id']=$new['new_id'];
           	$hasil['message']="Data pelayanan sudah ditambahkan ";
        }
        return $hasil;
    }
  public function updatepelayanan($pelayanan_id,$cow_id,$jenis_pelayanan,$tanggal_kejadian,$tipe_sapi="",$laktasi_ke="",$status_reproduksi="",$petugas,$status_laktasi="",$keterangan="") {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master	    = new  Master_Ref_Model();
         $cow        = new List_Cows_Model();
        $hasil      =array();
        $TglSkrg	=date("Y-m-d H:i:s");
        $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
       
        $cow_id_val	=$master->scurevaluetable($cow_id,"number");
        $tanggal_kejadian_val	=$master->scurevaluetable($tanggal_kejadian);
        $jenis_pelayanan_val	=$master->scurevaluetable($jenis_pelayanan,"number");
        $petugas_val	=$master->scurevaluetable($petugas,"number");
        $cols_and_vals="cow_id=$cow_id_val,tanggal_pelayanan=$tanggal_kejadian_val,jenis_pelayanan=$jenis_pelayanan_val,
        petugas=$petugas_val,lastupdate=$tgl_skrg_val,update_from='ws'";
        if(trim($tipe_sapi)<>""){
            $tipe_sapi_val	=$master->scurevaluetable($tipe_sapi,"number");
            $cols_and_vals   =$cols_and_vals.",tipe_sapi=$tipe_sapi_val";
            //$cow->update_tipe_sapi($cow_id,$tipe_sapi);
        }
        if(trim($laktasi_ke)<>""){
            $laktasi_ke_val	=$master->scurevaluetable($laktasi_ke,"number");
            $cols_and_vals   =$cols_and_vals.",laktasi_ke=$laktasi_ke_val";
        }
        if(trim($status_laktasi)<>""){
            $status_laktasi_val	=$master->scurevaluetable($status_laktasi,"number");
            $cols_and_vals   =$cols_and_vals.",status_laktasi=$status_laktasi_val";
            //$cow->update_status_laktasi($cow_id,$status_laktasi);
        }
        if(trim($status_reproduksi)<>""){
            $status_reproduksi_val	=$master->scurevaluetable($status_reproduksi,"number");
            $cols_and_vals   =$cols_and_vals.",status_reproduksi=$status_reproduksi_val";
           // $cow->update_status_reproduksi($cow_id,$status_reproduksi);
        }
        $sqlin="UPDATE keswan_pelayanan_sapi SET $cols_and_vals WHERE id=$pelayanan_id;";
		$rsl=$db->query($sqlin);
		if(isset($rsl->error) and $rsl->error===true){
	   	 		$hasil['success']=false;
            	$hasil['message']="Error, ".$rsl->query_last_message;
		}else{
           
            $hasil['success']=true;
           	$hasil['message']="Data pelayanan sudah disimpan ";
        }
        return $hasil;
    }
  
   public function getPelayanan($id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master	= new  Master_Ref_Model();
        $cow        = new List_Cows_Model();
		if(trim($id)=="" or  $id == null){
			return array();
		}else{
			$filter="kps.id=".$id."";
            $list_qry=$db->select("kps.id,cow_id,c.name,c.koloni_name,a.C_ANGGOTA,case when ifnull(anggota_id,'')<>'' then concat( C_ANGGOTA,' - ',a.NAMA) 
            else null end pemilik,tanggal_pelayanan,DATE_FORMAT(tanggal_pelayanan,'%d/%m/%Y') TanggalPelayanan,
            jenis_pelayanan,pelayanan_nama,case when ifnull(pelayanan_alias,'')<>'' then concat( pelayanan_nama,' (',kpj.pelayanan_alias,')') 
             else pelayanan_nama end jenis_pelayanan_nama,tipe_sapi,status_reproduksi,status_laktasi,
            kps.laktasi_ke,kps.petugas,kp.pNama,kp.pAlias,kp.pGelarDepan,kp.pGelarBelakang,kps.created,kps.lastupdate,operator
            ","keswan_pelayanan_sapi kps
            inner join keswan_pelayanan_jenis kpj on kpj.pelayanan_id=jenis_pelayanan
            inner join cow c on c.id=kps.cow_id
            inner join anggota a on a.ID_ANGGOTA=c.anggota_id
            left join keswan_pegawai kp on kp.pID=kps.petugas")->where($filter)->lim(0);
            $data = $db->fetchObject($list_qry);
            if(!empty($data)){
                $rec    	= new stdClass;
                 $rec->Cow		= $cow->getCow($data->cow_id);
                $rec->StatusAktif		= $referensi['status_sapi'][$data->is_active];
                $rec->StatusVerifikasi	= $referensi['status_verifikasi'][$data->is_need_verification];
				$rec->CaraPerolehanNama		= $referensi['cara_perolehan'][$data->cara_perolehan];
                $rec->StatusSapiNama		= $referensi['status_sapi'][$data->status_sapi];
                $rec->Sex		= trim($data->gender)=="2"?"Jantan":(trim($data->gender)=="1"?"Betina":"");
                $rec->PetugasNamaLengkap	= $master->nama_dan_gelar($data->pGelarDepan,$data->pNama,$data->pGelarBelakang);
				if(trim($format)=="array"){
					$result = array_merge((array) $data, (array) $rec);
					return $result;
				}else{
					$result	= (object) array_merge((array) $data, (array) $rec);
					return $result;
				}		
            }else{
                return array();
            }
			
		}
	}
    
    public function getPelayananTerakhir($cow_id,$jenis_pelayanan="",$exception_event_id="") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master	= new  Master_Ref_Model();
		if(trim($cow_id)=="" or  $cow_id == null){
			return array();
		}else{
			$filter="cow_id=".$cow_id."";
            if(trim($jenis_pelayanan)<>""){
                $filter_jp=trim($jenis_pelayanan)=="kawin"?"jenis_pelayanan in (12,18,21)":"jenis_pelayanan=".$jenis_pelayanan."";
                $filter=trim($filter)==""?$filter_jp:$filter." and ".$filter_jp."";
            }
            if(trim($exception_event_id)<>""){
                $filter_except="kps.id<>".$exception_event_id." and tanggal_pelayanan<(select tanggal_pelayanan from keswan_pelayanan_sapi where id=".$exception_event_id.")";
                $filter=trim($filter)==""?$filter_except:$filter." and ".$filter_except."";
            }
            $filter="tanggal_pelayanan=(select MAX(tanggal_pelayanan) from keswan_pelayanan_sapi where $filter)";
            
            $list_qry=$db->select("kps.id,cow_id,c.name,c.koloni_name,a.C_ANGGOTA,case when ifnull(anggota_id,'')<>'' then concat( C_ANGGOTA,' - ',a.NAMA) 
            else null end pemilik,tanggal_pelayanan,DATE_FORMAT(tanggal_pelayanan,'%d/%m/%Y') TanggalPelayanan,
            jenis_pelayanan,pelayanan_nama,case when ifnull(pelayanan_alias,'')<>'' then concat( pelayanan_nama,' (',kpj.pelayanan_alias,')') 
             else pelayanan_nama end jenis_pelayanan_nama,tipe_sapi,status_reproduksi,status_laktasi,
            kps.laktasi_ke,kps.petugas,kp.pNama,kp.pAlias,kp.pGelarDepan,kp.pGelarBelakang,kps.created,kps.lastupdate,operator
            ","keswan_pelayanan_sapi kps
            inner join keswan_pelayanan_jenis kpj on kpj.pelayanan_id=jenis_pelayanan
            inner join cow c on c.id=kps.cow_id
            inner join anggota a on a.ID_ANGGOTA=c.anggota_id
            left join keswan_pegawai kp on kp.pID=kps.petugas
            ")->where($filter)->lim(0);
            $data = $db->fetchObject($list_qry);
            if(!empty($data)){
                $rec    	= new stdClass;
                $rec->StatusAktif		= $referensi['status_sapi'][$data->is_active];
                $rec->StatusVerifikasi	= $referensi['status_verifikasi'][$data->is_need_verification];
				$rec->CaraPerolehanNama		= $referensi['cara_perolehan'][$data->cara_perolehan];
                $rec->StatusSapiNama		= $referensi['status_sapi'][$data->status_sapi];
                $rec->Sex		= trim($data->gender)=="2"?"Jantan":(trim($data->gender)=="1"?"Betina":"");
                $rec->PetugasNamaLengkap	= $master->nama_dan_gelar($data->pGelarDepan,$data->pNama,$data->pGelarBelakang);
				if(trim($format)=="array"){
					$result = array_merge((array) $data, (array) $rec);
					return $result;
				}else{
					$result	= (object) array_merge((array) $data, (array) $rec);
					return $result;
				}		
            }else{
                return array();
            }
			
		}
	}
    
     public function getListPalayananByCow($cow_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master	= new  Master_Ref_Model();
		if(trim($cow_id)=="" or  $cow_id == null){
			return array();
		}else{
			$filter="cow_id=".$cow_id."";
            $list_qry=$db->select("kps.id,cow_id,c.name,c.koloni_name,a.C_ANGGOTA,case when ifnull(anggota_id,'')<>'' then concat( C_ANGGOTA,' - ',a.NAMA) 
            else null end pemilik,tanggal_pelayanan,DATE_FORMAT(tanggal_pelayanan,'%d/%m/%Y') TanggalPelayanan,
            jenis_pelayanan,pelayanan_nama,case when ifnull(pelayanan_alias,'')<>'' then concat( pelayanan_nama,' (',kpj.pelayanan_alias,')') 
             else pelayanan_nama end jenis_pelayanan_nama,tipe_sapi,status_reproduksi,status_laktasi,
            kps.laktasi_ke,kps.petugas,kp.pNama,kp.pAlias,kp.pGelarDepan,kp.pGelarBelakang,kps.created,kps.lastupdate,operator
            ","keswan_pelayanan_sapi kps
            inner join keswan_pelayanan_jenis kpj on kpj.pelayanan_id=jenis_pelayanan
            inner join cow c on c.id=kps.cow_id
            inner join anggota a on a.ID_ANGGOTA=c.anggota_id
            inner join keswan_pegawai kp on kp.pID=kps.petugas
            ")->where($filter)->orderBy("tanggal_pelayanan desc")->lim();

			$ListData=array();
		    $referensi	= $_SESSION["referensi"];
            while($data = $db->fetchObject($list_qry))
            {
				if(!empty($data)){
    				$rec    	= new stdClass;
                    $rec->StatusAktif		= $referensi['status_sapi'][$data->is_active];
                    $rec->StatusVerifikasi	= $referensi['status_verifikasi'][$data->is_need_verification];
    				$rec->CaraPerolehanNama		= $referensi['cara_perolehan'][$data->cara_perolehan];
                    $rec->StatusSapiNama		= $referensi['status_sapi'][$data->status_sapi];
                    $rec->Sex		= trim($data->gender)=="J"?"Jantan":(trim($data->gender)=="B"?"Betina":"");
                    $rec->PetugasNamaLengkap	= $master->nama_dan_gelar($data->pGelarDepan,$data->pNama,$data->pGelarBelakang);
    				if(trim($format)=="array"){
    					$result = array_merge((array) $data, (array) $rec);
    				}else{
    					$result	= (object) array_merge((array) $data, (array) $rec);
    				}	
                    $ListData[]= $result;
                }
           }
            return $ListData;
		}
	}
    public function getMutasi($mutasi_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master	= new  Master_Ref_Model();
        $tpk    = new List_Tpk_Model();
        $sapi   = new List_Cows_Model();
		if(trim($mutasi_id)=="" or  $mutasi_id == null){
			return array();
		}else{
		  
		    $referensi	= $master->referensi_session();
			$filter="ms.id=".$mutasi_id."";
            $data=$db->select("ms.id,cow_id,c.name NoEartag,c.koloni_name,a.C_ANGGOTA NoAnggota,a.NAMA NamaAnggota,
            case when ifnull(anggota_id,'')<>'' then concat( C_ANGGOTA,' - ',a.NAMA) else null end pemilik,
            ID_KELOMPOK,ID_KELOMPOK_HARGA,tanggal_pelayanan,DATE_FORMAT(tanggal_pelayanan,'%d/%m/%Y') TanggalPelayanan,
            DATE_FORMAT(TGL_MASUK,'%d/%m/%Y') TanggalMasuk,TGL_MASUK,
            jenis_pelayanan,pelayanan_nama,case when ifnull(pelayanan_alias,'')<>'' then concat( pelayanan_nama,' (',kpj.pelayanan_alias,')') 
            else pelayanan_nama end jenis_pelayanan_nama,jenis_mutasi,jmNama jenis_mutasi_nama,alasan1,kkp1.KasusPenyakit alasan1_text,
            alasan2,kkp2.KasusPenyakit alasan2_text,no_polis,
            kadaluarsa_polis,no_urut_surat,kondisi_kandang,kondisi_sapi,laporan,santunan,tunggak,
            tipe_sapi,status_reproduksi,status_laktasi,
            kps.laktasi_ke,kps.petugas,kp.pNama,kp.pAlias,kp.pGelarDepan,kp.pGelarBelakang,kps.created,kps.lastupdate,operator
            ","mutasi_sapi ms
            inner join mutasi_jenis mj on mj.jmID=ms.jenis_mutasi
            inner join keswan_pelayanan_sapi kps on kps.id=ms.pelayanan_id
            inner join keswan_pelayanan_jenis kpj on kpj.pelayanan_id=jenis_pelayanan
            inner join cow c on c.id=kps.cow_id
            inner join anggota a on a.ID_ANGGOTA=c.anggota_id
            inner join keswan_pegawai kp on kp.pID=kps.petugas
            left join keswan_kasus_penyakit kkp1 on kkp1.KasusID=ms.alasan1
            left join keswan_kasus_penyakit kkp2 on kkp2.KasusID=ms.alasan2")
		      ->where($filter)->get(0);//->orderBy($order)
            if(!empty($data)){
                $rec    	= new stdClass;
                $rec->TanggalMasukAnggota=$master->detailtanggal($data->TGL_MASUK,2);
                $rec->TanggalKejadian=$master->detailtanggal($data->tanggal_pelayanan,2);
                $rec->KadaluarsaPolis=$master->detailtanggal($data->kadaluarsa_polis,2);
               
                $rec->PetugasNamaLengkap	= $master->nama_dan_gelar($data->pGelarDepan,$data->pNama,$data->pGelarBelakang);
                $rec->NamaTipe		= $referensi['tipe_sapi'][$data->tipe_sapi];
                $rec->Cow		= $sapi->getCow($data->cow_id,$format,$data->tanggal_pelayanan);
                $rec->Kelompok		= $tpk->getKelompok($data->ID_KELOMPOK);
                $rec->santunan_terbilang		= $master->Terbilang($data->santunan);
                if(trim($format)=="array"){
					$result = array_merge((array) $data, (array) $rec);
					return $result;
				}else{
					$result	= (object) array_merge((array) $data, (array) $rec);
					return $result;
				}		
            }
					
			return $data;
		}
	}
    public function getEventSapiBaru($pelayanan_id,$format="object") {
	   global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master	= new  Master_Ref_Model();
        $cow    = new List_Cows_Model();
		if(trim($pelayanan_id)=="" or  $pelayanan_id == null){
			return array();
		}else{
			$filter="kps.id=".$pelayanan_id."";
            $list_qry=$db->select("kps.id,cow_id,c.name,c.koloni_name,a.C_ANGGOTA,case when ifnull(anggota_id,'')<>'' then concat( C_ANGGOTA,' - ',a.NAMA) 
            else null end pemilik,tanggal_pelayanan,DATE_FORMAT(tanggal_pelayanan,'%d/%m/%Y') TanggalIdentifikasi,
            jenis_pelayanan,pelayanan_nama,case when ifnull(pelayanan_alias,'')<>'' then concat( pelayanan_nama,' (',kpj.pelayanan_alias,')') 
             else pelayanan_nama end jenis_pelayanan_nama,tipe_sapi,status_reproduksi,status_laktasi,
            kps.laktasi_ke,kps.petugas,kp.pNama,kp.pAlias,kp.pGelarDepan,kp.pGelarBelakang,kps.created,kps.lastupdate,operator
            ","keswan_pelayanan_sapi kps
            inner join keswan_pelayanan_jenis kpj on kpj.pelayanan_id=jenis_pelayanan
            inner join cow c on c.id=kps.cow_id
            inner join anggota a on a.ID_ANGGOTA=c.anggota_id
            left join keswan_pegawai kp on kp.pID=kps.petugas
            ")->where($filter)->lim(0);

			$ListData=array();
		    $referensi	= $_SESSION["referensi"];
            $data       = $db->fetchObject($list_qry);
            
			if(!empty($data)){
				$rec    	= new stdClass;
                $rec->TanggalIdentifikasi=$master->detailtanggal($data->tanggal_pelayanan,2);
                
                $rec->SapiBaru=$cow->getCow($data->cow_id);
                
                $sb=substr($data->name,(strlen($data->name)-3),3);
                $eartag_lama= strtoupper($data->name);
                $rec->eartag_baru_asli=$data->name;
                if(trim($sb)=="-SB"){
                    $eartag_lama=strtoupper(substr($data->name,0,(strlen($data->name)-3)));
                    $rec->eartag_baru_asli=$eartag_lama;
                }
                
                $rec->SapiLama=array();
                $cek_sapi_lama=$db->select("id,name","cow")->where("id<>".$data->cow_id." and UPPER(name)='".$eartag_lama."'")->get(0);
                if(!empty($cek_sapi_lama)){
                    $rec->SapiLama=$cow->getCow($cek_sapi_lama->id);
                }
                
                $rec->KodeTipeSapi		= $data->tipe_sapi;
                $rec->NamaTipeSapi		= $referensi['tipe_sapi'][$data->tipe_sapi];
				$rec->CaraPerolehanNama		= $referensi['cara_perolehan'][$data->cara_perolehan];
                $rec->StatusSapiNama		= $referensi['status_sapi'][$data->status_sapi];
                $rec->Sex		= trim($data->gender)=="J"?"Jantan":(trim($data->gender)=="B"?"Betina":"");
                $rec->PetugasNamaLengkap	= $master->nama_dan_gelar($data->pGelarDepan,$data->pNama,$data->pGelarBelakang);
				if(trim($format)=="array"){
					$result = array_merge((array) $data, (array) $rec);
				}else{
					$result	= (object) array_merge((array) $data, (array) $rec);
				}	
                return $result;
            }else{
               return array();
            }
            
		}
	}
     public function getPerkawinan($pelayanan_id,$format="object") {
	   global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master	= new  Master_Ref_Model();
        $cow    = new List_Cows_Model();
        
		if(trim($pelayanan_id)=="" or  $pelayanan_id == null){
			return array();
		}else{
			$filter="kps.id=".$pelayanan_id."";
            $list_qry=$db->select("kps.id,cow_id,c.name no_eartag,c.koloni_name,a.C_ANGGOTA,case when ifnull(anggota_id,'')<>'' then concat( C_ANGGOTA,' - ',a.NAMA) 
            else null end pemilik,tanggal_pelayanan,DATE_FORMAT(tanggal_pelayanan,'%d/%m/%Y') TanggalIdentifikasi,
            jenis_pelayanan,pelayanan_nama,case when ifnull(pelayanan_alias,'')<>'' then concat( pelayanan_nama,' (',kpj.pelayanan_alias,')') 
             else pelayanan_nama end jenis_pelayanan_nama,tipe_sapi,status_reproduksi,status_laktasi,
            kps.laktasi_ke,kps.petugas,kp.pNama,kp.pAlias,kp.pGelarDepan,kp.pGelarBelakang,kps.created,kps.lastupdate,operator,
            pejantan,metode_perkawinan,no_batch,pengamat_birahi,lama_birahi,kawin_ke,dosis,biaya,last_action,
            breeding_status","keswan_pelayanan_sapi kps
            inner join keswan_pelayanan_jenis kpj on kpj.pelayanan_id=jenis_pelayanan
            left join keswan_perkawinan kk on kk.pelayanan_id=kps.id
            inner join cow c on c.id=kps.cow_id
            inner join anggota a on a.ID_ANGGOTA=c.anggota_id
            left join keswan_pegawai kp on kp.pID=kps.petugas
            ")->where($filter)->lim(0);

			$ListData=array();
		    $referensi	= $_SESSION["referensi"];
            $data       = $db->fetchObject($list_qry);
            
			if(!empty($data)){
				$rec    	= new stdClass;
                $rec->TanggalIdentifikasi=$master->detailtanggal($data->tanggal_pelayanan,2);
                
                $rec->PelayananTerakhir =$this->getPelayananTerakhir($data->cow_id,"kawin",$data->id);
               
                $rec->KodeTipeSapi		= $data->tipe_sapi;
                $rec->NamaTipeSapi		= $referensi['tipe_sapi'][$data->tipe_sapi];
				$rec->CaraPerolehanNama		= $referensi['cara_perolehan'][$data->cara_perolehan];
                $rec->StatusSapiNama		= $referensi['status_sapi'][$data->status_sapi];
                $rec->Sex		= trim($data->gender)=="J"?"Jantan":(trim($data->gender)=="B"?"Betina":"");
                $rec->PetugasNamaLengkap	= $master->nama_dan_gelar($data->pGelarDepan,$data->pNama,$data->pGelarBelakang);
				if(trim($format)=="array"){
					$result = array_merge((array) $data, (array) $rec);
				}else{
					$result	= (object) array_merge((array) $data, (array) $rec);
				}	
                return $result;
            }else{
               return array();
            }
            
		}
	}
    public function verifikasiSapiBaru($pelayanan_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		if(trim($pelayanan_id)=="" or  $pelayanan_id == null){
			return array();
		}else{
			$filter="c.id=".$pelayanan_id."";
            $data= $db->select("c.id,c.name no_eartag,koloni_name,birthplace,birthdate,DATE_FORMAT(birthdate,'%d/%m/%Y') birthdate,
            datediff(current_date(),birthdate) as usia,current_date() as tgl_sekarang,posisi_eartag,
            c.barcode,photo_path,created_time,created_by,ifnull(c.is_active,0) is_active,anggota_id,ifnull(is_need_verification,0) is_need_verification,
            case when ifnull(anggota_id,'')<>'' then concat( C_ANGGOTA,' - ',a.NAMA) 
            else null end pemilik,a.NAMA, type,laktasi_ke,metode_perolehan,type,gender,
            a.ID_KELOMPOK kelompok_id,kel.name kelompok_name,mcp_id tpk_id,m.name tpk_name
            ","cow c
            inner join anggota a on a.ID_ANGGOTA=c.anggota_id
            left join kelompok kel on kel.id=a.ID_KELOMPOK
            left join mcp m on m.id=kel.mcp_id
            left join kelompok_harga kh on kh.id=a.ID_KELOMPOK_HARGA
            ")->where($filter)->get(0);

					
			if(!empty($data)){
				$referensi	= $_SESSION["referensi"];
				$rec    	= new stdClass;
                $rec->StatusAktif		= $referensi['status_sapi'][$data->is_active];
                $rec->StatusVerifikasi	= $referensi['status_verifikasi'][$data->is_need_verification];
				$rec->CaraPerolehanNama		= $referensi['cara_perolehan'][$data->cara_perolehan];
                $rec->StatusSapiNama		= $referensi['status_sapi'][$data->status_sapi];
                $rec->Sex		= trim($data->gender)=="J"?"Jantan":(trim($data->gender)=="B"?"Betina":"");
			
			//	$rec->url_foto= url::base()."foto/".$data->mhsFileFoto;
				if(trim($format)=="array"){
					$result = array_merge((array) $data, (array) $rec);
					return $result;
				}else{
					$result	= (object) array_merge((array) $data, (array) $rec);
					return $result;
				}				
			
			}else{
				return array();
			}
		}
	}
     public function getGantiPemilik($pelayanan_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master	= new  Master_Ref_Model();
        $tpk    = new List_Tpk_Model();
        $sapi   = new List_Cows_Model();
        $member = new List_Members_Model();
		if(trim($pelayanan_id)=="" or  $pelayanan_id == null){
			return array();
		}else{
		  
		    $referensi	= $master->referensi_session();
			$filter="kgp.pelayanan_id=".$pelayanan_id."";
            $data=$db->select("kgp.id,kgp.pelayanan_id,cow_id,c.name,c.koloni_name,c.tanggal_identifikasi,ifnull(c.afkir,0) afkir,pc.ID_ANGGOTA anggota_pc, concat(pc.C_ANGGOTA,' - ',pc.NAMA)  pemilik_sapi,
            pb.ID_ANGGOTA anggota_pb, pb.C_ANGGOTA,kgp.pemilik_baru pemilik_baru_id,
            case when ifnull(kgp.pemilik_baru,'')<>'' then concat(pb.C_ANGGOTA,' - ',pb.NAMA) 
            else null end pemilik_baru,pl.ID_ANGGOTA anggota_pl,pl.C_ANGGOTA,kgp.pemilik_lama pemilik_lama_id,case when ifnull(kgp.pemilik_lama,'')<>'' then concat(pl.C_ANGGOTA,' - ',pl.NAMA) 
            else null end pemilik_lama,tanggal_pelayanan,DATE_FORMAT(tanggal_pelayanan,'%d/%m/%Y') TanggalPelayanan,
            jenis_pelayanan,pelayanan_nama,case when ifnull(pelayanan_alias,'')<>'' then concat( pelayanan_nama,' (',kpj.pelayanan_alias,')') 
            else pelayanan_nama end jenis_pelayanan_nama,kps.tipe_sapi,kps.status_reproduksi,kps.status_laktasi,kps.laktasi_ke,       
            kps.laktasi_ke,kps.petugas,kp.pNama,kp.pAlias,kp.pGelarDepan,kp.pGelarBelakang,DATE_FORMAT(kps.created,'%d/%m/%Y %H:%i') TanggalInput,kps.lastupdate,operator
            ","keswan_pelayanan_sapi kps 
            inner join keswan_pelayanan_jenis kpj on kpj.pelayanan_id=kps.jenis_pelayanan
            inner join keswan_ganti_pemilik kgp on kgp.pelayanan_id=kps.id
            inner join cow c on c.id=kps.cow_id
            inner join anggota pc on pc.ID_ANGGOTA=c.anggota_id
            inner join anggota pb on pb.ID_ANGGOTA=kgp.pemilik_baru
            inner join anggota pl on pl.ID_ANGGOTA=kgp.pemilik_lama
            inner join keswan_pegawai kp on kp.pID=kps.petugas")
		      ->where($filter)->get(0);//->orderBy($order)
            if(!empty($data)){
                $rec    	= new stdClass;
                $rec->TanggalIdentifikasi=$master->detailtanggal($data->tanggal_identifikasi,2);
                $rec->TanggalEvent=$master->detailtanggal($data->tanggal_pelayanan,2);
                $rec->PetugasNamaLengkap	= $master->nama_dan_gelar($data->pGelarDepan,$data->pNama,$data->pGelarBelakang);
                $rec->PemilikLama=$member->getAnggota($data->pemilik_lama_id);
                $rec->PemilikBaru=$member->getAnggota($data->pemilik_baru_id);
                if(trim($format)=="array"){
					$result = array_merge((array) $data, (array) $rec);
					return $result;
				}else{
					$result	= (object) array_merge((array) $data, (array) $rec);
					return $result;
				}		
            }
					
			return $data;
		}
	}
    
    
  
  
	public function json($category="sapi",$query="") {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        $List   = array();
        $filter = "";
        switch($category){
            case "pejantan":
                if(trim($query)<>""){
            	   $filter="nama like '%".$query."%' or no_pejantan='".$query."' or asal='".$query."'";
                }
        	  
                $listdata= $db->select("id,no_pejantan,nama,asal,birthdate,DATE_FORMAT(birthdate,'%d/%m/%Y') TanggalLahir,
                beli_dari,id_dam,nama_dam,id_sire,nama_sire,
                asal_sire,id_mgs,nama_mgs,id_ggs,nama_ggs","pejantan")->where($filter)->get();
        		$i=0;
                if(!empty($listdata)){
            		while($data=current($listdata)){
            		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
            		    $List[$i]['ID']=$data->id;
            		    $List[$i]['NoPejantan']=$data->no_pejantan;
            		    $List[$i]['Nama']=$data->nama;
            		    $List[$i]['Lengkap']="<div class='label_typeahead'>[".$data->no_pejantan."] ".$data->nama."<br />Asal : ".$data->asal."</div>";
            		    $i++;
            		    next($listdata);
            		}
                }
               
          break;
          case "pemilik":
                if(trim($query)<>""){
            	   $filter="NAMA like '%".$query."%' or C_ANGGOTA='".$query."'";
                }
        	    $listdata= $db->select("ID_ANGGOTA,NAMA,C_ANGGOTA","anggota")->where($filter)->get();
        		$i=0;
                if(!empty($listdata)){
            		while($data=current($listdata)){
            		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
            		    $List[$i]['ID']=$data->ID_ANGGOTA;
            		    $List[$i]['IDAnggota']=$data->C_ANGGOTA;
            		    $List[$i]['Nama']=$data->NAMA;
            		    $List[$i]['Lengkap']="<div class='label_typeahead'>[".$data->C_ANGGOTA."] ".$data->NAMA."<br />Test aja</div>";
            		    $i++;
            		    next($listdata);
            		}
                }
                if(trim($query)<>""){
            	   $filter="name like '%".$query."%' or id='".$query."'";
                }
        	    $listdata2= $db->select("id,name","cow_ownership")->where($filter)->get();
                if(!empty($listdata2)){
        		while($data2=current($listdata2)){
        		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
        		    $List[$i]['ID']=$data2->id;
        		    //$List[$i]['IDAnggota']=$data->C_ANGGOTA;
        		    $List[$i]['name']=$data2->name;
        		    $List[$i]['Lengkap']="<div class='label_typeahead'>".$data2->name."<br />Test aja</div>";
        		    $i++;
        		    next($listdata2);
        		}
                }
          break;
      }
      return $List;
    } 

 
 
}
?>