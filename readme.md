![Anchor Header Image](.github/anchor-bg.jpeg)

# Anchor CMS

Anchor is a super-simple, lightweight blog system, made to let you just write. [Check out the site](http://anchorcms.com/) or checkout the [documentation here](http://docs.anchorcms.com/).

## Requirements

- PHP 5.6+
    - curl
    - mcrypt
    - gd
    - pdo\_mysql or pdo\_sqlite
- MySQL 5.6+ (MySQL 5.7 recommended)

To determine your PHP version, create a new file with this PHP code: `<?php echo PHP_VERSION; ?>// version.php` or run `php -v` in the command line. This will print your version number to the screen.

## Installation

1. Ensure that you have the required components.
2. Download Anchor either from [here](http://anchorcms.com/download), by cloning this Github repo or by running:
```
composer create-project anchorcms/anchor-cms anchor
```
3. Upload Anchor through FTP/SFTP or whatever upload method you prefer to the public-facing directory of your site.
4. Ensure that the permissions for the `content` and `anchor/config` folders are set to `0775` and that all files belong to the web user or is a part of the same group as the web user.
5. Create a database for Anchor to install to. You may name it anything you like. The method for database creation varies depending on your webhost but may require using PHPMyAdmin or Sequel Pro. If you are unsure of how to create this, ask your host.
6. Navigate your browser to your Anchor installation URL, if you have placed Anchor in a sub directory make sure you append the folder name to the URL: `http://MYDOMAINNAME.com/anchor`
7. Follow the installer instructions.
8. For security purposes, delete the `install` directory when you are done.

## Problems?

If you can't install Anchor, check the [forums](http://forums.anchorcms.com/); there's probably someone there who's had the same problem as you, and the community is always happy to help. Additionally, check out the [documentation](http://anchorcms.com/docs).

## Contributing

If you'd like to help out and contribute to this project, please take a look at the [contributing guidelines](https://github.com/anchorcms/anchor-cms/blob/master/.github/CONTRIBUTING.md). All information you need to get started should be included in there. If you have any questions then create an issue, make a forum post or message us directly.

## Feature Requests:

If you would like to add submit a feature request then please go to our [Feathub page](http://feathub.com/anchorcms/anchor-cms).

[![Feature Requests](http://feathub.com/anchorcms/anchor-cms?format=svg)](http://feathub.com/anchorcms/anchor-cms)
