Krudt - databasedriven web framework based on konstrukt
---

To use, first install Konstrukt system wide.

    sudo pear install http://konstrukt.googlecode.com/files/konstrukt-2.1.1.tgz

grab the installer and make it executable

    wget http://github.com/troelskn/krudt/raw/c6592010cb8257ef3733e8b35418a12419f6f9c4/krudt
    chmod +x krudt

You can now create a new project like this:

    krudt myapp

Get started by using the generators:

    cd myapp
    script/generate_model.php task title:string content:blob created:datetime completed:datetime
    script/generate_components.php tasks --slug=title

For more info, see [Konstrukt](http://www.konstrukt.dk)

That's all.  
