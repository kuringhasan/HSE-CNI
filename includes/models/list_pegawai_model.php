<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Karyawan Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class List_Pegawai_Model extends Model {
	
	public function __construct() {
	
	}
	public function getBiodata($id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		$master	= new  Master_Ref_Model();
        $cow    = new List_Cows_Model();
		if(trim($id)=="" or  $id == null){
			return array();
		}else{
            $filter="partner_id=".$id."";			
			$data= $db->select("em.id emp_id,em.partner_id,em.no_induk,p.nik,p.is_company,p.is_contractor,
            p.name,p.no_kk,p.alias,p.gelar_depan,p.gelar_belakang,p.tempat_lahir,p.tempat_lahir_lain,
            p.tanggal_lahir,p.agama,p.gender,status_pernikahan,nama_pasangan,p.kewarganegaraan,p.golongan_darah,p.pJenisTandaPengenal,
            p.alamat,alamat_rt,alamat_rw,p.phone,p.alamat_kecamatan,p.alamat_desa,alamat_kabupaten,
            kabupatenNama NamaKabupaten,kabupatenPropinsiKode KodeProvinsi,
            propinsiNama NamaProvinsi,alamat_kecamatan KodeKecamatan, kecNama NamaKecamatan,
            alamat_desa KodeDesa,desaStatus StatusDesa,desaNama NamaDesa,
            p.email,file_foto,p.kodepos,p.telepon,p.npwp,p.step,last_update,p.created,p.odoo_id,
            em.unit,em.tanggal_mulai_kerja,em.job_title_id,em.jenis_kontrak_id,em.inactive,
            reg_step,reg_date,reg_lastupdate","employees em
            inner join partner p on p.id=em.partner_id
            left join tbrdesa ds on ds.desaKode=p.alamat_desa
            left join tbrkecamatan kec on kec.kecKode=p.alamat_kecamatan
            left join tbrkabupaten kab on kab.kabupatenKode=p.alamat_kabupaten
            left join tbrpropinsi pro on pro.propinsiKode=kab.kabupatenPropinsiKode",$format)
			->where($filter)->get(0);
		//	print_r($data);		
			if(!empty($data)){
				$referensi	= $_SESSION["referensi"];
			
				$rec    	= new stdClass;
				$tgl		= explode("-",$data->tanggal_lahir);
				$bulan		= $master->namabulanIN((int)$tgl[1]);
				$rec->TanggalLahir2	= $tgl[2]." ".$bulan." ".$tgl[0];
                $rec->TanggalLahirDetail	= $master->detailtanggal($data->tanggal_lahir,2);
				$rec->Agama		= $referensi['agama'][$data->agama];
				$rec->Kelamin	= $referensi['sex'][$data->gender];
				$rec->NamaLengkap	= $master->nama_dan_gelar($data->gelar_depan,$data->name,$data->gelar_belakang);
               
                $file_foto      = $data->file_foto;
                if(trim($data->file_foto)==""){//nofoto_man
                    $file_foto =trim($data->gender)=="P"?"nofoto_woman.jpg":"nofoto_man.jpg";
                    $url_foto=url::base()."foto/".$file_foto;
                }else{
                    $url_foto=url::base(). "foto/staffs/".$file_foto;
                    if (!@getimagesize($url_foto)) {
        				$file_foto =trim($data->gender)=="P"?"nofoto_woman.jpg":"nofoto_man.jpg";
                        $url_foto=url::base()."foto/".$file_foto;
        			}
                }               
				$rec->url_foto=$url_foto;
                $imgData     = "";
                //echo $url_foto;
                if(file_get_contents($url_foto)){
                    $imgData = base64_encode(file_get_contents($url_foto));
                }
                $rec->foto=$imgData;
                
                if(trim($data->PATH_FOTO)==""){
                    $file_foto =trim($data->JENIS_KELAMIN)=="P"?"nofoto_woman.jpg":"nofoto_man.jpg";
                    $url_foto=url::base()."foto/".$file_foto;
                }else{
                    $url_foto="http://111.223.254.6/anggota/".$data->ID_ANGGOTA."/photo/";
                }
               
				$rec->url_foto=$url_foto;
              //  $rec->Tanggungan	= $this->getTanggungan($id,$format);
               
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
    public function getPegawaiUser($pegawai_id,$type="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master	= new  Master_Ref_Model();
        if(trim($pegawai_id)=="" or  $pegawai_id == null){
			return array();
		}else{
		     $filter="pID=".$pegawai_id;
            $data= $db->select(" pID pegawai_id,pNoInduk no_induk,pNama nama,pGelarDepan gelar_depan,
                    pGelarBelakang gelar_belakang, usr.username,usr.pwd password","keswan_pegawai kp
                    left join users usr on usr.pegawai_id=kp.pID",$type)->where($filter)->get(0);
            if(!empty($data)){
                $rec    	= new stdClass;
                $rec->nama_lengkap	= $master->nama_dan_gelar($data->gelar_depan,$data->nama,$data->gelar_belakang);
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
    public function getPetugas($petugas_id) {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        
        $data=$db->select("pw.id,mcp_id,m.name mcp_name,jenis,pID,pNoInduk,pNama,nama_lengkap(pNama,pGelarDepan,pGelarBelakang) NamaLengkap,pAlias,pGelarDepan,pGelarBelakang,
        rayon_id,ry.rayon_name","
        petugas_wilayah pw 
        inner join keswan_pegawai kp on kp.pID=pw.pegawai_id
        left join mcp m on m.id=pw.mcp_id
        left join keswan_tbrjabatan kj on kj.JabatanID=kp.pJabatan
        left join rayon ry on ry.id=m.rayon_id")->where("pw.id=$petugas_id")->get(0);//
        return $data;
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
     public function json($ketgori,$nama="",$other_value=array()) {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        $master		=new Master_Ref_Model();
        switch($ketgori){
            case "list_pegawai":
            	$filter="pNoInduk like '%".$nama."%' or pNama like '%".$nama."%'";
        	    $list_qry= $db->select(" pID,pNoInduk,pNama,pGelarDepan,pGelarBelakang","keswan_pegawai")->where($filter)->lim();
        		$i=0;
        		while($data=$db->fetchObject($list_qry)){
        		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
                    $nama_lengkap	= $master->nama_dan_gelar($data->pGelarDepan,$data->pNama,$data->pGelarBelakang);
        		    $List[$i]['ID']=$data->pID;
        		    $List[$i]['NoInduk']=$data->pNoInduk;
        		    $List[$i]['Nama']=$nama_lengkap;
                    $lengkap=trim($data->pNoInduk)<>""?"[".$data->pNoInduk."] ".$nama_lengkap:$nama_lengkap;
        		    $List[$i]['Lengkap']="<div class='label_typeahead'>".$lengkap."</div>";
        		    $i++;
        		}
            break;
             case "pegawai_for_user":
            	$filter="pNoInduk like '%".$nama."%' or pNama like '%".$nama."%'";
        	    $list_qry= $db->select(" pID,pNoInduk,pNama,pGelarDepan,pGelarBelakang","keswan_pegawai kp
                left join users usr on usr.pegawai_id=kp.pID")->where($filter)->lim();
        		$i=0;
        		while($data=$db->fetchObject($list_qry)){
        		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
                    $nama_lengkap	= $master->nama_dan_gelar($data->pGelarDepan,$data->pNama,$data->pGelarBelakang);
        		    $List[$i]['ID']=$data->pID;
        		    $List[$i]['NoInduk']=$data->pNoInduk;
        		    $List[$i]['Nama']=$nama_lengkap;
                    $lengkap=trim($data->pNoInduk)<>""?"[".$data->pNoInduk."] ".$nama_lengkap:$nama_lengkap;
        		    $List[$i]['Lengkap']="<div class='label_typeahead'>".$lengkap."</div>";
        		    $i++;
        		}
            break;
            case "list_petugas":
                $filter="(pNoInduk like '%".$nama."%' or pNama like '%".$nama."%')";
                if(isset($other_value['jenis']) and trim($other_value['jenis'])<>""){
                    $filter=$filter." and pw.jenis='".$other_value['jenis']."'";
                }
            	
        	    $list_qry= $db->select("distinct pID,pNoInduk,pNama,pGelarDepan,pGelarBelakang","keswan_pegawai kp
                inner join petugas_wilayah pw on pw.pegawai_id=kp.pID")->where($filter)->lim();
        		$i=0;
        		while($data=$db->fetchObject($list_qry)){
        		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
                    $nama_lengkap	= $master->nama_dan_gelar($data->pGelarDepan,$data->pNama,$data->pGelarBelakang);
        		    $List[$i]['ID']=$data->pID;
        		    $List[$i]['NoInduk']=$data->pNoInduk;
        		    $List[$i]['Nama']=$nama_lengkap;
                    $lengkap=trim($data->pNoInduk)<>""?"[".$data->pNoInduk."] ".$nama_lengkap:$nama_lengkap;
        		    $List[$i]['Lengkap']="".$lengkap."";
        		    $i++;
        		}
            break;
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