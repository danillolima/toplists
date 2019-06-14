<?php
namespace TopListas\Lib\Utils;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;

class BasicValidation extends Validation
    {
        public function initialize(){
            $this->add(
                'user',
                new PresenceOf(
                    [
                        'message' => 'O usuário é requerido',
                    ]
                )
            );
            $this->add(
                'email',
                new PresenceOf(
                    [
                        'message' => 'O email é requerido',
                    ]
                )
            );
            $this->add(
                'email',
                new Email(
                    [
                        'message' => 'O email é inválido',
                    ]
                )
            );
            $this->add(
                'pass',
                new PresenceOf(
                    [
                        'message' => 'A senha é requerida',
                    ]
                )
            );
        }
}