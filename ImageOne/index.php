<?php
session_start();
include_once('model/database.php');
include_once('model/auth_db.php');
include_once('model/image_db.php');

$action = filter_input(INPUT_POST,'action');
if ($action == NULL) {
	$action = filter_input(INPUT_GET,'action');
	if ($action == NULL) {
		$action = 'thumbnails';
	}
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
} elseif ($action == 'show_image') {
	$image_id = filter_input(INPUT_GET,'image_id',FILTER_VALIDATE_INT);
	if ($image_id == false) {
		$error_message = 'bad image id';
		include ('view/error.php');
	} else {
		$which = filter_input(INPUT_GET,"which", FILTER_SANITIZE_STRING);
		$the_data = get_image($image_id,$which);
		include ('view/image_page.php');
	}
} elseif ($action == 'thumbnails') {
	$thumb_metadata = thumb_list();
	include ('view/thumbs.php');
} else {
	include('debug.php');
}
?>