<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Karyawan Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class List_Mutasi_Model extends Model {
	
	public function __construct($NIP = "") {
	
	}
    public function insert($pelayanan_id,$cow_id,$jenis_mutasi,$alasan1,$alasan2,$no_polis,$tanggal_kadaluarsa,$no_surat,$kondisi_kandang,
        $kondisi_sapi,$laporan,$santunan,$tunggak,$catatan) {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master	    = new  Master_Ref_Model();
        $pelayanan  = new List_Pelayanan_Model();
        $referensi      = $master->referensi_session();
        $msg      =array();
        $TglSkrg	=date("Y-m-d H:i:s");
        $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
        $jenis_mutasi_val	=$master->scurevaluetable($jenis_mutasi,"number");
        $alasan1_val	=$master->scurevaluetable($alasan1,"number");
        $alasan2_val	=$master->scurevaluetable($alasan2,"number");
        $no_polis_val	=$master->scurevaluetable($no_polis,"string");
       
    	$tanggal_kadaluarsa_val		=$master->scurevaluetable($tanggal_kadaluarsa);
        $no_surat_val	=$master->scurevaluetable($no_surat,"string");
        $kondisi_kandang_val	=$master->scurevaluetable($kondisi_kandang,"string");
        $kondisi_sapi_val	=$master->scurevaluetable($kondisi_sapi,"string");
        $laporan_val	=$master->scurevaluetable($laporan,"string");
        $santunan_val	=$master->scurevaluetable($santunan,"number");	
        $tunggak_val	=$master->scurevaluetable($tunggak,"number");
        $petugas_val	= $master->scurevaluetable($ref_id,"number");
        $catatan_val	=$master->scurevaluetable($catatan,"string");
        if(trim($pelayanan_id)=="" or  $pelayanan_id == null or trim($cow_id)=="" or  $cow_id == null){
            $msg['success']=false;
            $msg['message']="Pelayanan ID dan atau Cow ID tidak boleh kosong";
        }else{
            
            $colsm="pelayanan_id,jenis_mutasi,alasan1,alasan2,keterangan,no_polis,kadaluarsa_polis,
            no_urut_surat,kondisi_kandang,kondisi_sapi,laporan,santunan,tunggak";
            $valuesm="$pelayanan_id,$jenis_mutasi_val,$alasan1_val,$alasan2_val,$catatan_val,$no_polis_val,$tanggal_kadaluarsa_val,
            $no_surat_val,$kondisi_kandang_val,$kondisi_sapi_val,$laporan_val,$santunan_val,$tunggak_val";
            $sqlinm="INSERT INTO mutasi_sapi ($colsm) VALUES ($valuesm);";
            $rslm=$db->query($sqlinm);
            if(isset($rslm->error) and $rslm->error===true){
            	$msg['success']=false;
            	$msg['message']="Error, ".$rslm->query_last_message;
            }else{
                $lastc   =$db->query("SELECT LAST_INSERT_ID() as new_id;");
                $newc    =$db->fetchArray($lastc);
                $mutasi_id   =$newc['new_id'];
                
                $afkir       = in_array($jenis_mutasi,array(1,2,3,5))?1:"";
                $afkir_val	=$master->scurevaluetable($afkir,"number");	
                $sqlupc="UPDATE cow SET is_active=0,afkir=$afkir_val,afkir_date=$tgl_skrg_val,last_update=$tgl_skrg_val WHERE id=$cow_id";
                $db->query($sqlupc);
              
                
                $msg['success'] =true;
                $msg['new_id']  =$mutasi_id;
                $msg['message']    ="Data mutasi sudah dibuat ".$sqlupc; 
            }
        }
        return $msg;
    }
    public function update($mutasi_id,$pelayanan_id,$cow_id,$jenis_mutasi,$alasan1,$alasan2,$no_polis,$tanggal_kadaluarsa,$no_surat,$kondisi_kandang,
        $kondisi_sapi,$laporan,$santunan,$tunggak,$catatan) {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master	    = new  Master_Ref_Model();
        $pelayanan  = new List_Pelayanan_Model();
        $referensi      = $master->referensi_session();
        $msg      =array();
        $TglSkrg	=date("Y-m-d H:i:s");
        $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
        $jenis_mutasi_val	=$master->scurevaluetable($jenis_mutasi,"number");
        $alasan1_val	=$master->scurevaluetable($alasan1,"number");
        $alasan2_val	=$master->scurevaluetable($alasan2,"number");
        $no_polis_val	=$master->scurevaluetable($no_polis,"string");
       
    	$tanggal_kadaluarsa_val		=$master->scurevaluetable($tanggal_kadaluarsa);
        $no_surat_val	=$master->scurevaluetable($no_surat,"string");
        $kondisi_kandang_val	=$master->scurevaluetable($kondisi_kandang,"string");
        $kondisi_sapi_val	=$master->scurevaluetable($kondisi_sapi,"string");
        $laporan_val	=$master->scurevaluetable($laporan,"string");
        $santunan_val	=$master->scurevaluetable($santunan,"number");	
        $tunggak_val	=$master->scurevaluetable($tunggak,"number");
        $petugas_val	= $master->scurevaluetable($ref_id,"number");
        $catatan_val	=$master->scurevaluetable($catatan,"string");
        if((trim($pelayanan_id)=="" or  $pelayanan_id == null) or (trim($mutasi_id)=="" or  $mutasi_id == null)){
            $msg['success']=false;
            $msg['message']="Pelayanan ID dan Mutasi ID tidak boleh kosong";
        }else{
            if(trim($cow_id)=="" or  $cow_id == null){
                $msg['success']=false;
                $msg['message']="Cow ID tidak boleh kosong";
            }else{
                $cols_and_valsm="pelayanan_id=$pelayanan_id,jenis_mutasi=$jenis_mutasi_val,alasan1=$alasan1_val,alasan2=$alasan2_val,
                keterangan=$catatan_val,no_polis=$no_polis_val,kadaluarsa_polis=$tanggal_kadaluarsa_val,no_urut_surat=$no_surat_val,
                kondisi_kandang=$kondisi_kandang_val,kondisi_sapi=$kondisi_sapi_val,laporan=$laporan_val,santunan=$santunan_val,tunggak=$tunggak_val";
               
                $sqlinm="UPDATE mutasi_sapi SET $cols_and_valsm WHERE id=$mutasi_id;";
                $rslm=$db->query($sqlinm);
                if(isset($rslm->error) and $rslm->error===true){
                	$msg['success']=false;
                	$msg['message']="Error, ".$rslm->query_last_message;
                }else{
                    /*$afkir       = in_array($jenis_mutasi,array(1,2,3,5))?1:"";
                    $afkir_val	=$master->scurevaluetable($afkir,"number");	
                    $sqlupc="UPDATE cow SET is_active=0,afkir=$afkir_val,afkir_date=$tgl_skrg_val,last_update=$tgl_skrg_val WHERE id=$cow_id";
                    $db->query($sqlupc);*/
                    
                    $msg['success'] =true;
                    $msg['message']    ="Update Data mutasi sudah disimpan"; 
                }
            }
        }
        return $msg;
    }
    public function delete($mutasi_id){
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $hasil=array();
        if(trim($mutasi_id)=="" or  $mutasi_id == null){
            $hasil['success']=false;
            $hasil['message']="Mutasi ID tidak boleh kosong";
        }else{
            $sqlinc="DELETE FROM mutasi_sapi  WHERE id=$mutasi_id;";
            $rslc=$db->query($sqlinc);
            if(isset($rslc->error) and $rslc->error===true){
       	 		$hasil['success']=false;
            	$hasil['message']="Error, ".$rslc->query_last_message;
    	    }else{
                $hasil['success']=true;
               	$hasil['message']="Data Mutasi sapi sudah dihapus ";
            }
         }
        return $hasil;
    }
	public function getMutasiByPelayanan($pelayanan_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
        $cow        = new List_Cows_Model();
		if(trim($pelayanan_id)=="" or  $pelayanan_id == null){
			return array();
		}else{
		    $referensi	= $_SESSION["referensi"];
			$filter="kps.id=".$pelayanan_id."";
            
            $list_qry= $db->select("ms.id,kps.id pelayanan_id,cow_id,c.name no_eartag,c.koloni_name,a.ID_ANGGOTA anggota_id,a.C_ANGGOTA NoAnggota,a.NAMA NamaAnggota,
            case when ifnull(anggota_id,'')<>'' then concat( C_ANGGOTA,' - ',a.NAMA) else null end pemilik,
            ID_KELOMPOK,ID_KELOMPOK_HARGA,tanggal_pelayanan,DATE_FORMAT(tanggal_pelayanan,'%d/%m/%Y') TanggalPelayanan,
            DATE_FORMAT(TGL_MASUK,'%d/%m/%Y') TanggalMasuk,TGL_MASUK,
            jenis_pelayanan,pelayanan_nama,case when ifnull(pelayanan_alias,'')<>'' then concat( pelayanan_nama,' (',kpj.pelayanan_alias,')') 
            else pelayanan_nama end jenis_pelayanan_nama,jenis_mutasi,jmNama jenis_mutasi_nama,alasan1,kkp1.KasusPenyakit alasan1_text,
            alasan2,kkp2.KasusPenyakit alasan2_text,no_polis,
            kadaluarsa_polis,no_urut_surat,kondisi_kandang,kondisi_sapi,laporan,santunan,tunggak,ms.keterangan,
            tipe_sapi,status_reproduksi,status_laktasi,ifnull(ms.verification,0) verification,ms.verification_date,
            kps.laktasi_ke,kps.petugas,kp.pNama,kp.pAlias,kp.pGelarDepan,kp.pGelarBelakang,kps.created,kps.lastupdate,operator
            "," keswan_pelayanan_sapi kps 
            inner join keswan_pelayanan_jenis kpj on kpj.pelayanan_id=jenis_pelayanan
            left join mutasi_sapi ms on ms.pelayanan_id=kps.id
            left join mutasi_jenis mj on mj.jmID=ms.jenis_mutasi
            inner join cow c on c.id=kps.cow_id
            inner join anggota a on a.ID_ANGGOTA=c.anggota_id
            left join keswan_pegawai kp on kp.pID=kps.petugas
            left join keswan_kasus_penyakit kkp1 on kkp1.KasusID=ms.alasan1
            left join keswan_kasus_penyakit kkp2 on kkp2.KasusID=ms.alasan2")
            ->where($filter)->lim(0);//
            
            $data = $db->fetchObject($list_qry);
            if(!empty($data)){
                
                $rec    	= new stdClass;
                $rec->Cow		= $cow->getCow($data->cow_id,$format);
                $rec->TanggalKadaluarsa=$master->detailtanggal($data->kadaluarsa_polis,2);
                if(trim($format)=="array"){
    				$result = array_merge((array) $data, (array) $rec);
    				return $result;
    			}else{
    				$result	= (object) array_merge((array) $data, (array) $rec);
    				return $result;
    			}		
                
            }
            
            
		}
	}
   
	
}
?>