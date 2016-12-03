<?php
$INC_DIR = $_SERVER["DOCUMENT_ROOT"]. "/ImageOne/";

include_once($INC_DIR . 'model/database.php');
function upload(){
	global $db;
	/*** check if a file was uploaded ***/
	if(is_uploaded_file($_FILES['userfile']['tmp_name']) && getimagesize($_FILES['userfile']['tmp_name']) != false)
	{
		/***  get the image info. ***/
		$size = getimagesize($_FILES['userfile']['tmp_name']);

		/*** assign our variables ***/
		$image_type   = $size['mime'];
		$imgfp        = fopen($_FILES['userfile']['tmp_name'], 'rb');
		$image_width  = $size[0];
		$image_height = $size[1];
		$image_size   = $size[3];
		$image_name   = $_FILES['userfile']['name'];
		$maxsize      = 99999999;

		/***  check the file is less than the maximum file size ***/
		if($_FILES['userfile']['size'] < $maxsize )
		{
			/*** create a second variable for the thumbnail ***/
			$thumb_data = $_FILES['userfile']['tmp_name'];

			/*** get the aspect ratio (height / width) ***/
			$aspectRatio=(float)($size[0] / $size[1]);

			/*** the height of the thumbnail ***/
			$thumb_height = 100;

			/*** the thumb width is the thumb height/aspectratio ***/
			$thumb_width = $thumb_height * $aspectRatio;

			/***  get the image source ***/
			$src = ImageCreateFromjpeg($thumb_data);

			/*** create the destination image ***/
			$destImage = ImageCreateTrueColor($thumb_width, $thumb_height);

			/*** copy and resize the src image to the dest image ***/
			ImageCopyResampled($destImage, $src, 0,0,0,0, $thumb_width, $thumb_height, $size[0], $size[1]);

			/*** start output buffering ***/
			ob_start();

			/***  export the image ***/
			imageJPEG($destImage);

			/*** stick the image content in a variable ***/
			$image_thumb = ob_get_contents();

			/*** clean up a little ***/
			ob_end_clean();

			/*** prepare the sql ***/
			$stmt = $db->prepare("INSERT INTO testblob (image_type ,image, image_height, image_width, image_thumb, thumb_height, thumb_width, image_name)
        VALUES (? ,?, ?, ?, ?, ?, ?, ?)");
			$stmt->bindParam(1, $image_type);
			$stmt->bindParam(2, $imgfp, PDO::PARAM_LOB);
			$stmt->bindParam(3, $image_height, PDO::PARAM_INT);
			$stmt->bindParam(4, $image_width,  PDO::PARAM_INT);
			$stmt->bindParam(5, $image_thumb,  PDO::PARAM_LOB);
			$stmt->bindParam(6, $thumb_height, PDO::PARAM_INT);
			$stmt->bindParam(7, $thumb_width,  PDO::PARAM_INT);
			$stmt->bindParam(8, $image_name);

			/*** execute the query ***/
			$stmt->execute();

		}
		else
		{
			/*** throw an exception is image is not of type ***/
			throw new Exception("File Size Error");
		}
	}
	else
	{
		// if the file is not less than the maximum allowed, print an error
		throw new Exception("Unsupported Image Format!");
	}
}
function get_image($image_id,$which) {
	global $db;
	$INC_DIR = $_SERVER["DOCUMENT_ROOT"]. "/ImageOne/";
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
	$INC_DIR = $_SERVER["DOCUMENT_ROOT"]. "/ImageOne/";
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