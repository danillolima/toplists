<?php

use Phalcon\Mvc\Model;

class Comment extends Model{
    public $id;
    public $content;
    public $author_comment;
    public $url;
    public $url_commented;

    public function initialize(){
        $this->setSource('tl_comment');
        $this->belongsTo(
            'author_comment',
            'User',
            'id'
        );
    }
}