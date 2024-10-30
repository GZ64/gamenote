<?php

namespace application\controllers;

use \library\core\Controller;
use \application\models\Jeu as ModelJeu;
use \application\models\Commentaires as ModelCommentaires;
use \application\models\Notes as ModelNotes;
use \application\models\User as ModelUser;

class Jeu extends Controller {
	
	private $ma;
	private $maCommentaires;
	private $maNotes;
	private $maUser;
	
	public function __construct() {
		parent::__construct();
		$this->ma = new ModelJeu('localhost');
		$this->maCommentaires = new ModelCommentaires('localhost');
		$this->maNotes = new ModelNotes('localhost');
		$this->maUser = new ModelUser('localhost');
	}
	
	public function indexAction() {
		$this->setDataView(array(
			                   'elements' => $this->ma->fetchJeux(),
			                   'notes' => $this->ma->fetchNotesMoy(),
			                   'commentaires' => $this->ma->fetchCommentaires()
		                   ));
	}
	
	
	public function readAction($id = null) {
		$commentaires = $this->maCommentaires->findByIDJeux($id);
		$commentaires = $this->maCommentaires->sortData($commentaires, 'commentaires');
		
		$notes = $this->maNotes->findByIDJeux($id);
		$notes = $this->maNotes->sortData($notes, 'note');
		
		$this->setDataView(array(
			                   'elements' => $this->ma->findByPrimary($id),
			                   'commentaires' => $commentaires,
			                   'notes' => $notes
		                   ));
	}
	
	public function createAction() {
		if (isset($_FILES['picture'])) {
			$info = pathinfo($_FILES['picture']['name']);
			$extension = $info['extension'];
			$nomFichier = str_replace(' ', '_', $_POST['nom'] . '.' . $extension);
			$dossier = '../public/img/jeux/';
			/* insertion du nom du fichier pour enregistrer en base */
			$_POST['img'] = $nomFichier;
			/* renommer le fichier avec le nom du jeu */
			$_FILES['picture']['name'] = $nomFichier;
			$fichier = basename($_FILES['picture']['name']);
		}
		$errors = $this->ma->getErrorData($_POST);
		if (!empty($_POST) && empty($errors)) {
			/* on met la premiere lettre du nom du jeu en capitale */
			$_POST['nom'] = ucfirst($_POST['nom']);
			if ($this->ma->insert($this->ma->cleanData($_POST))) {
				if (move_uploaded_file($_FILES['picture']['tmp_name'], $dossier . $fichier)) {
					echo 'Upload effectué avec succès !';
				} else {
					array_push($errors, 'Echec de l\'upload !');
				}
				header("location: " . LINK_WEB . "jeu");
				exit();
			} else {
				array_push($errors, "Le jeu existe deja !");
			}
		}
		
		$this->setDataView(['errors' => $errors]);
	}
	
	public function updateAction($id = null) {
		if (isset($_FILES['picture'])) {
			$info = pathinfo($_FILES['picture']['name']);
			$extension = $info['extension'];
			$nomFichier = str_replace(' ', '_', $_POST['nom'] . '.' . $extension);
			$dossier = '../public/img/jeux/';
			/* insertion du nom du fichier pour enregistrer en base */
			$_POST['img'] = $nomFichier;
			/* renommer le fichier avec le nom du jeu */
			$_FILES['picture']['name'] = $nomFichier;
			$fichier = basename($_FILES['picture']['name']);
		}
		$jeux = $this->ma->findByPrimary($id);
		
		if (empty($jeux)) {
			header("location: " . LINK_WEB . "jeu");
			exit();
		}
		
		$jeu = isset($jeux[0]) ? $jeux[0] : null;
		$errors = $this->ma->getErrorData($_POST);
		
		if (!empty($_POST) && !is_null($jeu) && empty($errors)) {
			if ($this->ma->updateByPrimary($this->ma->cleanData($_POST))) {
				if (move_uploaded_file($_FILES['picture']['tmp_name'], $dossier . $fichier)) {
					unlink ("../public/img/jeux/" . $jeu->img);
					header("location: " . LINK_WEB . "jeu/read/" . $_POST[$this->ma->getPrimaryName()]);
				}
				
				exit();
			} else {
				array_push($errors, "Error DB");
			}
		}
		$this->setDataView(array(
			                   'element' => $jeu,
			                   'errors' => $errors,
			                   "primaryName" => $this->ma->getPrimaryName()
		                   ));
	}
	
	public function deleteAction($id = null) {
		
		$error = array();
		$elements = $this->ma->findByPrimary($id);
		
		
		if (empty($elements)) {
			header('location: ' . LINK_WEB . "jeu");
			exit();
		}
		
		$element = isset($elements[0]) ? $elements[0] : null;
		if (!empty($_POST) && !is_null($element)) {
			if ($this->ma->deleteByPrimary($id)) {
				/* suppression de l'image du jeux */
				unlink ("../public/img/jeux/" . $elements[0]->img);
				header("location: " . LINK_WEB . "jeu");
				exit();
			} else {
				array_push($error, "Error DB");
			}
		}
		
		$this->setDataView(array(
			                   "element" => $element,
			                   "errors" => $error
		                   ));
		
	}
}
