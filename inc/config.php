<?php
// hide notices
error_reporting(E_ALL & ~ E_NOTICE);
set_time_limit(0);

date_default_timezone_set("Europe/Paris");
 
$path = '/tmp';
exec('ffmpeg -version', $path, $returncode);
if ($returncode == 127)
	die('FFMpeg n\'est pas disponible');

if(!session_id()) session_start();

/** CONFIG **/

define('SERVER_IP', '255.255.255.255');
define('ROOT', '/home/seedbox/');
define('BASE', '/');
define('DISK_SIZE', 1000); //disk size for the seedbox gb
define('USER_COUNT', 5); //Users count

/** Not public availabe **/
define('MUST_PAY', false); // Users have to pay
// define('SERVER_PRICE', 18);
// define('PAYPAL_EMAIL', 'email');

define('DEBUG', false);

define('ZIP_AUDIO_FOLDERS', 1); //zip audio folders automatically
define('MAX_AUDIO_FOLDER_SIZE', 700); //in MB - if it's bigger we won't zip it

define('ADMIN', 'ezseed'); //set the admin username

/** DO NOT MODIFY AFTER THIS LINE **/

define('SB', true);
define('DS', DIRECTORY_SEPARATOR);

define('DEBUG', false); //for live tests

require_once('class/user.class.php');
require_once('class/transmission.class.php');
require_once('class/explorer.class.php');

// Temporaly
class loader {
	
	static public $time_start;
	static public $time_end;

	static public function m($message) {
		echo '<p class="flush">';
		echo $message;
		echo '</p>';
		ob_flush();
		flush();
	}

	static public function s($message) {
		self::$time_start = microtime(true);
		echo '<p class="flush">';
		echo $message;
		ob_flush();
		flush();
	}

	static public function e() {
		self::$time_end = microtime(true);
		$time = self::$time_end - self::$time_start;
		echo ' - ' . number_format($time,3) . 's'. PHP_EOL;
		echo '</p>';
		ob_flush();
		flush();
	}
}

if(isset($_GET['mc_gross'])) {
	header('Location:./');
	exit();
}

//User check
$user = false;

if($_POST['username'] && $_POST['password']) 
{
	try {
		$user = new User($_POST);
	} catch (Exception $e) {
		$_SESSION['error'] = $e->getMessage();
	}

} else if($_COOKIE['u']) {
	
	try {
		$user = new User($_COOKIE['u']);
	} catch (Exception $e) {
		echo $e->getMessage();
	}


} else if($_GET['page'] != 'connexion') {
	header('Location:connexion');
	exit();
}

if($user && MUST_PAY === true && User::$days_left <= 1 && $_GET['page'] != 'payer') {
	header('Location:payer');
	exit();
}

if($user)
	$explorer = new Explorer();

//If > 95% block transmission
$percentSpaceUsed = (intval($explorer->spaceUsed) * 100) / round(DISK_SIZE / USER_COUNT);

