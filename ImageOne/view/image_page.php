<?php include ('header.php');?>
    <?php
    $image_id = filter_input(INPUT_GET,"image_id", FILTER_SANITIZE_NUMBER_INT);
    echo '<img src="view/show_image.php?image_id='.$image_id.'&which=image "/>';
	?>
	<p><a href="index.php?action=thumbnails">Return to List</a></p>
<?php include ('footer.php');?>