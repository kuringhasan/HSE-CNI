<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Damas Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class List_Notification_Model extends Model {
  
	public function __construct() {
		
	}
    public function getListUnreadNotifications($target_role="",$target_user="",$fotmat="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($target_role)=="" and  trim($target_user)==""){
			return array();
		}else{
		  
         
			$filter="(target_role='".$target_role."' or target_user='".$target_user."') and ifnull(readed,'')=''";
            $data_qry= $db->select("id,title,message,icon,link,target_role,target_user,created,readed,read_time","tbtnotifications")
            ->where($filter)->lim();
            
            $notifications=array();
            while($data = $db->fetchObject($data_qry))
            {
                
                $rec    	   = new stdClass;
                $rec->id        = $data->title;
                $rec->title          = $data->title;
                $rec->message        = $data->message;
                $rec->icon           = $data->icon;
                $rec->link           = $data->link;
                $rec->created        = $master->detailtanggal($data->created,2);
                $rec->read           = $data->readed;
                $rec->read_time        = $master->detailtanggal($data->read_time,2);
                
                
                if(trim($format)=="array"){
					$notifications[] = (array) $rec;
				}else{
					$notifications[]	= $rec;
				}		
                
                
            }
					
			return $notifications;
		
		}
	}
    public function insert($title,$message,$target_role="",$target_user="") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master = new Master_Ref_Model();
        $TglSkrg    =date("Y-m-d H:i:s");
        $msg        = array();
        $title_val	        =$master->scurevaluetable($title,"string");
        $message_val	    =$master->scurevaluetable($message,"string");
        $target_role_val    =$master->scurevaluetable($target_role,"string");
        $target_user_val	=$master->scurevaluetable($target_user,"string");
        $tgl_skrg_val	    =$master->scurevaluetable($TglSkrg,"string");
                
		$cols     ="title,message,target_role,target_user,created";
		$values   ="$title_val,$message_val,$target_role_val,$target_user_val,$tgl_skrg_val";
		$sqlin    ="INSERT INTO tbtnotifications ($cols) VALUES ($values);";
        

		$rsl=$db->query($sqlin);
		if(isset($rsl->error) and $rsl->error===true){
            $msg['success']=false;
            $msg['message']="Error, ".$rsl->query_last_message;
		}else{
            $last   =$db->query("SELECT LAST_INSERT_ID() as new_id;");
            $new    =$db->fetchArray($last);
            $msg['new_id']=$new['new_id'];
            $msg['success']=true;
            $msg['message']="Data sudah ditambahkan"; 
        }
        return $msg;
        
	}
    public function getCowByMember($member_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		if(trim($member_id)=="" or  $member_id == null){
			return array();
		}else{
		    $referensi	= $_SESSION["referensi"];
			$filter="anggota_id=".$member_id."";
            $data_qry= $db->select("c.id,c.name no_eartag,koloni_name,birthplace,birthdate,DATE_FORMAT(birthdate,'%d/%m/%Y') birthdate,
            datediff(current_date(),birthdate) as usia,current_date() as tgl_sekarang,posisi_eartag,metode_perolehan,
            c.barcode,photo_path,created_time,created_by,c.is_active,anggota_id","cow c")->where($filter)->lim();
            $data_sapi=array();
            while($data = $db->fetchObject($data_qry))
            {
                
                $rec    	   = new stdClass;
                $rec->NoEartag          = $data->no_eartag;
                $rec->Nama              = $data->koloni_name;
                $rec->PosisiEartag      = $data->posisi_eartag;
                $rec->CaraPerolehanNama	= $referensi['metode_perolehan'][$data->metode_perolehan];
                if(trim($format)=="array"){
					$data_sapi[] = (array) $rec;
				}else{
					$data_sapi[]	= $rec;
				}		
                
                
            }
					
			return $data_sapi;
		}
	}
	public function json($category="sapi",$query="") {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        $List   = array();
        $filter = "";
        switch($category){
            case "pejantan":
                if(trim($query)<>""){
            	   $filter="nama like '%".$query."%' or no_pejantan='".$query."' or asal='".$query."'";
                }
        	  
                $listdata= $db->select("id,no_pejantan,nama,asal,birthdate,DATE_FORMAT(birthdate,'%d/%m/%Y') TanggalLahir,
                beli_dari,id_dam,nama_dam,id_sire,nama_sire,
                asal_sire,id_mgs,nama_mgs,id_ggs,nama_ggs","pejantan")->where($filter)->get();
        		$i=0;
                if(!empty($listdata)){
            		while($data=current($listdata)){
            		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
            		    $List[$i]['ID']=$data->id;
            		    $List[$i]['NoPejantan']=$data->no_pejantan;
            		    $List[$i]['Nama']=$data->nama;
            		    $List[$i]['Lengkap']="<div class='label_typeahead'>[".$data->no_pejantan."] ".$data->nama."<br />Asal : ".$data->asal."</div>";
            		    $i++;
            		    next($listdata);
            		}
                }
               
          break;
          case "pemilik":
                if(trim($query)<>""){
            	   $filter="NAMA like '%".$query."%' or C_ANGGOTA='".$query."'";
                }
        	    $listdata= $db->select("ID_ANGGOTA,NAMA,C_ANGGOTA","anggota")->where($filter)->get();
        		$i=0;
                if(!empty($listdata)){
            		while($data=current($listdata)){
            		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
            		    $List[$i]['ID']=$data->ID_ANGGOTA;
            		    $List[$i]['IDAnggota']=$data->C_ANGGOTA;
            		    $List[$i]['Nama']=$data->NAMA;
            		    $List[$i]['Lengkap']="<div class='label_typeahead'>[".$data->C_ANGGOTA."] ".$data->NAMA."<br />Test aja</div>";
            		    $i++;
            		    next($listdata);
            		}
                }
                if(trim($query)<>""){
            	   $filter="name like '%".$query."%' or id='".$query."'";
                }
        	    $listdata2= $db->select("id,name","cow_ownership")->where($filter)->get();
                if(!empty($listdata2)){
        		while($data2=current($listdata2)){
        		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
        		    $List[$i]['ID']=$data2->id;
        		    //$List[$i]['IDAnggota']=$data->C_ANGGOTA;
        		    $List[$i]['name']=$data2->name;
        		    $List[$i]['Lengkap']="<div class='label_typeahead'>".$data2->name."<br />Test aja</div>";
        		    $i++;
        		    next($listdata2);
        		}
                }
          break;
      }
      return $List;
    } 

 
 
}
?>