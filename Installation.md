<b>Requirements :</b>

PHP 5 and higher

Aphache Rewide Module

Aphache CGI Module

ORM for the PHP PDO extension

<b>Installation :</b>

Unzip the package.

Upload the PHP Faces folders and files to your server. Normally the index.php file will be at your root.

Open the index.php file with a text editor and set your base URL.

Dispatcher::dispatch("applications".DS."demos","Welcome","http://yourhost.com/");

Dispatcher Parameters

1- Your current application directory

2- Beginning of your current application controller class

3-Your application's base URL


Open the application/config.php file with a text editor and edit your configuration

define("DEFAULT\_APPLICATION","demos"); //your default application name

define("DB\_CONNECTION\_STRING","mysql:host=localhost;dbname=myorm");// Your database host name and your database name