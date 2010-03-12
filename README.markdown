Krudt - databasedriven web application framework
==

Krudt is a web application framework based on [Konstrukt](http://konstrukt.dk). It provides a complete application environment with various helpers for getting started.

Dependencies
--

* PHP Version > 5
* SVN and GIT

Getting started
--

To create a new application, make a clone of krudt:

    git clone git://github.com/troelskn/krudt.git myapp
    cd myapp
    script/install

Krudt is designed to pull in all its dependencies as local copies under under `thirdparty/`. You might want to remove these and use system-wide installs (For example, using the pear installer), but this is a good starting point, as it provides a self-contained system.

Edit the config/development.inc.php with the database credentials. Most people needs to have these lines:

    $factory->pdo_dsn = 'mysql:host=localhost;dbname=dbname';
    $factory->pdo_username = 'dbuser';
    $factory->pdo_password = 'dbpassword';

Note that you need to manually create a database to match these details. From the commandline, you can simply run:

    mysql -u dbuser -p -e 'create database dbname'

You need to configure your web server to serve from the `www` folder of your project. Usually `/var/www/` is the default web root. If this is the case, you can simply create a symlink within that directory to point to your application:

    cd /var/www && ln -s /path/to/myapp/www myapp

And your application will run from `http://localhost/myapp/`

Usage
---

You can now use the generators to create a model component:

    script/generate_model task title:string content:blob created:datetime completed:datetime

Make sure that your database has been migrated to the new version.

    script/migrate

You'd want an interface to go with this. Try running:

    script/generate_components tasks --slug=title

And you can now see a standard crud interface at `http://localhost/myapp/tasks`

Getting your app on github
---

Your app starts out as a clone of krudt. If you want to push the app to a hosting site, such as guthub, you'll have to change the *remote* for your repository. You can do that like this:

    git remote rename origin krudt
    git remote add origin git://github.com/path/to/your/repo.git origin

You can now use the familiar commands `git push origin master` to push to your repository.

If you ever need to pull in updates to krudt, you can do so with `git pull krudt master`.

---

That's all. For more info, see [Konstrukt](http://www.konstrukt.dk)

