<?php
	$param_sec = $_REQUEST['ASmenu'];

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

	$sql = "select * from sdm_tree where id = ".$param_sec;
	$st = $pdo->query($sql);
	$parent = $st->fetch();
	$p_id = $parent[parent_id];

	$sql = "select * from sdm_tree where id = ".$p_id;
	$st = $pdo->query($sql);
	$parent = $st->fetch();
	$p_title = $parent[title];

	//echo "$p_id, $p_title";

	$sql = "select * from sdm_tree where parent_id = ".$p_id;
	$st = $pdo->query($sql);
	$cur_menu = $st->fetch();

	echo '<ul class="ASParent"><li>'.$p_title.'</li><ul>';

	while($cur_menu){
		$link_sql = "select * from web_url where id = ".$cur_menu[id];
		$st = $pdo->query($link_sql);
		$cur_url = $st->fetch();

		echo '<a href = "http://112.186.204.239:50000/ReallyFine/html/'.$cur_url[url].'"><li>'.$cur_menu[title].'</li></a>';

		if($cur_menu[id] == $param_sec){
			$sql = "select * from sdm_tree where parent_id = ".$cur_menu[id];
			$st2 = $pdo->query($sql);
			$child = $st2->fetch();
					
			echo '<ul class="ASChild">';
			while($child){
				$link_sql = "select * from web_url where id = ".$child[id];
				$st3 = $pdo->query($link_sql);
				$child_url = $st3->fetch();
				
				echo '<a href = "http://112.186.204.239:50000/ReallyFine/html/'.$child_url[url].'"><li>'.$child[title].'</li></a>';
		
				$child = $st2->fetch();
			}
			echo "</ul>";
		}
	
		$cur_menu = $st->fetch();
	}
	echo "</ul></ul>";
?>