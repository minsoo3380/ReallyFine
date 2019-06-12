<?php
	$param_menu = $_REQUEST['menu'];
	$param_sec = $_REQUEST['RFmenu'];
	
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

	$sql = "select id, title from sdm_tree where parent_id = 1";
	$st = $pdo->query($sql);
	$rows = $st->fetch();
			
	echo "<ul>";
	while($rows){
		$id = $rows[id];
		$title = $rows[title];

		echo "<li>$title</li>";
		echo "<ul>";
				
		$sub_sql = "select id, title from sdm_tree where parent_id = ".$id;
		$st2 = $pdo->query($sub_sql);

		while($sub = $st2->fetch()){
			echo "<li>$sub[title]</li>";
		}
		echo "</ul>";

		$rows = $st->fetch();
	}
	echo "</ul>";
?>
