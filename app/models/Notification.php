<?php

use Phalcon\Mvc\Model;

class Notification extends Model{
    public $id;
    public $user_notify;
    public $author_notify;
    public $time;
    public $url;
    public $type_not;

    public function initialize(){
        $this->setSource('tl_notification');
        $this->belongsTo(
            'type_not',
            'NotifyType',
            'id'
        );
        $this->belongsTo(
            'author_notify',
            'User',
            'id'
        );
        $this->belongsTo(
            'user_notify',
            'User',
            'id'
        );
    }
}