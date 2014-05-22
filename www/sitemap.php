<?php
define("_CRONJOB_",true);

require_once 'index.php';

$map = new Core_Crone_Sitemap();
$map->run();