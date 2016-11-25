<?php

$root = $_SERVER["DOCUMENT_ROOT"];
$config_fname = dirname($root) . '/.config';
$config_file = fopen($config_fname, 'rb') or die("Unable to configure database\n");

if ($config_file ) {
	$dbname   = trim(fgets($config_file));
	$username = trim(fgets($config_file));
	$password = trim(fgets($config_file));

	$dsn = 'mysql:host=localhost;dbname=' . $dbname;
	
	try {
		$db = new PDO($dsn,$username,$password);		
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		$error_message = $e->getMessage();
		include ('view/error.php');
		exit();
	}
}
?>