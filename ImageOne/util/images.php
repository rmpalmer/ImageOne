<?php
function resize($src, $max_hw) {
	
	$src_w = imagesx($src);
	$src_h = imagesy($src);
	
	$max_dim = max($src_w, $src_h);
	
	if ($max_dim > $max_hw) {
		$factor = ((float)$max_hw) / ((float)$max_dim);
		$new_width = (integer)($factor * (float)$src_w);
		$new_height = (integer)($factor * (float)$src_h);
		$destImage = ImageCreateTrueColor($new_width, $new_height);
		ImageCopyResampled($destImage, $src, 0,0,0,0, $new_width, $new_height, $src_w, $src_h);
		return $destImage;
	} else {
		return $src;
	}
}
?>