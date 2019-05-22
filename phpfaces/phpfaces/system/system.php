<?php
/**
 * @name System
 * @author Hüseyin Bora Abacý
 * @copyright Hüseyin Bora Abacý
 * @package system
 * @version 1.0
 *
 */
Class System
{
    private static $event=null;
    public static function Error(Exception $ex) {
        $heading=$ex->__toString();

        require_once SITE_ROOT."errors".DS."error_general.php";


        die;
    }
    public static function handleException(Exception $e,$error=false) {
       
        $heading= "Throw Exception !";
        if($e instanceof  ErrorException)
         $heading= "Error Exception !";
         if($e instanceof  PDOException)
         $heading= "DataBase Exception !";
           require_once SITE_ROOT."errors".DS."error.php";
           die;
        
      
    }
    public static function handleError($code, $message, $filename, $lineno, $vars) {
       if($code ==E_USER_ERROR)
       echo "FATAl";
       if($code!=E_NOTICE&&$code!=E_STRICT)
       System::handleException(new ErrorException($message, $code, 0, $filename, $lineno),true);


    }
    function println($var) {
        printf("%s\n", $var);
    }
    /**
     * @return RequestEvent
     */
  public static  function getEvent() {
        if(System::$event==null)
        System::$event=   RequestEventFactory::processEvent();
        return System::$event;
    }

}

?>
