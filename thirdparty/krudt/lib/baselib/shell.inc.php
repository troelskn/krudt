<?php
function shell($replacement = null) {
  static $instance = null;
  if ($replacement) {
    $instance = $replacement;
  }
  if (!$instance) {
    $instance = new baselib_Shell();
  }
  return $instance;
}

class baselib_Shell {
  protected $debug = false;
  protected $dry = false;

  function enableDebug() {
    $this->debug = true;
  }

  function disableDebug() {
    $this->debug = false;
  }

  function enableDry() {
    $this->dry = true;
  }

  function disableDry() {
    $this->dry = false;
  }

  /**
   * USAGE:
   *   exec('echo', 'Hello');
   * OR (like execBound):
   *   exec('foo -m :message :file', array(':message' => 'Hello', ':file' => 'test.txt'));
   */
  function exec($command /*, [...]*/) {
    $func_args = func_get_args();
    array_shift($func_args);
    if (count($func_args) === 1 && is_array($func_args[0])) {
      return $this->execBound($command, $func_args[0]);
    }
    $func_args = array_map('escapeshellarg', $func_args);
    array_unshift($func_args, $command);
    return $this->execRaw(implode(" ", $func_args));
  }

  /**
   * USAGE:
   *   execBound('foo -m :message :file', array(':message' => 'Hello', ':file' => 'test.txt'));
   */
  function execBound($command, $arguments = array()) {
    $pattern = '/('.implode('|', array_map('preg_quote', array_keys($arguments))).')/';
    $tokens = array();
    foreach (preg_split($pattern, $command, -1, PREG_SPLIT_DELIM_CAPTURE) as $token) {
      if (isset($arguments[$token])) {
        $tokens[] = escapeshellarg($arguments[$token]);
      } else {
        $tokens[] = $token;
      }
    }
    return $this->execRaw(implode('', $tokens));
  }

  function execRaw($command) {
    if ($this->debug) {
      echo "[Shell] $command\n";
      if ($this->dry) {
        echo "*Dry mode*\n";
        return "";
      } else {
        $output = shell_exec($command);
        echo $output . "\n";
        return $output;
      }

    }
    if ($this->dry) {
      echo "*Dry mode*\n";
      return "";
    }
    return shell_exec($command);
  }
}

class baselib_MockShell extends baselib_Shell {
  protected $mock = array();
  protected $commands = array();

  function mock($regexp, $output) {
    $this->mock[$regexp] = $output;
  }

  function commandsInvoked() {
    return $this->commands;
  }

  function execRaw($command) {
    $this->commands[] = $command;
    foreach ($this->mock as $regexp => $stub) {
      if (preg_match($regexp, $command)) {
        if ($this->debug) {
          echo "[MockShell] $command\n";
          echo "(mock) $stub\n";
        }
        return $stub;
      }
    }
    throw new Exception("No mock found for input: '$command'");
  }
}
