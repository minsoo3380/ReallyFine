<?php
	$param_local = $_REQUEST['local'];
	$param_dist = $_REQUEST['district'];

	$host = "localhost";
	$db = "ReallyFine";
	$user = "root";
	$pass = "123123";
	$sql = NULL;
	$tag = NULL;

	try{
		$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->exec("set names utf8");
	}catch(Exception $e){
		echo $e->getMessage();
	}

	if($param_dist != NULL){
		$sql = "select * from district";
		$st = $pdo->query($sql);

		while($row = $st->fetch()){
			echo '<option value="'.$row['dt_code'].'">'.$row['dt_name'].'</option>';
		}
	}else if($param_local != NULL){
		$sql = "select * from measure_station where dt_code = '".$param_local."'";
		$st = $pdo->query($sql);

		while($row = $st->fetch()){
			echo '<option value="'.$row['mss_code'].'">'.$row['mss_name'].'</option>';
		}
	}
?>
