<?php
foreach (scandir(dirname(dirname(__FILE__)).'/thirdparty') as $plugin) {
  if (substr($plugin, 0, 1) !== '.') {
    set_include_path(
      get_include_path()
      .PATH_SEPARATOR.dirname(dirname(__FILE__)).'/thirdparty/'.$plugin.'/lib');
  }
}

// Put default application configuration in this file.
// Individual sites (servers) can override it.
require_once 'applicationfactory.php';
require_once 'bucket.inc.php';
require_once 'pdoext.inc.php';
require_once 'pdoext/connection.inc.php';
require_once 'pdoext/query.inc.php';
require_once 'pdoext/tablegateway.php';
require_once 'thirdparty/krudt/lib/viewhelpers.inc.php';
date_default_timezone_set('Europe/Paris');

function create_container() {
  $factory = new ApplicationFactory();
  $container = new bucket_Container($factory);
  $container->registerImplementation('pdo', 'pdoext_Connection');
  $factory->template_dir = realpath(dirname(__FILE__) . '/../templates');
  $factory->pdo_dsn = 'mysql:host=localhost;dbname=dbname';
  $factory->pdo_username = 'user';
  $factory->pdo_password = 'password';
  return $container;
}
