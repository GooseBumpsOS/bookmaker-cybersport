#!/bin/bash

git stash
git pull

sudo rm -rf /var/www/bookmaker-cybersport/var/cache/prod

cp /var/www/.env /var/www/bookmaker-cybersport/.env
