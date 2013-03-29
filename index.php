<?php
include('inc/config.php');
//debug($torrents,'Torrents');

switch ($_GET['page']) {
	case 'deconnexion':
		include('logout.php');
		break;
	case 'connexion':
		include('login.php');
		break;
	case 'administration':
		include('admin.php');
		break;
	case 'ecouter':
		include('listen.php');
		break;
	case 'regarder':
		include('watch.php');
		break;
	case 'documentation':
		include('doc.php');
		break;
	case 'torrents':
		include('transmission.php');
		break;
	case 'payer':
		include('paiement.php');
		break;
	default:
		if($explorer->torrents) {
			$home = true;
			include('bureau.php');
		} else {
			header('Location: documentation');
		}
		break;
}
?>