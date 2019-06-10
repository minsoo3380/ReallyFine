<?php
	$param_sec = $_REQUEST['section'];
	$param_date = $_REQUEST['date'];
	$param_code = $_REQUEST['station'];

	$host = "localhost";
	$db = "ReallyFine";
	$user = "root";
	$pass = "123123";
	
	try{
		$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->exec("set names utf8");
	}catch(Exception $e){
		echo $e->getMessage();
	}

	switch($param_sec){
	case "1001":
		$sql_pm25 = "select * from public_data_pm25 where created_date = '".$param_date."' and mss_code = '".$param_code."' order by 'mss_code' asc";
		$sql_pm10 = "select * from public_data_pm10 where created_date = '".$param_date."' and mss_code = '".$param_code."' order by 'mss_code' asc";
		$sql_aws = "select * from AWSData where create_date = '".$param_date."' and mss_code = '".$param_code."' order by 'mss_code' asc";
		$st25 = $pdo->query($sql_pm25);
		$st10 = $pdo->query($sql_pm10);
		$rows = array();
		$table = array();

		$table['cols'] = array(
			array('label' => 'Date Time', 'type' => 'string'),
			array('label' => 'pm25', 'type' => 'number'),
			array('label' => 'pm10', 'type' => 'number'),
			array('label' => 'AWS', 'type' => 'number')
		);

		while($row25 = $st25->fetch()){
			$row10 = $st10->fetch();
			
			$sub_array = array();
			$datetime = $row25['created_time'];

			if(strlen($datetime) < 2){
				$datetime = "0".$datetime;
			}else{
				$datetime = $datetime;
			}

			$sub_array[] = array("v" => $datetime);
			$sub_array[] = array("v" => $row25['data_value']);
			$sub_array[] = array("v" => $row10['data_value']);
			$rows[] = array("c" => $sub_array);
		}
		
		$table['rows'] = $rows;
		$jsonTable = json_encode($table);
		
		echo $jsonTable;
		break;
	case "1101":
		break;
	case "1201":
		break;
	}
?>
