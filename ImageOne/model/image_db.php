<?php

if (isset($_SERVER['DOCUMENT_ROOT'])) {
	$INC_BASE = $_SERVER['DOCUMENT_ROOT'];
	if ($INC_BASE <> NULL) {
		$INC_DIR = $INC_BASE . "/ImageOne/";
	    include_once($INC_DIR . 'model/database.php');
	    include_once($INC_DIR . 'util/session.php');
	}
}

function add_keyword($keyword) {
	global $db;

	if (is_null($keyword)) {
		return;
	}
	$query_1 = 'SELECT idkeywords FROM keywords where word = :word';
	$statement_1 = $db->prepare($query_1);
	$statement_1->bindValue(':word', $keyword);
	$statement_1->execute();
	$old_id = $statement_1->fetchAll();
	$statement_1->closeCursor();

	$errors = array_filter($old_id);
	if (empty($errors)) {
		$query_2 = 'INSERT INTO keywords (word) VALUES (:word)';
		$statement_2 = $db->prepare($query_2);
		$statement_2->bindValue(':word', strtolower($keyword));
		$statement_2->execute();
		$statement_2->closeCursor();
	}
}

function delete_image($image_id) {
	global $db;
	
	// first remove any entries about keywords describing this note.
	$query_1 = 'DELETE d from describes as d join images as i on d.images_image_id=i.image_id
                            where i.image_id=:image_id';
	$statement_1 = $db->prepare($query_1);
	$statement_1->bindValue(':image_id', $image_id,PDO::PARAM_INT);
	$statement_1->execute();
	$statement_1->closeCursor();
	
	// only then can I remove the note itself
	$query_2 = 'DELETE FROM images where image_id = :image_id';
	$statement_2 = $db->prepare($query_2);
	$statement_2->bindValue(':image_id', $image_id);
	$statement_2->execute();
	$statement_2->closeCursor();
	
}

function check_existing($filesize,$hash) {
	global $db;
	
	$query = 'SELECT image_name from images where hash=:hash and filesize=:filesize';
	$statement = $db->prepare($query);
	$statement->bindValue(':hash',$hash,PDO::PARAM_STR);
	$statement->bindValue(':filesize',$filesize,PDO::PARAM_INT);
	$statement->execute();
	$array = $statement->fetch();
	$statement->closeCursor();
	
	if ($array) {
		return $array['image_name'];
	} else {
		return NULL;
	}
}

function upload($imageName,$tmpFilePath,$fileSize,$keywords){
	global $db;
	/*** check if a file was uploaded ***/
	if(true)
	{
		/***  get the image info. ***/
		$size = getimagesize($tmpFilePath);

		/*** assign our variables ***/
		$image_type   = $size['mime'];
		
		$imgfp        = fopen($tmpFilePath, 'rb');
		$image_width  = $size[0];
		$image_height = $size[1];
		$image_size   = $size[3];
		$maxsize      = 99999999;
		$hash_value   = hash_file('md5',$tmpFilePath);

		/***  check the file is less than the maximum file size ***/
		if($fileSize < $maxsize )
		{
			$old_name = check_existing($fileSize, $hash_value);
			if ($old_name <> NULL) {
				throw new Exception('file appears to already be stored: [' . $old_name . ']');
			}
			
			/*** create a second variable for the thumbnail ***/
			$thumb_data = $tmpFilePath;

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
			
			$query = <<<EOQ
INSERT INTO images (image_type , image, image_height, image_width, image_thumb, thumb_height, thumb_width, image_name, hash, filesize)
		    VALUES   (:image_type,:image,:image_height,:image_width,:image_thumb,:thumb_height,:thumb_width,:image_name,:hash,:filesize)
EOQ;
			
			$stmt = $db->prepare($query);
			
			$stmt->bindValue(':image_type',$image_type,PDO::PARAM_STR);
			$stmt->bindValue(':image',$imgfp, PDO::PARAM_LOB);
			$stmt->bindValue(':image_height',$image_height,PDO::PARAM_INT);
			$stmt->bindValue(':image_width',$image_width,PDO::PARAM_INT);
			$stmt->bindValue(':image_thumb',$image_thumb, PDO::PARAM_LOB);
			$stmt->bindValue(':thumb_height',$thumb_height,PDO::PARAM_INT);
			$stmt->bindValue(':thumb_width',$thumb_width,PDO::PARAM_INT);
			$stmt->bindValue(':image_name',$imageName,PDO::PARAM_STR);
			$stmt->bindValue(':hash',$hash_value,PDO::PARAM_STR);
			$stmt->bindValue(':filesize',$fileSize,PDO::PARAM_INT);

			/*** execute the query ***/
			$stmt->execute();
			$stmt->closeCursor();
			
			$image_id = $db->lastInsertId();
			
			if (!is_null($keywords)) {
				$keys = explode(" ", $keywords);
				foreach ($keys as $word) {
					add_keyword($word);
					$query_2 = 'INSERT INTO describes
					(images_image_id,keywords_idkeywords)
					select image_id,idkeywords from images inner join keywords
					where image_id=:image_id and word=:word';
					$statement_2 = $db->prepare($query_2);
					$statement_2->bindValue(':image_id', $image_id, PDO::PARAM_INT);
					$statement_2->bindValue(':word', $word, PDO::PARAM_STR);
					$statement_2->execute();
					$statement_2->debugDumpParams();
						
					$statement_2->closeCursor();
				}
			}			
			
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
function get_keywords($image_id) {
	global $db;
	
	$query = 'SELECT word from keywords join describes
				on describes.keywords_idkeywords=keywords.idkeywords
			    where describes.images_image_id = :image_id';
	$statement = $db->prepare($query);
	$statement->bindValue(':image_id', $image_id);
	$statement->execute();
	$keyword_array = $statement->fetchAll(PDO::FETCH_COLUMN);
	
	$keyword_as_string = '';
	foreach ($keyword_array as $word) {
		$keyword_as_string .= ($word . ' ');
	}
	return $keyword_as_string;

}
function get_image($image_id,$which) {
	global $db;
	$INC_DIR = $_SERVER["DOCUMENT_ROOT"]. "/ImageOne/";
	try    {
		/*** The sql statement ***/
		$sql = "SELECT " . $which . ", image_type FROM images WHERE image_id=:image_id";
		
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

		/* page limits and limit offset */
		default_limits();
		
		$filter_keys = get_filter_keys();
				
		if (($filter_keys == NULL) or (empty($filter_keys))) {
			$query = "SELECT image_id, thumb_height, thumb_width, image_type, image_name FROM images LIMIT :offset,:count";
			$stmt = $db->prepare($query);		
			$stmt->bindValue(':offset',$_SESSION['limit_offset'],PDO::PARAM_INT);
			$stmt->bindValue(':count',$_SESSION['limit_count'],PDO::PARAM_INT);
		}
		else {
            $query = <<<EOQ
SELECT image_id, thumb_height, thumb_width, image_type, image_name FROM 
images i INNER JOIN describes d ON i.image_id = d.images_image_id INNER JOIN keywords k ON d.keywords_idkeywords = k.idkeywords
WHERE k.word IN (
EOQ;
 			$filter_array = explode(" ",$filter_keys);
            $comma = '';
 			foreach ($filter_array as $filter_word) {
 				$query = $query . $comma . $db->quote(trim($filter_word));
 				$comma = ',';
 			}
			$query = $query . ') LIMIT :offset,:count';
			$stmt = $db->prepare($query);
			$stmt->bindValue(':offset',$_SESSION['limit_offset'],PDO::PARAM_INT);
			$stmt->bindValue(':count',$_SESSION['limit_count'],PDO::PARAM_INT);
		}

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
function update_keywords($image_id,$keywords) {
	global $db;
	
	// first get a list of existing keywords
	$query_1 = 'SELECT word from keywords join describes
				on describes.keywords_idkeywords=keywords.idkeywords
			    where describes.images_image_id = :image_id';
	$statement_1 = $db->prepare($query_1);
	$statement_1->bindValue(':image_id',$image_id,PDO::PARAM_INT);
	$statement_1->execute();
	$old_keyword_array = $statement_1->fetchAll(PDO::FETCH_COLUMN);
	$statement_1->closeCursor();
	$new_keyword_array = explode(" ",$keywords);
	
	// find changes to be made
	$to_add = array_diff($new_keyword_array,$old_keyword_array);
	$to_remove = array_diff($old_keyword_array,$new_keyword_array);
	
	// remove words no longer in the list
	if (count($to_remove) > 0) {
		$query_2 = 'DELETE d from describes as d join keywords as k on d.keywords_idkeywords = k.idkeywords
				where k.word=:word and d.images_image_id=:image_id';
		$statement_2 = $db->prepare($query_2);
		$statement_2->bindValue(':image_id',$image_id,PDO::PARAM_INT);
		foreach ($to_remove as $removeit) {
			$statement_2->bindValue(':word', $removeit,PDO::PARAM_STR);
			$statement_2->execute();
		}
		$statement_2->closeCursor();
	}
	
	// add new ones
	if (count($to_add) > 0) {
		$query_3 = 'INSERT INTO describes
					(images_image_id,keywords_idkeywords)
					select image_id,idkeywords from images inner join keywords
					where image_id=:image_id and word=:word';
		$statement_3 = $db->prepare($query_3);
		$statement_3->bindValue(':image_id', $image_id, PDO::PARAM_INT);
		foreach ($to_add as $addit) {
			if (!empty($addit)) {
				add_keyword($addit);
				$statement_3->bindValue(':word', $addit, PDO::PARAM_STR);
				$statement_3->execute();
			}
		}
		$statement_3->closeCursor();
	}	
}
?>
