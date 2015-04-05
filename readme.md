## Anchor CMS

Anchor is a super-simple, lightweight blog system, made to let you just write. [Check out the site](http://anchorcms.com/).

### Requirements

- PHP 5.3.6+
    - curl
    - mcrypt
    - gd
    - pdo\_mysql or pdo\_sqlite
- MySQL 5.2+

To determine your PHP version, create a new file with this PHP code: `<?php echo PHP_VERSION; // version.php`. This will print your version number to the screen.

### Install

1. Ensure that you have the required components.
2. Download Anchor either from [here](http://anchorcms.com/download) or by cloning this Github repo.
3. Upload Anchor through FTP/SFTP or whatever upload method you prefer to the public-facing directory of your site.
4. Ensure that the permissions for the `content` and `anchor/config` folders are set to `0777`.
5. Create a database for Anchor to install to. You may name it anything you like. The method for database creation varies depending on your webhost but may require using PHPMyAdmin or Sequel Pro. If you are unsure of how to create this, ask your host.
6. Navigate your browser to your Anchor installation URL, if you have placed Anchor in a sub directory make sure you append the folder name to the URL: `http://MYDOMAINNAME.com/anchor`
7. Follow the installer instructions
8. For security purposes, delete the `install` directory when you are done.

### Problems?

If you can't install Anchor, check the [forums](http://forums.anchorcms.com/); there's probably someone there who's had the same problem as you, and the community is always happy to help. Additionally, check out the [documentation](http://anchorcms.com/docs).

### Here are some example server configurations

These have been supplied by [@tk421](https://github.com/tk421) to help you get started with your Anchor site

 - CentOS 7: view the [configuration summary](https://manageacloud.com/cookbook/t5an3hm22jphr1eipus3bm67nb), [try this application](https://manageacloud.com/cookbook/t5an3hm22jphr1eipus3bm67nb/deploy#test_deployment) or [deploy an instance](https://manageacloud.com/cookbook/t5an3hm22jphr1eipus3bm67nb/deploy)

 - Ubuntu 14.04: view the [configuration summary](https://manageacloud.com/cookbook/848chc151i3kbj79q1qtnqng3u), [try this application](https://manageacloud.com/cookbook/848chc151i3kbj79q1qtnqng3u/deploy#test_deployment) or [deploy an instance](https://manageacloud.com/cookbook/848chc151i3kbj79q1qtnqng3u/deploy)

 - Ubuntu 14.10: view the [configuration summary](https://manageacloud.com/cookbook/anchor_cms_ubuntu_utopic_unicorn_1410), [try this application](https://manageacloud.com/cookbook/j85v7rqni00vh2f453udl02ka7/deploy#test_deployment) or [deploy an instance](https://manageacloud.com/cookbook/j85v7rqni00vh2f453udl02ka7/deploy)

 - Debian Wheezy: view the [configuration summary](https://manageacloud.com/cookbook/s57arpb6m2sqsho287emeksoql), [try this application](https://manageacloud.com/cookbook/s57arpb6m2sqsho287emeksoql/deploy#test_deployment) or [deploy an instance](https://manageacloud.com/cookbook/s57arpb6m2sqsho287emeksoql/deploy)

 - Amazon Linux: view the [configuration summary](https://manageacloud.com/cookbook/32tmgkt2rf7alk4tp1or312efp), [try this application](https://manageacloud.com/cookbook/32tmgkt2rf7alk4tp1or312efp/deploy#test_deployment) or [deploy an instance](https://manageacloud.com/cookbook/32tmgkt2rf7alk4tp1or312efp/deploy)
