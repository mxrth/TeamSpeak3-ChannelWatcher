#!/bin/bash

cd ../Build/TeamSpeak3-ChannelWatcher

rm -rf *

cp -rf ../../TeamSpeak3-ChannelWatcher/* .

rm -rf doc
rm -rf tests

wget getcomposer.org/composer.phar
php composer.phar install

find . -type d -name .git -print0 | xargs -0 -r rm -rf

rm composer*
rm phpunit*
rm -r .travis*
rm -r .git*
rm -rf nbproject

#delete this script too
rm make-package.sh

read -p "Version:" version

cd ../

zip -r "devMX TeamSpeak3 Webviewer v$version.zip" .