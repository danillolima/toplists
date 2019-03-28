<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class SignupController extends ControllerBase
{
    public function indexAction()
    {

    }

		public function registerAction(){
			$user = new User();		
    	$usern = $this->request->getPost("user");
			$name = $this->request->getPost("name");
			$surname = $this->request->getPost("surname");
      $pass = $this->request->getPost("pass");
			$email = $this->request->getPost("email");
			$user->is_mod = 0;
			$user->username = $usern;
      $user->name = $name;
    	$user->pass = $this->security->hash($pass);
			$user->email = $email;
			$user->surname = $surname;
      
			try {
				$success = $user->save();		
				echo "Obrigado por se cadastrar!";
			} catch (\PDOException $e) {
			//	echo  $e->getMessage();
				echo "Ops, erros foram encontrados.";
				if($e->getCode() == 23000){
					echo "Usuário já existe";
				}

				$messages = $user->getMessages();
				foreach ($messages as $message) {
    			echo $message->getMessage(), "<br/>";
    		}
			}
	}
}
