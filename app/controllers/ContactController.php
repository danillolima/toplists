<?php
use TopListas\Lib\Utils\ContactForm;

class ContactController extends ControllerBase{
	public function indexAction(){
	

		$form = new ContactForm();
		$this->view->form = $form;
	
		if ($this->request->isPost()) {
			if ($form->isValid($this->request->getPost()) == false) {
				foreach ($form->getMessages() as $message) {	
				//	$this->flash->error($message);
				} 
			}
			else{
				$contact = new Contact([
					'email' => $this->request->getPost('email'),
					'subject' => $this->request->getPost('subject', 'striptags'),
					'content' => $this->request->getPost('txt', 'striptags'),
				]);
				if (!$contact->save()) {
                    $this->flash->error($user->getMessages());
                } else {
                    $this->flash->success("Mensagem enviada com sucesso");
                    //Tag::resetInput();
                }
			}
			
		}
	}
}