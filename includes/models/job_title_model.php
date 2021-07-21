<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Karyawan Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Job_Title_Model extends Model {
	
	public function __construct() {
	
	}

    
   	public function insert_periode($minggu_ke,$start_date,$end_date,$year){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
        $hasil  = array();
        $cek=$db->select("id","week_periode")->where("week=$minggu_ke and tahun=$year")->get(0);
        if(empty($cek)){
            
            $colsc="week,start_date,end_date,tahun";
       	    $valuesc="$minggu_ke,'".$start_date."','".$end_date."',$year";
            $sqlinc="INSERT INTO week_periode ($colsc) VALUES ($valuesc);";
            $rslc=$db->query($sqlinc);
            if(isset($rslc->error) and $rslc->error===true){
       	 		$hasil['success']=false;
            	$hasil['message']="Error, ".$rslc->query_last_message;
    	    }else{
    	        
                $hasil['success']=true;
               	$hasil['message']="Data sudah diinsert";
            }
        }
        return $hasil;
			 
	}
    public function get_periode($minggu_ke,$year){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
        $hasil  = array();
        $cek=$db->select("*","week_periode")->where("week=$minggu_ke and tahun=$year")->get(0);
        
        return $cek;
			 
	}
	public function get_job_title($id){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
        $master		=new Master_Ref_Model();
       	if(trim($id)=="" or  $id == null){
			return array();
		}else{
		  $data=$db->select("jt.id,jt.name,jt.classification_id,jc.name classification_name,jt.active,
            jt.parent_id","job_title jt 
            inner join job_title_classification jc on jc.id=jt.classification_id")->where("jt.id=$id")->get(0);//
            
            if(!empty($data)){
                //$rec=new stdClass;
                $data->parent_id=$this->get_job_title($data->parent_id);
            }
            return $data;
        }
        
			 
	}
     public function json($query="",$array_value=array()) {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        
    	$filter="(jt.name like '%".$query."%' or jc.name like '%".$query."%') ";
        if(trim($array_value['web_id'])<>""){
            $filter=$filter." and jt.classification_id='".$array_value['classification_id']."'";
        }    
	    $list_qry= $db->select("jt.id,jt.name,jt.classification_id,jc.name classification_name,jt.active,
        jt.parent_id","job_title jt 
        inner join job_title_classification jc on jc.id=jt.classification_id")->where($filter)->lim();
		$i=0;
		while($data=$db->fetchObject($list_qry)){
		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
           $nama=$data->nomor;
           if(trim($data->name)<>"" and (trim($data->nomor)<>trim($data->name))){
              $nama=$nama." (".$data->name.")";
           }
           if(trim($data->type)<>""){
             $nama=$nama." ".$data->type."";
           }
          
		    $List[$i]['ID']=$data->id;
		    $List[$i]['Nama']=$data->name;
            $List[$i]['Name']=$data->name;
            
		    $List[$i]['Lengkap']=$data->name;
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