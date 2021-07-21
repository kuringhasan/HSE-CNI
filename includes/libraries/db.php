<?php
/**
 * @package  DCISTEM PHP Framework
 * @subpackage DB Library
 * 
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class DB {
	public $_driver  = null;
	public $_queries = 0;
	
	public function __construct() {
		global $dcistem;
		$dcistem->loadConfig("database");
        
		$config                = $dcistem->getOption("database");
		$driver                = __CLASS__."_".$config["driver"]."_Driver";
		$this->_driver         = new $driver();
		$this->_driver->config = $config;
	}
	
	public function connect() {
		$this->_driver->connect();
	}
	
	public function query($sql) {
		global $dcistem;
		$this->_queries++;
	
		$dcistem->setOption("framework/value/last_executed_query", $sql);
		$dcistem->setOption("framework/value/total_query", $this->_queries);
		return $this->_driver->query($sql);
	}
	
	public function fetchObject($query) {
		return $this->_driver->fetchObject($query);
	}

	public function fetchArray($query) {
		return $this->_driver->fetchArray($query);
	}
	
	public function fetchRow($query) {
		return $this->_driver->fetchRow($query);
	}
	
	public function numRow($query) {
		return $this->_driver->numRow($query);
	}
	
	public function select($fields, $table, $type = "object") {
		//echo "<pre>";print_r($this->_driver);echo "</pre>";
		$this->_driver->select($fields, $table, $type);
		return $this;
	}
	
	public function join($tables, $type = "INNER") {
		$this->_driver->join($tables, $type);
		return $this;
	}
	
	public function where($conditions) {
		$this->_driver->where($conditions);
		return $this;
	}
	
	public function orderBy($orders) {
		$this->_driver->orderBy($orders);
		return $this;
	}
	
	public function get($start = null, $rows = 1) {		
		return $this->_driver->get($start, $rows);
	}
	public function lim($start = null, $rows = 1) {		
		return $this->_driver->lim($start, $rows);
	}
	public function insert($table, $values) {
		$this->_driver->insert($table, $values);
		return $this;
	}
	
	public function update($table, $values, $conditions) {
		$this->_driver->update($table, $values, $conditions);
		return $this;
	}
	
	public function delete($table, $conditions) {
		$this->_driver->delete($table, $conditions);
		return $this;
	}
	
	public function close() {
		return $this->_driver->close();
	}
}
?>