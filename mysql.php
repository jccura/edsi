<?php
final class MySQL {
	private $connection;
	
	public function __construct($hostname, $username, $password, $database) {
		if (!$this->connection = mysqli_connect($hostname, $username, $password, $database)) {			
			exit('Error: Could not make a database connection using ' . $username . '@' . $hostname);
    	}

		$this->connection->query("SET NAMES 'utf8'");
		$this->connection->query("SET CHARACTER SET utf8");
		$this->connection->query("SET CHARACTER_SET_CONNECTION=utf8");
		$this->connection->query("SET SQL_MODE = ''");
  	}
		
  	public function query($sql) {
		//echo $sql."<br>";
		$resource = $this->connection->query($sql);
		if($resource === false) {		
			trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $this->connection->error, E_USER_ERROR);
			exit();
		} else {
			
			if(strpos($sql, 'insert') !== false 
				or strpos($sql, 'update') !== false 
				or strpos($sql, 'delete') !== false 
				or strpos($sql, 'INSERT') !== false 
				or strpos($sql, 'UPDATE') !== false 
				or strpos($sql, 'DELETE') !== false 
				or strpos($sql, 'call') !== false
				or strpos($sql, 'CALL') !== false ) {
					return false;
				} else { 
				
				$i = 0;
		
				$data = array();				
				while ($result = $resource->fetch_object()) {
					$data[$i] = json_decode(json_encode($result), true);
					$i++;
				}						
				$resource->free();			
				$query = new stdClass();
				$query->row = isset($data[0]) ? $data[0] : array();
				$query->rows = $data;
				$query->num_rows = $i;				
				unset($data);
				return $query;
			}
		} 		
  	}
	
	public function escape($value) {
		return $this->connection->real_escape_string($value);

	}
	
  	public function countAffected() {
    	return $this->connection->affected_rows;
  	}

  	public function getLastId() {
    	return $this->connection->insert_id;
  	}	
	
	public function __destruct() {
		$this->connection->close();
	}
}
?>