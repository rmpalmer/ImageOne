<?php include ('header.php');?>
	<?php
    $image_id = filter_input(INPUT_GET,"image_id", FILTER_SANITIZE_NUMBER_INT);
    echo '<img src="view/show_image.php?image_id='.$image_id.'&which=image "/>';
	?>
	<br>
	<form action="index.php" method="post" id="edit_image_form">
	<input type="text" name="keywords" value="<?php echo get_keywords($image_id);?>">
	<input type="hidden" name="action" value="update_keywords">
	<input type="hidden" name="image_id" value="<?php echo $image_id?>">
	<input type="submit" value="Update Keywords">
	</form>
	<form action="index.php" method="post" id="delete_image_form">
	<input type="hidden" name="action" value="delete_image">
	<input type="hidden" name="image_id" value="<?php echo $image_id?>">
	<input type="submit" value="Delete">	
	</form>
	<p><a href="index.php?action=thumbnails">Return to List</a></p>
	
<?php include ('footer.php');?>