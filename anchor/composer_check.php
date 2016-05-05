<?php

// If that didn't work then produce an error.
if (!file_exists(PATH . 'vendor')) {

    // Let web crawlers know this isn't a proper anchor-cms page (prevents search ranking drop).
    header('HTTP/1.1 503 Service Temporarily Unavailable');

    // Return a nice valid XHTML 1.1 error message (so as not to confuse any robots).
    echo('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">' . chr(13) . chr(10));
    echo('<html>' . chr(13) . chr(10));
    echo(' <head>' . chr(13) . chr(10));
    echo('  <title>AnchorCMS Error</title>' . chr(13) . chr(10));
    echo(' </head>' . chr(13) . chr(10));
    echo(' <body>' . chr(13) . chr(10));
    echo('  <div>' . chr(13) . chr(10));
    echo('   <code>' . chr(13) . chr(10));
    echo('    This Anchor blog is not yet configured.<br/><br/>' . chr(13) . chr(10) . chr(13) . chr(10));
    echo('    For the site owner:<br/>' . chr(13) . chr(10));
    echo('    &nbsp;We were unable to run composer ourselves; please run "composer install" from the command line to finish installing Anchor.<br/>' .chr(13) . chr(10));
    echo('    &nbsp;If you do not have composer installed please see <a href="https://getcomposer.org/">https://getcomposer.org/</a>.' .chr(13) . chr(10));
    echo('   </code>' .chr(13) . chr(10));
    echo('  </div>' .chr(13) . chr(10));
    echo(' </body>' . chr(13) . chr(10));
    echo('</html>' . chr(13) . chr(10));
    
    // This is a catastrophic error that can only be resolved by the site administrator.
    die();
}