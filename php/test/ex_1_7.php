<?php
	$db = new PDO('sqlite:dinner.db');
	$meals = array('아침', '점심', '저녁');
	
	if(in_array($_POST['meal'], $meals)){
		$stmt = $db->prepare('SELECT dish, price FROM meals WHERE meal LIKE ?');
		$stmt->execute(array($_POST['meal']));
		$rows = $stmt->fetchAll();

		if(count($rows) == 0){
			print "가능한 요리가 없습니다.";
		}
		else{
			print '<table><tr><th>요리</th><th>가격</th></tr>';
			foreach($rows as $row){
				print "<tr><td>$row[0]</td><td>$row[1]</td></tr>";
			}
			print "<table>";
		}
	}
	else{
		print "알 수 없는 메뉴입니다.";
	}

?>

