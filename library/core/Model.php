<?php

namespace library\core;

abstract class Model {
	protected $table;
	protected $database;
	protected $primary;
	protected $structure;
	protected $foreignKey;
	
	public function __construct($connexionName) {
		$classConnexion = Connexion::getInstance();
		$this->database = $classConnexion::getConnexion($connexionName);
	}
	
	public function getPrimaryName() {
		return $this->primary;
	}
	
	public function cleanData($data) {
		$tmpData = $data;
		foreach ($data as $key => $value) {
			if ($key === $this->primary) {
				continue;
			}
			
			if (!array_key_exists($key, $this->structure)) {
				unset($tmpData[$key]);
			} else {
				if (!isset($this->structure[$key]['ignoreEncode']) || $this->structure[$key]['ignoreEncode'] === false) {
					$tmpData[$key] = htmlentities($tmpData[$key], ENT_QUOTES);
				}
			}
		}
		return $tmpData;
	}
	
	public function sortData($datas, $champs) {
		$nom = "";
		$i = -1;
		$membre = array([0][0]);
		foreach ($datas as $data) {
			if ($nom != $data->usNom) {
				$nom = $data->usNom;
				$i++;
				$membre[$i] = array(
					'nom' => '','graphisme' => '','gameplay' => '', 'dureevie' => ''
				);
				$membre[$i]['nom'] = $data->usNom;
			}
			switch ($data->crNom) {
				case 'graphisme':
					$membre[$i]['graphisme'] = $data->$champs;
					break;
				case 'gameplay':
					$membre[$i]['gameplay'] = $data->$champs;
					break;
				case 'dureevie':
					$membre[$i]['dureevie'] = $data->$champs;
					break;
			}
		}
		return $membre;
	}
	public function getErrorData($data) {
		$error = array();
		
		foreach ($data as $key => $value) {
			if (array_key_exists($key, $this->structure)) {
				foreach ($this->structure[$key] as $rule => $info) {
					if ($rule === 'type' && $info === 'string' && !is_string($value)) {
						array_push($error, "Bad String type field: <strong>$key</strong>");
					}
					if ($rule === 'type' && $info === 'int' && !is_numeric($value)) {
						array_push($error, "Bad Int type field: <strong>$key</strong>");
					}
					if ($rule === 'minLength' && strlen($value) < $info) {
						array_push($error, "Minimal length is: <strong>$info</strong> in field <strong>$key</strong>");
					}
					if ($rule === 'maxLength' && strlen($value) >= $info) {
						array_push($error, "Maximal length is: <strong>$info</strong> in field <strong>$key</strong>");
					}
					if ($rule === 'min' && $value < $info) {
						array_push($error, "Minimal value is: <strong>$info</strong> in field <strong>$key</strong>");
					}
					if ($rule === 'max' && $value >= $info) {
						array_push($error, "Maximal value is: <strong>$info</strong> in field <strong>$key</strong>");
					}
					if ($rule === 'type' && $info === 'email' && filter_var($value, FILTER_VALIDATE_EMAIL)) {
						array_push($error, "Email si not valid");
					}
				}
			}
		}
		return $error;
	}
	
	public function fetchJeux() {
	$sql = $this->database->prepare("SELECT je.ID AS jid, je.nom AS nomJeux, je.console, je.img,
											us.ID AS uid, us.nom AS nomUser, us.mail, us.userUpdate
										FROM `jeux` AS `je`
										LEFT JOIN `users` AS `us` ON je.ID = us.ID");
		$sql->execute();
		return $sql->fetchAll();
	}
	
	public function fetchNotes() {
		$sql = $this->database-> prepare("SELECT note.note, note.dateNote, note.ID_criteres, note.ID_jeux, note.ID_criteres,
											je.nom AS nomJeu,
											cr.nom AS nomCritere
											FROM `notes` AS `note`
											LEFT JOIN `jeux` AS `je` ON je.ID = note.ID_jeux
											LEFT JOIN `criteres` AS `cr` ON cr.ID = note.ID_criteres");
		$sql->execute();
		return $sql->fetchAll();
	}
	public function fetchNotesMoy() {
		$sql = $this->database->prepare("SELECT note.ID_jeux, note.ID_criteres, AVG(note.note) AS noteMoy, cr.ID, cr.nom
											FROM `notes` AS `note`
											LEFT JOIN `criteres` AS `cr` ON cr.ID = note.ID_criteres
											LEFT JOIN `jeux` AS `je` ON je.ID = note.ID_jeux
											GROUP BY note.ID_jeux, note.ID_criteres");
		
		$sql->execute();
		return $sql->fetchAll();
	}
	public function fetchCommentaires() {
		$sql = $this->database->prepare("SELECT co.ID_jeux, co.ID_criteres, co.commentaires, co.dateCommentaire, cr.nom, je.nom
											FROM `commentaires` AS `co`
											LEFT JOIN `criteres` AS `cr` ON cr.ID = co.ID_criteres
											LEFT JOIN `jeux` AS `je` ON je.ID = co.ID_jeux
											GROUP BY co.dateCommentaire DESC, co.ID_jeux, co.ID_criteres, co.commentaires");
		$sql->execute();
		return $sql->fetchAll();
	}
	
	public function findByPrimary($valuePrimary, $fields = "*") {
		$sql = $this->database->prepare("SELECT je.ID AS jid, je.nom AS nomJeux, je.console, je.img,
											us.ID AS uid, us.nom AS nomUser, us.mail, us.userUpdate
										FROM `jeux` AS `je`
										LEFT JOIN `users` AS `us` ON je.ID_users = us.ID WHERE je.ID = :value");
		$sql->execute(array('value' => $valuePrimary));
		return $sql->fetchAll();
	}
	
	public function findByIDJeux($idJeux) {
		$sql = $this->database->prepare("SELECT *, us.nom as usNom, cr.nom as crNom
										FROM `{$this->table}`
										LEFT JOIN `users` AS us ON us.ID=ID_users
										LEFT JOIN `criteres` AS cr ON cr.ID = ID_criteres
										WHERE ID_jeux = :value
										ORDER BY us.nom
										LIMIT 5");
		$sql->execute(array('value' => $idJeux));
		return $sql->fetchAll();
	}
	
	public function alreadyInsert(array $data) {
		$sql = $this->database->prepare("SELECT *
										FROM `{$this->table}`
										WHERE ID_users = " . $data['ID_users'] . "
										AND ID_criteres = " . $data['ID_criteres'] . "
										AND ID_jeux = " . $data['ID_jeux']
		
		);
		$sql->execute();
		if ($sql->fetch()) {
			return true;
		} else {
			return false;
		}
	}
	public function insert(array $data) {
		// `field1`,`field2`,`field3`
		$listFields = "`" . implode('`,`', array_keys($data)) . "`";
		
		// :field1,:field2,:field3
		$listValues = ":" . implode(',:', array_keys($data));
		
		$sql = $this->database->prepare("INSERT INTO `{$this->table}` ({$listFields}) VALUES ({$listValues})");
		return $sql->execute($data);
	}
	
	public function updateByPrimary(array $data) {
		$listFieldsValues = "";
		foreach ($data as $key => $value) {
			if ($key !== $this->primary) {
				$listFieldsValues .= "`$key` = :$key,";
			}
		}
		$listFieldsValues = substr($listFieldsValues, 0, -1);
		
		$sql = $this->database->prepare("UPDATE `{$this->table}` SET {$listFieldsValues} WHERE `{$this->primary}` = :{$this->primary}");
		return $sql->execute($data);
	}
	
	public function deleteByPrimary($valuePrimary) {
		$sql = $this->database->prepare("DELETE FROM `{$this->table}` WHERE `{$this->primary}`=:value");
		return $sql->execute(array('value' => $valuePrimary));
	}
}