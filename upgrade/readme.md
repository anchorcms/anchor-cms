## Upgrading

**Backup your database and files!**

Here is an example for backup up on most servers via ssh using mysql dump:

	mysqldump --compact --quick --user myusername --password=mypassword --host=localhost anchorcms > /path/to/my/site/httpdocs/db.sql

lets tar gzip it to a safe location.

	tar --create --gzip --file=/home/myusername/backups/anchor.tgz  --directory=/path/to/my/site/httpdocs

# 0.7 and below -> 0.8

1.	Backup your files and database.

	![typical anchor folder](http://dl.dropbox.com/u/5264455/Screens/1ee7.png)

2.	Delete your old system folder

	![typical anchor folder](http://dl.dropbox.com/u/5264455/Screens/9cf2.png)

3.	Download the latest version and upload and overwrite any files that are needed.

	![typical anchor folder](http://dl.dropbox.com/u/5264455/Screens/e773.png)

	*If you have customised the default theme please rename it! or it will be over written.*

4.	Navigate to your site and append /upgrade to the url.

	![typical anchor folder](http://dl.dropbox.com/u/5264455/Screens/8ae2.png)

5.	Follow the instructions and that should be it.

	*After the upgrade is complete its recommened you delete the install and upgrade folders.*
