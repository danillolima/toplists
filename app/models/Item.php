<?php
use Phalcon\Mvc\Model;

class Item extends Model{
	public $id;
	public $name;
	public $url;
	public $description;
	public $author_item;

	public function initialize(){
		$this->setSource('tl_item');

		$this->hasManyToMany(
            'id',
            'ListItem',
			'id_item', 
			'id_list',
			'Lists',
			'id'
    	);
		/*
			Relaciona um item com várias imagens ou um imagem com vários items.
		*/
		$this->hasManyToMany(
	        'id',
			'ItemMedia',
			'id_item', 
			'id_media',
            'Media',
	        'id'
		);

		$this->belongsTo(
			'author_item',
			'User',
			'id'
		);
	}
}
