<?php
	$param = $_REQUEST['RFmenu'];
	$aside_pid = null;
	$aside_id = null;

	switch($param){
		case "900":
			$aside_pid = '6';
			break;
		case "1000":
			$aside_pid = '7';
			$aside_id = '11';
			break;
		case "1100":
			$aside_pid = '7';
			$aside_id = '12';
			break;
		case "1200":
			$aside_pid = '7';
			$aside_id = '13';
			break;
		case "1300":
			$aside_pid = '8';
			$aside_id = '14';
			break;
		case "1400":
			$aside_pid = '8';
			$aside_id = '15';
			break;
		case "1500":
			$aside_pid = '9';
			$aside_id = '16';
			break;
		case "1600":
			$aside_pid = '9';
			$aside_id = '17';
			break;
		case "1700":
			$aside_pid = '9';
			$aside_id = '18';
			break;
	}

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

	$sql = "select * from sdm_tree where id = ".$aside_pid;
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

	echo '<ul class="ASParent"><li>'.$p_title.'</li><ul>';

	while($cur_menu = $st->fetch()){
		$link_sql = "select * from web_url where id = ".$cur_menu[id];
		$st2 = $pdo->query($link_sql);
		$cur_url = $st2->fetch();

		echo '<a href = "http://112.186.204.239:50000/ReallyFine/html/'.$cur_url[url].'"><li>'.$cur_menu[title].'</li></a>';
	
		//echo count($cur_menu);		
		//echo " $cur_menu[id], $cur_menu[title]";

		if($cur_menu[id] == $aside_pid){
			$sql = "select * from sdm_tree where parent_id = ".$cur_menu[id];
			$st3 = $pdo->query($sql);
			$child = $st3->fetch();
					
			echo '<ul class="ASChild">';
			while($child){
				$link_sql = "select * from web_url where id = ".$child[id];
				$st4 = $pdo->query($link_sql);
				$child_url = $st4->fetch();
				
				if(strcmp($aside_id, $child[id]) == 0)
					echo '<img src="images/cur_site.png">';
				echo '<a href = "http://112.186.204.239:50000/ReallyFine/html/'.$child_url[url].'"><li>'.$child[title].'</li></a>';
		
				$child = $st3->fetch();
			}
			echo "</ul>";
		}
	}
	echo "</ul></ul>";
?>
