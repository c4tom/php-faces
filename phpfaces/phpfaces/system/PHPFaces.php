<?php
/**
 * @name System
 * @author H�seyin Bora Abac�
 * @copyright H�seyin Bora Abac�
 * @package system
 * @version 1.0
 *
 */
session_start();
ob_start();
error_reporting(E_ALL ^ E_NOTICE);

define("CLASS_PATH_DIR_NAME","phpfaces");
define("DS",DIRECTORY_SEPARATOR);
define("SYSTEM_DIR", realpath ( dirname ( __FILE__ )).DS);
define("SITE_ROOT",substr(SYSTEM_DIR,0,strlen(SYSTEM_DIR)-16));
define("CLASS_PATH",SITE_ROOT.CLASS_PATH_DIR_NAME.DS);

require_once(CLASS_PATH."patterns".DS."objectfactory.php");
require_once(SYSTEM_DIR."dispatcher.php");
require_once(SYSTEM_DIR."system.php");

set_error_handler(array("System", "handleError"));
set_exception_handler(array("System", "handleException"));


function import($class,$app=false)
{
   if($app==true)
   $basePath = ApplicationContext::getAppDirectory();
   else
   $basePath =CLASS_PATH_DIR_NAME;
    $end = strlen($class);
    $last=  $class[$end-2]. $class[$end-1];
  // echo $last;
   if($last==".*")
   {
    $dir = substr($class, 0, $end-2);
    $path = str_replace(".", DS,$dir );
  if ($opdir = opendir(SITE_ROOT.$basePath.DS.$path.DS)) {
    
    while (false !== ($file = readdir($opdir))) {
       // echo "$file\n<br>".end(explode(".", $dosya));
       if(end(explode(".", $file))=="php")
        require_once (SITE_ROOT.$basePath.DS.$path.DS.$file);
        
    }}
   }
   else
   {
   
  $path =strtolower(str_replace(".", DS,$class));
  require_once (SITE_ROOT.$basePath.DS.$path.".php");
   }
}


class  ApplicationContext
{
private static $APP_DIR;
private static $DEFAULT_CONTROLLER;
private static $BASE_URL;
private static $mode="uri";
private static $VIEW;
private static $APP_URL;
private static $pageVars=array();
static function setContext($appdir,$defcontroller,$base_url="none")
{
     self::$APP_DIR=$appdir;
     self::$DEFAULT_CONTROLLER=$defcontroller;
     self::$BASE_URL= $base_url;

     define("BASE_URL",$base_url);
     define("APP_URL",$base_url."/".$DEFAULT_CONTROLLER);
    self::$APP_URL = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];

}
   static function  setAppDirectory($appdir)
    {
        self::$APP_DIR = $appdir;
    }
static function setDefaultController($defcontroller)
    {
       self::$DEFAULT_CONTROLLER = $defcontroller;
    }

  static  function getAppDirectory()
    {
        return self::$APP_DIR;
    }

  static  function getDefaultController()
    {
        return self::$DEFAULT_CONTROLLER;
    }

      static  function getBaseURL()
    {
        return self::$BASE_URL;

    }
   static  function setBaseURL($url)
    {
         self::$BASE_URL= $url;

    }
       static  function setMode($mode)
    {
      self::$mode= $mode;


    }
   static  function getMode()
    {
        return self::$mode;

    }
    static  function setCurrentView($type)
    {
      self::$VIEW= $type;


    }
   static  function getCurrentView()
    {
        return self::$VIEW;

    }
    static  function getAppURI()
    {
        return self::$APP_URL;

    }
    static function addPageVar($name,$var) {
        self::$pageVars[$name]= $var;
    }
    static function getPageVars() {
        return self::$pageVars;
    }
}
?>