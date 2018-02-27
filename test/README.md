AnchorCMS - CI tests [![Build Status](https://travis-ci.org/anchorcms/anchor-cms.svg?branch=master)](https://travis-ci.org/anchorcms/anchor-cms)
--------------------

This directory contains tests intended to be carried out in a CI environment, more specifically [Travis-CI](https://travis-ci.org).  
These tests are split in [integration tests](./integration) and [unit tests](./unit) to make sure both user interaction and the base
code components work properly.

To run these tests locally, you will need to have a webserver running on `http://localhost:80`, a working PHP installation and a 
working AnchorCMS installation in that webserver's web root directory, plus MySQL up and running on `localhost:3306` with a root user
with password.  
These environment settings should be moved into ENV variables at some point.



Unit tests
==========

The unit tests are written using [Peridot](http://peridot-php.github.io/) with its 
[Leo assertion library](http://peridot-php.github.io/leo/). Despite being less popular than CodeCeption or PhpUnit, Peridot provides
a syntax extremely similar to common JavaScript testing libraries, to the point that boilerplate tests can be almost copy-pasted
between the two. This makes working with tests written in both languages way more pleasant to work with at the same time.

Unit test files are roughly named after the namespace they are located in: `<namespace>.<subdirectory>.<classname>.spec.php`.

To run the unit tests with Peridot, use the following command in the project directory:

```bash
./vendor/.bin/peridot --configuration test/peridot.php
```


Integration tests
=================

The integration tests are written in JavaScript, using both [Jest](https://facebook.github.io/jest/) for the actual testing framework
and [Puppeteer](https://github.com/GoogleChrome/puppeteer) for the headless browser. Puppeteer is probably the best option for headless
browser usage currently, as it spins up a fully-fledged version of Google Chrome that can be controlled via the remote debugging API.  
Puppeteer provides an abstraction layer on top of that, making user interaction almost plain english language.

Integration test files are roughly named after the user goal: `<user goal>.js`.

To run the integration tests with Jest, use th following command in the project directory:

```bash
npm test
```

