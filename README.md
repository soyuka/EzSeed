EzSeed
======

<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/fr/"><img alt="Licence Creative Commons" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/fr/88x31.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" property="dct:title">EzSeed</span> de <a xmlns:cc="http://creativecommons.org/ns#" href="https://github.com/soyuka/EzSeed/" property="cc:attributionName" rel="cc:attributionURL">soyuka</a> est mis à disposition selon les termes de la <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/fr/">licence Creative Commons Attribution - Pas d’Utilisation Commerciale - Partage dans les Mêmes Conditions 3.0 France</a>.<br />Les autorisations au-delà du champ de cette licence peuvent être obtenues à <a xmlns:cc="http://creativecommons.org/ns#" href="http://dgear.fr/contact/" rel="cc:morePermissions">ici</a>.

Thème par <a href="http://zupmage.eu">Lines</a>.

Site en phase bêta. Je ne suis pas responsable de la mauvaise utilisation du site.

Un module de paiement a aussi été développé, pour plus d'informations <a href="http://dgear.fr/contact">contactez-moi</a>, il ne sera
pas mis à la disposition du public par prévention d'une utilisation à but lucratif.

##Présentation##

EzSeed ([ˈiː-ziː siːdˈ]) est une plateforme permettant d'utiliser le P2P avec transmission 
sur un serveur distant et de le partager a sa famille ou ses amis. Il est très simple à utiliser après installation, 
même chez les plus novices. 

EzSeed a été préparé pour une utilisation avec Debian uniquement, il est possible que quelques changements soient nécéssaires sur un autre système d'opération.

<a href="http://www.zupmage.eu/multi-Io1963c1">Voir en images</a>

##Installation##

Note : En cours de rédaction

Afin de faire fonctionner ce script il est primordial d'avoir installé :
- Un serveur Web (par exemple Apache)
- ffmpeg
- zip
- php5, php5-cli, php5-ffmpeg

Il peut être intéressant d'installer aussi vsftpd pour permettre aux utilisateurs de se connecter par FTP à leur répertoire.

Une petite liste de tutoriels :
- <a href="http://www.lafermeduweb.net/billet/tutorial-creer-un-serveur-web-complet-sous-debian-1-apache-160.html">Installer un serveur sous Debian</a>
- <a href="http://d.stavrovski.net/blog/installing-ffmpeg-and-php-ffmpeg-in-debian-6-squeeze/">Installer ffmpeg et php-ffmpeg</a>
- <a href="www.admin-debian.com/ftp/vsftpd-un-serveur-ftp-hautement-securise/">Configurer vsftpd sous Debian</a>

Placez ensuite les fichiers à la racine de votre serveur web (par exemple /var/www/).

Pour apache n'oubliez pas d'ajouter ces lignes au 000-default dans sites-available :

```
<VirtualHost *:80>

  DocumentRoot /var/www/ezseed
  <Directory />
    DirectoryIndex index.php index.html index.htm index.xhtml
    Options FollowSymLinks
    AllowOverride All
    Order allow,deny
    allow from all
  </Directory>
  
  ## reste du fichier ##
  
</VirtualHost>
```

puis d'activer le mod rewrite `a2enmod rewrite`

Vous pouvez aisément changer de serveur web, il n'est responsonsable que du rewrite.


Vous pouvez maintenant configurer les paramètres dans le fichier inc/config.php :

```
define('SERVER_IP', '255.255.255.255'); // IP SERVER

define('ROOT', '/var/www/ezseed/'); // Root path

define('BASE', '/ezseed/'); // Web Base path

define('DISK_SIZE', 25); // Disk size gb

define('USER_COUNT', 1); // How many users ?


define('ZIP_AUDIO_FOLDERS', 1); //zip audio folders automatically

define('MAX_AUDIO_FOLDER_SIZE', 700); //in MB - if it's bigger we won't zip it


define('ADMIN', 'ezseed'); //set the admin username
```

Vérifiez que les dossiers `tmp` ait les droits d'écriture.

Ceci fait éditez le fichier config/newSeedbox.sh et changez le chemin :
`wwwConfigDir='/var/www/ezseed/config'`

Vous pouvez maintenant ajouter des utilisateurs à votre seedbox en ssh (en root ou chmod +x):
`./newSeedbox.sh`

Si mkpasswd n'est pas disponible vous pouvez prendre le paquet ubuntu :
```
wget http://mirrors.kernel.org/ubuntu/pool/universe/w/whois/mkpasswd_5.0.0ubuntu3_amd64.deb
dpkg -i mkpasswd_5.0.0ubuntu3_amd64.deb
```

Il vous sera demandé le nom d'utilisateur, le mot de passe, le peer-port et le rpc-port, par exemple :
```
PeerPort :
9092
RpcPort
51413
```

En tant qu'administrateur accédez à /administration pour retrouver ces informations.

##To Do##
- Fichier d'installation pour Debian
- Real-streaming (transformer automatiquement les vidéos en flv)
- Permettre le partage de vidéos
- Autoriser l'envoi de torrents
