<?php
session_start();
include_once('model/database.php');
include_once('model/auth_db.php');
include_once('model/image_db.php');
include_once('util/session.php');

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
} elseif ($action == 'choose_upload') {
	unset ($_FILES['userfile']);
	include ('view/upload.php');
} elseif ($action == 'submit_upload') {
	$num_to_upload = count($_FILES['userfiles']['name']);
	$keywords = filter_input(INPUT_POST,'keywords');
	if ($num_to_upload > 0) {
		try {
			for ($i = 0; $i<$num_to_upload; $i++) {
				
				$tmpFilePath = $_FILES['userfiles']['tmp_name'][$i];
				$imageName   = $_FILES['userfiles']['name'][$i];
				$fileSize    = $_FILES['userfiles']['size'][$i];
				if ($tmpFilePath != "") {
					upload($imageName,$tmpFilePath,$fileSize,$keywords);
				}
			}
			header("Location: .");
		}
		catch (Exception $e) {
			$error_message = $e->getMessage();
			include("view/error.php");
		}
	} else {
		$error_message = 'You must select a file';
		include ('view/error.php');
	}
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
} elseif ($action == 'update_keywords') {
	$image_id = filter_input(INPUT_POST,'image_id',FILTER_VALIDATE_INT);
	$keywords = trim(filter_input(INPUT_POST,'keywords',FILTER_SANITIZE_STRING));
	update_keywords($image_id,$keywords);
	header("Location: .");
} elseif ($action == 'delete_image') {
	$image_id = filter_input(INPUT_POST,'image_id',FILTER_VALIDATE_INT);
	delete_image($image_id);
	header("Location: .");
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
} elseif ($action == 'reset') {
	default_limits(true);
	$thumb_metadata = thumb_list();
	include ('view/thumbs.php');
} elseif ($action == 'next') {
	page_down();
	$thumb_metadata = thumb_list();
	include ('view/thumbs.php');
} elseif ($action == 'prev') {
	page_up();
	$thumb_metadata = thumb_list();
	include ('view/thumbs.php');
} else {
	include('debug.php');
}
?>