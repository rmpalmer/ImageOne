<?php
$INC_DIR = $_SERVER["DOCUMENT_ROOT"]. "/ImageOne/";

require ($INC_DIR . 'model/image_db.php');
/*** check if a file was submitted ***/
if(!isset($_FILES['userfile']))
{
	echo '<p>Please select a file</p>';
}
else
{
	try {
		upload();
		/*** give praise and thanks to the php gods ***/
		echo '<p>Thank you for submitting</p>';
	}
	catch(PDOException $e)
	{
		echo '<h4>'.$e->getMessage().'</h4>';
	}
	catch(Exception $e)
	{
		echo '<h4>'.$e->getMessage().'</h4>';
	}
}

?>
