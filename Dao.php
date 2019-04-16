<?php
class Dao {
	private $host ="us-cdbr-iron-east-03.cleardb.net";
	private $db = "heroku_37bb6c10c4cefc0";
	private $user = "b8df8be23a0e15";
	//private $pass = [hidden for security reasons];
	public function getConnection() {
		return new PDO("mysql:host={$this->host};dbname={$this->db}",$this->user,$this->pass);
	}
}//mysql://b8df8be23a0e15:d9cf9f72@us-cdbr-iron-east-03.cleardb.net/heroku_37bb6c10c4cefc0?reconnect=true
?>
