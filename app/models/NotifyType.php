<?php

use Phalcon\Mvc\Model;

class NotifyType extends Model{
    public $id;
    public $text;

    public function initialize(){
        $this->setSource('tl_notif_type');
        
    }
}