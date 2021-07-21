<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Karyawan Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Rehandling_Ore_Model extends Model {
	
	public function __construct($NIP = "") {
	
	}
   

    public function getRehandlingOre($id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($id)=="" or  $id == null){
			return array();
		}else{
		    $referensi	= $_SESSION["referensi"];
			$filter="dro.id=".$id."";
            $list_qry=$db->select("dro.id, dro.barge_id,transaction_id,contractor_id,p.name,p.alias,tanggal,DATE_FORMAT(tanggal,'%d/%m/%Y')  tgl,
            entry_time,shift,state,operator,sent_time,received_time,total_ritase,total_quantity","daily_rehandling_ore dro
            inner join partner p on p.id=dro.contractor_id")
    		->where($filter)->lim(0);//->orderBy($order)
           
                $data = $db->fetchObject($list_qry);
                $hasil=array();
                if(!empty($data)){
                
                    $rec    	   = new stdClass;
                    $rec->id            = $data->id;
                    $rec->transaction_id       = $data->transaction_id;
                    $rec->contractor_id        = $data->contractor_id;
                    $rec->contractor_name      = $data->name;
                    $rec->contractor_alias     = $data->alias;
                    $rec->tanggal               = $master->detailtanggal($data->tanggal,2);
                    $rec->shift                 = $data->shift;
                    $rec->entry_time            = $data->entry_time;
                    $rec->shift                 = $data->shift;
                    $rec->barge_id              = $data->barge_id;
                    $rec->sent_time             = $data->sent_time;
                    $rec->received_time         = $data->received_time;
                  
                    $rec->total_ritase          = $data->total_ritase;
                    $rec->total_quantity        = $data->total_quantity;
                    $rec->detail	            = $this->getRehandlingOreDetail( $data->id,$format);
                    
                    if(trim($format)=="array"){
    					$hasil = (array) $rec;
    				}else{
    					$hasil	= $rec;
    				}
                }
					
			return $hasil;
		}
	}
    public function getRehandlingOreDetail($trx_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($trx_id)=="" or  $trx_id == null){
			return array();
		}else{
		   // $referensi	= $_SESSION["referensi"];
			$filter="rehandling_ore_id=".$trx_id."";
            $list_qry=$db->select("tod.id,equipment_id,eq.nomor truck_nomor,ritase,rehandling_ore_id,quantity, dome_asal,ifnull(d.name,'-') as dome_name,  pt.alias ptalias",
            "daily_rehandling_ore_detail tod
            inner join equipment eq on eq.id=tod.equipment_id
            left join partner pt ON eq.partner_id = pt.id
            left join domes d on d.id=tod.dome_asal")->where($filter)->orderBy("equipment_id asc")->lim();
           
            $listdata=array();
            while($data = $db->fetchObject($list_qry))
            {
                
                $rec    	   = new stdClass;
                $rec->id                = $data->id;
                $rec->transit_ore_id    = $data->transit_ore_id;
                $rec->truck_id          = $data->equipment_id;
                $rec->equipment_name    = $data->truck_nomor."(".$data->ptalias.")";
                $rec->truck_nomor       = $data->truck_nomor;
                $rec->ritase            = $data->ritase;
                $rec->quantity            = $data->quantity;
                
                $rec->dome_name             = $data->dome_name;
                $rec->dome_id             = $data->dome_asal;

                if(trim($format)=="array"){
					$listdata[] = (array) $rec;
				}else{
					$listdata[]	= $rec;
				}		
                
                
            }
					
			return $listdata;
		}
	}
    public function getRehandlingOreDetailByID($id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($id)=="" or  $id == null){
			return array();
		}else{
		   // $referensi	= $_SESSION["referensi"];
			// $referensi	= $_SESSION["referensi"];
			$filter="dtod.id=".$id."";
            $data=$db->select("dtod.id,dtod.rehandling_ore_id,dto.transaction_id,
                dto.contractor_id,p.name contractor_name,p.alias contractor_alias,dto.barge_id,bg.name barge_name,
                DATE_FORMAT(dto.tanggal,'%d/%m/%Y')  tgl,dto.tanggal,dto.entry_time,
                dto.shift, ifnull(dtod.state,'') state,dto.sent_time,dto.received_time, dtod.equipment_id,eq.nomor no_dump_truck,dtod.ritase,
                dtod.quantity,dtod.dome_asal,dm.name dome_asal_name,dl.id location_id,dl.location_name,dl.eto_efo,
                verified_time","daily_rehandling_ore_detail dtod
                inner join daily_rehandling_ore dto on dto.id=dtod.rehandling_ore_id                   
                inner join partner p on p.id=dto.contractor_id
                inner join barges bg on bg.id=dto.barge_id
                inner join equipment eq on eq.id=dtod.equipment_id
                left join domes dm on dm.id=dtod.dome_asal
                left join dome_locations dl on dl.id=dm.location_id")->where($filter)->get(0);
           
					
			return $data;
		}
	}
	
}
?>