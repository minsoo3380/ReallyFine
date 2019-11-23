<?php
	$param_local = $_REQUEST['local'];
	$param_dist = $_REQUEST['district'];
	$param_aws = $_REQUEST['_aws'];

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

	if(strcmp($param_aws, "true") == 0){
		$sql = "select * from AWSLocation";
		$st = $pdo->query($sql);
		$data = $st->fetchAll();

		for($i = 0;$i < count($data);$i++){
			if($i == 0)
				echo '<option value="'.$data[$i][0].'" selected>'.$data[$i][1].'</option>';
			else
				echo '<option value="'.$data[$i][0].'">'.$data[$i][1].'</option>';
		}
		
		exit;
	}

	if($param_dist != NULL){
		$sql = "select * from district";
		$st = $pdo->query($sql);
		$i = 0;

		while($row = $st->fetch()){
			if($i == 0)
				echo '<option value="'.$row['dt_code'].'" selected>'.$row['dt_name'].'</option>';
			else
				echo '<option value="'.$row['dt_code'].'">'.$row['dt_name'].'</option>';
			$i = $i + 1;
		}
	}else if($param_local != NULL){
		$sql = "select * from measure_station where dt_code = '".$param_local."'";
		$st = $pdo->query($sql);

		while($row = $st->fetch()){
			echo '<option value="'.$row['mss_code'].'">'.$row['mss_name'].'</option>';
		}
	}
?>
