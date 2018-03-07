'use strict';

/*
 global describe,
 test,
 __PAGE__,
 __BASE_URL__
 */

const timeout = 5000;

const urls = {
  index:               '/install/index.php',
  languageAndTimezone: '/install/index.php?route=/start',
  database:            '/install/index.php?route=/database',
  metadata:            '/install/index.php?route=/metadata'
};

const selectors = {
  languageAndTimezone: {
    form:             `form[action="${urls.languageAndTimezone}"]`,
    languageSelector: '#lang_chosen',
    chosenLanguage:   '[name="language"]',
    timezoneSelector: '#timezone_chosen',
    chosenTimezone:   '[name="timezone"]'
  },
  database:            {
    form:            `form[action="${urls.database}"]`,
    hostname:        '[name="host"]',
    port:            '[name="port"]',
    username:        '[name="user"]',
    password:        '[name="pass"]',
    name:            '[name="name"]',
    prefix:          '[name="prefix"]',
    collation:       '#collation_chosen',
    chosenCollation: '[name="collation"]'
  },
  metadata:            {
    form: `form[action="${urls.metadata}"]`
  },
  installAnchorButton: `[href="${urls.index}"]`,
  buttonNextStep:      '.options button[type="submit"]'
};

describe( 'Installation', () => {
  let page;

  beforeAll( async () => page = await global.__BROWSER__.newPage(), timeout );

  test( 'User can click on link to start the installer', async () => {
    await page.goto( global.__BASE_URL__ );
    await page.waitForSelector( selectors.installAnchorButton );
    await page.click( selectors.installAnchorButton );
    await page.waitForSelector( selectors.languageAndTimezone.form );
  } );

  describe( 'Language and Timezone', () => {
    beforeAll( async () => await page.goto( global.__BASE_URL__ + urls.languageAndTimezone ) );

    test( 'User can choose their language', async () => {
      await page.waitForSelector( selectors.languageAndTimezone.languageSelector );
      await page.click( selectors.languageAndTimezone.languageSelector + ' a' );
      await page.focus( selectors.languageAndTimezone.languageSelector + ' input' );
      await page.keyboard.type( 'en' );
      await page.keyboard.down( 'Enter' );
      await page.waitFor( 300 );
      await page.waitForSelector( selectors.languageAndTimezone.chosenLanguage );

      const selectedTimezone = await page.$eval(
        selectors.languageAndTimezone.chosenLanguage,
        element => element.value
      );

      expect( selectedTimezone ).toEqual( 'en_GB' );
    } );

    // TODO: Timezone test is disabled for now, since I can't seem to get select2 to play nice
    // when being controlled using the browser. This might be resolved by replacing them with
    // a <datalist> solution anyway.
    xtest( 'User can choose their timezone', async () => {
      await page.waitForSelector( selectors.languageAndTimezone.timezoneSelector );
      await page.waitFor( 300 );
      await page.click( selectors.languageAndTimezone.timezoneSelector + ' a' );
      await page.focus( selectors.languageAndTimezone.timezoneSelector + ' input' );
      await page.keyboard.type( 'midway' );
      await page.keyboard.down( 'Enter' );
      await page.waitForSelector( selectors.languageAndTimezone.chosenTimezone );

      const selectedTimezone = await page.$eval(
        selectors.languageAndTimezone.chosenTimezone,
        element => element.value
      );

      expect( selectedTimezone ).toEqual( 'Pacific/Midway' );
    } );

    test( 'User can click on next step button to navigate to database settings', async () => {
      await page.goto( global.__BASE_URL__ + urls.languageAndTimezone );
      await page.waitForSelector( selectors.buttonNextStep );
      await page.click( selectors.buttonNextStep );
      await page.waitForSelector( selectors.database.form );
    } );
  } );

  describe( 'Database', () => {
    beforeAll( async () => await page.goto( global.__BASE_URL__ + urls.database ) );

    test( 'User can set their database hostname', async () => {
      await page.waitForSelector( selectors.database.hostname );
      await page.focus( selectors.database.hostname );
      await page.keyboard.type( 'localhost' );
    } );

    test( 'User can set their database port', async () => {
      await page.waitForSelector( selectors.database.port );
      await page.focus( selectors.database.port );
      await page.keyboard.type( '3306' );
    } );

    test( 'User can set their database username', async () => {
      await page.waitForSelector( selectors.database.username );
      await page.focus( selectors.database.username );
      await page.keyboard.type( 'root' );
    } );

    test( 'User can set their database password', async () => {
      await page.waitForSelector( selectors.database.password );
      await page.focus( selectors.database.password );
      // await page.keyboard.type( '' );
    } );

    test( 'User can set their database name', async () => {
      await page.waitForSelector( selectors.database.name );
      await page.focus( selectors.database.name );
      await page.keyboard.type( 'anchor' );
    } );

    test( 'User can set their database table prefix', async () => {
      await page.waitForSelector( selectors.database.prefix );
      await page.focus( selectors.database.prefix );
      await page.keyboard.type( '_anchor' );
    } );

    test( 'User can set their database collation', async () => {
      await page.waitForSelector( selectors.database.collation );
      await page.click( selectors.database.collation + ' a' );
      await page.focus( selectors.database.collation + ' input' );
      await page.keyboard.type( 'unicode' );
      await page.keyboard.down( 'Enter' );
      await page.waitFor( 300 );
      await page.waitForSelector( selectors.database.chosenCollation );

      const selectedCollation = await page.$eval(
        selectors.database.chosenCollation,
        element => element.value
      );

      expect( selectedCollation ).toEqual( 'utf8mb4_unicode_ci' );
    } );

    test( 'User can click on next step button to navigate to metadata settings', async () => {
      await page.goto( global.__BASE_URL__ + urls.metadata );
      await page.waitForSelector( selectors.buttonNextStep );
      await page.click( selectors.buttonNextStep );
      await page.waitForSelector( selectors.metadata.form );
    } );
  } );

  afterAll( async () => await page.close() );
} );
