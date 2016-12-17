<?php include 'header.php';?>

<table border="1px">
<tr></tr>
 <td>one</td>
 <td>two</td>
<tr></tr>
 <td colspan="2">three</td>
</table>
<br>
<form action="index.php" method="post">
<input type="text" name="filter_keys" value="<?php echo $_SESSION['filter_keys']?>">
<input type="hidden" name="action" value="set_filter_keys">
<input type="submit" value="Filter on Keywords">
</form>

<?php foreach ($thumb_metadata as $array) : ?>
  <div class="thumb" style="width: <?php echo $array['thumb_width'];?>px; height: <?php echo $array['thumb_height'];?>px;">
  <p><a href="?action=show_image&amp;image_id=<?php echo $array['image_id'];?>&amp;which=image">
  <img src="view/show_image.php?image_id=<?php echo $array['image_id'];?>&which=image_thumb"/>
  </a></p>
  </div>
<?php endforeach;?>
<br>
<p><a href="?action=logout">Logout</a></p>
<p><a href="?action=choose_upload">Upload</a></p>
<p><a href="?action=thumbnails">Refresh</a></p>
<p><a href="?action=next">Next</a></p>
<p><a href="?action=prev">Previous</a></p>
<p><a href="?action=reset">Reset</a>

<?php include 'footer.php'?>