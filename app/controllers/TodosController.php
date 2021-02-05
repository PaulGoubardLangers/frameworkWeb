<?php
namespace controllers;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\attributes\items\router\Get;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\controllers\Router;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;


 /**
  * Controller TodosController
  */
class TodosController extends ControllerBase{

    const CACHE_KEY = 'datas/lists/';
    const EMPTY_LIST_ID='not saved';
    const LIST_SESSION_KEY='list';
    const ACTIVE_LIST_SESSION_KEY='active-list';

    public function initialize(){
        parent::initialize();//TODO:
        $this->menu();
    }

    public function menu(){
    $this->loadView('TodosController/menu.html');
    }

    #[Route('_default', name: 'home')]
	public function index(){
		if(USession::exists(self::LIST_SESSION_KEY)){
		$list = USession::get(self::LIST_SESSION_KEY,[]);
		return $this->displayList($list);
		}
		$this->showMessage('Bienvenue!','Génère ta liste!','info','info circle',
		[['url' =>Router::path('todos.new'),'caption'=>'Créer une nouvelle liste','style'=>'basic inverted']]);
	}

	#[Post(path: "todos/add", name: 'todos.add')]
	public function addElement(){
		$list=USession::get(self::LIST_SESSION_KEY);
		if(URequest::filled('elements')){
		    $elements = explode("\n",URequest::post('elements'));
		    foreach($elements as $elem){
		        $list[] = $elem;
		    }
	    }else{
	        $list[] = URequest::post('elements');
	    }
	    USession::set(self::LIST_SESSION_KEY,$list);
	    $this->displayList($list);
}




	#[Get(path: "/todos/delete/(.+?)/", name: "todos.delete" )]
	public function deleteElement($index){
		$list = USession:get(self::LIST_SESSION_KEY);
		if(isset($list[$index])){
		    $list[$index]->deleteElement($index);
		    USession::set(self::LIST_SESSION_KEY,$list);
		}
		$this->displayList($list);
	}





	#[Post(path: "/todos/edit/(.+?)/", name:'todos.edit')]
	public function editElement($index){
		$list=USession::get(self::LIST_SESSION_KEY);
		if(isset($list[$index])){
		    $list[$index]=URequest::post('editElement');
		    USession::set(self::LIST_SESSION_KEY,$list);
		}
	    $this->displayList($list);
	}


	#[Get(path: "/todos/loadList/(.+?)", name:'todos.loadList')]
	public function loadList($uniqid){
		
	}


	#[Post(path: "/todos/loadList/", name:'todos.loadListpost')]
	public function loadListFromForm(){
		
	}


	#[Get(path: "/todos/new/(.+?)/", name:'todos.new')]
	public function newlist($force=false){
		if(USession::exists(self::LIST_SESSION_KEY) || $force!=false){
		    USession::set(self::LIST_SESSION_KEY, []);
		    $this->displayList($list);
		}else{
		    this->showMessage("nouvelle liste", "une liste existe déjà, voulez-vous la vider?","","");
            [['url' => Router::path('todos.new/1'), 'caption' => 'Créer une nouvelle liste', 'style' => 'basic inverted'],
            ['url' => Router::path('todos.menu'), 'caption' => 'Annuler', 'style' => 'basic inverted']]);


		}
	}


	#[Get(path: "/todos/savelist/", name:'todos.save')]
	public function savelist(){
		
	}

}
