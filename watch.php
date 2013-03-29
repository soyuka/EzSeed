<?php
require_once('inc/config.php');


if($_POST['submit'] == 'video') {
    
    $i = $_POST['i'] ? (int) $_POST['i'] : 0;

    $path = $explorer->linkToPath($_POST['files'], $_POST['folders'], $_POST['cover'], $_POST['submit'], $i);
    
    $httpAccess = 'http://'.SERVER_IP . BASE;

    if($path->fileAmount == 0) {
        header("HTTP/1.0 404 Not Found");
        exit();
    }
// debug($path->file[$i]);
} else {
    header("HTTP/1.0 400 Bad Request");
	exit();
}
// debug($i);
?>
<?php include('header.php'); ?>

<h1><?php echo substr(basename($path->file[$i]),0,-4);?></h1>

<div id="video">
<object id="ie_plugin" classid="clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616"
            width="320" height="212"
            codebase="./plugin/DivXBrowserPlugin.cab">

		    <param name="autoPlay" value="false" />
            <param name="src" value="<?echo $httpAccess.substr($path->file[$i],2);?>" />
            <param name="previewImage" value="<?echo  $httpAccess.substr($path->cover,2);?>" />

            <embed id="np_plugin" type="video/divx" src="<?echo $httpAccess.substr($path->file[$i],2);?>"
                   width="640" height="480"
                   autoPlay="false" previewImage="<?echo $httpAccess.substr($path->cover,2);?>"
                   pluginspage="http://go.divx.com/plugin/download/">
            </embed>
 </object>
</div>
<section class="center">
    <label for="direct">VLC :</label>
    <textarea name="direct" rows="1" cols="100" class="tip" title="Copiez-collez ce lien dans VLC (ouvrir un flux réseau) si le streaming ne marche pas !"><?echo $httpAccess .substr($path->file[$i],2);?></textarea>

    <?  
    //Next
    if($path->fileAmount > 1 && $i < $path->fileAmount - 1) {
        echo '';
        $next = $explorer->linkToPath($_POST['files'], $_POST['folders'], $_POST['cover'], $_POST['submit'], $i+1, $string = 'Suivant', $class = 'pullRight');
        echo $next->html;
    }  
    //Prev
    if($path->fileAmount > 1 && $i > 0) {
       $prev = $explorer->linkToPath($_POST['files'], $_POST['folders'], $_POST['cover'], $_POST['submit'], $i-1, $string = 'Precedent', $class = 'pullLeft');
       echo $prev->html;
    }
    ?>
</section>
<div class="clear"></div>
<script type="text/javascript">

    // This code detects which browser we are
    // running into (IE/Safari or others) and assigns
    // the correct element (object or embed) to
    // the "plugin" variable, that we will use.
    //

    var plugin;

    if(navigator.userAgent.indexOf('MSIE')   != -1 ||
       navigator.userAgent.indexOf('Safari') != -1)
    {
        plugin = document.getElementById('ie_plugin');
    }
    else
    {
        plugin = document.getElementById('np_plugin');
    }
</script>
<?php include('footer.php'); ?>
