<?php
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
			
	echo '<ul class="NavTop">';
	while($rows){
		$id = $rows[id];
		$title = $rows[title];
		
		$url_sql = "select w.url from sdm_tree as s join web_url as w on s.id=w.id where s.id = ".$id;
		$st3 = $pdo->query($url_sql);
		$web_url = $st3->fetch();

		//echo $web_url[url];

		echo '<a href="'.$web_url[url].'"><li class="NavList">'.$title.'</li></a>';
		echo '<ul class="inerList">';
				
		$sub_sql = "select id, title from sdm_tree where parent_id = ".$id;
		$st2 = $pdo->query($sub_sql);

		while($sub = $st2->fetch()){
			$url_sql2 = "select w.url from sdm_tree as s join web_url as w on s.id=w.id where s.id = ".$sub[id];
			$st4 = $pdo->query($url_sql2);
			$web_sub_url = $st4->fetch();

			//echo $web_sub_url[url];
			
			echo '<a href="'.$web_sub_url[url].'"><li>'.$sub[title].'</li></a>';
		}
		echo "</ul>";

		$rows = $st->fetch();
	}
	echo "</ul>";
?>
