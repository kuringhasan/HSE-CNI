<?php
/**
 * @package List Insiden
 * 
 * @author Abdiiwan <abdiiwan1841@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Ref_Insiden_Model extends Model {
	
	public function __construct() {
		
	}

   public function getAlatTerlibat($kode) {
    	global $dcistem;
    	$db = $dcistem->getOption("framework/db");
    	if(trim($kode)<>""){
	    	$data=$db->select("kode,nama_alat_terlibat","ref_alat_terlibat")
			->where("kode='".$kode."'")->get(0);
			 return $data;
		}else{
			 return array();
		}
   }

   public function getListAlatTerlibat() {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
    	$listdata=$db->select("kode,nama_alat_terlibat","ref_alat_terlibat")->get();
      	return $listdata;
   }
   
   
  
  public function comboAlatTerlibat($kode="") {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
    	$msg['kosong']=true;
    	$filter="";
    	if(trim($kode)<>""){
    		$filter="kode='".$kode."'";
    	}
        $listdata=$db->select("kode,nama_alat_terlibat","ref_alat_terlibat")
		->where($filter)->get();
        $html= "<option value='' >--Alat Terlibat--</option>";
        if(!empty($listdata)){
            while($data=current($listdata))
            {
                $selected=trim($nilai)==trim($data->kode)?" selected ":"";
                $html .= "<option value='".$data->kode."' $selected>[".$data->kode."] ".$data->nama_alat_terlibat."</option>";   
				next($listdata); 
            }
         	$msg['kosong']=false;
        }else{
            $msg['kosong']=true;
        }
		$msg['html']= $html;
        return json_encode($msg);
   }
   
    public function jsonAlatTerlibat($array_data=array(),$nama="") {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
    	$filter="(nama_alat_terlibat like '%".$nama."%' )";
    
		$listdata=$db->select("kode,nama_alat_terlibat","ref_alat_terlibat")
		->where($filter)->orderby("kode asc")->get();
		$i=0;
		while($data=current($listdata)){
		    $List[$i]['Kode']=$data->kode;
		    $List[$i]['Nama']=$data->nama_alat_terlibat;
		    $i++;
		    next($listdata);
		}
        return $List;
	}

	public function comboAjaxAlatTerlibat($nilai="") {
        
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $msg=array();
        $html="";

		$listkota =Model::getOptionList("ref_alat_terlibat","kode","nama_alat_terlibat","kode asc",""); 
		
			$html= "<option value='' >--Alat Terlibat--</option>";
		if (count($listkota)>0){
			while($data=each($listkota))
			{
				$selected=trim($nilai)==trim($data['key'])?" selected ":"";
				$html .= "<option value='".$data['key']."' $selected>".$data['value']."</option>";     
			}  
			$msg['kosong']=false;
		}else{
			$msg['kosong']=true;
		}
        $msg['html']= $html;
        return json_encode($msg);
   }

   public function getKodeJam($jam="") {
	global $dcistem;
	$db = $dcistem->getOption("framework/db");
	if(trim($jam)<>""){
		$data=$db->select("kode,nama_jam","ref_kode_jam")
		->where("'".$jam."' BETWEEN start_hour AND end_hour ")->get(0);
		 return $data;
	}else{
		 return array();
	}
}

}
?>