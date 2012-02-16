<?php

// classes loader
require_once __DIR__.'/vendor/mondator/vendor/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Mandango\Behavior' => __DIR__.'/vendor/mandango-behaviors/src',
    'Mandango\Mondator' => __DIR__.'/vendor/mondator/src',
    'Mandango'          => __DIR__.'/vendor/mandango/src',
));
$loader->register();

require_once __DIR__.'/vendor/mondator/vendor/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

Autoloader::add_namespaces(array(
	'FuelMandango' => PKGPATH.'/fuel-mandango/',
));

Autoloader::add_classes(array(
	'FuelMandango\Mandango' => PKGPATH.'/fuel-mandango/classes/mandango.php',
));

