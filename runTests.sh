#!/usr/bin/env bash
set -e
set -x
DB_HOST=$1
while ! mysqladmin ping -h"$DB_HOST" --silent; do
	echo "MYSQL NOT READY"
    sleep 1
done
echo "MYSQL READY"
echo y | bin/console doctrine:migrations:migrate
composer exec phing test