<?php
include('header.php');
?>
	<div class="element list" style="height:700px;padding:0px">
		<?if($percentSpaceUsed < 95) {?>
 			<iframe src="<?echo User::$transmission_web_url?>" width="100%" height="100%"></iframe>
		

 		<?}else{?>
			<p class="big center white">Vous utilisez trop d'espace disque, veuillez supprimer quelques fichiers !</p>
		<?}?>
	</div>
<?php
include('footer.php');
?>