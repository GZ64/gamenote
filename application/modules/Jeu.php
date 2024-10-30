<?php

namespace application\modules;

use \library\core\Module;
use \application\models\Jeu as ModelJeu;

class Jeu extends Module {
	
	
	public function lastAction($number) {
		$modelJeu = new ModelJeu('localhost');
		$elements = $modelJeu->fetchJeux();
		$this->setDataModule(array("elements" => $elements));
	}
}