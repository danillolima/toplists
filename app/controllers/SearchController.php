<?php

use Phalcon\Mvc\Controller;

class SearchController extends ControllerBase{
    public function indexAction(){
        $search = $this->request->getQuery('q'); 
        echo $search;
        
    }
}