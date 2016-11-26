<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>This is an image</title>
</head>
    <body>
    <p>Something</p>
    <?php
    $image_id = filter_input(INPUT_GET, "image_id", FILTER_SANITIZE_NUMBER_INT);
    echo '<img src="show_image.php?image_id='.$image_id.'&which=image "/>';
	?>
	<p><a href="thumbs.php">Return to List</a></p>
	
    </body>
</html>