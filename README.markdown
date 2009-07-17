Krudt - databasedriven web framework based on konstrukt
---

To use, first install Konstrukt system wide.

    sudo pear install http://konstrukt.googlecode.com/files/konstrukt-2.1.1.tgz

grab install.sh and make it executable

    wget http://github.com/troelskn/krudt/raw/1f80548cdda3ebc8fb299fddb5bfe402f69de1ba/install.sh
    chmod +x install.sh

You can now create a new project like this:

    install.sh myapp

Get started by using the generators:

    cd myapp
    script/generate_model.php task title:string content:blob created:datetime completed:datetime
    script/generate_components.php tasks --slug=title

For more info, see [Konstrukt](http://www.konstrukt.dk)

That's all.  
