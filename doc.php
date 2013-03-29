<?php
include('header.php');
?>
<div class="element list">
	<article>
	<h1 class="big">Documentation</h1>
	<ul>
		<li><a href="#introduction">Introduction</a></li>
		<li><a href="#site">Utilisation du site</a></li>
		<li><a href="#logiciels">Logiciels</a></li>
			<ul>
				<li><a href="#ftp">Client FTP pour télécharger</a></li>
				<li><a href="#transmission">Transmission Remote pour les torrents</a></li>
			</ul>
		<li><a href="#contact">Contact</a></li>
	</ul>
	<h2 id="introduction">Introduction</h2>

	<h3>Principe d'une seedbox</h3>
	<p>La seedbox vous permet de télécharger les torrents depuis un serveur puis d'utiliser les fichiers comme bon vous semble (streamin, téléchargement), et tout ça au max de votre connexion !</p>
	<p>Un petit schéma d'explication :</p>
	<div class="center">
		<img src="img/doc/seed1.png" />
	</div>
	<p>En bref, à la place de récupérer fichiers directement depuis chez toi, ils arrivent sur le serveur.</p>
	<h3>L'intérêt ?</h3>
	<p>Il n'y a aucune relation directe entre toi et le torrent (P2P), le serveur s'occupe de tout !<br />
		Tu es donc protégé de la Hadopi, et vu que le serveur est en très haut débit, tu n'as presque plus besoin de t'occuper des <a href="http://fr.wikipedia.org/wiki/BitTorrent_(protocole)#Vocabulaire">ratios</a> (surtout nécéssaire sur les sites privés).
	</p>

	<h2 id="site">Utilisation du site</h2> 
	<p>Le site est séparé en 2 parties distinctes :
	</p>

	<h3>Le bureau</h3>
	<p>Le bureau vous permet de naviguer dans vos fichiers plus simplement et d'écouter ou de regarder un torrent téléchargé</p>
	<blockquote>La liste de ces fichiers correspond en fait à vos torrents, lors de la supression d'un torrent depuis les torrents, veillez à supprimer aussi le fichier lié (Torrent & Data)</blockquote>
	<p>Lorsqu'un dossier est détecté, vous pouvez afficher celui-ci et voir ou écouter un de ces fichiers. Un dossier peut être téléchargé depuis <b>Le bureau</b> lorsqu'il n'est pas trop gros. Pour plus de liberté sur les fichiers voir les <a href="#logiciels">logiciels</a>.</p>

	<p>Afin de pouvoir visionner une vidéo il faut le <a href="//www.divx.com/downloads/divx/1">plugin DivX</a> !</p>

	<h3>Les torrents</h3>
	<p>L'interface web des torrents permet d'ajouter, d'enlever des torrents. Pour plus d'options sur les torrents voir les <a href="#logiciels">logiciels</a>.</p>

	<h2 id="logiciels">Logiciels</h2>
	<h3 id="ftp">Client FTP</h3>
	<p>Pour accéder à tes fichiers il faut utiliser ce qu'on appelle un client FTP qui se connecte au serveur.<br />
		Voici une liste de liens vers ces logiciels :</p>
	<ul>
		<li><a href="http://cyberduck.ch/">Cyberduck (Mac)</a></li>
		<li><a href="http://filezilla.fr/">FileZilla (Win/Mac/PC)</a></li>
	</ul>
	<p>Pour les utiliser, créer une nouvelle connexion et entrez les paramètres suivants :</p>
	<ul>
		<li>FTP</li>
		<li>Serveur : <?echo SERVER_IP?></li>
		<li>Utilisateur : <?echo User::$username?></li>
		<li>Mot de passe : <?echo User::$password?></li>
	</ul>

	<h3 id="transmission">Transmission Remote</h3>
	<p>Ce logiciel est comme la version web des torrents mais propose une interface plus agréable a utiliser selon moi, surtout si vous uploadez !<br />
	<a href="http://code.google.com/p/transmisson-remote-gui/">Téléchargez Transmission Remote GUI</a> (à gauche dans Downloads, pour toutes les plateformes)</p>

	<p>Les informations pour l'utiliser (clique en haut à gauche sur la petite flèche puis nouvelle connexion) :</p>
	<ul>
		<li>Serveur : <?echo SERVER_IP?></li>
		<li>Utilisateur : <?echo User::$username?></li>
		<li>Mot de passe : <?echo User::$password?></li>
		<li>Port : <?echo User::$rpc_port?></li>
	</ul>

	<br />
	<blockquote>Si vous avez des problèmes de connexion contactez-moi !</blockquote>

	<p><a href="torrents">Voilà ! Commence maintenant en ajoutant un torrent !</a></p>

	<h2 id="contact">Contact</h2>
	<p>Pour me contacter : soyuka@gmail.com ou par tél ou par skype (soyuka)</p>
	<!--
	Skype 'Skype Me™!' button
	http://www.skype.com/go/skypebuttons
	-->
	<script type="text/javascript" src="http://download.skype.com/share/skypebuttons/js/skypeCheck.js"></script>
	<a href="skype:soyuka?call"><img src="http://download.skype.com/share/skypebuttons/buttons/call_green_white_153x63.png" style="border: none;" width="153" height="63" alt="Skype Me™!" /></a>
	</article>
</div>
<?php include('footer.php');?>
