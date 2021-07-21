<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Karyawan Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Manage_Pegawai_Model extends Model {
	
	public function list_pegawai($nama=""){ 
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
    public function getNamaLengkap($nama,$gelar_depan="",$gelar_belakang=""){ 
		if(trim($gelar_depan)<>""){
			$nama=$gelar_depan." ".$nama;
		}
		if(trim($gelar_belakang)<>""){
			$nama=$nama.", ".$gelar_belakang;
		}
   		return $nama;
	}
	public function json($nama=""){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
        $master		=new Master_Ref_Model();
        $filter	="";
        if(trim($nama)<>""){
        	$filter	="(pNama like '%".$nama."%' or pNoInduk like '".$nama."%')";
        }
    	$listdata=$db->select("pID,pNoInduk,pNama","tbmPersonal")->where($filter)->orderby("pNama asc")->get();
        $i		=0;
        $List	= array();
        while($data=current($listdata)){
           // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
            $List[$i]['ID']=$data->pID;
            $List[$i]['NoInduk']=$data->pNoInduk;
            $List[$i]['Nama']=$data->pNama;
            $List[$i]['Lengkap']="[".$data->pNoInduk."] ".$data->pNama;
            $i++;
            next($listdata);
        }
        return $List;
			 
	}

	
}
?>