<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class SignupController extends ControllerBase
{
    public function indexAction()
    {
		// Tudo aqui fora é o GET
		// $this->view->dado = "teste";
		// Refatoração
		if($this->request->isPost()){

			$filter = new \Phalcon\Filter();
			
			$name = $this->request->getPost("name");
			$surname = $this->request->getPost("surname");
			$usern = $this->request->getPost("user");
			$pass = $this->request->getPost("pass");
			$email = $this->request->getPost("email", "email");

			$usern = $filter->sanitize($usern, "trim");
			
			if($email === false){
				$messages[] = "E-mail inválido.";
			}
			
			$user = new User();		

			$user->is_mod = 0;
			$user->username = $usern;
			$user->name = $name;
			$user->pass = $this->security->hash($pass);
			$user->email = $email;
			$user->surname = $surname;
			
			try {
				if($user->save()){
					$messages[] = "Cadastrado com sucesso.";
				}
				else
					$messages[] = "Erro tentando salvar os dados";
			} catch (\PDOException $e) {
				if($e->getCode() == 23000){
					$messages[] = "Erro tentando salvar os dados";
				}

				//$messages = $user->getMessages();
				//foreach ($messages as $message) {
				//echo $message->getMessage(), "<br/>";
			//}
			}
			/* Valida e salva se der
			Se a request for AJAX retornar um json de resposta */
			if($this->request->isAjax()){
				$this->view->disable();
				$this->response->setContent(json_encode($messages));
		        return $this->response;
			}
			else{
			// Se for um POST puro redirecionar para uma página coma a resposta
			
			$this->view->messages =	$messages;
			}
		}
    }
}
