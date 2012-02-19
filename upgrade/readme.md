
## Migrating to the latest version of Anchor

1.	**Backup your database and files!**

	Here is an example for backup up on most servers via ssh using mysql dump:

		mysqldump --compact --quick --user myusername --password=mypassword --host=localhost anchorcms > /path/to/my/site/httpdocs/db.sql

	lets tar gzip it to a safe location.

		tar --create --gzip --file=/home/myusername/backups/anchor.tgz  --directory=/path/to/my/site/httpdocs

2.	Download the latest version.

3.	Extract, upload and overwrite any files that are needed.

	*If you have customised the default theme please rename it! or it will be over written.*

4.	Navigate to your site and append /upgrade to the url.

	*Visiting the site in its current sate might show errors or a 404 page.*

5.	Follow the instructions and that should be it.

	*After the upgrade is complete its recommened you delete the install and upgrade folders.*

