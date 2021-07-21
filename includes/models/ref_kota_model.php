<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Karyawan Model
 * 
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
 * @author Alan Ridwan M <alanrm82@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Ref_Kota_Model extends Model {
	
	public function __construct($NIP = "") {
	
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
            case "listkota":
                if($parentcode=='')
                {
                    $msg['kosong']=true;
                    $html="<option value=''>--Pilih Kota--</option>";
                }else{
                    $listkota =Model::getOptionList("tbrKabupaten","kabupatenKode","concat(kabupatenJenis,' ',kabupatenNamaSaja) as nama","kabupatenKode asc","kabupatenPropinsiKode='".$parentcode."'"); 
                    
					 $html= "<option value='' >--Kota/Kab--</option>";
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
           case "listkec":
            	
                if($parentcode=='')
                {
                    $msg['kosong']=true;
                    $html="<option value=''>--Pilih Kec--</option>";
                }else{
                    $listkec =Model::getOptionList("tbr_lokasikecamatan","kecKode","kecNama","kecUrutan asc","kecKota='".$parentcode."'"); 
                     $html= "<option value='' >--Pilih Kec--</option>";
                    if (count($listkec)>0){
                        while($data=each($listkec))
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