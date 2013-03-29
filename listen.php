<?php
if(!defined(SB) && SB !== true)die();

if($_POST['submit'] == 'audio') {
    


    $i = $_POST['i'] ? (int) $_POST['i'] : 0;

    $path = $explorer->linkToPath($_POST['files'], $_POST['folders'], $_POST['cover'], $_POST['submit'], $i);
    
    $xmlFile = User::$temp_dir . 'playlist.xml';

	$doc = new DOMDocument('1.0');
	$doc->formatOutput = true;
	$doc->preserveWhiteSpace = false;

	$playlist = $doc->createElement('playlist');
	$doc->appendChild($playlist);

	$title = $doc->createElement('title', $path->folder);
	$playlist->appendChild($title);

	$cover = $doc->createElement('image', $path->cover);
	$playlist->appendChild($cover);

	$tracklist = $doc->createElement('trackList');
	$playlist->appendChild($tracklist);

	foreach ($path->file as $file) {
		if(file_exists($file)) {

			$track = $doc->createElement('track');
			$tracklist->appendChild($track);

			$location = $doc->createElement('location', BASE . $file);
			$track->appendChild($location);

			$title = $doc->createElement('title', basename($file));
			$track->appendChild($title);
		}
	}


	$doc->save($xmlFile);


    if($path->fileAmount == 0) {
        header("HTTP/1.0 404 Not Found");
        exit();
    }

} else {
    header("HTTP/1.0 400 Bad Request");
	exit();
}

include('header.php');
?>
<section>
	<div class="element list center" style="width:390px;margin:0 auto 20px auto">
		<div class="pullLeft">
			<div class="cover">
				<?php
					echo '<img src="'.$path->cover.'" class="cover">';
				?>
			</div>
		</div>
		<div class="inline-block">
			<object type="application/x-shockwave-flash" data="./swf/dewplayer-playlist.swf" width="240" height="200" id="dewplayer" name="dewplayer">
				<param name="wmode" value="transparent" />
				<param name="movie" value="./swf/dewplayer-playlist.swf" />
				<param name="flashvars" value="showtime=true&autoreplay=true&xml=<?php echo User::$temp_dir;?>playlist.xml" />
			</object>
		</div>
</section>
<?php
include('footer.php');
?>
	