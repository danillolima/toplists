<?php
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\Router;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Db\Adapter\Pdo\Factory;
use Phalcon\Session\Adapter\Files as Session;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Http\Response;
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Flash\Direct as FlashDirect;
use Phalcon\Flash\Session as FlashSession;


// Define constantes referentes aos diretórios
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

require_once (APP_PATH . '/library/Auth/Auth.php');
require_once (APP_PATH . '/library/Utils/BasicValidation.php');

// Registra um autoloader
$loader = new Loader();
// Cria um injetor de dependências
$di = new FactoryDefault();
//Diretórios da aplicação
$loader->registerDirs([
	'controllersDir' => APP_PATH . '/controllers/',
	'modelsDir' => APP_PATH . '/models/',
	'viewsDir'       => APP_PATH . '/views/',
	'libraryDir'     => APP_PATH . '/library/'		
]);

$di->set(
	'db',
	function () {
		return new DbAdapter([
			'host'     => '127.0.0.1',
			'username' => 'root',
			'password' => 'root',
			'dbname'   => 'toplistas',
			'charset'  => 'utf8',
			'options' => [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]
		]);
	}
);
	

$di->setShared(
    'db', 
    function () {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(
			[
				'host'     => 'localhost',
				'username' => 'root',
				'password' => 'root',
				'dbname'   => 'toplistas',
				'charset' => 'utf8',
				'options'  => [
					PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
					PDO::ATTR_CASE               => PDO::CASE_LOWER,
				]
			]
		);
        return $connection;
    }
);

$loader->register();
//Seta uma sessão compartilha
$di->setShared(
		'session',
		function () {
			$session = new Session();
			$session->start();
			return $session;
		}
);


$di->set(
    'flash',
    function () {
        return new FlashDirect();
    }
);

$di->setShared(
	'modelsMetadata',
	function() {
   		return new \Phalcon\Mvc\Model\MetaData\Files([
        'lifetime' => 86400,
        'metaDataDir' => APP_PATH . '/cache/'	 . 'models-metadata/'
    ]);
});

$di->set(
	'acl',
	function () {
		$acl = 	new Acl();
		$privateResources = $this->getShared('auth-identity');
	}
);
//https://github.com/phalcon/vokuro/blob/master/app/library/Auth/Auth.php
//https://github.com/phalcon/vokuro/blob/master/app/controllers/ControllerBase.php
//https://github.com/phalcon/vokuro/blob/master/app/config/services.php
//https://github.com/phalcon/vokuro/blob/master/app/config/privateResources.php

// Seta um componente de View
$di->set(
		'view',
		function () {
			$view = new View();
			//$view->setViewsDir(APP_PATH . '/views/');
			return $view;
		}
);

// Setup a base URI so that all generated URIs include the "tutorial" folder
$di->set(
		'url',
		function () {
			$url = new UrlProvider();
			$url->setBaseUri('/');
			return $url;
		}
);

// Register Volt as a service
$di->set(
    "voltService",
    function ($view, $di) {
        $volt = new Volt($view, $di);
        $volt->setOptions(
            [
                "compiledPath"      => "../app/cache/volt/",
                "compiledExtension" => ".compiled",
            ]
        );
        return $volt;
    }
);


// Register Volt as template engine
$di->set(
    "view",
    function () {
        $view = new View();
        $view->setViewsDir("../app/views/");
        $view->registerEngines(
            [
                ".volt" => "voltService",
            ]
        );
        return $view;
    }
);

$di->set(
    'auth',
    function () {
        return new Auth();
    },
    true
);

$loader->registerNamespaces(
    [
	   'TopListas\Lib'    => APP_PATH . '/library/',
	   'TopListas\Lib\Utils' => APP_PATH . '/library/Utils',
    ]
);



$di->set(
	'router',
	function () {
		$router = new Router();

		$router->add(
			'/criar-lista',
			[
				'controller' => 'list',
				'action'     => 'create',
			])->setName('list-create');

		$router->add(
			'/criar-lista/insere',
			[
				'controller' => 'list',
				'action'     => 'insertList',
			])->setName('list-create-post');
			
		$router->add(
			'/l/{category}/{title}/:params',
			[
				'controller' => 'list',
				'action'     => 'show',
				'params' => 3,
			])->setName('show-list');
		
		$router->add(
			'/l',
			[
				'controller' => 'list',
			])->setName('list-controller');
	
		

		$router->add(
			'/l/{params}',
			[
				'controller' => 'list',
				'action'=> 1
			])->setName('list-controller');

		$router->add(
			'/profile/{username}/:params',
			[
				'controller' => 'profile',
				'action'     => 'interface',
				'params'	=> 2,
			])->setName('edit-profile');
			
		$router->add(
			'/entrar/validar',
			[
				'controller' => 'login',
				'action'     => 'verify',
			])->setName('login-verify');
		
		$router->add(
			'/cadastrar',
			[
				'controller' => 'signup',
			]
		)->setName('signup');

		$router->add(
			'/cadastrar/salvar',
			[
				'controller' => 'signup',
				'action'     => 'register',
			])->setName('signup-register');	

		$router->add(
			'/profile/{username}',
			[
				'controller' => 'profile',
				'action'     => 'show',
			])->setName('show-profile-whatever');

		$router->add(
			'/profile',
			[
				'controller' => 'profile',
			])->setName('show-profile-logged');

		$router->add(
			'/i/:action/:params',
			[
				'controller' => 'item',
				'action'		=> 1,
				'params' => 2,
			])->setName('item');

		$router->add(
    		'/i/:params',
			[
				'controller' => 'item',
				'action' => 'show',
				'params' => 1,
			])->setName('item');

		$router->add(
			'/i',
			[
				'controller' => 'item',
			])->setName('item');
		
		$router->add(
			'/category/:params',
			[
				'controller' => 'category',
				'action' => 'show',
				'params' => 1,
			])->setName('category');

		$router->add(
			'/category',
			[
				'controller' => 'category',	
			])->setName('category');

		$router->add(
			'/api/lists/:action/:params',
			[
				'controller' => 'list',
				'action' => 1,
				'params' => 2
			])->setName('api');

			$router->removeExtraSlashes(true);
			$router->handle();
			return $router;
		}
);

$application = new Application($di);

try {
	// Handle the request
	$response = $application->handle();
	$response->send();
} catch (\Exception $e) {
	echo 'Exception: ', $e->getMessage();
}

/*
// Configuração da conexão com o banco de dados

			$router->add(
				'/l/{page}',
				[
					'controller' => 'list',
					'action' => 'all',
					'params' => 2,
				]
			)
			->setName('list-methods');

*/