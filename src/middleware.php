<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

require_once 'includes/Assertion.php';
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();