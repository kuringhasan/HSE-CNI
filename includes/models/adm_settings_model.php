<?php
/**
 * @package Web
 * @subpackage Redirect Model
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class Adm_Settings_Model extends Model {

	public function __construct() {
	
		
	}
    public function getListReferenceCowType() {
        $rekap_tipe=array('jml_induk',
                    'jml_dara',
                    'jml_betina_muda',
                    'jml_pedet_btn',
                    'jml_pedet_jtn',
                    'jml_jantan_dewasa');
        return $rekap_tipe;
    }

	public function getSetings($id,$format="object") {
		
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($id)=="" or  $id == null){
			return array();
		}else{
		  
         
            $filter="settingID=".$id."";
            $data=$db->select("settingID,settingKey,settingValue,settingKategori","tbrsettings")
            ->where($filter)->get(0);//->orderby($order)->where($filter)->get(0);

					
			if(!empty($data)){
				$referensi	= $master->referensi_session();
				$rec    	= new stdClass;
               
				$rec->settingKey		= $data->settingKey;
                $rec->settingValue		= $data->settingValue;
                $rec->settingKategori		= $data->settingKategori;
                $html_value='<input type="text" class="input" name="frm_value" id="frm_value" value="'.$data->settingValue.'" size="20" />';
                if(trim($data->settingKey)=="populasi"){
                    $rekap_tipe=$this->getListReferenceCowType();
                    $serialis=unserialize($data->settingValue);
                    if(is_array($serialis) ){
                       //echo "cek :";print_r($serialis);echo "</br>";
                        $html="";
                        //$arr_rc=array();
            			$j=0;
                        
                            while($pr=current($rekap_tipe)){
                     			$ck=in_array($pr,$serialis)?" checked='checked' ":"";
                     			$input  ="<input type=\"checkbox\" name=\"frm_value[".$j."]\" value=\"".$pr."\" $ck  />";
                                $html   = trim($html)==""?$input." ".$pr:$html."<br />".$input." ".$pr;
                				$j++;
                     			next($rekap_tipe);
                     		}
                            $html_value=$html;
                    }
                }
                
                
                $rec->settingHtmlValue		= $html_value;
               
				if(trim($format)=="array"){
					return (array) $rec;
				}else{
				//	$result	= (object) array_merge((array) $data, (array) $rec);
					return $rec;
				}				
			
			}else{
				return array();
			}
		}
	}

}
?>