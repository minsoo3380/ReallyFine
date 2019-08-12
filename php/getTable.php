<?php
	$param_sec = $_REQUEST['section'];
	$param_date = $_REQUEST['date'];
	
	function table_writer($st){
		echo '<table id="data_table"><thead><tr><th>측정망</th><th>측정소</th>';
		
		for($i = 1;$i <=24;$i++){
			echo '<th>'.$i.'</th>';
		}

		echo '</tr></thead><tbody>';
		
		$data = $st->fetch();
		$old = null;
		
		echo '<tr><td>'.$data[mss_location].'</td><td>'.$data[mss_name].'</td>';

		while(true){
			if($data[data_value] != -1)
				echo '<td>'.$data[data_value].'</td>';
			else		
				echo '<td>-</td>';

			$old = $data;
			$data = $st->fetch();

			if(!$data){
				echo '</tr>';
				break;
			}
			if($old[mss_name] != $data[mss_name]){
				echo '</tr><tr><td>'.$data[mss_location].'</td><td>'.$data[mss_name].'</td>';
				
			}
		}

		echo '</tbody><tfoot>';
		
			
		echo '</tfoot></table>';
	}

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
		case "1000":
			$sql = "select m.mss_location, m.mss_name, p.data_value, p.created_time from measure_station as m join public_data_pm10 as p on m.mss_code=p.mss_code where p.created_date = '".$param_date."' order by m.mss_code, cast(created_time as unsigned) asc;";
			//echo $sql;

			$st = $pdo->query($sql);
			table_writer($st);			

			break;
		case "1100":
			$sql = "select m.mss_location, m.mss_name, p.data_value, p.created_time from measure_station as m join public_data_pm25 as p on m.mss_code=p.mss_code where p.created_date = '".$param_date."' order by m.mss_code, cast(created_time as unsigned) asc;";
		
			$st = $pdo->query($sql);
			table_writer($st);

			break;
	}
?>
