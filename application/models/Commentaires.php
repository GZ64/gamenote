<?php

namespace application\models;


use library\core\Model;

class Commentaires extends Model {
	
	protected $primary = 'ID';
	protected $table = 'commentaires';
	protected $structure = array("commentaires" => array(
			"type" => "string",
			"minLength" => "2",
			"ignoreEncode" => true
		),
		'ID_users' => array(
			"type" => "int"
		),
		'ID_criteres' => array(
			"type" => "int"
		),
		'ID_jeux' => array(
			"type" => "int"
		)
	);
	
	public function __construct($connexionName) {
		parent::__construct($connexionName);
	}
	
	public function getPrimary() {
		return $this->primary;
	}
	public function getTableCommentaires() {
		return $this->table;
	}
}