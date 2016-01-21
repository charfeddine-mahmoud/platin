#!/bin/bash
# un commentaire
current=${PWD##*/} 
echo "Process begin ..."
cd ..
echo "Permission ..."
sudo chmod -R 777 $current
cd $current
echo "sudo rm -rf app/cache/*"
sudo rm -rf app/cache/*
echo "sudo php app/console assets:install --symlink"
sudo php app/console assets:install --symlink
echo "sudo php app/console assetic:dump"
sudo php app/console assetic:dump
echo "sudo php app/console cache:clear --env=prod"
sudo php app/console cache:clear --env=prod
echo "sudo php app/console cache:clear --env=dev"
sudo php app/console cache:clear --env=dev
echo "sudo php composer.phar dump-autoload --optimize"
sudo php composer.phar dump-autoload --optimize
cd ..
sudo chmod -R 777 $current
cd $current
echo "Process end ..."


