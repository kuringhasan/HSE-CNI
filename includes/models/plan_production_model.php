<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Damas Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class Plan_Production_Model extends Model {
  
	public function __construct() {
		
	}
    public function getPlanProduction($plan_id,$format="object"){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
        $master		=new Master_Ref_Model();
       	if(trim($plan_id)=="" or  $plan_id == null){
			return array();
		}else{
            $data=$db->select("pp.id,pp.tahun,pp.target_production,pp.contractor_id,
            p.name contractor_name,p.alias contractor_alias,pp.state,pp.lastupdated,pp.created_time,pp.note","plan_production pp
            inner join partner p on p.id=pp.contractor_id")
    		->where("pp.id=$plan_id")->get(0);//
            if(!empty($data)){
				$rec    	= new stdClass;
                //print_r($data);
                $rec->created_detail        = $master->detailtanggal($data->created_time,2);
                $rec->lastupdated_detail    = $master->detailtanggal($data->lastupdated,2);
                $rec->plan_monthly =        $this->getPlanProductionMonthly($data->id);
                $rec->plan_weekly =        $this->getPlanProductionWeekly($data->id);
                $rec->plan_daily =        $this->getPlanProductionDaily($data->id);
                if(trim($format)=="array"){
					$result = array_merge((array) $data, (array) $rec);
					return $result;
				}else{
					$result	= (object) array_merge((array) $data, (array) $rec);
					return $result;
				}	
            }
            
        }
        
			 
	}
    public function getPlanProductionMonthly($plan_id,$format="object"){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
        $master		=new Master_Ref_Model();
        $usr=new Core_User_Model();
       	if(trim($plan_id)=="" or  $plan_id == null){
			return array();
		}else{
            $list_data=array();
            $data_qry=$db->select("id,bulan,plan_id,contractor_id,target_production,target_barging,note,
            created_time,lastupdated","plan_production_monthly")
    		->where("plan_id=$plan_id")->orderby("created_time desc")->lim();//
            while($data = $db->fetchObject($data_qry))
            {
                $rec    	= new stdClass;
                $no_bulan=substr($data->bulan,(strlen($data->bulan)-2),2);
                $rec->no_bulan              = $no_bulan;
                $rec->nama_bulan            = $master->namabulanIN($no_bulan);
                $rec->singkatan_bulan            = $master->namabulanIN($no_bulan,true);
                $rec->created_detail        = $master->detailtanggal($data->created_time,2);
                $rec->lastupdated_detail    = $master->detailtanggal($data->lastupdated,2);
                $rec->verifier    = $usr->getDataByUsername($data->verifier);
                if(trim($format)=="array"){
					$list_data[] = array_merge((array) $data, (array) $rec);
					
				}else{
					$list_data[]	= (object) array_merge((array) $data, (array) $rec);
				
				}	
                
            }
            return $list_data;
        }
        
			 
	}
    public function getPlanProductionWeekly($plan_id,$format="object"){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
        $master		=new Master_Ref_Model();
        $usr=new Core_User_Model();
       	if(trim($plan_id)=="" or  $plan_id == null){
			return array();
		}else{
            $list_data=array();
            $data_qry=$db->select("ppw.id,ppw.week_periode_id,wp.week,ppw.plan_id,ppw.contractor_id,ppw.target_production,
            ppw.target_barging,ppw.note,ppw.created_time,ppw.lastupdated","plan_production_weekly ppw
            inner join week_periode wp on wp.id=ppw.week_periode_id")
    		->where("plan_id=$plan_id")->orderby("ppw.created_time desc")->lim();//
            while($data = $db->fetchObject($data_qry))
            {
                $rec    	= new stdClass;
                
                $rec->created_detail        = $master->detailtanggal($data->created_time,2);
                $rec->lastupdated_detail    = $master->detailtanggal($data->lastupdated,2);
                $rec->verifier    = $usr->getDataByUsername($data->verifier);
                if(trim($format)=="array"){
					$list_data[] = array_merge((array) $data, (array) $rec);
					
				}else{
					$list_data[]	= (object) array_merge((array) $data, (array) $rec);
				
				}	
                
            }
            return $list_data;
        }
			 
	}
    public function getPlanProductionDaily($plan_id,$format="object"){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
        $master		=new Master_Ref_Model();
        $usr=new Core_User_Model();
       	if(trim($plan_id)=="" or  $plan_id == null){
			return array();
		}else{
            $list_data=array();
            $data_qry=$db->select("id,tanggal,plan_id,contractor_id,target_production,target_barging,created_time,
            lastupdated","plan_production_daily")
    		->where("plan_id=$plan_id")->orderby("created_time desc")->lim();//
            while($data = $db->fetchObject($data_qry))
            {
                $rec    	= new stdClass;               
                $rec->tanggal_detail            = $master->detailtanggal($data->tanggal,2);
                $rec->created_detail        = $master->detailtanggal($data->created_time,2);
                $rec->lastupdated_detail    = $master->detailtanggal($data->lastupdated,2);
                $rec->verifier    = $usr->getDataByUsername($data->verifier);
                if(trim($format)=="array"){
					$list_data[] = array_merge((array) $data, (array) $rec);
					
				}else{
					$list_data[]	= (object) array_merge((array) $data, (array) $rec);
				
				}	
                
            }
            return $list_data;
        }	 
	}
  
    
    public function generateBreakdown($plan_id) {
        /** 1. change field state in table verification_production_matrix from null to draft
         *  3. update current_verification to draft ($current_step_verification_matrix_id)
         * ================================================================ */
        global $dcistem;
        $db   = $dcistem->getOption("framework/db");
         $master=new Master_Ref_Model();
         $settings= $master->settings();
         date_default_timezone_set($settings['production_time_zone']);
         $msg    = array();
         $TglSkrg		     =date("Y-m-d H:i:s");
         $tgl_skrg_val		 =$master->scurevaluetable($TglSkrg,"string");
        // $login_as=	$_SESSION['framework']['login_as']; 
		 $ref_id=$_SESSION["framework"]["ref_id"] ;
         $username=$_SESSION["framework"]['current_user']->Username;
         $data=$db->select("pp.id,pp.tahun,pp.target_production,pp.contractor_id,
            p.name contractor_name,p.alias contractor_alias,pp.state,pp.lastupdated,pp.created_time,pp.note","plan_production pp
            inner join partner p on p.id=pp.contractor_id")->where("pp.id=$plan_id")->get(0);//
            
            
         if(empty($data)){
            $msg['success']=false;
            $msg['message']="Error, tidak ada data";
         }else{
            /** 1. breakdown to daily 
             *  2. breakdown to week 
             *  3. breakdown to monthly 
             */
            $contractor_id=$data->contractor_id;
            $current_date=mktime(0,0,0,1,1,(int)$data->tahun);
          
            $i=1;
            while(date("Y",$current_date)==$data->tahun){
                $current_date=mktime(0,0,0,(int)date("m",$current_date),((int)date("d",$current_date)+1),(int)date("Y",$current_date));
                $i++;
            }
            $jumlah_hari=($i-1);
            $mod_plan=$data->target_production%$jumlah_hari;
            echo "plan:".$data->target_production." jml hari:".$jumlah_hari."  modulus:".$mod_plan."<br />";
            
            
            $plan=  explode(".",(string)$data->target_production);
            $plan_bulat=(int)$plan[0];
            $part_decimal=$plan[1];
            $jumlah_plan_per_day=($plan_bulat-$mod_plan)/$jumlah_hari;
            $decimal_plan=round(fmod($data->target_production,1),strlen($part_decimal));
            /** generate */
            $current_date2=mktime(0,0,0,1,1,(int)$data->tahun);           
            $j=1;
            $jml_error=0;
            while(date("Y",$current_date2)==$data->tahun){
                $real_jumlah_perday=$jumlah_plan_per_day;
                if($j<=$mod_plan){
                    $real_jumlah_perday=$jumlah_plan_per_day+1;
                }
                if($j==1){
                   $real_jumlah_perday=$real_jumlah_perday+$decimal_plan;
                }
                $filter_day="DATE_FORMAT(tanggal,'%Y-%m-%d')='".date("Y-m-d",$current_date2)."' and contractor_id=$contractor_id and plan_id=$plan_id";
                $cek_day=$db->select("id,target_production","plan_production_daily")->where($filter_day)->get(0);
                if(empty($cek_day)){
                    $sql1="INSERT INTO plan_production_daily (tanggal,plan_id,contractor_id,target_production,
                    target_barging,created_time) VALUES('".date("Y-m-d",$current_date2)."',$plan_id,$contractor_id,$real_jumlah_perday,
                    null,'".date("Y-m-d H:i:s")."');";
                    $rsl1=$db->query($sql1);
                    if(isset($rsl1->error) and $rsl1->error===true){
                  		$jml_error++;
                    }
                }else{
                    //edit
                    echo "baru:".$real_jumlah_perday." lama:".$cek_day->target_production."<br />";
                    if($real_jumlah_perday<>$cek_day->target_production){
                        $sql1="UPDATE plan_production_daily SET target_production=$real_jumlah_perday,target_barging=null,lastupdated='".date("Y-m-d H:i:s")."'
                              WHERE $filter_day;";
                        $rsl1=$db->query($sql1);
                        if(isset($rsl1->error) and $rsl1->error===true){
                      		$jml_error++;
                        }
                    }
                }
                echo $j.". ".date("Y-m-d",$current_date2)." : ".$real_jumlah_perday."<br />";
                
                $current_date2=mktime(0,0,0,(int)date("m",$current_date2),((int)date("d",$current_date2)+1),(int)date("Y",$current_date2));
                $j++;
            }
            if($jml_error==0){
                /** generate by week */
                $filter_list_week="tahun=".$data->tahun;
                $list_week_qry=$db->select("id,week,DATE_FORMAT(start_date,'%Y-%m-%d') start_date,DATE_FORMAT(end_date,'%Y-%m-%d') end_date,tahun","week_periode")->where($filter_list_week)->orderBy("ORDER BY week asc")->lim();
                while($wk = $db->fetchObject($list_week_qry))
                {
                    $filter_week="(DATE_FORMAT(tanggal,'%Y-%m-%d') BETWEEN '".$wk->start_date."' and '".$wk->end_date."') and contractor_id=$contractor_id and plan_id=$plan_id";
                    $count_week=$db->select("sum(target_production) jml_target_perweek","plan_production_daily")->where($filter)->get(0);
                    if(!empty($count_week)){
                        $real_jumlah_per_week=$count_week->jml_target_perweek;
                        $filter_week="week_periode_id=".$wk->id." and contractor_id=$contractor_id and plan_id=$plan_id";
                        $cek_week=$db->select("id,target_production","plan_production_weekly")->where($filter_week)->get(0);
                        if(!empty($cek_week)){
                            //edit
                            if($real_jumlah_per_week<>$cek_week->target_production){
                                $sqlupdate_week="UPDATE plan_production_weekly SET target_production=$real_jumlah_per_week,
                                target_barging=null,lastupdated='".date("Y-m-d H:i:s")."' WHERE $filter_week;";                               
                                $rsl_week=$db->query($sqlupdate_week);
                            }
                        }else{
                            //insert
                            $sqlinsert_week="INSERT INTO plan_production_weekly (week_periode_id,plan_id,contractor_id,
                            target_production,target_barging,note,created_time,lastupdated) VALUES(".$wk->id.",$plan_id,$contractor_id,
                            $real_jumlah_per_week,'Hasil generate','".date("Y-m-d H:i:s")."');";
                            $rsl_week=$db->query($sqlinsert_week);
                        }
                        
                    }
                    
                }
                /** generate by month */
                $current_month=mktime(0,0,0,1,1,(int)$data->tahun);
                while(date("Y",$current_month)==$data->tahun){
                    $filter="DATE_FORMAT(tanggal,'%Y-%m')='".date("Y-m",$current_month)."' and contractor_id=$contractor_id and plan_id=$plan_id";
                    $count_month=$db->select("sum(target_production) jml_target_permonth","plan_production_daily")->where($filter)->get(0);
                    if(!empty($count_month)){
                        $real_jumlah_per_month=$count_month->jml_target_permonth;
                        $filter_month="bulan='".date("Y-m",$current_month)."' and contractor_id=$contractor_id and plan_id=$plan_id";
                        $cek_month=$db->select("id,target_production","plan_production_monthly")->where($filter_month)->get(0);
                        if(!empty($cek_month)){
                            //update
                            echo "baru:".$real_jumlah_perday." lama:".$cek_month->target_production."<br />";
                            if($real_jumlah_per_month<>$cek_month->target_production){
                                $sqlupdate_month="UPDATE plan_production_monthly SET target_production=$real_jumlah_per_month,
                                target_barging=null,lastupdated='".date("Y-m-d H:i:s")."' WHERE $filter_month;";
                                echo $sqlupdate_month;
                                $rsl3=$db->query($sqlupdate_month);
                            }
                            
                        }else{
                            //insert
                            $sqlinsert_month="INSERT INTO plan_production_monthly (bulan,plan_id,contractor_id,target_production,
                            target_barging,note,created_time) VALUES('".date("Y-m",$current_month)."',$plan_id,$contractor_id,$real_jumlah_per_month,
                            null,'hasil generate','".date("Y-m-d H:i:s")."');";
                            $rsl3=$db->query($sqlinsert_month);
                        }
                        
                    }
                    $current_month=mktime(0,0,0,((int)date("m",$current_month)+1),(int)date("d",$current_month),(int)date("Y",$current_month));
                }
            }
            
         }
         return $msg;
        
    }
    public function setVerified($verification_id,$current_verification_matrix_id) {
        /** 1. change field state in table verification_production_matrix from draft to verified
         *  2. set verification matrix in table verification_production to next step
         *  3. update current_verification to draft ($next_step_verification_matrix)
         * ================================================================ */
          global $dcistem;
		 $db   = $dcistem->getOption("framework/db");
         $master=new Master_Ref_Model();
         $settings= $master->settings();
         date_default_timezone_set($settings['production_time_zone']);
         $msg    = array();
         $TglSkrg		     =date("Y-m-d H:i:s");
         $tgl_skrg_val		 =$master->scurevaluetable($TglSkrg,"string");
        // $login_as=	$_SESSION['framework']['login_as']; 
		 $ref_id=$_SESSION["framework"]["ref_id"] ;
         $username=$_SESSION["framework"]['current_user']->Username;
         $ma=$this->getTrxVerificationMatrixByID($current_verification_matrix_id);
         if($ma->matrix_state=="verified" ){
            $msg['success']=false;
            $msg['message']="Error, ".$ma->verifier." sudah mmverifikasi";
         }else{
             $access=$this->privilegeVerification($ma->category_id,$ma->matrix_id);
             if($access==true){
                $username_val=$master->scurevaluetable($username);
                // set verifief 
                $sql1="UPDATE verification_production_matrix SET matrix_state='verified',verified_time=$tgl_skrg_val,
                 verifier=$username_val WHERE id=$current_verification_matrix_id;";
                 
                $rsl1=$db->query($sql1);
                if(isset($rsl1->error) and $rsl1->error===true){
              		$msg['success']=false;
               	    $msg['message']="Error0, ".$rsl1->query_last_message;
                }else{
                  
                    $next_step=(int)$ma->matrix_step+1;
                    $next_ma=$this->getTrxVerificationMatrixByStep($verification_id,$next_step);
                    if(!empty($next_ma)){
                        $next_verification_matrix_id=$next_ma->id;
                        $sql2="UPDATE verification_production SET current_verification=$next_verification_matrix_id WHERE id=$verification_id;";
                         
                        $rsl2=$db->query($sql2);
                        if(isset($rsl2->error) and $rsl2->error===true){
                            $msg['success']=false;
                   	        $msg['message']="Error1, ".$rsl2->query_last_message. $sql2;
                        }else{
                            //set nect verification
                            $sql3="UPDATE verification_production_matrix SET matrix_state='draft',lastupdated=$tgl_skrg_val WHERE id=$next_verification_matrix_id;";
                            
                            $rsl3=$db->query($sql3);
                            if(isset($rsl3->error) and $rsl3->error===true){
                                $msg['success']=false;
                   	            $msg['message']="Error2, ".$rsl3->query_last_message;
                            }else{
                                $msg['success']=true;
                   	            $msg['message']="Data sudah diset verified ";
                            }
                        }
                    }else{
                        $msg['success']=true;
           	            $msg['message']="Data sudah diset verified dan sudah selesai";
                    }
                }
             }else{
                $msg['success']=false;
                $msg['message']="Error, Anda tidak berhak untuk melakukan verifikasi";
             }
         }
         return $msg;
        
    }
    


 
 
}
?>