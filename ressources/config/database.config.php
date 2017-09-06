<?php

use Idiorm\Silex\Provider\IdiormServiceProvider;

#1 : Connexion BDD
define('DBHOST',     'localhost');
define('DBNAME',     'hardware');
define('DBUSERNAME', 'root');
define('DBPASSWORD', 'mysql');

#2 : Doctrine DBAL
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'    => 'pdo_mysql',
        'host'      => DBHOST,
        'dbname'    => DBNAME,
        'user'      => DBUSERNAME,
        'password'  => DBPASSWORD
    ),
));

#3 : Idiorm ORM
$app->register(new IdiormServiceProvider(), array(
    'idiorm.db.options' => array(
        'connection_string' => 'mysql:host='.DBHOST.';dbname='.DBNAME,
        'username' => DBUSERNAME,
        'password' => DBPASSWORD,
        'id_column_overrides' => array()
    )
));




#4.1 : Récupération des catégories
$app['index_categories'] = function() use($app) {
    return $app['db']->fetchAll('SELECT DISTINCT productTypeName FROM articles');

};



#4.1 : Récupération des constrcuteur
$app['index_categories'] = function() use($app) {
    return $app['db']->fetchAll('SELECT DISTINCT constructeur FROM articles');

};



#4.1 : Récupération des models
$app['index_categories'] = function() use($app) {
    return $app['db']->fetchAll('SELECT DISTINCT model FROM articles');

};
