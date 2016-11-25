<?php
function is_valid_login($user, $password) {
	global $db;

	$pass = sha1($password);
	$query = 'SELECT authid FROM auth WHERE username = :username
			  and password = :pass';
	$statement = $db->prepare($query);
	$statement->bindValue(':username',$user, PDO::PARAM_STR);
	$statement->bindValue(':pass', $pass, PDO::PARAM_STR);
	$statement->execute();
	$valid = ($statement->rowCount() == 1);
	$statement->closeCursor();
	return $valid;
}

function logout() {
	echo 'destroy';
	session_start();
	$_SESSION = array();
	setcookie(session_name(), '', time() - 2592000, '/');
	session_destroy();
}