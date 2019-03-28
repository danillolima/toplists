<?php

use Phalcon\Mvc\User\Component;

use Phalcon\Http\Request;
use Phalcon\Http\Response;


class Auth extends Component{

  public function check($credentials){

    $userAuth = new User();
    $response = new Response();
    $userAuth = User::findFirstByUsername($credentials['user']);

    if($userAuth){
      if($this->security->checkhash($credentials['pass'], $userAuth->pass)){
        $this->session->set('auth-identity',[
          'id' => $userAuth->id,
          'user' => $userAuth->username,
        ]);
        return 0;
      }
      else {
        //Senha errada
       return 10;
      }
    }
    else{
      //Usuário não existe
      return 20;
    }
}

  public function getIdentity(){
    return $this->session->get('auth-identity');
  }



}

?>
