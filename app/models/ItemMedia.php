<?php
use Phalcon\Mvc\Model;

class ItemMedia extends Model{
  public $id;
  public $id_item;
  public $id_media;

  public function initialize(){
    $this->setSource('tl_item_media');

      $this->belongsTo(
           'id_item',
           'Item',
           'id'
      );

      $this->belongsTo(
           'id_media',
           'Media',
           'id'
       );
  }
}

