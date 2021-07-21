<?php
/**
 * @package Admin
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Dashboard_All_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
        global $dcistem;
        
        $tpl  = new View("dashboard_production");
        $db   = $dcistem->getOption("framework/db"); 
        $production     = new Recap_Production_Model();
        $current_year=(int)date("Y");
        $my=$db->select("min(CAST(DATE_FORMAT(tanggal,'%Y') AS INT)) min_tahun","daily_transit_ore")->get(0);
        $min_year=(int)$my->min_tahun<($current_year-10)?($current_year-10):(int)$my->min_tahun;
        
        $list_years=array();
        for ($i=$current_year;$i>=$min_year;$i--){
            $lbl=$i;
            if($i==$current_year){
                $lbl="This Year";
            }
            if($i==($current_year-1)){
                $lbl="Last Year";
            }
            $list_years[$i]=$lbl;
            
        }
        $tpl->list_years      = $list_years;
        
        //$production->generateMonthlyRecapShipment("2020");
        //$login_as = $_SESSION["framework"]
         //echo "<pre>";print_r($_SESSION["framework"]["current_user"]);echo "</pre>";
        
        //echo "<pre>";print_r($_SESSION["framework"]);echo "</pre>";
        $recap_sh= $production->getRecapShipmentByMonth("2020",12);
        //echo "<pre>";print_r($recap_sh);echo "</pre>";
        $current_level=$_SESSION["framework"]["user_level"];
        $tpl->current_level      = $current_level;
        $tpl->url_dashboard      = url::current("dashboard");
        $this->tpl->content = $tpl;
        $this->tpl->render();
	    
   }
   public function dashboard($kategori) {
        global $dcistem;
        $db   = $dcistem->getOption("framework/db"); 
        $master         =new Master_Ref_Model();
        $settings= $master->settings();
        date_default_timezone_set($settings['production_time_zone']);
        $production     = new Recap_Production_Model();
        $rekap=new Adm_Recap_Model();
        $partner = new List_Partner_Model(); 
        $list_contractor_active=$partner->getListActiveContractor();
        $this->settings =$master->settings();
        switch($kategori){
             case "daily_production_grafik":
                $tpl  = new View("daily_production_grafik");
                $daily =new Daily_Production_Model();
                
                //$list_contractor_active=array(158=>"LCP",160=>"BKM",161=>"ABC",165=>"GSI");
                $range      =isset($_GET['date_range'])?$_GET['date_range']:"yesterday";
                $kemarin=mktime(0,0,0,date("m"),((int)date("d")-1),date("Y"));  
                $start_date =isset($_GET['start_date'])?$_GET['start_date']:date("Y-m-d",$kemarin);
                $end_date =isset($_GET['end_date'])?$_GET['end_date']:date("Y-m-d",$kemarin);
                //echo $range;
                $daily_expit=$daily->getDailyRitaseExpitByContractor($range,$start_date,$end_date);   
                $daily_weight_expit=$daily->getDailyWeightExpitByContractor($range,$start_date,$end_date);
                //echo "<pre>";print_r($daily_expit);echo "</pre>";            
                $daily_barging=$daily->getDailyRitaseBargingByContractor($range,$start_date,$end_date);  
                $daily_weight_barging=$daily->getDailyWeightBargingByContractor($range,$start_date,$end_date);
                //echo "<pre>";print_r($daily_barging);echo "</pre>";   
                $bar_chart_expit=array();
                $labels=array();
                $values_stockpiling=array();
                $values_crossmining=array();
                $values_rehandling=array();
                foreach($list_contractor_active as $key2=>$value2){
                    $labels[]=$value2['alias'];
                    $jml_ritase_stockpiling=isset($daily_expit['stockpiling'][$key2]['jml_ritase'])?$daily_expit['stockpiling'][$key2]['jml_ritase']:0;
                    $values_stockpiling[]=$jml_ritase_stockpiling;
                    $jml_ritase_crossmining=isset($daily_expit['crossmining'][$key2]['jml_ritase'])?$daily_expit['crossmining'][$key2]['jml_ritase']:0;;
                    $values_crossmining[]=$jml_ritase_crossmining;
                    
                    $jml_ritase_rehandling=isset($daily_barging['rehandling'][$key2]['jml_ritase'])?$daily_barging['rehandling'][$key2]['jml_ritase']:0;;
                    $values_rehandling[]=$jml_ritase_rehandling;
                    
                    $list_contractor_active[$key2]['jml_ritase']=$jml_ritase_stockpiling+$jml_ritase_crossmining;
                    $list_contractor_active[$key2]['jml_ritase_barging']=$jml_ritase_rehandling+$jml_ritase_crossmining;
                    
                }
                $vals=array_unique(array_merge($values_stockpiling,$values_crossmining,$values_rehandling));
                $max_value=max($vals);
                $y_jml_segmen=10;
                $mod=$max_value%$y_jml_segmen;
                $y_max=$max_value+($y_jml_segmen-$mod);
                
                $tpl->y_max=$y_max;
                $tpl->y_step=$y_max/10;
                $tpl->list_contractor_active=$list_contractor_active;
                
                $j=0; 
                $bar_chart_expit['labels']=$labels;
                $bar_chart_expit['datasets'][$j]['label']="Stock Piling"; 
                $bar_chart_expit['datasets'][$j]['backgroundColor']="rgba(102, 255, 51, 1)";
                $bar_chart_expit['datasets'][$j]['borderColor']="rgba(102, 255, 51, 1)";
                $bar_chart_expit['datasets'][$j]['borderWidth']=0.5;
                $bar_chart_expit['datasets'][$j]['data']=$values_stockpiling; 
                $bar_chart_expit['datasets'][$j]['fill']=false; 
                $j++;                
                $bar_chart_expit['datasets'][$j]['label']="Cross Mining"; 
                $bar_chart_expit['datasets'][$j]['backgroundColor']="rgba(0, 0, 204, 1)";
                $bar_chart_expit['datasets'][$j]['borderColor']="rgba(0, 0, 204, 1)";
                $bar_chart_expit['datasets'][$j]['borderWidth']=0.5;
                $bar_chart_expit['datasets'][$j]['data']=$values_crossmining; 
                $bar_chart_expit['datasets'][$j]['fill']=false; 
                
                $tpl->bar_chart_expit=$bar_chart_expit;
                
                /** barging */
                            
                $bar_chart_barging=array();
                $j=0; 
                $bar_chart_barging['labels']=$labels;
                $bar_chart_barging['datasets'][$j]['label']="Cross Mining"; 
                $bar_chart_barging['datasets'][$j]['backgroundColor']="rgba(0, 0, 204, 1)";
                $bar_chart_barging['datasets'][$j]['borderColor']="rgba(0, 0, 204, 1)";
                $bar_chart_barging['datasets'][$j]['borderWidth']=0.5;
                $bar_chart_barging['datasets'][$j]['data']=$values_crossmining; 
                $bar_chart_barging['datasets'][$j]['fill']=false; 
                $j++;  
                $bar_chart_barging['datasets'][$j]['label']="Rehandling"; 
                $bar_chart_barging['datasets'][$j]['backgroundColor']="rgba(204, 0, 204, 1)";
                $bar_chart_barging['datasets'][$j]['borderColor']="rgba(204, 0, 204, 1)";
                $bar_chart_barging['datasets'][$j]['borderWidth']=0.5;
                $bar_chart_barging['datasets'][$j]['data']=$values_rehandling; 
                $bar_chart_barging['datasets'][$j]['fill']=false; 
                             
                
                
                $tpl->bar_chart_barging=$bar_chart_barging;
                /** end of barging */
              
                $tpl->judul_expit=$daily_expit['title'];
                $tpl->judul_barging=$daily_barging['title'];
                
                //WEIGHT DAILY
                
                $bar_chart_weight_expit=array();
                $labels=array();
                $values_weight_stockpiling=array();
                $values_weight_crossmining=array();
                $values_weight_rehandling=array();
                foreach($list_contractor_active as $key2=>$value2){
                    $labels[]=$value2['alias'];
                    $jml_weight_stockpiling=isset($daily_weight_expit['stockpiling'][$key2]['jml_quantity'])?$daily_weight_expit['stockpiling'][$key2]['jml_quantity']:0;
                    $values_weight_stockpiling[]=$jml_weight_stockpiling;
                    $jml_weight_crossmining=isset($daily_weight_expit['crossmining'][$key2]['jml_quantity'])?$daily_weight_expit['crossmining'][$key2]['jml_quantity']:0;;
                    $values_weight_crossmining[]=$jml_weight_crossmining;
                    
                    $jml_weight_rehandling=isset($daily_weight_barging['rehandling'][$key2]['jml_quantity'])?$daily_weight_barging['rehandling'][$key2]['jml_quantity']:0;;
                    $values_weight_rehandling[]=$jml_weight_rehandling;
                    
                    $list_contractor_active[$key2]['jml_quantity']=$jml_weight_stockpiling+$jml_weight_crossmining;
                    $list_contractor_active[$key2]['jml_quantity_barging']=$jml_weight_rehandling+$jml_weight_crossmining;
                    
                }
                $vals_weight=array_unique(array_merge($values_weight_stockpiling,$values_weight_crossmining,$values_weight_rehandling));
                $max_value_weight=max($vals_weight);
                $y_jml_segmen=10;
                $mod=$max_value_weight%$y_jml_segmen;
                $y_max_weight=$max_value_weight+($y_jml_segmen-$mod);
                
                $tpl->y_max_weight=$y_max_weight;
                $tpl->y_step_weight=$y_max_weight/10;
                $tpl->list_contractor_active=$list_contractor_active;
                
                $j=0; 
                $bar_chart_weight_expit['labels']=$labels;
                $bar_chart_weight_expit['datasets'][$j]['label']="Stock Piling"; 
                $bar_chart_weight_expit['datasets'][$j]['backgroundColor']="rgba(102, 255, 51, 1)";
                $bar_chart_weight_expit['datasets'][$j]['borderColor']="rgba(102, 255, 51, 1)";
                $bar_chart_weight_expit['datasets'][$j]['borderWidth']=0.5;
                $bar_chart_weight_expit['datasets'][$j]['data']=$values_weight_stockpiling; 
                $bar_chart_weight_expit['datasets'][$j]['fill']=false; 
                $j++;                
                $bar_chart_weight_expit['datasets'][$j]['label']="Cross Mining"; 
                $bar_chart_weight_expit['datasets'][$j]['backgroundColor']="rgba(0, 0, 204, 1)";
                $bar_chart_weight_expit['datasets'][$j]['borderColor']="rgba(0, 0, 204, 1)";
                $bar_chart_weight_expit['datasets'][$j]['borderWidth']=0.5;
                $bar_chart_weight_expit['datasets'][$j]['data']=$values_weight_crossmining; 
                $bar_chart_weight_expit['datasets'][$j]['fill']=false; 
                
                $tpl->bar_chart_weight_expit=$bar_chart_weight_expit;
                
                /** barging */
                            
                $bar_chart_weight_barging=array();
                $j=0; 
                $bar_chart_weight_barging['labels']=$labels;
                $bar_chart_weight_barging['datasets'][$j]['label']="Cross Mining"; 
                $bar_chart_weight_barging['datasets'][$j]['backgroundColor']="rgba(0, 0, 204, 1)";
                $bar_chart_weight_barging['datasets'][$j]['borderColor']="rgba(0, 0, 204, 1)";
                $bar_chart_weight_barging['datasets'][$j]['borderWidth']=0.5;
                $bar_chart_weight_barging['datasets'][$j]['data']=$values_weight_crossmining; 
                $bar_chart_weight_barging['datasets'][$j]['fill']=false; 
                $j++;  
                $bar_chart_weight_barging['datasets'][$j]['label']="Rehandling"; 
                $bar_chart_weight_barging['datasets'][$j]['backgroundColor']="rgba(204, 0, 204, 1)";
                $bar_chart_weight_barging['datasets'][$j]['borderColor']="rgba(204, 0, 204, 1)";
                $bar_chart_weight_barging['datasets'][$j]['borderWidth']=0.5;
                $bar_chart_weight_barging['datasets'][$j]['data']=$values_weight_rehandling; 
                $bar_chart_weight_barging['datasets'][$j]['fill']=false; 
                             
                
                
                $tpl->bar_chart_weight_barging=$bar_chart_weight_barging;
                /** end of barging */
              
                $tpl->judul_weight_expit=$daily_weight_expit['title'];
                $tpl->judul_weight_barging=$daily_weight_barging['title'];
                
                
                
                
                
            break;
            case "daily_shipment_grafik":
                $tpl  = new View("daily_shipment_grafik");
                $dshipment =new Daily_Shipment_Model();
                $range      =isset($_GET['date_range'])?$_GET['date_range']:"yesterday";
                $kemarin=mktime(0,0,0,date("m"),((int)date("d")-1),date("Y"));  
                $start_date =isset($_GET['start_date'])?$_GET['start_date']:date("Y-m-d",$kemarin);
                $end_date =isset($_GET['end_date'])?$_GET['end_date']:date("Y-m-d",$kemarin);
                $s=$dshipment->getDailyShipmentContractorByBarge($range,$start_date,$end_date);
                $labels                 =array();
                $values_barge_progress  =array();
                $vals_ritase            =array();                
                $bar_chart_ritase_shipment=array();
                
                $values_ids_progress    =array(); 
                $vals_ids_progress      =array();           
                $bar_chart_ids_progress =array();                
                foreach($s['list_progress'] as $key1=>$value1){
                    $labels[]=$value1['barge_name'];
                    $vals_ritase[]=$value1['total_ritase'];
                    $bar_chart_ritase_shipment['labels']=$labels;
                    
                    $vals_ids_progress[]=$value1['total_intermediate_draugh_survey'];                    
                    $bar_chart_ids_progress['labels']=$labels;
                    
                    $j=0;
                    foreach($value1['data'] as $key2=>$value2){                       
                        $values_barge_progress[$key2][]=$value2['jml_ritase'];
                        $bar_chart_ritase_shipment['datasets'][$j]['label']=$list_contractor_active[$key2]['alias']; 
                        $bar_chart_ritase_shipment['datasets'][$j]['backgroundColor']=$list_contractor_active[$key2]['rgb_color']; ;
                        $bar_chart_ritase_shipment['datasets'][$j]['borderColor']="rgba(102, 255, 51, 1)";
                        $bar_chart_ritase_shipment['datasets'][$j]['borderWidth']=0.5;
                        $bar_chart_ritase_shipment['datasets'][$j]['data']=$values_barge_progress[$key2]; 
                        $bar_chart_ritase_shipment['datasets'][$j]['fill']=false; 
                        
                        $values_ids_progress[$key2][]=$value2['jml_intermediate_draugh_survey'];
                        $bar_chart_ids_progress['datasets'][$j]['label']=$list_contractor_active[$key2]['alias']; 
                        $bar_chart_ids_progress['datasets'][$j]['backgroundColor']=$list_contractor_active[$key2]['rgb_color']; ;
                        $bar_chart_ids_progress['datasets'][$j]['borderColor']="rgba(102, 255, 51, 1)";
                        $bar_chart_ids_progress['datasets'][$j]['borderWidth']=0.5;
                        $bar_chart_ids_progress['datasets'][$j]['data']=$values_ids_progress[$key2]; 
                        $bar_chart_ids_progress['datasets'][$j]['fill']=false; 
                        
                        $j++;
                    }
                    
                }
                $ritase_axis=$dshipment->getStepAndMaxAxis($vals_ritase,10);    
                $tpl->y_max_ritase=$ritase_axis['max'];
                $tpl->y_step_ritase=$ritase_axis['step'];
                $tpl->bar_chart_ritase_shipment_title="Ritase - Barging On Progress ".$s['title'];
                $tpl->bar_chart_ritase_shipment=$bar_chart_ritase_shipment;
                /** IDS */
               // echo "<pre>";print_r($s);echo "</pre>";
                $ids_progress_axis=$dshipment->getStepAndMaxAxis($vals_ids_progress,10);    
                $tpl->y_max_ids_progress=$ids_progress_axis['max'];
                $tpl->y_step_ids_progress=$ids_progress_axis['step'];
                $tpl->bar_chart_ids_progress_title="Intermediate Draugh Survey - Barging On Progress<br />".$s['title'];
                $tpl->bar_chart_ids_progress=$bar_chart_ids_progress;
                /** END OF IDS */
                
                
               // echo "<pre>";print_r($s);echo "</pre>";
                /** SHIPMENT COMPLETED */
                $labels                     =array();
                $values_ritase_completed    =array();
                $vals_ritase_completed      =array();           
                $bar_chart_ritase_completed =array();   
                
                $values_ids_completed    =array(); 
                $vals_ids_completed      =array();           
                $bar_chart_ids_completed =array();                    
                foreach($s['list_completed'] as $key3=>$value3){
                    $labels[]=$value3['barge_name'];
                    $vals_ritase_completed[]=$value3['total_ritase'];
                    $bar_chart_ritase_completed['labels']=$labels;
                    
                    $vals_ids_completed[]=$value3['total_intermediate_draugh_survey'];                    
                    $bar_chart_ids_completed['labels']=$labels;
                    
                    $j=0;
                    //$values_ids_completed=array();
                    foreach($value3['data'] as $key4=>$value4){                       
                        $values_ritase_completed[$key4][]=$value4['jml_ritase'];
                        $bar_chart_ritase_completed['datasets'][$j]['label']=$list_contractor_active[$key4]['alias']; 
                        $bar_chart_ritase_completed['datasets'][$j]['backgroundColor']=$list_contractor_active[$key4]['rgb_color']; ;
                        $bar_chart_ritase_completed['datasets'][$j]['borderColor']="rgba(102, 255, 51, 1)";
                        $bar_chart_ritase_completed['datasets'][$j]['borderWidth']=0.5;
                        $bar_chart_ritase_completed['datasets'][$j]['data']=$values_ritase_completed[$key4]; 
                        $bar_chart_ritase_completed['datasets'][$j]['fill']=false;
                        
                        $values_ids_completed[$key4][]=$value4['jml_intermediate_draugh_survey'];
                        $bar_chart_ids_completed['datasets'][$j]['label']=$list_contractor_active[$key4]['alias']; 
                        $bar_chart_ids_completed['datasets'][$j]['backgroundColor']=$list_contractor_active[$key4]['rgb_color']; ;
                        $bar_chart_ids_completed['datasets'][$j]['borderColor']="rgba(102, 255, 51, 1)";
                        $bar_chart_ids_completed['datasets'][$j]['borderWidth']=0.5;
                        $bar_chart_ids_completed['datasets'][$j]['data']=$values_ids_completed[$key4]; 
                        $bar_chart_ids_completed['datasets'][$j]['fill']=false; 
                         
                        $j++;
                    }
                    //echo "<pre>";print_r($values_ids_completed);echo "</pre>";
                }
                $rc_axis=$dshipment->getStepAndMaxAxis($vals_ritase_completed,10);    
                $tpl->y_max_ritase_completed    =$rc_axis['max'];
                $tpl->y_step_ritase_completed   =$rc_axis['step'];
                $tpl->bar_chart_ritase_completed_title="Ritase - Barging Completed ".$s['title'];
                $tpl->bar_chart_ritase_completed=$bar_chart_ritase_completed;
                
                 /** IDS */
                $ids_completed_axis=$dshipment->getStepAndMaxAxis($vals_ids_completed,10);    
                $tpl->y_max_ids_completed=$ids_completed_axis['max'];
                $tpl->y_step_ids_completed=$ids_completed_axis['step'];
                $tpl->bar_chart_ids_completed_title="Intermediate Draugh Survey - Barging Completed<br />".$s['title'];
                $tpl->bar_chart_ids_completed=$bar_chart_ids_completed;
                /** END OF IDS */
               /** END OF SHIPMENT COMPLETED */
                
            break;
            case "dashboard_production_grafik":
                $tpl  = new View("dashboard_production_grafik");
                
                /** cross expit ore */
                $year=isset($_GET['year'])?$_GET['year']:date("Y");
                $transit_commulative=$production->getCommulativeRecapExpitOreStockpiling($year);
                //echo "<pre>";print_r($transit_commulative['data']);echo "</pre>";
                $tpl->data_transit_ore=$transit_commulative['data'];
                $transit_datas  =array();
                $transit_labels =array();
                $transit_colors  =array();
                $list_partner   = array();
                foreach($transit_commulative['data'] as $key=>$value){
                    if(!array_key_exists($key,$list_partner)){
                        $list_partner[$key]['partner_name']=$value['partner_name'];
                        $list_partner[$key]['alias']=$value['alias'];
                        $list_partner[$key]['color']=$value['color'];
                    }
                    
                    $transit_labels[]=$value['alias'];
                    $persentase=round(($value['jml_quantity']/$transit_commulative['total_quantity'])*100,2);
                    $transit_datas[]    =$persentase;
                    $transit_colors[]   =$value['color'];
                }
                
                foreach($list_contractor_active as $key2=>$value2){
                    $persentase=0;
                    if(isset($transit_commulative['data'][$key2]['jml_quantity']) and isset($transit_commulative['total_quantity'])){
                        $persentase=round(($transit_commulative['data'][$key2]['jml_quantity']/$transit_commulative['total_quantity'])*100,2);
                    }
                    $transit_datas[]    =$persentase;
                    $transit_colors[]   =$value2['color'];
                    
                    $list_contractor_active[$key2]['jml_stockpiling']=$jml_ritase_stockpiling+$jml_ritase_crossmining;
                    $list_contractor_active[$key2]['jml_ritase_barging']=$jml_ritase_rehandling+$jml_ritase_crossmining;
                    
                }
                $tpl->list_contractor_active=$list_contractor_active;
                
                $donut_chart_cumtransit['labels']=$transit_labels;
                $donut_chart_cumtransit['datasets']['backgroundColor']=$transit_colors;//array("rgba(255, 99, 132, 0.8)","rgba(54, 162, 235, 0.7)");
                $donut_chart_cumtransit['datasets']['data']=$transit_datas; 
               // echo "<pre>";print_r($donut_chart_cumtransit['data']);echo "</pre>";
                $tpl->donut_chart_cumtransit=$donut_chart_cumtransit;
                
                /** ================== Cross mining (PIT To Barge) =====================*/
                $transit_commulative_crossmining=$production->getCommulativeRecapExpitOreCrossMining($year);
                //echo "<pre>";print_r($transit_commulative_crossmining['data']);echo "</pre>";
                $tpl->data_transit_crossmining=$transit_commulative_crossmining['data'];
                $crossmining_datas  =array();
                $crossmining_labels =array();
                $crossmining_colors  =array();
                foreach($transit_commulative_crossmining['data'] as $key1=>$value1){
                    if(!array_key_exists($key1,$list_partner)){
                        $list_partner[$key1]['partner_name']=$value1['partner_name'];
                        $list_partner[$key1]['alias']=$value1['alias'];
                        $list_partner[$key1]['color']=$value1['color'];
                    }
                    $crossmining_labels[]=$value1['alias'];
                    $persentase=round(($value1['jml_quantity']/$transit_commulative_crossmining['total_quantity'])*100,2);
                    $crossmining_datas[]    =$persentase;
                    $crossmining_colors[]   =$value1['color'];
                }
                //print_r($crossmining_colors);
                $donut_chart_cumtcrossmining['labels']=$crossmining_labels;
                $donut_chart_cumtcrossmining['datasets']['backgroundColor']=$crossmining_colors;//array("rgba(255, 99, 132, 0.8)","rgba(54, 162, 235, 0.7)");
                $donut_chart_cumtcrossmining['datasets']['data']=$crossmining_datas; 
                $tpl->donut_chart_cumtcrossmining=$donut_chart_cumtcrossmining;
                /** ====================================================================== */
                
                /** ================== Rehandling (ETO/EFO To Barge) =====================*/
                $rehandling_commulative=$production->getCommulativeRecapRehandling($year);
                //echo "<pre>";print_r($rehandling_commulative);echo "</pre>";
                $tpl->rehandling_commulative=$rehandling_commulative['data'];
                $rehandling_datas  =array();
                $rehandling_labels =array();
                $rehandling_colors  =array();
                
                foreach($rehandling_commulative['data'] as $key2=>$value2){
                    if(!array_key_exists($key2,$list_partner)){
                        $list_partner[$key2]['partner_name']=$value2['partner_name'];
                        $list_partner[$key2]['alias']=$value2['alias'];
                        $list_partner[$key2]['color']=$value2['color'];
                    }
                    $rehandling_labels[]=$value2['alias'];
                    $persentase=round(($value2['jml_quantity']/$rehandling_commulative['total_quantity'])*100,2);
                    $rehandling_datas[]    =$persentase;
                    $rehandling_colors[]   =$value2['color'];
                }
                
                $donut_chart_cumrehandling['labels']=$rehandling_labels;
                $donut_chart_cumrehandling['datasets']['backgroundColor']=$rehandling_colors;//array("rgba(255, 99, 132, 0.8)","rgba(54, 162, 235, 0.7)");
                $donut_chart_cumrehandling['datasets']['data']=$rehandling_datas; 
                //echo "<pre>";print_r($donut_chart_cumrehandling);echo "</pre>";
                $tpl->donut_chart_cumrehandling=$donut_chart_cumrehandling;
                /** ====================================================================== */
                
                /** ================== data for barchart expit ore=====================*/
                $ref_color['ETO']="rgba(255, 125, 132, 0.8";
                $ref_color['BRG']="rgba(125, 162, 235, 0.8)";
                
                $lokasi_pit=$production->getCommulativeCrossMiningByLokasiPIT($year);
                //echo "<pre>";print_r($lokasi_pit['data']);echo "</pre>";
               
                $datas_lp   =array();
                $colors     =array();
                $labels=array();
                $judul['ETO']="Stockpiling";
                $judul['BRG']="Cross Mining";
                $k=0;
                foreach($lokasi_pit['data'] as $keyl=>$valuel){
                    if(!in_array($valuel['pit_name'],$labels)){
                        $pit=$valuel['pit_name'];
                        //echo $valuel['pit_name']." - ".$valuel['partner_alias'];
                        $srch_pit= strpos($valuel['pit_name'],$valuel['partner_alias']);
                        
                        if($srch_pit==false){
                            $pit=$pit." ".$valuel['partner_alias'];
                        }
                        //echo $pit."<br />";
                        $labels[]=$pit; 
                    }
                    $key_main="ETO";
                    $datas_lp[$key_main]['label']=$judul[$key_main];
                    $datas_lp[$key_main]['backgroundColor']=$ref_color[$key_main];
                    $datas_lp[$key_main]['borderWidth']=0.5;
                    $datas_lp[$key_main]['data'][$k]=$valuel[$key_main]['jml_quantity'];
                    
                    $key_main2="BRG";
                    $datas_lp[$key_main2]['label']=$judul[$key_main2];
                    $datas_lp[$key_main2]['backgroundColor']=$ref_color[$key_main2];
                    $datas_lp[$key_main]['borderWidth']=0.5;
                    $datas_lp[$key_main2]['data'][$k]=$valuel[$key_main2]['jml_quantity'];
                    $k++;
                }
                
                $bar_chart_pit=array();
                $bar_chart_pit['labels']=$labels;
                $j=0;
                while($data3=current($datas_lp)){
                   // echo "<pre>";print_r($data2);echo "</pre>";
                    $bar_chart_pit['datasets'][$j]['label']=$data3['label']; 
                    $bar_chart_pit['datasets'][$j]['backgroundColor']=$data3['backgroundColor'];
                    $bar_chart_pit['datasets'][$j]['borderColor']="rgba(247, 247, 247, 0.1)";
                    $bar_chart_pit['datasets'][$j]['borderWidth']=1;
                    $bar_chart_pit['datasets'][$j]['data']=$data3['data']; 
                    $bar_chart_pit['datasets'][$j]['fill']=false; 
                    
                    $j++;
                    next($datas_lp);
               }
           // echo "<pre>";print_r($labels);echo "</pre>";
                $tpl->bar_chart_pit= json_encode($bar_chart_pit);
               // echo "<pre>";print_r($bar_chart_pit);echo "</pre>";
                /** =========================end of data for barchart expit ore============================= */
                
                /** ================== data for barchart rehandling=====================*/
               
                $ref_color['RHD']="rgba(186,85,211,0.7)";
                
                //echo "<pre>";print_r($rehandling_commulative);echo "</pre>";
               
                $datas_lp   =array();
                $colors     =array();
                $labels=array();
                
                $judul[0]="Rehandling";
                $i=0;
                $k=0;
                foreach($rehandling_commulative['data'] as $key2=>$value2){
                    if(!in_array($value2['alias'],$labels)){
                      
                        $labels[]=$value2['alias']; 
                    }
                   
                    $datas_lp[$i]['label']=$judul[$i];
                    $datas_lp[$i]['backgroundColor']=$ref_color['RHD'];
                    $datas_lp[$i]['borderWidth']=0.5;
                    $datas_lp[$i]['data'][$k]=$value2['jml_quantity'];
                   
                    $k++;
                }
                //echo "<pre>";print_r($datas_lp);echo "</pre>";
                $bar_chart_rehandling=array();
                $bar_chart_rehandling['labels']=$labels;
                $j=0;
                while($data3=current($datas_lp)){
                   // echo "<pre>";print_r($data2);echo "</pre>";
                    $bar_chart_rehandling['datasets'][$j]['label']=$data3['label']; 
                    $bar_chart_rehandling['datasets'][$j]['backgroundColor']=$data3['backgroundColor'];
                    $bar_chart_rehandling['datasets'][$j]['borderColor']=$data3['backgroundColor'];;
                    $bar_chart_rehandling['datasets'][$j]['borderWidth']=0.5;
                    $bar_chart_rehandling['datasets'][$j]['data']=$data3['data']; 
                    $bar_chart_rehandling['datasets'][$j]['fill']=false; 
                    
                    $j++;
                    next($datas_lp);
               }
           // echo "<pre>";print_r($bar_chart_rehandling);echo "</pre>";
                $tpl->bar_chart_rehandling= json_encode($bar_chart_rehandling);
                
                /** =========================end of data for barchart rehandling============================= */
                
                /** monthly stockpiling*/
                
                $recap_stockpiling_monthly= $production->getRecapExpitOreStockpilingByMonth($year,12);
               // echo "<pre>";print_r($recap_stockpiling_monthly);echo "</pre>";
                $barchart_monthly_stockpling=array();
                $barchart_monthly_stockpling['labels']=$recap_stockpiling_monthly['labels'];
                $j=0;
                foreach($recap_stockpiling_monthly['data_quantity'] as $key4=>$value4){
                    
                    $barchart_monthly_stockpling['datasets'][$j]['label']=$recap_stockpiling_monthly['partner'][$key4]['Alias']; 
                    $barchart_monthly_stockpling['datasets'][$j]['backgroundColor']=$recap_stockpiling_monthly['partner'][$key4]['RGBColor'];
                    $barchart_monthly_stockpling['datasets'][$j]['borderWidth']=1;
                    $barchart_monthly_stockpling['datasets'][$j]['data']=$value4; 
                    $barchart_monthly_stockpling['datasets'][$j]['fill']=false; 
                    $j++;
                }
               //echo "<pre>";print_r($barchart_monthly_stockpling);echo "</pre>";
                $tpl->barchart_monthly_stockpling= json_encode($barchart_monthly_stockpling);//json_encode($chart158);
                /** end of monthly stockpiling*/
                
                 /** monthly crossmining*/
                
                $recap_crossmining_monthly= $production->getRecapExpitOreCrossminingByMonth($year,12);
               // echo "<pre>";print_r($recap_crossmining_monthly);echo "</pre>";
                $barchart_monthly_crossmining=array();
                $barchart_monthly_crossmining['labels']=$recap_crossmining_monthly['labels'];
                $j=0;
                foreach($recap_crossmining_monthly['data_quantity'] as $key4=>$value4){
                    
                    $barchart_monthly_crossmining['datasets'][$j]['label']=$recap_crossmining_monthly['partner'][$key4]['Alias']; 
                    $barchart_monthly_crossmining['datasets'][$j]['backgroundColor']=$recap_crossmining_monthly['partner'][$key4]['RGBColor'];
                    $barchart_monthly_crossmining['datasets'][$j]['borderWidth']=1;
                    $barchart_monthly_crossmining['datasets'][$j]['data']=$value4; 
                    $barchart_monthly_crossmining['datasets'][$j]['fill']=false; 
                    $j++;
                }
               //echo "<pre>";print_r($barchart_monthly_stockpling);echo "</pre>";
                $tpl->barchart_monthly_crossmining= json_encode($barchart_monthly_crossmining);//json_encode($chart158);
                /** end of monthly stockpiling*/
                
                 /** monthly rehandling*/
                
                $recap_rehandling_monthly= $production->getRecapRehandlinggByMonth($year,12);
                //echo "<pre>";print_r($recap_rehandling_monthly);echo "</pre>";
                $barchart_monthly_rehandling=array();
                $barchart_monthly_rehandling['labels']=$recap_rehandling_monthly['labels'];
                $j=0;
                foreach($recap_rehandling_monthly['data_quantity'] as $key6=>$value6){
                    
                    $barchart_monthly_rehandling['datasets'][$j]['label']=$recap_rehandling_monthly['partner'][$key6]['Alias']; 
                    $barchart_monthly_rehandling['datasets'][$j]['backgroundColor']=$recap_rehandling_monthly['partner'][$key6]['RGBColor'];
                    $barchart_monthly_rehandling['datasets'][$j]['borderWidth']=1;
                    $barchart_monthly_rehandling['datasets'][$j]['data']=$value6; 
                    $barchart_monthly_rehandling['datasets'][$j]['fill']=false; 
                    $j++;
                }
              // echo "<pre>";print_r($barchart_monthly_rehandling);echo "</pre>";
                $tpl->barchart_monthly_rehandling= json_encode($barchart_monthly_rehandling);//json_encode($chart158);
                /** end of monthly rehandling*/
                
                 /** monthly shipment*/
                
                $recap_shipment_monthly= $production->getRecapShipmentByMonth($year,12);
               // echo "<pre>";print_r($recap_shipment_monthly);echo "</pre>";
                $barchart_monthly_shipment=array();
                $barchart_monthly_shipment['labels']=$recap_shipment_monthly['labels'];
                $j=0;
                foreach($recap_shipment_monthly['draught_survey'] as $key7=>$value7){
                    
                    $barchart_monthly_shipment['datasets'][$j]['label']="Monthly Shipment"; 
                    $barchart_monthly_shipment['datasets'][$j]['backgroundColor']="rgba(255,140,0, 0.8)";
                    $barchart_monthly_shipment['datasets'][$j]['borderWidth']=1;
                    $barchart_monthly_shipment['datasets'][$j]['data']=$value7; 
                    $barchart_monthly_shipment['datasets'][$j]['fill']=false; 
                    $j++;
                }
              
                $tpl->barchart_monthly_shipment= json_encode($barchart_monthly_shipment);//json_encode($chart158);
                /** end of monthly shipment*/
                
              
                $recap_barge_shipment_monthly= $production->getRecapBargeByMonth($year,6,6);
               // print_r($recap_barge_shipment_monthly);
                $barchart_barge_shipment_monthly=array();
                $barchart_barge_shipment_monthly['labels']=$recap_barge_shipment_monthly['labels'];
                $j=0;
                if(!empty($recap_barge_shipment_monthly['draught_survey'])){
                    foreach($recap_barge_shipment_monthly['draught_survey'] as $key7=>$value7){
                        
                        $barchart_barge_shipment_monthly['datasets'][$j]['label']=$value7['barge_name']; 
                        $barchart_barge_shipment_monthly['datasets'][$j]['backgroundColor']=$value7['rgb_color'];
                        $barchart_barge_shipment_monthly['datasets'][$j]['borderWidth']=0;
                        $barchart_barge_shipment_monthly['datasets'][$j]['data']=$value7['data']; 
                        $barchart_barge_shipment_monthly['datasets'][$j]['fill']=true; 
                        $j++;
                    }
                }
                //echo "<pre>";print_r($barchart_barge_shipment_monthly);echo "</pre>";
                $tpl->barchart_barge_shipment_monthly1= json_encode($barchart_barge_shipment_monthly);
                
                //bulkan jul-dec
                $recap_barge_shipment_monthly2= $production->getRecapBargeByMonth($year,12,6);
                $barchart_barge_shipment_monthly2=array();
                $barchart_barge_shipment_monthly2['labels']=$recap_barge_shipment_monthly2['labels'];
                $j=0;
                if(!empty($recap_barge_shipment_monthly2['draught_survey'])){
                    foreach($recap_barge_shipment_monthly2['draught_survey'] as $key8=>$value8){
                        
                        $barchart_barge_shipment_monthly2['datasets'][$j]['label']=$value8['barge_name']; 
                        $barchart_barge_shipment_monthly2['datasets'][$j]['backgroundColor']="";//$value8['rgb_color'];
                        $barchart_barge_shipment_monthly2['datasets'][$j]['borderWidth']=1;
                        $barchart_barge_shipment_monthly2['datasets'][$j]['data']=$value8['data']; 
                        $barchart_barge_shipment_monthly2['datasets'][$j]['fill']=false; 
                        $j++;
                    }
                }
               // echo "<pre>";print_r($recap_barge_shipment_monthly2);echo "</pre>";
                $tpl->barchart_barge_shipment_monthly2= json_encode($barchart_barge_shipment_monthly2);
                $tpl->total_data_barge_shipment_monthly2= $recap_barge_shipment_monthly2['total_data'];
                
                
                
                 //Total to Barge
                $transit_commulative_tobarge=$production->getCummulativeRecapLoadingToBarge($year);
                //echo "<pre>";print_r($transit_commulative_tobarge['data']);echo "</pre>";
                $tpl->data_total_tobarge=$transit_commulative_tobarge['data'];
                $tobarge_datas  =array();
                $tobarge_labels =array();
                $tobarge_colors  =array();
                foreach($transit_commulative_tobarge['data'] as $key4=>$value4){
                    if(!array_key_exists($key4,$list_partner)){
                        $list_partner[$key4]['partner_name']=$value4['partner_name'];
                        $list_partner[$key4]['alias']=$value4['alias'];
                        $list_partner[$key4]['color']=$value4['color'];
                    }
                    $tobarge_labels[]=$value4['alias'];
                    $persentase=round(($value4['jml_quantity']/$transit_commulative_tobarge['total_quantity'])*100,2);
                    $tobarge_datas[]    =$persentase;
                    $tobarge_colors[]   =$value4['color'];
                }
                $donut_chart_cumtobarge['labels']=$tobarge_labels;
                $donut_chart_cumtobarge['datasets']['backgroundColor']=$tobarge_colors;//array("rgba(255, 99, 132, 0.8)","rgba(54, 162, 235, 0.7)");
                $donut_chart_cumtobarge['datasets']['data']=$tobarge_datas; 
                $tpl->donut_chart_cumtobarge=$donut_chart_cumtobarge;
                
                $tpl->list_partner=$list_partner;
                
                
                
                 //echo "<pre>";print_r($tpl->donut_chart_cumtransit);echo "</pre>";
                //$s=$rekap->getRekapBudgetingByMonth(2019,10,12);
              // echo "<pre>";print_r($s);echo "</pre>";
                //$rk=$rekap->getRekapProductionByMonth(2019,06,9);
                $transit_cumulative[158]["partner_id"]=158;
                $transit_cumulative[158]["partner_name"]="PT. Lamario Celebes Perkasa";
                $transit_cumulative[158]["alias"]="LCP";
                $transit_cumulative[158]["qty"]=1300;
                $transit_cumulative[158]["persentase"]=round((1300/3000)*100,2);
                $transit_cumulative[158]["color"]=$color[158];
                
                $transit_cumulative[159]["partner_id"]=159;
                $transit_cumulative[159]["partner_name"]="PT. Premier Offshore Indonesia";
                $transit_cumulative[159]["alias"]="PL";
                $transit_cumulative[159]["qty"]=900;
                $transit_cumulative[159]["persentase"]=round((1300/3000)*100,2);;
                $transit_cumulative[159]["color"]=$color[159];
                
                $transit_cumulative[160]["partner_id"]=160;
                $transit_cumulative[160]["partner_name"]="PT. Bumi Karya Makmur";
                $transit_cumulative[160]["alias"]="BKM";
                $transit_cumulative[160]["qty"]=800;
                $transit_cumulative[160]["persentase"]=round((800/3000)*100,2);;
                $transit_cumulative[160]["color"]=$color[160];
                $labels=array();
                $datas=array();
                $colors=array();
                $r=255;
                $g=125;
                $b=132;
                foreach($transit_cumulative as $key=>$value){
                    $labels[]=$value['alias'];
                    $datas[]=$value['persentase'];
                    $colors[]=$value['color'];
                    
                }
               
               // $data_budget['selisih']=$data_budget['budget']-$data_budget['actual'];
                //echo "<pre>";print_r($data_transit_ore);echo "</pre>";
                
                $donut_chart=array();
                $j=0; 
                
              
                 /** shipment ore */ 
                $shipment_ore_a=1000;
                $shipment_ore_b=1100;
                $shipment_ore_c=200;
                $total_shipment_ore=$shipment_ore_a+$shipment_ore_b+$shipment_ore_c;
                $data_shipment_ore['con_a']['persentase'] =($shipment_ore_a/$total_shipment_ore)*100;
                $data_shipment_ore['con_a']['quantity']   =$shipment_ore_a;
                $data_shipment_ore['con_b']['persentase'] =($shipment_ore_b/$total_shipment_ore)*100;
                $data_shipment_ore['con_b']['quantity']   =$shipment_ore_b;
                $data_shipment_ore['con_c']['persentase'] =($shipment_ore_c/$total_shipment_ore)*100;
                $data_shipment_ore['con_c']['quantity']   =$shipment_ore_c;
               // $data_budget['selisih']=$data_budget['budget']-$data_budget['actual'];
                //echo "<pre>";print_r($data_shipment_ore);echo "</pre>";
                $tpl->data_shipment_ore=$data_shipment_ore;
                $donut_chart_shipment=array();
                $j=0; 
                $donut_chart_shipment['datasets']['data']=array($data_shipment_ore['con_a']['persentase'],$data_shipment_ore['con_b']['persentase'],$data_shipment_ore['con_c']['persentase']);
                $donut_chart_shipment['datasets']['backgroundColor']=
                        array("rgba(255, 125, 132, 0.8)",
                            "rgba(125, 162, 235, 0.7)",
                            "rgba(75, 162, 125, 0.7)");//array("rgba(255, 99, 132, 0.8)","rgba(54, 162, 235, 0.7)");
                 $donut_chart_shipment['labels']=array("Con A","Con B", "Con C");
                 
                 //echo "<pre>";print_r($donut_chart_shipment);echo "</pre>";
                 //echo json_encode($donut_chart_shipment);
                 $tpl->donut_chart_shipment=$donut_chart_shipment;
                 
                 
                 
                $bar_chart=array();
                $j=0; 
                $bar_chart['labels']=$s['label'];
                $bar_chart['datasets'][$j]['label']="Plan"; 
                $bar_chart['datasets'][$j]['backgroundColor']="gray";
                $bar_chart['datasets'][$j]['borderColor']="gray";
                $bar_chart['datasets'][$j]['borderWidth']=0.5;
                $bar_chart['datasets'][$j]['data']=$s['data']['Plan']; 
                $bar_chart['datasets'][$j]['fill']=false; 
                $j++;
                $bar_chart['datasets'][$j]['label']="Aktual"; 
                $bar_chart['datasets'][$j]['backgroundColor']="rgba(255, 99, 132, 0.8)";
                $bar_chart['datasets'][$j]['borderColor']="rgba(255, 99, 132, 0.8)";
                $bar_chart['datasets'][$j]['borderWidth']=0.5;
                $bar_chart['datasets'][$j]['data']=$s['data']['Actual']; 
                $bar_chart['datasets'][$j]['fill']=false; 
                
                /*$j++;
                foreach($s['data'] as $key=>$value){                   
                    $bar_chart['datasets'][$j]['label']=$key; 
                    $bar_chart['datasets'][$j]['backgroundColor']=$color[$key];
                    $bar_chart['datasets'][$j]['borderColor']=$color[$key];
                    $bar_chart['datasets'][$j]['borderWidth']=0.5;
                    $bar_chart['datasets'][$j]['data']=$value; 
                    $bar_chart['datasets'][$j]['fill']=false; 
                    $j++;
                }*/
                //echo "<pre>";print_r($bar_chart);echo "</pre>";
                //echo json_encode($bar_chart);
                $tpl->bar_chart=$bar_chart;
                
                
               // exit;
            break;
            case "recap_production":
                $tpl  = new View("dashboard_recap_production");
                $recap_current_year=array();
              /*  $color=array("XXXXXXX"=>"#d2d6de",
                            "3205226"=>"#f39c12",//karangtengah
                            "3205225"=>"#f012be",//mande
                            "3205224"=>"#01ff70",//ciranjang
                            "3205223"=>"#00a65a",//haurwangi
                            "3205222"=>"#3c8dbc",//bojong picung
                            "3205221"=>"#00c0ef");//sukaluyu
              */ 
                $list_qry=$db->select("partner_id,p.name,
                sum(target) target,sum(cumulative_target) cumulative_target,
                sum(qty) qty,sum(cumulative_qty) cumulative_qty","report_monthly_production  rmp
                inner join partner p on p.id=rmp.partner_id")
                ->where("left(month,4)=".date("Y")." GROUP BY partner_id")->lim();//
                $recap  = array();
                while($data=$db->fetchObject($list_qry)){
                    $recap[$data->partner_id]['parnter_id']=$data->partner_id;
                    $recap[$data->partner_id]['parnter_name']=$data->name;
                    $recap[$data->partner_id]['target']                 =$data->target;
                    $recap[$data->partner_id]['cumulative_target']      =$data->cumulative_target;
                    $recap[$data->partner_id]['qty']     =$data->qty;
                    $recap[$data->partner_id]['cumulative_qty']     =$data->cumulative_qty;
                    
                }
            //echo "<pre>";print_r($recap);echo "</pre>";
          
               
                $tpl->recap=$recap;
                $color=array(157=>"orange",
                            158=>"green",//karangtengah
                            159=>"blue",//mande
                            160=>"red");//sukaluyu
                $tpl->url_recap      = url::page(2072);
                $s=$rekap->getRekapProductionByMonth(2019,06,6);
               // echo "<pre>";print_r($s);echo "</pre>";
                
                $bar_chart=array();
                foreach($s['data'] as $key=>$value){
                    $bar_chart[$key]['labels']=$s['label'];
                    $bar_chart[$key]['datasets'][0]['label']=$s['partner'][$key]['Alias']." Monthly Trend"; 
                    $bar_chart[$key]['datasets'][0]['backgroundColor']=$color[$key];
                    $bar_chart[$key]['datasets'][0]['borderWidth']=1;
                    $bar_chart[$key]['datasets'][0]['data']=$value; 
                    $bar_chart[$key]['datasets'][0]['fill']=false; 
                }
               // echo "<pre>";print_r($bar_chart);echo "</pre>";
                $tpl->bar_chart= $bar_chart;//json_encode($chart158);
                $tpl->url_unverified    = url::page(2012);
                $tpl->url_inactive      = url::page(2013);
            break;
            case "grafik":
                $tpl  = new View("grafik");
                                
                $list_qry=$db->select("partner_id,p.name,p.alias,
                ifnull(sum(target),0)  cumulative_target,
                ifnull(sum(qty),0) cumulative_qty","report_monthly_production  rmp
                inner join partner p on p.id=rmp.partner_id")
                ->where("left(month,4)=".date("Y")." GROUP BY partner_id")->lim();//
                $recap  = array();
                $labels     =array();
                $data_array =array();
                while($data=$db->fetchObject($list_qry)){
                    $labels[]=$data->alias;
                    $recap[$data->partner_id]['parnter_id']=$data->partner_id;
                    $recap[$data->partner_id]['parnter_name']=$data->alias;
                    $recap[$data->partner_id]['target']                 =$data->target;
                    $recap[$data->partner_id]['cumulative_target']      =$data->cumulative_target;
                    $recap[$data->partner_id]['qty']     =$data->qty;
                    $recap[$data->partner_id]['cumulative_qty']     =$data->cumulative_qty;
                    $data_array[0]['label']="Plan";
                    $data_array[0]['backgroundColor']="orange";
                    $data_array[0]['data'][]=$data->cumulative_target;
                    $data_array[1]['label']="Progress";
                    $data_array[1]['backgroundColor']="green";
                    $data_array[1]['borderWidth']=1;
                    $data_array[1]['data'][]=$data->cumulative_qty;
                    
                }
                $chart['labels']=$labels; 
                $chart['datasets']=array($data_array[0],$data_array[1]); 
               // echo "<pre>";print_r($chart);echo "</pre>";
                $tpl->data_chart= json_encode($chart);
                
                
                
                $rk=$rekap->getRekapProductionByMonth(2019,06,9);
                
               // echo "<pre>";print_r($rk);echo "</pre>";
                $partner=$rk['partner'];
                $bar_chart=array();
                $j=0; 
                $bar_chart['labels']=$rk['label'];
                $bar_chart['datasets'][$j]['label']="Plan"; 
                $bar_chart['datasets'][$j]['backgroundColor']="gray";
                $bar_chart['datasets'][$j]['borderColor']="gray";
                $bar_chart['datasets'][$j]['borderWidth']=0.5;
                $bar_chart['datasets'][$j]['data']=$rk['cumulative_target'][157]; 
                $bar_chart['datasets'][$j]['fill']=false; 
                $j++;
                foreach($rk['data_cumulative'] as $key=>$value){                   
                    $bar_chart['datasets'][$j]['label']=$partner[$key]['Alias']; 
                    $bar_chart['datasets'][$j]['backgroundColor']=$color[$key];
                    $bar_chart['datasets'][$j]['borderColor']=$color[$key];
                    $bar_chart['datasets'][$j]['borderWidth']=0.5;
                    $bar_chart['datasets'][$j]['data']=$value; 
                    $bar_chart['datasets'][$j]['fill']=false; 
                    $j++;
                }
                //echo "<pre>";print_r($bar_chart);echo "</pre>";
                $tpl->bar_chart= $bar_chart;//json_encode($chart158);
                $tpl->url_populasi      = url::page(2011);
                $tpl->url_unverified    = url::page(2012);
                $tpl->url_inactive      = url::page(2013);
            break;
            case "weekly_production":
                 $tpl  = new View("grafik_weekly");
                $wekly= $rekap->getRekapProductionByWeek(array("week"=>44,"year"=>2018),array("week"=>8,"year"=>2019));
               
                $datasets=array();
                $j=0;
                $bar_chart=array();
                $bar_chart['labels']=$wekly[157]['label'];
                $j=0;
                while($data2=current($wekly)){
                   // echo "<pre>";print_r($data2);echo "</pre>";
                    $bar_chart['datasets'][$j]['label']=$data2['partner_alias']; 
                    $bar_chart['datasets'][$j]['backgroundColor']=$data2['color'];
                    $bar_chart['datasets'][$j]['borderColor']=$data2['color'];
                    $bar_chart['datasets'][$j]['borderWidth']=0.5;
                    $bar_chart['datasets'][$j]['data']=$data2['qty']; 
                    $bar_chart['datasets'][$j]['fill']=false; 
                    
                    $j++;
                    next($wekly);
               }
              //echo "<pre>";print_r($bar_chart);echo "</pre>";
                $tpl->weekly_data= json_encode($bar_chart);
                
                $wekly2= $rekap->getRekapProductionByWeek(array("week"=>9,"year"=>2019),array("week"=>26,"year"=>2019));
            
                $j=0;
                $bar_chart2=array();
                $bar_chart2['labels']=$wekly2[157]['label'];
                $j=0;
                while($data3=current($wekly2)){
                   // echo "<pre>";print_r($data2);echo "</pre>";
                    $bar_chart2['datasets'][$j]['label']=$data3['partner_alias']; 
                    $bar_chart2['datasets'][$j]['backgroundColor']=$data3['color'];
                    $bar_chart2['datasets'][$j]['borderColor']=$data3['color'];
                    $bar_chart2['datasets'][$j]['borderWidth']=0.5;
                    $bar_chart2['datasets'][$j]['data']=$data3['qty']; 
                    $bar_chart2['datasets'][$j]['fill']=false; 
                    
                    $j++;
                    next($wekly2);
               }
             
                $tpl->weekly_data2= json_encode($bar_chart2);
            break;
        }
        $tpl->content = $tpl;
        $tpl->render();
	    
   }
    public function refresh($report="weekly") {
        global $dcistem;
        $db   = $dcistem->getOption("framework/db"); 
        $master=new Master_Ref_Model();
        switch($report){
            case "weekly":
                $list_qry=$db->select("rwp.id,wp.tahun,wp.week,rwp.partner_id,qty,target","report_weekly_production rwp
                inner join week_periode wp on wp.id=rwp.periode_id ")
        		->where("ifnull(locked,0)=0")->orderBy("wp.tahun asc,wp.week asc")->lim();//
                while($data = $db->fetchObject($list_qry))
                {
                    $pre_total=$db->select("IFNULL(sum(qty),0) cum_qty,IFNULL(sum(target),0) cum_target","report_weekly_production rwp
                    inner join week_periode wp on wp.id=rwp.periode_id")
                    ->where("partner_id=".$data->partner_id." and ((wp.week<=".$data->week." and wp.tahun=".$data->tahun.") or (wp.tahun<".$data->tahun.")) ")->get(0);
                   // print_r($pre_total);
                    $cum_target     = $pre_total->cum_target;
                    $cum_qty        = $pre_total->cum_qty;
                    $cum_target_val	=$master->scurevaluetable($cum_target,"number",false);
                    $cum_qty_val	=$master->scurevaluetable($cum_qty,"number",false);
		        	$cols_and_vals   ="cumulative_qty=$cum_qty_val,cumulative_target=$cum_target_val";
                    $sqlin	="UPDATE report_weekly_production SET $cols_and_vals WHERE id=".$data->id."";								
					$db->query($sqlin);                
                                    
                }
            break;
            case "monthly":
                $list_qry=$db->select("id,month,partner_id,qty,target","report_monthly_production ")
        		->where("ifnull(locked,0)=0")->orderBy("cast( left(month,4) as INT) asc,cast( right(month,2) as INT) asc")->lim();//
                while($data = $db->fetchObject($list_qry))
                {
                    list($tahun,$bulan)=explode("-",$data->month);
                    $bulan=(int)$bulan;
                    $pre_total=$db->select("sum(IFNULL(qty,0)) cum_qty,sum(IFNULL(target,0)) cum_target","report_monthly_production")
                    ->where("partner_id=".$data->partner_id." and ((cast( left(month,4) as INT)=".$tahun." and cast( right(month,2) as INT)<=".$bulan.") 
                    or (cast( left(month,4) as INT)<".$tahun." ))")->get(0);
                   // print_r($pre_total);
                    $cum_target     = $pre_total->cum_target;
                    $cum_qty        = $pre_total->cum_qty;
                    $cum_target_val	=$master->scurevaluetable($cum_target,"number",false);
                    $cum_qty_val	=$master->scurevaluetable($cum_qty,"number",false);
		        	$cols_and_vals   ="cumulative_qty=$cum_qty_val,cumulative_target=$cum_target_val";
                    $sqlin	="UPDATE report_monthly_production SET $cols_and_vals WHERE id=".$data->id."";								
					$db->query($sqlin); 
                }               
            break;
        }
    }
 

}
 

?>