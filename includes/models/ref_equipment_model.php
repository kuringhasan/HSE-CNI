<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Karyawan Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Ref_Equipment_Model extends Model {
	
	public function __construct() {
	
	}

    
   	public function insert_periode($minggu_ke,$start_date,$end_date,$year){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
        $hasil  = array();
        $cek=$db->select("id","week_periode")->where("week=$minggu_ke and tahun=$year")->get(0);
        if(empty($cek)){
            
            $colsc="week,start_date,end_date,tahun";
       	    $valuesc="$minggu_ke,'".$start_date."','".$end_date."',$year";
            $sqlinc="INSERT INTO week_periode ($colsc) VALUES ($valuesc);";
            $rslc=$db->query($sqlinc);
            if(isset($rslc->error) and $rslc->error===true){
       	 		$hasil['success']=false;
            	$hasil['message']="Error, ".$rslc->query_last_message;
    	    }else{
    	        
                $hasil['success']=true;
               	$hasil['message']="Data sudah diinsert";
            }
        }
        return $hasil;
			 
	}
    public function get_periode($minggu_ke,$year){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
        $hasil  = array();
        $cek=$db->select("*","week_periode")->where("week=$minggu_ke and tahun=$year")->get(0);
        
        return $cek;
			 
	}
	public function getEquipment($id){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
        $master		=new Master_Ref_Model();
       	if(trim($id)=="" or  $id == null){
			return array();
		}else{
            $data=$db->select("eq.id,eq.category category_code, eqc.category category_name,eq.name,type,load_factor,merk,nomor,remarks,
            vendor_id,p.name vendor_name,depricated","equipment eq 
            inner join equipment_category eqc on eqc.code=eq.category
            left join partner p on p.id=eq.vendor_id")
    		->where("eq.id=$id")->get(0);//
            return $data;
        }
        
			 
	}
     public function json($query="",$array_value=array()) {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        
    	$filter="nomor like '%".$query."%' or nomor like '%".$query."%' or merk like '%".$query."%'";
	    $list_qry= $db->select("eq.id,nomor,type,category,eq.name, p.name nama_vendor","equipment eq
        left join partner p on p.id=eq.vendor_id")->where($filter)->lim();
		$i=0;
		while($data=$db->fetchObject($list_qry)){
		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
           $nama=$data->nomor;
           if(trim($data->name)<>"" and (trim($data->nomor)<>trim($data->name))){
              $nama=$nama." (".$data->name.")";
           }
           if(trim($data->type)<>""){
             $nama=$nama." ".$data->type."";
           }
           if(trim($data->type)<>""){
             $nama=$nama." ".$data->type."";
           }
           if(trim($data->nama_vendor)<>""){
             $nama=$nama."\ln".$data->nama_vendor."";
           }
		    $List[$i]['ID']=$data->id;
		    $List[$i]['Closed']=$data->closed;
		    $List[$i]['Nama']=$data->ket_indo;
            $List[$i]['Name']=$data->ket_english;
            
		    $List[$i]['Lengkap']=$nama;
		    $i++;
		}
        return $List;
    }
    public function json_data($category="list_truck",$query="",$array_value=array()) {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        $List   = array();
        $filter = "";
        switch($category){
            case "list_truck":
                $filter="category='HA' and (nomor like '%".$query."%' or c.name like '%".$query."%' or c.alias like '%".$query."%' or eq.type like '%".$query."%')";
        	    $list_qry= $db->select("eq.id,nomor,eq.type,category,eq.name, p.name nama_vendor,
                ec.partner_id contractor_id,c.name contractor_name,c.alias contractor_alias","equipment eq
                inner join equipment_contractor ec on ec.equipment_id=eq.id
                left join partner p on p.id=eq.vendor_id
                left join partner c on c.id=ec.partner_id")->where($filter)->lim();
        		$i=0;
        		while($data=$db->fetchObject($list_qry)){
        		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
                   $nama=$data->nomor;
                   
                   if(trim($data->type)<>""){
                     $nama=$nama." (".$data->type.")";
                   }
                
                   if(trim($data->contractor_name)<>""){
                     $nama=$nama." <br />".$data->contractor_name."";                    
                     if(trim($data->contractor_alias)<>""){
                        $nama=$nama." (".$data->contractor_alias.")";
                     }
                   }
        		    $List[$i]['ID']=$data->id;
        		    $List[$i]['Nama']=$data->nomor;
                    $List[$i]['Name']=$data->nomor;
                    
        		    $List[$i]['Lengkap']=$nama;
        		    $i++;
        		}

          break;

      }
      return $List;
    }

     public function list_data() {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
    	$filter="pNama like '%".$nama."%' or pNIK='".$nama."'";
	    $list_qry= $db->select("id,start_date,end_date,closed","periode")->where($filter)->lim();
		$i=0;
		while($data=$db->fetchObject($list_qry)){
		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
           $ket="";
            if(trim($data->coordKategori)=="JRG"){
                $ket=$data->coordKeteranganJaringan;
            }
            if(trim($data->coordKategori)=="TPS"){
                $ket="TPS ".$data->tpsNama." Desa ".$data->desaNama."<br /> Kec. ".$data->kecNama;
            }
            $jar=$data->ccNama." : ".$ket;
		    $List[$i]['ID']=$data->coordID;
		    $List[$i]['NIK']=$data->pNIK;
		    $List[$i]['Nama']=$data->pNama;
            $lengkap=trim($data->pNIK)<>""?"[".$data->pNIK."] ".$data->pNama:$data->pNama;
		    $List[$i]['Lengkap']="<div class='label_typeahead'>".$lengkap."<br />".$jar."</div>";
		    $i++;
		}
        return $List;
    }
    
    public function sync2odoo() {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $errorlevel=error_reporting();
        $sync_odoo=new Odoo_Api_Model();
        //error_reporting($errorlevel & ~E_NOTICE);
        $msg_error="";
        
        if(!$sync_odoo->connect()){
            $msg_error="Tidak dapat terhubung ke server, silahkan cek kembali URL, username atau password API";
        }
        $hasil_sync=array();
        $kategori=$category;
        if(trim($msg_error)==""){
            //echo "db:".$sync_odoo->database;
            $sync_success   = false;
            $message        = "";
            $list_ada=$db->select("id,start_date,end_date,closed,sync","periode")->where("ifnull(sync,false)=false")->orderBy("id desc")->get();
            if(!empty($list_ada)){
                //echo "<pre>"; print_r($list_ada);echo "</pre>";
                 while($data=current($list_ada)){   
                    $ids = $sync_odoo->models->execute_kw($sync_odoo->database, $sync_odoo->connect(), $sync_odoo->password,
                    'res.partner', 'search',array(array(
                                                    array('id', '=', 14)
                                                    )
                                            ),array('limit'=>1));
                   echo "<pre>"; print_r($ids);echo "</pre>";exit; 
                    $col_and_values=array("id"=>(int)$data->id,
                                            "star_date"=>$data->start_date,
                                            "end_date"=>$data->end_date,
                                            "closed"=>$data->closed);
                                            echo "con:".$sync_odoo->connect();
                      echo "<pre>"; print_r($col_and_values);echo "</pre>";                    
                        $create_tpk = $sync_odoo->models->execute_kw($sync_odoo->database, $sync_odoo->connect(), $sync_odoo->password,'periode', 'create',array($col_and_values));
                     
                    echo "<pre>"; print_r($create_tpk);echo "</pre>";exit;
                    $id_odoo= !empty($ids)?$ids[0]:""; 
                    if(!empty($cek_data)){
                        $col_and_values=array("name"=>$data->name,
                                        "x_address"=>$data->address,
                                        "active"=>$data->is_active);
                        $result = $this->models->execute_kw($this->database, $this->connect(), $this->password, 'stock.location', 'write',
                                        array(array((int)$id_odoo), $col_and_values));
                    
                        if(!is_array($result) and $result==true){
                            $sync_success=true;
                            $message="Berhasil sync update Location TPK";
                        }else{
                            $message="Gagal sync update Location TPK. Error : ".$result['faultString'];//$result;
                        }
                    }else{
                        // insert
                        $col_and_values=array("name"=>$data->name,
                                            "x_tpk_erp"=>(int)$data->id,
                                            "x_address"=>$data->address,
                                            "x_category"=>$category,
                                            "active"=>$data->is_active,
                                            "location_id"=>11);
                                            
                        $create_tpk = $this->models->execute_kw($this->database, $this->connect(), $this->password,'stock.location', 'create',array($col_and_values));
                        //$get_data=$this->get_purchase($id);
                        //echo "<pre>";print_r($create_tpk);echo "</pre>";exit;
                        if(!is_array($create_tpk) and trim($create_tpk)<>""){
                            $id_odoo=(int)$create_tpk;
                            $sync_success=true;
                            $message="Berhasil sync create Location TPK";
                        }else{
                            $message="Gagal sync create Location TPK";
                        }
                    }
                    if($sync_success){
                        $sql_up="UPDATE mcp SET sync=1 WHERE id=".$data->id."";
                        $db->query($sql_up);
                    }
                    next($list_ada);
                }
            }else{//end if emty $list_ada
                
            }//end if emty $list_ada
                      
            if($sync_success){
                $hasil_sync['sync']=true;
                $hasil_sync['id']=$data->id;
                $hasil_sync['name']=$data->name;
                $hasil_sync['category']=$kategori;
                $hasil_sync['message']=$message;
            }else{
                $hasil_sync['sync']=false;
                $hasil_sync['id']=$data->id;
                $hasil_sync['name']=$data->name;
                $hasil_sync['category']=$kategori;
                $hasil_sync['message']=$message;
            }
        }else{
            $hasil_sync['sync']=false;
            $hasil_sync['message']=$msg_error;
        }
        return $hasil_sync;
    }
    public function comboAjax($kategori,$parentcode,$nilai="") {
        
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $msg=array();
        $html="";
        switch($kategori){
            case "list_trucks":
                if($parentcode=='')
                {
                    $msg['kosong']=true;
                    $html="<option value=''>--Dump Trucks--</option>";
                }else{
                    $condition="ifnull(category,'')='HA' and partner_id=$parentcode";
                   // $sql    ="SELECT id,name,nomor from equipment where ifnull(category,'')='HA' and contractor_id=$contractor_id";
                    $listdata =Model::getOptionList("equipment eq 
                    inner join equipment_contractor ec on ec.equipment_id=eq.id
                    inner join partner p on p.id=ec.partner_id","eq.id","eq.name","eq.id asc",$condition); 
                    
					 $html= "<option value='' >--Pilih Dump Truck--</option>";
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