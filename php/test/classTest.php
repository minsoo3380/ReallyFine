<?php
	class dbcon{
		public $host, $user, $pw, $dbName;
		public $mysqli;
		
		function __construct(){
			$this->host = "localhost";
			$this->user = "root";
			$this->pw = "123123";
			$this->dbName = "ReallyFine";
		}

		function connect(){
			$mysqli = new mysqli($host, $user, $pw, $dbName);
			
			if($mysqli){
				echo "<script>console.log('db connect successed')</script>";
			}else{
				echo "<script>console.log('db connect failed')</script>";
			}
		}
	}
?>			
