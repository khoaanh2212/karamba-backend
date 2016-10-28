FROM php:7.0.8-apache
ARG SOURCES_MIRROR=""
RUN if [ ! -z "${SOURCES_MIRROR}" ]; then echo "$SOURCES_MIRROR" > /etc/apt/sources.list ; fi
RUN apt-get update && apt-get install -y git \
	bzip2 libbz2-dev curl libcurl4-openssl-dev libmcrypt-dev libssl-dev libxml2 libxml2-dev \
	zip php-pear \
	mysql-client \
 	&& apt-get clean all
RUN apt-get update && apt-get install aptitude -y && aptitude install libxft-dev -y && apt-get clean all
RUN docker-php-ext-install bz2
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install calendar
RUN docker-php-ext-install curl
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install phar
RUN docker-php-ext-install opcache
RUN echo yes | pecl install xdebug
RUN a2enmod rewrite
RUN a2enmod ssl
RUN mkdir -p /var/www/carambacars
WORKDIR /var/www/carambacars
COPY composer.json /var/www/carambacars
COPY composer.lock /var/www/carambacars
COPY composer.phar /usr/local/bin/composer
RUN composer install --no-autoloader --no-scripts
RUN mkdir -p /var/www/carambacars/app
COPY app/AppKernel.php /var/www/carambacars/app
COPY app/AppCache.php /var/www/carambacars/app
COPY app/config /var/www/carambacars/app/config
COPY app/autoload.php /var/www/carambacars/app
COPY bin /var/www/carambacars/bin
RUN mkdir -p var/cache
RUN mkdir -p var/logs
RUN mkdir -p var/sessions

######### PRELOAD BUNDLES #####################
RUN mkdir -p src/AppBundle
RUN mkdir -p src/AppBundle/SecurityProviders
COPY src/AppBundle/AppBundle.php src/AppBundle/
COPY src/AppBundle/SecurityProviders src/AppBundle/SecurityProviders
########## ENDOF BUNDLES ######################

########## LOGS ###############################
#RUN touch var/logs/prod.log
#RUN ln -sf /dev/stdout var/logs/prod.log
########## ENDOFLOGS ####################

RUN mkdir -p web
RUN rm -fr /app/config/parameters.yml
RUN composer install
COPY runTests.sh /opt/
COPY . /var/www/carambacars/
RUN chown www-data:www-data -R /var/www/carambacars/
CMD ["bash", "start.sh"]