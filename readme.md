## Anchor CMS

Anchor is a super-simple, lightweight blog system, made to let you just write. [Check out the site](http://anchorcms.com/).

### Requirements

- PHP 5.5
- MySQL 5 or Sqlite 3 (PostgreSQL soon)

To determine your PHP version, create a new file with this PHP code: `<?php echo PHP_VERSION; // version.php`. This will print your version number to the screen.

### Install

	git clone https://github.com/anchorcms/anchor-cms.git
	cd anchor-cms
	curl -sS https://getcomposer.org/installer | php
	php composer.phar install
	cd web
	php -S localhost:8080

Run the install wizard from your browser (http://localhost:8080) to complete the installation

### Testing

Tests are located in the `spec` folder.

	php composer.phar update --dev
	php vendor/bin/phpspec run

### Problems?

If you can't install Anchor, check the [forums](http://forums.anchorcms.com/); there's probably someone there who's had the same problem as you, and the community is always happy to help. Additionally, check out the [documentation](http://anchorcms.com/docs).

### Here are some example server configurations

These have been supplied by [@tk421](https://github.com/tk421) to help you get started with your Anchor site

- [CentOS 7](https://manageacloud.com/cookbook/t5an3hm22jphr1eipus3bm67nb)
- [Ubuntu 14.04](https://manageacloud.com/cookbook/848chc151i3kbj79q1qtnqng3u)
- [Ubuntu 14.10](https://manageacloud.com/cookbook/anchor_cms_ubuntu_utopic_unicorn_1410)
- [Debian Wheezy](https://manageacloud.com/cookbook/s57arpb6m2sqsho287emeksoql)
- [Amazon Linux](https://manageacloud.com/cookbook/32tmgkt2rf7alk4tp1or312efp)
