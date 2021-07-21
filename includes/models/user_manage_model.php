<?php

/**
 * @package Admin
 * @subpackage Admin Login Modul
 * 
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class User_Manage_Model extends Model {
	
	public function __construct($WebID="",$skema_database="") {
		$this->WebID=$WebID;
	}
	 public function getListPagePrivileges($Kondisi="") {
		global $dcistem;
		$db     = $dcistem->getOption("framework/db");
		
		$listdata	=$db->select("lvl.AppLevelListLevelID,lvl.AppLevelListLevelName,lvl.AppLevelListRefName,
		 		pri.AppPagePrivilegePrivileges,pg.AppPageListParentID,pg.AppPageListPageID,pg.AppPageListWebID,
		 		pg.AppPageListPageName,pg.AppPageListPageTitle,pg.AppPageListPageController,
				pg.AppPageListPageGroup,pg.AppPageListPageOrder,
				pg.AppPageListPageProperties","tbaapppagelist pg 
				inner join  tbaapppageprivilege  pri on pri.AppPagePrivilegePageID=pg.AppPageListPageID
				inner join  tbaapplevellist lvl 
				on lvl.AppLevelListLevelID=pri.AppPagePrivilegeLevelID ")->where($Kondisi)->orderby("pg.AppPageListWebID,lvl.AppLevelListLevelID, pg.AppPageListPageOrder asc")->get(); 
				//print_r();exit;
		$lst=array();
        while($data=current($listdata)){
        	$rec=new stdClass;
        	$controller_name = str_replace(" ", "_", ucwords(str_replace("_", " ", $data->AppPageListPageController)))."_Controller";
			$path            		= $dcistem->getOption("system/dir/controller").strtolower($controller_name).".php";
			$rec->PageID         	= $data->AppPageListPageID;
			$rec->WebID         	= $data->AppPageListWebID;
			$rec->ParentID			=$data->AppPageListParentID;
			
			$rec->LevelID			=$data->AppLevelListLevelID;
			$rec->LevelName			=$data->AppLevelListLevelName;
			
			$rec->PageName			=$data->AppPageListPageName;
			$rec->PageTitle			=$data->AppPageListPageTitle;
		//	echo $data->AppPagePrivilegePrivileges;
			$pri=$this->privileges($path,$data->AppPagePrivilegePrivileges);
			$rec->Privileges		=$pri;
			$lst[]=$rec;
			next($listdata);
        }
      // echo "<pre style='text-align:left;'>";print_r($lst); echo "</pre>";exit;
        return  $lst;  
	}	
 public function getPagePrivileges($page_id,$level_id) {
		global $dcistem;
		$db     = $dcistem->getOption("framework/db");
		$Kondisi="AppPageListPageID=$page_id and AppLevelListLevelID='".$level_id."'";
		$data	=$db->select("lvl.AppLevelListLevelID,lvl.AppLevelListLevelName,lvl.AppLevelListRefName,
		 		pri.AppPagePrivilegePrivileges,pg.AppPageListParentID,pg.AppPageListPageID,pg.AppPageListWebID,
		 		pg.AppPageListPageName,pg.AppPageListPageTitle,pg.AppPageListPageController,
				pg.AppPageListPageGroup,pg.AppPageListPageOrder,
				pg.AppPageListPageProperties","tbaapppagelist pg 
				inner join  tbaapppageprivilege  pri on pri.AppPagePrivilegePageID=pg.AppPageListPageID
				inner join  tbaapplevellist lvl 
				on lvl.AppLevelListLevelID=pri.AppPagePrivilegeLevelID ")->where($Kondisi)->get(); 
	//	print_r($data);exit;
            $lst=array();
            if(!empty($data)){
            	$rec=new stdClass;
            	$controller_name = str_replace(" ", "_", ucwords(str_replace("_", " ", $data->AppPageListPageController)))."_Controller";
            	$path            		= $dcistem->getOption("system/dir/controller").strtolower($controller_name).".php";
            	$rec->PageID         	= $data->AppPageListPageID;
            	$rec->WebID         	= $data->AppPageListWebID;
            	$rec->ParentID			=$data->AppPageListParentID;
            	
            	$rec->LevelID			=$data->AppLevelListLevelID;
            	$rec->LevelName			=$data->AppLevelListLevelName;
            	
            	$rec->PageName			=$data->AppPageListPageName;
            	$rec->PageTitle			=$data->AppPageListPageTitle;
            //	echo $data->AppPagePrivilegePrivileges;
            	$pri=$this->privileges($path,$data->AppPagePrivilegePrivileges);
            	$rec->Privileges		=$pri;
            	$lst[]=$rec;
            }
      // echo "<pre style='text-align:left;'>";print_r($lst); echo "</pre>";exit;
        return  $lst;  
	}	
    public function getPagePrivilegesByID($privilege_id) {
		global $dcistem;
		$db     = $dcistem->getOption("framework/db");
		$Kondisi="AppPagePrivilegeID=$privilege_id";
		$data	=$db->select("AppPagePrivilegeID,AppPageListParentID,AppPageListWebID,AppPagePrivilegePageID,AppPageListPageName,
        AppPageListPageTitle,AppPageListPageController,AppPagePrivilegeLevelID,AppPagePrivilegePrivileges","tbaapppageprivilege pri
        inner join tbaapppagelist pg on pg.AppPageListPageID=pri.AppPagePrivilegePageID")->where($Kondisi)->get(0); 
		//print_r($data);
           
        if(!empty($data)){
        	$rec=new stdClass;
        	$controller_name = str_replace(" ", "_", ucwords(str_replace("_", " ", $data->AppPageListPageController)))."_Controller";
        	$path            		= $dcistem->getOption("system/dir/controller").strtolower($controller_name).".php";
        	$rec->PageID         	= $data->AppPagePrivilegePageID;
        	$rec->WebID         	= $data->AppPageListWebID;
        	$rec->ParentID			=$data->AppPageListParentID;
        	
        	$rec->LevelID			=$data->AppPagePrivilegeLevelID;
        	$rec->LevelName			=$data->AppLevelListLevelName;
        	
        	$rec->PageName			=$data->AppPageListPageName;
        	$rec->PageTitle			=$data->AppPageListPageTitle;
            $page=trim($data->AppPageListPageTitle)==""?$data->AppPageListPageName:$data->AppPageListPageName." (".$data->AppPageListPageTitle.")";
            $rec->Page			=$page;
            $rec->PagePath			=$path;
        //	echo $data->AppPagePrivilegePrivileges;
        	$pri=$this->privileges($path,$data->AppPagePrivilegePrivileges);
        	$rec->Privileges		=$pri;
            $html="";
            //$arr_rc=array();
			$j=0;
     		if(!empty($pri)){
                while($pr=current($pri)){
         			$ck=$pr['check']==true?" checked='checked' ":"";
         			$input  ="<input type=\"checkbox\" name=\"privileges[".$j."]\" value=\"".$pr['method']."\" $ck  />";
                    $html   = trim($html)==""?$input." ".$pr['method']:$html."<br />".$input." ".$pr['method'];
         		
    				$j++;
         			next($pri);
         		}
            }
            $rec->HtmlPrivileges		=$html;
            return $rec;
        	
        }else{
           return  array();  
        }
      // echo "<pre style='text-align:left;'>";print_r($lst); echo "</pre>";exit;
        //return  $lst;  
	}	
  public function pageTreePrivileges($ParentID=0, $PageID = "",$Kondisi="") {
		global $dcistem;
		$db     = $dcistem->getOption("framework/db");
		$WebID  = (trim($WebID) <> "" ? $WebID : $dcistem->getOption("system/web/id"));
		 $login_as=	$_SESSION['framework']['login_as']; 
		$ref_id=$_SESSION["framework"]["ref_id"] ;
        $Operator=$_SESSION["framework"]['current_user']->Username;
       
        //echo "cek".url::current("add");exit;
		$page=new Core_Page_Model();
		 $modellogin=new Adm_Login_Model();
		$Kondisi1="AppPageListParentID=$ParentID";
		$Kondisi2=trim($Kondisi)<>""?$Kondisi1." AND ".$Kondisi:$Kondisi1;
		$list   = $this->getListPagePrivileges($Kondisi2);
		//echo "<pre style='text-align:left;'>";print_r($list);echo "</pre>";exit;
		$result = array();
		if (!empty($list))
		{
			while($data = current($list)) {
		
				$tmp                 = new stdClass;
				$tmp->PageID         = $data->PageID;
				$tmp->ParentID        = $data->ParentID;
				$tmp->WebID         = $data->WebID;
				$tmp->PageName       = $data->PageName;
				$tmp->PageTitle      = $data->PageTitle;
				$tmp->LevelID 		= $data->LevelID;
				$tmp->LevelName 		= $data->LevelName;
			/*	$tmp->PageController = $data->PageController;
				$tmp->PageGroupID    = $data->PageGroupID;
				$tmp->PageOrder		= $data->PageOrder;
				$tmp->PageVisbility  = $data->PageVisbility;*/
			
	     		$arr_rc=array();
	     		$j=0;
	     		if(count($data->Privileges)>0)
	     		{
		     		while($pr=current($data->Privileges)){
		     			$rc=new stdClass;
		     		//	print_r($pr);
		     			$ck=$pr['check']==true?" checked='checked' ":"";
			     		$input="<input type=\"checkbox\" name=\"privileges[".$j."]\" value=\"".$pr['method']."\" $ck  disabled='disabled'/>";
		     			/*$ck=$pr['check']==true?" checked ":"";
		     			$input="<input type=\"checkbox\" name=\"privileges[".$pr['method']."]\" value=\"\" $ck  disabled/>";*/
		     			$rc->method=$pr['method'];
						$rc->input=$input;
						$rc->priv=$pr['check'];
						$arr_rc[]=$rc;
						$j++;
		     			next($data->Privileges);
		     		}
	     		}
	     		$tmp->Privileges		=$arr_rc;
				
				$Childs     			= $this->pageTreePrivileges($data->PageID, $PageID,$Kondisi);
				$tmp->PageChilds     =$Childs;
			$Kontrol=$modellogin->privilegeInputForm("button","btn_add","btn_add","Add",$PageID,"AddSubmenu","onclick=\"addsubmenu(".$data->PageID.");\" title=\"Tambah Submenu\" class=\"btn add\"");
				$Kontrol=$Kontrol.$modellogin->privilegeInputForm("button","","btn-edit".$data->PageID,"Edit",$page_id_current,"edit","onclick=\"edit('".url::current("edit")."','".$data->PageID."','".$data->LevelID."');\" class='btn edit' title='Edit Page'");
			$Kontrol = $Kontrol.$modellogin->privilegeInputForm("button","","btn-delete".$data->PageID,"Hapus",$page_id_current,"delete","onclick=\"hapus('".url::current("delete")."','".$data->PageID."','".$data->LevelID."');\" class='btn del'   title='Hapus Menu'");
	//		print_r($data);
			$tmp->Kontrol  = $Kontrol;
				$result[]            = $tmp;
			//	}
				next($list);
			}

		}
		//echo "<pre style='text-align:left;'>";print_r($result);echo "</pre>";exit;
		return $result;
	}
    public function pageTree($ParentID=0, $PageID = "",$Kondisi="") {
		global $dcistem;
		$db     = $dcistem->getOption("framework/db");
		$WebID  = (trim($WebID) <> "" ? $WebID : $dcistem->getOption("system/web/id"));
		 $login_as=	$_SESSION['framework']['login_as']; 
		$ref_id=$_SESSION["framework"]["ref_id"] ;
        $Operator=$_SESSION["framework"]['current_user']->Username;
       
        //echo "cek".url::current("add");exit;
		$page=new Core_Page_Model();
		 $modellogin=new Adm_Login_Model();
		$Kondisi1=$page->translate_condition(array(
			"ParentID" => $ParentID,
			"WebID"    => $WebID
		));
		$Kondisi=trim($Kondisi)<>""?$Kondisi1." AND ".$Kondisi:$Kondisi1;
		
		$list   = $page->getList($Kondisi, "PageOrder");
		$result = array();
		if (!empty($list))
		{
			while($data = current($list)) {
			//print_r ($data);
			//	if(!$data->PageProperties["hide"]) {
			$tmp                 = new stdClass;
			$tmp->PageID         = $data->PageID;
			$tmp->ParentID         = $data->ParentID;
			$tmp->WebID         = $data->WebID;
			$tmp->PageName       = $data->PageName;
			$tmp->PageTitle      = $data->PageTitle;
			$tmp->ParentID       = $data->ParentID;
			$tmp->PageProperties = $data->PageProperties;
			$tmp->PageController = $data->PageController;
			$tmp->PageGroupID    = $data->PageGroupID;
			$tmp->PageOrder		= $data->PageOrder;
			$tmp->PageVisbility  = $data->PageVisbility;
			$Childs     = $this->pageTree($data->PageID, $PageID,"");
			$tmp->PageChilds     =$Childs;
			$title="";
			if (trim($data->PageTitle)=="" and trim($data->PageName)<>""){
				$title="[".$data->PageID."] ".$data->PageName;
			}elseif(trim($data->PageTitle)<>"" and trim($data->PageName)==""){
				$title="[".$data->PageID."] ".$data->PageTitle;
			}elseif(trim($data->PageTitle)<>"" and trim($data->PageName)<>""){
				$title="[".$data->PageID."] ".$data->PageName." (".$data->PageTitle.")";
			}else{
				$title="[".$data->PageID."]";
			}
			$serial=array("PageID"=>$data->PageID,
                            "Title"=>$title);//    
            $js=htmlentities(json_encode($serial), ENT_QUOTES);  
			
			
		//	if (count($Childs)>0){
				$Kontrol=$modellogin->privilegeInputForm("button","","btn-add".$data->PageID,"add",$login_as, $PageID,"Tambah","onclick=\"bukaform('add','".url::current("add")."','".$js."');\" class='btn-add' title='Tambah Page' style='float:left;'");
		//	}
			$Kontrol=$Kontrol.$modellogin->privilegeInputForm("button","","btn-edit".$data->PageID,"edit",$login_as, $PageID,"Edit","onclick=\"bukaform('edit','".url::current("edit")."','".$data->PageID."');\" class='btn-edit' title='Edit Page' style='float:left;'");
			$Kontrol = $Kontrol.$modellogin->privilegeInputForm("button","","btn-delete".$data->PageID,"delete",$login_as,$PageID,"Hapus","onclick=\"bukaform('delete','".url::current("delete")."','".$data->PageID."');\" class='btn-delete'   title='Hapus Page' style='float:left;'");
	//		print_r($data);
			$tmp->Kontrol  = $Kontrol;
			$result[]            = $tmp;
			//	}
				next($list);
			}

		}
		
		return $result;
	}
	
	public function userlist($filter="",$awal=null,$jumlah_tampil=null) {
		global $dcistem;
		$db     = $dcistem->getOption("framework/db");
		$login=new Adm_Login_Model();
		/*
		if(trim($filter)<>""){
			$filter=trim($this->WebID)==""?$filter:"AppPageListWebID='".$this->WebID."' AND ".$filter;
		}else{
			$filter="AppPageListWebID='".$this->WebID."'";
		}
		*/
		$lim="";
		if($awal<>null and $jumlah_tampil<>null){
			$lim=trim($filter)==""?"":$filter."   LIMIT $awal,$jumlah_tampil";
		}
	
		$listdata=$db->select("AppUserListUsername,AppUserListPassword,AppUserListName,AppUserListEmail,
		AppUserListNoInduk,AppUserListHP,AppUserListIDPersonal,
		AppUserLevelLevelID,AppLevelListLevelName,AppLevelListRefName,AppUserLevelRefID","tbaappuserlist usr
		 left join tbaappuserlevel ul on ul.AppUserLevelUsername=usr.AppUserListUsername
		 inner join tbaapplevellist lvl on lvl.AppLevelListLevelID=ul.AppUserLevelLevelID")
		 ->where($filter)->get($awal,$jumlah_tampil);
     	
     	$url_edit   = url::current("edit");
   		$url_delete   = url::current("delete");
   		$url_reset   = url::current("resetpassword");
     	
     	$no=0;
     	while($data=current($listdata)){
     		$List[$data->AppUserListUsername]['Username']	=$data->AppUserListUsername;
     		$List[$data->AppUserListUsername]['Nama']		=$data->AppUserListName;
     		$List[$data->AppUserListUsername]['Email']		=$data->AppUserListEmail;
     		$List[$data->AppUserListUsername]['HP']		=$data->AppUserListHP;
     		$List[$data->AppUserListUsername]['NoInduk']	=$data->AppUserListNoInduk;
     		$List[$data->AppUserListUsername]['UserLevel'][$data->AppUserLevelLevelID]['LevelID']		=$data->AppUserLevelLevelID;
     		$List[$data->AppUserListUsername]['UserLevel'][$data->AppUserLevelLevelID]['LevelName']	=$data->AppLevelListLevelName;
     		$List[$data->AppUserListUsername]['UserLevel'][$data->AppUserLevelLevelID]['LevelRefName']	=$data->AppLevelListRefName;
     		$List[$data->AppUserListUsername]['UserLevel'][$data->AppUserLevelLevelID]['LevelRefID']	=$data->AppUserLevelRefID;
 		
 			$url_del      = url::current("delete",$data->AppUserListUsername);
			$url_edit =url::current("edit",$data->AppUserListUsername);
			$tombol=$login->privilegeInputForm("link","","","<i class=\"fa fa-fw fa-refresh\"></i>",$this->page->PageID,"resetpassword","title='Reset Password' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs btn-edit-data\" target=\"_blank\"");
			$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-fw fa-gear\"></i>",$this->page->PageID,"edit","title='Edit Data Pegawai' href=\"".$url_edit."\" class=\"btn btn-primary btn-xs btn-edit-data\" target=\"_blank\"");
			$tombol=$tombol."&nbsp".$login->privilegeInputForm("link","","","<i class=\"fa fa-fw fa-trash-o\"></i>",$this->page->PageID,"del","title='Hapus Data Pegawai' data-toggle=\"modal\" data-remote=\"true\" href=\"".$url_del."\" class=\"btn btn-primary btn-xs btn-del-data\" data-target=\"#largeModal\" role=\"".$data->pNama."\"");
			$List[$data->AppUserListUsername]['Kontrol']=$tombol;  
     		/*
     		$List[$data->AppUserListUsername]['Kontrol']="<button type='button' class='btn reset' onclick=\"bukaform('reset','".$url_reset."','".$data->AppUserListUsername."');\"  title='Reset Password' >Reset</button><button type='button' class='btn edit' onclick=\"bukaform('edit','".$url_edit."','".$data->AppUserListUsername."');\" title='Edit User' >Edit</button><button type='button' class='btn delete' onclick=\"bukaform('delete','".$url_delete."','".$data->AppUserListUsername."');\"  title='Hapus User' >Hapus</button>";  */
     		
			 $no++;
     		next($listdata);
     	}
     	//echo "<pre>";print_r($ListData);echo "</pre>";
    return $List;
	}
 public function jsonData($pilihan,$query="",$array_data=array()) {
        global $dcistem;
		$db   = $dcistem->getOption("framework/db");
         $List=array();
         
         switch($pilihan){
         	case "user": 
         	//print_r($array_data);
         		$user=$array_data['user'];
               
				$sortir="";
				if (trim($user)<>""){
					$sortir=trim($sortir)==""?"AppUserListUsername = '".$user."'":$sortir." and AppUserListUsername = '".$user."'";
				}
				if(trim($sortir)<>""){
					$sortir=trim($this->WebID)==""?$sortir:"AppPageListWebID='".$this->WebID."' AND ".$sortir;
				}else{
					$sortir="AppPageListWebID='".$this->WebID."'";
				}
				$listuser=$db->select("AppUserListUsername,AppUserListName,AppUserListEmail,AppUserListNoInduk,AppUserListPassword,
			 	AppUserLevelLevelID,AppLevelListLevelName,AppLevelListRefName,AppUserLevelRefID,AppPageListParentID,AppPageListPageID,
				 AppPageListWebID,AppPageListPageName,
			 	AppPageListPageOrder,AppPageListPageProperties","vwaUserPrivilege")->where($sortir)->get();
     	
     	
	     	$List=array();
	     	while($data=current($listuser)){
	     		$List['Username']	=$data->AppUserListUsername;
	     		$List['Nama']		=$data->AppUserListName;
	     		$List['Email']		=$data->AppUserListEmail;
	     		$List['NoInduk']		=$data->AppUserListNoInduk;
	     		$List['UserLevel'][$data->AppUserLevelLevelID]['LevelID']		=$data->AppUserLevelLevelID;
	     		$List['UserLevel'][$data->AppUserLevelLevelID]['LevelName']		=$data->AppLevelListLevelName;
	     		$List['UserLevel'][$data->AppUserLevelLevelID]['LevelRefID']	=$data->AppUserLevelRefID;
	     		$List['UserLevel'][$data->AppUserLevelLevelID]['LevelRefName']	=$data->AppLevelListRefName;
	     		
	     		next($listuser);
	     	}
                 
            break;
            case "level": 
         	
				$LevelID=$array_data['LevelID'];
               
				$sortir=trim($this->WebID)==""?"":"AppPageListWebID='".$this->WebID."'";
				if (trim($LevelID)<>""){
					$sortir=trim($sortir)==""?"AppLevelListLevelID = '".$LevelID."'":$sortir." and AppLevelListLevelID = '".$LevelID."'";
				}
				if (trim($nama)<>""){
					$sortir=trim($sortir)==""?" AppLevelListLevelName like '%".$nama."%'":$sortir." and AppLevelListLevelName like '%".$nama."%'";
				}
				
				$level=$db->select("AppLevelListLevelID,AppLevelListLevelName,ApplevelListRefName","tbaAppLevelList")->where($sortir)->get(0);
     	
	     		$List['LevelID']	=$level->AppLevelListLevelID;
	     		$List['LevelName']	=$level->AppLevelListLevelName;
	     		$List['RefName']	=$level->ApplevelListRefName;
		     	
                 
            break;
            case "levellist": 
				$sortir="";
				if (trim($nama)<>""){
					$sortir="(AppLevelListLevelName like '%".$nama."%' or AppLevelListLevelID like '%".$nama."%')";
				}
				$listlevel=$db->select("AppLevelListLevelID,AppLevelListLevelName,
				AppLevelListRefName","tbaapplevellist")->where($sortir)
				->orderby("AppLevelListLevelName asc")->get();
		     	$List=array();
		     	$i=0;
		     	while($data=current($listlevel)){
		     		$List[$i]['LevelID']	=$data->AppLevelListLevelID;
		     		$List[$i]['LevelName']	=$data->AppLevelListLevelName;
		     		$List[$i]['RefName']	=$data->AppLevelListRefName;
		     	
		     		$i++;
		     		next($listlevel);
		     	}
                 
            break;
            case "page": 
         		$sortir="";
				$PageID=$array_data['PageID'];
                if (trim($nama)<>""){
					$sortir=trim($sortir)==""?" AppPageListPageTitle like '%".$nama."%'":$sortir." and AppPageListPageTitle like '%".$nama."%'";
				}
				if (trim($PageID)<>""){
					$sortir=trim($sortir)==""?"AppPageListPageID = '".$PageID."'":$sortir." and AppPageListPageID = '".$PageID."'";
				}
			
				
				$page=$db->select("AppPageListPageID as PageID, AppPageListParentID as ParentID, AppPageListWebID as WebID, 	
		AppPageListPageName as PageName, AppPageListPageTitle as PageTitle, AppPageListPageController as PageController, 
		AppPageListPageOrder as PageOrder,AppPageListPageProperties as PageProperties, AppPageListPageGroup  as PageGroupID, 
		AppPageListVisibility as PageVisibility","tbaapppagelist")->where($sortir)->orderby("AppPageListPageID asc")->get(0);
		
     			if(count($page)>=1){
     				$List['PageID']			=$page->PageID;
		     		$List['ParentID']		=$page->ParentID;
		     	//	$List['Parent']			=$this->jsonData("page","",array("PageID"=>$page->ParentID));
		     		$parent=$this->jsonData("page","",array("PageID"=>$page->ParentID));
		     		$List['Parent']			=$parent;
		     		$List['ParentPageID']			=$parent['PageID'];
		     		$List['ParentPageName']			=$parent['PageName'];
		     		$List['ParentPageTitle']		=$parent['PageTitle'];
		     		$List['WebID']			=$page->WebID;
		     		$List['PageName']		=$page->PageName;
		     		$List['PageTitle']		=$page->PageTitle;
		     		$List['PageController']	=$page->PageController;
		     		
		     		$List['PageOrder']		=$page->PageOrder;
		     		$List['PageProperties']	= unserialize($page->PageProperties);
		     		$List['PageGroupID']	=$page->PageGroupID;
		     		$List['PageVisibility']	=$page->PageVisibility;
	     		}
		     	//print_r($List);exit;
                 
            break;
            case "pagelist": 
                $filter="AppPageListWebID='kpbs-erp'";
                if($array_data['action']=="add"){
                    //$filter=$filter." and ifnull(AppPagePrivilegePageID,'')<>''";
                }
                if(trim($query)<>""){
                   $filter="AppPageListPageName like '%".$query."%' or AppPageListPageTitle like '%".$query."%' ";
                }
                
                $listdata=$db->select("AppPageListPageID as PageID, AppPageListParentID as ParentID, AppPageListWebID as WebID, 	
                AppPageListPageName as PageName, AppPageListPageTitle as PageTitle, AppPageListPageController as PageController, 
                AppPageListPageOrder as PageOrder,AppPageListPageProperties as PageProperties","tbaapppagelist pg
                left join tbaapppageprivilege pr on pr.AppPagePrivilegePageID=pg.AppPageListPageID")
                ->where($filter)->orderby("AppPageListPageID asc")->get();
		          if(!empty($listdata)){
		              $i=0;
            		while($data=current($listdata)){
            		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
                        $controller_name = str_replace(" ", "_", ucwords(str_replace("_", " ", $data->PageController)))."_Controller.php";
            		     $page=trim($data->PageTitle)==""?$data->PageName:$data->PageName." (".$data->PageTitle.")";
            
                        $List[$i]['ID']=$data->PageID;
            		    $List[$i]['PageName']=$data->PageName;
            		    $List[$i]['PageTitle']=$data->PageTitle;
            		    $List[$i]['Lengkap']="<div class='label_typeahead'>".$data->PageID." ".$page."<br /> ".$controller_name."</div>";
            		    $i++;
            		    next($listdata);
            		}
                }
     			
		     //	print_r($List);exit;
                 
            break;
            
            case "privileges": 
         		$sortir="";
				$PageID=$array_data['PageID'];
				$LevelID=$array_data['LevelID'];
                if (trim($nama)<>""){
					$sortir=trim($sortir)==""?" AppPageListPageTitle like '%".$nama."%'":$sortir." and AppPageListPageTitle like '%".$nama."%'";
				}
				if (trim($PageID)<>""){
					$sortir=trim($sortir)==""?"AppPageListPageID = '".$PageID."'":$sortir." and AppPageListPageID = '".$PageID."'";
				}
				if (trim($LevelID)<>""){
					$sortir=trim($sortir)==""?"AppLevelListLevelID = '".$LevelID."'":$sortir." and AppLevelListLevelID = '".$LevelID."'";
				}
			
				
			$page	=$db->select("lvl.AppLevelListLevelID,lvl.AppLevelListLevelName,lvl.AppLevelListRefName,
		 		pri.AppPagePrivilegePrivileges,pg.AppPageListParentID,pg.AppPageListPageID,pg.AppPageListWebID,
		 		pg.AppPageListPageName,pg.AppPageListPageTitle,pg.AppPageListPageController,
				pg.AppPageListVisibility,pg.AppPageListPageGroup,pg.AppPageListPageOrder,
				pg.AppPageListPageProperties","tbaapppagelist pg 
				inner join  tbaapppageprivilege  pri on pri.AppPagePrivilegePageID=pg.AppPageListPageID
				inner join  tbaapplevellist lvl 
				on lvl.AppLevelListLevelID=pri.AppPagePrivilegeLevelID ")->where($sortir)->orderby("pg.AppPageListWebID,lvl.AppLevelListLevelID, pg.AppPageListPageOrder asc")->get(0); 
		
     			if(count($page)>=1){
     				$controller_name = str_replace(" ", "_", ucwords(str_replace("_", " ", $page->AppPageListPageController)))."_Controller";
					$path            		= $dcistem->getOption("system/dir/controller").strtolower($controller_name).".php";

					$priv=$this->privileges($path,$page->AppPagePrivilegePrivileges);
     				$arr_rc=array();
     				$j=0;
		     		while($pr=current($priv)){
		     			$rc=new stdClass;
		     			$ck=$pr['check']==true?" checked='checked' ":"";
		     			$input="<input type=\"checkbox\" name=\"privileges[".$j."]\" value=\"".$pr['method']."\" $ck  />";
		     			$rc->method=$pr['method'];
						$rc->input=$input;
						$rc->priv=$pr['check'];
						$arr_rc[]=$rc;
						$j++;
		     			next($priv);
		     		}
		     		$List['Privileges']		=$arr_rc;
     				
     				$List['PageID']			=$page->AppPageListPageID;
		     		$List['ParentID']		=$page->AppPageListParentID;
		     		$List['LevelID']		=$page->AppLevelListLevelID;
		     		$List['WebID']			=$page->AppPageListWebID;
		     	//	$List['Parent']			=$this->jsonData("page","",array("PageID"=>$page->ParentID));
		     		$parent=$this->jsonData("page","",array("PageID"=>$page->AppPageListParentID));
		     		$List['Parent']			=$parent;
		     		$List['ParentPageID']			=$parent['PageID'];
		     		$List['ParentPageName']			=$parent['PageName'];
		     		$List['ParentPageTitle']		=$parent['PageTitle'];
		     		$List['PageName']		=$page->AppPageListPageName;
		     		$List['PageTitle']		=$page->AppPageListPageTitle;
		     		$List['PageController']	=$page->AppPageListPageController;
		     		
		     		$List['PageOrder']		=$page->AppPageListPageOrder;
		     		$List['PageProperties']	= unserialize($page->AppPageListPageProperties);
		     		$List['PageGroupID']	=$page->AppPageListPageGroup;
		     		$List['PageVisibility']	=$page->AppPageListVisibility;
	     		}
		     	//echo print_r($List);exit;
                 
            break;
            case "listwebid": 
         		$sortir="";
                if (trim($nama)<>""){
					$sortir=trim($sortir)==""?" AppPageListWebID like '%".$nama."%'":$sortir." and AppPageListWebID like '%".$nama."%'";
				}
				$listweb=$db->select("DISTINCT AppPageListWebID ","tbaapppagelist")->where($sortir)->orderby("AppPageListWebID asc")->get();
		
     			if(count($listweb)>=1){
		     		$i=0;
     				while($data=current($listweb)){     		
			     		$List[$i]['WebID']			=$data->AppPageListWebID;
			     		$List[$i]['WebName']		=$data->AppPageListWebID;
			     	
			     		$i++;
			     		next($listweb);
			     	}
	     		}
		     	//print_r($List);exit;
                 
            break;
            case "cek_pageid": 
         		$sortir="";
                $PageID=$array_data['PageID'];
                if (trim($nama)<>""){
					$sortir=trim($sortir)==""?" AppPageListPageID like '%".$nama."%'":$sortir." and AppPageListPageID like '%".$nama."%'";
				}
				if (trim($PageID)<>""){
					$sortir=trim($sortir)==""?"AppPageListPageID = '".$PageID."'":$sortir." and AppPageListPageID = '".$PageID."'";
				}
				if (trim($sortir)<>""){
					$cekpage=$db->select("AppPageListPageID,AppPageListPageName,AppPageListPageTitle,AppPageListPageController ","tbaapppagelist")->where($sortir)->get(0);
	     			if(count($cekpage)==0){
				     		$List['tersedia']		=true;
				     		$List['pesan']			="Dapat digunakan";
		     		}else{
		     			$List['data']['PageID']			=$cekpage->AppPageListPageID;
			     		$List['data']['PageName']		=$cekpage->AppPageListPageName;
			     		$List['data']['PageTitle']		=$cekpage->AppPageListPageTitle;
		     			$List['tersedia']		=false;
				     	$List['pesan']			="Sudah digunakan";
		     		}
		     	}
		     	//print_r($List);exit;
                 
            break;
         
        }
        return $List;    
    }  
    
    public function addlevel($Username,$LevelID,$ref_id="") {
		global $dcistem;
		$prefdb = $dcistem->getOption("database/admindb");  
        $db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
       
        $msg=array();
		$cek=$db->select("AppUserLevelUsername,AppUserLevelLevelID,AppUserLevelRefID","tbaappuserlevel")
		->where("AppUserLevelUsername='".$Username."' and AppUserLevelLevelID='".$LevelID."'")->get();
        if(empty($cek)){
           
	        $sqlin="";
	        $User_val      =$master->scurevaluetable($Username,"string");
	        $LevelID_val   =$master->scurevaluetable($LevelID,"string");
            $ref_id_val    =$master->scurevaluetable($ref_id,"string");
			$cols="AppUserLevelUsername,AppUserLevelLevelID,AppUserLevelRefID";
			$values="$User_val,$LevelID_val,$ref_id_val";
			$sqlin="INSERT INTO tbaappuserlevel ($cols) VALUES ($values);";
            $rsl=$db->query($sqlin);
    		if(isset($rsl->error) and $rsl->error===true){
    	   	 		$msg['success']=false;
                	$msg['message']="Error, ".$rsl->query_last_message;
    		}else{
    		  
                $msg['success']=true;
               	$msg['message']="Level <strong>$NamaLevel</strong> sudah ditambahkan.";
            }
            
            
        }else{
             $msg['success']=false;
             $msg['message']="Level <strong>$NamaLevel</strong> untuk username <strong>$Username</strong> sudah ada.";
        }
        return  $msg;       
    }
public function editlevel($Username,$LevelID,$RefID="",$NamaLevel="") {
		global $dcistem;
		$prefdb = $dcistem->getOption("database/admindb");  
        $db   = $dcistem->getOption("framework/db");
        $master=new Master_Ref_Model();
        $msg=array();
        $cek=$db->select("AppUserLevelUsername,AppUserLevelLevelID,AppUserLevelRefID",$this->SkemaDatabase."tbaAppUserLevel")
		->where("AppUserLevelUsername='".$Username."' and AppUserLevelLevelID='".$LevelID."'")->get();
        if(count($cek)==1){
           
	        $sqlin="";
	        $RefID=$master->scurevaluetable($RefID,"string");
			$sqlin="UPDATE ".$this->SkemaDatabase."tbaAppUserLevel SET  AppUserLevelRefID=$RefID WHERE AppUserLevelUsername='".$Username."' and AppUserLevelLevelID='".$LevelID."';";
            try{
            	$db->query($sqlin);
                $msg['sukses']=true;
                $msg['pesan']="Level <strong>$NamaLevel</strong> untuk username <strong>$Username</strong> sudah diubah."; 
            }catch(Exception $e) {
            	$msg['sukses']=false;
                $msg['pesan']=="Error, ".$e->getMessage();
            }
        }else{
            $msg['sukses']=false;
            $msg['pesan']="Level <strong>$NamaLevel</strong> untuk username <strong>$Username</strong> tidak ada dalam database.";
        }
        return  $msg;       
    }
 public function deletelevel($Username,$LevelID,$NamaLevel="") {
		global $dcistem;
		
        $db   = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master=new Master_Ref_Model();
        $sqlin="";
        $msg=array();
        $User	=$master->scurevaluetable($Username,"string");
        $LevelID=$master->scurevaluetable($LevelID,"string");
		$sqlin="DELETE FROM ".$this->SkemaDatabase."tbaAppUserLevel 
		    WHERE AppUserLevelUsername='".$Username."' and AppUserLevelLevelID=$LevelID;";
        if($db->query($sqlin))
        {
            $msg['sukses']=true;
            $msg['pesan']="Level <strong>$NamaLevel</strong> untuk username <strong>$Username</strong> sudah dihapus.";
        
        }else{
        	$msg['sukses']=false;
            $msg['pesan']=="Error, Gagal melakukan panambahan data";
        }
        return  $msg;       
    }
    
    public function edituser($username,$levels=array(),$full_name,$email="",$hp="", $no_induk="",$personal_id="") {
		global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master	    = new  Master_Ref_Model();
        $msg=array();
        if(trim($username)=="" or  $username == null or trim($full_name)=="" or  $full_name == null  or empty($levels)){
       	    $msg['success']=false;
            $msg['message']=="Username, Nama Lengkap dan minimal satu role harus diisi";
        }else{
            
            $user=new Core_User_Model();
            $chek_user=$user->getDataByUsername($username);
            $msg_error="";
            if (empty($chek_user)){
                $msg_error="Username tidak ditemukan";
            }
            if(trim($msg_error)==""){
                $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
               
              
                $full_name_val	=$master->scurevaluetable($full_name);
                $email_val	=$master->scurevaluetable($email);
                $hp_val	=$master->scurevaluetable($hp);
                $no_induk_val	=$master->scurevaluetable($no_induk);
               // $personal_id_val	=$master->scurevaluetable($personal_id,"number");
                $cols_and_vals="AppUserListName=$full_name_val,AppUserListEmail=$email_val,AppUserListHP=$hp_val";
             
                $sqlin=" UPDATE tbaappuserlist SET $cols_and_vals WHERE AppUserListUsername='".$username."';";
                $rsl=$db->query($sqlin);
        		if(isset($rsl->error) and $rsl->error===true){
        	   	 		$msg['success']=false;
                    	$msg['message']="Error, ".$rsl->query_last_message;
        		}else{
        		    $sqldel="DELETE FROM tbaappuserlevel  WHERE AppUserLevelUsername='".$username."';";
                    $rsldel=$db->query($sqlin);
                    if(isset($rsldel->error) and $rsldel->error===true){
        	   	 		$msg['success']=false;
                    	$msg['message']="Error, ".$rsldel->query_last_message;
                    }else{
            		    foreach($levels as $key=>$value){
            		       if(trim($value['level_id'])<>"" and trim($value['ref_id'])<>""){
                                $this->addlevel($username,$value['level_id'],$value['ref_id']);
                           }
            		    }
                        $msg['success']=true;
                       	$msg['message']="Perubahan data user sudah disimpan";
                    }
                }
            }else{
                $msg['success']=false;
               	$msg['message']="Username sudah digunakan, silahkan gunakan yang lain";
            }
           
        }
        return  $msg; 
    }
    public function adduser($username,$password,$levels=array(),$full_name,$email="",$hp="", $no_induk="",$personal_id="") {
		global $dcistem;
        $db   = $dcistem->getOption("framework/db");
        date_default_timezone_set("Asia/Jakarta");
        $master	    = new  Master_Ref_Model();
        $msg=array();
        if(trim($username)=="" or  $username == null or trim($password)=="" or  $password == null or empty($levels)){
       	    $msg['success']=false;
            $msg['message']=="Username, password dan minimal satu role harus diisi";
        }else{
            
            $user=new Core_User_Model();
            $chek_user=$user->getDataByUsername($username);
            $msg_error="";
            if (!empty($chek_user)){
                $msg_error="Username sudah digunakan";
            }
            if(trim($msg_error)==""){
                $tgl_skrg_val		=$master->scurevaluetable($TglSkrg,"string");
               
                $username_val	=$master->scurevaluetable($username);
                $password_hash=TEXT::better_crypt($password,10);
                $password_val	=$master->scurevaluetable($password_hash);
                $full_name_val	=$master->scurevaluetable($full_name);
                $email_val	=$master->scurevaluetable($email);
                $hp_val	=$master->scurevaluetable($hp);
                $no_induk_val	=$master->scurevaluetable($no_induk);
               // $personal_id_val	=$master->scurevaluetable($personal_id,"number");
                $cols="AppUserListUsername,AppUserListPassword,AppUserListName,AppUserListEmail,AppUserListHP";
                $values="$username_val,$password_val,$full_name_val,$email_val,$hp_val";
                $sqlin=" INSERT INTO  tbaappuserlist ($cols) VALUES($values)";
                $rsl=$db->query($sqlin);
        		if(isset($rsl->error) and $rsl->error===true){
        	   	 		$msg['success']=false;
                    	$msg['message']="Error, ".$rsl->query_last_message;
        		}else{
        		    foreach($levels as $key=>$value){
        		       if(trim($value['level_id'])<>"" and trim($value['ref_id'])<>""){
                            $this->addlevel($username,$value['level_id'],$value['ref_id']);
                       }
        		    }
                    $msg['success']=true;
                   	$msg['message']="User sudah ditambahkan";
                }
            }else{
                $msg['success']=false;
               	$msg['message']="Username sudah digunakan, silahkan gunakan yang lain";
            }
           
        }
        return  $msg;       
    }
   public function pagePrivilegeslist($sortir="") {
		global $dcistem;
		$prefdb = $dcistem->getOption("database/admindb");  
        $db   	= $dcistem->getOption("framework/db");
        $list	=$db->select("lvl.AppLevelListLevelID,lvl.AppLevelListLevelName,lvl.AppLevelListRefName,pri.AppPagePrivilegePrivileges,pg.AppPageListParentID,
		pg.AppPageListPageID,pg.AppPageListWebID,pg.AppPageListPageName,pg.AppPageListPageTitle,pg.AppPageListPageController,
		pg.AppPageListVisibility,pg.AppPageListGroupID,pg.AppPageListPageOrder,
		pg.AppPageListPageProperties","tbaapppagelist pg 
inner join tbaapppagerivilege  pri on pri.AppPagePrivilegePageID=pg.AppPageListPageID
inner join  tbaapplevellist lvl on lvl.AppLevelListLevelID=pri.AppPagePrivilegeLevelID ")->where($sortir)->get(); 

        $lst=array();
        while($data=current($list)){
        	$rec=new stdClass;
        	$controller_name = str_replace(" ", "_", ucwords(str_replace("_", " ", $data->AppPageListPageController)))."_Controller";
			$path            		= $dcistem->getOption("system/dir/controller").strtolower($controller_name).".php";
			$rec->PageName			=$data->AppPageListPageName;
			$rec->PageTitle			=$data->AppPageListPageTitle;
		//	echo $data->AppPagePrivilegePrivileges;
			$pri=$this->privileges($path,$data->AppPagePrivilegePrivileges);
			$rec->PageFasilitas		=$pri;
			$lst[]=$rec;
			next($list);
        }
      // echo "<pre style='text-align:left;'>";print_r($lst); echo "</pre>";exit;
        return  $lst;       
    }
    public function privileges($path_file,$pri="") {
    	$hasil="";
    	if(file_exists($path_file)) {
    		
	    	$arr = file($path_file);
	   
		    foreach ($arr as $line)
		    {
		        if (preg_match('/function ([_A-Za-z0-9]+)/', $line, $regs))
		            $arr_methods[] = $regs[1];
		    }
			$privileges=trim($pri)==""?array():unserialize($pri);
		//	echo "<pre  style='text-align:left;'>"; print_r($arr_methods);echo "</pre>";exit;
		//echo "<pre  style='text-align:left;'>$pri================================<br>";print_r($privileges); echo "</pre>";
			$result = array();
			$arr=array();
			$i=0;
			while($list = current($arr_methods)) {
				if ($list <> '__construct') {
					$result[] = $list;
					$arr[$i]['method']=$list;
					$cek=false;
					if(in_array($list,$privileges)){
						$cek=true;
					}
					$arr[$i]['check']=$cek;
				}
				$i++;	
				next($arr_methods);
			}
			$hasil= serialize($result);
			//echo "<pre  style='text-align:left;'>$hasil====================$path_file<br>";print_r($arr); echo "</pre>";
			
		}
        return  $arr;       
    }
    
   public function adduserdanlevel($webid,$username,$password,$nama_lengkap,$email="",$no_induk,$level_id,$ref_id="") {
		global $dcistem;
		 $prefdb = $dcistem->getOption("database/admindb");
		 $db = $dcistem->getOption("framework/db");
		 	$master=new Master_Ref_Model();
		 //$manage= new User_Manage_Model($webid,"");

			$validasi_form=$this->validasi($webid,$username,$password,$nama_lengkap,$level_id);
		
			if(count($validasi_form['arrayerror'])==0) {
				$Password=$password;//md5(crypt($_POST["frmPassword"], $_POST["frmPassword"]));
				$Nama		=$master->scurevaluetable($nama_lengkap);
				$email		=$master->scurevaluetable($email);
				$NoInduk	=$master->scurevaluetable($no_induk);
				$Username_form	=$master->scurevaluetable($username);
				$Password_nilai	=md5(crypt($Password, $Password));
      			$Password_nilai	=$master->scurevaluetable($Password_nilai,"string");
			
				$search="AppUserListUsername='".$username."'";
        		$cek=$db->select("AppUserListUsername","tbaappuserlist")->where($search)->get();
        		if (count($cek)==0)
				{
					$coloms="AppUserListUsername,AppUserListPassword,AppUserListName,AppUserListEmail,AppUserListNoInduk";
					$values="$Username_form,$Password_nilai,$Nama,$email,$NoInduk";
					$hsl=$this->adduser($coloms,$values);
					if ($hsl['sukses']==true){
						$sql="";
						$sql_del="DELETE FROM tbaAppUserLevel  WHERE AppUserLevelUsername=$Username_form;";
						$search="AppUserLevelUsername='".$username."' and AppUserLevelLevelID='".$level_id."' ";
        				$cek_level=$db->select("AppUserLevelLevelID","tbaappuserlevel")->where($search)->get();
					
						$LevelID	=$master->scurevaluetable($level_id);
						$RefID		=$master->scurevaluetable($ref_id);
						$sql="INSERT INTO ".$this->SkemaDatabase."tbaappuserlevel 
						(AppUserLevelUsername,AppUserLevelLevelID,AppUserLevelRefID)
						VALUES($Username_form,$LevelID,$RefID);";
						try{
							$db->query($sql);
							$hasil['sukses']=$hsl['sukses'];
		        			$hasil['pesan']=$hsl['pesan'];
						}catch (exception $e){
							$hasil['sukses']=false;
		        			$hasil['pesan']="Gagal menambah user, silahkan ulangi!";
						}
					}else{
						$hasil['sukses']=false;
				        $hasil['pesan']=$msg_error;
					}
				}else{
					$hasil['sukses']=false;
			        $hasil['pesan']="Username sudah digunakan, silahkan gunakan username lain!";
				}
			}else{
				$hasil['sukses']=false;
		        $hasil['pesan']=$validasi_form['arrayerror'];
			}
    	return $hasil;  
	}

	public function validasi($webid,$username,$password,$nama_lengkap,$level_id) 
    {
        $pesan=array();
        if(trim($webid)==""){
                $pesan["WebID"]="WebID tidak boleh kosong!";   
        }
        if(trim($username)==""){
                $pesan["Username"]="Username tidak boleh kosong!"; 
        }
        if (trim($password)==""){
			$pesan["Password"]="Password tidak boleh kosong!";  
		}
		if(trim($nama_lengkap)==""){
                $pesan["Nama"]="Nama tidak boleh kosong!";   
        }
        if (trim($level_id)==""){
			$pesan["LevelID"]="Level User tidak boleh kosong!";  
		}
         return $pesan;
         
    }	
}

?>