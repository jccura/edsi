<?php
final class DB {
	private $driver;
	
	public function __construct($driver, $hostname, $username, $password, $database) {
		echo "dito sa DB.. >>>> /home/iso7n2zl2kqg/public_html/shop/".$driver . '.php <br>';
		if (file_exists("/home/iso7n2zl2kqg/public_html/shop/".$driver . '.php')) {
			require_once("/home/iso7n2zl2kqg/public_html/shop/".$driver . '.php');
		} else if (file_exists($driver . '.php')) {
			require_once($driver . '.php');
		} else {
			exit('Error: Could not load database file ' . $driver . '!');
		}
				
		$this->driver = new $driver($hostname, $username, $password, $database);
	}
		
  	public function query($sql) {
		return $this->driver->query($sql);
  	}
	
	public function escape($value) {
		return $this->driver->escape($value);
	}
	
  	public function countAffected() {
		return $this->driver->countAffected();
  	}

  	public function getLastId() {
		return $this->driver->getLastId();
  	}	
}
?>