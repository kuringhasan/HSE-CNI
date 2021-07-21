<?php
/**
 * @package Admin
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Adm_Dashboard_Controller extends Admin_Template_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
        global $dcistem;
        
        $tpl  = new View("adm_dashboard");
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
        $rekap=new Adm_Recap_Model();
        $color=array(157=>"orange",
                    158=>"green",//karangtengah
                    159=>"blue",//mande
                    160=>"red");//sukaluyu
        $this->settings =$master->settings();
        switch($kategori){
            
            case "monthly_budgeting":
                $tpl  = new View("dashboard_budgeting");
                $s=$rekap->getRekapBudgetingByMonth(2019,10,12);
              // echo "<pre>";print_r($s);echo "</pre>";
                //$rk=$rekap->getRekapProductionByMonth(2019,06,9);
                $total_budget=1000000000;
                $budget_until_last_month=500000000;
                $actual_until_last_month=450000000;
                $data_budget['budget']=($budget_until_last_month/$total_budget)*100;
                $data_budget['actual']=($actual_until_last_month/$total_budget)*100;
                $data_budget['selisih']=$data_budget['budget']-$data_budget['actual'];
               // echo "<pre>";print_r($data_budget);echo "</pre>";
                $tpl->data_budget=$data_budget;
                $donut_chart=array();
                $j=0; 
                $donut_chart['labels']="Expenses";
                $donut_chart['datasets']['backgroundColor']=array("red","blue");//array("rgba(255, 99, 132, 0.8)","rgba(54, 162, 235, 0.7)");
               
                $donut_chart['datasets']['data']=array(50,45); 
                // echo "<pre>";print_r($donut_chart);echo "</pre>";
                 $tpl->donut_chart=$donut_chart;
                 
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
                
                
                $bar_chart_department=array();
                $j=0; 
                $bar_chart_department['labels']=array("Dep A","Dep B","Dep C", "Dep D");
                $bar_chart_department['datasets'][$j]['label']="Plan"; 
                $bar_chart_department['datasets'][$j]['backgroundColor']="gray";
                $bar_chart_department['datasets'][$j]['borderColor']="gray";
                $bar_chart_department['datasets'][$j]['borderWidth']=0.5;
                $bar_chart_department['datasets'][$j]['data']=array(60000,50000,70000,75000); 
                $bar_chart_department['datasets'][$j]['fill']=false; 
                $j++;
                $bar_chart_department['datasets'][$j]['label']="Aktual"; 
                $bar_chart_department['datasets'][$j]['backgroundColor']="rgba(255, 99, 132, 0.8)";
                $bar_chart_department['datasets'][$j]['borderColor']="rgba(255, 99, 132, 0.8)";
                $bar_chart_department['datasets'][$j]['borderWidth']=0.5;
                $bar_chart_department['datasets'][$j]['data']=array(58000,45000,60000,70000); 
                $bar_chart_department['datasets'][$j]['fill']=false; 
                
                $tpl->bar_chart_department=$bar_chart_department;
               // exit;
            break;
            case "daily_production":
                $tpl  = new View("dashboard_daily_production");
            break;
            case "recap_production":
                $tpl  = new View("dashboard_production_grafik");
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