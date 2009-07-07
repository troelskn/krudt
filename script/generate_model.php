#!/usr/bin/env php
<?php
set_include_path(
  PATH_SEPARATOR . get_include_path()
  . PATH_SEPARATOR . dirname(dirname(__FILE__))."/lib");

require_once 'baselib.inc.php';

function replace_names($php, $model_name, $model_plural_name) {
  $php = str_replace('contacts', $model_plural_name, $php);
  $php = str_replace('contact', $model_name, $php);
  $php = str_replace('Contacts', ucfirst($model_plural_name), $php);
  $php = str_replace('Contact', ucfirst($model_name), $php);
  return $php;
}

function replace_accessors($php, $model_fields) {
  $all = array();
  foreach ($model_fields as $field => $type) {
    $all[] = "  function $field() {
    return \$this->row['$field'];
  }";
  }
  return str_replace(
    "  function slug() {
    return \$this->row['slug'];
  }",
    implode("\n", $all), $php);
}

function replace_defaults($php, $model_fields) {
  $all = array("'id' => null");
  foreach ($model_fields as $field => $type) {
    $all[] = "'$field' => null";
  }
  return str_replace(
    "'id' => null, 'slug' => null",
    implode(", ", $all), $php);
}

$dir_generator_templates = (dirname(dirname(__FILE__)) . '/generator_templates');
$destination_root = getcwd();

$sql_types = array(
  'integer' => 'int',
  'string' => 'varchar(255)',
  'date' => 'date',
  'datetime' => 'datetime',
  'decimal' => 'decimal(6,2)',
  'blob' => 'blob',
  'boolean' => 'enum(0,1)');

$regular_args = array();
$model_fields = array();
foreach (console()->arguments_as_array() as $arg) {
  if (preg_match('~(.+):(.+)~', $arg, $matches)) {
    if (!in_array($matches[2], array_keys($sql_types))) {
      echo "Illegal field type: $matches[2]\n";
      exit -1;
    }
    $model_fields[$matches[1]] = $matches[2];
  } else {
    $regular_args[] = $arg;
  }
}

if (count($regular_args) == 0 || count($regular_args) > 2) {
  echo "USAGE: " . console()->script_filename() . " [OPTIONS] model_name [model_plural_name] [field_name:type ...]\n";
  echo "OPTIONS:\n";
  echo "  --dry  Simulate all changes.\n";
  echo "type can be one of:\n";
  echo "  " . implode(", ", array_keys($sql_types)) . "\n";
  exit -1;
}

$model_name = $regular_args[0];
if (count($regular_args) == 1) {
  $model_plural_name = preg_match('/s$/', $model_name) ? ($model_name . "es") : ($model_name . "s");
} else {
  $model_plural_name = $regular_args[1];
}
$model_plural_name = strtolower($model_plural_name);
$model_name = strtolower($model_name);
$file_name = $model_plural_name;

if (console()->option('dry')) {
  echo "Dry mode. No changes are actual.\n";
  filesys(new baselib_ReadonlyFilesys());
}
filesys()->enable_debug();
echo "Generating: model_name => $model_name, model_plural_name => $model_plural_name, model_fields => " . var_export($model_fields, true) . "\n";
filesys()->mkdir_p("$destination_root/lib");

$content = filesys()->get_contents($dir_generator_templates . "/lib/contacts.inc.php");
$content = replace_names($content, $model_name, $model_plural_name);
$content = replace_accessors($content, $model_fields);
$content = replace_defaults($content, $model_fields);
filesys()->put_contents("$destination_root/lib/$file_name.inc.php", $content);

// TODO generate ddl migration
