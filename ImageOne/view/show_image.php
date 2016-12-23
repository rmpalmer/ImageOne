<?php
session_start();
$INC_DIR = $_SERVER["DOCUMENT_ROOT"]. "/ImageOne/";
include_once ($INC_DIR . 'model/image_db.php');
include_once ($INC_DIR . 'util/images.php');

if (!isset($_SESSION['is_auth_user'])) {
	$error_message = 'This is not allowed';
	include ($INC_DIR . 'view/denial.php');
} elseif(filter_has_var(INPUT_GET, "image_id") !== false && filter_input(INPUT_GET, 'image_id', FILTER_VALIDATE_INT) !== false) {
	/*** assign the image id ***/
	$image_id = filter_input(INPUT_GET, "image_id", FILTER_SANITIZE_NUMBER_INT);
	$which = filter_input(INPUT_GET,"which", FILTER_SANITIZE_STRING);
	$max_hw = filter_input(INPUT_GET,"max_hw", FILTER_SANITIZE_NUMBER_INT);
	
    $the_data = get_image($image_id,$which);
	
	/*** set the headers and display the image ***/
	header("Content-type: ".$the_data['image_type']);

	/*** output the image (perhaps resized ***/
	if ($max_hw == NULL) {
		echo $the_data[$which];
	} else {
		ob_start();
		echo $the_data[$which];
		$full_res = imageCreateFromString(ob_get_contents());
		ob_end_clean();
		
		// this works when resize just returns the original
		$to_show = resize($full_res, $max_hw);
		imageJPEG($to_show);
	}
	
}
else
{
	echo 'Please use a real id number';
}
?>