EzSeed
======

<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/fr/"><img alt="Licence Creative Commons" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/fr/88x31.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" property="dct:title">EzSeed</span> de <a xmlns:cc="http://creativecommons.org/ns#" href="https://github.com/soyuka/EzSeed/" property="cc:attributionName" rel="cc:attributionURL">soyuka</a> est mis à disposition selon les termes de la <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/fr/">licence Creative Commons Attribution - Pas d’Utilisation Commerciale - Partage dans les Mêmes Conditions 3.0 France</a>.<br />Les autorisations au-delà du champ de cette licence peuvent être obtenues à <a xmlns:cc="http://creativecommons.org/ns#" href="http://dgear.fr/contact/" rel="cc:morePermissions">ici</a>.

Thème par <a href="http://zupmage.eu">Lines</a>.

Site en phase bêta. Je ne suis pas responsable de la mauvaise utilisation du site.

Un module de paiement a aussi été développé, pour plus d'informations <a href="http://dgear.fr/contact">contactez-moi</a>, il ne sera
pas mis à la disposition du public par prévention d'une utilisation à but lucratif.

Je propose une installation sous Debian 6.0 pour plus d'informations <a href="http://dgear.fr/contact">contactez-moi</a>.

##Présentation##

<img src="http://www.zupmage.eu/up/CdA0QaJtpl.png" alt="EzSeed logo" class="logo" /> EzSeed ([ˈiː-ziː siːdˈ]) est une plateforme permettant d'utiliser le P2P avec transmission 
sur un serveur distant et de le partager a sa famille ou ses amis. Il est très simple à utiliser après installation, 
même chez les plus novices. 

EzSeed a été préparé pour une utilisation avec Debian uniquement, il est possible que quelques changements soient nécéssaires sur un autre système d'opération.

<a href="http://www.zupmage.eu/multi-Io1963c1">Voir en images</a>

##Requis##

Afin de faire fonctionner ce script il est primordial d'avoir installé :
- Un serveur Web (par exemple Apache)
- ffmpeg
- zip
- php5, php5-cli, php5-ffmpeg
- transmission

Il peut être intéressant d'installer aussi vsftpd pour permettre aux utilisateurs de se connecter par FTP à leur répertoire.

##Wiki##
<ul>
<li><a href="https://github.com/soyuka/EzSeed/wiki/Debian6-Apache-Installation">Exemple d'installation avec Apache sous Debian 6</a></li>

</ul>

##Un peu d'aide##

Vérfiez que l'extension ffmpeg est bien installée :

```
ffmpeg -version

php -m | grep ffmpeg
```

Si mkpasswd n'est pas disponible vous pouvez prendre le paquet ubuntu :
```
wget http://mirrors.kernel.org/ubuntu/pool/universe/w/whois/mkpasswd_5.0.0ubuntu3_amd64.deb
dpkg -i mkpasswd_5.0.0ubuntu3_amd64.deb
```

##To Do##
- Problème d'intégration de transmission
- Fichier d'installation automatique pour Debian
- Real-streaming (transformer automatiquement les vidéos en flv)
- Permettre le partage de fichiers
- Autoriser l'envoi de torrents/fichiers
