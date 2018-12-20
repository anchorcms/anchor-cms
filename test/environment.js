'use strict';

/*
 global module,
 require
 */

const chalk           = require( 'chalk' );
const NodeEnvironment = require( 'jest-environment-node' );
const puppeteer       = require( 'puppeteer' );
const fs              = require( 'fs' );
const os              = require( 'os' );
const path            = require( 'path' );
const jestConfig      = require( '../jest.config' );

const baseDirectory = path.join( os.tmpdir(), 'jest_puppeteer_global_setup' );

/**
 * Provides the global environment to puppeteer
 */
class Environment extends NodeEnvironment {

  /**
   * Creates a new environment
   *
   * @param {object} config
   */
  constructor ( config ) {
    super( config );
  }

  /**
   * Sets up the test environment
   *
   * @return {Promise<void>}
   */
  async setup () {
    jestConfig.globals.__DEBUG__ && console.log( chalk.yellow( 'Setup Test Environment' ) );

    await super.setup();

    const wsEndpoint = fs.readFileSync( path.join( baseDirectory, 'wsEndpoint' ), 'utf8' );

    if ( !wsEndpoint ) {
      throw new Error( 'wsEndpoint not found' );
    }

    this.global.__BROWSER__ = await puppeteer.connect( { browserWSEndpoint: wsEndpoint } );
  }

  /**
   * Tears down the test environment
   *
   * @return {Promise<void>}
   */
  async teardown () {
    jestConfig.globals.__DEBUG__ && console.log( chalk.yellow( 'Teardown Test Environment' ) );

    return await super.teardown();
  }

  /**
   * Runs the test script
   *
   * @param  {*} script
   * @return {*}
   */
  runScript ( script ) {
    return super.runScript( script );
  }
}

module.exports = Environment;
