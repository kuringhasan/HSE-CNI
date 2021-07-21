<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Damas Model
 *
 * @author Hasan <kuring.hasan@gmail.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class Ref_Pit_Model extends Model {

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

        $cols_and_vals ="name=$nama_val,closed=$closed_val,contractor_id=$contractor_id_val,is_empty=$empty_val";				

		$sqlin="UPDATE domes SET $cols_and_vals WHERE id=$id_lama;";
		$rsl = $db->query($sqlin);
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
    public function getDome($id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($id)=="" or  $id == null){
			return array();
		}else{

			$filter="id=".$id."";
            $data= $db->select("*","domes")->where($filter)->get(0);

			if(!empty($data)){
				$referensi	= $master->referensi_session();
				$rec    	= new stdClass;
                $rec->id	= $data->id;
                $rec->name  =   $data->name;
                $rec->closed      = $data->closed;
                $rec->contractor_id		= $data->contractor_id;
				if(trim($format)=="array"){
					$result = array_merge((array) $data, (array) $rec);
					return $result;
				}else{
					$result	= (object) array_merge((array) $data, (array) $rec);
					return $result;
				}

			}else{
				return array();
			}
		}
	}

	public function json($category="list_obat",$query="",$array_value=array()) {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        $List   = array();
        $filter = "";
        switch($category){
            case "list_obat":
                if(trim($query)<>""){
            	   $filter="(o.name like '%".$query."%' or o.name='".$query."' or oc.category like '%".$query."%' or oc.category = '".$query."')";
                }
                if(!empty($array_value)){

                    if(isset($array_value['active']) and trim($array_value['active'])<>""){
                        $filter=trim($filter)<>""?$filter." and ifnull(o.active,'')=".$array_value['active']."":"ifnull(o.active,'')=".$array_value['active']."";
                    }

                }
                $listdata= $db->select("o.id,o.name,o.category category_code,oc.category category_name,o.active","keswan_obat o
                left join keswan_obat_category oc on oc.code=o.category")->where($filter)->get();
        		$i=0;
                if(!empty($listdata)){
            		while($data=current($listdata)){
      		            $lengkap=trim($data->category_code)==""?$data->name:$data->name." - ".$data->category_name;
            		    $List[$i]['ID']=$data->id;
            		    $List[$i]['Nama']=$data->name;
            		    $List[$i]['Lengkap']=$lengkap;
            		    $i++;
            		    next($listdata);
            		}
                }

          break;

      }
      return $List;
    }

    public function comboAjax($kategori,$parentcode,$nilai=array()) {

        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $msg=array();
        $html="";
        switch($kategori){
            case "list_pit":
                if($parentcode=='')
                {
                    $msg['kosong']=true;
                    $html="<option value=''>-PIT-</option>";
                }else{
                    $condition="contractor_id='".$parentcode."'";
                    $listdata =Model::getOptionList("lokasi_pit","id","block_name","block_name asc",$condition);

					 $html= "<option value='' >-PIT-</option>";
                    if (count($listdata)>0){
                        while($data=each($listdata))
                        {
                           $selected=trim($nilai['nilai'])==trim($data['key'])?" selected ":"";
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
