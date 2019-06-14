<?php
use Phalcon\Mvc\Model;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;

class Vote extends Model
{
    public $id;
    public $author_vote;
    public $vote;
    public $item_voted;

	public function initialize(){
        $this->setSource('tl_vote');
        $this->belongsTo(
            'author_vote',
            'User',
            'id'
        );
        $this->belongsTo(
            'item_voted',
            'ListItem',
            'id'
        );
    }
    

    public function afterSave(){
       
    }
    public function validation(){
        $validator = new Validation();
        $validator->add(
            [
                "author_vote",
                "item_voted",
            ],
            new Uniqueness([
                'message' => 'Você só pode votar uma vez.'
            ])
        );
        return $this->validate($validator);

    }
}
