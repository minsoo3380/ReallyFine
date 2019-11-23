<?php
	$param_posX = $_REQUEST['posX'];
	$param_posY = $_REQUEST['posY'];
	
	$url = "http://openapi.airkorea.or.kr/openapi/services/rest/MsrstnInfoInqireSvc/getNearbyMsrstnList?_returnType=json&ServiceKey=2PuKO1q0Q%2Ftx7rBWUUUVCO5xzzuLRVLj6ocZPCVsWE96i9p6SzkLzDulR2RDDTbAHgI26d3TW%2B3JFpd1n5emJw%3D%3D";

	$url = $url."&tmX=".$param_posX."&tmY=".$param_posY;

	$html = file_get_contents($url);
	$obj = json_encode($html, JSON_UNESCAPED_UNICODE);

	echo $obj;
?>
