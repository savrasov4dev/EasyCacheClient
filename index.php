<?php

use App\CacheConnector;
use App\EasyCache;

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/config.php";

$connector = new CacheConnector(CACHE_SERVICE_SOCKET);
$cache     = new EasyCache($connector, MAX_READ_LENGTH);

$cache->set("Test 0", json_encode(["Knock-knock\n", "Knock-knock-knock\n",]));
$cache->set("Test 1", "knock 1", 60);
$cache->set("Test 2", "knock 2", 1);
$cache->set("Test 3", "knock 3", 10);
$cache->set("Test 4", "knock 4", 1);

echo $cache->get("Test 0");

