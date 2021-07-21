<?php
/**
 * @package Rekap Data
 * @subpackage Rekap Data Pendaftaran
 *
 * @author Hasan <kuring.hasan@gmail.com>
 */

defined("PANDORA") OR die("No direct access allowed.");

class Recap_Production_Model extends Model {
  public function __construct() {
        global $dcistem;
       
	   $this->UnitID=$dcistem->getOption("system/web/unit_id");
       
  }
    
  public function updateMonthlyRecapTransitOre($transaction_id,$ritase,$quantity,$tanggal,$partner_id)
  {
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
  
  public function generateMonthlyRecapShipment($tahun)
  {
        global $dcistem;
    	$db   = $dcistem->getOption("framework/db");
        $master= new Master_Ref_Model();
        date_default_timezone_set("Asia/Jakarta");
        $TglSkrg		=date("Y-m-d H:i:s");
       	$tgl_skrg_val	=$master->scurevaluetable($TglSkrg,"string");
        $msg            = array();
        $list_qry=$db->select("barge_id,DATE_FORMAT(completed_time,'%Y-%m') bulan,completed_time,sum(total_ritase) total_ritase,
            sum(total_quantity) total_quantity,sum(total_survey_quantity) total_survey_quantity","shipment")
            ->where("YEAR(completed_time)='".$tahun."' and state='done'
            GROUP BY barge_id, DATE_FORMAT(completed_time,'%Y-%m')")->orderby("completed_time ASC,barge_id asc")->lim();//
        while($data = $db->fetchObject($list_qry))
        {
            //echo "<pre>";print_r($data);echo "</pre>";
            //update
            $total_ritase_val	        =$master->scurevaluetable($data->total_ritase,"number");
            $total_quantity_val	        =$master->scurevaluetable($data->total_quantity,"number");
            $total_survey_quantity_val  =$master->scurevaluetable($data->total_survey_quantity,"number");
            $cek_recap=$db->select("id,month","recap_monthly_shipment")
            ->where("barge_id=".$data->barge_id." and month='".$data->bulan."'")->get(0);
            $sqlin  = "";
            if(empty($cek_recap)){
                $cols="month,barge_id,ritase,quantity,total_draught_survey";
    			$values="'".$data->bulan."',".$data->barge_id.",$total_ritase_val,$total_quantity_val,$total_survey_quantity_val";
    			$sqlin="INSERT INTO recap_monthly_shipment ($cols) VALUES ($values);";                
            }else{
                $cols_and_vals="ritase=$total_ritase_val,quantity=$total_quantity_val,total_draught_survey=$total_survey_quantity_val";
                $sqlin="UPDATE recap_monthly_shipment SET $cols_and_vals WHERE month='".$data->bulan."' and barge_id=".$data->barge_id."";
            }
           
			$rsl=$db->query($sqlin);
			if(isset($rsl->error) and $rsl->error===true){
		   	 		$msg['success']=false;
                	$msg['message']="Error, ".$rsl->query_last_message;
			}else{
                $msg['success']=true;
                $msg['message']="Perubahan data sudah disimpan"; 
               
            }
        }
        
  }  
  public function getCommulativeRecapExpitOreStockpiling($tahun) {
    global $dcistem;
	$db   = $dcistem->getOption("framework/db");
	$modelsortir=new Adm_Sortir_Model();
    $master = new Master_Ref_Model();
    //mktime
    
    $dt=mktime(0,0,0,$end_month,1,$tahun);
  
    $label=array();
    
    
    //echo "<pre>";print_r($label);echo "</pre>";
     //echo "<pre>";print_r($label);echo "</pre>";
    $filter="left(month,4)>=$tahun and tujuan_pengangkutan='ETO'";
    //$sqlr= "SELECT partner_id,name,alias,rgb_color,sum(ritase) jml_ritase,sum(quantity) jml_quantity FROM recap_monthly_transit_ore rmt
   // inner join partner p on p.id=rmt.contractor_id  WHERE left(month,4)>=$tahun  GROUP BY partner_id";
    $list_qry=$db->select("SQL_CALC_FOUND_ROWS partner_id,name,alias,rgb_color,sum(ritase) jml_ritase,sum(quantity) jml_quantity","recap_monthly_transit_ore rmt
        inner join partner p on p.id=rmt.partner_id")->where($filter ." GROUP BY partner_id ")->orderby("partner_id asc")->lim();//
   
    $list_data=array();
    $total_qty=0;
    while($data = $db->fetchObject($list_qry)){
        //echo "<pre>";print_r($data);echo "</pre>";
    //if(!empty($data)){
        $list_data[$data->partner_id]['partner_id']=$data->partner_id;
        $list_data[$data->partner_id]['partner_name']=$data->name;
        $list_data[$data->partner_id]['alias']=$data->alias;
        $list_data[$data->partner_id]['color']=$data->rgb_color;
        $list_data[$data->partner_id]['jml_ritase']=$data->jml_ritase;
        $list_data[$data->partner_id]['jml_quantity']= $data->jml_quantity;
        $total_qty=$total_qty+$data->jml_quantity;
        
        
    }
    $hasil=array("data"=>$list_data,"total_quantity"=>$total_qty);
   //echo "<pre>";print_r($hasil);echo "</pre>";exit;
    return $hasil;
}
public function getCommulativeRecapExpitOreCrossMining($tahun) {
    global $dcistem;
	$db   = $dcistem->getOption("framework/db");
	$modelsortir=new Adm_Sortir_Model();
    $master = new Master_Ref_Model();
    //mktime
    $dt=mktime(0,0,0,$end_month,1,$tahun);
  
    $label=array();
    
    
    //echo "<pre>";print_r($label);echo "</pre>";
    $filter="left(month,4)>=$tahun and tujuan_pengangkutan='BRG'";
    //$sqlr= "SELECT partner_id,name,alias,rgb_color,sum(ritase) jml_ritase,sum(quantity) jml_quantity FROM recap_monthly_transit_ore rmt
   // inner join partner p on p.id=rmt.contractor_id  WHERE left(month,4)>=$tahun  GROUP BY partner_id";
    $list_qry=$db->select("SQL_CALC_FOUND_ROWS partner_id,name,alias,rgb_color,sum(ritase) jml_ritase,sum(quantity) jml_quantity","recap_monthly_transit_ore rmt
        inner join partner p on p.id=rmt.partner_id")->where($filter ." GROUP BY partner_id ")->orderby("partner_id asc")->lim();//
   
    $list_data=array();
    $total_qty=0;
    while($data = $db->fetchObject($list_qry)){
        //echo "<pre>";print_r($data);echo "</pre>";
    //if(!empty($data)){
        $list_data[$data->partner_id]['partner_id']=$data->partner_id;
        $list_data[$data->partner_id]['partner_name']=$data->name;
        $list_data[$data->partner_id]['alias']=$data->alias;
        $list_data[$data->partner_id]['color']=$data->rgb_color;
        $list_data[$data->partner_id]['jml_ritase']=$data->jml_ritase;
        $list_data[$data->partner_id]['jml_quantity']=$data->jml_quantity;
        $total_qty=$total_qty+$data->jml_quantity;
        
        
    }
    $hasil=array("data"=>$list_data,"total_quantity"=>$total_qty);
   //echo "<pre>";print_r($hasil);echo "</pre>";exit;
    return $hasil;
}
public function getCommulativeRecapRehandling($tahun) {
    global $dcistem;
	$db   = $dcistem->getOption("framework/db");
	$modelsortir=new Adm_Sortir_Model();
    $master = new Master_Ref_Model();
    //mktime
    $dt=mktime(0,0,0,$end_month,1,$tahun);
  
    $label=array();
    
    
    //echo "<pre>";print_r($label);echo "</pre>";
    $filter="left(month,4)>=$tahun";
    //$sqlr= "SELECT partner_id,name,alias,rgb_color,sum(ritase) jml_ritase,sum(quantity) jml_quantity FROM recap_monthly_transit_ore rmt
   // inner join partner p on p.id=rmt.contractor_id  WHERE left(month,4)>=$tahun  GROUP BY partner_id";
    $list_qry=$db->select("SQL_CALC_FOUND_ROWS partner_id,name,alias,rgb_color,sum(ritase) jml_ritase,sum(quantity) jml_quantity","recap_monthly_rehandling_ore rmr
        inner join partner p on p.id=rmr.partner_id")->where($filter ." GROUP BY partner_id ")->orderby("partner_id asc")->lim();//
   
    $list_data=array();
    $total_qty=0;
    while($data = $db->fetchObject($list_qry)){
        //echo "<pre>";print_r($data);echo "</pre>";
    //if(!empty($data)){
        $list_data[$data->partner_id]['partner_id']=$data->partner_id;
        $list_data[$data->partner_id]['partner_name']=$data->name;
        $list_data[$data->partner_id]['alias']=$data->alias;
        $list_data[$data->partner_id]['color']=$data->rgb_color;
        $list_data[$data->partner_id]['jml_ritase']=$data->jml_ritase;
        $list_data[$data->partner_id]['jml_quantity']=$data->jml_quantity;//$data->jml_quantity;
        $total_qty=$total_qty+$data->jml_quantity;
        
        
    }
    $hasil=array("data"=>$list_data,"total_quantity"=>$total_qty);
   //echo "<pre>";print_r($hasil);echo "</pre>";exit;
    return $hasil;
}
public function getCommulativeCrossMiningByLokasiPIT($tahun) {
    global $dcistem;
	$db   = $dcistem->getOption("framework/db");
	$modelsortir=new Adm_Sortir_Model();
    $master = new Master_Ref_Model();
    //mktime
    $dt=mktime(0,0,0,$end_month,1,$tahun);
  
    $label=array();
    
    
    //echo "<pre>";print_r($label);echo "</pre>";
    $filter="left(month,4)>=$tahun ";
    //$sqlr= "SELECT partner_id,name,alias,rgb_color,sum(ritase) jml_ritase,sum(quantity) jml_quantity FROM recap_monthly_transit_ore rmt
   // inner join partner p on p.id=rmt.contractor_id  WHERE left(month,4)>=$tahun  GROUP BY partner_id";
    $list_qry=$db->select("SQL_CALC_FOUND_ROWS partner_id,name,alias,rgb_color,lokasi_pit_id,block_name pit_name,tujuan_pengangkutan,sum(ritase) jml_ritase,sum(quantity) jml_quantity","recap_monthly_transit_ore rmt
        inner join partner p on p.id=rmt.partner_id
        inner join lokasi_pit pit on pit.id=rmt.lokasi_pit_id")->where($filter ." GROUP BY partner_id,lokasi_pit_id,tujuan_pengangkutan ")->orderby("partner_id asc,lokasi_pit_id asc,tujuan_pengangkutan desc")->lim();//
   
    $list_data=array();
    $datasets=array();
    $labels     = array();
    $datas     = array();
    $total_qty=0;
    $i=0;
    while($data = $db->fetchObject($list_qry)){
        //echo "<pre>";print_r($data);echo "</pre>";
    //if(!empty($data)){
         
         
        $list_data[$data->lokasi_pit_id]['pit_id']  =$data->lokasi_pit_id;
        $list_data[$data->lokasi_pit_id]['pit_name']=$data->pit_name;
        $list_data[$data->lokasi_pit_id]['partner_alias']=$data->alias;
        if (isset($list_data[$data->lokasi_pit_id]['ETO'])){
             
            if(trim($data->tujuan_pengangkutan)=="ETO"){
                $labels[]=$data->pit_name." (".$data->tujuan_pengangkutan.")"; 
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['partner_id']=$data->partner_id;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['partner_name']=$data->name;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['alias']=$data->alias;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['color']=$data->rgb_color;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['pit_id']=$data->lokasi_pit_id;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['pit']=$data->pit_name;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['tujuan']=$data->tujuan_pengangkutan;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['jml_ritase']=$data->jml_ritase;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['jml_quantity']=$data->jml_quantity;
            }
        }else{
            if(!in_array($data->pit_name." (ETO)",$labels)){
                $labels[]=$data->pit_name." (ETO)"; 
                //$datas_qty[]=$data->jml_quantity; 
            }
            if(trim($data->tujuan_pengangkutan)=="ETO"){
                //echo "ce".$data->lokasi_pit_id."<br>";
                
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['partner_id']=$data->partner_id;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['partner_name']=$data->name;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['alias']=$data->alias;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['color']=$data->rgb_color;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['pit_id']=$data->lokasi_pit_id;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['pit']=$data->pit_name;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['tujuan']=$data->tujuan_pengangkutan;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['jml_ritase']=$data->jml_ritase;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['jml_quantity']=$data->jml_quantity;
            }else{
                
                $list_data[$data->lokasi_pit_id]['ETO']=array();
            }
        }
        if (isset($list_data[$data->lokasi_pit_id]['BRG'])){
            if(trim($data->tujuan_pengangkutan)=="BRG"){
                
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['partner_id']=$data->partner_id;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['partner_name']=$data->name;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['alias']=$data->alias;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['color']=$data->rgb_color;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['pit_id']=$data->lokasi_pit_id;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['pit']=$data->pit_name;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['tujuan']=$data->tujuan_pengangkutan;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['jml_ritase']=$data->jml_ritase;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['jml_quantity']=$data->jml_quantity;
            }
        }else{
            if(!in_array($data->pit_name." (BRG)",$labels)){
                $labels[]=$data->pit_name." (BRG)"; 
                //$datas_qty[]=$data->jml_quantity; 
            }
            if(trim($data->tujuan_pengangkutan)=="BRG"){
                
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['partner_id']=$data->partner_id;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['partner_name']=$data->name;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['alias']=$data->alias;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['color']=$data->rgb_color;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['pit_id']=$data->lokasi_pit_id;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['pit']=$data->pit_name;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['tujuan']=$data->tujuan_pengangkutan;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['jml_ritase']=$data->jml_ritase;
                $list_data[$data->lokasi_pit_id][$data->tujuan_pengangkutan]['jml_quantity']=$data->jml_quantity;
            }else{
                
                $list_data[$data->lokasi_pit_id]['BRG']=array();
            }
        }
        /*$list_data[$data->lokasi_pit_id]['ETO']=$data->pit_name;
        $list_data[$data->lokasi_pit_id]['data']=$data->pit_name;
         
        $list_data2[$i]['partner_id']=$data->partner_id;
        $list_data2[$i]['partner_name']=$data->name;
        $list_data2[$i]['alias']=$data->alias;
        $list_data2[$i]['color']=$data->rgb_color;
        $list_data2[$i]['pit_id']=$data->lokasi_pit_id;
        $list_data2[$i]['pit']=$data->pit_name;
        $list_data2[$i]['tujuan']=$data->tujuan_pengangkutan;
        $list_data2[$i]['jml_ritase']=$data->jml_ritase;
        $list_data2[$i]['jml_quantity']=$data->jml_quantity;*/
        $total_qty=$total_qty+$data->jml_quantity;
        $i++;
        
    }
    $hasil=array("data"=>$list_data,"total_quantity"=>$total_qty,"labels"=>$labels);
    //echo "<pre>";print_r($list_data2);echo "</pre>";
  //echo "<pre>";print_r($hasil);echo "</pre>";
    return $hasil;
}
public function getRecapExpitOreStockpilingByMonth($tahun,$end_month,$limit=12) {
    global $dcistem;
	$db   = $dcistem->getOption("framework/db");
	$modelsortir=new Adm_Sortir_Model();
    $master = new Master_Ref_Model();
    //mktime
    $dt=mktime(0,0,0,$end_month,1,$tahun);
  
    $label=array();
    $cols="";
    $label_array=array();
    $tahun_seacrh="";
    for($i=12;$i>=1;$i--){
        
        $j=$i-1;
       
         
        $dt=mktime(0,0,0,((int)$end_month-$j),1,$tahun);
        $thn    = date("Y",$dt);
        $bln    = date("m",$dt);
        $nama_bulan=$master->namabulanIN((int)$bln);
        $label[$i]['field_ritase']="ritase".$bln.$thn;
        $label[$i]['field_quantity']="quantity".$bln.$thn;
        $label[$i]['NoBulan']=$bln;
        $label[$i]['tahun']=$thn;
        $label[$i]['Label']=$nama_bulan." ".$thn;
        $col="SUM(case when cast( right(month,2) as UNSIGNED)=".(int)$bln." and left(month,4)=$thn then ritase else 0 END) ritase".$bln.$thn.",
        SUM(case when cast( right(month,2) as UNSIGNED)=".(int)$bln." and left(month,4)=$thn then quantity else 0 END) quantity".$bln.$thn."";
        $cols=trim($cols)==""?$col:$cols.",".$col;
        $nm=$master->namabulanIN((int)$bln,true)." ".substr($thn,(strlen($thn)-2),2);
        $label_array[12-$i]=$nm;
        if($i==12){
            $tahun_seacrh=$thn;
        }
       
    }
    
    //echo "<pre>";print_r($label);echo "</pre>";
    $sqlr= "SELECT partner_id,name,alias,rgb_color,".$cols." FROM recap_monthly_transit_ore rmp
    inner join partner p on p.id=rmp.partner_id  WHERE left(month,4)>=$tahun_seacrh and tujuan_pengangkutan='ETO' GROUP BY partner_id";
  // echo "sql:".$sqlr;
    $list_qry   =$db->query($sqlr);
    $list_data_ritase=array();
    $list_data_quantity=array();
    $partner=array();
    while($data       =$db->fetchObject($list_qry)){
        //echo "<pre>";print_r($data);echo "</pre>";
    //if(!empty($data)){
        $partner[$data->partner_id]['ID']=$data->partner_id;
        $partner[$data->partner_id]['Name']=$data->name;
        $partner[$data->partner_id]['Alias']=$data->alias;
         $partner[$data->partner_id]['RGBColor']=$data->rgb_color;
        $data_ritase_array=array();
        $data_quantity_array=array();
        for($i=12;$i>=1;$i--){
            //echo $data->$label[$i]['field_cum_qty'];
            $data_ritase_array[]=$data->$label[$i]['field_ritase'];
            $data_quantity_array[]=$data->$label[$i]['field_quantity'];
        }
        $list_data_ritase[$data->partner_id]=$data_ritase_array;
        $list_data_quantity[$data->partner_id]=$data_quantity_array;
       
        
    }
    $hasil=array("data_ritase"=>$list_data_ritase,"data_quantity"=>$list_data_quantity,"labels"=>$label_array,"partner"=>$partner);
   // echo "<pre>";print_r($hasil);echo "</pre>";
    return $hasil;
}
public function getRecapExpitOreCrossminingByMonth($tahun,$end_month,$limit=12) {
    global $dcistem;
	$db   = $dcistem->getOption("framework/db");
	$modelsortir=new Adm_Sortir_Model();
    $master = new Master_Ref_Model();
    //mktime
    $dt=mktime(0,0,0,$end_month,1,$tahun);
  
    $label=array();
    $cols="";
    $label_array=array();
    $tahun_seacrh="";
    for($i=12;$i>=1;$i--){
        
        $j=$i-1;
       
         
        $dt=mktime(0,0,0,((int)$end_month-$j),1,$tahun);
        $thn    = date("Y",$dt);
        $bln    = date("m",$dt);
        $nama_bulan=$master->namabulanIN((int)$bln);
        $label[$i]['field_ritase']="ritase".$bln.$thn;
        $label[$i]['field_quantity']="quantity".$bln.$thn;
        $label[$i]['NoBulan']=$bln;
        $label[$i]['tahun']=$thn;
        $label[$i]['Label']=$nama_bulan." ".$thn;
        $col="SUM(case when cast( right(month,2) as UNSIGNED)=".(int)$bln." and left(month,4)=$thn then ritase else 0 END) ritase".$bln.$thn.",
        SUM(case when cast( right(month,2) as UNSIGNED)=".(int)$bln." and left(month,4)=$thn then quantity else 0 END) quantity".$bln.$thn."";
        $cols=trim($cols)==""?$col:$cols.",".$col;
        $nm=$master->namabulanIN((int)$bln,true)." ".substr($thn,(strlen($thn)-2),2);
        $label_array[12-$i]=$nm;
        if($i==12){
            $tahun_seacrh=$thn;
        }
       
    }
    
    //echo "<pre>";print_r($label);echo "</pre>";
    $sqlr= "SELECT partner_id,name,alias,rgb_color,".$cols." FROM recap_monthly_transit_ore rmp
    inner join partner p on p.id=rmp.partner_id  WHERE left(month,4)>=$tahun_seacrh and tujuan_pengangkutan='BRG' GROUP BY partner_id";
  // echo "sql:".$sqlr;
    $list_qry   =$db->query($sqlr);
    $list_data_ritase=array();
    $list_data_quantity=array();
    $partner=array();
    while($data       =$db->fetchObject($list_qry)){
        //echo "<pre>";print_r($data);echo "</pre>";
    //if(!empty($data)){
        $partner[$data->partner_id]['ID']=$data->partner_id;
        $partner[$data->partner_id]['Name']=$data->name;
        $partner[$data->partner_id]['Alias']=$data->alias;
         $partner[$data->partner_id]['RGBColor']=$data->rgb_color;
        $data_ritase_array=array();
        $data_quantity_array=array();
        for($i=12;$i>=1;$i--){
            //echo $data->$label[$i]['field_cum_qty'];
            $data_ritase_array[]=$data->$label[$i]['field_ritase'];
            $data_quantity_array[]=$data->$label[$i]['field_quantity'];
        }
        $list_data_ritase[$data->partner_id]=$data_ritase_array;
        $list_data_quantity[$data->partner_id]=$data_quantity_array;
       
        
    }
    $hasil=array("data_ritase"=>$list_data_ritase,"data_quantity"=>$list_data_quantity,"labels"=>$label_array,"partner"=>$partner);
   // echo "<pre>";print_r($hasil);echo "</pre>";
    return $hasil;
}
public function getRecapRehandlinggByMonth($tahun,$end_month,$limit=12) {
    global $dcistem;
	$db   = $dcistem->getOption("framework/db");
	$modelsortir=new Adm_Sortir_Model();
    $master = new Master_Ref_Model();
    //mktime
    $dt=mktime(0,0,0,$end_month,1,$tahun);
  
    $label=array();
    $cols="";
    $label_array=array();
    $tahun_seacrh="";
    for($i=12;$i>=1;$i--){
        
        $j=$i-1;
       
         
        $dt=mktime(0,0,0,((int)$end_month-$j),1,$tahun);
        $thn    = date("Y",$dt);
        $bln    = date("m",$dt);
        $nama_bulan=$master->namabulanIN((int)$bln);
        $label[$i]['field_ritase']="ritase".$bln.$thn;
        $label[$i]['field_quantity']="quantity".$bln.$thn;
        $label[$i]['NoBulan']=$bln;
        $label[$i]['tahun']=$thn;
        $label[$i]['Label']=$nama_bulan." ".$thn;
        $col="SUM(case when cast( right(month,2) as UNSIGNED)=".(int)$bln." and left(month,4)=$thn then ritase else 0 END) ritase".$bln.$thn.",
        SUM(case when cast( right(month,2) as UNSIGNED)=".(int)$bln." and left(month,4)=$thn then quantity else 0 END) quantity".$bln.$thn."";
        $cols=trim($cols)==""?$col:$cols.",".$col;
        $nm=$master->namabulanIN((int)$bln,true)." ".substr($thn,(strlen($thn)-2),2);
        $label_array[12-$i]=$nm;
        if($i==12){
            $tahun_seacrh=$thn;
        }
       
    }
    
    //echo "<pre>";print_r($label);echo "</pre>";
    $sqlr= "SELECT partner_id,name,alias,rgb_color,".$cols." FROM recap_monthly_rehandling_ore rmp
    inner join partner p on p.id=rmp.partner_id  WHERE left(month,4)>=$tahun_seacrh  GROUP BY partner_id";
  // echo "sql:".$sqlr;
    $list_qry   =$db->query($sqlr);
    $list_data_ritase=array();
    $list_data_quantity=array();
    $partner=array();
    while($data       =$db->fetchObject($list_qry)){
        //echo "<pre>";print_r($data);echo "</pre>";
    //if(!empty($data)){
        $partner[$data->partner_id]['ID']=$data->partner_id;
        $partner[$data->partner_id]['Name']=$data->name;
        $partner[$data->partner_id]['Alias']=$data->alias;
         $partner[$data->partner_id]['RGBColor']=$data->rgb_color;
        $data_ritase_array=array();
        $data_quantity_array=array();
        for($i=12;$i>=1;$i--){
            //echo $data->$label[$i]['field_cum_qty'];
            $data_ritase_array[]=$data->$label[$i]['field_ritase'];
            $data_quantity_array[]=$data->$label[$i]['field_quantity'];
        }
        $list_data_ritase[$data->partner_id]=$data_ritase_array;
        $list_data_quantity[$data->partner_id]=$data_quantity_array;
       
        
    }
    $hasil=array("data_ritase"=>$list_data_ritase,"data_quantity"=>$list_data_quantity,"labels"=>$label_array,"partner"=>$partner);
   // echo "<pre>";print_r($hasil);echo "</pre>";
    return $hasil;
}
public function getRecapShipmentByMonth($tahun,$end_month,$limit=12) {
    global $dcistem;
	$db   = $dcistem->getOption("framework/db");
	$modelsortir=new Adm_Sortir_Model();
    $master = new Master_Ref_Model();
    //mktime
    $dt=mktime(0,0,0,$end_month,1,$tahun);
  
    $label=array();
    $cols="";
    $label_array=array();
    $tahun_seacrh="";
    for($i=12;$i>=1;$i--){
        
        $j=$i-1;
       
         
        $dt=mktime(0,0,0,((int)$end_month-$j),1,$tahun);
        $thn    = date("Y",$dt);
        $bln    = date("m",$dt);
        $nama_bulan=$master->namabulanIN((int)$bln);
        $label[$i]['field_ritase']="ritase".$bln.$thn;
        $label[$i]['field_quantity']="quantity".$bln.$thn;
        $label[$i]['field_draught_survey']="draught_survey".$bln.$thn;
        $label[$i]['NoBulan']=$bln;
        $label[$i]['tahun']=$thn;
        $label[$i]['Label']=$nama_bulan." ".$thn;
        $col="SUM(case when cast( right(month,2) as UNSIGNED)=".(int)$bln." and left(month,4)=$thn then ritase else 0 END) ritase".$bln.$thn.",
        SUM(case when cast( right(month,2) as UNSIGNED)=".(int)$bln." and left(month,4)=$thn then quantity else 0 END) quantity".$bln.$thn.",
        SUM(case when cast( right(month,2) as UNSIGNED)=".(int)$bln." and left(month,4)=$thn then total_draught_survey else 0 END) draught_survey".$bln.$thn."";
        $cols=trim($cols)==""?$col:$cols.",".$col;
        $nm=$master->namabulanIN((int)$bln,true)." ".substr($thn,(strlen($thn)-2),2);
        $label_array[12-$i]=$nm;
        if($i==12){
            $tahun_seacrh=$thn;
        }
       
    }
    
    //echo "<pre>";print_r($label);echo "</pre>";
    $sqlr= "SELECT ".$cols." FROM recap_monthly_shipment   
        WHERE left(month,4)>=$tahun_seacrh";
  // echo "sql:".$sqlr;
    $list_qry   =$db->query($sqlr);
    $list_data_ritase=array();
    $list_data_quantity=array();
    $partner=array();
    $m=0;
    while($data       =$db->fetchObject($list_qry)){
       // echo "<pre>";print_r($data);echo "</pre>";
    //if(!empty($data)){
       
        $data_ritase_array=array();
        $data_quantity_array=array();
        $draught_survey_array=array();
        for($i=12;$i>=1;$i--){
           // echo $i."-".$data->$label[$i]['field_ritase'];
            
            $data_ritase_array[]=$data->$label[$i]['field_ritase'];
            $data_quantity_array[]=$data->$label[$i]['field_quantity'];
            $draught_survey_array[]=$data->$label[$i]['field_draught_survey'];
            
        }
       
        $list_data_ritase[$m]=$data_ritase_array;
        $list_data_quantity[$m]=$data_quantity_array;
        $list_draught_survey[$m]=$draught_survey_array;
        $m++;
    }
    // exit;
    $hasil=array("data_ritase"=>$list_data_ritase,"data_quantity"=>$list_data_quantity,"draught_survey"=>$list_draught_survey,"labels"=>$label_array);
   // echo "<pre>";print_r($hasil);echo "</pre>";
    return $hasil;
}
public function getRecapBargeByMonth($tahun,$end_month,$limit=12) {
    global $dcistem;
	$db   = $dcistem->getOption("framework/db");
	$modelsortir=new Adm_Sortir_Model();
    $master = new Master_Ref_Model();
    //mktime
    $dt=mktime(0,0,0,$end_month,1,$tahun);
  
    $label=array();
    $cols="";
    $label_array=array();
    $tahun_seacrh="";
    for($i=$limit;$i>=1;$i--){
        
        $j=$i-1;
       
         
        $dt=mktime(0,0,0,((int)$end_month-$j),1,$tahun);
        $thn    = date("Y",$dt);
        $bln    = date("m",$dt);
        $nama_bulan=$master->namabulanIN((int)$bln);
        //echo $nama_bulan;
        $label[$i]['field_ritase']="ritase".$bln.$thn;
        $label[$i]['field_quantity']="quantity".$bln.$thn;
        $label[$i]['field_draught_survey']="draught_survey".$bln.$thn;
        $label[$i]['NoBulan']=$bln;
        $label[$i]['tahun']=$thn;
        $label[$i]['Label']=$nama_bulan." ".$thn;
        $col="SUM(case when cast( right(month,2) as UNSIGNED)=".(int)$bln." and left(month,4)=$thn then ritase else 0 END) ritase".$bln.$thn.",
        SUM(case when cast( right(month,2) as UNSIGNED)=".(int)$bln." and left(month,4)=$thn then quantity else 0 END) quantity".$bln.$thn.",
        SUM(case when cast( right(month,2) as UNSIGNED)=".(int)$bln." and left(month,4)=$thn then total_draught_survey else 0 END) draught_survey".$bln.$thn."";
        $cols=trim($cols)==""?$col:$cols.",".$col;
        $nm=$master->namabulanIN((int)$bln,true)." ".substr($thn,(strlen($thn)-2),2);
        //echo $bln." ".$nm." | ";
        $label_array[$limit-$i]=$nm;
       // if($i==12){
            $tahun_seacrh=$thn;
        //}
       
    }
    
    //echo "<pre>";print_r($label_array);echo "</pre>";
    $sqlr= "SELECT barge_id,brg.name,rgb_color,".$cols." FROM recap_monthly_shipment   rms
            inner join barges brg on brg.id=rms.barge_id  
        WHERE left(month,4)>=$tahun_seacrh GROUP BY barge_id";
  //echo "sql:".$sqlr;
    $list_qry   =$db->query($sqlr);
    $list_data_ritase=array();
    $list_data_quantity=array();
    $partner=array();
    $m=0;
    $total_data=0;
    while($data       =$db->fetchObject($list_qry)){
       // echo "<pre>";print_r($data);echo "</pre>";
    //if(!empty($data)){
       
        $data_ritase_array=array();
        $data_quantity_array=array();
        $draught_survey_array=array();
        for($i=$limit;$i>=1;$i--){
           // echo $i."-".$data->$label[$i]['field_ritase'];
            
            $data_ritase_array[]=$data->$label[$i]['field_ritase'];
            $data_quantity_array[]=$data->$label[$i]['field_quantity'];
            $draught_survey_array[]=($data->$label[$i]['field_draught_survey']/1000);
            $total_data=$total_data+$data->$label[$i]['field_draught_survey'];
            
        }
       
        $list_data_ritase[$m]['barge_id']=$data->barge_id;
        $list_data_ritase[$m]['barge_name']=$data->name;
        $list_data_ritase[$m]['data']=$data_ritase_array;
        
        $list_data_quantity[$m]['barge_id']=$data->barge_id;
        $list_data_quantity[$m]['barge_name']=$data->name;
        $list_data_quantity[$m]['data']=$data_quantity_array;
        
        $list_draught_survey[$m]['barge_id']=$data->barge_id;
        $list_draught_survey[$m]['barge_name']=$data->name;
        $list_draught_survey[$m]['rgb_color']=$data->rgb_color;
        $list_draught_survey[$m]['data']=$draught_survey_array;
        $m++;
    }
    // exit;
    $hasil=array("data_ritase"=>$list_data_ritase,"data_quantity"=>$list_data_quantity,"draught_survey"=>$list_draught_survey,"labels"=>$label_array,"total_data"=>$total_data);
   // echo "<pre>";print_r($hasil);echo "</pre>";
    return $hasil;
}


public function getCummulativeRecapLoadingToBarge($tahun) {
    global $dcistem;
	$db   = $dcistem->getOption("framework/db");
	$modelsortir=new Adm_Sortir_Model();
    $master = new Master_Ref_Model();
    //mktime
    $dt=mktime(0,0,0,$end_month,1,$tahun);
  
    $label=array();
    
    
    //echo "<pre>";print_r($label);echo "</pre>";
    $filter="left(month,4)>=$tahun and tujuan_pengangkutan='BRG'";
    //$sqlr= "SELECT partner_id,name,alias,rgb_color,sum(ritase) jml_ritase,sum(quantity) jml_quantity FROM recap_monthly_transit_ore rmt
   // inner join partner p on p.id=rmt.contractor_id  WHERE left(month,4)>=$tahun  GROUP BY partner_id";
    $list_qry=$db->select("SQL_CALC_FOUND_ROWS partner_id,name,alias,rgb_color,sum(ritase) jml_ritase,sum(quantity) jml_quantity","recap_monthly_transit_ore rmt
        inner join partner p on p.id=rmt.partner_id")->where($filter ." GROUP BY partner_id ")->orderby("partner_id asc")->lim();//
   
    $list_data=array();
    $total_qty=0;
    while($data = $db->fetchObject($list_qry)){
        //echo "<pre>";print_r($data);echo "</pre>";
    //if(!empty($data)){
        $list_data[$data->partner_id]['partner_id']=$data->partner_id;
        $list_data[$data->partner_id]['partner_name']=$data->name;
        $list_data[$data->partner_id]['alias']=$data->alias;
        $list_data[$data->partner_id]['color']=$data->rgb_color;
        $list_data[$data->partner_id]['jml_ritase']=$data->jml_ritase;
        $list_data[$data->partner_id]['jml_quantity']=$data->jml_quantity;
        $total_qty=$total_qty+$data->jml_quantity;
        
        
    }
    //echo "<pre>";print_r($label);echo "</pre>";
    $filter2="left(month,4)>=$tahun";
    //$sqlr= "SELECT partner_id,name,alias,rgb_color,sum(ritase) jml_ritase,sum(quantity) jml_quantity FROM recap_monthly_transit_ore rmt
   // inner join partner p on p.id=rmt.contractor_id  WHERE left(month,4)>=$tahun  GROUP BY partner_id";
    $list_qry2=$db->select("SQL_CALC_FOUND_ROWS partner_id,name,alias,rgb_color,sum(ritase) jml_ritase,sum(quantity) jml_quantity","recap_monthly_rehandling_ore rmh
        inner join partner p on p.id=rmh.partner_id")->where($filter2 ." GROUP BY partner_id ")->orderby("partner_id asc")->lim();//
   
    while($data1 = $db->fetchObject($list_qry2)){
        //echo "<pre>";print_r($data);echo "</pre>";
    //if(!empty($data)){
        $list_data[$data1->partner_id]['partner_id']=$data1->partner_id;
        $list_data[$data1->partner_id]['partner_name']=$data1->name;
        $list_data[$data1->partner_id]['alias']=$data1->alias;
        $list_data[$data1->partner_id]['color']=$data1->rgb_color;
        $list_data[$data1->partner_id]['jml_ritase']=$data1->jml_ritase;
        $list_data[$data1->partner_id]['jml_quantity']=$data1->jml_quantity;
        $total_qty=$total_qty+$data1->jml_quantity;
        
        
    }
    $hasil=array("data"=>$list_data,"total_quantity"=>$total_qty);
   //echo "<pre>";print_r($hasil);echo "</pre>";exit;
    return $hasil;
}
     public function getRekapPelayananTiapTPK_ByMonth($tahun,$bulan,$sort_array=array()) {
        global $dcistem;
    	$db   = $dcistem->getOption("framework/db");
    	$modelsortir=new Adm_Sortir_Model();
        $master = new Master_Ref_Model();
        $judul1="REKAP KEGIATAN";
        if( trim($tahun)<>"" ){   //name
            $keriteria[]="rkpTahun ='".$tahun."'";
            $judul2="Tahun ".$tahun;
        } 
        if( trim($bulan)<>"" ){   //name
            $keriteria[]="rkpBulan =".(int)$bulan."";
            $nama_bln=$master->namabulanIN((int)$bulan);
            if( trim($tahun)<>"" ){
                $judul2=$nama_bln." ".$tahun;
            }
        }
    	$filter=$modelsortir->fromFormcari($keriteria,"and");
       
        $group="group by rkpTahun, rkpTPK";
        if(trim($filter)<>""){
            $filter=$filter." ".$group;
            $group="";
        }    
        $list_qry2=$db->select("rkpTahun, rkpTPK,m.name NamaTPK,rkpJenisPelayanan,kpj.pelayanan_nama,
            sum(case when rkpBulan=1 then rkpJumlah else 0 end) jml_jan,
            sum(case when rkpBulan=2 then rkpJumlah else 0 end) jml_feb,
            sum(case when rkpBulan=3 then rkpJumlah else 0 end) jml_mar,
            sum(case when rkpBulan=4 then rkpJumlah else 0 end) jml_apr,
            sum(case when rkpBulan=5 then rkpJumlah else 0 end) jml_mei,
            sum(case when rkpBulan=6 then rkpJumlah else 0 end) jml_jun,
            sum(case when rkpBulan=7 then rkpJumlah else 0 end) jml_jul,
            sum(case when rkpBulan=8 then rkpJumlah else 0 end) jml_agu,
            sum(case when rkpBulan=9 then rkpJumlah else 0 end) jml_sep,
            sum(case when rkpBulan=10 then rkpJumlah else 0 end) jml_okt,
            sum(case when rkpBulan=11 then rkpJumlah else 0 end) jml_nop,
            sum(case when rkpBulan=12 then rkpJumlah else 0 end) jml_des,
            sum(ifnull(rkpJumlah,0)) jumlah","tbmrekappelayananbydate rkp
            inner join keswan_pelayanan_jenis kpj on kpj.pelayanan_id=rkp.rkpJenisPelayanan
            inner join mcp m on m.id=rkp.rkpTPK $group")
    		->where($filter)->orderby("rkpTahun asc, rkpTPK asc")->lim();//
        $no         =1;
        $ListRekap  =array();      
        $tmp_tpk    ="";
        $i          =0;
        while($rekap = $db->fetchObject($list_qry2))
        {            
            // echo "<pre>";print_r($rekap);echo "</pre>";//[$rekap->SubID]
            $ListRekap[$rekap->rkpTPK]['id']=$rekap->rkpTPK;
            $ListRekap[$rekap->rkpTPK]['name']=$rekap->NamaTPK;
            if(trim($tmp_tpk)<>trim($rekap->rkpTPK)){
                $i=0;
            }
            
            $ListRekap[$rekap->rkpTPK]['data'][$i]['No']=$no;
            $ListRekap[$rekap->rkpTPK]['data'][$i]['pelayanan_id']=$rekap->rkpJenisPelayanan;
            $ListRekap[$rekap->rkpTPK]['data'][$i]['pelayanan_nama']=$rekap->pelayanan_nama;   
            $ListRekap[$rekap->rkpTPK]['data'][$i]['jml_jan']=$rekap->jml_jan;
            $ListRekap[$rekap->rkpTPK]['data'][$i]['jml_feb']=$rekap->jml_feb;
            $ListRekap[$rekap->rkpTPK]['data'][$i]['jml_mar']=$rekap->jml_mar;
            $ListRekap[$rekap->rkpTPK]['data'][$i]['jml_apr']=$rekap->jml_apr;                
            $ListRekap[$rekap->rkpTPK]['data'][$i]['jml_mei']=$rekap->jml_mei;
            $ListRekap[$rekap->rkpTPK]['data'][$i]['jml_jun']=$rekap->jml_jun;
            $ListRekap[$rekap->rkpTPK]['data'][$i]['jml_jul']=$rekap->jml_jul;
            $ListRekap[$rekap->rkpTPK]['data'][$i]['jml_agu']=$rekap->jml_agu;
            $ListRekap[$rekap->rkpTPK]['data'][$i]['jml_sep']=$rekap->jml_sep;
            $ListRekap[$rekap->rkpTPK]['data'][$i]['jml_okt']=$rekap->jml_okt;
            $ListRekap[$rekap->rkpTPK]['data'][$i]['jml_nop']=$rekap->jml_nop;
            $ListRekap[$rekap->rkpTPK]['data'][$i]['jml_des']=$rekap->jml_des;
            $ListRekap[$rekap->rkpTPK]['data'][$i]['jumlah']=$rekap->jumlah;
            
            //$ListRekap[$rekap->rkpTPK]['sub_total']=$ListRekap[$rekap->rkpTPK]['sub_total']+(int)$rekap->jumlah;
          
            
            $i++;
            $no++;
        }
        echo "<pre>";print_r($ListRekap);echo "</pre>";//[$rekap->SubID]
        if(!empty($ListRekap)){
            return array("data"=>$ListRekap,"jumlah_row"=>$jml_data,"title"=>$judul2);
        }else{
            return array();
        }
	}
    public function getRekapPelayananAllByMonth($tahun,$bulan,$sort_array=array()) {
        global $dcistem;
    	$db   = $dcistem->getOption("framework/db");
    	$modelsortir=new Adm_Sortir_Model();
        $master = new Master_Ref_Model();
        $judul1="REKAP KEGIATAN";
        if( trim($tahun)<>"" ){   //name
            $keriteria[]="rkpTahun ='".$tahun."'";
            $judul2="Tahun ".$tahun;
        } 
        if( trim($bulan)<>"" ){   //name
            $keriteria[]="rkpBulan =".(int)$bulan."";
            $nama_bln=$master->namabulanIN((int)$bulan);
            if( trim($tahun)<>"" ){
                $judul2=$nama_bln." ".$tahun;
            }
        }
    	$filter=$modelsortir->fromFormcari($keriteria,"and");
       
        $group="group by rkpTahun, rkpKasus";
        if(trim($filter)<>""){
            $filter=$filter." ".$group;
            $group="";
        }    
        $list_qry2=$db->select("rkpTahun, rkpKasus,KasusPenyakit,SubID,SubNama,
            sum(case when rkpBulan=1 then rkpJumlah else 0 end) jml_jan,
            sum(case when rkpBulan=2 then rkpJumlah else 0 end) jml_feb,
            sum(case when rkpBulan=3 then rkpJumlah else 0 end) jml_mar,
            sum(case when rkpBulan=4 then rkpJumlah else 0 end) jml_apr,
            sum(case when rkpBulan=5 then rkpJumlah else 0 end) jml_mei,
            sum(case when rkpBulan=6 then rkpJumlah else 0 end) jml_jun,
            sum(case when rkpBulan=7 then rkpJumlah else 0 end) jml_jul,
            sum(case when rkpBulan=8 then rkpJumlah else 0 end) jml_agu,
            sum(case when rkpBulan=9 then rkpJumlah else 0 end) jml_sep,
            sum(case when rkpBulan=10 then rkpJumlah else 0 end) jml_okt,
            sum(case when rkpBulan=11 then rkpJumlah else 0 end) jml_nop,
            sum(case when rkpBulan=12 then rkpJumlah else 0 end) jml_des,
            sum(ifnull(rkpJumlah,0)) sub_total","tbmrekappelayananbydate rkp
        inner join keswan_pelayanan_jenis kpj on kpj.pelayanan_id=rkp.rkpJenisPelayanan
        inner join mcp m on m.id=rkp.rkpTPK $group")
    		->where($filter)->orderby("rkpTahun asc, ")->lim();//
        $no=1;
        $i=0;
        $ListRekap=array();
      
        $last="";
        $jml_data=array();
        while($rekap = $db->fetchObject($list_qry2))
        {            
            // echo "<pre>";print_r($rekap);echo "</pre>";//[$rekap->SubID]
            $ListRekap[$i]['No']=$no;
            $ListRekap[$i]['SubID']=$rekap->SubID;
            $jml_data[$rekap->SubID]=$jml_data[$rekap->SubID]+1;
            $ListRekap[$i]['SubSistem']=$rekap->SubNama;               
            $ListRekap[$i]['Kasus']=$rekap->KasusPenyakit;
            $ListRekap[$i]['jml_jan']=$rekap->jml_jan;
            $ListRekap[$i]['jml_feb']=$rekap->jml_feb;
            $ListRekap[$i]['jml_mar']=$rekap->jml_mar;
            $ListRekap[$i]['jml_apr']=$rekap->jml_apr;                
            $ListRekap[$i]['jml_mei']=$rekap->jml_mei;
            $ListRekap[$i]['jml_jun']=$rekap->jml_jun;
            $ListRekap[$i]['jml_jul']=$rekap->jml_jul;
            $ListRekap[$i]['jml_agu']=$rekap->jml_agu;
            $ListRekap[$i]['jml_sep']=$rekap->jml_sep;
            $ListRekap[$i]['jml_okt']=$rekap->jml_okt;
            $ListRekap[$i]['jml_nop']=$rekap->jml_nop;
            $ListRekap[$i]['jml_des']=$rekap->jml_des;
            $ListRekap[$i]['sub_total']=$rekap->sub_total;
           // $ListRekap[$i]['jml_data']=$jml_data;
            if($last==$rekap->SubID){
                //echo "sama";
                //$jml_data=$jml_data+1;
                $ListRekap[$i]['first']=false;
            }else{
                $ListRekap[$i]['first']=true;
                $last=$rekap->SubID;
            }
            //$ListRekap[$i]['title']=$judul;
            
            $i++;
            $no++;
        }
        if(!empty($ListRekap)){
            return array("data"=>$ListRekap,"jumlah_row"=>$jml_data,"title2"=>$judul2);
        }else{
            return array();
        }
	}
    public function getRekapTahunanByPetugas($tahun,$bulan,$sort_array=array()) {
        global $dcistem;
    	$db   = $dcistem->getOption("framework/db");
    	$modelsortir=new Adm_Sortir_Model();
        $master = new Master_Ref_Model();
        $judul1="REKAP KEGIATAN";
        if( trim($tahun)<>"" ){   //name
            $keriteria[]="rkpTahun ='".$tahun."'";
            $judul2="Tahun ".$tahun;
        } 
        if( trim($bulan)<>"" ){   //name
            $keriteria[]="rkpBulan =".(int)$bulan."";
            $nama_bln=$master->namabulanIN((int)$bulan);
            if( trim($tahun)<>"" ){
                $judul2=$nama_bln." ".$tahun;
            }
        }
    	$filter=$modelsortir->fromFormcari($keriteria,"and");
       
        $group="group by rkpTahun, rkpPetugas";
        if(trim($filter)<>""){
            $filter=$filter." ".$group;
            $group="";
        }    
        $list_qry2=$db->select("SQL_CALC_FOUND_ROWS rkpTahun, rkpPetugas,kp.pNama,kp.pAlias,kp.pGelarDepan,kp.pGelarBelakang,
        sum(case when rkpJenisPelayanan=21 then rkpJumlah else 0 end) jml_ib,
        sum(case when rkpJenisPelayanan=7 then rkpJumlah else 0 end) jml_bunting,
        sum(case when rkpJenisPelayanan=25 then rkpJumlah else 0 end) jml_kosong,
        sum(case when rkpJenisPelayanan=28 then rkpJumlah else 0 end) jml_kelahiran,
        sum(case when rkpJenisPelayanan=19 then rkpJumlah else 0 end) jml_ganti_eartag,
        sum(case when rkpJenisPelayanan=39 then rkpJumlah else 0 end) jml_ganti_pemilik,
        sum(case when rkpJenisPelayanan=10 then rkpJumlah else 0 end) jml_pengobatan,
        sum(case when rkpJenisPelayanan=27 then rkpJumlah else 0 end) jml_sapi_baru,
        sum(case when rkpJenisPelayanan=13 then rkpJumlah else 0 end) jml_mutasi,
        sum(case when rkpKategori='lainnya' then rkpJumlah else 0 end) jml_lainnya,
        sum(ifnull(rkpJumlah,0)) sub_total","tbmrekappelayananbypetugas rkp
        inner join keswan_pegawai kp on kp.pID=rkp.rkpPetugas $group")
		->where($filter)->lim();//->orderby($order)
        $no=1;
        $i=0;
        $ListRekap=array();
        /*$total["total_ib"]=0;
        $total['total_bunting']=$rekap->jml_bunting;
        $total['total_kosong']=$rekap->jml_kosong;
        $total['total_kelahiran']=$rekap->jml_kelahiran;                
        $total['total_ganti_eartag']=$rekap->jml_ganti_eartag;
        $total['total_ganti_pemilik']=$rekap->jml_ganti_pemilik;
        $total['total_pengobatan']=$rekap->jml_pengobatan;
        $total['total_sapi_baru']=$rekap->jml_sapi_baru;
        $total['total_mutasi']=$rekap->jml_mutasi;
        $total['total_lainnya']=$rekap->jml_lainnya;
        //$ListRekap[$i]['sub_total']=$rekap->sub_total;*/
        
        while($rekap = $db->fetchObject($list_qry2))
        {            
            $ListRekap[$i]['PegawaiID']=$rekap->rkpPetugas;
            $nama_lengkap=$master->nama_dan_gelar($rekap->pGelarDepan,$rekap->pNama,$rekap->pGelarBelakang);                
            $ListRekap[$i]['Petugas']=$nama_lengkap;
            $ListRekap[$i]['jml_ib']=$rekap->jml_ib;
                $total["total_ib"]=$total["total_ib"]+(int)$rekap->jml_ib;
            
            $ListRekap[$i]['jml_bunting']=$rekap->jml_bunting;
            $ListRekap[$i]['jml_kosong']=$rekap->jml_kosong;
            $ListRekap[$i]['jml_kelahiran']=$rekap->jml_kelahiran;                
            $ListRekap[$i]['jml_ganti_eartag']=$rekap->jml_ganti_eartag;
            $ListRekap[$i]['jml_ganti_pemilik']=$rekap->jml_ganti_pemilik;
            $ListRekap[$i]['jml_pengobatan']=$rekap->jml_pengobatan;
            $ListRekap[$i]['jml_sapi_baru']=$rekap->jml_sapi_baru;
            $ListRekap[$i]['jml_mutasi']=$rekap->jml_mutasi;
            $ListRekap[$i]['jml_lainnya']=$rekap->jml_lainnya;
            $ListRekap[$i]['sub_total']=$rekap->sub_total;
            
            $i++;
            $no++;
        }
        if(!empty($ListRekap)){
            return array("data"=>$ListRekap,"title1"=>$judul1,"title2"=>$judul2);
        }else{
            return array();
        }
	}
  public function getRekapByKota() {
    global $dcistem;
	$db   = $dcistem->getOption("framework/db");
	$modelsortir=new Adm_Sortir_Model();
   $list_qry=$db->query(" select kabupatenKode,kabupatenNama,v1.jml from tbrkabupaten kab
        right JOIN (select  rkpKota, sum(rkpJumlahKontak) as jml from tbmrekapbydesa 
        where rkpKota in ('32117','32141','32744') or ifnull(rkpKota,'')=''
        GROUP BY rkpTahun,rkpKota) v1 on v1.rkpKota=kab.kabupatenKode
        UNION
        select kabupatenKode,kabupatenNama,v1.jml from tbrkabupaten kab
        left JOIN (select  rkpKota, sum(rkpJumlahKontak) as jml from tbmrekapbydesa 
        where rkpKota in ('32117','32141','32744') or ifnull(rkpKota,'')=''
        GROUP BY rkpTahun,rkpKota) v1 on v1.rkpKota=kab.kabupatenKode
        where kab.kabupatenKode in ('32117','32141','32744') ");
   $list_data=array();
   $i=0;
   
   
   
   while($data=$db->fetchObject($list_qry)){
        $kode=$data->kabupatenKode;
        $nama=$data->kabupatenNama;
        if(trim($data->kabupatenKode)==""){
            $kode="XXXXX";
            $nama='Belum Ditentukan';
        }
        $list_data[$i]['Kode']=$kode;
        $list_data[$i]['Nama']=$nama;
        $list_data[$i]['Jumlah']=trim($data->jml)==""?0:$data->jml;
        $i++;
    
   }
    // echo "<pre>";print_r($list_data);echo "</pre>";//exit;
		return $list_data;
	}
 public function getRekapProductionByMonth($tahun,$end_month,$limit=12) {
    global $dcistem;
	$db   = $dcistem->getOption("framework/db");
	$modelsortir=new Adm_Sortir_Model();
    $master = new Master_Ref_Model();
    //mktime
    $dt=mktime(0,0,0,$end_month,1,$tahun);
  
    $label=array();
    $cols="";
    $label_array=array();
    $tahun_seacrh="";
    for($i=12;$i>=1;$i--){
        
        $j=$i-1;
       
         
        $dt=mktime(0,0,0,((int)$end_month-$j),1,$tahun);
        $thn    = date("Y",$dt);
        $bln    = date("m",$dt);
        $nama_bulan=$master->namabulanIN((int)$bln);
        $label[$i]['field_qty']="qty".$bln.$thn;
        $label[$i]['field_cum_qty']="cum_qty".$bln.$thn;
        $label[$i]['field_cum_target']="cum_target".$bln.$thn;
        $label[$i]['NoBulan']=$bln;
        $label[$i]['tahun']=$thn;
        $label[$i]['Label']=$nama_bulan." ".$thn;
        $col="SUM(case when cast( right(month,2) as INT)=".(int)$bln." and left(month,4)=$thn then qty else 0 END) qty".$bln.$thn.",
        SUM(case when cast( right(month,2) as INT)=".(int)$bln." and left(month,4)=$thn then cumulative_qty else 0 END) cum_qty".$bln.$thn.",
        SUM(case when cast( right(month,2) as INT)=".(int)$bln." and left(month,4)=$thn then cumulative_target END) cum_target".$bln.$thn;
        $cols=trim($cols)==""?$col:$cols.",".$col;
        $nm=$master->namabulanIN((int)$bln,true)." ".substr($thn,(strlen($thn)-2),2);
        $label_array[12-$i]=$nm;
        if($i==12){
            $tahun_seacrh=$thn;
        }
       
    }
    
    //echo "<pre>";print_r($label);echo "</pre>";
    $sqlr= "SELECT partner_id,name,alias,".$cols." FROM report_monthly_production rmp
    inner join partner p on p.id=rmp.partner_id  WHERE left(month,4)>=$tahun_seacrh  GROUP BY partner_id";
   //echo $sqlr;
    $list_qry   =$db->query($sqlr);
    $list_data=array();
    $list_data_cum=array();
    $partner=array();
    while($data       =$db->fetchObject($list_qry)){
        //echo "<pre>";print_r($data);echo "</pre>";
    //if(!empty($data)){
        $partner[$data->partner_id]['ID']=$data->partner_id;
        $partner[$data->partner_id]['Name']=$data->name;
        $partner[$data->partner_id]['Alias']=$data->alias;
        $data_array=array();
        $data_cum_array=array();
        $data_cum_target=array();
        for($i=12;$i>=1;$i--){
            //echo $data->$label[$i]['field_cum_qty'];
            $data_array[]=$data->$label[$i]['field_qty'];
            $data_cum_array[]=$data->$label[$i]['field_cum_qty'];
            $data_cum_target[]=$data->$label[$i]['field_cum_target'];
        }
        $list_data[$data->partner_id]=$data_array;
        $list_data_cum[$data->partner_id]=$data_cum_array;
        $list_data_cum_target[$data->partner_id]=$data_cum_target;
        
    }
    $hasil=array("data"=>$list_data,"data_cumulative"=>$list_data_cum,"cumulative_target"=>$list_data_cum_target,"label"=>$label_array,"partner"=>$partner);
   // echo "<pre>";print_r($hasil);echo "</pre>";
    return $hasil;
}

public function getRekapBudgetingByMonth($tahun,$end_month,$limit=12) {
    global $dcistem;
	$db   = $dcistem->getOption("framework/db");
	$modelsortir=new Adm_Sortir_Model();
    $master = new Master_Ref_Model();
    //mktime
    $dt=mktime(0,0,0,$end_month,1,$tahun);
  
    $label=array();
    $cols="";
    $label_array=array();
    $tahun_seacrh="";
    for($i=12;$i>=1;$i--){
        
        $j=$i-1;
       
         
        $dt=mktime(0,0,0,((int)$end_month-$j),1,$tahun);
        $thn    = date("Y",$dt);
        $bln    = date("m",$dt);
        $nama_bulan=$master->namabulanIN((int)$bln);
        $label[$i]['field_plan']="plan".$bln.$thn;
        $label[$i]['field_actual']="actual".$bln.$thn;
        $label[$i]['NoBulan']=$bln;
        $label[$i]['tahun']=$thn;
        $label[$i]['Label']=$nama_bulan." ".$thn;
        $col="SUM(case when cast( right(bulan,2) as INT)=".(int)$bln." and left(bulan,4)=$thn then plan else 0 END) plan".$bln.$thn.",
        SUM(case when cast( right(bulan,2) as INT)=".(int)$bln." and left(bulan,4)=$thn then actual else 0 END) actual".$bln.$thn;
        $cols=trim($cols)==""?$col:$cols.",".$col;
        $nm=$master->namabulanIN((int)$bln,true)." ".substr($thn,(strlen($thn)-2),2);
        $label_array[12-$i]=$nm;
        if($i==12){
            $tahun_seacrh=$thn;
        }
       
    }
    
    //echo "<pre>";print_r($label);echo "</pre>";
    $sqlr= "SELECT bulan,".$cols." FROM report_monthly_budgeting   WHERE left(bulan,4)>=$tahun_seacrh  GROUP BY left(bulan,4)";
  // echo $sqlr;
    $list_qry   =$db->query($sqlr);
    $list_data=array();
    $list_data_cum=array();
    $budget=array();
    while($data       =$db->fetchObject($list_qry)){
        //echo "<pre>";print_r($data);echo "</pre>";
    //if(!empty($data)){
      
        $data_array=array();
        $plan_array=array();
        $actual_array=array();
        for($i=12;$i>=1;$i--){
            //echo $data->$label[$i]['field_cum_qty'];
            $data_array[]=$data->$label[$i]['field_plan']/1000;
            $actual_array[]=$data->$label[$i]['field_actual']/1000;
        }
        $list_data['Plan']=$data_array;
        $list_data['Actual']=$actual_array;
        
    }
    $hasil=array("data"=>$list_data,"label"=>$label_array);
    //echo "<pre>";print_r($hasil);echo "</pre>";
    return $hasil;
}


public function getRekapCumulativeProductionByMonth($tahun,$end_month,$limit=12) {
    global $dcistem;
	$db   = $dcistem->getOption("framework/db");
	$modelsortir=new Adm_Sortir_Model();
    $master = new Master_Ref_Model();
    //mktime
    $dt=mktime(0,0,0,$end_month,1,$tahun);
  
    $label=array();
    $cols="";
    $label_array=array();
    $tahun_seacrh="";
    for($i=12;$i>=1;$i--){
        
        $j=$i-1;
       
         
        $dt=mktime(0,0,0,((int)$end_month-$j),1,$tahun);
        $thn    = date("Y",$dt);
        $bln    = date("m",$dt);
        $nama_bulan=$master->namabulanIN((int)$bln);
        $label[$i]['field']="bln".$bln.$thn;
        $label[$i]['NoBulan']=$bln;
        $label[$i]['tahun']=$thn;
        $label[$i]['Label']=$nama_bulan." ".$thn;
        $col="SUM(case when cast( right(month,2) as INT)=".(int)$bln." and left(month,4)=$thn then qty else 0 END) bln".$bln.$thn;
        $cols=trim($cols)==""?$col:$cols.",".$col;
        $nm=$master->namabulanIN((int)$bln,true)." ".substr($thn,(strlen($thn)-2),2);
        $label_array[12-$i]=$nm;
        if($i==12){
            $tahun_seacrh=$thn;
        }
       
    }
    
    //echo "<pre>";print_r($label_array);echo "</pre>";
    $sqlr= "SELECT id,month,partner_id,target,cumulative_target,qty,cumulative_qty,
                locked FROM report_monthly_production WHERE left(month,4)>=$tahun_seacrh 
                order by cast( left(month,4) as INT) asc,cast( right(month,2) as INT) asc";
   //echo $sqlr;
    $list_qry   =$db->query($sqlr);
    $data_array=array();
    while($data       =$db->fetchObject($list_qry)){
        //echo "<pre>";print_r($data);echo "</pre>";
    //if(!empty($data)){
        $list_data['label'][$data->partner_id][]=$data->qty;
        $list_data['data_array'][$data->partner_id][]=$data->qty;
        $list_cum_data['data_array'][$data->partner_id][]=$data->cumulative_qty;
       
        
    }
    $data_array=array("data"=>$list_data,"data_cumulative"=>$list_cum_data,"label"=>$label_array);
    //echo "<pre>";print_r($data_array);echo "</pre>";
    return $data_array;
}
    
    public function getRekapPelayananByCurrentMonth($tahun,$end_month,$limit=12) {
    global $dcistem;
	$db   = $dcistem->getOption("framework/db");
	$modelsortir=new Adm_Sortir_Model();
    $master = new Master_Ref_Model();
    //mktime
    $dt=mktime(0,0,0,$end_month,1,$tahun);
  
    $label=array();
    $cols="";
    for($i=1;$i<=12;$i++){
        $dt=mktime(0,0,0,((int)$end_month-$i),1,$tahun);
        $thn    = date("Y",$dt);
        $bln    = date("m",$dt);
        $nama_bulan=$master->namabulanIN((int)$bln);
        $label[$i]['field']="bln".$bln.$thn;
        $label[$i]['NoBulan']=$bln;
        $label[$i]['tahun']=$thn;
        $label[$i]['Label']=$nama_bulan." ".$thn;
        $col="SUM(case when rkpBulan=$bln and rkpTahun=$thn then rkpJumlah else 0 END) bln".$bln.$thn;
        $cols=trim($cols)==""?$col:$cols.",".$col;
       
    }
    $sqlr= "SELECT ".$cols." FROM tbmrekappelayananbydate WHERE rkpTahun>=$thn";
    //echo "<pre>";print_r($label);echo "</pre>";
    
    $list_qry=$db->query($sqlr);
    $list_data=array();
    $i=0;
    while($data=$db->fetchObject($list_qry)){
        $kode=$data->kabupatenKode;
        $nama=$data->kabupatenNama;
        /*if(trim($data->kabupatenKode)==""){
            $kode="XXXXX";
            $nama='Belum Ditentukan';
        }*/
        $list_data[$i]['Kode']=$kode;
        $list_data[$i]['Nama']=$nama;
        $list_data[$i]['bln9']=$data->bln9;
        $list_data[$i]['bln10']=$data->bln10;
        $list_data[$i]['bln11']=$data->bln11;
        $list_data[$i]['bln12']=$data->bln12;
        $list_data[$i]['bln1']=$data->bln1;
        $list_data[$i]['bln2']=$data->bln2;
        $list_data[$i]['bln3']=$data->bln3;
        $list_data[$i]['bln4']=$data->bln4;
        //$list_data[$i]['Jumlah']=trim($data->jml)==""?0:$data->jml;
        $i++;
    
   }
    // echo "<pre>";print_r($list_data);echo "</pre>";//exit;
		return $list_data;
	}
 public function getRekapByKecamatan() {
    global $dcistem;
	$db   = $dcistem->getOption("framework/db");
	$modelsortir=new Adm_Sortir_Model();
   $list_qry=$db->query(" select kecKode,kecNama,v1.jml_kontak,v1.jml_konstituen,jumlah_dpt from tbrkecamatan kec
        left JOIN (select  rkpKecamatan, sum(rkpJumlahKontak) as jml_kontak,sum(rkpJumlahKonstituen) as jml_konstituen,
        sum(ifnull(rkpJumlahDPT,0)) jumlah_dpt from tbmrekapbydesa GROUP BY rkpPemilu,rkpKota,rkpKecamatan) v1 on v1.rkpKecamatan=kec.kecKode
        where kecDAPIL='320523'");
   $list_data=array();
   $i=0;
   
   
   
   while($data=$db->fetchObject($list_qry)){
        $kode=$data->kecKode;
        $nama=$data->kecNama;
        if(trim($data->kecKode)==""){
            $kode="XXXXX";
            $nama='Belum Ditentukan';
        }
        $list_data[$i]['Kode']=$kode;
        $list_data[$i]['Nama']=$nama;
        $list_data[$i]['JumlahDPT']=trim($data->jumlah_dpt)==""?0:$data->jumlah_dpt;
        $list_data[$i]['JumlahKontak']=trim($data->jml_kontak)==""?0:$data->jml_kontak;
        $list_data[$i]['JumlahKonstituen']=trim($data->jml_konstituen)==""?0:$data->jml_konstituen;
        $i++;
    
   }
    // echo "<pre>";print_r($list_data);echo "</pre>";exit;
		return $list_data;
	}
public function getRekapProductionByWeek($start_week=array(),$end_week=array()) {
    global $dcistem;
    $db   = $dcistem->getOption("framework/db");
    $modelsortir=new Adm_Sortir_Model();
    $filter="(wp.week>=".$start_week['week']." and wp.tahun=".$start_week['year'].") or (wp.week<=".$end_week['week']." and wp.tahun=".$end_week['year'].")";
    if($start_week['year']==$end_week['year']){
        $filter="(wp.week>=".$start_week['week']." and wp.week<=".$end_week['week']." and wp.tahun=".$end_week['year'].")";
    }
    
    $list_qry=$db->select("rwp.id,partner_id,p.name,p.alias,periode_id,wp.week,target,cumulative_target,qty,cumulative_qty,locked
    ","report_weekly_production rwp
    inner join week_periode wp on wp.id=rwp.periode_id
    inner join partner p on p.id=rwp.partner_id")->orderBy("wp.tahun asc, wp.week asc")
    ->where($filter)->lim();
    $list_data=array();
    $i=0;
    while($data=$db->fetchObject($list_qry)){
        $list_data[$data->partner_id]['partner_id']=$data->partner_id;
        $list_data[$data->partner_id]['partner_name']=$data->name;
        $list_data[$data->partner_id]['partner_alias']=$data->alias;
       // $list_data[$data->partner_id]['data'][$data->week]['week']=$data->week;
        $list_data[$data->partner_id]['label'][]=$data->week;
        $list_data[$data->partner_id]['qty'][]=$data->qty;
        $list_data[$data->partner_id]['cumulative_qty'][]=$data->cumulative_qty;
        $list_data[$data->partner_id]['target'][]=$data->target;
        $list_data[$data->partner_id]['cumulative_target'][]=$data->cumulative_target;
        $i++;
    
    }
  // echo "<pre>";print_r($list_data);echo "</pre>";
    //$hasil=array("data"=>$list_data,"data_cumulative"=>$list_data_cum,"cumulative_target"=>$list_data_cum_target,"label"=>$label_array,"partner"=>$partner);
   // echo "<pre>";print_r($hasil);echo "</pre>";
    return $list_data;
}
  
  
}