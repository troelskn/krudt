<?php
// You need to have simpletest in your include_path
if (realpath($_SERVER['PHP_SELF']) == __FILE__) {
  require_once 'simpletest/autorun.php';
}
set_include_path(
  PATH_SEPARATOR . get_include_path()
  . PATH_SEPARATOR . dirname(dirname(dirname(__FILE__)))."/lib");

require_once 'baselib.inc.php';

class TestOfGenerators extends UnitTestCase {
  function setUp() {
    $this->sandbox_dir = dirname(__FILE__) . '/sandbox';
    if (filesys()->is_dir($this->sandbox_dir)) {
      filesys()->rm_rf($this->sandbox_dir);
    }
    filesys()->mkdir($this->sandbox_dir);
    filesys()->chdir($this->sandbox_dir);
  }
  function test_components_generator_prints_usage() {
    $this->assertTrue(preg_match("/USAGE/", shell()->exec("../../../script/generate_components.php")));
  }
  function test_model_generator_prints_usage() {
    $this->assertTrue(preg_match("/USAGE/", shell()->exec("../../../script/generate_model.php")));
  }
  function test_model_generates_lib_file() {
    shell()->exec("../../../script/generate_model.php ninja");
    $this->assertTrue(filesys()->is_file($this->sandbox_dir . '/lib/ninjas.inc.php'));
  }
  function test_components_fails_without_a_model() {
    $this->assertTrue(preg_match("~Can't find model in lib/ninji.inc.php~", shell()->exec("../../../script/generate_components.php ninji")));
    $this->assertFalse(filesys()->is_file($this->sandbox_dir . '/lib/components/ninji/list.php'));
    $this->assertFalse(filesys()->is_file($this->sandbox_dir . '/lib/components/ninji/entry.php'));
  }
  function test_components_generates_lib_files() {
    shell()->exec("../../../script/generate_model.php ninja ninji slug:string first_name:string last_name:string created:datetime");
    shell()->exec("../../../script/generate_components.php ninji");
    $this->assertTrue(filesys()->is_file($this->sandbox_dir . '/lib/components/ninji/list.php'));
    $this->assertTrue(filesys()->is_file($this->sandbox_dir . '/lib/components/ninji/entry.php'));
  }
}

class TestOfModelGenerator extends UnitTestCase {
  function setUp() {
    $this->sandbox_dir = dirname(__FILE__) . '/sandbox';
    filesys()->rm_rf($this->sandbox_dir);
    filesys()->mkdir($this->sandbox_dir);
    filesys()->chdir($this->sandbox_dir);
  }
  function test_can_run_generator() {
    $this->assertTrue(preg_match("/USAGE/", shell()->exec("../../../script/generate_model.php")));
  }
  function test_generates_lib_file() {
    shell()->exec("../../../script/generate_model.php ninja");
    $this->assertTrue(filesys()->is_file($this->sandbox_dir . '/lib/ninjas.inc.php'));
  }
}

