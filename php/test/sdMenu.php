<?php
	require('classTest.php');

	$db = new dbcon();
	
	echo "<p>$db->host</p>";	
	echo "<p>$db->user</p>";
	echo "<p>$db->pw</p>";
	echo "<p>$db->dbName</p>";

	$db->connect();
?>
