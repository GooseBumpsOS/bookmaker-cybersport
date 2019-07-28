#!/bin/bash

git stash
git pull

sudo rm -rf var/cach/prod

cp /var/www/.env /var/www/bookmaker-cybersport/.env
