<?php

use Phalcon\Mvc\Controller;

class ProfileController extends ControllerBase{

	public function initialize(){
  		$this->view->pick('profile/index');
		$this->view->params = "logged";
	}

	public function indexAction(){
    	$this->view->params = "logged";
	}

	public function interfaceAction($params){
		$this->view->params = $params;
		$dados = $this->auth->getIdentity();
			
		$this->view->userLists = Lists::find(['id_user = :id:',
											'bind' => [
												'id' => $dados["id"],
											]]);
	}

	public function showAction( $params){
		$this->view->params = $params[0];
	}
}
