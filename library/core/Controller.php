<?php

namespace library\core;

use \library\template\Helpers;

abstract class Controller extends GeneriqueControl {
	private $layout = 'Default';
	private $responseHeader = "text/html";
	private $dataView = array();
	
	public function __construct() {
		parent::__construct();
	}
	
	protected function setLayout($name) {
		$pathLayout = APP_ROOT . "views/layout/{$name}.phtml";
		if (!empty($name) && file_exists($pathLayout)) {
			$this->layout = $name;
		}
	}
	
	protected function getLayoutPath() {
		return APP_ROOT . "views/layout/" . $this->layout . ".phtml";
		
	}
	
	protected function setResponseHeader($value) {
		$value = strtolower($value);
		$possibilities = array(
			"text" => "text/plain",
			"html" => "text/html",
			"json" => "application/json",
			"xml" => "application/xml",
		);
		if (array_key_exists($value, $possibilities)) {
			$this->responseHeader = $possibilities[$value];
			return true;
		}
		return false;
	}
	
	protected function getResponseHeader() {
		return $this->responseHeader;
	}
	
	protected function setDataView(array $data) {
		foreach ($this->getNameReserved() as $value) {
			if (array_key_exists($value, $data)) {
				throw new \Exception("variable name : '$value' is reserved by system");
			}
		}
		$this->dataView = array_merge($this->dataView, $data);
	}
	
	public function renderView($controllerName, $actionName) {
		$controllerName = str_replace('\\', '/', $controllerName);
		header("content-type: " . $this->getResponseHeader() . "; charset=utf-8");
		$pathView = APP_ROOT . str_replace('/application', 'views', $controllerName) . DS . str_replace('Action', '', lcfirst($actionName)) . '.phtml';
		$helpers = new Helpers();
		
		ob_start();
		if (file_exists($pathView)) {
			extract($this->dataView);
			include($pathView);
		}
		
		$viewContent = ob_get_clean();
		ob_start();
		include($this->getLayoutPath());
		$finalRender = ob_get_clean();
		$this->addFilesRender($finalRender);
		echo $finalRender;
	}
}