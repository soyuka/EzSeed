<?php
class Explorer extends TransmissionRPC {

	public $spaceUsed;

	public $percentSpaceUsed;

	public $statistics;

	//Transmission RPC stopped status array
	public $statusStoppedArray = array(0,1,2,3,4);

	//When we can't read finfo, just check extension
	protected $videosExtArray = array(
		'avi',
		'wmv',
		'mkv',
		'mka',
		'mks',
		'mpeg',
		'mpg',
		'flv',
		'mp4',
		'mov'
	);

	protected $audioExtArray = array(	
		'mp3',
		'wav',
		'flac',
		'ogg',
		'aiff',
		'wma',
		'aac'
	);

	public $torrents;

	public function __construct() 
	{

		//Block try/catch
		try {

			$rpc = new parent($url = User::$transmission_rpc_url,$username = User::$username, $password = User::$password);

			if(!$_COOKIE['u']) {
				User::setUserCookie();
				header('Location:./');
				exit();
			}
		} catch (TransmissionRPCException $e) {

			if($e->getCode() == -4) {
				$_SESSION['error'] = 'Mauvais mot de passe';
				header('Location:connexion');
				exit();
			} else 
				die($e->getMessage()); //to be improved
		}


		$this->torrents = $rpc->get($ids = array(), $fields = array(
			"id", "name", "status", "totalSize", "peersConnected","peersKnown", "peersSendingToUs", "peersGettingFromUs", 
			"percentDone", "uploadRatio", "isFinished", "leftUntilDone", "trackerStats", "downloadDir", "files") 
		);

		//New user ? No torrents added 
		if(!isset($this->torrents->arguments->torrents))
			$this->torrents = false;
		else
			$this->torrents = $this->torrents->arguments->torrents;
		

		if($this->torrents) {
			foreach ($this->torrents as $torrent) 
			{
				//get some fuckin' filetypes
				$torrent = $this->getTorrentFileTypes($torrent);
				//get cover
				$torrent = $this->getCoverLink($torrent);
  			}
		}
		
		//user stats
		$this->statistics = $rpc->sstats();


  		//space used
  		$this->spaceUsed = $this->spaceUsed(false);

  		//If > 95% block transmission
		$this->percentSpaceUsed = (intval($this->spaceUsed) * 100) / round(DISK_SIZE / USER_COUNT);
  	}

  	/**
	* Overrides rpc remove fonction
	* Removes zip if founded one
  	**/
  	public function remove($id, $delete_local_data) {
  		$rpc = new parent($url = User::$transmission_rpc_url,$username = User::$username, $password = User::$password);
  		//add tmp remove
  		$torrent = $rpc->get ( $id, array("downloadDir", "files") );

  		$pathinfo = pathinfo($torrent->arguments->torrents[0]->files[0]->name);

  		$zip = User::$temp_dir . DS . $pathinfo['dirname'] . '.zip';

  		if(is_file($zip)) {
  			unlink($zip);
  		}

  		return $rpc->remove($id, $delete_local_data);
  	}

  	/**
  	* Reorder - orders an array by names (natcasesort + keep keys unchanged)
  	* had an issue with array_values
  	* @param array
  	* @return array
  	*/
  	private function reOrder($array) 
  	{

  		if(is_array($array)) {
	  		natcasesort($array);

			//restore keys
			$a = array();
			foreach ($array as $key => $value) {
			    array_push($a, $value);
			    unset($array[$key]); //unset the var
			}

			$array = $a;
  		}

  		return $array;
  	}

  	/**
  	* getCoverLink - finds cover in a folder
  	* if file is a video ffmpeg gets a video frame
  	* if downloading it returns default cover
  	* @param singular torrent (from $this->constructor)
  	* @return torrent
  	*/
  	private function getCoverLink($torrent) {
  		//Cover
		$torrent->folder->image = false;

		//if downloading
		if($torrent->status == 4) {

			$ext = substr(strrchr($torrent->files[0]->name,'.'),1);
			if(in_array($ext, $this->videosExtArray)) {
				$torrent->folder->image = 'img/cover/video.png';
			} else if(in_array($ext, $this->audioExtArray)) {
				$torrent->folder->image = 'img/cover/audio.png';
			} else {
				$torrent->folder->image = 'img/cover/other.png';
			}

		}//Now if not stopped
		else if($torrent->status != 16) 
		{

			//If audio try to find some cover
			if($torrent->folder->types == 'audio') 
			{
				$torrent->folder->image = $this->findFolderImage($torrent->files);

				$file_path = User::$temp_dir . basename($torrent->folder->path).'.zip'; // name of zip file
				
				$archive_folder = User::$download_dir . basename($torrent->folder->path); // the folder which you archivate
				
				$to_zip = str_replace(' ', '\ ', basename($torrent->folder->path));

				if(!is_file($file_path)) {

					if(defined('ZIP_AUDIO_FOLDERS') && ZIP_AUDIO_FOLDERS == 1) {

						$folderSize = floatval(str_replace(',', '.', $this->spaceUsed(false, str_replace(' ', '\ ', $archive_folder)))) * 1024; //in MB
						
						if($folderSize < MAX_AUDIO_FOLDER_SIZE) {

							loader::s("Création d'un zip pour ".basename($torrent->folder->path));
							
							include_once('myZipArchive.class.php');

							$zip = new MyZipArchive;

							// To Do, could possibly run in background
							// $cmd = "screen -dmS bg cd " . ROOT . substr(User::$download_dir,2) . " && zip -R ../../../tmp/".User::$username."/". $to_zip . " ". $to_zip . "/* > /dev/null"; //&& mv ".$to_zip.".zip ". ROOT . substr(User::$temp_dir,2) . $to_zip.".zip
							// exec($cmd, $o, $r);
							// var_dump($r);
							// var_dump($cmd);
							// die();
							
							if ($zip -> open($file_path, MyZipArchive::CREATE) === TRUE) 
							{ 
							    $zip -> addDir($archive_folder, basename($torrent->folder->path));
							    
							    $zip -> close();
							} 
							else 
							{ 
							    header("HTTP/1.0 500 Internal Server Error");
							}

							loader::e();
						}
					}
				}

			}

			//If video try to get frame
			if($torrent->folder->types == 'video') 
			{	
				//Checks tmp already exists cause it could be long creating a new one...
				if(!is_file( User::$temp_dir.basename($torrent->files[$torrent->folder->videoKey]->name).'.jpg') ) {

					loader::s("Récupération de la cover de ".basename($torrent->files[$torrent->folder->videoKey]->name));

					$mov = new ffmpeg_movie(User::$download_dir.$torrent->files[$torrent->folder->videoKey]->name);
					$frame = $mov->getFrame(100);
					$img = $frame->toGDImage();
					imagejpeg($img, User::$temp_dir.basename($torrent->files[$torrent->folder->videoKey]->name).'.jpg');
					imagedestroy($img);

					$torrent->folder->image = User::$temp_dir.basename($torrent->files[$torrent->folder->videoKey]->name).'.jpg';
					
					loader::e();

				} else {
					$torrent->folder->image = User::$temp_dir.basename($torrent->files[$torrent->folder->videoKey]->name).'.jpg';

				}
			}

			//default image
			$torrent->folder->image = ($torrent->folder->image) ? $torrent->folder->image : 'img/cover/'.$torrent->folder->types.'.png';
		
		} else {
			$torrent->folder->image = 'img/cover/stopped.png';
		}

		return $torrent;
  	}

  	/**
	* getTorrentFileTypes - get Torrent file/folder types
  	* @param singular torrent (from $this->constructor)
	* @return $torrent
  	*/
  	private function getTorrentFileTypes($torrent) {
  		
			//If not waiting for some shit
			$torrent->folder->path = false;

			if(!in_array($torrent->status, $this->statusStoppedArray)) 
			{

				$nbFiles = count($torrent->files);

				//Folder
				if($nbFiles > 1) 
				{
					$dir = explode('/',$torrent->files[0]->name);
					$torrent->folder->path = $dir[0];
				}

				//Defines Types
				$types = array();
				
				foreach ($torrent->files as $key=>$file) 
				{
					//Check if paused
					if($torrent->status == 16) {
						if(file_exists(User::$download_dir . $file->name))
						{
							//File exists + status = stopped => WTF ? NB Why here ? 
							$torrent->status = 7; 
							$types[$key] = $this->getFileType($file->name);
							$torrent->files[$key]->type = $types[$key];
							//save video $key for cover
							if($types[$key]->type == 'video')
								$torrent->folder->videoKey = (!$torrent->folder->videoKey) ? $key : $torrent->folder->videoKey;
						} else {
							//File don't exists anymore...
							$types[$key]->type = 'erreur';
							$torrent->files[$key]->type = $types[$key];
						}
					} else {
						if (file_exists(User::$download_dir . $file->name)) {
							$types[$key] = $this->getFileType($file->name);
							$torrent->files[$key]->type = $types[$key];
							//save video $key for cover
							if($types[$key]->type == 'video')
									$torrent->folder->videoKey = (!$torrent->folder->videoKey) ? $key : $torrent->folder->videoKey;
						} else {
							//File don't exists anymore...
							$types[$key]->type = 'erreur';
							$torrent->files[$key]->type = $types[$key];
						}
					}

				}
			
				//Define folder type
				$torrent->folder->types = $this->getFolderType($types);

			}

			return $torrent;
  	}

  	/**
	* getFolderType - parse all types to find the general folder type
	* usefull for filters (audio, video etc.)
	* @param $types - generated in getTorrentFileTypes
	* @return $folderType 
  	*/
  	private function getFolderType($types) {
		$nbFiles = count($types);
		
		$folderType = 'other';
		$audio = 0;

		$i = 0;
		if($nbFiles == 1) {
			if($types[0]->type == 'audio')
				$folderType = 'audio';
			if($types[0]->type == 'video')
				$folderType = 'video';
			if($types[0]->type == 'erreur')
				$folderType = 'erreur';
		} else {
			while($i <= $nbFiles && $i != -1)
			{

				switch ($types[$i]->type)
				{
					case 'audio':
						$folderType = 'audio';
						$i = -1;
						break;
					case 'video':
						$folderType = 'video';
						$i = -1;
						break;
					case 'erreur':
						$folderType = 'erreur';
						$i = -1;
						break;
					default:
						$i++;
						break;
				}
			}
		}

		//debug($folderType);

		return $folderType;
	}

	/**
	* getFileType - finfo function (public cause MIME is used on the video player)
	* @param $fileName
	* @return string $type->MIME AND $type->type
	*/
	public function getFileType($fileName) {
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		
		if(is_file(User::$download_dir .$fileName)) {
			$type->MIME = finfo_file($finfo, User::$download_dir .$fileName);

			//Correcting mkv type
			if($type->MIME == 'application/octet-stream' && substr($fileName,-3) == 'mkv')
				$type->MIME = 'video/x-matroska';
			if($type->MIME == 'application/octet-stream' && substr($fileName,-3) == 'mp3')
				$type->MIME = 'audio/mpeg';

			$typeTmp = explode('/', $type->MIME);
			$type->type = $typeTmp[0];

		} else {
			$type->type = 'erreur';
			$type->MIME = 'application/octet-stream';
		}

		finfo_close($finfo);


		return $type;
	}

	/**
	* findFolderImage - gets image from a folder
	* @param array $files - torrent files
	* @return $torrent->folder->image
	*/
	private function findFolderImage(array $files) {
		$torrent->folder->image = false;
		foreach ($files as  $file) {
			if($file->type->type == 'image') {
				$torrent->folder->image = User::$download_dir . $file->name;
			}
		}
		return $torrent->folder->image;
	}

	/**
	* spaceUsed - gets space used by User::$download_dir DIR
	* should be working on unix only ?
	* @return space used in Gb
	*/
	public function spaceUsed($go = false, $directory = false) 
	{
		if(!$directory)
			$directory = User::$download_dir;

		return $this->byteConvert(
			trim(
				str_replace($directory, '', 
					exec('du -sk ' . $directory)
				)
			) * 1024, 4, $go
		);
	}

	/**
	* byteConcert - convert size into readable string
	* @param $bytes
	* @param $exp_max
	* @return $formated size
	*/
	public function byteConvert($bytes, $exp_max=null, $go = true) 
	{
	    $symbol = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
	 
	    $exp = 0;
	    if( $exp_max === null )
	      $exp_max = count($symbol)-1;
	    $converted_value = 0;
	 
	    if( $bytes > 0 )
	    {
	      $exp = floor( log($bytes)/log(1024) );
	      if( $exp > $exp_max )
	        $exp = $exp_max;
	      $converted_value = ( $bytes/pow(1024,$exp) );
	    }

	    //Added some things to hide symbol
	 	if($exp < 3)
		    return ($go === false) ? number_format($converted_value/1024,2,',','.') : number_format($converted_value,2,',','.').' '.$symbol[$exp];
  		else
  		    return ($go === false) ? number_format($converted_value,2,',','.') : number_format($converted_value,2,',','.').' '.$symbol[$exp];

  	}

  	/**
  	* pathToLink - Transforms the file path into serialized arrays
  	* arrays are base64 encoded and will be used in POST forms
  	* @param $folder - folder
  	*/
  	private function pathToLink($folder, $file, $zip = false) 
  	{
	  	$folders = array();
	  	$files = array();
		$path = new stdClass();

		if($zip) { 
			$folders[0] = User::$temp_dir;
			$files[0] = basename($zip);
		} else {

			if(is_array($file)) {
				foreach ($file as $i => $value) {
					$files[$i] = array();
					$folders[$i] = array();

					$fileName = $value->name; //file name / path

					$pathes = explode('/', $fileName);
					$nbPath = count($pathes);


					foreach ($pathes as $key => $value) {
						//Si pas fichier
						if($key != $nbPath - 1) {
							$folders[$i][] = basename($value);
						} else {
							$files[$i] = basename($value);
						}
					}
				}
			} else {
				$fileName = $file->name;
				$pathes = explode('/', $fileName);
				$nbPath = count($pathes);


				foreach ($pathes as $key => $value) {
					//Si pas fichier
					if($key != $nbPath - 1) {
						$folders[0][] = basename($value);
					} else {
						$files[] = basename($value);
					}
				}


			}
		}

		$path->files = base64_encode(serialize($files));
		$path->folders = base64_encode(serialize($folders));

  		if($folder->image)
  			$path->cover = $folder->image;
  		else
  			$path->cover = './img/cover/'.$folder->types.'.png';

  		$path->cover = base64_encode($path->cover);

  		return $path;
  	}

  	public function linkTopath($files, $folders, $cover, $type, $i = 0, $string = false, $class = false) 
  	{
  		$zip = false;

  		//Re-generating form
  		$form = $this->formActionByType($type, false);
  		$pathInfo->files = $files;
  		$pathInfo->folders = $folders;
  		$pathInfo->cover = $cover;
  		
  		$form->content = ($string) ? $string : $form->content;
  		$form->class .= ($class) ? ' '.$class : '';

  		$path->html = $this->htmlPathInput($pathInfo, $form, $i);

  		$files = unserialize(base64_decode($files));
  		$folders = unserialize(base64_decode($folders));
  		
  		$path->cover = base64_decode($cover);

  		$path->fileAmount = count($files);
  		
  		if(substr($files[0], -3) == 'zip')
  			$zip = true;
  		//debug($folders);

  		//Generates pathes
  		foreach ($folders as $key => $names) {
  			$path->file[$key] = $zip ? User::$temp_dir : User::$download_dir;

  			if(is_array($names)) {
	  			foreach ($names as $folder) {
	  				if($folder != '..' && $folder !=  '.' && substr($folder, 0,1) != '.') 
	  				{
		  				$path->file[$key] .= basename($folder) . '/';
		  				//Save name for zip
	  					if(!$path->folder)
	  						$path->folder = basename($folder);
	  				}
	  			}
	  		} 
	  		/*else {
	  			$path->file[$key] .= basename($names) . '/';
	  		}*/
	  		//add some security there !
	  		if($files[$key] != '..' && $files[$key] != '.' && substr($folder, 0,1) != '.')
  				$path->file[$key] .= basename($files[$key]);
  		}


  		//sort Array nat case + restore keys
  		$path->file = $this->reOrder($path->file);

  		return $path;
  	}

  	/**
	* Generates html form by path
  	*/
  	private function htmlPathInput($path, $form, $i = false, $tooltip = false)
  	{
  		$html = '';

  		if($form->action) {
	  		$html .= '<form method="POST" action="'.$form->action.'">';
	  		$html .= '<input type="hidden" name="files" value="'.$path->files.'" />';
	  		$html .= '<input type="hidden" name="folders" value="'.$path->folders.'" />';
	  		$html .= '<input type="hidden" name="cover" value="'.$path->cover.'" />';
	  		$html .= ($i) ? '<input type="hidden" name="i" value="'.$i.'" />' : '';
	  		$html .= '<button type="submit" name="submit" value="'.$form->type.'"';
	  		$html .= ($tooltip) ? 'title="'.$tooltip.'" ' : '';
	  		$html .= 'class="'.$form->class.'"';
	  		$html .= '>';
			$html .= $form->content;
			$html .= '</button>';
	  		$html .= '</form>';
	  	} else {
	  		$html .= '<a href="'.$path->directPath.'" class="'.$form->class.'">'.$form->content.'</a>';
	  	}

  		return $html;
  	}
	
	/**
  	* Renvoie l'action du formulaire en fonction du type
  	* @return string | false (bool)
  	*/
  	private function formActionByType($type, $button, $content = false) 
  	{
  		$form->type = $type;

  		if($button){
  			$class .= 'icon ';$form->string = ' ';$form->class = 'btn ';
  		} else {
  			$class = '';$form->class = 'none ';$form->string = ''; 
  		}

  		switch ($form->type) {
			case 'audio':
				$form->action = 'ecouter';
				$form->string .= 'Ecouter';
				$class .= 'ecouter';
				break;
			case 'video':
				$form->action = 'regarder';
				$form->string.= 'Visionner';
				$class.='regarder';
				break;
			//Usually 1 file
			case 'image':
				$form->action =  false;
				$form->string.= 'Voir';
				$class .='regarder';
				break;
			case 'text':
				$form->action = false;
				$form->string.= 'Lire';
				$class .='regarder';
				break;
			default:
				$form->action = 'download.php';
				$form->string = 'Télécharger';
				$class .= 'telecharger';
				break;
		}

		if($content) {
			$form->content = $content;
			$form->class .= 'miniature ';
		} else {
			if($button) {
				$form->content = '<i class="'.$class.'"></i>';
				$form->content .= '<i class="separateur"></i>';
				$form->content .= $form->string;
			} else {
				$form->content = $form->string;
			}
		}

		return $form;
  	}

  	public function htmlStreamingLink($folder, $files, $button = false, $miniature = false) 
  	{

  		$class='';
  		$html = '';
  		$string = '';

  		if($folder->path && $folder->types == 'other') {
  			return false;
  		} else {
	  		$filesTmp = array();

	  		//If folder, find files corresponding to folder types 
	  		if(is_array($files)) {
	  			$type = $folder->types;

	  			//Put files with the same type in the array, don't care about others
	  			foreach ($files as $key => $value) {
	  				if($value->type->type == $type) {
	  					$filesTmp[] = $value;
	  				}
	  			}

	  		} else {
	  			$type = ($files->type->type != $folder->types) ? $files->type->type : $folder->types;
	  			$filesTmp = $files;
	  		}

	  		//check for the zip
	  		$zip = is_file(User::$temp_dir . $folder->path . '.zip') ? User::$temp_dir . $folder->path . '.zip' : false;

	  		$pathInfo = $this->pathToLink($folder, $files, false);

	  		//debug($type);

	  		$pathInfo->directPath = User::$download_dir . $folder->path .'/'. basename($files->name);

	  		//deifne form action + string + class
	  		//miniature for the button content
	  		$form =  $this->formActionByType($type, $button, $miniature);

	  		$html =  $this->htmlPathInput($pathInfo, $form, false, $tooltip = $form->string);

	  		
	  		//generates download link
			if($miniature === false && ( is_file($zip) || !$folder->path || (count($files) == 1 && $form->action != false && $form->action != 'download.php' ) ) )  {
				$form =  $this->formActionByType(null, $button);

				$html .=  $this->htmlPathInput($this->pathToLink($folder, $files, $zip), $form); 	
			}

			return $html;
		}
  	}
  	
  	public function htmlZipLink($folder,$files) {
  		$pathInfo = $this->pathToLink($folder, $files);

  		$form->class = 'none pullRight tip-warn';
  		$form->action =  'zip.php';
  		$form->content = 'Télécharger le dossier';
  		$form->type = 'zip';

  		if(!is_file(User::$temp_dir . $folder->path . '.zip')) {
	  		$html =  $this->htmlPathInput(
	  			$pathInfo, $form, false, 
	  			"Attention la création d'un zip peut être longue, nous vous conseillons de télécharger les dossiers depuis un client FTP (voir l'aide) !");
  		} else {
  			$html =  $this->htmlPathInput(
	  			$pathInfo, $form, false, false);
  		}
  		return $html;

  	}

  	public function htmlCover($torrent, $link = false) {

  		$miniature = '';

  		$miniature .= '<div class="cover">';
  		$miniature .= '<img src="';
  		$miniature .= ($torrent->folder->image) ? $torrent->folder->image : './img/cover/stopped.png';
  		$miniature .= '" />';
		
		if(isset($torrent->percentDone) && $torrent->percentDone != 1) {
			$percent = round($torrent->percentDone*100);
			$miniature .= '<div class="downloading" style="height:'.$percent.'%"><span>'.$percent.'%</span></div>';
		}
		
		$miniature .= '</div>';

		return ($link && (!in_array($torrent->status, $this->statusStoppedArray) && $torrent->status != 16) ) ? $this->htmlStreamingLink($torrent->folder, $torrent->files, $button = false, $miniature) : $miniature;
  	}
}

?>
