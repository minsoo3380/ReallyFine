<?php
	include "./PHPExcel-1.8/Classes/PHPExcel.php";

	$param_type = $_REQUEST['type'];
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
		$table_max = null;
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
		
	//1이 평균 0이 일반
	$sql = "select m.mss_location, m.mss_name, ";
	
	if( strcmp($param_type, "1") == 0){
		$sql = $sql."w.created_date, ";
	}

	for($i = 0;$i < 24;$i++){
		$str = "t".(string)($i + 1);

		$sql = $sql.$str;

		if($i != 23){
			$sql = $sql.", ";
		}
	}

	$sql = $sql." from measure_station as m join ".$type." as w on m.mss_code=w.mss_code where m.dt_code = '".$param_dist."' and w.created_date like '%".$param_date."%'";

	//print($sql);

	$st = $pdo->query($sql);
	$data = $st->fetchAll();
	
	$phpExcel = new PHPExcel();
	$fileName = iconv("UTF-8", "EUC-KR", "result");
	$sNum = 833;
	$sheetCnt = 0;
	//print(chr(833));
	//print(chr(858));
	
	if( strcmp($param_type, "0") == 0){
		$phpExcel->setActiveSheetIndex(0)->setCellValue("A1", "측정망");
		$phpExcel->setActiveSheetIndex(0)->setCellValue("B1", "측정소명");

		for($i = 2;$i < 26;$i++){
			if( ($sNum + $i) < 859){
				$sIndex = chr($sNum + $i)."1";
				//echo $sIndex;
			}else{
				$sheetCnt = $i;
			}
			$phpExcel->setActiveSheetIndex(0)->setCellValue($sIndex, $i - 1);
		}
		
		//print_r($data);
		//print($sheetCnt);
	
		for($i = 2;$i < count($data) + 2;$i++){
			for($j = 0;$j < 26;$j++){
				$sIndex = chr( $sNum + $j).$i;
				$phpExcel->setActiveSheetIndex(0)->setCellValue($sIndex, $data[$i - 2][$j]);
				//echo $data[$i-2][$j];
				//echo $sIndex;
			}
			//echo "<br>";
		}
		
		
		header("Content-Type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename=".$fileName.".xls");
		header("Cache-Control:max-age=0");

		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save('php://output');
		
		exit;
	}else if( strcmp($param_type, "1") == 0){
		$phpExcel->setActiveSheetIndex(0)->setCellValue("A1", "측정망");
		$phpExcel->setActiveSheetIndex(0)->setCellValue("B1", "측정소명");
		$phpExcel->setActiveSheetIndex(0)->setCellValue("C1", "측정일");

		for($i = 3;$i < 27;$i++){
			//echo "<br>".$i."<br>";

			if($i != 26){
				$sIndex = chr($sNum + $i)."1";
				//echo $sIndex;
			}else{
				$phpExcel->setActiveSheetIndex(0)->setCellValue("AA1", "24");
				break;
			}
			$phpExcel->setActiveSheetIndex(0)->setCellValue($sIndex, $i - 2);
		}
		//print($sheetCnt);

		for($i = 2;$i < count($data) + 2;$i++){
			for($j = 0;$j < 27;$j++){
				if($j != 26){
					$sIndex = chr( $sNum + $j).$i;
					//echo "<br>".$data[$i - 2][$j];
				}
				else{	
					$phpExcel->setActiveSheetIndex(0)->setCellValue("AA".$i, $data[$i - 2][26]);
					//echo "AA".$i;
					//echo "<br>".$data[$i - 2][26];
					break;
				}
				//$phpExcel->setActiveSheetIndex(0)->setCellFalue($sIndex, $data[$i - 2][$j]);
				//echo $sIndex;
				$phpExcel->setActiveSheetIndex(0)->setCellValue($sIndex, $data[$i - 2][$j]);
			}
			//echo "<br>";
		}
		
		header("Content-Type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename=".$fileName.".xls");
		header("Cache-Control:max-age=0");

		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save('php://output');
		
		exit;
	
	}
?>
