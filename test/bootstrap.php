<?php

require_once __DIR__.'/Autoloader.php';
require_once __DIR__ . '/../vendor/autoload.php';
$appDir = __DIR__ . '/../src';
$appLoader = new Autoloader('Rueckgrat', $appDir);
$appLoader->register();
