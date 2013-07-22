## Anchor CMS

Anchor is a super-simple, lightweight blog system, made to let you just write. [Check out the site](http://anchorcms.com/).

### Requirements

- PHP 5.3+
    - pdo\_mysql
- MySQL 5.1+

To determine your PHP version, create a new file with this PHP code: `<?php echo PHP_VERSION; // version.php`. This will print your version number to the screen.

### Install

1. Insure that you have the required components.
2. Download Anchor either from [here](http://anchorcms.com/download) or by cloning this Github repo.
3. Upload Anchor through FTP/SFTP or whatever upload method you prefer to the public-facing directory of your site.
4. Ensure that the permissions for the `content` and `anchor/config` folders are set to `0777`.
5. Create a database for Anchor to install to. You may name it anything you like. The method for database creation varies depending on your webhost but may require using PHPMyAdmin or Sequel Pro. If you are unsure of how to create this, ask your host.
6. Navigate your browser to your Anchor installation URL, if you have placed Anchor in a sub directory make sure you append the folder name to the URL: `http://MYDOMAINNAME.com/anchor`
7. Follow the installer instructions
8. For security purposes, delete the `install` directory when you are done.

### Problems?

If you can't install Anchor, check the [forums](http://forums.anchorcms.com/); there's probably someone there who's had the same problem as you, and the community is always happy to help. Additionally, check out the [documentation](http://anchorcms.com/docs).

## Contribution Guidelines

Please submit issues to anchorcms/anchor-cms and pull requests to *-dev branches.

Checking out out Anchor

	mkdir anchor-dev
	cd anchor-dev
	git init
	git remote add origin git@github.com:anchorcms/anchor-cms.git
	git checkout VERSION-dev
	git pull origin VERSION-dev

Replacing VERSION with the corisponding branch.