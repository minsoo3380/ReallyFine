<?php
	$param_name = $_REQUEST['name'];
	/*
	$url = "http://openapi.airkorea.or.kr/openapi/services/rest/ArpltnInforInqireSvc/getMsrstnAcctoRltmMesureDnsty?_returnType=json&ServiceKey=2PuKO1q0Q%2Ftx7rBWUUUVCO5xzzuLRVLj6ocZPCVsWE96i9p6SzkLzDulR2RDDTbAHgI26d3TW%2B3JFpd1n5emJw%3D%3D&ver=1.3&dataTerm=DAILY&numOfRows=1&stationName=";
	
	$url = $url.$param_name;
	$html = file_get_contents($url);
	$obj = json_decode($html);
	//print_r($obj);
	//print_r($obj->list);
	//print_r($obj->list[0]);
	print_r($obj->list[0]->pm10Grade);
	*/
	// 너무나도 많은 데이터 요청으로 너무 느림....
	// server DB접근으로 변경
	// 아 진짜.... 에어코리아 서버 개판이라서 접근 조차 안됨... 공공데이터 포함해서;;; 짜증나네
	
?>
