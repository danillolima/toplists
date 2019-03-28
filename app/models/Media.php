<?php
use Phalcon\Mvc\Model;

class Media extends Model{
	public $id;
	public $type;
	public $url;
	public $author_media;

	public function initialize(){
		$this->setSource('tl_media');

		$this->hasManyToMany(
	          'id',
	          'ItemMedia',
	          'id_media', 'id_item',
            'Item',
	         	'id'
		        );
		$this->belongsTo(
							'id',
							'Lists',
							'id_media'
						);
		$this->belongsTo(
			'author_media',
			'User',
			'id'
		);
	}
}


