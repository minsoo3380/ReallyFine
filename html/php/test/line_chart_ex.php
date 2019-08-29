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
		$st25 = $pdo->query($sql_pm25);
		$st10 = $pdo->query($sql_pm10);
		$rows = array();
		$table = array();

		$table['cols'] = array(
			array('label' => 'Date Time', 'type' => 'datetime'),
			array('label' => 'pm25', 'type' => 'number')
		);

		while($row25 = $st25->fetch()){
			$sub_array = array();
			$datetime = $row25['created_time'];

			if(strlen($datetime) < 2){
				$datetime = "0".$datetime.":00";
			}else{
				$datetime = $datetime.":00";
			}

			$sub_array[] = array("v" => $datetime);
			$sub_array[] = array("v" => $row25['data_value']);
			$rows[] = array("c" => $sub_array);
		}
		
		$table['rows'] = $rows;
		$jsonTable = json_encode($table);
		
		//echo $jsonTable;
		break;
	case "1101":
		break;
	case "1201":
		break;
	}
?>

<html>
	<head>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<Script src="../js/jquery-3.4.0.js"></script>
		<script type="text/javascript">
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChart);
		
			function drawChart(){
				var data = new google.visualization.DataTable(<?php echo $jsonTable; ?>);
				var options = {
					title:'Public Dust Data',
					legend:{position:'bottom'},
					chartArea:{width:'95%', height:'65%'}};
				var chart = new google.visualization.LineChart(document.getElementById('line_chart'));
				chart.draw(data, options);
			}
			</script>
		<style>
			.page-wrapper{
				width:1000px;
				margin:0 auto;
			}
		</style>
	</head>

	<body>
		<div class="page-wrapper">
			<br />
			<h2 align="center">Display Google Line Chart with JSON PHP & Mysql</h2>
			<div id="line_chart" style="width: 100%; height:500px"></div>
		</div>
	</body>
</html>
