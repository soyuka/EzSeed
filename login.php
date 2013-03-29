<?php
if(!defined(SB) && SB !== true)die();

include('header.php');
?>

	<div class="element list">
		<form method="POST" action="connexion">
		<input type="text" name="username" placeholder="Nom d'utilisateur" class="username" required="true"/>
		<input type="password" name="password" placeholder="Mot de passe" class="password" required="true" />
		<div class="center">
			<button type="submit" class="connexion">Connexion</button>
		</div>
		<?if($_SESSION['error']) {?>
			<p class="center error"><?echo $_SESSION['error'];?></p>
		<?
			unset($_SESSION['error']);
		}?>
		</form>
	</div>
