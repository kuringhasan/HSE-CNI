<?php
/**
 * @package Core
 * @subpackage Page Model
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class Core_Page_Model extends Model {

	public function __construct($PageID = 0, $WebID = "") {
		global $dcistem;
		$PageID = (int) $PageID;
		$WebID  = ($WebID == "" ? $dcistem->getOption("system/web/id") : $WebID);
		if($PageID > 0) {
			$this->getDataFrom_PageID($PageID, $WebID);
		}
	}

	public function translate($text) {
		$list = array(
			"AppPageListPageID"         => "PageID",
			"AppPageListParentID"       => "ParentID",
			"AppPageListWebID"          => "WebID",
			"AppPageListPageName"       => "PageName",
			"AppPageListPageTitle"      => "PageTitle",
			"AppPageListPageController" => "PageController",
			"AppPageListPageOrder"      => "PageOrder",
			"AppPageListPageProperties" => "PageProperties",
            "AppPageListIcon" => "PageIcon"
		);
		$key   = array_keys($list);
		$value = array_values($list);
		$find  = array_search($text, $key);
		if($find !== false) {
			return $value[$find];
		}
		$find  = array_search($text, $value);
		if($find !== false) {
			return $key[$find];
		}
		return false;
	}

	public function getData($condition) {
		global $dcistem;
		$data = $this->getList($condition, "", 0);
		if(is_object($data)) {
			$this->appendVariable($data);
		}
	}

	public function getList($condition = "", $orders = "", $start = null, $limit = 0) {
		global $dcistem;
		$db    = $dcistem->getOption("framework/db");
		$query = $db->select($this->translate_field(array(
					"PageID",
					"ParentID",
					"WebID",
					"PageName",
					"PageTitle",
					"PageController",
					"PageOrder",
					"PageProperties"
				)), "tbaapppagelist");
		if(!empty($condition)) {
			$query = $query->where($condition);
		}
		if(!empty($orders)) {
			$query = $query->orderBy($orders);
		}
		if($limit > 0) {
			$result = text::trim($query->get($start, $limit));
		} else {
			if(!is_null($start)) {
			$result = text::trim($query->get($start));
			} else {
				$result = text::trim($query->get());
			}
		}
		if(is_object($result)) {
			$result->PageProperties = Core::checkSerialize($result->PageProperties);
		} else if(is_array($result)) {
			while($data = each($result)) {
				$result[$data[0]]->PageProperties = Core::checkSerialize($data[1]->PageProperties);
			}
			reset($result);
		}
		return $result;
	}

	public function insert($fields, $values) {
		global $dcistem;
		$db    = $dcistem->getOption("framework/db");
		$saves = array();
		while($field = current($fields)) {
			$saves[$field] = $values[$field];
			next($fields);
		}
		echo Core::debug($saves);
		exit;

	}

	/*

	public function save($condition, $fields, $values = null) {
		global $dcistem;
		$db     = $dcistem->getOption("framework/db");
		$source = $this;
		if($values <> null) {
			if(is_array($values)) {
				$source = $this;
				$source->appendVariable($values);
			} else {
				$source = $values;
			}
		}
		$saves = array();
		while($field = current($fields)) {
			$saves[$field] = $source->$field;
			next($fields);
		}
		$condition = $this->translate_condition($condition);
		$found     = count($this->getList($condition));
		if(!$found) {
			$this->homepage();
			$saves["ParentID"] = $this->PageID;
			$saves["WebID"]    = $dcistem->getOption("system/web/id");
			$last              = $this->getList($this->translate_condition(array(
				"ParentID" => $saves["ParentID"],
				"WebID"    => $saves["WebID"]
			)), "PageOrder", 0, 1);
			if(is_object($last)) {
				$saves["PageOrder"] = $last->PageOrder + 1;
			} else {
				$saves["PageOrder"] = 1;
			}

			$saves = $this->translate_value($saves);
			$db->insert("DB_USERMANAGdev.dbo.tbaAppPageList", $saves);
		} else {
			$saves = $this->translate_value($saves);
			$db->update("DB_USERMANAGdev.dbo.tbaAppPageList", $saves, $condition);
		}
	}

	*/

	public function getDataFrom_PageID($PageID) {
		global $dcistem;
		$condition = array(
			"PageID" => $PageID,
			"WebID"  => $dcistem->getOption("system/web/id")
		);
       
		$this->getData($this->translate_condition($condition));
	}

	public function getDataFrom_PageName($PageName) {
		global $dcistem;
		$condition = array(
			"PageName" => $PageName,
			"WebID"    => $dcistem->getOption("system/web/id")
		);
		$this->getData($this->translate_condition($condition));
	}

	public function getDataFrom_PageName_ParentID($PageName, $ParentID) {
		global $dcistem;
		$condition = "IFNULL(AppPageListPageName,'')='".$PageName."' and AppPageListParentID=$ParentID and 
		AppPageListWebID='".$dcistem->getOption("system/web/id")."'";
		$this->getData($this->translate_condition($condition));
	}

	public function homepage($WebID = "") {
		global $dcistem;
		$WebID = (trim($WebID) <> "" ? $WebID : $dcistem->getOption("system/web/id"));
		$this->getData($this->translate_condition(array(
			"ParentID" => "0",
			"WebID"    => $WebID
		)));
		if($this->PageID < 1) {
			Core::fatalError("Default Home Page not found!");
		}
		$this->getDataFrom_PageID($this->PageID, $WebID);
	}

	public function privilege($PageID, $LevelID) {
		global $dcistem;
		$db     = $dcistem->getOption("framework/db");
 		$result = $db->select("AppPagePrivilegePrivileges as Privileges", "tbaapppageprivilege", "array")->where(array(
			"AppPagePrivilegePageID"  => $PageID,
			"AppPagePrivilegeLevelID" => $LevelID
		))->get(0);
		if(empty($result["Privileges"])) {
			$result = $db->select("AppPagePrivilegePrivileges as Privileges", "tbaapppageprivilege", "array")->where(array(
				"AppPagePrivilegePageID"  => $PageID,
				"AppPagePrivilegeLevelID" => ""
			))->get(0);
		}

		if(!empty($result["Privileges"])) {
			$result = Core::checkSerialize($result["Privileges"]);
			return (array) $result;
		}
		return array();
	}

	public function buildTree($ParentID, $WebID = "") {
		global $dcistem;
		$db     = $dcistem->getOption("framework/db");
		$WebID  = (trim($WebID) <> "" ? $WebID : $dcistem->getOption("system/web/id"));
		$list   = $this->getList($this->translate_condition(array(
			"ParentID" => $ParentID,
			"WebID"    => $WebID
		)), "PageOrder");
		$result = array();
		while($data = current($list)) {
			if(!$data->PageProperties["hide"]) {
				$tmp                 = new stdClass;
				$tmp->PageID         = $data->PageID;
				$tmp->PageName       = $data->PageName;
				$tmp->PageTitle      = $data->PageTitle;
				$tmp->ParentID       = $data->ParentID;
				$tmp->PageProperties = $data->PageProperties;
				$tmp->PageChilds     = $this->buildTree($data->PageID, $WebID);
				$result[]            = $tmp;
			}
			next($list);
		}
		return $result;
	}
	public function log_visitor($kategori,$judul_halaman,$url_current,$deskripsi=null){
		global $dcistem;
		$db     = $dcistem->getOption("framework/db");
		$device_detect=new Mobile_Detect_Model();
		$deviceType=($device_detect->isMobile()?($device_detect->isTablet()?"tablet":"phone"):"computer");
       	$devise=$deviceType;
       	/*foreach($device_detect->getUserAgents() as $userAgent){
       		$device_detect->setUserAgent($userAgent);
       		//$isMobile = $device_detect->isMobile();
			 $isTablet = $device_detect->isTablet();
			
       		
       	}	echo "<pre>";print_r($device_detect);echo "</pre>";
		echo "<pre>";print_r($device_detect->getScriptVersion());echo "</pre>";exit;
       	switch($deviceType){
       		case "computer":
       		
       		break;
       		case "tablet":
       			$devise = $devise." [".$device_detect->getTabletDevices()."]";
       		break;
       		case "phone":
       			$devise = $devise." [".$device_detect->getPhoneDevices()."]";
       		break;
       	}
        $browser=$device_detect->getBrowsers();
        $os = $device_detect->getOperatingSystems();*/
		$master=new Master_Ref_Model();
		$user=$_SESSION["framework"]['current_user']->Username;
        $username= trim($user)==""?"guest":$user;
		$ip = core::get_ip();
		$deskripsi=mysql_real_escape_string($deskripsi);
		//echo $deskripsi;
		$deskripsi=trim($deskripsi)==""?null:$deskripsi;
        $deskripsi=$master->scurevaluetable($deskripsi);
			$sql="INSERT INTO tbatranslog (tLogCat,tLogPath,tLogdate,tLogUid,tLogiP,tLogName,tLogDesc,tLogDevice)
			     VALUES('".$kategori."','".$url_current."','".date("Y/m/d H:i:s")."','".$username."','".$ip[0]."',
				 '".$judul_halaman."',$deskripsi,'".$devise."');";
            $db->query($sql);
            /*
			"tLogBrowser"=>$browser,
			"tLogOpertingSystem"=>$os*/


	}
	public function listdata($id_elemen_list,$url_redirect) {
		$js= "<script type='text/javascript'>
    	var myElem = document.getElementById('".$id_elemen_list."');
		if (myElem == undefined || myElem == null || myElem == 'undefined') 
		{ 
 			window.location=\"".$url_redirect."\";
		}
	     </script>";
		echo $js;
	}
    
    public function json($query="",$array_value=array()) {
		global $dcistem;
    	$db = $dcistem->getOption("framework/db");
        
        
        $filter="(AppPageListPageID like '%".$query."%' or AppPageListPageTitle like '%".$query."%')";
        if(trim($array_value['web_id'])<>""){
            $filter=$filter." and AppPageListWebID='".$array_value['web_id']."'";
        }        
  
	    $list_qry= $db->select("AppPageListPageID,AppPageListParentID,AppPageListWebID,AppPageListPageName,
                    AppPageListPageTitle,AppPageListPageController,AppPageListPageOrder,AppPageListIcon","tbaapppagelist")->where($filter)->lim();
		$i=0;
		while($data=$db->fetchObject($list_qry)){
		   // $nama2=str_ireplace($nama,"<strong>".$nama."</strong>",$data->lokNamaUPB);
           $nama="[".$data->AppPageListPageID."]";
           if(trim($data->AppPageListPageTitle)<>"" ){
              $nama=$nama." ".$data->AppPageListPageTitle.")";
           }
          
		    $List[$i]['ID']=$data->AppPageListPageID;
		    $List[$i]['Nama']=$data->AppPageListPageTitle;
            $List[$i]['Name']=$data->AppPageListPageTitle;
            
		    $List[$i]['Lengkap']=$nama;
		    $i++;
		}
        return $List;
    }

}
?>