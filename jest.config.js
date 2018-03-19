'use strict';

/*
 global module,
 exports
 */

/**
 * Provides configuration to Jest
 */
module.exports = {

  // set the verbose mode to the value of the DEBUG environment variable
  verbose: !!process.env.DEBUG || !!process.env.CI,

  // Coverage won't be working, since we're not testing JS here
  collectCoverage: false,

  globals: {

    // the base URL AnchorCMS is running at
    __BASE_URL__: 'http://localhost',

    // set the DEBUG environment variable
    __DEBUG__: !!process.env.DEBUG
  },

  globalSetup:     './test/setup.js',
  globalTeardown:  './test/teardown.js',
  testEnvironment: './test/environment.js',

  // do not send notifications if the tests are carried out in a CI environment
  notify: !process.env.CI,

  // only match tests within the integration test folder
  testMatch: [
    '**/test/integration/**/*.js'
  ]
};
