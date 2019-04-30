<?php
	$logic = $_GET['menu'];
	echo "<script>console.log($logic)</script>";

	require('dbcon.php');

	$db = new dbcon();
	$mysqli = new mysqli($db->host, $db->user, $db->pw, $db->dbName);

	$sql = "select id, title from sdm_tree where parent_id = 1";
	$res = $mysqli->query($sql);
	
	$layer1 = $res->num_rows;
	echo "<script>console.log($layer1)</script>";


?>
