<?php
/**
 * @name Dispatcher
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package system
 * @version 1.0
 *
 */
class Dispatcher
{
    static $app;
    private static $mode;

    static function getMode() {
        return Dispatcher::$mode;
    }

    public  static function getAppDirName() {
        $arr=explode("/",$_SERVER['REQUEST_URI']);
        $base = explode("//", ApplicationContext::getBaseURL());
        $cindex=substr_count($base[1],"/");

        $is_uri=false;

        if(isset($_GET[APPLICATION_TRRIGER])){
            Dispatcher::$app = $_GET[APPLICATION_TRRIGER];

        }
        elseif(isset($arr[$cindex])&&$arr[$cindex]!="")
        {
            Dispatcher::$app=$arr[$cindex];
            $is_uri= true;

        }
        else{
            ApplicationContext::setMode("index");
            Dispatcher::$app=DEFAULT_APPLICATION;
            return false;
        }
        if(Dispatcher::$app==DEFAULT_APPLICATION){
            ApplicationContext::setBaseURL(ApplicationContext::getBaseURL().Dispatcher::$app."/");
            return false;
        }
        $configfile = SITE_ROOT  .APPLICATIONS_DIRECTORY.DS. Dispatcher::$app .DS."config.php";

        if(file_exists($configfile))
        {
            require_once $configfile;
            self::dispatch(ApplicationContext::getAppDirectory(),ApplicationContext::getDefaultController(), ApplicationContext::getBaseURL(),false);
            return true;

        }

        return false;

    }


    public static function dispatch($appdir,$defcontroller,$base_url,$repeat=true)
    {
       ApplicationContext::setContext($appdir, $defcontroller,$base_url);
        if(self::getAppDirName()&&$repeat)
        {
            return;
        }

        Dispatcher::$mode= "app";
        $arr=explode("/",$_SERVER['REQUEST_URI']);
        $base = explode("//", ApplicationContext::getBaseURL());
        $do=null;
        $cindex = substr_count($base[1],"/");

        if(isset($_GET[CONTROLLER_TRRIGER]))
        $do = $_GET[CONTROLLER_TRRIGER];


        if(!$do&&isset($arr[$cindex]))
        if(!preg_match("/index\.php/", $arr[$cindex]))
        $do=$arr[$cindex];

        if(!$do)
        $do=ApplicationContext::getDefaultController();
        $do = ucfirst(strtolower($do));
        $controllerfile = SITE_ROOT . DS .ApplicationContext::getAppDirectory().DS. 'controllers' . DS . $do . '.php';
        $controller = null;

        if (!realpath ( $controllerfile )&&!CONTROLLER_EXISTS){

            $do=ApplicationContext::getDefaultController();
            $controllerfile = SITE_ROOT . DS .ApplicationContext::getAppDirectory().DS. 'controllers' . DS . $do . '.php';
            ApplicationContext::setMode("index");
        }/*fin*/
        if (realpath ( $controllerfile )) {
            require_once ($controllerfile);

            define("CONTROLLER_NAME",$do);
            $controller = ObjectFactory::getNewInstance($do);
          
        }
        else
        {
            $heading ="Page not found";
            $message="Please try again later";
            include_once 'errors/error_404.php';
        }

    }

    public static function dispatchphpf()
    {
        $request=$_SERVER["REQUEST_URI"];
        $dir = dirname($request);
        Dispatcher::$mode= "file";
        $controllername= basename($request ,".phpf");
        $controllerfile = realpath($_SERVER["DOCUMENT_ROOT"].$dir."/". $controllername . '.php');
        $viewfile =  realpath($_SERVER["DOCUMENT_ROOT"].$request);

        ApplicationContext::setCurrentView($viewfile);
         $host = $_SERVER [HTTP_HOST];
        $base = "http://".$host."".$dir."/";
        define("CURRENT_URL",$base);
        $curdir= str_replace("/", DS, $_SERVER["DOCUMENT_ROOT"].$dir).DS;
       // echo $curdir;
        chdir ($curdir);
        require_once  SITE_ROOT."applications".DS."config.php";
        ApplicationContext::setContext($curdir, $controllername);
        require_once($controllerfile);
        $controller = ObjectFactory::getNewInstance($controllername);
    }

   function dispatchclass($class) {
        $request=$_SERVER["REQUEST_URI"];
        $dir = dirname($request);
        Dispatcher::$mode= "file";
        $controllername= $class;
        $controllerfile = realpath($_SERVER["DOCUMENT_ROOT"].$dir."/". $controllername . '.php');

        $host = $_SERVER [HTTP_HOST];
        $base = "http://".$host."".$dir."/";
        define("CURRENT_URL",$base);
        $curdir= str_replace("/", DS, $_SERVER["DOCUMENT_ROOT"].$dir).DS;
        ApplicationContext::setContext($curdir, $controllername,BASE_URL);
        $controller = ObjectFactory::getNewInstance($controllername);
    }
}
?>