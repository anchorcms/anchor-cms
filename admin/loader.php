<?php
include('../core/paths.php');
include($path . 'config/database.php');

$db = new PDO('mysql:host=' . $host . ';dbname=' . $name, $user, $pass, array(PDO::ATTR_PERSISTENT => true));

include($path . 'core/class.php');
include($path . '/core/connect.php');
include($path . '/core/themes.php');
include($path . '/core/stats.php');
include($path . '/config/settings.php');