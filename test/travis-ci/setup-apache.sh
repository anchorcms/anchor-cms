#!/bin/sh

# set up Apache for php-fpm
# @see https://github.com/travis-ci/travis-ci.github.com/blob/master/docs/user/languages/php.md#apache--php

sudo a2enmod rewrite actions fastcgi alias ssl

# configure apache virtual hosts
sudo cp -f test/travis-ci/apache_vhost /etc/apache2/sites-available/default
sudo cp -f test/travis-ci/apache_vhost /etc/apache2/sites-available/000-default.conf
sudo sed -e "s?%TRAVIS_BUILD_DIR%?$(pwd)?g" --in-place /etc/apache2/sites-available/default
sudo sed -e "s?%TRAVIS_BUILD_DIR%?$(pwd)?g" --in-place /etc/apache2/sites-available/000-default.conf

sudo a2ensite 000-default.conf
sudo service apache2 restart
