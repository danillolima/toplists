<?php
namespace TopListas\Lib\Utils;
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\Email;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class ContactForm extends Form{
  
    public function initialize($entity = null, $options = null)
    {
        $email = new Text('email',[
            'class' => 'form-control'
        ]);
        $email->setLabel('E-mail');
        $email->addValidator(
            new PresenceOf([
                'message' => 'O email é requerido'
            ]));

        $email->addValidator(
            new Email( [
                'message' => 'O email não é válido',
            ]));

        $this->add($email);

        $subject = new Text('subject', [
            'class' => 'form-control'
        ]);
        $subject->setLabel('Assunto');
        $subject->addValidators([
            new PresenceOf([
                'message' => 'O assunto é obrigatório'
            ])
        ]);
        $this->add($subject);

        $txt = new TextArea('txt', [
            'class' => 'form-control'
        ]);
        $txt->setLabel('Mensagem');
        $txt->addValidators([
            new PresenceOf([
                'message' => 'A mensagem é obrigatória'
            ])
        ]);
        $this->add($txt);

     
        $this->add(new Submit('Enviar', [
            'class' => 'x-auto'
        ]));
    }

    public function messages($name)
    {
        if ($this->hasMessagesFor($name)) {
            foreach ($this->getMessagesFor($name) as $message) {
                $this->flash->error($message);
            }
        }
    }

}