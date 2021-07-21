<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Damas Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Ref_Barge_Model extends Model {
  
	public function __construct() {
		
	}
    public function insert($nama,$capacity,$description,$rgb_color,$is_active) {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master=new Master_Ref_Model();
        $TglSkrg=date("Y-m-d H:i:s");
        $sqlin="";
        $nama_val	=$master->scurevaluetable($nama);
        $description_val	=$master->scurevaluetable($description);
        $rgb_color_val	=$master->scurevaluetable($rgb_color);
        $is_active_val	=$master->scurevaluetable($is_active);
        $capacity_val	=$master->scurevaluetable($capacity,"number");
        
       	$cols="name,capacity,description,rgb_color,is_active";
        $vals="$nama_val,$capacity_val,$description_val,$rgb_color_val,$is_active_val";
		$sqlin="INSERT INTO barges ($cols) VALUES($vals);";
		$rsl=$db->query($sqlin);
		if(isset($rsl->error) and $rsl->error===true){
	   	 		$msg['success']=false;
            	$msg['message']="Error, ".$rsl->query_last_message." ".$sqlin;
		}else{
            $msg['success']=true;
            $msg['kode']=$kode;
            $msg['message']="Data sudah ditambahkan"; 
           
        }
     
      return $msg;
   }     
 public function update($id_lama,$nama,$capacity,$description,$rgb_color,$is_active) {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master=new Master_Ref_Model();
        $TglSkrg=date("Y-m-d H:i:s");
        $nama_val	=$master->scurevaluetable($nama);
        $description_val	=$master->scurevaluetable($description);
        $rgb_color_val	=$master->scurevaluetable($rgb_color);
        $is_active_val	=$master->scurevaluetable($is_active);
        $capacity_val	=$master->scurevaluetable($capacity,"number");
        
        $cols_and_vals="name=$nama_val,capacity=$capacity_val,description=$description_val,
                    rgb_color=$rgb_color_val,is_active=$is_active_val";
       
		$sqlin="UPDATE barges SET $cols_and_vals WHERE id=$id_lama;";
		$rsl=$db->query($sqlin);
		if(isset($rsl->error) and $rsl->error===true){
	   	 		$msg['success']=false;
            	$msg['message']="Error, ".$rsl->query_last_message;
		}else{
            $msg['success']=true;
            $msg['message']="Perubahan data sudah disimpan "; 
           
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
            $sqlin="DELETE FROM barges  WHERE id=$id;";
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
    public function getBarge($id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($id)=="" or  $id == null){
			return array();
		}else{
		  
			$filter="id=".$id."";
            return $db->select("*","barges")->where($filter)->get(0);

		
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
     public function comboAjax($kategori,$parentcode,$nilai="") {

        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $msg=array();
        $html="";
        switch($kategori){
            case "list_barges":
                if($parentcode=='')
                {
                    $msg['kosong']=true;
                    $html="<option value=''>-Barge-</option>";
                }else{
                    $condition="location_id='".$parentcode."'";
                    $listdata =Model::getOptionList("barges","id","name","name asc",$condition);

					 $html= "<option value='' >-Barge-</option>";
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