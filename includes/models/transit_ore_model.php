<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Karyawan Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Transit_Ore_Model extends Model {
	
	public function __construct($NIP = "") {
	
	}
   

    public function getTransitOre($id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($id)=="" or  $id == null){
			return array();
		}else{
		    $referensi	= $_SESSION["referensi"];
			$filter="dto.id=".$id."";
            $list_qry=$db->select("dto.id,transaction_id,dto.contractor_id,p.name,p.alias,lokasi_pit_id,pit.block_name pit_name,tanggal,DATE_FORMAT(tanggal,'%d/%m/%Y')  tgl,
            entry_time,shift,dto.state,operator,sent_time,received_time,total_ritase,total_quantity","daily_transit_ore dto
            inner join partner p on p.id=dto.contractor_id
            inner join lokasi_pit pit on pit.id=dto.lokasi_pit_id")
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
                    $rec->tanggal              = $master->detailtanggal($data->tanggal,2);
                    $rec->shift                 = $data->shift;
                    $rec->entry_time            = $data->entry_time;
                    $rec->shift                 = $data->shift;
                    $rec->sent_time             = $data->sent_time;
                    $rec->received_time         = $data->received_time;
                    $rec->pit_id                = $data->lokasi_pit_id;
                    $rec->pit_name              = $data->pit_name;
                    $rec->total_ritase          = $data->total_ritase;
                    $rec->total_quantity        = $data->total_quantity;
                    $rec->detail	            = $this->getTransitOreDetail( $data->id,$format);
                    
                    if(trim($format)=="array"){
    					$hasil = (array) $rec;
    				}else{
    					$hasil	= $rec;
    				}
                }
					
			return $hasil;
		}
	}
    public function getTransitOreDetail($trx_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($trx_id)=="" or  $trx_id == null){
			return array();
		}else{
		   // $referensi	= $_SESSION["referensi"];
			$filter="transit_ore_id=".$trx_id."";
            $list_qry=$db->select("tod.id,equipment_id, pt.alias ptalias, eq.nomor truck_nomor,ritase,transit_ore_id,quantity,tujuan_pengangkutan,
            dome_id,barge_id,ifnull(d.name,'-') as dome_name,ifnull(b.name,'-') as barge_name","daily_transit_ore_detail tod
            inner join equipment eq on eq.id=tod.equipment_id left join partner pt ON eq.partner_id = pt.id
            left join domes d on d.id=tod.dome_id left join barges b on b.id=tod.barge_id")->where($filter)->orderBy("equipment_id asc")->lim();
           
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
                $rec->quantity              = $data->quantity;
                
                $rec->tujuan_pengangkutan   = $data->tujuan_pengangkutan;//=="BRG"?"Barge":"ETO/EFO";
                $rec->dome_name             = $data->dome_name;
                $rec->barge_name             = $data->barge_name;
                $rec->dome_id             = $data->dome_id;
                $rec->barge_id             = $data->barge_id;
                
                if(trim($format)=="array"){
					$listdata[] = (array) $rec;
				}else{
					$listdata[]	= $rec;
				}		
                
                
            }
				//	print_r($listdata);
			return $listdata;
		}
	}
    public function getTransitOreDetailByID($id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($id)=="" or  $id == null){
			return array();
		}else{
		   // $referensi	= $_SESSION["referensi"];
			$filter="dtod.id=".$id."";
            $data=$db->select("dtod.id,dtod.transit_ore_id,dto.transaction_id,
                dto.contractor_id,p.name contractor_name,p.alias contractor_alias,dto.lokasi_pit_id,pit.block_name pit_name,
                DATE_FORMAT(dto.tanggal,'%d/%m/%Y')  tgl,dto.tanggal,dto.entry_time,
                dto.shift, ifnull(dto.state,'draft') state,dto.sent_time,dto.received_time, dtod.equipment_id,eq.nomor no_dump_truck,dtod.ritase,
                dtod.quantity,dtod.tujuan_pengangkutan,dm.location_id,dl.location_name,dtod.dome_id,dm.name dome_name,
                dtod.barge_id,bg.name barge_name","daily_transit_ore_detail dtod
                inner join daily_transit_ore dto on dto.id=dtod.transit_ore_id
                inner join equipment eq on eq.id=dtod.equipment_id
                inner join partner p on p.id=dto.contractor_id
                inner join lokasi_pit pit on pit.id=dto.lokasi_pit_id
                left join domes dm on dm.id=dtod.dome_id
                left join dome_locations dl on dl.id=dm.location_id
                left join barges bg on bg.id=dtod.barge_id")->where($filter)->get(0);
           
					
			return $data;
		}
	}
	
}
?>