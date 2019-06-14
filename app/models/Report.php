<?php

use Phalcon\Mvc\Model;

class Report extends Model{
    public $id;
    public $url;
    public $motive;
    public $content;
    public $author;

    public function initialize(){
        $this->setSource('tl_report');
        $this->belongsTo(
            'author',
            'User',
            'id'
        );
    }
}