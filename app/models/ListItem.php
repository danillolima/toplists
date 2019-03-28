<?php
use Phalcon\Mvc\Model;

class ListItem extends Model{
	public $id;
	public $id_item;
	public $id_list;
    public $votes;
    public $url;
    public $author;

	public function initialize(){
		$this->setSource('tl_list_item');
    $this->belongsTo(
           'id_item',
           'Item',
           'id'
       );

       $this->belongsTo(
           'id_list',
           'Lists',
           'id'
       );
	}
}
