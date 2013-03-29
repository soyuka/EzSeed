<?php
	if(!defined(SB) && SB !== true)die();

	setcookie('u', 'deleted', time()-3600*24, BASE);
	header('Location:./');
	exit();
?>