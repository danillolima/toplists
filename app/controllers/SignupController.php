<?php
use TopListas\Lib\Utils\BasicValidation;
use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class SignupController extends ControllerBase
{
    public function indexAction()
    {

		if($this->request->isPost()){

			$filter = new \Phalcon\Filter();

			$name = $this->request->getPost("name");
			$surname = $this->request->getPost("surname");
			$usern = $this->request->getPost("user");
			$pass = $this->request->getPost("pass");
			$email = $this->request->getPost('email', 'email');

			$usern = $filter->sanitize($usern, "trim");

			$validation = new BasicValidation();

			$erros =  $validation->validate($_POST);

			if(count($erros)){
				foreach ($erros as $message) {
					$messages[$message->getField()][$message->getType()] = $message->getMessage();
				}
			}
			else{
				$user = new User();		
				$user->is_mod = 0;
				$user->username = $usern;
				$user->name = $name;
				$user->pass = $this->security->hash($pass);
				$user->email = $email;
				$user->surname = $surname;
				
				try {
					if($user->create()){
						$messages["sucesso"]["form"] = "Cadastrado com sucesso.";
					}
					else{
						$aux = $user->getMessages();
						foreach ($aux as $message)
							$messages["erros"]["geral"] = $message; 
					}
				} catch (\PDOException $e) {
					if($e->getCode() == 23000){
						$messages["erro"]["usuario"] = "Usuário já cadastrado";
					}
				}
			}
		
			/* Valida e salva se der
			Se a request for AJAX retornar um json de resposta */
			if($this->request->isAjax()){
				$this->view->disable();
				$this->response->setContent(json_encode($messages));
		        return $this->response;
			}
			else{		
				$this->view->messages =	$messages;
			}
		}
    }
}
