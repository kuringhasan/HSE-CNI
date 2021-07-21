<?php
/**
 * @package Mahasiswa
 * @subpackage Fakultas Model
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Ref_Syarat_Model extends Model {
	
	public function __construct() {
	   }
 public function insert($kode,$nama,$singkatan,$urutan,$upload_daftar,$upload_daftar_ulang,$keterangan="") {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master=new Master_Ref_Model();
        $TglSkrg=date("Y-m-d H:i:s");
        $sqlin="";
        $kode_val	=$master->scurevaluetable($kode);
        $nama_val	=$master->scurevaluetable($nama);
        $singkatan_val	=$master->scurevaluetable($singkatan);
        $urutan_val	=$master->scurevaluetable($urutan,"number");
        $upload_daftar_val	=$master->scurevaluetable($upload_daftar,"number");
        $upload_daftar_ulang_val	=$master->scurevaluetable($upload_daftar_ulang,"number");
        $keterangan_val	=$master->scurevaluetable($keterangan);
        
        
       	$cols="SKode,SNama,SSingkatan,SKeterangan,SUrutan,SUploadDaftar,SUploadDaftarUlang";
        $vals="$kode_val,$nama_val,$singkatan_val,$keterangan_val,$urutan_val,$upload_daftar_val,$upload_daftar_ulang_val";
		$sqlin="INSERT INTO tbrsyarat ($cols) VALUES($vals);";
		$rsl=$db->query($sqlin);
		if(isset($rsl->error) and $rsl->error===true){
	   	 		$msg['success']=false;
            	$msg['message']="Error, ".$rsl->query_last_message;
		}else{
            $msg['success']=true;
            $msg['kode']=$kode;
            $msg['message']="Perubahan data sudah disimpan"; 
           
        }
     
      return $msg;
   }     
 public function update($kode_lama,$kode_baru,$nama,$singkatan,$urutan,$upload_daftar,$upload_daftar_ulang,$keterangan="") {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master=new Master_Ref_Model();
        $TglSkrg=date("Y-m-d H:i:s");
        $kode_baru_val	=$master->scurevaluetable($kode_baru);
        $nama_val	=$master->scurevaluetable($nama);
        $singkatan_val	=$master->scurevaluetable($singkatan);
        $urutan_val	=$master->scurevaluetable($urutan,"number");
        $upload_daftar_val	=$master->scurevaluetable($upload_daftar,"number");
        $upload_daftar_ulang_val	=$master->scurevaluetable($upload_daftar_ulang,"number");
        $keterangan_val	=$master->scurevaluetable($keterangan);
        
        
       	$cols_and_vals="SKode=$kode_baru_val,SNama=$nama_val,SSingkatan=$singkatan_val,SKeterangan=$keterangan_val,
           SUrutan=$urutan_val,SUploadDaftar=$upload_daftar_val,SUploadDaftarUlang=$upload_daftar_ulang_val";
		$sqlin="UPDATE tbrsyarat SET $cols_and_vals WHERE SKode='".$kode_lama."';";
		$rsl=$db->query($sqlin);
		if(isset($rsl->error) and $rsl->error===true){
	   	 		$msg['success']=false;
            	$msg['message']="Error, ".$rsl->query_last_message;
		}else{
            $msg['success']=true;
            $msg['message']="Perubahan data sudah disimpan"; 
           
        }
     
      return $msg;
   }
    public function delete($kode_lama) {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        $msg=array();
        if(trim($kode_lama)=="" or  $kode_lama == null){
            $msg['success']=false;
           	$msg['message']="Error, Kode Syarat tidak boleh kosong";
                
        }else{
            $sqlin="DELETE FROM tbrsyarat  WHERE SKode='".$kode_lama."';";
            $rsl=$db->query($sqlin);
    		if(isset($rsl->error) and $rsl->error===true){
    	   	 		$msg['success']=false;
                	$msg['message']="Error, ".$rsl->query_last_message;
    		}else{
                $msg['success']=true;
                $msg['message']="Data sudah dihapus"; 
            }
      }
     
      return $msg;
   }
  public function getSyarat($kode) {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        $condition="SKode='".$kode."'";
        $data=$db->select("SKode,SNama,SSingkatan,SKeterangan,SUrutan,IFNULL(SUploadDaftar,0) SUploadDaftar,
        IFNULL(SUploadDaftarUlang,0) SUploadDaftarUlang","tbrsyarat")->where($condition)->orderBy("SUrutan asc")->get(0);
		
     
      return $data;
   }
 
 
 public function maxNoUrut3fromRight($fields="",$kondisi="",$nmTabel="") {
	global $dcistem;
		$db   = $dcistem->getOption("framework/db");
	    $nodaftar=$db->select("max(RIGHT(".$fields.",3)) as max",$nmTabel)->where($kondisi)->Get(0);
                    
                    $max=(int)$nodaftar->max;
                    $next=(int)$nodaftar->max+1;
                    $nomax=substr("000".(string)$max,-3,3);
                    $nonext=substr("000".(string)$next,-3,3);
                   
        
        
		return array("NoMax"=>$nomax,"NoNext"=>$nonext);
		
	
	}
	
 	
}
?>