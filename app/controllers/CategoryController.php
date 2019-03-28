<?php
use Phalcon\Mvc\Controller;

class CategoryController extends ControllerBase{

	public function indexAction(){
		$this->view->setTemplateAfter("categories");
		$this->view->categorys = Category::find(['order' => 'name' ]);
	}

	public function showAction($cat){
		$this->view->setTemplateAfter("single-category");
		$cat = '/category/' . $cat;
		$category = Category::findFirstByUrl($cat);
		$this->view->category = $category;
		$this->view->lists = Lists::find(['id_category = :id:',
										  'bind' => [
										  'id' => $category->id,
										 ],
										]);
	}
	public function allAction(){

	}
}