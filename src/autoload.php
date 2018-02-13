<?php 

require __DIR__.'/../vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
	'Memory' 	=> __DIR__,
	'Symfony' 	=> __DIR__.'/../vendor'
));

$loader->register();