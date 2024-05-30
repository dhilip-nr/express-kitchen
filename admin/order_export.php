<?php
	$filename = base64_decode($_GET['id']);
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/octet-stream");
	readfile("uploads/".$filename);
	unlink("uploads/".$filename);
	exit;
?>