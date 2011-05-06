<?php
function __autoload($class) {
  global $path;
  include($path . 'models/' . strtolower($class) . '.php');
}