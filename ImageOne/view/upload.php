<?php include ('header.php');?>

<form method="post" action="index.php" enctype="multipart/form-data">
Select Image File:
<input type="file" name="userfile"  size="40"><br>
<label>Keywords:</label>
<input type="text" name="keywords" size="60">
<input type="hidden" name="MAX_FILE_SIZE" value="10000000">
<input type="hidden" name="action" value="submit_upload">
<br />
<input type="submit" value="Store">
</form>

<?php include ('footer.php');?>