This is an add on (or plugin, if you prefer) for Konstrukt.
You should check the entire repository out into the root of a standard Konstrukt project.
This will add some runtime resources as well as two new commands under script/
The two commands are:
  script/generate_model.php
  script/generate_components.php

Together, these gives you a basic crud scaffolding generator.
You start by generating a model (A database table gateway). After that, you can create standard components for this.

Krudt depends on pdoext for database access.
