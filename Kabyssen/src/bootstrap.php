<?php
/**
 * Bootstrapping functions, essential and needed for Kabyssen to work together with some common helpers. 
 *
 */
 
/**
 * Default exception handler.
 *
 */
function myExceptionHandler($exception) {
  echo "Kabyssen: Uncaught exception: <p>" . $exception->getMessage() . "</p><pre>" . $exception->getTraceAsString(), "</pre>";
}
set_exception_handler('myExceptionHandler');
 
 
/**
 * Autoloader for classes.
 *
 */
function myAutoloader($class) {
  $path = KABYSSEN_INSTALL_PATH . "/kabyssen/src/{$class}/{$class}.php";
  if(is_file($path)) {
    include($path);
  }
  else {
    throw new Exception("Classfile '{$class}' does not exist.");
  }
}
spl_autoload_register('myAutoloader');

function dump($array) {
  echo "<pre>" . htmlentities(print_r($array, 1)) . "</pre>";
}
