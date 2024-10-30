<?php

namespace application\models;

use \library\core\Model;

class Notes extends Jeu {
	
	protected $primary = 'ID';
	protected $table = 'notes';
	protected $structure = array(
		"title" => array(
			"type" => "string",
			"minLength" => "2",
			"maxLength" => "50"
		),
		"content" => array(
			"type" => "string",
			"minLength" => "25",
			"ignoreEncode" => true
		)
	);
	
	public function __construct($connexionName) {
		parent::__construct($connexionName);
	}
	
	public function getPrimary() {
		return $this->primary;
	}
	
	public function getChampsNotes($nom) {
		return $this->champs[$nom];
	}
	
	public function getTableNotes() {
		return $this->table;
	}
}