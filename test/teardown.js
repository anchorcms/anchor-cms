'use strict';

/*
 global window,
 document
 */

const chalk      = require( 'chalk' );
const puppeteer  = require( 'puppeteer' );
const rimraf     = require( 'rimraf' );
const os         = require( 'os' );
const path       = require( 'path' );
const jestConfig = require( '../jest.config' );

const baseDirectory = path.join( os.tmpdir(), 'jest_puppeteer_global_setup' );

module.exports = async function () {
  jestConfig.globals.__DEBUG__ && console.log( chalk.green( 'Teardown Puppeteer' ) );

  await global.__BROWSER__.close();

  rimraf.sync( baseDirectory );
};
