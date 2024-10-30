<?php

namespace application\models;

use \library\core\Model;

class User extends Model {
	
	protected $primary = 'ID';
	protected $table = 'users';
	protected $structure = array(
		"mail" => array(
			"type" => "mail"
		),
		"password" => array(
			"type" => "string",
			"minLength" => "5",
			"maxLength" => "15"
		)
	);
	
	public function __construct($connexionName) {
		parent::__construct($connexionName);
	}
	
	public function findForAuth($data) {
		$sql = $this->database->prepare("SELECT `ID`, `nom`, `mail`, `userUpdate` FROM `users` WHERE `mail`=:mail AND `password`=:password");
		$sql->execute($data);
		$result = $sql->fetchAll();
		
		if (count($result) === 1) {
			return $result[0];
		}
		
		return false;
	}
	
	public function getTableUsers() {
		return $this->table;
	}
	public function getPrimary() {
		return $this->primary;
	}
}