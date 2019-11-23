<?php
	$param_sec = $_REQUEST['section'];
	$param_dist = $_REQUEST['district'];
	$param_date = $_REQUEST['date'];
	$table_max = null;	

	$host = "localhost";
	$db = "ReallyFine";
	$user = "root";
	$pass = "123123";

	$type = null;
	
	if($param_sec == "1200"){
		if((int)$param_dist < 100){
			$param_dist = "0".$param_dist;
		}
		$url = "https://www.weather.go.kr/cgi-bin/aws/nph-aws_txt_min_cal_test?".$param_date."&0&MINDB_30M&4".$param_dist."&m&K";
		//echo $url."<br>";
		$content = file_get_contents_utf8($url);
		$content = remove_tag($content, array('p'));
		echo $content;
		exit;
	}else if($param_sec == "1000"){
		$type = "web_pm10";
	}else if($param_sec == "1100"){
		$type = "web_pm25";
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

	function remove_tag($str, $remove_tags) {
		foreach ($remove_tags as $key => $val) {
			$str = preg_replace("/<{$val}[^>]*>/i", '', $str);
			$str = preg_replace("/<\/{$val}>/i", '', $str);
		}
		return $str;
	}

	function file_get_contents_utf8($url){
		$html = file_get_contents($url);
		return mb_convert_encoding($html, 'UTF-8',
			mb_detect_encoding($html, 'euc-kr, ISO-8859-1', true));
	}
	
	function table_writer($st, $p_date){
		$param_type = $_REQUEST['type'];
		$data = $st->fetchAll();
		
		if($param_type == 0){
			echo "<thead><th>측정망</th><th>측정소명</th>";
	
			for($i = 0;$i < 24;$i++){
				echo "<th>".(string)($i + 1)."</th>";
			}
			
			echo "</thead>";
	
			echo "<tbody>";
	
			for($i = 0;$i < count($data);$i++){
				echo "<tr>";
	
				for($j = 0;$j < 26;$j++){
					echo "<td>";
					
					if($data[$i][$j] == -1){
						echo "-";
					}else if($data[$i][$j] == -2){
						echo "";
					}else{
						echo $data[$i][$j];
					}
					echo "</td>";
				}
				
				echo "</tr>";
			}
	
			echo "</tbody>";
		}else if($param_type == 1){
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

			echo "<thead><th>측정망</th><th>측정소명</th>";

			for($i = 0;$i < $months[$mon - 1];$i++){
				echo "<th>".(string)($i + 1)."</th>";
			}

			echo "</thead>";
			echo "<tbody>";
			
			$day_cnt = 0;
			$str_td = null;

			for($i = 0;$i < (int)(count($data));$i++){			
				$sum = 0;
				$count = 0;
				
				if($day_cnt == 0){	
					echo "<tr>";
					echo "<td>".$data[$i][0]."</td><td>".$data[$i][1]."</td>";
				}

				for($j = 2;$j < 26;$j++){
					if($data[$i][$j] == -1 || $data[$i][$j] == -2)
						continue;
					$sum = $sum + $data[$i][$j];
					$count = $count + 1;
				}
				$avg = (int)($sum / $count);
				
				$str_td = $str_td."<td>".(string)($avg)."</td>";
				$day_cnt = $day_cnt + 1;
				
				if($day_cnt == $day){
					echo $str_td."</tr>";
					$day_cnt = 0;
					$str_td = null;
				}
			}
		}
	}
	
	//1이 평균 0이 일반
	$sql = "select m.mss_location, m.mss_name, ";
	
	for($i = 0;$i < 24;$i++){
		$str = "t".(string)($i + 1);

		$sql = $sql.$str;

		if($i != 23){
			$sql = $sql.", ";
		}
	}

	$sql = $sql." from measure_station as m join ".$type." as w on m.mss_code=w.mss_code where m.dt_code = '".$param_dist."' and w.created_date like '%".$param_date."%'";

	$st = $pdo->query($sql);
	
	table_writer($st, $param_date);	
?>
