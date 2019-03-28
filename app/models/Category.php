<?php
use Phalcon\Mvc\Model;

class Category extends Model{
	public $id;
	public $name;
	public $url;
	public $id_parent;
	public $is_menu;
	public $description;
	
	public function initialize(){
		$this->setSource('tl_category');
		$this->belongsTo(
				'id',
				'Lists',
				'id_category'
		);
	}
}