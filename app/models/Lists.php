<?php
use Phalcon\Mvc\Model;

class Lists	 extends Model{
	public $id;
	public $name;
	public $url;
	public $description;
	public $id_subject;
	public $list_type;
	public $source;
	//public $allow_votes;
	public $id_media;
	public $id_user;
	public $id_category;

	public function initialize(){
		$this->setSource('tl_list');

		$this->hasManyToMany(
						'id',
						'ListItem',
						'id_list', 'id_item',
						'Item',
						'id'
		);

		$this->hasOne(
			'id_category',
			'Category',
			'id'
		);

		$this->belongsTo(
			'id_media',
			'Media',
			'id'
		);


	}
}
