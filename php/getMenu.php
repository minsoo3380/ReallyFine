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

	switch($param_menu){
		case "1":
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

			break;
		case "2":
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

			echo "<ul><li>$p_title</li><ul>";

			while($cur_menu){
				echo "<li>$cur_menu[title]</li>";

				if($cur_menu[id] == $param_sec){
					$sql = "select * from sdm_tree where parent_id = ".$cur_menu[id];
					$st2 = $pdo->query($sql);
					$child = $st2->fetch();
					
					echo "<ul>";
					while($child){
						echo "<li>$child[title]</li>";
				
						$child = $st2->fetch();
					}
					echo "</ul>";
				}
	
				$cur_menu = $st->fetch();
			}
			echo "</ul></ul>";

			break;
	}
?>
