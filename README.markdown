Krudt - databasedriven web framework based on konstrukt
==

This is an basic crud scaffolding generator for [Konstrukt](http://konstrukt.dk). The script will add some runtime ressources as well as two new commands under scripts: 

The two commands are:
    
    script/generate_model.php
    script/generate_components.php

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

To use, first install Konstrukt system wide.

    pear channel-discover pearhub.org
    pear install pearhub/konstrukt

Grab the installer and make it executable

    wget http://github.com/lsolesen/krudt/raw/master/krudt
    chmod +x krudt

Usage
--

You can now create a new project like this:

    krudt myapp

Get started by setting up the environment:

    cd myapp
    chmod 777 var
    
Edit the config/local.inc.php with the database credentials. Most people needs to have these lines:

    $factory->pdo_dsn = 'mysql:host=localhost;dbname=dbname';
    $factory->pdo_username = 'dbuser';
    $factory->pdo_password = 'dbpassword';


Use the generators:
    
    php script/generate_model.php task title:string content:blob created:datetime completed:datetime
    php script/generate_components.php tasks --slug=title

Now make sure that your database has been migrated to the new version.

    php script/migrate.php

For more info, see [Konstrukt](http://www.konstrukt.dk)

That's all.  
