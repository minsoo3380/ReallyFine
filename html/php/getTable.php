<?php
	$param_sec = $_REQUEST['section'];
	$param_dist = $_REQUEST['district'];
	$param_date = $_REQUEST['date'];
	$table_max = null;	

	$host = "localhost";
	$db = "ReallyFine";
	$user = "root";
	$pass = "123123";

	if($param_sec == "1200"){
		$table_max = null;
	}else{
		$table_max = 24;
	}
	
	try{
		$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->exec("set names utf8");
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
	switch($param_sec){
		case "1000":
			/*
			$sql = "select m.mss_location, m.mss_name, p.data_value, p.created_time from measure_station as m join public_data_pm10 as p on m.mss_code=p.mss_code where p.created_date = '".$param_date."' order by m.mss_code, cast(created_time as unsigned) asc;";
			//echo $sql;

			$st = $pdo->query($sql);
			table_writer($st);			
			*/
			break;
		case "1100":
			$sql = "select m.mss_location, m.mss_name, p.data_value, p.created_time from measure_station as m join public_data_pm25 as p on m.mss_code=p.mss_code where p.created_date = '".$param_date."' order by m.mss_code, cast(created_time as unsigned) asc;";
		
			$st = $pdo->query($sql);
			table_writer($st);

			break;
	}
?>
