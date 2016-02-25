## Anchor CMS

Anchor is a super-simple, lightweight blog system, made to let you just write. [Check out the site](http://anchorcms.com/).

[![Feature Requests](http://feathub.com/anchorcms/anchor-cms?format=svg)](http://feathub.com/anchorcms/anchor-cms)

### Requirements

- PHP 5.3.6+
    - curl
    - mcrypt
    - gd
    - pdo\_mysql or pdo\_sqlite
- MySQL 5.2+

To determine your PHP version, create a new file with this PHP code: `<?php echo PHP_VERSION; // version.php`. This will print your version number to the screen.

### Installation

1. Ensure that you have the required components.
2. Download Anchor either from [here](http://anchorcms.com/download) or by cloning this Github repo or by running:
```
composer create-project anchorcms/anchor-cms anchor
```
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

Distribution  | Status
------------- | -------------
[Debian Jessie 8](https://manageacloud.com/configuration/anchor_debian_jessie) | [![Debian Jessie 8](https://manageacloud.com/configuration/anchor_debian_jessie/build/7/image)](https://manageacloud.com/configuration/anchor_debian_jessie/builds)
[Debian Wheezy 7.0](https://manageacloud.com/configuration/anchor_cms_blog) | [![Debian Wheezy 7.0](https://manageacloud.com/configuration/anchor_cms_blog/build/1/image)](https://manageacloud.com/configuration/anchor_cms_blog/builds)
[Ubuntu Vivid 15.04](https://manageacloud.com/configuration/anchor_cms_ubuntu_vivid) | [![Ubuntu Vivid Vervet 15.04](https://manageacloud.com/configuration/anchor_cms_ubuntu_vivid/build/8/image)](https://manageacloud.com/configuration/anchor_cms_ubuntu_vivid/builds)
[Ubuntu Utopic 14.10](https://manageacloud.com/configuration/anchor_cms_ubuntu_utopic_unicorn_1410) | [![Ubuntu Utopic Unicorn 14.10](https://manageacloud.com/configuration/anchor_cms_ubuntu_utopic_unicorn_1410/build/6/image)](https://manageacloud.com/configuration/anchor_cms_ubuntu_utopic_unicorn_1410/builds)
[Ubuntu Trusty 14.04](https://manageacloud.com/configuration/anchor_cms_ubuntu_trusty_tahr_1404) | [![Ubuntu Trusty Tahr 14.04](https://manageacloud.com/configuration/anchor_cms_ubuntu_trusty_tahr_1404/build/2/image)](https://manageacloud.com/configuration/anchor_cms_ubuntu_trusty_tahr_1404/builds)
[CentOS 7](https://manageacloud.com/configuration/anchor_cms) | [![CentOS 7](https://manageacloud.com/configuration/anchor_cms/build/5/image)](https://manageacloud.com/configuration/anchor_cms/builds)
