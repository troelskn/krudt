Krudt - databasedriven web framework based on konstrukt
==

This is an basic crud scaffolding generator for [Konstrukt](http://konstrukt.dk). The script will add some runtime ressources as well as two new commands under scripts: 

The two commands are:
    
    script/generate_model
    script/generate_components

You start by generating a model (A database table gateway). After that, you can create standard components for this.

Dependencies
--

* PHP Version > 5
* SVN and GIT
* Konstrukt
* pdoext
* bucket

Installation
--

Krudt is designed to pull in all its dependencies as local copies under under `thirdparty/`. You might want to remopve these and use system-wide installs (For example, using the pear installer), but this is a good starting point, as it provides a self-contained system.

Start by running `script/install`. This will init and pull in all submodules/svn-externals into `thirdparty`.

Usage
--

Edit the config/local.inc.php with the database credentials. Most people needs to have these lines:

    $factory->pdo_dsn = 'mysql:host=localhost;dbname=dbname';
    $factory->pdo_username = 'dbuser';
    $factory->pdo_password = 'dbpassword';

Note that you need to manually create a database to match these details. From the commandline, you can simply run:

    mysql -u dbuser -p -e 'create database dbname'

Use the generators to create a model component:
    
    script/generate_model task title:string content:blob created:datetime completed:datetime

Make sure that your database has been migrated to the new version.

    script/migrate

You'd want an interface to go with this. Try running:

    script/generate_components tasks --slug=title

And you can now see a standard crud at `http://localhost/path/to/your/app/www/tasks`

For more info, see [Konstrukt](http://www.konstrukt.dk)

That's all.  
