<?php
use Phalcon\Http\Request;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;

class ControllerBase extends Controller{
	public $site;

  public function beforeExecuteRoute(Dispatcher $dispatcher){

	    $allow =  array (
                "private" => array(
                "list" => array(
                    'create' => 1,
                    'insert' => 1
	        ),
                "profile" => array(
                        'index' => 1,
                   )
	        )
	    );

	    $controllerName = strtolower($dispatcher->getControllerName());

	    $actionName = $dispatcher->getActionName();
			$identity = $this->auth->getIdentity();
	    $this->view->identity = $identity;

	    $this->view->action = $allow;

			// dados globais do site
			$site = array(
				"title" => 'Top Listas',
				"url" => 'http://127.0.0.1'
			);
			$this->view->site = $site;

	    if(isset($allow["private"]["$controllerName"]["$actionName"]) ){
	      if(!is_array($identity)){
	        $dispatcher->forward([
	                    'controller' => 'login',
	                    'action' => 'index'
	                    ]);
	        return false;
	      }
			}
			$menu = Category::find();
			$this->view->menu = $menu;

			$this->view->listasDestaque = Lists::find([
					'limit' => 10
				]);
		}
		public function cleanURL($url){
    	setlocale(LC_ALL, 'pt_BR.UTF8');
    	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $url);
    	$clean = preg_replace("/[^a-zA-Z0-9\/_| -]/", '', $clean);
    	$clean = strtolower(trim($clean, '-'));
			$clean = preg_replace("/[\/_| -]+/", '-', $clean);
    	return $clean;
    }
}
