<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>Introduction to SPL</title>
<style type="text/css">
div.thumb {
  float: left;
  width: 25%;
  border: thin silver solid;
  margin: 0.5em;
  padding: 0.5em;
}
div.thumb p {
  text-align: center;
  font-style: italic;
  font-size: smaller;
  text-indent: 0;
}
</style>
</head>
<body>
<h2>Thumbnails</h2>
<p>By Richard Palmer</p>
<?php

$thumb_metadata = thumb_list();
foreach($thumb_metadata as $array) {
	echo '<div class="thumb" style="width: '.$array['thumb_width'].'px; height: '.$array['thumb_height'].'px;">
            <p><a href="view/show_image.php?image_id='.$array['image_id'].'&which=image'.'">
            <img src="view/show_image.php?image_id='.$array['image_id'].'&which=image_thumb" alt="'.$array['image_name'].' /">
            </a></p>
            <p>'.$array['image_name'].'</p></div>';
}
echo '<p> <a href="?action=logout">Logout</a></p>';
?>
<p><a href="view/upload.html">Upload</a></p>
</body>
</html>