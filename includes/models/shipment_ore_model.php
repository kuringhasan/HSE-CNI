<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Karyawan Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Shipment_Ore_Model extends Model {
	
	public function __construct($NIP = "") {
	
	}
   
   public function update_recap($transaction_id,$ritase,$quantity,$tanggal,$partner_id) {
		/** 
         * 1. Insert/Update recap
         * 2. Insert recap history history_recap 
         * 3. */
        global $dcistem;
    	$db   = $dcistem->getOption("framework/db");
        $master= new Master_Ref_Model();
        date_default_timezone_set("Asia/Jakarta");
        $TglSkrg		=date("Y-m-d H:i:s");
       	$tgl_skrg_val	=$master->scurevaluetable($TglSkrg,"string");
        $bulan          =substr($tanggal,1,7);
        $bulan_val	    =$master->scurevaluetable($bulan);
        $partner_id_val	=$master->scurevaluetable($partner_id,"number");    	
        $msg            = array();
        $cek=$db->select("id,contractor_id,month,ritase,quantity,cumulative_quantity","recap_monthly_transit_ore")->where("month='".$bulan."' and contractor_id=$partner_id")->get(0);
        if(!empty($cek)){
            //update
            
            $his_ritase =0;
            $his_qty    =0;
            $cek_history=$db->select("id,ritase,quantity","history_recap_detail")->where("transaction_id='".$transaction_id."' and recap_category='monthly'")->get(0);
            if(!empty($cek_history)){
                $his_ritase =$cek_history->ritase;
                $his_qty    =$cek_history->quantity;
            }
            $balance_start_ritase               = $cek->ritase-$his_ritase;
            $balance_start_quantity             = $cek->quantity-$his_qty;
            $balance_start_cumulative_quantity  = $cek->cumulative_quantity-$his_qty;
            
            $balance_end_ritase               = $balance_start_ritase+$ritase;
            $balance_end_quantity             = $balance_start_quantity+$quantity;
            $balance_end_cumulative_quantity  = $balance_start_cumulative_quantity+$quantity;
            
            $balance_end_ritase_val	                =$master->scurevaluetable($balance_end_ritase,"number");
            $balance_end_quantity_val	            =$master->scurevaluetable($balance_end_quantity,"number");
            $balance_end_cumulative_quantity_val	=$master->scurevaluetable($balance_end_cumulative_quantity,"number");
           
             
            $cols_and_vals="contractor_id=$partner_id_val,month=$bulan_val,ritase=$balance_end_ritase_val,
            quantity=$balance_end_quantity_val,cumulative_quantity=$balance_end_cumulative_quantity_val";
	
            $sqlin="UPDATE recap_monthly_transit_ore SET $cols_and_vals WHERE month='".$bulan."' and contractor_id=$partner_id";

			$rsl=$db->query($sqlin);
			if(isset($rsl->error) and $rsl->error===true){
		   	 		$msg['success']=false;
                	$msg['message']="Error, ".$rsl->query_last_message;
			}else{
                $msg['success']=true;
                $msg['message']="Perubahan data sudah disimpan"; 
               
            }
        }else{
            //insert
            // get comulative
            $tahun=substr($tanggal,1,4);
            $cek=$db->select("contractor_id,LEFT(month,4) tahun, sum(ritase) cum_ritase,sum(quantity) cum_quantity","recap_monthly_transit_ore")
            ->where("LEFT(month,4)='".$tahun."' and contractor_id=$partner_id GROUP BY contractor_id, LEFT(month,4)")->get(0);
            
            
            $balance_end_ritase               = $ritase;
            $balance_end_quantity             = $quantity;
            $balance_end_cumulative_quantity=$cek->cum_quantity+$quantity;
            
            $balance_end_ritase_val	                =$master->scurevaluetable($balance_end_ritase,"number");
            $balance_end_quantity_val	            =$master->scurevaluetable($balance_end_quantity,"number");
            $balance_end_cumulative_quantity_val	=$master->scurevaluetable($balance_end_cumulative_quantity,"number");
           
			$cols="contractor_id,month,ritase,quantity,cumulative_quantity,created";
			$values="$partner_id_val,$bulan_val,$balance_end_ritase_val,$balance_end_quantity_val,$balance_end_cumulative_quantity_val,$tgl_skrg_val";
			$sqlin="INSERT INTO recap_monthly_transit_ore ($cols) VALUES ($values);";
            

			$rsl=$db->query($sqlin);
			if(isset($rsl->error) and $rsl->error===true){
		   	 		$msg['success']=false;
                	$msg['message']="Error, ".$rsl->query_last_message;
			}else{
                $msg['success']=true;
                $msg['message']="Data sudah ditambahkan"; 
               
            }
        }
        return $msg;
   }     
 
    public function delete_recap($kode_lama) {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        $msg=array();
        if(trim($kode_lama)=="" or  $kode_lama == null){
            $msg['success']=false;
           	$msg['message']="Error, Kode Syarat tidak boleh kosong";
                
        }else{
            $sqlin="DELETE FROM tbrsyarat  WHERE SKode='".$kode_lama."';";
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

    public function getShipment($id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($id)=="" or  $id == null){
			return array();
		}else{
		    $referensi	= $_SESSION["referensi"];
			$filter="sh.id=".$id."";
            $list_qry=$db->select("sh.id,sh.transaction_id,sh.urutan_pengiriman,sh.barge_id,bg.name barge_name,sh.jetty_id,jt.name jetty_name,
            gate_id,gt.name gate_name,pre_stowage_plan,sh.berth_time,DATE_FORMAT(sh.berth_time,'%d/%m/%Y %H:%i') berth_time2, 
            sh.commenced_time,DATE_FORMAT(sh.commenced_time,'%d/%m/%Y %H:%i') commenced_time2, completed_time,
            DATE_FORMAT(sh.completed_time,'%d/%m/%Y %H:%i') completed_time2,sh.total_ritase,
            sh.total_quantity,sh.final_draugh_survey,sh.verification,sh.verification_date,sh.verifikator,sh.lastupdate,
            sh.state,sync,recap_monthly_id,sh.step,sh.received_time,sh.created_time,sh.final_time,sh.sent_time,
            lay_time_plan,lay_time_real,jumlah_truck","shipment sh
            left join barges bg on bg.id=sh.barge_id
            left join jetty jt on jt.id=sh.jetty_id
            left join gates gt on gt.id=sh.gate_id")
    		->where($filter)->lim(0);//->orderBy($order)
           
                $data = $db->fetchObject($list_qry);
                $hasil=array();
                if(!empty($data)){
                
                    $rec    	   = new stdClass;
                    
                  
                    $rec->detail	            = $this->getShipmentDetail( $data->id,$format);
                     $rec->list_gangguan	            = $this->getShipmentGangguan( $data->id,$format);
                    $rec->files	                = $this->getShipmentFiles( $data->id,$format);
                   
                    if(trim($format)=="array"){
    					$result = array_merge((array) $data, (array) $rec);
    					return $result;
    				}else{
    					$result	= (object) array_merge((array) $data, (array) $rec);
    					return $result;
    				}	
                    
                    
                }
					
			return $hasil;
		}
	}
    public function getShipmentByShift($shipment_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($shipment_id)=="" or  $shipment_id == null){
			return array();
		}else{
		    $referensi	= $_SESSION["referensi"];
			$filter="sh.id=".$shipment_id."";
            $list_qry=$db->select("sh.id,sh.transaction_id,sh.urutan_pengiriman,sh.barge_id,bg.name barge_name,sh.jetty_id,jt.name jetty_name,
            gate_id,gt.name gate_name,pre_stowage_plan,sh.berth_time,DATE_FORMAT(sh.berth_time,'%d/%m/%Y %H:%i') berth_time2, 
            sh.commenced_time,DATE_FORMAT(sh.commenced_time,'%d/%m/%Y %H:%i') commenced_time2, completed_time,
            DATE_FORMAT(sh.completed_time,'%d/%m/%Y %H:%i') completed_time2,sh.total_ritase,
            sh.total_quantity,sh.final_draugh_survey,sh.verification,sh.verification_date,sh.verifikator,sh.lastupdate,
            sh.state,sync,recap_monthly_id,sh.step,sh.received_time,sh.created_time,sh.final_time,sh.sent_time,
            lay_time_plan,lay_time_real,jumlah_truck","shipment sh
            left join barges bg on bg.id=sh.barge_id
            left join jetty jt on jt.id=sh.jetty_id
            left join gates gt on gt.id=sh.gate_id")
    		->where($filter)->lim(0);//->orderBy($order)
           
                $data = $db->fetchObject($list_qry);
                $hasil=array();
                if(!empty($data)){
                
                    $rec    	   = new stdClass;
                    
                    $filter2="shipment_id=".$shipment_id."";
                    $list_qry2=$db->select("shd.id,detail_transaction_id,shipment_id,shd.dome_id,d.name dome_name,dome_distance,shd.contractor_id,p.name,p.alias,
                    shift,start_time,DATE_FORMAT(start_time,'%d/%m/%Y %H:%i') start_time2,
                    DATE_FORMAT(start_time,'%d%m%Y') start_tgl,
                    DATE_FORMAT(start_time,'%Y-%m-%d') tanggal,end_time,
                    DATE_FORMAT(end_time,'%d/%m/%Y %H:%i') end_time2,jumlah_jam,
                    ritase,quantity,intermediate_draugh_survey,created_time,lastupdate","shipment_detail shd
                    inner join partner p on p.id=shd.contractor_id and ifnull(p.is_contractor,0)=1
                    left join domes d on d.id=shd.dome_id")->where($filter2)->orderBy("shift asc,start_time asc")->lim();
                   
                    $listdata=array();
                    $domes=array();
                    $ritase_loaded="";
                    while($deta = $db->fetchObject($list_qry2))
                    {
                       $ritase_loaded=$deta->ritase;
                       $listdata[$deta->start_tgl]['tanggal']= $deta->tanggal;
                       $listdata[$deta->start_tgl]['detail_tanggal']= $master->detailtanggal($deta->tanggal,2);
                       $listdata[$deta->start_tgl]['data_shift'][$deta->shift]['shift']= $deta->shift;
                       $listdata[$deta->start_tgl]['data_shift'][$deta->shift]['tanggal']= $deta->tanggal;
                       $listdata[$deta->start_tgl]['data_shift'][$deta->shift]['shift']= $deta->shift;
                       $listdata[$deta->start_tgl]['data_shift'][$deta->shift]['ritase_loaded']=$ritase_loaded;
                       $listdata[$deta->start_tgl]['data_shift'][$deta->shift]['ritase']= $listdata[$deta->start_tgl]['data_shift'][$deta->shift]['ritase'] + $deta->ritase;
                       $listdata[$deta->start_tgl]['data_shift'][$deta->shift]['jumlah_jam']= $listdata[$deta->start_tgl]['data_shift'][$deta->shift]['jumlah_jam'] + $deta->jumlah_jam;
                       $listdata[$deta->start_tgl]['data_shift'][$deta->shift]['domes'][$deta->dome_id]['dome_id']= $deta->dome_id;
                       $listdata[$deta->start_tgl]['data_shift'][$deta->shift]['domes'][$deta->dome_id]['dome_name']= $deta->dome_name;
                        $listdata[$deta->start_tgl]['data_shift'][$deta->shift]['domes'][$deta->dome_id]['dome_distance']= $deta->dome_distance;
                       $listdata[$deta->start_tgl]['data_shift'][$deta->shift]['domes'][$deta->dome_id]['contractor_id']= $deta->contractor_id;
                       $listdata[$deta->start_tgl]['data_shift'][$deta->shift]['domes'][$deta->dome_id]['contractor_name']= $deta->name;
                    }
                   
                    
                    
                    $filter3="shipment_id=".$shipment_id."";
                    $list_qry3=$db->select("sg.id,shipment_id,gangguan_id,rut.name,shift,start_time,DATE_FORMAT(start_time,'%d/%m/%Y %H:%i') start_time2,
                    DATE_FORMAT(start_time,'%d%m%Y') start_tgl,DATE_FORMAT(start_time,'%Y-%m-%d') tanggal,end_time,
                    DATE_FORMAT(end_time,'%d/%m/%Y %H:%i') end_time2,jumlah_jam,sg.description,
                    lastupdate,created_time","shipment_gangguan sg
                    inner join ref_uneffective_time rut on rut.id=sg.gangguan_id"
                    )->where($filter3)->orderBy("shift asc,start_time asc")->lim();
                   
                    //$list_gangguan=array();
                    //$listdata[$deta3->start_tgl]['data_shift'][$deta3->shift]['uneffective_time']=0;
                    while($deta3 = $db->fetchObject($list_qry3))
                    {
                        $listdata[$deta3->start_tgl]['data_shift'][$deta3->shift]['uneffective_time']= $listdata[$deta3->start_tgl]['data_shift'][$deta3->shift]['uneffective_time']+$deta3->jumlah_jam;
                        $listdata[$deta3->start_tgl]['data_shift'][$deta3->shift]['list_gangguan'][$deta3->gangguan_id]['gangguan_id']= $deta3->gangguan_id;
                        $listdata[$deta3->start_tgl]['data_shift'][$deta3->shift]['list_gangguan'][$deta3->gangguan_id]['gangguan']= $deta3->name;
                        $listdata[$deta3->start_tgl]['data_shift'][$deta3->shift]['list_gangguan'][$deta3->gangguan_id]['jumlah_jam']= $listdata[$deta3->start_tgl]['data_shift'][$deta3->shift]['list_gangguan'][$deta3->gangguan_id]['jumlah_jam']+$deta3->jumlah_jam;
                       
                        
                        
                    }
                     $rec->detail=$listdata;	
                    //$rec->detail	            = $this->getShipmentDetail( $data->id,$format);
                    //$rec->files	                = $this->getShipmentFiles( $data->id,$format);
                   
                    if(trim($format)=="array"){
    					$result = array_merge((array) $data, (array) $rec);
    					return $result;
    				}else{
    					$result	= (object) array_merge((array) $data, (array) $rec);
    					return $result;
    				}	
                    
                    
                }
					
			return $hasil;
		}
	}
    public function getShipmentDetail($shipment_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($shipment_id)=="" or  $shipment_id == null){
			return array();
		}else{
		   // $referensi	= $_SESSION["referensi"];
			$filter="shipment_id=".$shipment_id."";
            $list_qry=$db->select("shd.id,detail_transaction_id,shipment_id,shd.dome_id,d.name dome_name,dome_distance,shd.contractor_id,p.name,p.alias,
            shift,start_time,DATE_FORMAT(start_time,'%d/%m/%Y %H:%i') start_time2,end_time,
            DATE_FORMAT(end_time,'%d/%m/%Y %H:%i') end_time2,jumlah_jam,
            ritase,quantity,intermediate_draugh_survey,created_time,lastupdate","shipment_detail shd
            inner join partner p on p.id=shd.contractor_id and ifnull(p.is_contractor,0)=1
            left join domes d on d.id=shd.dome_id")->where($filter)->orderBy("contractor_id asc,start_time asc")->lim();
           
            $listdata=array();
            while($data = $db->fetchObject($list_qry))
            {
                
                $rec    	   = new stdClass;
                $fil=$this->getShipmentDetailFile($data->id);
                $rec->file         = $fil;
                //$rec->quantity         = number_format($data->quantity,2,",",".");
                //$rec->intermediate_draugh_survey   = number_format($data->intermediate_draugh_survey,2,",",".");
                
                 if(trim($format)=="array"){
					$result = array_merge((array) $data, (array) $rec);
                    $listdata[]=$result;
					//return $result;
				}else{
					$result	= (object) array_merge((array) $data, (array) $rec);
					$listdata[]=$result;
				}	
                
               
                
                
            }
					
			return $listdata;
		}
	}
     public function getShipmentDetailByID($detail_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($detail_id)=="" or  $detail_id == null){
			return array();
		}else{
		   // $referensi	= $_SESSION["referensi"];
			$filter="shd.id=".$detail_id."";
            $list_qry=$db->select("shd.id,detail_transaction_id,shipment_id,shd.dome_id,d.name dome_name,dome_distance,shd.contractor_id,p.name,p.alias,
            shift,start_time,DATE_FORMAT(start_time,'%d/%m/%Y %H:%i') start_time2,end_time,
            DATE_FORMAT(end_time,'%d/%m/%Y %H:%i') end_time2,ritase,quantity,intermediate_draugh_survey,created_time,lastupdate","shipment_detail shd
            inner join partner p on p.id=shd.contractor_id and ifnull(p.is_contractor,0)=1
            left join domes d on d.id=shd.dome_id")->where($filter)->lim();
           return $db->fetchObject($list_qry);
					
		}
	}
     public function generateUrutanPengiriman($barge_id,$format="array") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($barge_id)=="" or  $barge_id == null){
			return array("urutan_terakhir"=>0,"urutan_baru"=>1);
		}else{
		   // $referensi	= $_SESSION["referensi"];
			$filter="barge_id=".$barge_id."";
            $list_qry=$db->select("ifnull(max(CAST(right(urutan_pengiriman,2)  AS SIGNED)),0) as max_id","shipment")->where($filter)->lim();
           $hasil= $db->fetchObject($list_qry);
           $last_urutan=$hasil->max_id;
           $urutan_baru=(int)$hasil->max_id+1;
           return  array("urutan_terakhir"=>$last_urutan,"urutan_baru"=>$urutan_baru);
					
		}
	}
     public function getShipmentGangguan($shipment_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($shipment_id)=="" or  $shipment_id == null){
			return array();
		}else{
		   // $referensi	= $_SESSION["referensi"];
			$filter="shipment_id=".$shipment_id."";
            $list_qry=$db->select("sg.id,shipment_id,gangguan_id,rut.name,shift,start_time,DATE_FORMAT(start_time,'%d/%m/%Y %H:%i') start_time2,end_time,
            DATE_FORMAT(end_time,'%d/%m/%Y %H:%i') end_time2,jumlah_jam,sg.description,
            lastupdate,created_time","shipment_gangguan sg
            inner join ref_uneffective_time rut on rut.id=sg.gangguan_id")->where($filter)->orderBy("start_time asc")->lim();
           
            $listdata=array();
            while($data = $db->fetchObject($list_qry))
            {
                
                $rec    	   = new stdClass;
                
                 if(trim($format)=="array"){
					$result = !empty($rec)? array_merge((array) $data, (array) $rec):(array) $data;
                    $listdata[]=$result;
					//return $result;
				}else{
					$result	= !empty($rec)?(object) array_merge((array) $data, (array) $rec):(array) $data;
					$listdata[]=$result;
				}	
                
               
                
                
            }
					
			return $listdata;
		}
	}
    public function getShipmentGangguanByID($gangguan_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master = new Master_Ref_Model();
		if(trim($gangguan_id)=="" or  $gangguan_id == null){
			return array();
		}else{
		   // $referensi	= $_SESSION["referensi"];
			$filter="sg.id=".$gangguan_id."";
            $list_qry=$db->select("sg.id,shipment_id,gangguan_id,rut.name,shift,start_time,DATE_FORMAT(start_time,'%d/%m/%Y %H:%i') start_time2,end_time,
            DATE_FORMAT(end_time,'%d/%m/%Y %H:%i') end_time2,jumlah_jam,sg.description,
            lastupdate,created_time","shipment_gangguan sg
            inner join ref_uneffective_time rut on rut.id=sg.gangguan_id")->where($filter)->lim();
          
					
			return $db->fetchObject($list_qry);
		}
	}
    public function getShipmentFiles($shipment_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		if(trim($shipment_id)=="" or  $shipment_id == null){
			return array();
		}else{
		   // $referensi	= $_SESSION["referensi"];
			$filter="shipment_id=".$shipment_id." and category='shipment'";
            $list_qry=$db->select("id,category,shipment_id,path,file_name,nama_berkas","files")
            ->where($filter)->orderBy("file_name asc")->lim();
           
            $listdata=array();
            while($data = $db->fetchObject($list_qry))
            {
                
                $rec    	   = new stdClass;
                $rec->id                = $data->id;
                $rec->shipment_id    = $data->shipment_id;
                $rec->category  = $data->category;
                $rec->file_name           = $data->file_name;
                $rec->nama_berkas           = $data->nama_berkas;
               
                $file_foto      = $data->file_name;
                if(trim($data->file_name)==""){
                    $file_foto ="no-image.png";
                    $url_foto=url::base()."files/shipment/".$file_foto;
                }else{
                    $url_foto=url::base()."files/shipment/".$file_foto;
                }
               
				$rec->url_file=$url_foto;
               
                if(trim($format)=="array"){
					$listdata[] = (array) $rec;
				}else{
					$listdata[]	= $rec;
				}		
                
                
            }
					
			return $listdata;
		}
	}
    
    public function getShipmentDetailFiles($shipment_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		if(trim($shipment_id)=="" or  $shipment_id == null){
			return array();
		}else{
		   // $referensi	= $_SESSION["referensi"];
			$filter="shipment_id=".$shipment_id." and category='shipment_detail'";
            $list_qry=$db->select("id,category,shipment_id,path,file_name,nama_berkas","files")
            ->where($filter)->orderBy("file_name asc")->lim();
           
            $listdata=array();
            while($data = $db->fetchObject($list_qry))
            {
                
                $rec    	   = new stdClass;
                $rec->id                = $data->id;
                $rec->shipment_id    = $data->shipment_id;
                $rec->shipment_detail_id    = $data->shipment_detail_id;
                $rec->category  = $data->category;
                $rec->file_name           = $data->file_name;
                $rec->nama_berkas           = $data->nama_berkas;
               
                $file_foto      = $data->file_name;
                if(trim($data->file_name)==""){
                    $file_foto ="no-image.png";
                    $url_foto=url::base()."files/shipment/".$file_foto;
                }else{
                    $url_foto=url::base()."files/shipment/".$file_foto;
                }
               
				$rec->url_file=$url_foto;
               
                if(trim($format)=="array"){
					$listdata[] = (array) $rec;
				}else{
					$listdata[]	= $rec;
				}		
                
                
            }
					
			return $listdata;
		}
	}
    public function getShipmentDetailFile($detail_id,$format="object") {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		if(trim($detail_id)=="" or  $detail_id == null){
			return array();
		}else{
		   // $referensi	= $_SESSION["referensi"];
			$filter="shipment_detail_id=".$detail_id." and category='shipment_detail'";
            $list_qry=$db->select("id,category,shipment_id,shipment_detail_id,path,file_name,nama_berkas","files")
            ->where($filter)->lim(0);
           
            $data = $db->fetchObject($list_qry);
            if (!empty($data)){
                
                $rec    	   = new stdClass;
                
                $file_foto      = $data->file_name;
                if(trim($data->file_name)==""){
                    $file_foto ="no-image.png";
                    $url_foto=url::base()."files/shipment/".$file_foto;
                }else{
                    $url_foto=url::base()."files/shipment/".$file_foto;
                }
               
				$rec->url_file=$url_foto;
               
                if(trim($format)=="array"){
					$data = array_merge((array) $data, (array) $rec);
                  
				}else{
					$data	= (object) array_merge((array) $data, (array) $rec);
					
				}	
                
                
            }
					
			return $data;
		}
	}
    
     public function json_gangguan($query="",$array_value=array()) {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        
    	$filter="(name like '%".$query."%' or name like '%".$query."%') ";
        /*if(trim($array_value['web_id'])<>""){
            $filter=$filter." and jt.classification_id='".$array_value['classification_id']."'";
        }  */  
	    $list_qry= $db->select("id,name","ref_uneffective_time")->where($filter)->lim();
		$i=0;
		while($data=$db->fetchObject($list_qry)){
		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
          
		    $List[$i]['ID']=$data->id;
		    $List[$i]['Nama']=$data->name;
            $List[$i]['Name']=$data->name;
		    $i++;
		}
        return $List;
    }
	
}
?>