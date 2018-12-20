'use strict';

/*
 global beforeAll,
 afterAll,
 test,
 describe,
 expect
 */

const config = {

  // base URL where AnchorCMS runs
  baseUrl: 'http://localhost',

  // strict testing mode: If this is not a debugging run, we'll fail on anything unexpected.
  // otherwise, we'll output error messages but continue running.
  strict: !process.env.DEBUG
};

const puppeteer = require( 'puppeteer' );
const faker     = require( 'faker' );

let browser,
    page;

/**
 * Setup: will open a Chrome instance anc make the page ready
 */
beforeAll( async () => {

  /**
   * Launches the Chrome instance, disabling the sandbox and memory restrictions
   * as recommended by the Puppeteer dev team
   *
   * @type {Puppeteer.Browser}
   */
  browser = await puppeteer.launch( {
                                      args: [
                                        '--no-sandbox',
                                        '--disable-setuid-sandbox',
                                        '--disable-dev-shm-usage'
                                      ]
                                    } );
  /**
   * Opens a new page and stores a reference
   *
   * @type {Page}
   */
  page = await browser.newPage();

  try {
    await page.goto( config.baseUrl );
  } catch ( error ) {
    console.error( `Could not connect to local web server: ${error.message}` );

    if ( config.strict ) {
      console.error( `Failing tests prematurely` );

      return process.exit( 3 );
    }
  }
} );

/**
 * Teardown: will close the page and the browser
 */
afterAll( async () => {
  await page.close();
  await browser.close();
} );

describe( 'Installation', () => {
  test( 'User can click on link to start the installer', async () => {
    await page.goto( config.baseUrl );
    await page.waitForSelector( '[href="/install/index.php"]' );
    await page.click( '[href="/install/index.php"]' );
    await page.waitForNavigation();
    await page.waitForSelector( '[action="/install/index.php?route=/start"]' );
  }, 10000 );
} );
