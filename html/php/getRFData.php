<?php
	$param_date = $_REQUEST['date'];
	$param_RFmenu = $_REQUEST['RFmenu'];
	$param_type = $_REQUEST['dateType'];
	$param_dist = $_REQUEST['district'];
	$table = null;

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

	switch($param_RFmenu){
		case "1000":
			$table = "web_pm10";
			break;
		case "1100":
			$table = "web_pm25";
			break;
		case "1200":
			$table = null;
			break;
		default:
			$table = null;
	}
	
	function MakeJson($st, $p_date, $p_type, $p_dist, $pdo){
		$obj = array();
		$cols = array();
		$rows = array();
		$data_rows = null;

		$data = $st->fetchAll();

		if(strcmp($p_type, "0") == 0){
			$cols[] = array('label' => 'Date Time', 'type' => 'string');
			//print_r($data[0]['mss_name']);
			
			for($i = 0;$i < count($data);$i++){
				$cols[] = array('label' => $data[$i]['mss_name'], 'type' => 'number');
			}

			$obj['cols'] = $cols;
			
			for($i = 0;$i < 24;$i++){
				$data_rows = array();
				$time = 't';
				$time = $time.(string)($i + 1);
				$data_rows[] = array("v" => (string)($i + 1));

				for($j = 0;$j < count($data);$j++){
					if($data[$j][$time] <= 0)
						$data_rows[] = array("v" => null);
					else
						$data_rows[] = array("v" => $data[$j][$time]);
				}
				$rows[] = array("c" => $data_rows);
			}
			
			$obj['rows'] = $rows;

			echo json_encode($obj, JSON_UNESCAPED_UNICODE);
		}else if(strcmp($p_type, "1") == 0){
			$year = (int)substr($p_date, 0, 4);
			$mon = (int)substr($p_date, 5, 2);
			$cur_mon = (int)date("m");
			$day = 0;
			
			$months = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
			
			if( (($year % 4 == 0) && ($year % 100 != 0)) || ($year % 400 == 0) )
				$months[1] = 29;

			if($mon != $cur_mon){
				$day = $months[$mon - 1];
			}else{
				$day = (int)date("d");
			}
			
			$cols[] = array('labal' => 'Day of Month', 'type' => 'string');
			
			$pre_date = $data[0]['created_date'];
			$mss_cnt = 0;

			for($i = 0;$i < count($data);$i++){
				$cur_date = $data[$i]['created_date'];
				
				if(strcmp($pre_date, $cur_date) == 0){
					$cols[] = array('label' => $data[$i]['mss_name'], 'type' => 'number');
					$mss_cnt = $mss_cnt + 1;
				}else{
					break;
				}
			}
			
			$obj['cols'] = $cols;
			
			//echo json_encode($obj, JSON_UNESCAPED_UNICODE);
			//print_r(count($data));
			
			$day_cnt = 0;
			$if_logic = 0;
			$cur_date = $data[0]['created_date'];

			for($k = 0;$k < $months[$mon - 1];$k++){
				$data_rows = array();
				$data_rows[] = array("v" => (string)($k + 1));
				//print_r( $data_rows);
				$pre_date = $cur_date;
				//echo "<br>".$k."<br>";
				//echo $day_cnt;
				//echo "k - pre : ".$pre_date." k - cur : ".$cur_date."<br>";
				//echo $data[$day_cnt]['mss_name']."<br>";

				if($if_logic == 1){
					for($i = 0;$i < $mss_cnt;$i++){
						$data_rows[] = array("v" => null);
					}
					$rows[] = array("c" => $data_rows);
				}

				for($i = $day_cnt;$i < count($data);$i++){
					//echo "i : ".$i." day : ".$day_cnt."<br>";
					$sum = 0;
					$count = 0;
					$cur_date = $data[$i]['created_date'];
	
					if( strcmp($pre_date, $cur_date) == 0){
						for($j = 4;$j < 28;$j++){
							if( (int)$data[$i][$j] < 0){
								continue;
							}
							$sum = $sum + $data[$i][$j];
							$count = $count + 1;
							//echo "sum : ".$sum.", c : ".$count."<br>";
						}
						$avg = (int)($sum / $count);
						//echo "avg : ".$avg."<br>";
						$data_rows[] = array("v" => $avg);
						if($i == count($data) -1){
							$day_cnt = $i + 1;
							$if_logic = 1;
							$rows[] = array("c" => $data_rows);
						}
					}else{
						//echo "in";
						$day_cnt = $i;
						$rows[] = array("c" => $data_rows);
						//echo "cur : ".$cur_date." pre : ".$pre_date."<br>";
						//echo "cnt : ".$day_cnt."<br>";
						break;
					}
				}
			}
			
			$obj['rows'] = $rows;
			echo json_encode($obj, JSON_UNESCAPED_UNICODE);
			
		}
	}
	
	//1이 평균 0이 일반
	$sql = "select m.mss_code, m.mss_location, m.mss_name, w.created_date, ";
	
	for($i = 0;$i < 24;$i++){
		$str = "t".(string)($i + 1);

		$sql = $sql.$str;

		if($i != 23){
			$sql = $sql.", ";
		}
	}

	$sql = $sql." from measure_station as m join ".$table." as w on m.mss_code=w.mss_code where m.dt_code = '".$param_dist."' and w.created_date like '%".$param_date."%'";

	if(strcmp($param_type, "1") == 0){
		$sql = $sql." order by w.created_date asc";
	}
	//echo $sql;
	$st = $pdo->query($sql);
	
	MakeJson($st, $param_date, $param_type, $param_dist, $pdo);
?>
