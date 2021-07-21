<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Damas Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Adm_File_Model extends Model {
  
	public function __construct() {
		
	}
    public function insert($nama,$contractor_id,$closed,$is_empty) {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master=new Master_Ref_Model();
        $TglSkrg=date("Y-m-d H:i:s");
        $sqlin="";
        $nama_val	=$master->scurevaluetable($nama);
        $closed_val	=$master->scurevaluetable($closed);
        $empty_val	=$master->scurevaluetable($is_empty);
        $contractor_id_val	=$master->scurevaluetable($contractor_id,"number");
        
       	$cols="name,closed,contractor_id,is_empty";
        $vals="$nama_val,$closed_val,$contractor_id_val,$empty_val";
		$sqlin="INSERT INTO domes ($cols) VALUES($vals);";
		$rsl=$db->query($sqlin);
		if(isset($rsl->error) and $rsl->error===true){
	   	 		$msg['success']=false;
            	$msg['message']="Error, ".$rsl->query_last_message;
		}else{
            $msg['success']=true;
            $msg['kode']=$kode;
            $msg['message']="Data sudah ditambahkan"; 
           
        }
     
      return $msg;
   }     
 public function update($id_lama,$nama,$contractor_id,$closed,$is_empty) {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master=new Master_Ref_Model();
        $TglSkrg=date("Y-m-d H:i:s");
        $nama_val	=$master->scurevaluetable($nama);
        $closed_val	=$master->scurevaluetable($closed);
        $empty_val	=$master->scurevaluetable($is_empty);
        $contractor_id_val	=$master->scurevaluetable($contractor_id,"number");
        
        $cols_and_vals="name=$nama_val,closed=$closed_val,contractor_id=$contractor_id_val,empty=$empty_val";
       
		$sqlin="UPDATE domes SET $cols_and_vals WHERE id=$id_lama;";
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
    public function delete($id) {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        $msg=array();
        if(trim($id)=="" or  $id == null){
            $msg['success']=false;
           	$msg['message']="Error, id tidak boleh kosong";
                
        }else{
            $sqlin="DELETE FROM domes  WHERE id=$id;";
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
   public function update_record_file($category,$path, $filename,$ref_id,$nama_berkas="") {
     global $dcistem;
	$db = $dcistem->getOption("framework/db");
    
     $master=new Master_Ref_Model();
     $path_val=$master->scurevaluetable($path);
     $nama_berkas_val=$master->scurevaluetable($nama_berkas);
     $cols="category,path,file_name,nama_berkas";
     $vals="'".$category."',$path_val,'".$filename."',$nama_berkas_val";
    
     $col_and_vals="category='".$category."',path=$path_val,file_name='".$filename."',nama_berkas=$nama_berkas_val";
     $where="category='".$category."'";
     if(trim($category)=="shipment"){
        $cols=$cols.",shipment_id";
        $vals=$vals.",$ref_id";
        
        $where=$where." and shipment_id=$ref_id";
        if(trim($nama_berkas)<>""){
            $where=$where." and nama_berkas='".$nama_berkas."'";
        }
        
     }
     if(trim($category)=="shipment_detail"){
        $cols=$cols.",shipment_detail_id";
        $vals=$vals.",$ref_id";
        $where=$where." and shipment_detail_id=$ref_id";
     }
     $cek=$db->select("id","files")->where($where)->get(0);
     
    $sqlin="";
    $ket_success="";
    
    if(empty($cek)){
        $sqlin    ="INSERT INTO files ($cols) VALUES($vals);";
        $ket_success="Berhasil penambahan berkas";
    }else{
        $sqlin    ="UPDATE files SET $col_and_vals WHERE $where;";
        $ket_success="Perubahan berkas sudah disimpan";
    }
    // echo $sql."<br />";
    $rsl=$db->query($sqlin);
	if(isset($rsl->error) and $rsl->error===true){
   	 		$msg['success']=false;
        	$msg['message']="Error, ".$rsl->query_last_message;
	}else{
        $msg['success']=true;
        $msg['message']=$ket_success; 
       
    }
    return $msg;
   }
    public function delete_record_file($category,$ref_id,$other_filter) {
     
     $where="category='".$category."'";
     if(trim($category)=="shipment"){
        $where=$where." and shipment_id=$ref_id";
     }
     if(trim($category)=="shipment_detail"){
        $where=$where." and shipment_detail_id=$ref_id";
     } 
     if(trim($other_filter)<>""){
        $where=$where." and $other_filter";
        $sql    ="DELETE FROM files  WHERE $where;";
         //echo $sql;
        $qryinsert3    = $this->db->prepare($sql);
        return $qryinsert3->execute();
     }else{
        return false;
     }
   }
 
}
?>