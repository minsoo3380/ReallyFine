<?php
	$param_sec = $_REQUEST['section'];
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

?>

	<div id="Line_Controls_Chart">
		<div id="lineChartArea" style="padding:0px 20px 0px 0px;"></div>
		<div id="controlsArea" style="padding:0px 20px 0px 0px;"></div>
	</div>

	<script>
		var chartDrowFun = {
			chartDrow : function(){
				var chartData = '';

				var chartDateformat = 'yyyy년 MM월 dd일';
				var chartLineCount = 10;
				var controlLineCount = 10;
				
				function drawDashboard(){
					var data = new google.visualiztion.DataTable();
					data.addColumn('datetime', 'measure_date');
					data.addColumn('number', 'pm25');
					data.addColumn('number', 'pm10');

					var dataRow = [];
					
				}
			}
		}
	</script>
