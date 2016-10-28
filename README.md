KARAMBACARS
===========

Local development enviroment
----------------------------

* Install [**docker**][3]
* Install apt dependencies
```
sudo apt-get update && sudo apt-get install \
    libbz2-dev libcurl4-openssl-dev libmcrypt-dev \
    libssl-dev libxml2 libxml2-dev \
    libreadline-dev libxslt-dev \
    curl zip php-pear bzip2 libpq-dev
```

* Add the following entry to /etc/hosts: 127.0.0.1 mysql
* Run mysql container in current directory:
```
docker run --name karambacars-mysql -d \
	-p 3306:3306 \
	-v $(pwd)/config/mysql/:/etc/mysql/conf.d/ \
	-v $(pwd)/var/mysql/:/var/lib/mysql/ \
	-e MYSQL_ROOT_PASSWORD=root \
	-e MYSQL_DATABASE=karambacars \
	mysql:5.7.12
```

* Install [**PhpBrew**][1]
	* Follow the steps on their website. (Please run ```phpbrew init``` before continue)
	* Install php version 7.0.8 with the following option: `phpbrew update && phpbrew install 7.0.8 +default +dbs +opcache && phpbrew switch 7.0.8`
	* Install the following extensions with `phpbrew ext install`
		* xdebug
* Install [**composer**][2] components
	* `./composer.phar install`
* Run migrations
	* `bin/console doctrine:migrations:migrate`
* start development server
	* `bin/console server:run`

NAME SERVER
------------

add the following entries to /etc/hosts

* 127.0.0.1 www.karambacars.com admin.karambacars.com api.karambacars.com dealers.karambacars.com
* 127.0.0.1 mysql

[1]: https://github.com/phpbrew/phpbrew
[2]: https://getcomposer.org/
[3]: https://docs.docker.com/engine/installation/linux/ubuntulinux/
