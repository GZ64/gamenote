<?php

namespace application\models;

use \library\core\Model;

class Jeu extends Model {
	
	protected $primary = 'ID';
	protected $table = 'jeux';
	protected $structure = array(
		"nom" => array(
			"type" => "string",
			"minLength" => "2",
			"maxLength" => "50",
			"ignoreEncode" => true
		),
		"console" => array(
			"type" => "string",
			"minLength" => "2",
			"maxLength" => "50",
			"ignoreEncode" => true
		),
		"img" => array(
			"type" => "string",
		),
		"ID_users" => array(
			"type" => "int"
		)
	);
	
	public function __construct($connexionName) {
		parent::__construct($connexionName);
	}
	
	public function getPrimary() {
		return $this->primary;
	}
	
	public function getTableJeux() {
		return $this->table;
	}
}