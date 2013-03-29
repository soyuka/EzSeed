<?php
require_once('inc/config.php');

if(isset($_GET['id']) && !empty($_GET['id'])) 
{
	$explorer->remove((int) $_GET['id'], true);

	
	if(isset($_GET['cover']) && is_string($_GET['cover']))
	{
		$path = USER_TEMP . basename($_GET['cover']);

		if(is_file($path))
			unlink($path);
	}

	header("Location:./");
	exit();
} else {
	header("HTTP/1.0 400 Bad Request");
	exit();
}