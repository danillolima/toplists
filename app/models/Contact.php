<?php

use Phalcon\Mvc\Model;

class Contact extends Model{
    public $id;
	public $subject;
	public $content;
    public $email;
    
    public function initialize(){
        $this->setSource('tl_contact');
    }
}