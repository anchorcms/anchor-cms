#!/bin/sh

cd $TRAVIS_BUILD_DIR

# install optimized composer dependencies
rm -rf ./vendor ./composer.lock
composer install --no-dev --prefer-dist --optimize-autoloader --ignore-platform-reqs --no-interaction --no-suggest --no-progress

# build static frontend assets
npm run build

mkdir ./release-build

# copy all relevant files and directories to the build directory
cp -R ./anchor ./content ./system ./system ./themes ./vendor ./index.php ./composer.json ./composer.lock ./readme.md ./license.md ./release-build/

# create an archive from it
tar czf ./release.tar.gz -C release-build/ *
