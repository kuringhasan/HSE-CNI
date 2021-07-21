<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Karyawan Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class List_Production_Model extends Model {
	
	public function __construct($NIP = "") {
	
	}
    public function insert($pelayanan_id,$cow_id,$no_eartag_lama,$no_eartag_baru,$keterangan="") {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master	    = new  Master_Ref_Model();
        $pelayanan  = new List_Pelayanan_Model();
        $referensi      = $master->referensi_session();
        $msg      =array();
        $TglSkrg	=date("Y-m-d H:i:s");
        $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
        $no_eartag_lama     = strtoupper($no_eartag_lama);
        $no_eartag_lama_val	=$master->scurevaluetable($no_eartag_lama);
        $no_eartag_baru     = strtoupper($no_eartag_baru);
        $no_eartag_baru_val	=$master->scurevaluetable($no_eartag_baru);
        $keterangan_val	=$master->scurevaluetable($keterangan);
        if(trim($pelayanan_id)=="" or  $pelayanan_id == null){
            $msg['success']=false;
            $msg['message']="Pelayanan ID tidak boleh kosong";
        }else{
            if(trim($cow_id)=="" or  $cow_id == null){
                $msg['success']=false;
                $msg['message']="Cow ID tidak boleh kosong";
            }else{  
                $colsm="pelayanan_id,no_eartag_lama,no_eartag_baru,keterangan";
    		    $valuesm="$pelayanan_id,$no_eartag_lama_val,$no_eartag_baru_val,$keterangan_val";
                $sqlinm="INSERT INTO keswan_ganti_eartag ($colsm) VALUES ($valuesm);";
                $rslm=$db->query($sqlinm);
                if(isset($rslm->error) and $rslm->error===true){
    	   	 		$msg['success']=false;
                	$msg['message']="Error, ".$rslm->query_last_message." ".$sqlinm;
    		    }else{
    		        $lastc   =$db->query("SELECT LAST_INSERT_ID() as new_id;");
                    $newc    =$db->fetchArray($lastc);
                    $new_ganti_eartag_id   =$newc['new_id'];
                    $sqlupc="UPDATE cow SET name=$no_eartag_baru_val,barcode=$no_eartag_baru_val,last_update=$tgl_skrg_val,sync=null WHERE id=$cow_id";
                    $db->query($sqlupc);
                    $msg['success']=true;
                    $msg['new_id']=$new_ganti_eartag_id;
                    $msg['message']="Berhasil menambahkan data ganti eartag";
    	        }
             }//
        }
        return $msg;
    }
     public function update($ganti_eartag_id,$pelayanan_id,$cow_id,$no_eartag_lama,$no_eartag_baru,$keterangan="") {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master	    = new  Master_Ref_Model();
        $pelayanan  = new List_Pelayanan_Model();
        $referensi      = $master->referensi_session();
        $msg      =array();
        $TglSkrg	=date("Y-m-d H:i:s");
        $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
        $no_eartag_lama     = strtoupper($no_eartag_lama);
        $no_eartag_lama_val	=$master->scurevaluetable($no_eartag_lama);
        $no_eartag_baru     = strtoupper($no_eartag_baru);
        $no_eartag_baru_val	=$master->scurevaluetable($no_eartag_baru);
        $keterangan_val	=$master->scurevaluetable($keterangan);
        if(trim($pelayanan_id)=="" or  $pelayanan_id == null){
            $msg['success']=false;
            $msg['message']="Pelayanan ID tidak boleh kosong";
        }else{
            if(trim($cow_id)<>"" and  $cow_id == null){
                $msg['success']=false;
                $msg['message']="Cow ID tidak boleh kosong";
            }else{  
                $cols_and_valsm="pelayanan_id=$pelayanan_id,no_eartag_lama=$no_eartag_lama_val,no_eartag_baru=$no_eartag_baru_val,
                keterangan=$keterangan_val";
    		    
                $sqlinm="UPDATE keswan_ganti_eartag SET  $cols_and_valsm WHERE id=$ganti_eartag_id;";
                $rslm=$db->query($sqlinm);
                if(isset($rslm->error) and $rslm->error===true){
    	   	 		$msg['success']=false;
                	$msg['message']="Error, ".$rslm->query_last_message." ".$sqlinm;
    		    }else{
    		        $sqlupc="UPDATE cow SET name=$no_eartag_baru_val,barcode=$no_eartag_baru_val,last_update=$tgl_skrg_val,sync=null WHERE id=$cow_id";
                    $db->query($sqlupc);
                    $msg['success']=true;
                    $msg['message']="Berhasil update data ganti eartag";
    	        }
             }
        }
        return $msg;
    }
	public function getProduction($production_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		if(trim($production_id)=="" or  $production_id == null){
			return array();
		}else{
		    $referensi	= $_SESSION["referensi"];
			$filter="pro.id=".$production_id."";
            
            $list_qry=$db->select("pro.id,tanggal,DATE_FORMAT(tanggal,'%d/%m/%Y') tgl,
            pro.week,pro.partner_id,p.name,p.alias,qty,pro.created,ifnull(verification,0) verification,verification_date,verifikator","production pro
            inner join partner p on p.id=pro.partner_id")
            ->where($filter)->lim(0);//
            
            $data = $db->fetchObject($list_qry);
            if(!empty($data)){
                
                $rec    	= new stdClass;
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