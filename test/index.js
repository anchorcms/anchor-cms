const puppeteer = require( 'puppeteer' );

(async() => {
  const browser = await puppeteer.launch( { args: [
    '--no-sandbox',
    '--disable-setuid-sandbox',
    '--disable-dev-shm-usage'
  ] } );
  const page    = await browser.newPage();

  try {
    await page.goto( 'http://localhost' );
  } catch ( error ) {
    console.error( `Could not load page: ${error.message}` );

    return process.exit( 1 );
  }

  try {
    const content = await page.content()
    console.log( 'HTML content:', content );
  } catch ( error ) {
    console.error( `Could not fetch content: ${error.message}` );
    return process.exit( 1 );
  }

  await page.close();
  await browser.close();
})();
