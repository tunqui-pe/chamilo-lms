## Upgrade to Chamilo v2 

## From 1.11.x

2.x is a major version. It contains a series of new features, that
also mean a series of new database changes in regards with versions 1.x. As 
such, it is necessary to go through an upgrade procedure when upgrading from 
1.10.x to 1.11.x.

The upgrade procedure is relatively straightforward. If you have a 1.11.x 
initially installed with Git, here are the steps you should follow 
(considering you are already inside the Chamilo folder):
```
git fetch --all
git checkout origin master
```

Then load the Chamilo URL in your browser, adding "web/install.php" and 
follow the upgrade instructions. Select the "Upgrade from 1.x" button to 
proceed.

If you have previously updated database rows manually, you might face issue with
FOREIGN KEYS during the upgrade process. Please make sure your database is
consistent before upgrading. This usually means making sure that you have to delete
rows from tables referring to rows which have been deleted from the user or access_url tables.
Typically:
<pre>
    DELETE FROM access_url_rel_course WHERE access_url_id NOT IN (SELECT id FROM access_url);
</pre>

### From non-Git Chamilo 1.11 ###

In the *very unlikely* case of upgrading a "normal" Chamilo 1.x installation (done with the downloadable zip package) to a Git-based installation, 
make sure you delete the contents of a few folders first. 
These folders are re-generated later by the ```composer update``` command. 
This is likely to increase the downtime of your Chamilo portal of a few additional minutes (plan for 10 minutes on a reasonnable internet connection).

```
rm composer.lock
rm -rf vendor/*
```

## Updating code

To update your code with the latest developments in the master branch, go to
your Chamilo folder and type:
```
git pull origin master
```
If you have made customizations to your code before the update, you will have
two options:
- abandon your changes (use "git stash" to do that)
- commit your changes locally and merge (use "git commit" and then "git pull")

You are supposed to have a reasonable understanding of Git in order to
use Chamilo as a developer, so if you feel lost, please check the Git manual
first: http://git-scm.com/documentation

## Updating your database from new code

Since the 2015-05-27, Chamilo offers the possibility to make partial database
upgrades through Doctrine migrations.

To update your database to the latest version, go to your Chamilo root folder
and type
```
php bin/doctrine.php migrations:migrate --configuration=app/config/migrations.yml
```

If you want to proceed with a single migration "step" (the steps reside in
src/Chamilo/CoreBundle/Migrations/Schema/V110/), then check the datetime of the
version and type the following (assuming you want to execute Version20150527120703)
```
php bin/doctrine.php migrations:execute 20150527120703 --up --configuration=app/config/migrations.yml
```

You can also print the differences between your database and what it should be by issuing the following command from the Chamilo base folder:
```
php bin/doctrine.php orm:schema-tool:update --dump-sql
```
