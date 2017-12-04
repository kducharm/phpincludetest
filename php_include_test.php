<?php
/**
 * @file
 * PHP Include/Require Performance Testing.
 */

class PhpIncludeTest {
  // Functions to test.
  private $phpFunctions = array('require_once', 'include', 'require');

  private $numTestsPerFunctions = 10;
  private $testsDestDir = './testfiles';

  /**
   * Run Test Suite.
   */
  public function runTests($env = '', $memory_test = FALSE) {
    $results_array = array();
    // Number of test PHP files to create for inclusion.
    $num_files = $this->numTestsPerFunctions * count($this->phpFunctions);

    $function_index = 1;

    echo "Creating PHP Test Files...\n";
    self::createTestFiles($num_files);

    foreach ($this->phpFunctions as $php_function) {
      echo "-----------------------\n";
      echo "  Testing {$php_function}\n";
      echo "-----------------------\n";

      // Run each PHP function with a test file, does not reuse any test file.
      $loop_start = microtime(TRUE);
      for ($testfile_index = (($function_index - 1) * $this->numTestsPerFunctions) + 1; $testfile_index <= ($function_index * $this->numTestsPerFunctions); $testfile_index++) {
        $start = microtime(TRUE);
        $function_string = "$php_function \"./testfiles/testfile{$testfile_index}.php\";";
        eval($function_string);
        $stop = microtime(TRUE);
        echo ": " . ($stop - $start) . "\n";
      }
      $loop_end = microtime(TRUE);
      $results_array[$php_function] = ($loop_end - $loop_start);
      $function_index++;
    }
    echo "\n";
    foreach ($results_array as $php_function => $time_elapsed) {
      echo "({$env}) " . ucfirst($php_function) . " total time: {$time_elapsed}\n";
    }
  }

  /**
   * Create PHP test files for inclusion.
   *
   * @param int $num_files
   *   Number of test files to create.
   */
  private function createTestFiles($num_files) {
    // Create dir if doesn't exist.
    if (!is_dir($this->testsDestDir)) {
      mkdir($this->testsDestDir);
    }
    $files = glob($this->testsDestDir . '/*');
    foreach ($files as $file) {
      if (is_file($file)) {
        unlink($file);
      }
    }
    $test_code = '<?php
echo "... " . __FILE__;
if ($memory_test) {
  echo "-M";
  for($i = 0; $i < 100000; $i++){
    $j[] = $i;
  }
}
';

    for ($testfile_index = 1; $testfile_index <= $num_files; $testfile_index++) {
      file_put_contents($this->testsDestDir . "/testfile{$testfile_index}.php", $test_code);
    }
  }
}

// Command line usage:
// Set higher precision for microtime.

if (count($argv) < 2) {
  echo "Usage: php_include_test.php [options] <environment>\n";
  echo " <environment>: String representing where test was run from for report\n";
  echo " -m: Run memory test + include test\n";
  exit;
}
$options = getopt('mo');

if (count($options)) {
  $env = $argv[2];
}
else {
  $env = $argv[1];
}

$ini_settings = ini_get_all();
echo "PHP Opcache Enabled: " . $ini_settings['opcache.enable_cli']['global_value'] . "\n";
ini_set("precision", 16);
ini_set("memory_limit", "256M");
$test_obj = new PhpIncludeTest();
$test_obj->runTests($env, isset($options['m']));
