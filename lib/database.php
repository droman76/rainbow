<?php


class Database {
	private $db_conx; //database connection;
	private $meminstance; // database memory cache
	private $error;

	function __construct() {
		global $CONFIG;

		$this->db_conx = mysqli_connect($CONFIG->dbhost, $CONFIG->dbuser, $CONFIG->dbpass, $CONFIG->dbname);
		// Evaluate the connection
		if (mysqli_connect_errno()) {
    		elog(mysqli_connect_error());
    		exit();
		
    	}	
		else {
			//ilog("Successful database connection, happy coding!!!");
		}
		// start the memory cache class
		/*
		$this->meminstance = new Memcache();
		$this->meminstance->pconnect('localhost', 11211);
		*/

	}

	public function query($query) {
		/*
		$querykey = "KEY" . md5($query); // create a key for memcache
		
		// first attempt to retrieve from the memory cache
		$result = $this->meminstance->get($querykey);

		if (!$result) {
       		$result = mysqli_query($this->db_conx,$query);
       		if (!$result){
       			elog('[DB] Query Failed! $query');

       		}

       		$this->meminstance->set($querykey, $result, 0, 600);
			ilog("DB: got result from MYSQL for query $query");
			return $result;
		}

		ilog("DB: Got Result from MEMCACHE for query $query");
		*/
		$result = mysqli_query($this->db_conx,$query);
       	
       	if (!$result){
       		$error = mysqli_error ($this->db_conx);
       		$this->error = $error;
       		elog("[DB] Query Failed! $error \n QUERY: $query");
       		return false;	
       	}
		return $result;
	}

	public function get_insert_id(){
		return mysqli_insert_id($this->db_conx);

	}
	public function escape($str) {
		return mysqli_real_escape_string($this->db_conx, $str);

	}
	public function errors(){
		return $this->error;
	}

}

// global database
$db = NULL;

function get_query($query){
	global $db;

	if ($db == null) {
		//ilog('Creating new instance of DB!');
		$db = new Database();
	}
	return $db->query($query);
}

function get_insert_id() {
	global $db;
	if ($db == null) {
		//ilog('Creating new instance of DB!');
		$db = new Database();
	}
	return $db->get_insert_id();

	


}
function get_errors() {
	global $db;
	if ($db != null) return $db->errors();
}

function db_escape($str){
	global $db;
	if ($db == null) {
		//ilog('Creating new instance of DB!');
		$db = new Database();
	}
	return $db->escape($str);


}

function db_get_rowcount($result) {
	return mysqli_num_rows($result);
}
function get_rows($result){
	return mysqli_num_rows($result);

}




?>