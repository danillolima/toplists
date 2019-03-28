<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;
use Phalcon\Http\Response;

class ItemController extends ControllerBase{
		public function indexAction(){
            $this->view->item = 0;
		}

		public function showAction($item){
            $this->view->setTemplateAfter('item');
			$item = '/i/' . $item;
            $display_item = Item::findFirstByUrl($item);
			$this->view->item = $display_item;
		}

		public function searchAction(){
			$this->view->disable();
			$request = new Request();
			$response = new Response();
			$response->setHeader("Content-type", "application/json;charset=utf-8");

		//	if ($request->isPost()) {
//if ($request->isAjax()) {
					$busca = $this->request->getPost('busca');
					$items = Item::find(
							[
									'columns' => 'name',
									'conditions' => "name LIKE :busca:",
									'bind' => [
											'busca' => '%'.$busca.'%',
									],
							]
							);
						$itemsArray = Array();
						foreach ($items as $item) {
							$itemsArray[] = ['name' => $item->name];
						}

					$response->setContent( json_encode($itemsArray) )	;
					return $response;
			///	}
			//}
		}
		//deleta um item por id
		public function deleteAction($id){
			$this->view->disable();
			$item = Item::findFirstById($id);
			//vê se o item não é subject de alguma lista
			$lista = Lists::findFirstById_subject($id);
			if($lista){
				echo "Esse item é assunto de outra lista";
			}
			else{
				foreach ($item->Media as $media) {

					$path = BASE_PATH . "/public" . $media->url;
					$relations = ItemMedia::findById_media($media->id);
					foreach ($relations as $rel) {
						$rel->delete();
					}

					if(unlink ($path)){
						if($media->delete() === true){
							echo "deletando imagem<br>";
						}
					}
					else{
						echo "erro deletando img<br>";
					}

				}
				$relations = ListItem::findById_item($item->id);
				foreach ($relations as $rel) {
					$rel->delete();
				}

				if($item->delete() === true)
						echo "item deletado";
				else
					echo "item não deletado";

			}

		}
		public function searchCompleteAction(){
			$this->view->disable();
			$request = new Request();
			$response = new Response();
			$response->setHeader("Content-type", "application/json;charset=utf-8");
		//	if ($request->isPost()) {
//if ($request->isAjax()) {
					$busca = $this->request->getPost('busca');
					$items = Item::find([
									'conditions' => "name LIKE :busca:",
									'bind' => [
											'busca' => $busca,
									],
							]
							);
					$response->setContent( json_encode($items) )	;
					return $response;
			///	}
			//}
		}
}
