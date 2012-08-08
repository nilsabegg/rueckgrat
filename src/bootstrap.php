<?php
require_once ('../config/routing.php');
require_once '../vendor/rueckgrat/src/Rueckgrat/Autoloader.php';
require_once '../vendor/autoload.php';
$classLoader = new Rueckgrat\Autoloader('Rueckgrat', __DIR__ . '/../vendor/rueckgrat/src');
$classLoader->register();
session_start();
session_set_cookie_params(60*60*24*30);
$config = parse_ini_file('../config/config.ini', true);
$pimple = new Rueckgrat\DependencyInjectionContainer($config);
$bootloader = new Rueckgrat\Bootloader($pimple);
//$bootloader->removeMagicQuotes();
//$bootloader->unregisterGlobals();
$bootloader->callHook();