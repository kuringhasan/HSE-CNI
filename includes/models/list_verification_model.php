<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage Biodata Damas Model
 * 
 * @author Hasan <kuring.hasan@gmail.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class List_Verification_Model extends Model {
  
	public function __construct() {
		
	}
    public function getProductionVerification($verification_id,$format="object"){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
        $master		=new Master_Ref_Model();
       	if(trim($verification_id)=="" or  $verification_id == null){
			return array();
		}else{
            $data=$db->select("vp.id,vp.contractor_id,p.name contractor_name,p.alias contractor_alias,date_report,
            DATE_FORMAT(date_report,'%d/%m/%Y')  tgl,vp.shift,vp.category_id,ca.name category_name,vp.current_verification,
            vpm.matrix_state,vpm.matrix_name,vp.created_time,vp.lastupdated_time,vpm.verified_time","verification_production vp
            inner join category_approval ca on ca.id=vp.category_id
            inner join partner p on p.id=vp.contractor_id
            left join verification_production_matrix vpm on vpm.id=vp.current_verification")
    		->where("vp.id=$verification_id")->get(0);//
            if(!empty($data)){
				$rec    	= new stdClass;
                $rec->progress_verification = $this->getTrxVerificationMatrix($data->id);
                $rec->created_detail        = $master->detailtanggal($data->created_time,2);
                $rec->lastupdated_detail    = $master->detailtanggal($data->lastupdated_time,2);
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
    public function getTrxVerificationMatrix($verification_id){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
        $master		=new Master_Ref_Model();
        $usr=new Core_User_Model();
       	if(trim($verification_id)=="" or  $verification_id == null){
			return array();
		}else{
            $list_data=array();
            $data_qry=$db->select("id,verification_id,matrix_state,matrix_id,matrix_name,matrix_step,matrix_role,
            category_id,created_time,lastupdated,verified_time,verifier","verification_production_matrix")
    		->where("verification_id=$verification_id")->orderby("created_time desc")->lim();//
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
    public function getTrxVerificationMatrixByID($id){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
        $master		=new Master_Ref_Model();
       	if(trim($id)=="" or  $id == null){
			return array();
		}else{
            $data=$db->select("id,verification_id,matrix_state,matrix_id,matrix_name,matrix_step,matrix_role,
            category_id,created_time,lastupdated,verified_time,verifier","verification_production_matrix")
    		->where("id=$id")->get(0);//
           
            return $data;
        }
        
			 
	}
    public function getTrxVerificationMatrixByStep($verification_id,$step){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
        $master		=new Master_Ref_Model();
       	if(trim($verification_id)=="" or  $verification_id == null or trim($step)=="" or  $step == null){
			return array();
		}else{
            $data=$db->select("id,verification_id,matrix_state,matrix_id,matrix_name,matrix_step,matrix_role,
            category_id,created_time,lastupdated,verified_time","verification_production_matrix")
    		->where("verification_id=$verification_id and matrix_step=$step")->get(0);//
           
            return $data;
        }
        
			 
	}
    public function getCurrentTrxVerificationMatrix($verification_id){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
       	if(trim($verification_id)=="" or  $verification_id == null){
			return array();
		}else{
		    $cek_last_step=$db->select("max(matrix_step) last_step_verified","verification_production_matrix")
    		->where("verification_id=$verification_id and matrix_state='verified'")->get(0);//
           //echo "<pre>"; print_r($cek_last_step);echo "</pre>";
            if(trim($cek_last_step->last_step_verified)<>""){
                $last_step=$cek_last_step->last_step_verified;
                $next_step=(int)$last_step+1;
                return $this->getTrxVerificationMatrixByStep($verification_id,$next_step);
            }else{
                 $sqlup6="UPDATE verification_production_matrix SET matrix_state=null WHERE verification_id=$verification_id;";
                  
                 $db->query($sqlup6);
                return $this->getTrxVerificationMatrixByStep($verification_id,1);
            }
        }
        
			 
	}
    public function privilegeVerification($category_id,$matrix_id) {
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
		if(trim($category_id)==""){
			return false;
		}else{
		  
            $leveluser_login_current=	$_SESSION['framework']['login_as']; 
            $filter="category_id=$category_id";
            if(trim($matrix_id)<>""){
                $filter=$filter. " and id=$matrix_id";
            }
	        $results = $db->select("role as Privileges", "matrix_approval","array")->where($filter)->get();
            $punya_akses=false;
            foreach($results as $key=>$value){
                $action=$value["Privileges"];
                $array_action= Core::checkSerialize($action);
                $cek=trim($action)==""?false:in_array($leveluser_login_current,$array_action);
                if($cek==true){
                    $punya_akses=true;
                }
                if($leveluser_login_current=="administrator"){
                    $punya_akses=true;
                }
            }
            
					
			return $punya_akses;
		
		}
	}
  
    public function createProductionVerification($category_id,$contractor_id,$tanggal,$shift_id) {
        //format $tanggal=yyyy-mm-dd
	    global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        $settings= $master->settings();
        date_default_timezone_set($settings['production_time_zone']);
        $msg    = array();
		$TglSkrg		     =date("Y-m-d H:i:s");
       	$tgl_skrg_val		 =$master->scurevaluetable($TglSkrg,"string");
        $filter_ver="contractor_id=$contractor_id and category_id=$category_id and shift=$shift_id and DATE_FORMAT(date_report,'%Y-%m-%d')='".$tanggal."'";
        $cek_verification=$db->select("id","verification_production")->where($filter_ver)->get(0);
        if(empty($cek_verification)){//insert
        	$cols="contractor_id,date_report,shift,category_id,created_time";
            $vals="$contractor_id,'".$tanggal."',$shift_id,$category_id,$tgl_skrg_val";
    		$sqlin="INSERT INTO verification_production ($cols) VALUES($vals);";
    		$rsl=$db->query($sqlin);
    		if(isset($rsl->error) and $rsl->error===true){
                $msg['verification_id']="";      
                $msg['success']=false;
                $msg['message']="Error, ".$rsl->query_last_message." ".$sqlin;
    		}else{
    		   $msg['success']=true;
    		    $last   =$db->query("SELECT LAST_INSERT_ID() as new_id;");
                $new    =$db->fetchArray($last);
                $msg['verification_id']=$new['new_id'];                
                $msg['message']="Data sudah ditambahkan"; 
                $this->createProductionVerificationMatrix($new['new_id'],$category_id);
               
            }
        }else{//update
            $verification_id=$cek_verification->id; 
            $msg['success']=false;
            $msg['verification_id']=$verification_id;                
            $msg['message']="Data sudah ada";     
            $this->createProductionVerificationMatrix($verification_id,$category_id);    
        }
        return $msg;
	}
    public function setToDraft($verification_id,$current_step_verification_matrix_id) {
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
         $ma=$this->getTrxVerificationMatrixByID($current_step_verification_matrix_id);
         if($ma->matrix_state=="verified" ){
            $msg['success']=false;
            $msg['message']="Error, ".$ma->verifier." sudah mmverifikasi";
         }else{
             $access=$this->privilegeVerification($ma->category_id,$ma->matrix_id);
             if($access==true){
                $username_val=$master->scurevaluetable($username);
                // set verifief 
                $sql1="UPDATE verification_production_matrix SET matrix_state='draft',lastupdated=$tgl_skrg_val,
                 verifier=$username_val WHERE id=$current_step_verification_matrix_id;";
                $rsl1=$db->query($sql1);
                if(isset($rsl1->error) and $rsl1->error===true){
              		$msg['success']=false;
               	    $msg['message']="Error, ".$rsl1->query_last_message;
                }else{
                    $sql2="UPDATE verification_production SET current_verification=$current_step_verification_matrix_id,lastupdated_time=$tgl_skrg_val WHERE id=$verification_id;";
                     
                    $rsl2=$db->query($sql2);
                    if(isset($rsl2->error) and $rsl2->error===true){
                        $msg['success']=false;
               	        $msg['message']="Error, ".$rsl2->query_last_message;
                    }else{
                        $msg['success']=true;
           	            $msg['message']="Data sudah diset draft ";
                    }
                }
             }else{
                $msg['success']=false;
                $msg['message']="Error, Anda tidak berhak untuk melakukan verifikasi";
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
    public function createProductionVerificationMatrix($verification_id,$category_id) {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        $settings= $master->settings();
        date_default_timezone_set($settings['production_time_zone']);
		$TglSkrg		     =date("Y-m-d H:i:s");
       	$tgl_skrg_val		 =$master->scurevaluetable($TglSkrg,"string");
        $matrix=$this->getMatrixApproval($category_id);
        //echo $verification_id;
        //echo "<pre>";print_r($matrix);echo "</pre>";
        foreach($matrix as $key=>$value){
            $filter_ver="verification_id=$verification_id and category_id=$category_id and matrix_step=".$value->step;
            $cek_matrix=$db->select("id","verification_production_matrix")->where($filter_ver)->get(0);
            if(empty($cek_matrix)){
                $cols="verification_id,matrix_id,matrix_name,matrix_step,matrix_role,category_id,created_time";
                $vals="$verification_id,".$value->id.",'".$value->name."','".$value->step."',
                '".$value->role."',$category_id,$tgl_skrg_val";
        		$sqlin="INSERT INTO verification_production_matrix ($cols) VALUES($vals);";
                $rsl2=$db->query($sqlin);
                
                if(isset($rsl2->error) and $rsl2->error===true){
                }else{
                    $last2   =$db->query("SELECT LAST_INSERT_ID() as new_id;");
                    $new2    =$db->fetchArray($last2);
          //print_r($new2);
                    $matrix_verification_id=$new2['new_id'];  
                   // echo   $matrix_verification_id." step :".$value->step."<br />";
                    if($value->step==1){
                        $sqlin5="UPDATE verification_production  SET last_verification=$matrix_verification_id,
                        lastupdated_time=$tgl_skrg_val WHERE id=$verification_id;";
                        
                        $db->query($sqlin5);
                    }
                }
            }
            
        }
    }
    public function getMatrixApproval($category_id){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
        $master		=new Master_Ref_Model();
       	if(trim($category_id)=="" or  $category_id == null){
			return array();
		}else{
            $data=$db->select("id,name,step,role,category_id","matrix_approval")
    		->where("category_id=$category_id")->orderby("step asc")->get();//
            return $data;
        }
        
			 
	}
    public function getMatrixApprovalByID($ref_verification_matrix_id){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
        $master		=new Master_Ref_Model();
       	if(trim($ref_verification_matrix_id)=="" or  $ref_verification_matrix_id == null){
			return array();
		}else{
            $data=$db->select("id,name,step,role,category_id","matrix_approval")
    		->where("id=$ref_verification_matrix_id")->orderby("step asc")->get(0);//
            if(!empty($data)){
                $rec    	= new stdClass;
                $next_step=(int)$data->step+1;
                $rec->next_matrix_approval    = $this->getMatrixApprovalByStep($next_step,$data->category_id);
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
    public function getMatrixApprovalByStep($step,$category_id,$format="object"){ 
		global $dcistem;
       	$db = $dcistem->getOption("framework/db");
        $master		=new Master_Ref_Model();
       	if(trim($step)=="" or  $step == null or trim($category_id)=="" or  $category_id == null){
			return array();
		}else{
            $data=$db->select("id,name,step,role,category_id","matrix_approval",$format)
    		->where("step=$step and category_id=$category_id")->get(0);//            
            return $data;
        }
        
			 
	}


 
 
}
?>