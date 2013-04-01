<?php
class User {

	public static $username;
	public static $password; //That's crap

	public static $download_dir;
	public static $temp_dir;
	public static $upload_dir; //ToDo + additionnal directories ?
	
	public static $days_left;
	public static $rpc_port;
	public static $peer_port;


	public static $transmission_rpc_url;
	public static $transmission_web_url;

	public function __construct($user_infos) {

		if(!is_array($user_infos))
			$user_infos = unserialize(base64_decode($user_infos));

		$file_handle = @fopen(ROOT . "config/users", "r"); $i = 0; $error = 'Votre compte n\'existe pas';

		while (!feof($file_handle) && $i != -1) 
		{
			$line = fgets($file_handle);
			$line = explode(';',$line);

			//this is the user 
			//NB LINES
			if($line[0] == $user_infos['username']) {
				self::$username = $user_infos['username'];
				self::$password = $user_infos['password'];
				self::$peer_port = $line[1];
				self::$rpc_port = $line[2];
				self::$days_left = $line[3];
				$error = false;

				$i = -1;
			}
		}
		fclose($file_handle);

		

		if($error)
			throw new Exception($error, 501);

		//if(!session_id()) session_start();

		//Now we might set variables
		self::$download_dir =  './users/'.self::$username.'/downloads/';
		self::$temp_dir =   './tmp/'.self::$username.'/';

		self::$transmission_rpc_url = 'http://' . SERVER_IP . ':'.self::$rpc_port.'/transmission/rpc/';
		self::$transmission_web_url = 'http://'.self::$username . ':' . self::$password . '@' . SERVER_IP . ':'.self::$rpc_port.'/transmission/web/';

	}

	public static function setUserCookie() {
		$array = array('username' => self::$username,'password' => self::$password);

		return setcookie('u', base64_encode(serialize($array)), time()+60*60*24*30, BASE, null, null, true); //BASE, SERVER_IP,null, null ICI
	}

	public static function debug($objet, $titre = NULL)
	{
		$string = '<div id="debug"><div class="debug" style="display:none;">';

		$string .= (is_null($titre)) ? '' : $titre . ' : ';
		$string .= '<br /><pre>';
		
			ob_start();
			var_dump($objet);

		$debug = ob_get_clean();

		$string .= $debug;

		$string .= '<br /></pre>';

		$string .= '</div><button class="debug">Debug</button></div>';
		
		if(DEBUG === true) {
			echo $string;
		} else {
			return $string;
		}
	}

}