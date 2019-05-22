<?php
class Singleton
{
   private static $_instances = array();
   /**
    * @param string $classname
    * @return Singleton
    */
  protected static function getInstance()
   {
      $classname = func_get_arg(0);
      if (! isset(self::$_instances[$classname]))
      {
         self::$_instances[$classname] = new $classname();
      }
      return self::$_instances[$classname];
   }

}
?>
