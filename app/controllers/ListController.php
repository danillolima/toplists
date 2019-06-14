<?php
use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;
use Phalcon\Http\Response;

class ListController extends ControllerBase{
    public function indexAction(){
	  $response = new Response();
			/*
				if(!$this->session->has('auth-identity') ){
					$response->redirect('/');
					return $response;
				}*/
			$response->setContent("Sorry, the page doesn't exist");
			$this->response->setContentType('text/html', 'iso-8859-1');
    }

    public function showAction($params){
    	$this->view->setTemplateAfter('single-list');
    	$title = $this->dispatcher->getParam('title');
    	$category = $this->dispatcher->getParam('category');
			$url = '/l/' . $category . '/' . $title;

    	$Lists  =  Lists::findFirst("url = '$url' ");
    	if($Lists != false){
				$categorys = new Category();
				$categorys = Category::find();
				$subcategorys = new Category();
				$subcategorys = Category::find(["id_parent != 'NULL'"]);
				$dados = $this->auth->getIdentity();
			
				
				$user = new User();
				$user = User::findFirstById($dados["id"]);

				$Lists->ListItem  = ListItem::Find([ 'conditions' => 'id_list = ?1',
																						 'bind' => [
																							 1 => $Lists->id,
																						 ],	
																						 'order' => 'votes DESC',
																						 ]) ;
				$this->view->query = $Lists;
				$this->view->categorys = $categorys;
				$this->view->subcategorys = $subcategorys;
				$this->view->params = $params;
				$this->view->user = $dados;
				$this->view->listitem = ListItem::find([ 'conditions' => 'id_list = ?1',
				'bind' => [
					1 => $Lists->id,
				],	
				'order' => 'votes DESC',
				]) ;
      }
    	else
    		$this->view->query = false;
	}
	public function debugAction(){
		$categorys = new Category();
			$categorys = Category::find();
			$subcategorys = new Category();
			$subcategorys = Category::find(["id_parent != 'NULL'", 'order' => 'name']);
		
			$this->view->categorys = $categorys;
			$this->view->subcategorys = $subcategorys;
			foreach($categorys as $cat)
				echo $cat->name;

	}
	

   	public function createAction(){
			$categorys = new Category();
			$categorys = Category::find();
			$subcategorys = new Category();
			$subcategorys = Category::find(["id_parent != 'NULL'", 'order' => 'name']);
			$this->view->query = $Lists;
			$this->view->categorys = $categorys;
			$this->view->subcategorys = $subcategorys;
			//$this->response->setContentType('text/html', 'utf-8');
   		$this->view->setTemplateAfter("create-list");
   	}

		public function editAction(){
			$this->view->disable();
			if ($this->request->isAjax()) {
				if ($this->request->isPost()) {
					$title = $this->request->getPost('title');
					$description = $this->request->getPost('description');
					$id = $this->request->getPost('key');
					$category = $this->request->getPost('category');
				
					$category_list = new Category();
					$category_list = Category::findFirst("id = $category");

					$label = $this->cleanURL($category_list->name);
					$url = '/l/' . $label . '/' . $this->cleanURL($title);
					
					$nlist = Lists::findFirstByName($title);

					if($nlist){
							if($nlist->id != $id)
									return "Essa lista já existe";
					}

						$list = Lists::findFirstById($id);
						$list->name = $title;
						$list->description = $description;
						$list->url = $url;
						if($list->update()){
							echo "Lista atualizada com sucesso";
						}
						else{
							echo "Erro atualizando a lista";
						}
				}
		}
	}
/*
Cria lista
	public $id;
	public $name;
	public $url;
	public $description;
	public $id_subject;
	public $list_type;
	public $source;
	public $allow_votes;
	public $id_media;
	public $id_user;
	public $id_category;
*/
    public function insertListAction(){
		//Essa função recebe um dados de /criar-lista
		$identity = $this->auth->getIdentity();
		$random = new \Phalcon\Security\Random();
		$bytes = $random->bytes();

    $name = $this->request->getPost('title');
    $description = $this->request->getPost('description');
    $category_id = $this->request->getPost('category');
		$subject = $this->request->getPost('subject');
		  
		if(empty($this->request->getPost('source')))
			$source = null;
		else
			$source = $this->request->getPost('source');
		
		$list_type = $this->request->getPost('ltype');
		/*
		não tem mais upload de imagem criando a lista
		//Pego as imagens do formulário e ponho numa array
		$media[] = new Media();
		$media["imgUp"] = NULL;
		$media["imgUpLista"] = NULL;

		if($this->request->hasFiles(true) == true){
			foreach ($this->request->getUploadedFiles() as $file) {
				if($file->getError() == 0){
					$fileN = $random->uuid() . '-' . strtolower($file->getName());
					$path = '../public/uploads/media/' . $fileN;
					try{
						$file->moveTo($path);
						$media[$file->getKey()] = new Media();
						$media[$file->getKey()]->type = 0;
						$media[$file->getKey()]->url = '/uploads/media/' . $fileN;
						$media[$file->getKey()]->create();
						}catch(Exception $e){
								echo "Erro:", $e->getMessage(), '<br>';
						}
				}
			}
		}*/

			// Cria o item relacionado à lista
			//$subject_item = new Item();
			

			
		


			try{

				$subject_item = Item::findFirstByName($subject);
				
				if(!$subject_item){
					$subject_item = new Item();
					$subject_item->name = $subject;
					$subject_item->url = '/i/' . $this->cleanURL($subject);
					$subject_item->description = "Adicione Algo!";
					$subject_item->id_category = $category_id;
					$subject_item->author_item = $identity["id"];			
				}

				$subject_item->save();
			
				$messages = $subject_item->getMessages();
				foreach ($messages as $message) {
					echo $message, "<br>";
				}
				/*
				if($media["imgUp"] == NULL)  
					$subject_item->Media = NUll;
				else
					$subject_item->Media = $media["imgUp"];
				$subject_item->author_item = $identity["id"];
				*/

    } catch (Exception $e){
			echo 'Erro:', $e->getMessage(), '<br>';
		}
  
			//Pesquisa Categoria recebida
    	$category_list = new Category();
    	$category_list = Category::findFirst("id = $category_id");
			//Monta URL de até dois niveis /categoria/titutlo ou categoria/subcategoria/titulo
      //if($category_list->id_parent == NULL){
         $label = $this->cleanURL($category_list->name);
         $url = '/l/' . $label . '/' . $this->cleanURL($name);
			//}
			/*
       else{
	    	$label = $this->cleanURL($category_list->name);
		    $url = '/' . $label . '/' . $this->cleanURL($name);
		    $category_listR = Category::findFirst("id = $category_list->id_parent");
				$label = $this->cleanURL($category_listR->name);
        $url = '/l/'. $label . $url;
      }*/
    	//Crio a lista
      
    	$n_list = new Lists();
    	$n_list->name = $name;
			$n_list->url = $url;
			$n_list->description = $description;
			$n_list->id_subject = $subject_item->id;
      $n_list->list_type = $list_type;
      $n_list->source = $source;
			$n_list->id_media = NUll;
    	$n_list->id_user = $identity["id"];
		  $n_list->id_category = $category_id;
		  $n_list->url = $url;

			try {
				if($n_list->create() === false){
					echo "Não foi possível salvar a lista<br>";
					$messages = $n_list->getMessages();
					foreach ($messages as $message) {
						echo $message, "<br>";
					}
				}
				else{
					$this->response->redirect($n_list->url);
					$this->view->disable();
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
}

		public function allAction($param){
			if('create' == $param){
				$this->createAction();
			}
		}
		public function voteAction($param){
			//Desabilita para imprimir json
			$this->view->disable();

			$voto = new Vote();
			$item = new ListItem();
			//Tipo de requisição
			if ($this->request->isAjax() && $this->request->isPost()) {

					$id_item_voted = $this->request->getPost('votein');
					$usuario = $this->request->getPost('user');
					$voteValue =  $this->request->getPost('votevalue');
					$user = new User();
					$user = User::findFirstByUsername($usuario);

					if($user){
						$item = ListItem::findFirstById($id_item_voted);
						$voto = Vote::findFirst(
								[
									'author_vote = :user: AND item_voted = :id_item:',
									'bind' => [
										'user' => $user->id,
										'id_item' => $id_item_voted,
									],
								]
							);
							$msg = 'Voto salvo com sucesso!';
							if($voto){
								if($voteValue == 1 && $voto->vote == 1){
									$voto->vote--;
									$item->votes--;
								}
								else if($voteValue == 1 && $voto->vote<1){
									$voto->vote++;
									$item->votes++;
								}	
								if($voteValue == -1 && $voto->vote== -1){
									$voto->vote++;
									$item->votes++;
								}
								else if($voteValue == -1 && $voto->vote>-1){
									$voto->vote--;
									$item->votes--;
								}
							}
							else{
								$voto = new Vote();
								$voto->vote = $voteValue;
								$voto->item_voted = $item->id;
								$voto->author_vote = $user->id;
								$item->votes += $voteValue;
							}
							if ($item->save() === false || $voto->save() === false) {
								$messages = $voto->getMessages();
								
								foreach ($messages as $message) {
									echo json_encode(array(
										'id' => 'item-' . $id_item_voted,
										'message' => $message->getMessage()), JSON_UNESCAPED_UNICODE);
								}
							}
							else{
								echo json_encode(array(
									'id' => 'item-' . $id_item_voted,
									'message' => $msg,
									'numero_votos' => $item->votes), JSON_UNESCAPED_UNICODE);
							}
					}
					else{
						echo(json_encode(array(
									'id' => 'item-' . $id_item_voted,
									'message' => 'Você não tem autorização para fazer isso'), JSON_UNESCAPED_UNICODE));
					}
			}
			else{
				echo('404'); 
			}
	}

	/* funcções de api 
			/api/lists/:action/:params
	*/
	public function insertImageAction($params){
		
		$media = new Media();
		$author = User::findFirstByUsername($this->request->getPost('author'));
		$lista = Lists::findFirstById($params);
		$random = new \Phalcon\Security\Random();
		$bytes = $random->bytes();

		if($this->request->hasFiles(true) == true){
			foreach ($this->request->getUploadedFiles() as $file) {
				if($file->getError() == 0){
					$fileN = strtolower($file->getName());
					$path = '../public/uploads/media/' . $fileN;
					try{
							$file->moveTo($path);		
							//0 imagem 1 video
							$media->type = 0;
							$media->url = '/uploads/media/' . $fileN;
							$media->User = $author;
							$media->save();
								
							$messages = $media->getMessages();
							foreach ($messages as $message) {
								echo $message, "<br>";
							}

							$lista->Media = $media;
							$lista->save();

							$messages = $lista->getMessages();
							foreach ($messages as $message) {
								echo $message, "<br>";
							}
						}catch(Exception $e){
								echo "Erro:", $e->getMessage(), '<br>', $e->getLine() ;
						}
				}
			}
		}
	}
	
/*
			/api/lists/insertItem/id
	*/
	public function insertItemAction($id){
		$this->view->disable();
	//	if ($this->request->isAjax()) {
		if ($this->request->isPost()) {
	
			$name = $this->request->getPost("name");
			$item = Item::findFirstByName($name);

			$typeMedia = $this->request->getPost("media");
			$description = $this->request->getPost("description");
			$identity = $this->auth->getIdentity();
			$user = new User();
			
			if(!$item){
				//Se o item não foi encontrado 
				$item = new Item();
				$item->name = $name;
				$item->url = '/i/' . $this->cleanURL($name);
				$item->description = $description;
				$item->is_public = 0;
				$item->author_item = $identity["id"];
				$item->created = date('Y-m-d H:i:s');

				if($item->create()){
					echo "Item criado com sucesso";
				}
				else{
					echo "Erro ao criar item";
					foreach ($item->getMessages() as $message) {
						echo $message;
						echo $identity["id"], '2';
					}
				}
			// 1 = imagem, 2 = videos
			if($typeMedia == 1){
				if($this->request->hasFiles(true) == true){

					foreach ($this->request->getUploadedFiles() as $file) {

						$fileN = md5(uniqid(rand(), true)) . '.webp';
						$path = '../public/uploads/media/' . $fileN;
								if($file->moveTo($path) == true){
									$this->flash->success("upload de imagem sucesso");
									$media = new Media();
									$media->type = $typeMedia;
									$media->url = '/uploads/media/' . $fileN;
									$media->create();
									$item->Media = $media;

								}
								else{
									echo "erro";
								}
					}
				}
			}
			else if($typeMedia == 2){
				$url = $this->request->getPost("video");
				$media = new Media();
				$media->type = $typeMedia;
				$media->url = $url;
				$media->create();
				$item->Media = $media;
			}
		}
	
			$addTo = Lists::findFirstById($id);
			$relacao = new ListItem();
			$relacao->id_item = $item->id;
			$relacao->id_list = $id;
			$relacao->votes = 0;
			$relacao->author = $item->author_item;
			$relacao->url = "teste";
			if($relacao->save()){
				$this->response->redirect($addTo->url);
			}

			//$addTo->Item = $item;
			//echo $addTo->name;
			//$addTo->update();
			try{					
				
			} catch(\Exception $e){
					foreach (	$addTo->getMessages() as $message) {
						echo $message, "\n";
					}
					// echo $e->message();
				}
	}
}
 //api/lists/removeItem/ID?item=ID

 public function removeItemAction($idList){

	$idItem = $this->request->getQuery('item');
//	if()
	$ListItem = ListItem::findFirst(['id_list = :idL: AND id_item = :idI:',
																'bind' => [
																	'idL' => $idList,
																	'idI' => $idItem
																]
															]);

	$votos = Vote::find(['item_voted = :idI:',
													'bind' => [
														'idI' => $ListItem->id
													]
													]);
	

	foreach($votos as $voto){
		if($voto->delete()===false){
			echo "erro deletando o item";
		}
		else{
			echo "sucesso deletando o item";
		}
	}

	if ($ListItem !== false) {
			if ($ListItem->delete() === false) {
					echo "Desculpa, não conseguimos deletar o item agora!";
	
					$messages = $robot->getMessages();
	
					foreach ($messages as $message) {
							echo $message, "\n";
					}
			} else {
					echo 'Deletado com sucesso!';
			}
			$listR = Lists::findFirstById($idList);
			
			$this->response->redirect($listR->url);
	}
}



}
?>
