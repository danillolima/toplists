<?php

use Phalcon\Mvc\Controller;

use Phalcon\Http\Response;

class SessionController extends Controller{

  public function logoutAction(){
    $this->session->destroy();
    $response = new Response();
		$response->redirect('index');
    return $response;
  }

}
