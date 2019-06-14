<?php
use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;

class LoginController extends ControllerBase{
	public function indexAction(){

	}

	public function verifyAction(){
		if ($this->request->isPost()) {	
			$status = $this->auth->check([
					  'user' => $this->request->getPost('user'),
					  'pass' => $this->request->getPost('pass')
					]);
			if($status == 0){
				return $this->response->redirect('/profile/' . $credentials['user']);
			}
			else{
				echo "Dados de login inválidos";
			}
		}
	}
}

