EzSeed
======

<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/fr/"><img alt="Licence Creative Commons" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/fr/88x31.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" property="dct:title">EzSeed</span> de <a xmlns:cc="http://creativecommons.org/ns#" href="https://github.com/soyuka/EzSeed/" property="cc:attributionName" rel="cc:attributionURL">soyuka</a> est mis à disposition selon les termes de la <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/fr/">licence Creative Commons Attribution - Pas d’Utilisation Commerciale - Partage dans les Mêmes Conditions 3.0 France</a>.<br />Les autorisations au-delà du champ de cette licence peuvent être obtenues à <a xmlns:cc="http://creativecommons.org/ns#" href="http://dgear.fr/contact/" rel="cc:morePermissions">ici</a>.

Thème par <a href="http://zupmage.eu">Lines</a>.

Site en phase bêta. Je ne suis pas responsable de la mauvaise utilisation du site.

Un module de paiement a aussi été développé, pour plus d'informations <a href="http://dgear.fr/contact">contactez-moi</a>, il ne sera
pas mis à la disposition du public par prévention d'une utilisation à but lucratif.

Je propose une installation sous Debian 6.0 pour plus d'informations <a href="http://dgear.fr/contact">contactez-moi</a>.

##Présentation##

EzSeed ([ˈiː-ziː siːdˈ]) est une plateforme permettant d'utiliser le P2P avec transmission 
sur un serveur distant et de le partager a sa famille ou ses amis. Il est très simple à utiliser après installation, 
même chez les plus novices. 

EzSeed a été préparé pour une utilisation avec Debian uniquement, il est possible que quelques changements soient nécéssaires sur un autre système d'opération.

<a href="http://www.zupmage.eu/multi-Io1963c1">Voir en images</a>

##Requis##

Note : En cours de rédaction

Afin de faire fonctionner ce script il est primordial d'avoir installé :
- Un serveur Web (par exemple Apache)
- ffmpeg
- zip
- php5, php5-cli, php5-ffmpeg

Il peut être intéressant d'installer aussi vsftpd pour permettre aux utilisateurs de se connecter par FTP à leur répertoire.

##Exemple d'installation avec Apache sous Debian 6##

1) Installation des dépendances

`apt-get install apache2 ffmpeg php5 php5-cli php5-ffmpeg`

2) Vérifiez que `short_open_tag` est à `On` dans `php.ini`

`nano /etc/php/apache2/php.ini`

3) Installez git et clonez l'app

```
apt-get install git
# on se place dans /home
cd /home
git clone https://github.com/soyuka/EzSeed
# on change le répertoire de nom
mkdir seedbox
mv ./EzSeed/* ./seedbox/
```

4) On créé un virtualhost sous apache et on désactive le défaut

```
a2dissite 000-default
nano /etc/apache2/sites-available/seedbox
```

Mettez-y : 
```
<VirtualHost *:80>

  DocumentRoot /home/seedbox/
  <Directory /home/seedbox/>
    Options -Indexes FollowSymLinks MultiViews
    AllowOverride All
  </Directory>
</VirtualHost>
```

5) On lance maintenant le tout :

```
a2ensite seedbox
a2enmod rewrite
/etc/init.d/apache2 restart
```

6) Editons le fichier de création d'utilisateur :

`nano /home/seedbox/config/newSeedbox.sh``

Changez le `wwwDir` en `/home/seedbox`

7) On créé notre premier utilisateur :

```
chmod +x /home/seedbox/config/newSeedbox.sh
/home/seedbox/config/newSeedbox.sh

#Par exemple :
username
123456
51413
9091

#Le script installe transmission-daemon s'il n'est pas disponible
```

Note : Il faut changer les ports à chaque utilisateur !

8) Vous pouvez maintenant configurer les paramètres dans le fichier inc/config.php :

```
nano /home/seedbox/inc/config.php

# Définissez vos variables par exemple :
define('SERVER_IP', '255.255.255.255'); // IP SERVER

define('ROOT', '/home/seedbox/'); // Root path

define('BASE', '/'); // Web Base path

define('DISK_SIZE', 25); // Disk size gb

define('USER_COUNT', 1); // How many users ?

define('ZIP_AUDIO_FOLDERS', 1); //zip audio folders automatically

define('MAX_AUDIO_FOLDER_SIZE', 700); //in MB - if it's bigger we won't zip it

define('ADMIN', 'username'); //set the admin username
```

9) Vérifiez que les dossiers `tmp` ait les droits d'écriture et connectez-vous sur votre ip !

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
- Fichier d'installation pour Debian
- Real-streaming (transformer automatiquement les vidéos en flv)
- Permettre le partage de vidéos
- Autoriser l'envoi de torrents
