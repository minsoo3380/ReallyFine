<?php
	$param_posX = $_REQUEST['posX'];
	$param_posY = $_REQUEST['posY'];
	
	$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$param_posX.",".$param_posY."&language=ko&key=AIzaSyDJIbZy2OQSfRA5hOeqz69T-8I3QQYbHqI";

	//echo $url;

	$html = file_get_contents($url);
	$obj = json_encode($html, JSON_UNESCAPED_UNICODE);

	echo $obj;
?>

