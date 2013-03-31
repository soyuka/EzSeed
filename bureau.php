<?php
include('header.php');
?>
<?php
foreach ($explorer->torrents as $torrent) {
	//$type = $torrent->folder->types;

	if(is_array($torrent->files))
		$type = $torrent->folder->types; //($torrent->files[0]->type->type != $torrent->folder->types) ? $torrent->files->type->type : 
	else
		$type = ($torrent->files->type->type != $torrent->folder->types) ? $torrent->files->type->type : $torrent->folder->types;

	if(empty($type))
		$type = 'other';


?>
<!-- Miniatures -->
<div class="element miniature <?echo $type?>">
	<div class="titre"><h1 class="league-gothic tip" title="<? echo $torrent->name?>"><? echo $torrent->name?></h1></div>
	<?echo $explorer->htmlCover($torrent, $link = true);?>
</div>

<!-- LIST ELEMENTS -->
<div class="element list <?echo $type?>" data-id="<?php echo $torrent->id;?>">

	<?php
	if($torrent->id == 28) {
		debug($torrent->folder);
		if(is_array($torrent->files))
			debug($torrent->files);
	}
	
	if($type == 'erreur') {
		echo '<p>'.$torrent->name.' ce fichier n\'existe plus, supprimez le torrent.</p>';
	} else {
	?>
		<div class="pullLeft">
			<?echo $explorer->htmlCover($torrent);?>
		</div>
		<div class="pullLeft">
			<h1 class="league-gothic"><? echo $torrent->name;?></h1>
			<hr />
			<p class="info tip" title="Ratio"><img src="img/icons/ratio.png" />&nbsp;<?echo isset($torrent->uploadRatio) ? round($torrent->uploadRatio,2) : 0?></p>
			<p class="info tip" title="Provenance"><img src="img/icons/provenance.png" />&nbsp;<?echo isset($torrent->trackerStats[0]) ? $torrent->trackerStats[0]->host : 'Inconnu'?></p>
			<p class="info tip" title="Peers">
				
				<?
				switch ($torrent->status) {
					case '4':
						echo '<img src="img/icons/leecher.png" /> ';
						echo isset($torrent->peersSendingToUs) ? $torrent->peersSendingToUs : 0;
						break;
					case '8':
						echo '<img src="img/icons/seeder.png" /> ';
						echo (isset($torrent->peersGettingFromUs)) ? $torrent->peersGettingFromUs : 0;
						break;
					case '6':
						echo '<img src="img/icons/seeder.png" />';
						echo (isset($torrent->peersGettingFromUs)) ? $torrent->peersGettingFromUs : 0;
						break;
					default :
						echo '<img src="img/icons/leecher.png" /> ';
						echo isset($torrent->peersConnected) ? $torrent->peersConnected : $torrent->peersKnown;
				}

				?></p>
			
			<p class="info tip" title="Statut"> <?echo $explorer->getStatusString($torrent->status);?></p>
			<div class="clear"></div>

			<?if(!in_array($torrent->status, $explorer->statusStoppedArray) && $torrent->status != 16) {?>

			<div class="clear"></div>
			<div class="button-grp">
				<?php
				echo $explorer->htmlStreamingLink(
							$torrent->folder, $torrent->files, $button = true);
				?>

				<button value="delete" class="btn ">
					<a href="delete.php?id=<?echo $torrent->id?>&cover=<? echo ($torrent->folder->image) ? basename($torrent->folder->image) : 0;?>" onclick="return(confirm('Attention supprimer le torrent supprimera les fichiers liés ! Veux-tu continuer ?'));"><i class="icon supprimer"></i><i class="separateur"></i> Supprimer</a>
				</button>
			<!-- TODO -->
			<!-- <a class="button league-gothic">
					<i class="icon partager"></i>
					<i class="separateur"></i>
					Partager
				</a> -->
			</div>
			<?} else {
			?>
				

			<?
			}?>

			<?if($torrent->folder->path) {?>
			<div class="showFiles">
				Fichiers du dossier
			</div>
			<div class="files">
				<table>
					<thead>
						<tr><th>#</th><th>Nom</th><th>Poids</th><th>Options</th></tr>
					</thead>
					<tbody>
						<?
						$i = 0;$tailleDossier = 0;
						//var_dump($torrent->files);
						//debug($torrent->folder->path);				

						foreach ($torrent->files as $key => $file) {
							$fullPath = explode('/', $file->name);
							$name = '';

							$nbPath = count($fullPath);

							if($nbPath >= 3) {
								foreach ($fullPath as $key => $path) {
										if($key != 0) {
											if($key != $nbPath)
											$name .= $path;

										if($key != $nbPath - 1)
											$name .= '/';
									}

								}
							} else {
								$name = $fullPath[1];
							}

							

								$tailleFichier = $explorer->byteConvert($file->length);
								$tailleDossier += $file->length; 

								echo '<tr>';
								echo '<td>'.$i.'</td>';
								echo '<td>'.$name.'</td>';
								echo '<td>'.$tailleFichier.'</td>';
								echo '<td>';
								echo $explorer->htmlStreamingLink($torrent->folder,$file, $button = false);
								echo '</td>';
								echo '</tr>';
								$i++;
							
						}
						?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3">Taille totale : <?echo $explorer->byteConvert($tailleDossier);?></td>
							<td>
							<?
							if($tailleDossier/1024/1024 < MAX_AUDIO_FOLDER_SIZE) {
								echo $explorer->htmlZipLink($torrent->folder,$torrent->files);
							} else {
								echo '<a href="documentation#ftp" title="Ce dossier est trop gros pour être zippé, utilise un client ftp !" class="tip pullRight">';
								echo '<img src="img/icons/help.png"/>';
								echo '</a>';
							}
							?>
						</td>
						</tr>
					</tfoot>
				</table>
			</div>
			<?}?>
		</div>
	<?php } ?>
</div>

<?
}//fin foreach
include('footer.php');
?>