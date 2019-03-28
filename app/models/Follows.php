<?php

use Phalcon\Mvc\Model;

class Follows extends Model{
    public $id;
    public $followed;
    public $following;

    public function initialize(){
        $this->setSource('tl_follows');
        $this->belongsTo(
            'followed',
            'User',
            'id'
        );
        $this->belongsTo(
            'following',
            'User',
            'id'
        );
    }
}