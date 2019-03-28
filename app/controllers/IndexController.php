<?php


use Phalcon\Mvc\Controller;

class IndexController extends ControllerBase{
    public function indexAction(){
		
   		$posts = Lists::find([
   				'limit' => 10
   		]);
   		$this->view->posts = $posts;
    }
}

