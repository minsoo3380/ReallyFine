<?php

	$table = array();

	print_r($table);
	echo "<br>";
	$table['cols'] = array(
		array('label1' => 'Weekly Task', 'type' => 'string'),
		array('label2' => 'Percentage', 'type' => 'number'));
		
	print_r($table);
	echo "<br><br>";

	$temp = array();
	print_r($temp);
	echo "<br>";

	$temp[] = array('v' => (string) 'test01');
	print_r($temp);
	echo "<br>";

	$temp[] = array('v' => (int) '123');
	print_r($temp);
	echo "<br>";

	$rows[] = array('c' => $temp);

	print_r($temp);
	echo "<br>";
	print_r($rows);
?>
