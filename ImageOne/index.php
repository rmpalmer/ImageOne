<?php
session_start();
include_once('model/database.php');
include_once('model/auth_db.php');
include_once('model/image_db.php');

$action = filter_input(INPUT_POST,'action');
if ($action == NULL) {
	$action = filter_input(INPUT_GET,'action');
}
if (!isset($_SESSION['is_auth_user'])) {
	$action = 'login';
} 

if ($action == 'logout') {
	logout();
	header("Location: .");
} elseif ($action == 'login') {
	$username = filter_input(INPUT_POST, 'username');
	$password = filter_input(INPUT_POST, 'password');
	if (is_valid_login($username, $password)) {
		$_SESSION['is_auth_user'] = true;
		header("Location: .");
	} else {
		$login_message = 'You must log in';
		include ('view/login.php');
	}
} else {
	include('view/header.php');
	include('view/thumbs.php');
	include('view/footer.php');
}
?>