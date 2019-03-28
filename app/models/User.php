<?php
use Phalcon\Mvc\Model;

class User extends Model
{
	public $id;
	public $username;
	public $name;
	public $surname;
	public $about;
	public $pass;
	public $email;
	public $qtd_comments;
	public $qtd_lists;
	public $qtd_itens;
	public $registered;

	public function initialize(){
		$this->setSource('tl_user');
		$this->hasMany(
			'id',
			'Comment',
			'author_comment'
		);
		$this->hasMany(
			'id',
			'Follows',
			'following'
		);
		
	}
}
