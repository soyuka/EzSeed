<?if(!defined('SB')) die();?>
<!DOCTYPE html>
<html>
<head>
	<meta name="robots" content="noindex, nofollow">
	<meta charset="utf-8" />
	<title>Seedbox de <?echo User::$username?></title>
	<link rel="icon" type="image/png" href="<?echo BASE;?>img/favicon.png" />

	<link rel="stylesheet" type="text/css" href="<?echo BASE;?>css/reset.css" />
	<link rel="stylesheet" type="text/css" href="<?echo BASE;?>css/style.css" />
	<link rel="stylesheet" type="text/css" href="<?echo BASE;?>css/fonts.css" />
	<link rel="stylesheet" type="text/css" href="<?echo BASE;?>css/jqueryUI/jquery-ui.css" />

	<? if($_GET['page'] == 'documentation') {?>
	<link rel="stylesheet" type="text/css" href="<?echo BASE;?>css/doc.css" />
	<?}?>

	<? if($_GET['page'] == 'ecouter') {?>
	<?}?>

	<? if($home) {?>
	<link rel="stylesheet" type="text/css" href="<?echo BASE;?>css/isotope.css" />
	<?}?>


	<script type="text/javascript" src="<?echo BASE;?>js/jquery.min.js"></script>
	<script type="text/javascript">
	$('.flush').remove();
	</script>
	<script type="text/javascript" src="<?echo BASE;?>js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?echo BASE;?>js/jquery.cookie.js"></script>

	<? if($_GET['page'] == 'ecouter') {?>
	<?}?>

	<?if($home){?>
		<script type="text/javascript" src="<?echo BASE;?>js/jquery.isotope.min.js"></script>
		<script type="text/javascript" src="<?echo BASE;?>js/jquery.quickfit.js"></script>
	<?}?>
</head>
<body>
<header>
	<div id="overlay"></div>
	<div class="container">
		<?php if($_GET['page'] != 'connexion') {?>

			<div id="user" class="pullRight">
				<?echo User::$username?>
				<a href="deconnexion" title="Se déconnecter" class="tip"><img src="<?echo BASE?>img/icons/login.png" class="pullRight" style="margin-left:5px"/></a>
				<a href="documentation" title="Aide" class="tip"><img src="<?echo BASE?>img/icons/help.png" class="pullRight" style="margin-left:5px"/></a>
				<a href="#" class="tip user-infos" title=""><img src="<?echo BASE?>img/icons/info.png" class="pullRight" style="margin-left:5px"/></a>
				<div class="hide" id="user-infos">
					<ul>
						<li>Nombre de torrents : <? echo $explorer->statistics->arguments->torrentCount;?></li>
						<li>Reçu : <? echo $explorer->byteConvert($explorer->statistics->arguments->cumulative_stats->downloadedBytes, null, true);?></li>
						<li>Envoyé : <? echo $explorer->byteConvert($explorer->statistics->arguments->cumulative_stats->uplodadedBytes, null, true);?></li>
				</div>
				<?if(defined('MUST_PAY') && MUST_PAY === true) {?>
					<a href="payer" title="Paiement - <? echo (User::$days_left > 1) ? User::$days_left . 'jours restants' : User::$days_left . 'jour restant';?>" class="tip">
						<?php
						if(User::$days_left < 7)
							$payIcon = 'pay-red.png';
						else if(User::$days_left > 7 && User::$days_left < 15)
							$payIcon = 'pay.png';
						else if (User::$days_left >= 15)
							$payIcon = 'pay-green.png';
						?>

						<img src="<?echo BASE?>img/icons/<?echo $payIcon;?>" class="pullRight" />
					</a>
				<?}?>
			</div>

			<nav id="menu" class="pullLeft clearfix">
				<a href="<? echo "http://";?><?echo SERVER_IP . BASE;?>" title="Bureau">
					<button>
						<span class="icon" id="bureau"></span>
						<p>Bureau</p>
					</button>
				</a>
				<a href="torrents" title="Accéder aux torrents">
					<button id="torrents">
						<span class="icon" id="torrents"></span>
						<p>Torrents</p>
					</button>
				</a>
			</nav>

			<div id="diskSpace" class="league-gothic">
				<div id="usedBar" style="width:<?echo $percentSpaceUsed;?>%"></div>
				<span class="text">Espace Disque</span>
				<span class="size">
					<span class="used"><?echo $explorer->spaceUsed?></span>
					<span class="left">/ <?echo round(DISK_SIZE / USER_COUNT)?> Go</span>
				</span>
			</div>
		<?php } else { ?>
			<h1 class="text-center"><img src="<?echo BASE?>img/translogo.png" /></h1>
		<?php } ?>
		<div class="clear clearfix"></div>
	</div>
</header>
<? if($home) {?>
	<nav id="display">
		<div id="displayOptions">
			<img src="img/liste.png" alt="Présentation par liste" title="Présentation par liste" data-filter=".list"/>
			<img src="img/miniatures.png" alt="Présentation par miniatures" title="Présentation par miniatures" data-filter=".miniature" />
		</div>
		<ul id="displayFilters" class="league-gothic">
			<li data-filter="*" data-display=".list">Tous</li>
			<li data-filter=".video" data-display=".list">Vidéos</li>
			<li data-filter=".audio" data-display=".list">Audio</li>
			<li data-filter=".other" data-display=".list">Autres</li>
		</ul>
	</nav>

	<div style="text-align:center;" id="loader">
		<img src="img/loading.gif" alt="Loading" />
	</div>

	<? if($_COOKIE['hasSeen'] != 1) {?>
	<div class="maintenance">
		<a href="#" class="close pullRight setCookie">fermer</a>
		<h3>23/02/2013</h3>
		<div>
			<p>
				Nous accueillons aujourd'hui un nouveau membre et je passe donc le serveur à 200 go par personnes, le prix est lui revu à la baisse pour un coût de 3,60€ par mois !
				Si vous voulez quand même plus de place, contactez-moi !
			</p>
		</div>
		<p class="small">Ces informations sont disponibles dans le changelog</p>
	</div>
	<? } ?>
<?}?>

<section>
	