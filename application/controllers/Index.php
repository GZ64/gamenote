<?php

namespace application\controllers;

use \library\core\Controller;
use \application\models\Jeu;

class Index extends Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function indexAction() {
		$modelJeu = new Jeu('localhost');
		$this->setDataView(array(
//			                   'listJeu' => $modelJeu->fetchAll(),
			                   'listJeuPrimary' => $modelJeu->findByPrimary(2)
		                   ));
	}
	
	public function testAction() {
	}
}
