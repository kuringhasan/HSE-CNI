<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Karyawan Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class List_Tpk_Model extends Model {
	
	public function __construct($NIP = "") {
	
	}
	public function getTPK($id=""){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
        $master		=new Master_Ref_Model();
      
     	$filter	="m.id=$id";
    	 $data=$db->select("m.id,mcp_type,name,address,is_active,rayon_id,ry.rayon_name,kp.pNama kord_rayon_name,
        kp.pAlias kord_rayon_alias,kp.pGelarDepan kord_rayon_gelar_depan,kp.pGelarBelakang kord_rayon_gelar_belakang","mcp m
        left join rayon ry on ry.id=m.rayon_id
        left join keswan_pegawai kp on kp.pID=ry.kordinator")
		->where($filter)->get(0);
       
        return $data;
			 
	}
	public function getKelompok($kelompik_id=""){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
       if(trim($kelompik_id)=="" or  $kelompik_id == null){
			return array();
		}else{
			$filter="kel.id=".$kelompik_id."";
        	$listdata=$db->select("kel.id kelompok_id,kel.name kelompok_nama,mcp_id,mcp_type,m.name mcp_nama,address mcap_alamat,
            is_active mcp_active","kelompok kel
            inner join mcp m on m.id=kel.mcp_id")->where($filter)->get(0);
            return $listdata;
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
	public function comboAjax($kategori,$parentcode,$nilai="") {
        
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $msg=array();
        $html="";
        switch($kategori){
            case "listkelompok":
                if($parentcode=='')
                {
                    $msg['kosong']=true;
                    $html="<option value=''>--Pilih Kelompok--</option>";
                }else{
                    $listkota =Model::getOptionList("kelompok","id","name","id asc","mcp_id='".$parentcode."'"); 
                    
					 $html= "<option value='' >--Pilih Kelompok--</option>";
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
                    
                }
            break;
            case "list_kelompokharga":
                if($parentcode=='')
                {
                    $msg['kosong']=true;
                    $html="<option value=''>--Pilih --</option>";
                }else{
                    $listkota =Model::getOptionList("kelompok_harga","id","name","id asc","kelompok_id='".$parentcode."'"); 
                    
					 $html= "<option value='' >--Pilih--</option>";
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
                    
                }
            break;
            case "listbagian":
            	
                if($parentcode=='')
		        {
		        	$msg['kosong']=true;
                    $html="<option value=''>--Pilih Bagian--</option>";
		        }else{
		             $listdata =Model::getOptionList("tbrunitkerjabagian","BagianKode","BagianNama","BagianNama asc","BagianUnitKerja='".$parentcode."'");   
		            $html= "<option value='' >--Pilih Bagian--</option>";
		            if (count($listdata)>0){
			            while($data=each($listdata))
			            {
			                $selected=trim($nilai)==trim($data['key'])?" selected ":"";
			                $html .= "<option value='".$data['key']."' $selected>".$data['value']."</option>";    
			            }
			         	$msg['kosong']=false;
                    }else{
                        $msg['kosong']=true;
                    }
		        }  
				 
            break;
            
        }
        $msg['html']= $html;
        return json_encode($msg);
   }
	
}
?>