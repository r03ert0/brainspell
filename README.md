# brainspell
brainspell is a web platform to facilitate the creation of an open, human-curated, classification of the neuroimaging literature.

![](https://raw.githubusercontent.com/r03ert0/brainspell/master/images/brainspell.png)

# Running brainspell locally
* create a database using the sql script brainspell_db.sql
* create a base.php file by adapting base.php.example to contain the user/pass to your database
* create a virtual host for brainspell in localhost
* configure apache to redirect 404 errors to the script 404.php (used for URL rewriting)
