<?php

namespace library\core;

class Router {
    private static $instance;
    private static $protectedRoutes;
	

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public static function setProtectedRoutes(Array $routes){
        self::$protectedRoutes = $routes;
    }
    public static function getActionName($name) {
        return strTolower($name) . 'Action';
    }

    public static function getControllerName($name) {
        return '\application\controllers\\' . ucfirst(strtolower($name));
    }

    public static function getControllerPath($name) {
        return APP_ROOT . 'controllers' . DS . ucfirst(strtolower($name)) . '.php';
    }

    public static function dispatchModule($module = null, $action = null, $param = array())
    {
        if (is_null($module) || is_null($action)) {
            throw new \Exception("Modules name required");
        }
        if (file_exists(self::getModulePath($module)) && class_exists(self::getModuleName($module))) {
            $moduleClassName = self::getModuleName($module);
            $iModule = new $moduleClassName();

            if (method_exists($iModule, self::getActionName($action))) {
                $action = self::getActionName($action);

                call_user_func_array(array($iModule, $action), $param);
                call_user_func_array(array($iModule, "renderModule"), array($moduleClassName, $action));
            } else {
                throw new \Exception("Action: '$action' not found in module: '$module'");
            }
        } else {
            throw new \Exception("Module: '$module' not found");
        }
    }
    public static function getModuleName($name)
    {
        return '\application\modules\\' . ucfirst(strtolower($name));
    }

    public static function getModulePath($name)
    {
        return APP_ROOT . 'modules' . DS . ucfirst(strtolower($name)) . '.php';
    }
    private static function isValid($route){
        $matchRoute = false;
        foreach (self::$protectedRoutes as $value) {
            if(preg_match("#$value#", $route) === 1){
                $matchRoute = true;
                break;
            }
        }
        if($matchRoute && !isset($_SESSION['user'])){
            return false;
        }
        return true;
    }
    public static function dispatchPage($url)
    {
        $urlData = explode('/', $url);
        $controller = self::getControllerName('index');
        $action = self::getActionName('index');
	    if (!empty($urlData[0])) {
            if (file_exists(self::getControllerPath($urlData[0])) && class_exists(self::getControllerName($urlData[0]))) {
                $controller = self::getControllerName($urlData[0]);
                array_splice($urlData, 0, 1);
            } else {
                $controller = self::getControllerName('error');
            }
        }
        if(!self::isValid($url)){
            $controller = self::getControllerName('error');
            $action     = self::getActionName('forbidden');
            $urlData    = array();
        }

        $iController = new $controller;
        if (!empty($urlData[0])) {
            if (method_exists($iController, self::getActionName($urlData[0]))) {
                $action = self::getActionName($urlData[0]);
                array_splice($urlData, 0, 1);
            }
        }
	
	    call_user_func_array(array($iController, $action), $urlData);
        call_user_func_array(array($iController, 'renderView'), array($controller, $action));
    }
}