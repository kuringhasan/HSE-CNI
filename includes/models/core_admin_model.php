<?php
/**
 * @package Core
 * @subpackage Admin Model
 *
 * @author Hasan <kuring.hasan@gmail.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class Core_Admin_Model extends Model {

	public function __construct($LevelID = "") {
		global $dcistem;
		$auth = $dcistem->getOption("framework/auth");
		if(!is_a($auth, "Auth")) {
   			$config = $dcistem->getOption("system/auth");
   			$driver = "Auth_".$config["driver"]."_Driver";
			$auth   = new $driver();
			$dcistem->setOption("framework/auth", $auth);
		}
		if(!empty($LevelID)) {
			$this->getDataFrom_LevelID($LevelID);
		}
	}
	public function optionListContractorDependingLevel($label,$default_value="",$prefix_id_form="",$form_properties=""){
		global $dcistem;
		$master=new Master_Ref_Model();
		$db   = $dcistem->getOption("framework/db");
		$level_id = $_SESSION["framework"]["login_as"];
		$ref_id= $_SESSION["framework"]["ref_id"];
        $lis=array();
        
        if($level_id=='administrator' or $level_id=='admin_production' or $level_id=='admin_shipment' or $level_id=='pimpinan')
        {// ADMINISTRATOR	 
        	/** START OF ListContractor */
        	
        
            $list_kontraktor=Model::getOptionList("partner","id","name","","ifnull(is_contractor,0)=1 and ifnull(active,'active')='active'"); 
        	//$ListBidang =Model::getOptionList("tbr_lokasibidang","LokasiBidangKode","LokasiBidangNama","LokasiBidangKode asc","");  
			$lis['ListContractor']['Label']=$label;
            $lis['ListContractor']['ContractorName']="";
            $lis['ListContractor']['InputForm']  ="<select name='contractor_id'  id='".$prefix_id_form."contractor_id' ".$form_properties.">";
            $lis['ListContractor']['InputForm'] .="<option value=\"\">--Contractor--</option>";
			while($data = each($list_kontraktor)) 
          	{
                $pilih=$data['key']==$default_value?" selected ":"";
                $lis['ListContractor']['InputForm']  .="<option value='".$data['key']."'  ".$pilih." >".$data['value']."</option>";
	      	}
            $lis['ListContractor']['InputForm'] .="</select>";
            /** END OF ListContractor */
            
        }// END OF ADMINISTRATOR
       
        if($level_id=='pjo_contractor' or $level_id=='admin_contractor' or $level_id=='contractor_pit' or $level_id=='contractor_rehandling')
        {
        	$contractor_id=$ref_id;
          	$kondisi="id=".$contractor_id."";          	
            $kontraktor	=$db->select("id,name","partner")->where($kondisi)->get(0);          	
            	$lis['ListContractor']['Label']	=$label;
			$lis['ListContractor']['ContractorName']=$kontraktor->name;          
            $lis['ListContractor']['InputForm']=$kontraktor->name."<input name='contractor_id'  id='".$prefix_id_form."contractor_id'  type=\"hidden\"  value='".$kontraktor->id."' ".$form_properties."/>";
		
        }
       
		return $lis;
		
	}
	public function SearchDependingLevel($kategori="report_production",$field=""){
		global $dcistem;
		$master=new Master_Ref_Model();
		$db   = $dcistem->getOption("framework/db");
		$sortir=new Adm_Sortir_Model();
		$level_id = $_SESSION["framework"]["login_as"];
		$ref_id= $_SESSION["framework"]["ref_id"];
        //echo "<pre>";print_r($_SESSION["framework"]);echo "</pre>";
        $keriteria=array();
        $search="";
        
        switch($kategori){
            case "report_production" :
                if($level_id=='pjo_contractor' or $level_id=='contractor_pit' or $level_id=='contractor_rehandling' or  $level_id=='admin_contractor')
                {// PETUGAS KESWAN
                    $field=trim($field)==""?"contractor_id":$field;
                    $keriteria[]="$field=".(int)$ref_id.""; 
                  	$search=$sortir->fromFormcari($keriteria,"and");	 
                	
                }// END OF ADMINISTRATOR BIDANG
            break;
            case "ref_contractor" :
                if($level_id=='pjo_contractor' or $level_id=='contractor_pit' or $level_id=='contractor_rehandling' or  $level_id=='admin_contractor')
                {// PETUGAS KESWAN
                    $field=trim($field)==""?"contractor_id":$field;
                    $keriteria[]="$field=".(int)$ref_id.""; 
                  	$search=$sortir->fromFormcari($keriteria,"and");	 
                	
                }// END OF ADMINISTRATOR BIDANG
            break;
           case "ref_dome" :
                if($level_id=='pjo_contractor' or $level_id=='contractor_pit' or $level_id=='contractor_rehandling' or  $level_id=='admin_contractor')
                {// PETUGAS KESWAN
                    $field=trim($field)==""?"contractor_id":$field;
                    $keriteria[]="$field=".(int)$ref_id.""; 
                  	$search=$sortir->fromFormcari($keriteria,"and");	 
                	
                }// END OF ADMINISTRATOR BIDANG
            break;
            case "logistik" :
                if($level_id=='petugas_logistik')
                {// PETUGAS KESWAN
                    $field=trim($field)==""?"pegawai_id":$field;
                    $keriteria="$field=".(int)$ref_id.""; 
                  	$search=$sortir->fromFormcari($keriteria,"and");	 
                	
                }// END OF ADMINISTRATOR BIDANG
            break;
       }
      
		return array("array"=>$keriteria,"string"=>$search);
		
	}
	public function FilterDependingLevel($fields=""){
		global $dcistem;
		$master=new Master_Ref_Model();
		$db   = $dcistem->getOption("framework/db");
		$sortir=new Adm_Sortir_Model();
		$level_id = $_SESSION["framework"]["login_as"];
		$ref_id= $_SESSION["framework"]["ref_id"];
        $keriteria=array();
        $search="";
        switch ($level_id){
        	case 'administrator':
        		$search="";
        	break;
        	case 'admin_bappeda':
        		$search="";
        	break;
        	case 'admin_kecamatan':
        	case 'camat':
        		$keriteria[]="$fields='".$ref_id."'"; 
        	break;
        	case 'admin_desa':
        	case 'kepala_daerah':
        	case 'lurah':
        		$keriteria[]="$fields='".$ref_id."'"; 
        	break;
        }
        $search=$sortir->fromFormcari($keriteria,"and");
        //echo "cek".$loginas.$trupb;exit;
		return $search;
		
	}
	public function CurrentLevelUnit(){
		global $dcistem;
		$master=new Master_Ref_Model();
		$db   = $dcistem->getOption("framework/db");
		$level_id = $_SESSION["framework"]["login_as"];
		$ref_id= $_SESSION["framework"]["ref_id"];
		$user_level=$db->select("AppLevelListLevelID,AppLevelListLevelName,AppLevelListRefName,AppUserLevelRefID,
		AppLevelUnit","tbaapplevellist l inner join tbaappuserlevel ul on ul.AppUserLevelLevelID=l.AppLevelListLevelID")
		->where("AppLevelListLevelID='".$level_id."'")->get(0);
		$kode_unit=$user_level->AppUserLevelRefID;
        $result=array();
        if(trim($user_level->AppLevelUnit)=='desa')
        {// Level Desa/Kelurahan
			$ds=$db->select("desa_kode,desa_nama,desa_status, kecKode,kecNama","tbr_desa ds 
			inner join tbr_kecamatan kec on kec.kecKode=ds.desa_kecamatan")
			->where("desa_kode='".$kode_unit."'")->get(0); 
			
			$status=trim($ds->desa_status)==""?"":$ds->desa_status." ";
			$result['current']['code']	=$ds->desa_kode;
			$result['current']['name']	=$status.$ds->desa_nama;
			$result['current']['level_unit']	=$user_level->AppLevelUnit;
			$result['up']['code']		=$ds->kecKode;
			$result['up']['name']		=$ds->kecNama;
			$result['up']['level_unit']	="kecamatan";	 
        	
        }// END OF ADMINISTRATOR
        if(trim($user_level->AppLevelUnit)=='kecamatan')
        {// Kecamatan	 
        	$kec=$db->select("kecKode,kecNama,kecKodeKota,KotaNama","tbr_kecamatan kec
			inner join tbr_kota kot on kot.KotaKode=kec.kecKodeKota")
			->where("kecKode='".$kode_unit."'")->get(0); 
			$result['current']['code']	=$kec->kecKode;
			$result['current']['name']	=$kec->kecNama;
			$result['up']['code']		=$kec->kecKodeKota;
			$result['up']['name']		=$kec->KotaNama;
        }// END OF ADMINISTRATOR BIDANG
        if(trim($user_level->AppLevelUnit)=='skpd')
        {
        	
        }
        if(trim($user_level->AppLevelUnit)=='admin_subunit')
        {
          	
            
        }
        //echo "cek".$loginas.$trupb;exit;
		return $result;
		
	}
	public function translate_kodelokasi(){
		global $dcistem;
		$master=new Master_Ref_Model();
		$db   = $dcistem->getOption("framework/db");
		$sortir=new Adm_Sortir_Model();
		$level_id = $_SESSION["framework"]["login_as"];
		$ref_id= $_SESSION["framework"]["ref_id"];
		$kondisi="lbKode='".$ref_id."'";
		$hasil=array();
		$lokasi=$db->select("lbKode,lbPemilik,PemilikBarangNama,lbProvinsi,LokasiProvNama,lbKabupaten,
		LokasiKabNama,lbBidang,lsubNama,lbUnit,LokasiBidangNama,lubNama","vwm_lokasibarang")
		->where($kondisi)->get(0); 
		
		switch($lokasi->lbPemilik){
			case "00":
				$hasil['Pamilik']=$lokasi->PemilikBarangNama;
			break;
			case "11":
				$hasil['Pamilik']=$lokasi->PemilikBarangNama." ".$lokasi->LokasiProvNama;
				$hasil['Provinsi']['Kode']=$lokasi->lbProvinsi;
				$hasil['Provinsi']['Nama']=$lokasi->LokasiProvNama;
				$hasil['Kabupaten']['Kode']=$lokasi->lbKabupaten;
				$hasil['Kabupaten']['Nama']=$lokasi->LokasiKabNama;
			break;
			case "12":
				$hasil['Pamilik']=$lokasi->PemilikBarangNama." ".$lokasi->LokasiKabNama;
				$hasil['Provinsi']['Kode']=$lokasi->lbProvinsi;
				$hasil['Provinsi']['Nama']=$lokasi->LokasiProvNama;
				$hasil['Kabupaten']['Kode']=$lokasi->lbKabupaten;
				$hasil['Kabupaten']['Nama']=$lokasi->LokasiKabNama;
			
			break;
		}
		$hasil['Unit']['Kode']=$lokasi->lbUnit;
		$hasil['Unit']['Nama']=$lokasi->lubNama;
		if(isset($_POST)){
			$srt_unit="lubKode='".$_POST['Unit']."'";
			$unit=$db->select("lubKode,lubNama","tbr_lokasiskpd")->where($srt_unit)->get(0);
			$hasil['Unit']['Kode']=$unit->lubKode;
			$hasil['Unit']['Nama']=$unit->lubNama;
		}
		return $hasil;
		
	}
}
?>