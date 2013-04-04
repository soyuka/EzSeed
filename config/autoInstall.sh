#!/bin/bash

echo "Répertoire d'installation de la seedbox : (par exemple /home/seedbox) "
read dir

if [[ -z "$dir" ]]
then
	dir = "/home/seedbox"
fi

apt-get install apache2 ffmpeg php5 php5-cli php5-ffmpeg git

cd /tmp

git clone https://github.com/soyuka/EzSeed

mkdir $dir

mv ./EzSeed/* $dir

cp ./EzSeed/.htaccess $dir/.htaccess

a2dissite 000-default

cd /etc/apache2/sites-available

echo "<VirtualHost *:80>

  DocumentRoot $dir
  <Directory $dir/>
    Options -Indexes FollowSymLinks MultiViews
    AllowOverride All
  </Directory>
</VirtualHost>" >> seedbox

a2ensite seedbox

a2enmod rewrite

/etc/init.d/apache2 restart

echo "Configurez maintenant le fichier inc/config.php et créez votre premier utilisateur avec config/newSeedbox.sh !"