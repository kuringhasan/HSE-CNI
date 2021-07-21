<?php
/**
 * @package Admin
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Dashboard_Production_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
        global $dcistem;
        
        $tpl  = new View("dashboard_production");
        $db   = $dcistem->getOption("framework/db"); 
        //$login_as = $_SESSION["framework"]
         //echo "<pre>";print_r($_SESSION["framework"]["current_user"]);echo "</pre>";
        //echo "<pre>";print_r($_SESSION["framework"]);echo "</pre>";
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
        $production     = new Recap_Production_Model();
        $rekap=new Adm_Recap_Model();
        $color=array(158=>"rgba(255, 125, 132, 0.8",
                    159=>"rgba(125, 162, 235, 0.7)",//karangtengah
                    160=>"rgba(75, 162, 125, 0.7)");//sukaluyu
        $this->settings =$master->settings();
        switch($kategori){
            case "daily_production_grafik":
                $tpl  = new View("daily_production_grafik");
                $daily =new Daily_Production_Model();
                $daily=$daily->getDailyExpitRitaseByContractor(date("Y"));
                //echo "<pre>";print_r($transit_commulative['data']);echo "</pre>";
                $tpl->data_transit_ore=$transit_commulative['data'];
                $transit_datas  =array();
                $transit_labels =array();
                $transit_colors  =array();
                $list_partner   = array();
                foreach($transit_commulative['data'] as $key=>$value){
                    $list_partner[$key]['partner_name']=$value['partner_name'];
                    $list_partner[$key]['alias']=$value['alias'];
                    $list_partner[$key]['color']=$value['color'];
                    
                    $transit_labels[]=$value['alias'];
                    $persentase=round(($value['jml_quantity']/$transit_commulative['total_quantity'])*100,2);
                    $transit_datas[]    =$persentase;
                    $transit_colors[]   =$value['color'];
                }
                $donut_chart_cumtransit['labels']=$transit_labels;
                $donut_chart_cumtransit['datasets']['backgroundColor']=$transit_colors;//array("rgba(255, 99, 132, 0.8)","rgba(54, 162, 235, 0.7)");
                $donut_chart_cumtransit['datasets']['data']=$transit_datas; 
                $tpl->donut_chart_cumtransit=$donut_chart_cumtransit;
                
            break;
            case "dashboard_production_grafik":
                $tpl  = new View("dashboard_production_grafik");
                
                /** cross expit ore */
                $transit_commulative=$production->getCommulativeRecapExpitOreStockpiling(date("Y"));
                //echo "<pre>";print_r($transit_commulative['data']);echo "</pre>";
                $tpl->data_transit_ore=$transit_commulative['data'];
                $transit_datas  =array();
                $transit_labels =array();
                $transit_colors  =array();
                $list_partner   = array();
                foreach($transit_commulative['data'] as $key=>$value){
                    $list_partner[$key]['partner_name']=$value['partner_name'];
                    $list_partner[$key]['alias']=$value['alias'];
                    $list_partner[$key]['color']=$value['color'];
                    
                    $transit_labels[]=$value['alias'];
                    $persentase=round(($value['jml_quantity']/$transit_commulative['total_quantity'])*100,2);
                    $transit_datas[]    =$persentase;
                    $transit_colors[]   =$value['color'];
                }
                $donut_chart_cumtransit['labels']=$transit_labels;
                $donut_chart_cumtransit['datasets']['backgroundColor']=$transit_colors;//array("rgba(255, 99, 132, 0.8)","rgba(54, 162, 235, 0.7)");
                $donut_chart_cumtransit['datasets']['data']=$transit_datas; 
                $tpl->donut_chart_cumtransit=$donut_chart_cumtransit;
                
                /** ================== Cross mining (PIT To Barge) =====================*/
                $transit_commulative_crossmining=$production->getCommulativeRecapExpitOreCrossMining(date("Y"));
                //echo "<pre>";print_r($transit_commulative_crossmining['data']);echo "</pre>";
                $tpl->data_transit_crossmining=$transit_commulative_crossmining['data'];
                $crossmining_datas  =array();
                $crossmining_labels =array();
                $crossmining_colors  =array();
                foreach($transit_commulative_crossmining['data'] as $key1=>$value1){
                    $list_partner[$key1]['partner_name']=$value1['partner_name'];
                    $list_partner[$key1]['alias']=$value1['alias'];
                    $list_partner[$key1]['color']=$value1['color'];
                    
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
                $rehandling_commulative=$production->getCommulativeRecapRehandling(date("Y"));
                //echo "<pre>";print_r($rehandling_commulative);echo "</pre>";
                $tpl->rehandling_commulative=$rehandling_commulative['data'];
                $rehandling_datas  =array();
                $rehandling_labels =array();
                $rehandling_colors  =array();
                
                foreach($rehandling_commulative['data'] as $key2=>$value2){
                    $list_partner[$key2]['partner_name']=$value2['partner_name'];
                    $list_partner[$key2]['alias']=$value2['alias'];
                    $list_partner[$key2]['color']=$value2['color'];
                    
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
                
                /** ================== data for barchart =====================*/
                //$list_data=array();
                //$i=0;
                /*while($data=$db->fetchObject($list_qry)){
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
                
                }*/
                /** =========================end of data for barchart============================= */
                 //Total to Barge
                $transit_commulative_tobarge=$production->getCummulativeRecapLoadingToBarge(date("Y"));
                //echo "<pre>";print_r($transit_commulative_tobarge['data']);echo "</pre>";
                $tpl->data_total_tobarge=$transit_commulative_tobarge['data'];
                $tobarge_datas  =array();
                $tobarge_labels =array();
                $tobarge_colors  =array();
                foreach($transit_commulative_tobarge['data'] as $key4=>$value4){
                    $list_partner[$key4]['partner_name']=$value4['partner_name'];
                    $list_partner[$key4]['alias']=$value4['alias'];
                    $list_partner[$key4]['color']=$value4['color'];
                    
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
                echo "<pre>";print_r($wekly);echo "</pre>";
                $datasets=array();
                $j=0;
                $bar_chart=array();
                $bar_chart['labels']=$wekly[157]['label'];
                $j=0;
                while($data2=current($wekly)){
                   // echo "<pre>";print_r($data2);echo "</pre>";
                    $bar_chart['datasets'][$j]['label']=$data2['partner_alias']; 
                    $bar_chart['datasets'][$j]['backgroundColor']=$color[$data2['partner_id']];
                    $bar_chart['datasets'][$j]['borderColor']=$color[$data2['partner_id']];
                    $bar_chart['datasets'][$j]['borderWidth']=0.5;
                    $bar_chart['datasets'][$j]['data']=$data2['qty']; 
                    $bar_chart['datasets'][$j]['fill']=false; 
                    
                    $j++;
                    next($wekly);
               }
             
                $tpl->weekly_data= json_encode($bar_chart);
                
                $wekly2= $rekap->getRekapProductionByWeek(array("week"=>9,"year"=>2019),array("week"=>26,"year"=>2019));
            
                $j=0;
                $bar_chart2=array();
                $bar_chart2['labels']=$wekly2[157]['label'];
                $j=0;
                while($data3=current($wekly2)){
                   // echo "<pre>";print_r($data2);echo "</pre>";
                    $bar_chart2['datasets'][$j]['label']=$data3['partner_alias']; 
                    $bar_chart2['datasets'][$j]['backgroundColor']=$color[$data3['partner_id']];
                    $bar_chart2['datasets'][$j]['borderColor']=$color[$data3['partner_id']];
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
   public function load_data($ketegori="") {
        $db   = $dcistem->getOption("framework/db"); 
        $master=new Master_Ref_Model();
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