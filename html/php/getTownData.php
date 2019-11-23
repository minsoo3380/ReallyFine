<?php
	$param_station = $_REQUEST['station'];
	
	$url = "http://openapi.airkorea.or.kr/openapi/services/rest/ArpltnInforInqireSvc/getMsrstnAcctoRltmMesureDnsty?stationName=".$param_station."&dataTerm=DAILY&pageNo=1&numOfRows=1&ServiceKey=2PuKO1q0Q%2Ftx7rBWUUUVCO5xzzuLRVLj6ocZPCVsWE96i9p6SzkLzDulR2RDDTbAHgI26d3TW%2B3JFpd1n5emJw%3D%3D&ver=1.3&_returnType=json";
	//echo $url;

	$html = file_get_contents($url);
	$obj = json_encode($html, JSON_UNESCAPED_UNICODE);

	echo $obj;
?>
