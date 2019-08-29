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
	
	$sql = "select * from district";
	$st = $pdo->query($sql);
	$array = $st->fetchAll();

	echo count($array[0]);	
	print_r($array[0]);
?>
