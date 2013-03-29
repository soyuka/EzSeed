<?php
include('header.php');
if(defined('ADMIN') && User::$username == ADMIN) {
?>
	<div class="element list">
		<table>
			<tr><th>#</th><th>Username</th><th>Port Peer<th>Port RPC</th><th>Jours restants</th><th>Place utilisée</th></tr>

		<?php
			$file_handle = @fopen(ROOT . "config/users", "r");

			$i = 1;

			while (!feof($file_handle)) 
			{
				$line = fgets($file_handle);
				$line = explode(';',$line);
				if(sizeof($line) > 1) {
					echo '<tr>';
					echo '<td>'.$i.'</td>';
					echo '<td>'.$line[0].'</td>';
					echo '<td>'.$line[1].'</td>';
					echo '<td>'.$line[2].'</td>';
					echo '<td>'.$line[3].'</td>';
					echo '<td>'.$explorer->spaceUsed(true, ROOT . 'users/' . $line[0] . DS . 'downloads').'</td>';
					echo '</tr>';
				}
				$i++;
			}
			fclose($file_handle);
		?>
		</table>

	</div>
<?php
} else {
	echo "Vous n'êtes pas autorisé à accéder à cette section";
}
include('footer.php');
?>