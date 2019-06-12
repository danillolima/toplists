<?php
use TopListas\Lib\Utils\ContactForm;

class ContactController extends ControllerBase{
	public function indexAction(){
	
		//$this->view->disable();

		$form = new ContactForm();
		
		if ($this->request->isPost()) {
				$contact = new Contact([
					'email' => $this->request->getPost('email'),
					'subject' => $this->request->getPost('subject', 'striptags'),
					'content' => $this->request->getPost('txt', 'striptags'),
				]);
				if (!$contact->save()) {
                    $this->flash->error($contact->getMessages());
                } else {
                    $this->flash->success("Mensagem enviada com sucesso");
                    //Tag::resetInput();
                }
			
			
		}
		//phpinfo();
		$this->view->form = $form;
	
	}
}