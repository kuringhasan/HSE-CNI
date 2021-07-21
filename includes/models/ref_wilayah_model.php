<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Karyawan Model
 * 
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
 * @author Alan Ridwan M <alanrm82@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Ref_Wilayah_Model extends Model {
	
	public function __construct() {
	   global $dcistem;
	   $this->UnitID=$dcistem->getOption("system/web/unit_id");
       $this->DapilID=$dcistem->getOption("system/web/dapil_id");
	}
	
	public function getKota($kode_kota=""){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
      
        $detail=$db->select("kabupatenKode Kode,kabupatenNama Nama,kabupatenJenis Jenis,kabupatenJenisSingkat JenisSingkat, 
        propinsiKode KodeProvinsi,propinsiNama NamaProvinsi","tbrkabupaten kota
			left join tbrpropinsi prov on prov.propinsiKode=kota.kabupatenPropinsiKode")
			->where("kabupatenKode='".$kode_kota."'")->get(0);
        if(!empty($detail)){
            return $detail;
        }else{
            return array();
        }
        
			 
	}
    public function getKecamatan($kode_kec=""){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
      
        $detail=$db->select("kecKode Kode,kecNama Nama,kecKodeKPU KodeKPU,kecKodeBPS KodeBPS,kecDAPIL DAPIL,
        kabupatenKode KodeKota,kabupatenNama NamaKota, propinsiKode KodeProvinsi,propinsiNama NamaProvinsi","tbrkecamatan kc
        inner join tbrkabupaten kota on kota.kabupatenKode=kc.kecKota
		inner join tbrpropinsi prov on prov.propinsiKode=kota.kabupatenPropinsiKode")
			->where("kecKode='".$kode_kec."'")->get(0);
        if(!empty($detail)){
            return $detail;
        }else{
            return array();
        }
        
			 
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
                    $listkota =Model::getOptionList("tbrkabupaten","kabupatenKode","concat(kabupatenJenis,' ',kabupatenNamaSaja) as nama","kabupatenKode asc","kabupatenPropinsiKode='".$parentcode."'"); 
                    
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
             case "listdapilkota":
            	
                if($parentcode=='')
                {
                    $msg['kosong']=true;
                    $html="<option value=''>--Dapil--</option>";
                }else{
                    $listdapil =Model::getOptionList("tbrdapilkota","dapilKode","dapilNama","dapilNama asc","dapilKota='".$parentcode."'"); 
                     $html= "<option value='' >--Dapil--</option>";
                    if (count($listdapil)>0){
                        while($data=each($listdapil))
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
           case "listkecamatan":
            	
                if($parentcode=='')
                {
                    $msg['kosong']=true;
                    $html="<option value=''>--Pilih Kec--</option>";
                }else{
                    $filter_dapil="";
                    if(trim($this->DapilID)<>""){
                        $filter_dapil="and kecDAPIL='".$this->DapilID."'";
                    }
                    $listkec =Model::getOptionList("tbrkecamatan","kecKode","kecNama","kecNama asc","kecKota='".$parentcode."' ".$filter_dapil); 
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
             case "listkecamatan_dapil":
            	
                if($parentcode=='')
                {
                    $msg['kosong']=true;
                    $html="<option value=''>--Pilih Kec--</option>";
                }else{
                    $listkec =Model::getOptionList("tbrkecamatan","kecKode","kecNama","kecNama asc","kecDAPIL='".$parentcode."'"); 
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
            case "listdesa":
            	
                if($parentcode=='')
                {
                    $msg['kosong']=true;
                    $html="<option value=''>--Pilih Desa--</option>";
                }else{
                    $listdesa =Model::getOptionList("tbrdesa","desaKode","desaNama","desaNama asc","desaKecamatan='".$parentcode."'"); 
                     $html= "<option value='' >--Pilih Desa--</option>";
                    if (count($listdesa)>0){
                        while($data=each($listdesa))
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