<?php
/**
 * @package Mahasiswa
 * @subpackage Fakultas Model
 * 
 * @author Hasan <san2_1981@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Ref_Jabatan_Model extends Model {
	
	public function __construct() {
	   }
public function getJabatan($id) {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
    
    	if(trim($id)<>"" and $id <> null){
    		 $data=$db->select("JabatanID ID,JabatanParent Parent,JabatanUnit UnitOrganisasiID,
             JabatanNama NamaJabatan,JabatanUrutan Urutan,JabatanTunjangan Tunjangan","tbrjabatan")
		->where("JabatanID=".$id."")->get(0);
			 	
			 return $data;
		}else{
			 return object();
		}
   }	   
public function refTreeJabatan($ParentID,$i,$tanda="") {
	global $dcistem;
	$db   = $dcistem->getOption("framework/db");
	$kepagawaian=new Manage_Pegawai_Model();
   $listdata=$db->select("JabatanID,JabatanParent,JabatanUnit,unitNama,JabatanNama,JabatanUrutan,JabatanTunjangan","tbrjabatan jab
   left join tbrunitorganisasi unit on unit.unitID=jab.JabatanUnit")
		->where("JabatanParent='".$ParentID."'")->orderby("JabatanParent asc,JabatanUrutan asc")->get();
		$j=array();
		$i=0;
      	while($data = current($listdata)){
      		$j[$i]['id']       = $data->JabatanID;
      		$j[$i]['name']     = $data->JabatanNama;//trim($data->UnitStatus)==""?$data->UnitNama:$data->UnitStatus." ".$data->UnitNama;
      		$j[$i]['prefix']    = ($data->JabatanParent==0?"":$tanda." ").$data->JabatanNama;
      		$j[$i]['parent']   	= $data->JabatanParent;
            $j[$i]['unit_id']   = $data->JabatanUnit;
            $j[$i]['unit_name']   = $data->unitNama;
            $j[$i]['tunjangan']   = $data->JabatanTunjangan;
            $j[$i]['pejabat_nama']    = $kepagawaian->getNamaLengkap($data->pNama,$data->pGelarDepan,$data->pGelarBelakang);
      		$j[$i]['order']     = $data->JabatanUrutan;
      		
        	$j[$i]['children'] =$this->refTreeJabatan($data->JabatanID,0,($tanda."-"));
       	
        	next($listdata);
        	$i++;
      	}
		 
  		return $j;
}
public function refTreeJabatan2($ParentID,$i,$tanda="") {
	global $dcistem;
	$db   = $dcistem->getOption("framework/db");
	$kepagawaian=new Manage_Pegawai_Model();
   $listdata=$db->select("JabatanID,JabatanParent,JabatanUnit,unitNama,JabatanNama,JabatanUrutan,JabatanTunjangan,
   pNoInduk NoInduk,pNama,pGelarDepan,pGelarBelakang","tbrjabatan jab
   left join tbrunitorganisasi unit on unit.unitID=jab.JabatanUnit
   left join tbtjabatanhistory jh on jh.tjabJabatan=jab.JabatanID and ifnull(tjabAktif,'0')='1'
   left join tbmpersonal peg on peg.pID=jh.tjabPegawai")
		->where("JabatanParent='".$ParentID."'")->orderby("JabatanParent asc,JabatanUrutan asc")->get();
		$j=array();
		$i=0;
      	while($data = current($listdata)){
      		$j[$i]['id']       = $data->JabatanID;
      		$j[$i]['name']     = $data->JabatanNama;//trim($data->UnitStatus)==""?$data->UnitNama:$data->UnitStatus." ".$data->UnitNama;
      		$j[$i]['prefix']    = ($data->JabatanParent==0?"":$tanda." ").$data->JabatanNama;
      		$j[$i]['parent']   	= $data->JabatanParent;
            $j[$i]['unit_id']   = $data->JabatanUnit;
            $j[$i]['unit_name']   = $data->unitNama;
            $j[$i]['tunjangan']   = $data->JabatanTunjangan;
            $j[$i]['pejabat_nama']    = $kepagawaian->getNamaLengkap($data->pNama,$data->pGelarDepan,$data->pGelarBelakang);
      		$j[$i]['order']     = $data->JabatanUrutan;
      		
        	$j[$i]['children'] =$this->refTreeJabatan($data->JabatanID,0,($tanda."-"));
       	
        	next($listdata);
        	$i++;
      	}
		 
  		return $j;
}
public function forTreeView($array_input,$new_arr=array(),$root_parent=0){
	
	while($m=current($array_input)){
	
		$n['id']	=$m['id'];
		$n['name']	=$m['name'];
		$n['prefix_name']	=$m['prefix'];
		$n['parent']		=$m['parent'];
        $n['unit_id']	      =$m['unit_id'];
		$n['unit_name']       =$m['unit_name'];
        $n['tunjangan']    =$m['tunjangan'];
        $n['pejabat_nama']    =$m['pejabat_nama'];
        $n['order']         =$m['order'];
  	     $class=($m['parent']==$root_parent or $m['parent']=="")?"treegrid-".(int)$m['id']:"treegrid-".(int)$m['id']." treegrid-parent-".(int)$m['parent'];
		
        $n['class']	=$class;
		if(isset($m['children']) and is_array($m['children'])){	
			//$class=($m['parent']==0 or $m['parent']=="")?"treegrid-".$m['id']:"treegrid-".$m['id']." treegrid-parent-".$m['parent'];
		}
		array_push($new_arr,$n);
		if(isset($m['children']) and is_array($m['children'])){	
			$new_arr=$this->forTreeView($m['children'],$new_arr);
		//	$class=($m['parent']==0 or $m['parent']=="")?"treegrid-".$m['id']:"treegrid-".$m['id']." treegrid-parent-".$m['parent'];
		}
		
		
		next($array_input);
	}
	return $new_arr;
}

public function del_rekursif($array_input,$new_arr=array()) {
	while($m=current($array_input)){
		array_push($new_arr,$m['id']);
		if(isset($m['children']) and is_array($m['children'])){	
			$new_arr=$this->del_rekursif($m['children'],$new_arr);
		}
		next($array_input);
	}
	return $new_arr;
}
    
public function comboAjax($kategori,$parentcode,$nilai="") {
        
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $msg=array();
        $html="";
        switch($kategori){
            case "list_rw":
                if($parentcode=='')
                {
                    $msg['kosong']=true;
                    $html="<option value=''>--Pilih RW--</option>";
                }else{
                    $listdata=Model::getOptionList("tbm_unit", "UnitID","UnitNama","UnitUrutan ASC","UnitParent=$parentcode and UnitKategori='rw'"); 
                    
					 $html= "<option value='' >--Pilih RW--</option>";
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
            case "list_rt":
                if($parentcode=='')
                {
                    $msg['kosong']=true;
                    $html="<option value=''>--Pilih RT--</option>";
                }else{
                    $listdata=Model::getOptionList("tbm_unit", "UnitID","UnitNama","UnitUrutan ASC","UnitParent=$parentcode and UnitKategori='rt'"); 
                    
					 $html= "<option value='' >--Pilih RT--</option>";
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
   public function getDetailRefJabatan($id,$format="object") {
		global $dcistem;
		$db   = $dcistem->getOption("framework/db");

		if(trim($id)=="" or  $id == null){
			return array();
		}else{
			$referensi	= $_SESSION["referensi"];
			$filter="kode=".$id."";
			$list_qry=$db->select("*","ref_jabatan")
			->where($filter)->lim(0);//->orderBy($order)
		
			$data = $db->fetchObject($list_qry);
			$hasil=array();
			if(!empty($data)){
			
				$rec    	   				= new stdClass;
				$rec->kode            		= $data->kode;
				$rec->nama_jabatan       	= $data->nama_jabatan;
				
				if(trim($format)=="array"){
					$hasil = (array) $rec;
				}else{
					$hasil	= $rec;
				}
			}
					
			return $hasil;
		}
   }
}

?>