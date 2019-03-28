<?php

use Phalcon\Mvc\Model;

class Message extends Model{
    public $id;
    public $content;
    public $to_destinatary;
    public $from_author;
    public $is_read;

    public function initialize(){
        $this->setSource('tl_message');
        $this->belongsTo(
            'to_destinatary',
            'User',
            'id'
        );
        $this->belongsTo(
            'from_author',
            'User',
            'id'
        );
    }
}