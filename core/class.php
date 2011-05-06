<?php
function __autoload($class) {
  global $path;
  include($path . 'core/' . strtolower($class) . '.php');
}