<?php

namespace application\controllers;

use \library\core\Controller;
use \application\models\Commentaires as ModelCommentaire;
use \application\models\Jeu as ModelJeu;

class Commentaire extends Controller {
	
	private $mc;
	private $mj;
	
	public function __construct() {
		parent::__construct();
		$this->mc = new ModelCommentaire('localhost');
		$this->mj = new ModelJeu('localhost');
	}
	
	public function addAction($id) {
		$errors = $this->mc->getErrorData($_POST);

		if (!empty($_POST) && empty($errors)) {
			
			if ($this->mc->alreadyInsert($_POST)) {
				array_push($errors, "Vous avez deja commenté ce jeux pour ce critere!");
			} else {
				if ($this->mc->insert($this->mc->cleanData($_POST))) {
					header("location: " . LINK_WEB . "jeu/read/" . $id);
					exit();
				} else {
					array_push($errors, "Erreur DB");
				}
			}
		}
		$this->setDataView(array(
			                   "errors" => $errors,
								"IDJeux"=> $id,
								"jeux" => $this->mj->findByPrimary($id)
		                   ));
		
		$this->setFile("script", "tinymce.min");
		$this->setFile("script", "initEditor");
	}
}