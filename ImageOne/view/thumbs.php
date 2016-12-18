<?php include 'header.php';?>

<form action="index.php" method="post" id="upload_form">
<input type="hidden" name="action" value="choose_upload">
</form>

<form action="index.php" method="post" id="logout_form">
<input type="hidden" name="action" value="logout">
</form>

<form action="index.php" method="post" id="prev_form">
<input type="hidden" name="action" value="prev">
</form>

<form action="index.php" method="post" id="next_form">
<input type="hidden" name="action" value="next">
</form>

<form action="index.php" method="post" id="reset_form">
<input type="hidden" name="action" value="reset">
</form>

<form action="index.php" method="post" id="refresh_form">
<input type="hidden" name="action" value="thumbnails">
</form>



<table>
<tr>
 <td><button type="submit" form="upload_form" value="submit">Upload</button></td>
 <td><button type="submit" form="logout_form" value="submit">Logout</button></td>
</tr>
<tr>
 <td><button type="submit" form="prev_form" value="submit">Previous</button></td>
 <td><button type="submit" form="next_form" value="submit">Next</button></td>
</tr>
<tr>
 <td><button type="submit" form="reset_form" value="submit">Reset</button></td>
 <td><button type="submit" form="refresh_form" value="submit">Refresh</button></td>
</tr>
</table>

<form action="index.php" method="post">
<input type="text" name="filter_keys" value="<?php echo $_SESSION['filter_keys']?>">
<input type="hidden" name="action" value="set_filter_keys">
<input type="submit" value="Filter on Keywords">
</form>

<form action="index.php" method="post">
<input type="text" name="batch_size" value="<?php echo $_SESSION['limit_count']?>">
<input type="hidden" name="action" value="set_batch_size">
<input type="submit" value="Results per page">
</form>

<?php foreach ($thumb_metadata as $array) : ?>
  <div class="thumb" style="width: <?php echo $array['thumb_width'];?>px; height: <?php echo $array['thumb_height'];?>px;">
  <p><a href="?action=show_image&amp;image_id=<?php echo $array['image_id'];?>&amp;which=image">
  <img src="view/show_image.php?image_id=<?php echo $array['image_id'];?>&which=image_thumb"/>
  </a></p>
  </div>
<?php endforeach;?>
<br>

<?php include 'footer.php'?>