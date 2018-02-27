'use strict';

/*
 global module,
 require
 */

const chalk      = require( 'chalk' );
const puppeteer  = require( 'puppeteer' );
const fs         = require( 'fs' );
const mkdirp     = require( 'mkdirp' );
const os         = require( 'os' );
const path       = require( 'path' );
const jestConfig = require( '../jest.config' );

const baseDirectory = path.join( os.tmpdir(), 'jest_puppeteer_global_setup' );

module.exports = async function () {
  jestConfig.globals.__DEBUG__ && console.log( chalk.green( 'Setup Puppeteer' ) );

  const puppeteerArgs = {
    args:     [
      '--no-sandbox',
      '--disable-setuid-sandbox',
      '--disable-dev-shm-usage'
    ],
    headless: !jestConfig.globals.__DEBUG__,
    devtools: jestConfig.globals.__DEBUG__
  };

  if ( jestConfig.globals.__DEBUG__ ) {
    puppeteerArgs.slowMo = 500;
  }

  const browser = await puppeteer.launch( puppeteerArgs );

  /**
   * Opens a new page and stores a reference
   *
   * @type {Page}
   */
  const page = await browser.newPage();

  try {
    await page.goto( jestConfig.globals.__BASE_URL__ );
  } catch ( error ) {
    console.error( chalk.red( `Could not connect to local web server: ${error.message}` ) );

    if ( jestConfig.globals.__DEBUG__ ) {
      console.error( chalk.red( `Failing tests prematurely` ) );

      return process.exit( 3 );
    }
  }

  global.__BROWSER__ = browser;

  mkdirp.sync( baseDirectory );

  fs.writeFileSync(
    path.join( baseDirectory, 'wsEndpoint' ),
    browser.wsEndpoint()
  );
};
