<?php

use Phalcon\Mvc\Controller;

class SearchController extends ControllerBase{
    public function indexAction(){
        //pego o valor do form
        $search = $this->request->getQuery('q');
        //uso o modelo e a sintaxe do phalcon 
        
        $lists = Lists::find([
            'conditions' => 'name LIKE :busca:',
            'bind' => [
                'busca' => '%'.$search.'%',
            ],
        ]);
        $this->view->lists = $lists;
        /*
        //json_encode($lists);
        foreach ($lists as $list) {
            echo <a$list->name, "<br>";
        }
       echo $search;
       */
    }
}