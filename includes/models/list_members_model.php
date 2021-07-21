<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Karyawan Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class List_Members_Model extends Model {
	
	public function __construct() {
	
	}
    public function getAnggota($id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		$master	= new  Master_Ref_Model();
        $cow    = new List_Cows_Model();
		if(trim($id)=="" or  $id == null){
			return array();
		}else{
            $filter="ID_ANGGOTA=".$id."";			
			$data= $db->select("ID_ANGGOTA, NIK,NoKK,C_ANGGOTA as NoAnggota,NAMA,nama_panggilan NamaPanggilan,JENIS_KELAMIN,
            status_pernikahan,agama,status,status_name,tempat_lahir, TGL_LAHIR,date_format(TGL_LAHIR,'%d/%m/%Y') as TanggalLahir,PATH_FOTO,
            TGL_MASUK,date_format(TGL_MASUK,'%d/%m/%Y') as TanggalMasuk, mcp_id, m.name as NamaTPK,ID_KELOMPOK KelompokID,
            kel.name as NamaKelompok,ID_KELOMPOK_HARGA KelompokHargaID,kh.name KelompokHargaNama,ALAMAT1,ALAMAT2,
            alamat_kabupaten KodeKabupaten,kabupatenNama NamaKabupaten,kabupatenPropinsiKode KodeProvinsi,
            propinsiNama NamaProvinsi,alamat_kecamatan KodeKecamatan,alamat_desa KodeDesa,alamat_rt RT,alamat_rw RW,
            LOKASI,NO_TELP,NO_HP,email,reg_step,reg_date,reg_lastupdate","anggota ang
            left join kelompok kel on kel.id=ang.ID_KELOMPOK
            left join mcp m on m.id=kel.mcp_id
            left join kelompok_harga kh on kh.id=ang.ID_KELOMPOK_HARGA
            left join anggota_status angs on angs.status_id=ang.status
            left join tbrkabupaten kab on kab.kabupatenKode=ang.alamat_kabupaten
            left join tbrpropinsi pro on pro.propinsiKode=kab.kabupatenPropinsiKode ",$format)
			->where($filter)->get(0);
					
			if(!empty($data)){
				$referensi	= $_SESSION["referensi"];
			
				$rec    	= new stdClass;
				$tgl		= explode("-",$data->TGL_LAHIR);
				$bulan		= $master->namabulanIN((int)$tgl[1]);
				$rec->TanggalLahir2	= $tgl[2]." ".$bulan." ".$tgl[0];
                $rec->TanggalLahirDetail	= $master->detailtanggal($data->TGL_LAHIR,2);
                $rec->TanggalMasukDetail	= $master->detailtanggal($data->TGL_MASUK,2);
				$rec->Agama		= $referensi['agama'][$data->agama];
				$rec->Kelamin	= $referensi['sex'][$data->JENIS_KELAMIN];
				$rec->NamaLengkap	= $master->nama_dan_gelar($data->pGelarDepan,$data->NAMA,$data->pGelarBelakang);
                $file_foto      = $data->PATH_FOTO;
                if(trim($data->PATH_FOTO)==""){
                    $file_foto =trim($data->JENIS_KELAMIN)=="P"?"nofoto_woman.jpg":"nofoto_man.jpg";
                    $url_foto=url::base()."foto/".$file_foto;
                }else{
                    $url_foto="http://111.223.254.6/anggota/".$data->ID_ANGGOTA."/photo/";
                }
               
				$rec->url_foto=$url_foto;
            
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
    public function getAnggotaByNumber($no_anggota,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		$master	= new  Master_Ref_Model();
        $cow    = new List_Cows_Model();
		if(trim($no_anggota)=="" or  $no_anggota == null){
			return array();
		}else{
            $filter="C_ANGGOTA='".$no_anggota."'";			
			$data= $db->select("ID_ANGGOTA, NIK,C_ANGGOTA as NoAnggota,NAMA,nama_panggilan NamaPanggilan,JENIS_KELAMIN,
            status_pernikahan,agama,
            status,status_name,tempat_lahir, TGL_LAHIR,date_format(TGL_LAHIR,'%d/%m/%Y') as TanggalLahir,PATH_FOTO,TGL_MASUK,
            date_format(TGL_MASUK,'%d/%m/%Y') as TanggalMasuk, mcp_id, m.name as NamaTPK,ID_KELOMPOK KelompokID,kel.name as NamaKelompok,
            ID_KELOMPOK_HARGA KelompokHargaID,kh.name KelompokHargaNama, ALAMAT1,ALAMAT2,alamat_kabupaten KodeKabupaten,kabupatenNama NamaKabupaten,kabupatenPropinsiKode KodeProvinsi,
            propinsiNama NamaProvinsi,
            alamat_kecamatan KodeKecamatan,alamat_desa KodeDesa,alamat_rt RT,alamat_rw RW,alamat,
            NO_TELP,NO_HP,email,reg_step,reg_date,reg_lastupdate","anggota ang
            left join kelompok kel on kel.id=ang.ID_KELOMPOK
            left join mcp m on m.id=kel.mcp_id
            left join kelompok_harga kh on kh.id=ang.ID_KELOMPOK_HARGA
            left join anggota_status angs on angs.status_id=ang.status
            left join tbrkabupaten kab on kab.kabupatenKode=ang.alamat_kabupaten
            left join tbrpropinsi pro on pro.propinsiKode=kab.kabupatenPropinsiKode ",$format)
			->where($filter)->get(0);
					
			if(!empty($data)){
				$referensi	= $_SESSION["referensi"];
			
				$rec    	= new stdClass;
				$tgl		= explode("-",$data->TGL_LAHIR);
				$bulan		= $master->namabulanIN((int)$tgl[1]);
				$rec->TanggalLahir2	= $tgl[2]." ".$bulan." ".$tgl[0];
                $rec->TanggalLahirDetail	= $master->detailtanggal($data->TGL_LAHIR,2);
                $rec->TanggalMasukDetail	= $master->detailtanggal($data->TGL_MASUK,2);
				$rec->Agama		= $referensi['agama'][$data->agama];
				$rec->Kelamin	= $referensi['sex'][$data->JENIS_KELAMIN];
				$rec->NamaLengkap	= $master->nama_dan_gelar($data->pGelarDepan,$data->NAMA,$data->pGelarBelakang);
                $file_foto      = $data->PATH_FOTO;
                if(trim($data->PATH_FOTO)==""){
                    $file_foto =trim($data->JENIS_KELAMIN)=="P"?"nofoto_woman.jpg":"nofoto_man.jpg";
                    $url_foto=url::base()."foto/".$file_foto;
                }else{
                    $url_foto="http://111.223.254.6/anggota/".$data->ID_ANGGOTA."/photo/";
                }
               
				$rec->url_foto=$url_foto;
            
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
	public function getBiodata($id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		$master	= new  Master_Ref_Model();
        $cow    = new List_Cows_Model();
		if(trim($id)=="" or  $id == null){
			return array();
		}else{
            $filter="ID_ANGGOTA=".$id."";			
			$data= $db->select("ID_ANGGOTA, NIK,NoKK,C_ANGGOTA as NoAnggota,NAMA,nama_panggilan NamaPanggilan,
            JENIS_KELAMIN,status_pernikahan,agama,
            status,status_name,tempat_lahir, TGL_LAHIR,date_format(TGL_LAHIR,'%d/%m/%Y') as TanggalLahir,PATH_FOTO,TGL_MASUK,
            date_format(TGL_MASUK,'%d/%m/%Y') as TanggalMasuk, mcp_id, m.name as NamaTPK,ID_KELOMPOK KelompokID,kel.name as NamaKelompok,
            ID_KELOMPOK_HARGA KelompokHargaID,kh.name KelompokHargaNama,ALAMAT1,ALAMAT2,alamat_kabupaten KodeKabupaten,kabupatenNama NamaKabupaten,kabupatenPropinsiKode KodeProvinsi,
            propinsiNama NamaProvinsi,
            alamat_kecamatan KodeKecamatan,alamat_desa KodeDesa,alamat_rt RT,alamat_rw RW,
            NO_TELP,NO_HP,email,reg_step,reg_date,reg_lastupdate","anggota ang
            left join kelompok kel on kel.id=ang.ID_KELOMPOK
            left join mcp m on m.id=kel.mcp_id
            left join kelompok_harga kh on kh.id=ang.ID_KELOMPOK_HARGA
            left join anggota_status angs on angs.status_id=ang.status
            left join tbrkabupaten kab on kab.kabupatenKode=ang.alamat_kabupaten
            left join tbrpropinsi pro on pro.propinsiKode=kab.kabupatenPropinsiKode ",$format)
			->where($filter)->get(0);
					
			if(!empty($data)){
				$referensi	= $_SESSION["referensi"];
			
				$rec    	= new stdClass;
				$tgl		= explode("-",$data->TGL_LAHIR);
				$bulan		= $master->namabulanIN((int)$tgl[1]);
				$rec->TanggalLahir2	= $tgl[2]." ".$bulan." ".$tgl[0];
                $rec->TanggalLahirDetail	= $master->detailtanggal($data->TGL_LAHIR,2);
                $rec->TanggalMasukDetail	= $master->detailtanggal($data->TGL_MASUK,2);
				$rec->Agama		= $referensi['agama'][$data->agama];
				$rec->Kelamin	= $referensi['sex'][$data->JENIS_KELAMIN];
				$rec->NamaLengkap	= $master->nama_dan_gelar($data->pGelarDepan,$data->NAMA,$data->pGelarBelakang);
                $file_foto      = $data->PATH_FOTO;
                if(trim($data->PATH_FOTO)==""){
                    $file_foto =trim($data->JENIS_KELAMIN)=="P"?"nofoto_woman.jpg":"nofoto_man.jpg";
                    $url_foto=url::base()."foto/".$file_foto;
                }else{
                    $url_foto="http://111.223.254.6/anggota/".$data->ID_ANGGOTA."/photo/";
                }
               
				$rec->url_foto=$url_foto;
                $rec->Tanggungan	= $this->getTanggungan($id,$format);
                $rec->DataSapi	= $cow->getCowByMember($id,$format);
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
    
    public function getBiodataCalon($id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		$master	= new  Master_Ref_Model();
        $cow    = new List_Cows_Model();
		if(trim($id)=="" or  $id == null){
			return array();
		}else{
            $filter="ID_ANGGOTA=".$id."";			
			$data= $db->select("ID_ANGGOTA, NIK,NoKK,C_ANGGOTA as NoAnggota,NAMA,nama_panggilan NamaPanggilan,JENIS_KELAMIN,
            agama,status_pernikahan,status,status_name,tempat_lahir, TGL_LAHIR,date_format(TGL_LAHIR,'%d/%m/%Y') as TanggalLahir,PATH_FOTO,
            TGL_MASUK,date_format(TGL_MASUK,'%d/%m/%Y') as TanggalMasuk, mcp_id, m.name as NamaTPK,ID_KELOMPOK KelompokID,
            kel.name as NamaKelompok,ID_KELOMPOK_HARGA KelompokHargaID,kh.name KelompokHargaNama,ALAMAT1,ALAMAT2,
            alamat_kabupaten KodeKabupaten,kabupatenNama NamaKabupaten,kabupatenPropinsiKode KodeProvinsi,
            propinsiNama NamaProvinsi,alamat_kecamatan KodeKecamatan,alamat_desa KodeDesa,alamat_rt RT,alamat_rw RW,
            NO_TELP,NO_HP,email,reg_step,status_pendaftaran,reg_date,reg_lastupdate,petugas_survey,
            pNama,pAlias,pGelarDepan,pGelarBelakang","anggota ang
            left join kelompok kel on kel.id=ang.ID_KELOMPOK
            left join mcp m on m.id=kel.mcp_id
            left join keswan_pegawai kp on kp.pID=ang.petugas_survey
            left join kelompok_harga kh on kh.id=ang.ID_KELOMPOK_HARGA
            left join anggota_status angs on angs.status_id=ang.status
            left join tbrkabupaten kab on kab.kabupatenKode=ang.alamat_kabupaten
            left join tbrpropinsi pro on pro.propinsiKode=kab.kabupatenPropinsiKode ",$format)
			->where($filter)->get(0);
					
			if(!empty($data)){
				$referensi	= $_SESSION["referensi"];
			
				$rec    	= new stdClass;
				$tgl		= explode("-",$data->TGL_LAHIR);
				$bulan		= $master->namabulanIN((int)$tgl[1]);
				$rec->TanggalLahir2	= $tgl[2]." ".$bulan." ".$tgl[0];
                $rec->TanggalLahirDetail	= $master->detailtanggal($data->TGL_LAHIR,2);
                $rec->TanggalMasukDetail	= $master->detailtanggal($data->TGL_MASUK,2);
				$rec->Agama		= $referensi['agama'][$data->agama];
				$rec->Kelamin	= $referensi['sex'][$data->JENIS_KELAMIN];
				$rec->NamaLengkap	= $master->nama_dan_gelar($data->pGelarDepan,$data->NAMA,$data->pGelarBelakang);
                $rec->NamaPetugasSurvey=$master->nama_dan_gelar($data->pGelarDepan,$data->pNama,$data->pGelarBelakang);
                $rec->StatusPerkawinan	= $referensi['status_pernikahan'][$data->status_pernikahan];
                $file_foto      = $data->PATH_FOTO;
                if(trim($data->PATH_FOTO)==""){
                    $file_foto =trim($data->JENIS_KELAMIN)=="P"?"nofoto_woman.jpg":"nofoto_man.jpg";
                    $url_foto=url::base()."foto/".$file_foto;
                }else{
                    $url_foto="http://111.223.254.6/anggota/".$data->ID_ANGGOTA."/photo/";
                }
               
				$rec->url_foto=$url_foto;
                $rec->Tanggungan	= $this->getTanggungan($id,$format);
                $rec->DataSapi	= $cow->getAllCowByMember($id,$format);
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
    public function getTanggungan($member_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master	= new  Master_Ref_Model();
		if(trim($member_id)=="" or  $member_id == null){
			return array();
		}else{
		    $referensi	= $_SESSION["referensi"];
			$filter="tgIDAnggota=".$member_id."";
            $data_qry= $db->select("tgID,tgIDAnggota,tgNIK,tgNama,tgSex,tgTempatLahir,tgTanggalLahir,tgStatusHubKeluarga,
            tgAktif,tgKeterangan","anggota_tanggungan")->where($filter)->lim();
            $data_tanggungan=array();
            while($data = $db->fetchObject($data_qry))
            {
                
                $rec    	   = new stdClass;
                $rec->ID          = $data->tgID;
                $rec->NIK          = $data->tgNIK;
                $rec->Nama          = $data->tgNama;
                $rec->KodeGender        = $data->tgSex;
                $rec->NamaGender        = $referensi['sex'][$data->tgSex];
                $rec->TempatLahir        = $data->tgTempatLahir;
                $rec->TanggalLahirDetail	= $master->detailtanggal($data->tgTanggalLahir,2);
                $rec->KodeHubunganKeluarga	= $data->tgStatusHubKeluarga;
                $rec->NamaHubunganKeluarga	= $referensi['hubungan_keluarga'][$data->tgStatusHubKeluarga];
                if(trim($format)=="array"){
					$data_tanggungan[] = (array) $rec;
				}else{
					$data_tanggungan[]	= $rec;
				}		
                
                
            }
					
			return $data_tanggungan;
		}
	}
    public function getTanggunganByID($tanggungan_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master	= new  Master_Ref_Model();
		if(trim($tanggungan_id)=="" or  $tanggungan_id == null){
			return array();
		}else{
		    $referensi	= $_SESSION["referensi"];
			$filter="tgID=".$tanggungan_id."";
            $data= $db->select("tgID,tgIDAnggota,tgNIK,tgNama,tgSex,tgTempatLahir,tgTanggalLahir,tgStatusHubKeluarga,
            tgAktif,tgKeterangan","anggota_tanggungan")->where($filter)->get(0);
            
            if(!empty($data)){
                
                $rec    	   = new stdClass;
                $rec->ID          = $data->tgID;
                $rec->NIK          = $data->tgNIK;
                $rec->Nama          = $data->tgNama;
                $rec->KodeGender        = $data->tgSex;
                $rec->NamaGender        = $referensi['sex'][$data->tgSex];
                $rec->TempatLahir        = $data->tgTempatLahir;
                $rec->TanggalLahirDetail	= $master->detailtanggal($data->tgTanggalLahir,2);
                $rec->KodeHubunganKeluarga	= $data->tgStatusHubKeluarga;
                $rec->NamaHubunganKeluarga	= $referensi['hubungan_keluarga'][$data->tgStatusHubKeluarga];
                if(trim($format)=="array"){
					return (array) $rec;
				}else{
					return $rec;
				}		
                
                
            }else{
                return array();
            }
		}
	}
	public function getKota($kode=""){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
        $master		=new Master_Ref_Model();
        $filter	="";
        if(trim($nama)<>""){
        	$filter	="staf_name like '%".$nama."%'";
        }
    	$listdata=$db->select("staf_id,staf_no_induk,staf_name,staf_inisial","tbmPersonil")
		->where($filter)->orderby("staf_name asc")->get();
        $i		=0;
        $List	= array();
        while($data=current($listdata)){
           // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
            $List[$i]['ID']=$data->staf_id;
            $List[$i]['NoInduk']=$data->staf_no_induk;
            $List[$i]['Nama']=$data->staf_name;
            $List[$i]['Label']="[".$data->staf_no_induk."] <br />".$data->staf_name;
            $i++;
            next($listdata);
        }
        return $List;
			 
	}
     public function json($nama="",$other_value=array()) {
		global $dcistem;
       
    	$db = $dcistem->getOption("framework/db");
        $filter="(C_ANGGOTA like '%".$nama."%' or NAMA like '%".$nama."%' or C_ANGGOTA='".$nama."')";
        if(isset($other_value['tpk']) and trim($other_value['tpk'])<>""){
            $filter=$filter." and kel.mcp_id=".$other_value['tpk']."";
        }
    	if(isset($other_value['kelompok']) and trim($other_value['kelompok'])<>""){
            $filter=$filter." and kel.id=".$other_value['kelompok']."";
        }
        
	    $list_qry= $db->select("ID_ANGGOTA,C_ANGGOTA,NAMA, m.name NamaTPK, kel.name NamaKelompok","anggota ang
        left join kelompok kel on kel.id=ang.ID_KELOMPOK
        left join mcp m on m.id=kel.mcp_id")->where($filter)->lim();
		$i=0;
		while($data=$db->fetchObject($list_qry)){
		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
          
		    $List[$i]['ID']=$data->ID_ANGGOTA;
		    $List[$i]['NoAnggota']=$data->C_ANGGOTA;
		    $List[$i]['Nama']=$data->NAMA;
            $lengkap=trim($data->C_ANGGOTA)<>""?"[".$data->C_ANGGOTA."] ".$data->NAMA:$data->NAMA;
            $tpk=$data->NamaTPK." - ".$data->NamaKelompok;
		    $List[$i]['Lengkap']="".$lengkap."<br>\n".$tpk."";
		    $i++;
		}
        return $List;
    }
     public function list_data() {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
    	$filter="pNama like '%".$nama."%' or pNIK='".$nama."'";
	    $list_qry= $db->select("id,start_date,end_date,closed","periode")->where($filter)->lim();
		$i=0;
		while($data=$db->fetchObject($list_qry)){
		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
           $ket="";
            if(trim($data->coordKategori)=="JRG"){
                $ket=$data->coordKeteranganJaringan;
            }
            if(trim($data->coordKategori)=="TPS"){
                $ket="TPS ".$data->tpsNama." Desa ".$data->desaNama."<br /> Kec. ".$data->kecNama;
            }
            $jar=$data->ccNama." : ".$ket;
		    $List[$i]['ID']=$data->coordID;
		    $List[$i]['NIK']=$data->pNIK;
		    $List[$i]['Nama']=$data->pNama;
            $lengkap=trim($data->pNIK)<>""?"[".$data->pNIK."] ".$data->pNama:$data->pNama;
		    $List[$i]['Lengkap']="<div class='label_typeahead'>".$lengkap."<br />".$jar."</div>";
		    $i++;
		}
        return $List;
    }
	
	
}
?>