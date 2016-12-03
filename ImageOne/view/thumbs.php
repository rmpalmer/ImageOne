<?php include 'header.php';?>

<?php foreach ($thumb_metadata as $array) : ?>
  <div class="thumb" style="width: <?php echo $array['thumb_width'];?>px; height: <?php echo $array['thumb_height'];?>px;">
  <p><a href="?action=show_image&amp;image_id=<?php echo $array['image_id'];?>&amp;which=image">
  <img src="view/show_image.php?image_id=<?php echo $array['image_id'];?>&which=image_thumb"/>
  </a></p>
  </div>
<?php endforeach;?>

<p><a href="?action=logout">Logout</a></p>
<p><a href="view/upload.html">Old Upload</a></p>
<p><a href="?action=choose_upload">Upload</a></p>

<?php include 'footer.php'?>