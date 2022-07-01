<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// LARACODE

use Illuminate\Database\Capsule\Manager as Capsule;

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => getenv('DB_HOST'),
	'username' => getenv('DB_USERNAME'),
	'password' => getenv('DB_PASSWORD'),
	'database' => getenv('DB_DATABASE'),
	'dbdriver' => getenv('DB_DRIVER'),
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$capsule = new Capsule;

$capsule->addConnection(
array(
'driver'    => 'mysql',
'host'      => $db['default']['hostname'],
'database'  => $db['default']['database'],
'username'  => $db['default']['username'],
'password'  => $db['default']['password'],
'charset'   => $db['default']['char_set'],
'collation' => $db['default']['dbcollat'],
'prefix'    => $db['default']['dbprefix'],
),
"default"
);

$capsule->setAsGlobal();
$capsule->bootEloquent();