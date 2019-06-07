<?php
	$param_sec = $_REQUEST['section'];
	$param_date = $_REQUEST['date'];
	$param_chart = $_REQUEST['chart'];

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

	$pm25_Alen = 0;
	$pm25_len = 0;
	$pm10_Alen = 0;
	$pm10_len = 0;
	$pm25_rows = NULL;
	$pm10_rows = NULL;

	switch($param_sec){
	case "1001":
		$sql_pm25 = "select * from public_data_pm25 where created_date = '".$param_date."' order by 'mss_code' asc";
		$sql_pm10 = "select * from public_data_pm10 where created_date = '".$param_date."' order by 'mss_code' asc";
		$st25 = $pdo->query($sql_pm25);
		$st10 = $pdo->query($sql_pm10);
		$pm25_rows = $st25->fetchAll(PDO::FETCH_OBJ);
		$pm10_rows = $st10->fetchAll();
		break;
	case "1101":
		break;
	case "1201":
		break;
	}

	echo json_encode($pm25_rows);
?>
