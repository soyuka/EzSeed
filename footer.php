<?if(!defined('SB'))die();?>
</section>
<div id="maintenance" class="maintenance">
	<a href="#" class="close pullRight">fermer</a>
	<div class="clearfix"></div>
	<h3>30/03/2013</h3>
	<div>
		<p>
			Petite mise à jour : 
			<ul>
				<li>Mise à jour de la partie écouter</li>
				<li>Auto-zip des albums</li>
				<li>Little bug fixes</li>
			</ul>
			<br />
			Le script est maintenant sous Licence Creative Commons et est distribué gratuitement sur le <a href="https://github.com/EzSeed">github</a> !
		</p>
	</div>
	<h3>23/02/2013</h3>
	<div>
		<p>
			Mise à jour de JQuery 1.9.1
		</p>
	</div>
	<h3>12/02/2013</h3>
	<div>
		<p>Salut à tous, comme vous l'avez peut-être remarqué une petite maintenance à eu lieu hier et aujourd'hui.<br />
		Quelques bugs ont été résolus à droite à gauche et le module de paiement a été mis en place :</p>
		<ul>
			<li style="padding-left:10px;">- Les nombres de jours sont maintenant correctement décomptés et vous n'y échapperez plus niark niark.</li>
			<li style="padding-left:10px;">- le bouton <strong>supprimer</strong> des torrents a été modifié pour qu'il supprime aussi les données. </li>
		</ul>
		<p>Si vous êtes sûr de vouloir garder les données et de virer le torrent, le clic droit => supprimer est toujours envisageable.
		D'ici quelques temps j'essaierai d'optimiser un peu plus le code pour que la page charge plus vite.</p>

	</div>
</div>
<footer class="center small">
Designed by <a href="//zupmage.eu">Lines</a> & developped by Soyuka © 2012-2013 | <a class="changelog" href="#">ChangeLog</a> | <a href="mailto:soyuka@gmail.com">Contact</a>
</footer>

<script type="text/javascript">
$('body').find('.tip').tooltip({track: true});

$('body').find('.tip-warn').tooltip(
{
    tooltipClass: 'ui-state-error',
    track:true
});

$('body').find('.tip.user-infos').tooltip(
{
	content: function() {
		return $('#user-infos').html();
	}
});

$('body').on('click', 'button.debug', function() {
    $d = $(this).parent().find('div.debug');
    
    if($d.is(':hidden'))
        $d.show();
    else
        $d.hide();
});

$('body').on('click', 'a.changelog', function() {

	$('#overlay').fadeIn();

	$(window).scrollTop('150');

	$('#maintenance').fadeIn();

	return false;
});

$('body').on('click', 'button[type="submit"]', function() {
	$.cookie('jumpTo', $(this).parent().parent().parent().parent().data('id'));
});

$('body').on('click', 'a.close', function() {
	$(this).parent().fadeOut();
	$('#overlay').hide();
	return false;

});

$('body').on('click', '.setCookie', function() {
	$.cookie('hasSeen', '1');
});

</script>

<?if($home) {?>
<script type="text/javascript">
var jumpTo = $.cookie('jumpTo');

function scrollToElement(selector, time, verticalOffset) {
    time = typeof(time) != 'undefined' ? time : 1000;
    verticalOffset = typeof(verticalOffset) != 'undefined' ? verticalOffset : 0;
    element = $(selector);
    offset = element.offset();
    offsetTop = offset.top + verticalOffset;
    $('html, body').animate({
        scrollTop: offsetTop
    }, time);
}

$(window).load(function() {

	$('section').isotope({
		containerStyle: { overflow: 'visible', position: 'relative'},
		itemSelector : '.element', filter:'.list',
		layoutMode: 'straightDown'
	});

	$('.element.miniature .titre h1').quickfit();

	$('section').fadeIn().isotope('reLayout');
	
	$('#loader').hide();
	//$('[data-id="'+jumpTo+'""]').height()
	if(jumpTo != undefined) {
		scrollToElement($('.element[data-id="'+jumpTo+'"]'));
		$.removeCookie('jumpTo');
	}

});

$(document).ready(function() {
    $('section').hide();

	$('body').on('click', '.showFiles', function() {
		$button = $(this);
		$files = $(this).next('.files');
		$element = $(this).parent().parent();

		if($files.is(':hidden')) {
			$button.text('Cacher les fichiers');

			var margin = $files.height() + 20;
			$element.css({'margin-bottom':'+'+margin+'px'});

			$files.delay(100).fadeIn();
			$('section').isotope('reLayout');

		} else {
			$button.text('Fichiers du dossier');
			$files.hide();
			$element.css({'margin-bottom':'20px'});
			$('section').isotope('reLayout');
		}
	});

	$('body').on('click', '#displayFilters li', function(){
		var selector = $(this).attr('data-filter');
		var display = $(this).attr('data-display');
		console.log(selector +  display);
		$('section').isotope({ filter: display + selector });
		return false;
	});

	$('body').on('click', '#displayOptions img', function() {
		var selector = $(this).attr('data-filter');
		
		$('section').isotope({ 
			filter: selector,
			layoutMode: 'masonry',
			masonry: {
    			columnWidth: 210
  			}

		});

		$('#displayFilters li').each(function() {
			$(this).attr('data-display', selector);
		});


		return false;
	});

});
</script>
<?}?>
</body>
</html>