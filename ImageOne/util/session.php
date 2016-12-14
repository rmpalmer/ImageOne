<?php
function default_limits($reset=false) {
	if (!isset($_SESSION['limit_offset']) or $reset) {
		$_SESSION['limit_offset'] = 0;
	}
	if (!isset($_SESSION['limit_count']) or $reset) {
		$_SESSION['limit_count'] = 10;
	}
}
function page_up() {
	default_limits();
	$_SESSION['limit_offset'] = max(0,$_SESSION['limit_offset'] - $_SESSION['limit_count']);
	
}
function page_down() {
	default_limits();
	$_SESSION['limit_offset'] = $_SESSION['limit_offset'] + $_SESSION['limit_count'];
}
?>