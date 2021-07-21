<?php
/**
 * @package Rekap Data
 * @subpackage Rekap Data Pendaftaran
 *
 * @author Hasan <kuring.hasan@gmail.com>
 */

defined("PANDORA") OR die("No direct access allowed.");

class Daily_Production_Model extends Model {
  public function __construct() {
        global $dcistem;
       
	   $this->UnitID=$dcistem->getOption("system/web/unit_id");
       
  }
  
public function getDailyRitaseExpitByContractor($range,$star_date="",$end_date="") {
    /** Range :
     * today
     * yesterday
     * this_week
     * this_month
     * date_range -> harus isi star_date dan end_date
    */
    global $dcistem;
	$db   = $dcistem->getOption("framework/db");
    
	$modelsortir=new Adm_Sortir_Model();
    $master = new Master_Ref_Model();
    $settings= $master->settings();
    date_default_timezone_set($settings['production_time_zone']);
    
    $filter="";
    $judul="";
    switch($range){
        case "today":
            $filter="DATE_FORMAT(tanggal,'%Y-%m-%d')='".date("Y-m-d")."'";
            $judul="RITASE EXPIT (Today, ".date("Y-m-d").")"; 
        break;
        case "yesterday":
            $tm=mktime(0,0,0,date("m"),((int)date("d")-1),date("Y"));
            $filter="DATE_FORMAT(tanggal,'%Y-%m-%d')='".date("Y-m-d",$tm)."'";   
            $judul="RITASE EXPIT (Yesterday, ".date("Y-m-d",$tm).")";       
        break;
        case "this_week":
            $filter_week="'".date("Y-m-d")."' BETWEEN start_date and end_date";
            $week=$db->select("id,week,DATE_FORMAT(start_date,'%Y-%m-%d') start_date,DATE_FORMAT(end_date,'%Y-%m-%d') end_date","week_periode")->where($filter_week)->get(0);
            $judul="RITASE EXPIT (Week #".$week->week.", ".$week->start_date." to ".$week->end_date.")";
            $filter="DATE_FORMAT(tanggal,'%Y-%m-%d') BETWEEN '".$week->start_date."' and '".$week->end_date."'";             
        break;
        case "last_week":
            $filter_week="'".date("Y-m-d")."' BETWEEN start_date and end_date";
            $this_week=$db->select("id,week,DATE_FORMAT(start_date,'%Y-%m-%d') start_date,DATE_FORMAT(end_date,'%Y-%m-%d') end_date","week_periode")->where($filter_week)->get(0);
            $filter_last_week="start_date <'".$this_week->start_date."'";
            $last_week=$db->select("id,week,DATE_FORMAT(start_date,'%Y-%m-%d') start_date,DATE_FORMAT(end_date,'%Y-%m-%d') end_date","week_periode")->where($filter_last_week)->orderBy("start_date desc")->get(0);
            
            $judul="RITASE EXPIT (Week #".$last_week->week.", ".$last_week->start_date." to ".$last_week->end_date.")";
            $filter="DATE_FORMAT(tanggal,'%Y-%m-%d') BETWEEN '".$last_week->start_date."' and '".$last_week->end_date."'";             
        break;
        case "this_month":            
            $filter="DATE_FORMAT(tanggal,'%Y-%m')='".date("Y-m")."'";  
            $judul="RITASE EXPIT (".date("M Y").")";        
        break;
        
        case "last_month":     
            $tm=mktime(0,0,0,((int)date("m")-1),(int)date("d"),date("Y"));       
            $filter="DATE_FORMAT(tanggal,'%Y-%m')='".date("Y-m",$tm)."'";  
            $judul="RITASE EXPIT (".date("M Y",$tm).")";      
        break;
         case "custom":     
            if(trim($star_date)<>"" and trim($star_date)<>""){       
                $filter="DATE_FORMAT(tanggal,'%Y-%m-%d') BETWEEN '".$star_date."' and '".$end_date."'";
            }
            if(trim($star_date)<>"" and trim($star_date)==""){       
                $filter="DATE_FORMAT(tanggal,'%Y-%m-%d')='".$star_date."'";   
            }  
            if(trim($star_date)=="" and trim($star_date)<>""){       
                $filter="DATE_FORMAT(tanggal,'%Y-%m-%d')='".$star_date."'";   
            }    
            $lbl_tgl="(".$start_date." to ".$end_date.")";
            if($start_date==$end_date){
                $lbl_tgl="(".$start_date.")";
            }
            $judul="RITASE EXPIT $lbl_tgl";     
        break;
    }
    //echo $range.$filter;
    $list_data=array();
    if(trim($filter)<>""){
       // echo $filter;
        $stockpiling=array();
        $list_qry1=$db->select("dto.contractor_id,p.name,p.alias,p.rgb_color,sum(dtod.ritase) jml_ritase","daily_transit_ore_detail dtod
            inner join daily_transit_ore dto on dto.id=dtod.transit_ore_id
            inner join partner p on p.id=dto.contractor_id")
            ->where($filter." and dtod.tujuan_pengangkutan in('ETO','EFO') GROUP BY dto.contractor_id")->lim();//->orderBy($order)
            while($data = $db->fetchObject($list_qry1)){
                $stockpiling[$data->contractor_id]['contractor_id']=$data->contractor_id;
                $stockpiling[$data->contractor_id]['contractor_name']=$data->name;
                $stockpiling[$data->contractor_id]['contractor_alias']=$data->alias;
                $stockpiling[$data->contractor_id]['contractor_color']=$data->rgb_color;
                $stockpiling[$data->contractor_id]['jml_ritase']=$data->jml_ritase;
            }
        $crossmining=array();
        $list_qry2=$db->select("dto.contractor_id,p.name,p.alias,p.rgb_color,sum(dtod.ritase) jml_ritase","daily_transit_ore_detail dtod
            inner join daily_transit_ore dto on dto.id=dtod.transit_ore_id
            inner join partner p on p.id=dto.contractor_id")
            ->where($filter." and dtod.tujuan_pengangkutan in('BRG') GROUP BY dto.contractor_id")->lim();//->orderBy($order)
            while($data = $db->fetchObject($list_qry2)){
                $crossmining[$data->contractor_id]['contractor_id']=$data->contractor_id;
                $crossmining[$data->contractor_id]['contractor_name']=$data->name;
                $crossmining[$data->contractor_id]['contractor_alias']=$data->alias;
                $crossmining[$data->contractor_id]['contractor_color']=$data->rgb_color;
                $crossmining[$data->contractor_id]['jml_ritase']=$data->jml_ritase;
            }
        $list_data=array('stockpiling'=>$stockpiling,"crossmining"=>$crossmining,"title"=>$judul);
    }
    
   //echo "<pre>";print_r($hasil);echo "</pre>";exit;
    return $list_data;
}

public function getDailyWeightExpitByContractor($range,$star_date="",$end_date="") {
    /** Range :
     * today
     * yesterday
     * this_week
     * this_month
     * date_range -> harus isi star_date dan end_date
    */
    global $dcistem;
	$db   = $dcistem->getOption("framework/db");
    
	$modelsortir=new Adm_Sortir_Model();
    $master = new Master_Ref_Model();
    $settings= $master->settings();
    date_default_timezone_set($settings['production_time_zone']);
    
    $filter="";
    $judul="";
    switch($range){
        case "today":
            $filter="DATE_FORMAT(tanggal,'%Y-%m-%d')='".date("Y-m-d")."'";
            $judul="WEIGHT EXPIT (Today, ".date("Y-m-d").")"; 
        break;
        case "yesterday":
            $tm=mktime(0,0,0,date("m"),((int)date("d")-1),date("Y"));
            $filter="DATE_FORMAT(tanggal,'%Y-%m-%d')='".date("Y-m-d",$tm)."'";   
            $judul="WEIGHT EXPIT (Yesterday, ".date("Y-m-d",$tm).")";       
        break;
        case "this_week":
            $filter_week="'".date("Y-m-d")."' BETWEEN start_date and end_date";
            $week=$db->select("id,week,DATE_FORMAT(start_date,'%Y-%m-%d') start_date,DATE_FORMAT(end_date,'%Y-%m-%d') end_date","week_periode")->where($filter_week)->get(0);
            $judul="WEIGHT EXPIT (Week #".$week->week.", ".$week->start_date." to ".$week->end_date.")";
            $filter="DATE_FORMAT(tanggal,'%Y-%m-%d') BETWEEN '".$week->start_date."' and '".$week->end_date."'";             
        break;
        case "last_week":
            $filter_week="'".date("Y-m-d")."' BETWEEN start_date and end_date";
            $this_week=$db->select("id,week,DATE_FORMAT(start_date,'%Y-%m-%d') start_date,DATE_FORMAT(end_date,'%Y-%m-%d') end_date","week_periode")->where($filter_week)->get(0);
            $filter_last_week="start_date <'".$this_week->start_date."'";
            $last_week=$db->select("id,week,DATE_FORMAT(start_date,'%Y-%m-%d') start_date,DATE_FORMAT(end_date,'%Y-%m-%d') end_date","week_periode")->where($filter_last_week)->orderBy("start_date desc")->get(0);
            
            $judul="WEIGHT EXPIT (Week #".$last_week->week.", ".$last_week->start_date." to ".$last_week->end_date.")";
            $filter="DATE_FORMAT(tanggal,'%Y-%m-%d') BETWEEN '".$last_week->start_date."' and '".$last_week->end_date."'";             
        break;
        case "this_month":            
            $filter="DATE_FORMAT(tanggal,'%Y-%m')='".date("Y-m")."'";  
            $judul="WEIGHT EXPIT (".date("M Y").")";        
        break;
        
        case "last_month":     
            $tm=mktime(0,0,0,((int)date("m")-1),(int)date("d"),date("Y"));       
            $filter="DATE_FORMAT(tanggal,'%Y-%m')='".date("Y-m",$tm)."'";  
            $judul="WEIGHT EXPIT (".date("M Y",$tm).")";      
        break;
         case "custom":     
            if(trim($star_date)<>"" and trim($star_date)<>""){       
                $filter="DATE_FORMAT(tanggal,'%Y-%m-%d') BETWEEN '".$star_date."' and '".$end_date."'";
            }
            if(trim($star_date)<>"" and trim($star_date)==""){       
                $filter="DATE_FORMAT(tanggal,'%Y-%m-%d')='".$star_date."'";   
            }  
            if(trim($star_date)=="" and trim($star_date)<>""){       
                $filter="DATE_FORMAT(tanggal,'%Y-%m-%d')='".$star_date."'";   
            }    
            $lbl_tgl="(".$start_date." to ".$end_date.")";
            if($start_date==$end_date){
                $lbl_tgl="(".$start_date.")";
            }
            $judul="WEIGHT EXPIT $lbl_tgl";     
        break;
    }
    //echo $range.$filter;
    $list_data=array();
    if(trim($filter)<>""){
       // echo $filter;
        $stockpiling=array();
        $list_qry1=$db->select("dto.contractor_id,p.name,p.alias,p.rgb_color,sum(dtod.quantity) jml_quantity","daily_transit_ore_detail dtod
            inner join daily_transit_ore dto on dto.id=dtod.transit_ore_id
            inner join partner p on p.id=dto.contractor_id")
            ->where($filter." and dtod.tujuan_pengangkutan in('ETO','EFO') GROUP BY dto.contractor_id")->lim();//->orderBy($order)
            while($data = $db->fetchObject($list_qry1)){
                $stockpiling[$data->contractor_id]['contractor_id']=$data->contractor_id;
                $stockpiling[$data->contractor_id]['contractor_name']=$data->name;
                $stockpiling[$data->contractor_id]['contractor_alias']=$data->alias;
                $stockpiling[$data->contractor_id]['contractor_color']=$data->rgb_color;
                $stockpiling[$data->contractor_id]['jml_quantity']=$data->jml_quantity;
            }
        $crossmining=array();
        $list_qry2=$db->select("dto.contractor_id,p.name,p.alias,p.rgb_color,sum(dtod.quantity) jml_quantity","daily_transit_ore_detail dtod
            inner join daily_transit_ore dto on dto.id=dtod.transit_ore_id
            inner join partner p on p.id=dto.contractor_id")
            ->where($filter." and dtod.tujuan_pengangkutan in('BRG') GROUP BY dto.contractor_id")->lim();//->orderBy($order)
            while($data = $db->fetchObject($list_qry2)){
                $crossmining[$data->contractor_id]['contractor_id']=$data->contractor_id;
                $crossmining[$data->contractor_id]['contractor_name']=$data->name;
                $crossmining[$data->contractor_id]['contractor_alias']=$data->alias;
                $crossmining[$data->contractor_id]['contractor_color']=$data->rgb_color;
                $crossmining[$data->contractor_id]['jml_quantity']=$data->jml_quantity;
            }
        $list_data=array('stockpiling'=>$stockpiling,"crossmining"=>$crossmining,"title"=>$judul);
    }
    
   //echo "<pre>";print_r($hasil);echo "</pre>";exit;
    return $list_data;
}
public function getDailyRitaseBargingByContractor($range,$star_date="",$end_date="") {
    /** Range :
     * today
     * yesterday
     * this_week
     * this_month
     * date_range -> harus isi star_date dan end_date
    */
    global $dcistem;
	$db   = $dcistem->getOption("framework/db");
    
	$modelsortir=new Adm_Sortir_Model();
    $master = new Master_Ref_Model();
    $settings= $master->settings();
    date_default_timezone_set($settings['production_time_zone']);
    
    $filter="";
    $judul="";
    switch($range){
        case "today":
            $filter="DATE_FORMAT(tanggal,'%Y-%m-%d')='".date("Y-m-d")."'";
            $judul="RITASE BARGING (Today, ".date("Y-m-d").")";  
        break;
        case "yesterday":
            $tm=mktime(0,0,0,date("m"),((int)date("d")-1),date("Y"));
            $filter="DATE_FORMAT(tanggal,'%Y-%m-%d')='".date("Y-m-d",$tm)."'";    
            $judul="RITASE BARGING (Yesterday, ".date("Y-m-d",$tm).")";         
        break;
        case "this_week":
            $filter_week="'".date("Y-m-d")."' BETWEEN start_date and end_date";
            $week=$db->select("id,week,DATE_FORMAT(start_date,'%Y-%m-%d') start_date,DATE_FORMAT(end_date,'%Y-%m-%d') end_date","week_periode")->where($filter_week)->get(0);
            $judul="RITASE BARGING (Week #".$week->week.", ".$week->start_date." to ".$week->end_date.")";
            $filter="DATE_FORMAT(tanggal,'%Y-%m-%d') BETWEEN '".$week->start_date."' and '".$week->end_date."'";             
        break;
        case "last_week":
            $filter_week="'".date("Y-m-d")."' BETWEEN start_date and end_date";
            $this_week=$db->select("id,week,DATE_FORMAT(start_date,'%Y-%m-%d') start_date,DATE_FORMAT(end_date,'%Y-%m-%d') end_date","week_periode")->where($filter_week)->get(0);
            $filter_last_week="start_date <'".$this_week->start_date."'";
            $last_week=$db->select("id,week,DATE_FORMAT(start_date,'%Y-%m-%d') start_date,DATE_FORMAT(end_date,'%Y-%m-%d') end_date","week_periode")->where($filter_last_week)->orderBy("start_date desc")->get(0);
            
            $judul="RITASE BARGING (Week #".$last_week->week.", ".$last_week->start_date." to ".$last_week->end_date.")";
            $filter="DATE_FORMAT(tanggal,'%Y-%m-%d') BETWEEN '".$last_week->start_date."' and '".$last_week->end_date."'";             
        break;
        case "this_month":            
            $filter="DATE_FORMAT(tanggal,'%Y-%m')='".date("Y-m")."'";   
            $judul="RITASE BARGING (".date("M Y").")";         
        break;
        case "last_month":     
            $tm=mktime(0,0,0,((int)date("m")-1),(int)date("d"),date("Y"));       
            $filter="DATE_FORMAT(tanggal,'%Y-%m')='".date("Y-m",$tm)."'";  
            $judul="RITASE BARGING (".date("M Y",$tm).")";      
        break;
         case "custom":     
            if(trim($star_date)<>"" and trim($star_date)<>""){       
                $filter="DATE_FORMAT(tanggal,'%Y-%m-%d') BETWEEN '".$star_date."' and '".$end_date."'";
            }
            if(trim($star_date)<>"" and trim($star_date)==""){       
                $filter="DATE_FORMAT(tanggal,'%Y-%m-%d')='".$star_date."'";   
            }  
            if(trim($star_date)=="" and trim($star_date)<>""){       
                $filter="DATE_FORMAT(tanggal,'%Y-%m-%d')='".$star_date."'";   
            }  
            $lbl_tgl="(".$start_date." to ".$end_date.")";
            if($start_date==$end_date){
                $lbl_tgl="(".$start_date.")";
            }
            $judul="RITASE BARGING $lbl_tgl";    
        break;
    }
    $list_data=array();
    if(trim($filter)<>""){
        
        $crossmining=array();
        $list_qry2=$db->select("dto.contractor_id,p.name,p.alias,p.rgb_color,sum(dtod.ritase) jml_ritase","daily_transit_ore_detail dtod
            inner join daily_transit_ore dto on dto.id=dtod.transit_ore_id
            inner join partner p on p.id=dto.contractor_id")
            ->where($filter." and dtod.tujuan_pengangkutan in('BRG') GROUP BY dto.contractor_id")->lim();//->orderBy($order)
            while($data = $db->fetchObject($list_qry2)){
                $crossmining[$data->contractor_id]['contractor_id']=$data->contractor_id;
                $crossmining[$data->contractor_id]['contractor_name']=$data->name;
                $crossmining[$data->contractor_id]['contractor_alias']=$data->alias;
                $crossmining[$data->contractor_id]['contractor_color']=$data->rgb_color;
                $crossmining[$data->contractor_id]['jml_ritase']=$data->jml_ritase;
            }
        $rehandling=array();
        $list_qry1=$db->select("dro.contractor_id,p.name,p.alias,p.rgb_color,sum(drod.ritase) jml_ritase","daily_rehandling_ore_detail drod
            inner join daily_rehandling_ore dro on dro.id=drod.rehandling_ore_id
            inner join partner p on p.id=dro.contractor_id")
            ->where($filter." GROUP BY dro.contractor_id")->lim();//->orderBy($order)
            while($data = $db->fetchObject($list_qry1)){
                $rehandling[$data->contractor_id]['contractor_id']=$data->contractor_id;
                $rehandling[$data->contractor_id]['contractor_name']=$data->name;
                $rehandling[$data->contractor_id]['contractor_alias']=$data->alias;
                $rehandling[$data->contractor_id]['contractor_color']=$data->rgb_color;
                $rehandling[$data->contractor_id]['jml_ritase']=$data->jml_ritase;
            }
        $list_data=array('crossmining'=>$crossmining,"rehandling"=>$rehandling,"title"=>$judul);
    }
    
   //echo "<pre>";print_r($hasil);echo "</pre>";exit;
    return $list_data;
}
public function getDailyWeightBargingByContractor($range,$star_date="",$end_date="") {
    /** Range :
     * today
     * yesterday
     * this_week
     * this_month
     * date_range -> harus isi star_date dan end_date
    */
    global $dcistem;
	$db   = $dcistem->getOption("framework/db");
    
	$modelsortir=new Adm_Sortir_Model();
    $master = new Master_Ref_Model();
    $settings= $master->settings();
    date_default_timezone_set($settings['production_time_zone']);
    
    $filter="";
    $judul="";
    switch($range){
        case "today":
            $filter="DATE_FORMAT(tanggal,'%Y-%m-%d')='".date("Y-m-d")."'";
            $judul="WEIGHT BARGING (Today, ".date("Y-m-d").")";  
        break;
        case "yesterday":
            $tm=mktime(0,0,0,date("m"),((int)date("d")-1),date("Y"));
            $filter="DATE_FORMAT(tanggal,'%Y-%m-%d')='".date("Y-m-d",$tm)."'";    
            $judul="WEIGHT BARGING (Yesterday, ".date("Y-m-d",$tm).")";         
        break;
        case "this_week":
            $filter_week="'".date("Y-m-d")."' BETWEEN start_date and end_date";
            $week=$db->select("id,week,DATE_FORMAT(start_date,'%Y-%m-%d') start_date,DATE_FORMAT(end_date,'%Y-%m-%d') end_date","week_periode")->where($filter_week)->get(0);
            $judul="WEIGHT BARGING (Week #".$week->week.", ".$week->start_date." to ".$week->end_date.")";
            $filter="DATE_FORMAT(tanggal,'%Y-%m-%d') BETWEEN '".$week->start_date."' and '".$week->end_date."'";             
        break;
        case "last_week":
            $filter_week="'".date("Y-m-d")."' BETWEEN start_date and end_date";
            $this_week=$db->select("id,week,DATE_FORMAT(start_date,'%Y-%m-%d') start_date,DATE_FORMAT(end_date,'%Y-%m-%d') end_date","week_periode")->where($filter_week)->get(0);
            $filter_last_week="start_date <'".$this_week->start_date."'";
            $last_week=$db->select("id,week,DATE_FORMAT(start_date,'%Y-%m-%d') start_date,DATE_FORMAT(end_date,'%Y-%m-%d') end_date","week_periode")->where($filter_last_week)->orderBy("start_date desc")->get(0);
            
            $judul="WEIGHT BARGING (Week #".$last_week->week.", ".$last_week->start_date." to ".$last_week->end_date.")";
            $filter="DATE_FORMAT(tanggal,'%Y-%m-%d') BETWEEN '".$last_week->start_date."' and '".$last_week->end_date."'";             
        break;
        case "this_month":            
            $filter="DATE_FORMAT(tanggal,'%Y-%m')='".date("Y-m")."'";   
            $judul="WEIGHT BARGING (".date("M Y").")";         
        break;
        case "last_month":     
            $tm=mktime(0,0,0,((int)date("m")-1),(int)date("d"),date("Y"));       
            $filter="DATE_FORMAT(tanggal,'%Y-%m')='".date("Y-m",$tm)."'";  
            $judul="WEIGHT BARGING (".date("M Y",$tm).")";      
        break;
         case "custom":     
            if(trim($star_date)<>"" and trim($star_date)<>""){       
                $filter="DATE_FORMAT(tanggal,'%Y-%m-%d') BETWEEN '".$star_date."' and '".$end_date."'";
            }
            if(trim($star_date)<>"" and trim($star_date)==""){       
                $filter="DATE_FORMAT(tanggal,'%Y-%m-%d')='".$star_date."'";   
            }  
            if(trim($star_date)=="" and trim($star_date)<>""){       
                $filter="DATE_FORMAT(tanggal,'%Y-%m-%d')='".$star_date."'";   
            }  
            $lbl_tgl="(".$start_date." to ".$end_date.")";
            if($start_date==$end_date){
                $lbl_tgl="(".$start_date.")";
            }
            $judul="WEIGHT BARGING $lbl_tgl";    
        break;
    }
    $list_data=array();
    if(trim($filter)<>""){
        
        $crossmining=array();
        $list_qry2=$db->select("dto.contractor_id,p.name,p.alias,p.rgb_color,sum(dtod.quantity) jml_quantity","daily_transit_ore_detail dtod
            inner join daily_transit_ore dto on dto.id=dtod.transit_ore_id
            inner join partner p on p.id=dto.contractor_id")
            ->where($filter." and dtod.tujuan_pengangkutan in('BRG') GROUP BY dto.contractor_id")->lim();//->orderBy($order)
            while($data = $db->fetchObject($list_qry2)){
                $crossmining[$data->contractor_id]['contractor_id']=$data->contractor_id;
                $crossmining[$data->contractor_id]['contractor_name']=$data->name;
                $crossmining[$data->contractor_id]['contractor_alias']=$data->alias;
                $crossmining[$data->contractor_id]['contractor_color']=$data->rgb_color;
                $crossmining[$data->contractor_id]['jml_quantity']=$data->jml_quantity;
            }
        $rehandling=array();
        $list_qry1=$db->select("dro.contractor_id,p.name,p.alias,p.rgb_color,sum(drod.quantity) jml_quantity","daily_rehandling_ore_detail drod
            inner join daily_rehandling_ore dro on dro.id=drod.rehandling_ore_id
            inner join partner p on p.id=dro.contractor_id")
            ->where($filter." GROUP BY dro.contractor_id")->lim();//->orderBy($order)
            while($data = $db->fetchObject($list_qry1)){
                $rehandling[$data->contractor_id]['contractor_id']=$data->contractor_id;
                $rehandling[$data->contractor_id]['contractor_name']=$data->name;
                $rehandling[$data->contractor_id]['contractor_alias']=$data->alias;
                $rehandling[$data->contractor_id]['contractor_color']=$data->rgb_color;
                $rehandling[$data->contractor_id]['jml_quantity']=$data->jml_quantity;
            }
        $list_data=array('crossmining'=>$crossmining,"rehandling"=>$rehandling,"title"=>$judul);
    }
    
   //echo "<pre>";print_r($hasil);echo "</pre>";exit;
    return $list_data;
}
  
}