<?php
/**
 * @package DB Library
 * @subpackage MySQL DB Driver
 * 
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/
 
defined("PANDORA") OR die("No direct access allowed.");

class DB_MySQL_Driver {
	public $config;
	public $connection;
	public $select;
	private $debug    = false;
	private $temp_dir = "";
	private $types    = array("0", "1");
	
	public function connect() {
		global $dcistem;
		$this->debug    = $dcistem->getOption("system/web/debug");
		$this->temp_dir = $dcistem->getOption("system/dir/temp");
		
		reset($this->types);
		switch($this->config["mode"]) {
		case "double" :
			while($type = current($this->types)) {
				if($this->config["persistent"]) {
					$this->connection[$type] = @mysql_pconnect($this->config["config"][$type]["host"], $this->config["config"][$type]["user"], $this->config["config"][$type]["pass"]);
				} else {
					$this->connection[$type] = @mysql_connect($this->config["config"][$type]["host"], $this->config["config"][$type]["user"], $this->config["config"][$type]["pass"]);
				}
				if(!$this->connection[$type]) {
					Core::fatalError("Can't connect to MySQL server on '".$this->config["config"][$type]["host"]."'");
				}
				if(!@mysql_select_db($this->config["config"][$type]["name"])) {
					Core::fatalError("Unknown database '".$this->config["config"][$type]["name"]."'");
				}
				next($this->types);
			}
			break;
		case "single" :
		default :
			if($this->config["persistent"]) {
				$this->connection = @mysql_pconnect($this->config["config"]["0"]["host"], $this->config["config"]["0"]["user"], $this->config["config"]["0"]["pass"]);
			} else {
				$this->connection = @mysql_connect($this->config["config"]["0"]["host"], $this->config["config"]["0"]["user"], $this->config["config"]["0"]["pass"]);
			}
			if(!$this->connection) {
				Core::fatalError("Can't connect to MySQL server on '".$this->config["config"]["0"]["host"]."'");
			}
			if(!@mysql_select_db($this->config["config"]["0"]["name"])) {
				Core::fatalError("Unknown database '".$this->config["config"]["0"]["name"]."'");
			}
			break;
		
		}
	
		//$this->connection->set_charset('utf8');	
	}
	

	public function query($sql) {
	
		switch($this->config["mode"]) {
		case "single" :
			$connection = $this->connection;
			break;
		case "double" :
			$prefix = trim(strtoupper(array_shift(explode(" ", $sql))));
			switch($prefix) {
			case "SELECT" :
				$connection = $this->connection["0"];
				break;
			case "INSERT" :
			case "UPDATE" :
			case "DELETE" :
				$connection = $this->connection["0"];
				break;
			}
			break;
		}
	
		$result = @mysql_query($sql,$connection);
		$rec =new stdClass;
		if($result===false){
			global $dcistem;
			$rec->error=true;
			$rec->getMessage=$result;
			$rec->last_executed_query=$sql;
			$rec->query_last_message=mysql_error();
			$result=$rec;
		}
		
		return $result; 
	}
	public function fetchObject($query) {
		if(isset($query->error) and $query->error===true){
			return	$query;
				
		}else{
			return mysql_fetch_object($query); 
		}
	}
	
	public function fetchArray($query) {
		if(isset($query->error) and $query->error===true){
			return	$query;
				
		}else{
			return mysql_fetch_assoc($query);
		}
		
	}
	
	public function fetchRow($query) {
		return mysql_fetch_row($query);

	}

	public function numRow($query) {
		return mysql_num_rows($query);
	}
	public function affectedRows($query) {
		return mysql_num_rows($query);
	}
	public function select($fields, $table, $type = "object") {
		$this->select = array("result_type" => $type);
		if(is_array($fields)) {
			if(strlen($fields[0])) {
				$this->select["fields"] = implode(", ", $fields);
			} else {
				$field = array();
				while($data = each($fields)) {
					$field[] = $data[0]." as ".$data[1];
				}
				$this->select["fields"] = implode(", ", $field);
			}
		} else {
			$this->select["fields"] = $fields;
		}
		if(is_array($table)) {
			$this->select["table"] = implode(", ", $table);
		} else {
			$this->select["table"] = $table;
		}
	}
	
	public function join($tables, $type = "INNER") {
		$types = array("INNER", "LEFT", "RIGHT");
		$type  = (in_array($type, $types) ? $type : "INNER");
		$this->select["join"] = $type;
		if(is_array($tables)) {
			$this->select["join_tables"] = implode(", ", $tables);
		} else {
			$this->select["join_tables"] = $tables;
		}
	}
	
	public function where($conditions) {
		if(is_array($conditions)) {
			if(strlen($conditions[0])) {
				$this->select["conditions"] = implode(" AND ", $conditions);
			} else {
				$condition = array();
				while($data = each($conditions)) {
					$condition[] = $data[0]." = '".$data[1]."'";
				}
				$this->select["conditions"] = implode(" AND ", $condition);
			}
		} else {
			$this->select["conditions"] = $conditions;
		}
	}
	
	public function orderBy($orders) {
		if(is_array($orders)) {
			if(strlen($orders[0])) {
				$this->select["order"] = implode(" AND ", $orders);
			} else {
				$order = array();
				while($data = each($orders)) {
					$order[] = $data[0]." ".$data[1];
				}
				$this->select["order"] = implode(", ", $order);
			}
		} else {
			$this->select["order"] = $orders;
		}
	}
		
	public function get($start = null, $rows = 1) {
		global $dcistem;
		$sql = "SELECT ".$this->select["fields"]." FROM ".$this->select["table"];
		if(strlen($this->select["join"])) {
			$sql .= " ".$this->select["join"]." JOIN ".$this->select["join_tables"];
			if(strlen($this->select["conditions"])) {
				$sql .= " ON ".$this->select["conditions"]."";
			}
		} else {
			if(strlen($this->select["conditions"])) {
				$sql .= " WHERE ".$this->select["conditions"];
			}
		}
		if(strlen($this->select["order"])) {
			$sql .= " ORDER BY ".$this->select["order"];
		}
		$db     = $dcistem->getOption("framework/db");
		
		$end    = ($start === null ? null :  $rows);
		if($start !== null && $end !== null) {
			$sql .= " LIMIT ".$start.", ".$end;
		}
		$query  = $this->query($sql);
		
		if(isset($query->error) and $query->error===true){
			
			echo Core::debug($query->last_executed_query);
			Core::fatalError($query->query_last_message);
		}else{
			$result = array();
			switch($this->select["result_type"]) {
			case "object" :
				while($data = $this->fetchObject($query)) {
					$result[] = $data;
				}
				break;
			case "array" :
				while($data = $this->fetchArray($query)) {
					$result[] = $data;
				}
				break;
			case "row" :
				while($data = $this->fetchRow($query)) {
					$result[] = $data;
				}
				break;
			}
			if(count($result) == 1 && !is_null($start) && $rows < 2) {
				$result = $result[0];
			}
		
			return $result;
		}
		
	}
public function lim($start = null, $rows = 1) {
		global $dcistem;
		$sql = "SELECT ".$this->select["fields"]." FROM ".$this->select["table"];
		if(strlen($this->select["join"])) {
			$sql .= " ".$this->select["join"]." JOIN ".$this->select["join_tables"];
			if(strlen($this->select["conditions"])) {
				$sql .= " ON ".$this->select["conditions"]."";
			}
		} else {
			if(strlen($this->select["conditions"])) {
				$sql .= " WHERE ".$this->select["conditions"];
			}
		}
		if(strlen($this->select["order"])) {
			$sql .= " ORDER BY ".$this->select["order"];
		}
		$db     = $dcistem->getOption("framework/db");
		
		$end    = ($start === null ? null :  $rows);
		if($start !== null && $end !== null) {
			$sql .= " LIMIT ".$start.", ".$end;
		}
		$query  = $this->query($sql);
		
		if(isset($query->error) and $query->error===true){
			
			echo Core::debug($query->last_executed_query);
			Core::fatalError($query->query_last_message);
		}else{
			return  $query;
		
		}
		
	}
	public function insert($table, $values) {
		global $dcistem;
		$sql = "INSERT INTO ".$table;
		if(is_array($values)) {
			$name  = array_keys($values);
			$value = array_values($values);
			$sql  .= " (".implode(", ", $name).") VALUES ('".implode("', '", $value)."')";			
		} else {
			$sql .= " ".$values;
		}
		$db = $dcistem->getOption("framework/db");
		$db->query($sql);
	}
	
	public function update($table, $values, $conditions) {
		global $dcistem;
		$sql = "UPDATE ".$table." SET ";
		if(is_array($values)) {
			$value = array();
			while($data = each($values)) {
				$value[] = $data[0]." = '".$data[1]."'";
			}
			$sql .= implode(", ", $value);	
		} else {
			$sql .= " ".$values;
		}
		$sql .= " WHERE ";
		if(is_array($conditions)) {
			if(strlen($conditions[0])) {
				$sql .= implode(" AND ", $conditions);
			} else {
				$condition = array();
				while($data = each($conditions)) {
					$condition[] = $data[0]." = '".$data[1]."'";
				}
				$sql .= implode(" AND ", $condition);
			}
		} else {
			$sql .= $conditions;
		}
		$db = $dcistem->getOption("framework/db");
		$db->query($sql);
	}
	
	public function delete($table, $conditions) {
		global $dcistem;
		$sql = "DELETE FROM ".$table." WHERE ";
		if(is_array($conditions)) {
			if(strlen($conditions[0])) {
				$sql .= implode(" AND ", $conditions);
			} else {
				$condition = array();
				while($data = each($conditions)) {
					$condition[] = $data[0]." = '".$data[1]."'";
				}
				$sql .= implode(" AND ", $condition);
			}
		} else {
			$sql .= $conditions;
		}
		$db = $dcistem->getOption("framework/db");
		return $db->query($sql);
	}
	
	public function close() {
		if(!$this->config["persistent"]) {
			switch($this->config["mode"]) {
			case "single" :
				@mysql_close($this->connection);
				break;
			case "double" :
				reset($this->types);
				while($type = current($this->types)) {
					@mysql_close($this->connection[$type]);
					next($this->types);
				}
				break;
			}
		}
	}
		
}
?>