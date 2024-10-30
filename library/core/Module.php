<?php

namespace library\core;

use \library\template\Helpers;

abstract class Module extends GeneriqueControl {
	
	private $dataModule = array();
	
	public function __construct() {
		parent::__construct();
	}
	
	protected function setDataModule(Array $data) {
		foreach ($this->getNameReserved() as $value) {
			if (array_key_exists($value, $data)) {
				throw new \Exception("Variable name: '$value' is reserved by system");
			}
		}
		$this->dataModule = array_merge($this->dataModule, $data);
	}
	
	public function renderModule($moduleName, $actionName) {
		$moduleName = str_replace('\\', '/', $moduleName);
		$pathView = APP_ROOT . str_replace('/application', 'views', $moduleName) . DS . str_replace('Action', '', $actionName) . '.phtml';
		if (file_exists($pathView)) {
			extract($this->dataModule);
			include($pathView);
		}
	}
}