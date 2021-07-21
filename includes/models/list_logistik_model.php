<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Karyawan Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class List_Logistik_Model extends Model {
	
	public function __construct($NIP = "") {
	
	}
    public function insert($pelayanan_id,$anggota_id,$tanggal_kejadian,$jumlah_bayi,$keadaan_melahirkan,$jenis_kelamin,$jumlah_dipelihara="",$jumlah_mati="",$jumlah_dijual="",$list_pedet=array(),$cow_induk="",$cow_bapak="",$petugas) {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master	    = new  Master_Ref_Model();
        $pelayanan  = new List_Pelayanan_Model();
        $cow        = new List_Cows_Model();
        $referensi      = $master->referensi_session();
        $msg      =array();
        $TglSkrg	=date("Y-m-d H:i:s");
        $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
        $keadaan_melahirkan_val	=$master->scurevaluetable($keadaan_melahirkan,"number");
        $jumlah_bayi_val	=$master->scurevaluetable($jumlah_bayi,"number");
        $jenis_kelamin_val	=$master->scurevaluetable($jenis_kelamin,"number");
        $tanggal_kejadian_val	= $master->scurevaluetable($tanggal_kejadian,"string");
        if(trim($pelayanan_id)=="" or  $pelayanan_id == null){
            $msg['success']=false;
            $msg['message']="Pelayanan ID tidak boleh kosong";
        }else{
            if(!empty($list_pedet)){
                $colsm="pelayanan_id,jumlah_bayi,keadaan_melahirkan,jenis_kelamin";
    		    $valuesm="$pelayanan_id,$jumlah_bayi_val,$keadaan_melahirkan_val,$jenis_kelamin_val";
                if(trim($jumlah_dipelihara)<>""){
                    $jumlah_dipelihara_val	=$master->scurevaluetable($jumlah_dipelihara,"number");
                    $colsm   =$colsm.",jumlah_dipelihara";
                    $valuesm =$valuesm.",$jumlah_dipelihara_val";
                }
                if(trim($jumlah_mati)<>""){
                    $jumlah_mati_val	=$master->scurevaluetable($jumlah_mati,"number");
                    $colsm   =$colsm.",jumlah_mati";
                    $valuesm =$valuesm.",$jumlah_mati_val";
                }
                if(trim($jumlah_dijual)<>""){
                    $jumlah_dijual_val	=$master->scurevaluetable($jumlah_dijual,"number");
                    $colsm   =$colsm.",jumlah_dijual";
                    $valuesm =$valuesm.",$jumlah_dijual_val";
                }
                $sqlinm="INSERT INTO keswan_kelahiran ($colsm) VALUES ($valuesm);";
                $rslm=$db->query($sqlinm);
                if(isset($rslm->error) and $rslm->error===true){
    	   	 		$msg['success']=false;
                	$msg['message']="Error, ".$rslm->query_last_message." ".$sqlinm;
    		    }else{
    		        $lastc   =$db->query("SELECT LAST_INSERT_ID() as new_id;");
                    $newc    =$db->fetchArray($lastc);
                    $kelahiran_id   =$newc['new_id'];
                    if(trim($kelahiran_id)<>""){
                        $jumlah_pedet    =count($list_pedet);
                        $jumlah_gagal   =0;
                        foreach($list_pedet as $key=>$value){
                            $sex		=$value['gender'];
                            $sex_val	=$master->scurevaluetable($sex,"number");
                            $no_eartag		= strtoupper($value['no_eartag']);
                            $no_eartag_val	=$master->scurevaluetable($no_eartag);
                            $posisi_eartag		=$value['posisi_eartag'];
                            $posisi_eartag_val	=$master->scurevaluetable($posisi_eartag);
                            $koloni_name		=$value['koloni_name'];
                            $koloni_name_val	=$master->scurevaluetable($koloni_name);
                            $tipe               = $sex;
                            
                            $insert_sapi= $cow->insert($petugas,$no_eartag,$posisi_eartag,$koloni_name,$tanggal_kejadian,$tanggal_kejadian,$anggota_id,$tipe,$cow_induk,$cow_bapak,$sex,1,"",1);
                            if($insert_sapi['success']==true){
                                
                                $new_cow_id=$insert_sapi['new_id'];
                                $new_cow_id_val	=$master->scurevaluetable($new_cow_id,"number");
                                $colsp="kelahiran_id,cow_id,sex,no_eartag,posisi_eartag,name,berat,harga_jual,tanggal_lahir";
            				    $valuesp="$kelahiran_id,$new_cow_id_val,$sex_val,$no_eartag_val,$posisi_eartag_val,$koloni_name_val,null,null,$tanggal_kejadian_val";
                                $sqlinp="INSERT INTO keswan_kelahiran_bayi ($colsp) VALUES($valuesp);";
        				        $sl=$db->query($sqlinp);
                                
                                //INSERT PELAYANAN SAPI BARU
                                $insert_sapibaru=$pelayanan->insertpelayanan($new_cow_id,27,$tanggal_kejadian,1,0,"",$petugas);
                                
                            }else{
                                $jumlah_gagal++;
                            }
                         }// end of foreach
                         if($jumlah_pedet<=$jumlah_gagal){
                            $msg['success']=true;
                            $msg['kelahiran_id']=$kelahiran_id;
                            $msg['message']="Data pelayanan telah dibuat. Gagal insert data pedet";
                         }else{
                            $msg['success']=true;
                            $msg['kelahiran_id']=$kelahiran_id;
                            $msg['message']="Berhasil menambahkan data pelayanan dan data pedet";
                         }
                    }else{
                        $msg['success']=false;
        	            $msg['message']="Gagal create keswan_kelahiran";
                    }
    	        }
             }else{
                $msg['success']=false;
                $msg['message']="Pedet tidak boleh kosong";
             }
          }
        return $msg;
    }
     public function update($kelahiran_id,$pelayanan_id,$anggota_id,$tanggal_kejadian,$jumlah_bayi,$keadaan_melahirkan,$jenis_kelamin,$jumlah_dipelihara="",$jumlah_mati="",$jumlah_dijual="",$list_pedet=array(),$cow_induk="",$cow_bapak="",$petugas) {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master	    = new  Master_Ref_Model();
        $pelayanan  = new List_Pelayanan_Model();
        $cow        = new List_Cows_Model();
        $referensi      = $master->referensi_session();
        $msg      =array();
        $TglSkrg	=date("Y-m-d H:i:s");
        $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
        
        $jumlah_bayi_val	=$master->scurevaluetable($jumlah_bayi,"number");
        if(trim($kelahiran_id)=="" or  $kelahiran_id == null){
            $msg['success']=false;
           	$msg['message']="Error, ID Kelahiran tidak boleh kosong";
        }else{
            if(!empty($list_pedet)){
                $jenis_kelamin_val	=$master->scurevaluetable($jenis_kelamin,"number");
                $keadaan_melahirkan_val	=$master->scurevaluetable($keadaan_melahirkan,"number");
                $cols_and_valsp="pelayanan_id=$pelayanan_id,jumlah_bayi=$jumlah_bayi_val,keadaan_melahirkan=$keadaan_melahirkan_val,jenis_kelamin=$jenis_kelamin_val";
    		   
                if(trim($jumlah_dipelihara)<>""){
                    $jumlah_dipelihara_val	=$master->scurevaluetable($jumlah_dipelihara,"number");
                    $cols_and_valsp   =$cols_and_valsp.",jumlah_dipelihara=$jumlah_dipelihara_val";
                }
                if(trim($jumlah_mati)<>""){
                    $jumlah_mati_val	=$master->scurevaluetable($jumlah_mati,"number");
                    $cols_and_valsp   =$cols_and_valsp.",jumlah_mati=$jumlah_mati_val";
                }
                if(trim($jumlah_dijual)<>""){
                    $jumlah_dijual_val	=$master->scurevaluetable($jumlah_dijual,"number");
                    $cols_and_valsp   =$cols_and_valsp.",jumlah_dijual=$jumlah_dijual_val";
                }
                $sqlupm="UPDATE keswan_kelahiran SET $cols_and_valsp WHERE id=$kelahiran_id;";
                $rslm=$db->query($sqlupm);
                if(isset($rslm->error) and $rslm->error===true){
           	 		$msg['success']=false;
                	$msg['message']="Error, ".$rslm->query_last_message." ".$sqlupm;
        	    }else{
                        $jumlah_pedet    =count($list_pedet);
                        $jumlah_gagal   =0;
                        foreach($list_pedet as $key=>$value){
                            
                            $child_cow_id		=isset($value['child_cow_id'])?$value['child_cow_id']:"";
                            $sex		=$value['gender'];
                            $sex_val	=$master->scurevaluetable($sex,"number");
                            $no_eartag		= strtoupper($value['no_eartag']);
                            $no_eartag_val	=$master->scurevaluetable($no_eartag);
                            $posisi_eartag		=$value['posisi_eartag'];
                            $posisi_eartag_val	=$master->scurevaluetable($posisi_eartag);
                            $koloni_name		=$value['koloni_name'];
                            $koloni_name_val	=$master->scurevaluetable($koloni_name);
                            $tipe               = $sex;
                            
                            $filter_pedet="name='".$no_eartag."'";
                            if(trim($child_cow_id)<>""){
                                $filter_pedet=$filter_pedet." and id=$child_cow_id";
                            }
                            $cek=$db->select("id","cow")->where($filter_pedet)->get(0);
                            $sqlinp     = "";
                            if(empty($cek)){
                                //insert cow
                                $insert_sapi= $cow->insert($petugas,$no_eartag,$posisi_eartag,$koloni_name,$tanggal_kejadian,$tanggal_kejadian,$anggota_id,$tipe,$cow_induk,$cow_bapak,$sex,1,"",1);
                                if($insert_sapi['success']==true){
                                    $child_cow_id=$insert_sapi['new_id'];
                                }
                            }else{
                                //update cow
                                $child_cow_id=$cek->id;
                                $update_sapi= $cow->update($child_cow_id,$no_eartag,$posisi_eartag,$koloni_name,$tanggal_kejadian,$tanggal_kejadian,$anggota_id,$tipe,$cow_induk,$cow_bapak,$sex,1,"",1);
                               
                            }
                            if(trim($child_cow_id)<>""){
                                $child_cow_id_val	=$master->scurevaluetable($child_cow_id,"number");
                                $filter_bayi="UPPER(no_eartag)='".$no_eartag."'";
                                
                                $cek_bayi=$db->select("id","keswan_kelahiran_bayi")->where($filter_bayi)->get();
                                if(empty($cek_bayi)){
                                    $colsp="kelahiran_id,cow_id,sex,no_eartag,posisi_eartag,name,berat,harga_jual,tanggal_lahir";
                				    $valuesp="$kelahiran_id,$child_cow_id_val,$sex_val,$no_eartag_val,$posisi_eartag_val,$koloni_name_val,null,null,$tanggal_kejadian_val";
                                    $sqlinp="INSERT INTO keswan_kelahiran_bayi ($colsp) VALUES($valuesp);";
            				        $sl=$db->query($sqlinp);
                                    //INSERT PELAYANAN SAPI BARU
                                    $insert_sapibaru=$pelayanan->insertpelayanan($child_cow_id,27,$tanggal_kejadian,1,0,"",$petugas);
                                    
                                }else{
                                    $cols_and_valsp="kelahiran_id=$kelahiran_id,cow_id=$child_cow_id_val,sex=$sex_val,no_eartag=$no_eartag_val,
                                    posisi_eartag=$posisi_eartag_val,name=$koloni_name_val,berat=null,harga_jual=null,tanggal_lahir=$tanggal_kejadian_val";
                				   
                                    $sqlinp="UPDATE keswan_kelahiran_bayi SET $cols_and_valsp WHERE UPPER(no_eartag)='".$no_eartag."';";
            				        $sl=$db->query($sqlinp);
                                    //$pelayanan->updatepelayanan($child_cow_id,27,$tanggal_kejadian,1,0,"",$ref_id);
                                    $filter_ksp="cow_id=$child_cow_id and jenis_pelayanan=27 and tanggal_pelayanan='".$tanggal_kejadian."'";
                                    $cek_event_sapibaru=$db->select("id","keswan_pelayanan_sapi")->where($filter_ksp)->get(0);
                                    if(empty($cek_bayi)){
                                        //insert event
                                        $insert_sapibaru=$pelayanan->insertpelayanan($child_cow_id,27,$tanggal_kejadian,1,0,"",$petugas);
                                    }else{
                                         //update event
                                         $pelayanan_id_sapibaru=$cek_event_sapibaru->id;
                                         $update_sapibaru=$pelayanan->updatepelayanan($pelayanan_id_sapibaru,$child_cow_id,27,$tanggal_kejadian,1,0,"",$petugas);
                                    }
                                }
                            }else{
                                $jumlah_gagal++;
                            }
                         }// end of foreach
                         if($jumlah_pedet<=$jumlah_gagal){
                            $msg['success']=true;
                            $msg['kelahiran_id']=$kelahiran_id;
                            $msg['message']="Data pelayanan telah diupdate. Gagal insert/update data pedet";
                         }else{
                            $msg['success']=true;
                            $msg['kelahiran_id']=$kelahiran_id;
                            $msg['message']="Berhasil update data pelayanan dan kelahiran pedet";
                         }
                         
                 }
             }else{
                $msg['success']=false;
                $msg['message']="Pedet tidak boleh kosong";
             }
         }
        return $msg;
    }
	public function getKelahiranByPelayanan($pelayanan_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $cow        = new List_Cows_Model();
		if(trim($pelayanan_id)=="" or  $pelayanan_id == null){
			return array();
		}else{
		    $referensi	= $_SESSION["referensi"];
			$filter="kps.id=".$pelayanan_id."";
            
            $list_qry=$db->select("lhr.id, kps.id pelayanan_id,cow_id,c.name no_eartag,c.koloni_name,a.C_ANGGOTA,case when ifnull(anggota_id,'')<>'' then concat( C_ANGGOTA,' - ',a.NAMA) 
            else null end pemilik,tanggal_pelayanan,DATE_FORMAT(tanggal_pelayanan,'%d/%m/%Y') TanggalPelayanan,
            jenis_pelayanan,pelayanan_nama,case when ifnull(pelayanan_alias,'')<>'' then concat( pelayanan_nama,' (',kpj.pelayanan_alias,')') 
            else pelayanan_nama end jenis_pelayanan_nama,lhr.pelayanan_id,jumlah_bayi,jumlah_dipelihara,jumlah_mati,
            jumlah_dijual,keadaan_melahirkan,lhr.jenis_kelamin,
            
            kps.laktasi_ke,kps.petugas,kp.pNama,kp.pAlias,kp.pGelarDepan,kp.pGelarBelakang,kps.created,kps.lastupdate,operator
            ","keswan_pelayanan_sapi kps 
            inner join keswan_pelayanan_jenis kpj on kpj.pelayanan_id=jenis_pelayanan
            left join keswan_kelahiran lhr on lhr.pelayanan_id= kps.id
            inner join cow c on c.id=kps.cow_id
            inner join anggota a on a.ID_ANGGOTA=c.anggota_id
            left join keswan_pegawai kp on kp.pID=kps.petugas")->where($filter)->lim(0);//
            
            $data = $db->fetchObject($list_qry);
            if(!empty($data)){
                
                $rec    	= new stdClass;
                $rec->Cow		= $cow->getCow($data->cow_id,$format);
                $rec->DataPedet		= $this->getBabyCowKelahiran($data->id,$format);
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
    public function getLogistik($trx_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($trx_id)=="" or  $trx_id == null){
			return array();
		}else{
		    $referensi	= $_SESSION["referensi"];
			$filter="id=".$trx_id."";
            $list_qry=$db->select("lgt.id,periode_id,anggota_id,C_ANGGOTA no_anggota,ang.NAMA nama_anggota,trx_date,date_format(trx_date,'%d/%m/%Y') as tanggal_transaksi,created,operator,pengambil,total,
                pegawai_id,kp.pNama,kp.pAlias,kp.pGelarDepan,kp.pGelarBelakang","logistik_trx lgt
                inner join anggota ang on ang.ID_ANGGOTA=lgt.anggota_id
                left join keswan_pegawai kp on kp.pID=lgt.pegawai_id")->where($filter)->lim(0);
           
                $data = $db->fetchObject($list_qry);
                $hasil=array();
                if(!empty($data)){
                
                    $rec    	   = new stdClass;
                    $rec->id            = $data->id;
                    $rec->anggota_id        = $data->anggota_id;
                    $rec->no_anggota        = $data->no_anggota;
                    $rec->nama_anggota        = $data->nama_anggota;
                    $rec->trx_date        = $data->trx_date;
                    $rec->tanggal        = $master->detailtanggal($data->trx_date,2);
                    $rec->pengambil           = $data->pengambil;
                    $rec->total         = $data->total;
                    $rec->pegawai_id    = $data->pegawai_id;
                    $rec->petugas_nama	= $master->nama_dan_gelar($data->pGelarDepan,$data->pNama,$data->pGelarBelakang);
                    $rec->detail	= $this->getDetailLogistik($trx_id,$format);
                    
                    if(trim($format)=="array"){
    					$hasil = (array) $rec;
    				}else{
    					$hasil	= $rec;
    				}
                }
					
			return $hasil;
		}
	}
    public function getDetailLogistik($trx_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($trx_id)=="" or  $trx_id == null){
			return array();
		}else{
		    $referensi	= $_SESSION["referensi"];
			$filter="trx_id=".$trx_id."";
            $list_qry=$db->select("log.id,barang_id,brg.name barang_name,barang_kredit_id,anggota_barang_kredit_detail_id,jumlah,
            log.harga,log.sub_total,log.anggota_id,kelompok_harga_id,tanggal,log.created_time,log.periode_id,log.closed,kredit_first,
            log.created_by,trx_id","logistik log
            left join barang brg on brg.id=log.barang_id")->where($filter)->orderBy("barang_id asc")->lim();
           
            $listdata=array();
            while($data = $db->fetchObject($list_qry))
            {
                
                $rec    	   = new stdClass;
                $rec->id            = $data->id;
                $rec->trx_id        = $data->trx_id;
                $rec->barang_id        = $data->barang_id;
                $rec->barang_name        = $data->barang_name;
                $rec->qty           = $data->jumlah;
                $rec->harga        = $data->harga;
                $rec->sub_total        = $data->sub_total;
                
                if(trim($format)=="array"){
					$listdata[] = (array) $rec;
				}else{
					$listdata[]	= $rec;
				}		
                
                
            }
					
			return $listdata;
		}
	}
	
}
?>