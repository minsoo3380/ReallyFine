<?php
	$param_val = $_REQUEST['type'];
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
		if($param_val == '0'){

			$sql_pm25 = "select * from public_data_pm25 where created_date = '".$param_date."' and mss_code = '".$param_code."' order by 'mss_code' asc";
			$sql_pm10 = "select * from public_data_pm10 where created_date = '".$param_date."' and mss_code = '".$param_code."' order by 'mss_code' asc";
			$st25 = $pdo->query($sql_pm25);
			$st10 = $pdo->query($sql_pm10);

			$rows = array();
			$table = array();

			$table['cols'] = array(
				array('label' => 'Date Time', 'type' => 'string'),
				array('label' => 'pm25', 'type' => 'number'),
				array('label' => 'pm10', 'type' => 'number'),
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
		}else{
			$cur_month = substr($param_date, 0, 7);
			$sql_pm25 = "select * from public_data_pm25 where created_date like '$cur_month%' and mss_code = $param_code order by 'created_date' asc";
			
			$sql_pm10 = "select * from public_data_pm10 where created_date like '$cur_month%' and mss_code = $param_code order by 'created_date' asc";

			$st25 = $pdo->query($sql_pm25);
			$st10 = $pdo->query($sql_pm10);

			$rows = array();
			$table = array();

			$table['cols'] = array(
				array('label' => 'Date', 'type' => 'string'),
				array('label' => 'pm25', 'type' => 'number'),
				array('label' => 'pm10', 'type' => 'number'),
				array('label' => 'pm25_High', 'type' => 'number'),
				array('label' => 'pm10_High', 'type' => 'number'),
			);
			
			$value25 = 0;
			$value10 = 0;
			$value25H = 0;
			$value10H = 0;
			$count = 0;

			while($row25 = $st25->fetch()){
				$count++;

				$row10 = $st10->fetch();
			
				$sub_array = array();
				$cur_date = $row25['created_date'];
				$cur_date = substr($cur_date, 8, 10);
				
				if($value25H < (int)$row25['data_value']){
					$value25H = (int)$row25['data_value'];
				}
				if($value10H < (int)$row10['data_value']){
					$value10H = (int)$row10['data_value'];
				}
				$value25 += $row25['data_value'];
				$value10 += $row10['data_value'];

				if($count == 24){
					$value25 = $value25 / 24;
					$value10 = $value10 / 24;

					$sub_array[] = array("v" => $cur_date);
					$sub_array[] = array("v" => $value25);
					$sub_array[] = array("v" => $value10);
					$sub_array[] = array("v" => $value25H);
					$sub_array[] = array("v" => $value10H);
					$rows[] = array("c" => $sub_array);

					$count = 0;
					$value25 = 0;
					$value10 = 0;
					$value25H = 0;
					$value10H = 0;
				}
			}
		
			$table['rows'] = $rows;
			$jsonTable = json_encode($table);
		
			echo $jsonTable;
		}
		break;
	case "1101":
		if($param_val == '0'){

			$sql_aws = "select * from AWSData where create_date = '".$param_date."' and mss_code = '".$param_code."' order by 'mss_code' asc";
			$stAws = $pdo->query($sql_aws);

			$rows = array();
			$table = array();

			$table['cols'] = array(
				array('label' => 'Date Time', 'type' => 'string'),
				array('label' => 'direction', 'type' => 'number'),
			);
	
			while($rowAws = $stAws->fetch()){
				$sub_array = array();
				$datetime = $rowAws['create_time'];
				$datetime = (String)((int)$datetime + 1);
	
				if(strlen($datetime) < 2){
					$datetime = "0".$datetime;
				}else{
					$datetime = $datetime;
				}
	
				$sub_array[] = array("v" => $datetime);
				$sub_array[] = array("v" => $rowAws['direction_val']);
				$rows[] = array("c" => $sub_array);
			}
			
			$table['rows'] = $rows;
			$jsonTable = json_encode($table);
			
			echo $jsonTable;
		}else{
			$cur_month = substr($param_date, 0, 7);

			$sql_aws = "select * from AWSData where create_date like '$cur_month%' and mss_code = $param_code order by 'created_date' asc";
			$stAws = $pdo->query($sql_aws);

			$rows = array();
			$table = array();

			$table['cols'] = array(
				array('label' => 'Date', 'type' => 'string'),
				array('label' => 'direction', 'type' => 'number'),
			);
			
			$value = 0;
			$count = 0;

			while($row = $stAws->fetch()){
				$count++;
			
				$sub_array = array();
				$cur_date = $row['create_date'];
				$cur_date = substr($cur_date, 8, 10);
				
				$value += $row['direction_val'];

				if($count == 24){
					$value = $value / 24;

					$sub_array[] = array("v" => $cur_date);
					$sub_array[] = array("v" => $value);
					$rows[] = array("c" => $sub_array);

					$count = 0;
					$value = 0;
				}
			}
		
			$table['rows'] = $rows;
			$jsonTable = json_encode($table);
		
			echo $jsonTable;
		}
		break;
	case "1101":
		if($param_val == '0'){
			
		}
		break;
	case "1201":
		if($param_val == '0'){
			$sql_aws = "select * from AWSData where create_date = '".$param_date."' and mss_code = '".$param_code."' order by 'mss_code' asc";
			$stAws = $pdo->query($sql_aws);

			$rows = array();
			$table = array();

			$table['cols'] = array(
				array('label' => 'Date Time', 'type' => 'string'),
				array('label' => 'velocity', 'type' => 'number'),
			);
	
			while($rowAws = $stAws->fetch()){
				$sub_array = array();
				$datetime = $rowAws['create_time'];
				$datetime = (String)((int)$datetime + 1);
	
				if(strlen($datetime) < 2){
					$datetime = "0".$datetime;
				}else{
					$datetime = $datetime;
				}

				$sub_array[] = array("v" => $datetime);
				$sub_array[] = array("v" => $rowAws['velocity']);
				$rows[] = array("c" => $sub_array);
			}
		
			$table['rows'] = $rows;
			$jsonTable = json_encode($table);
		
			echo $jsonTable;
		}else{
			$cur_month = substr($param_date, 0, 7);

			$sql_aws = "select * from AWSData where create_date like '".$cur_month."%' and mss_code = '".$param_code."' order by 'create_date' asc";
			$stAws = $pdo->query($sql_aws);

			$rows = array();
			$table = array();

			$table['cols'] = array(
				array('label' => 'Date Time', 'type' => 'string'),
				array('label' => 'velocity', 'type' => 'number'),
			);
		
			$count = 0;
			$value = 0;

			while($rowAws = $stAws->fetch()){
				$count++;

				$sub_array = array();
				$cur_date = $rowAws['create_date'];
				$cur_date = substr($cur_date, 8, 10);
	
				$value += $rowAws['velocity'];

				if($count == 24){
					$value = $value / 24;

					$sub_array[] = array("v" => $cur_date);
					$sub_array[] = array("v" => $value);
					$rows[] = array("c" => $sub_array);

					$count = 0;
					$value = 0;
				}
			}

			$table['rows'] = $rows;
			$jsonTable = json_encode($table);
		
			echo $jsonTable;
		}
		break;
	}
?>
