<?php
/**
 * @package DCISTEM PHP Framework
 * @subpackage View Library
 *
 * @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class View {
	public $_file;

	public function __construct($file = null) {
		global $dcistem;
		if($file <> null) {
			$this->setFile($file);
		}
	}

	public static function getInstance() {
		$class = __CLASS__;
		return new $class();
	}

	public function setFile($file) {
		global $dcistem;
		$file  = trim($file, "/");
		$found = false;
		$paths = array(
			$dcistem->getOption("system/dir/theme").$_SESSION["framework"]["theme"]."/".$file,
			$dcistem->getOption("system/dir/view").$file
		);
		while($path = current($paths)) {
			if(file_exists($path.".php")) {
				$found = true;
				break;
			}
			next($paths);
		}
		if($found) {
			$this->_file = $path.".php";
		} else {
			Core::fatalError("Can't find view file (".$paths[0].")");
		}
	}

	public function render() {
		global $dcistem;
		Core::benchmarkSave();
		$this->_global = $dcistem->getOption("framework/value");
		$this->_vars   = get_object_vars($this);
		while($this->_var = each($this->_vars)) {
			if($this->_var[0][0] <> "_") {
				$this->_global[$this->_var[0]] = $this->_var[1];
			}
		}
		extract($this->_global, EXTR_OVERWRITE);
		include $this->_file;
	}

	public function appendVariable($var) {
		if(is_object($var)) {
			$object = get_object_vars($var);
			while($data = each($object)) {
				$this->$data[0] = $data[1];
			}
		}
		if(is_array($var)) {
			while($data = each($var)) {
				$this->$data[0] = $data[1];
			}
		}
	}

	public function __toString() {
		$this->render();
		return "";
	}

}
?>