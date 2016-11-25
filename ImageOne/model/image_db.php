<?php
$INC_DIR = $_SERVER["DOCUMENT_ROOT"]. "/ImageOne/";

include_once($INC_DIR . 'model/database.php');

function get_image($image_id,$which) {
	global $db;
	$INC_DIR = $_SERVER["DOCUMENT_ROOT"]. "/OneImage/";
	try    {
		/*** The sql statement ***/
		$sql = "SELECT " . $which . ", image_type FROM testblob WHERE image_id=:image_id";
		
		/*** prepare the sql ***/
		$stmt = $db->prepare($sql);
		
		$stmt->bindValue(':image_id', $image_id, PDO::PARAM_INT);

		/*** exceute the query ***/
		$stmt->execute();

		/*** set the fetch mode to associative array ***/
		$stmt->setFetchMode(PDO::FETCH_ASSOC);

		/*** set the header for the image ***/
		$array = $stmt->fetch();

		$stmt->closeCursor();

		/*** check we have a single image and type ***/
		if(sizeof($array) <> 2)	{
			throw new Exception("Out of bounds Error");
		}
		return ($array);
	}
	catch(PDOException $e)
	{
		$error_message = $e->getMessage();
		include ($INC_DIR . 'model/database_error.php');
	}
	catch(Exception $e)
	{
		$error_message = $e->getMessage();
		include ($INC_DIR . 'model/database_error.php');
	}
}

function thumb_list() {
	global $db;
	$INC_DIR = $_SERVER["DOCUMENT_ROOT"]. "/OneImage/";
	try    {

		/*** The sql statement ***/
		$query = "SELECT image_id, thumb_height, thumb_width, image_type, image_name FROM testblob";

		/*** prepare the sql ***/
		$stmt = $db->prepare($query);

		/*** exceute the query ***/
		$stmt->execute();

		/*** set the fetch mode to associative array ***/
		$stmt->setFetchMode(PDO::FETCH_ASSOC);

		$result = $stmt->fetchAll();

		return ($result);
	}
	catch(PDOException $e)
	{
		$error_message = $e->getMessage();
		include ($INC_DIR . 'model/database_error.php');
	}
	catch(Exception $e)
	{
		$error_message = $e->getMessage();
		include ($INC_DIR . 'model/database_error.php');
	}
}
?>