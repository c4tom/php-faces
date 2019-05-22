<?php
/**
 * @name Urim
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package io
 * @version 1.0
 * @category PHP Faces Lib
 */
 import("io.uri");
 import("patterns.singleton");
 class URIM extends URI 
{
private $c;
private $i;
private $pre;
private static $instance=null;
 function URIM($c,$i,$pre="")
   {
       parent::URI();
	   $this->c=$c;
	   $this->i=$i;
	   $this->pre=$pre;
	 
   }

   
   
  function run()
   {
    $reque  = $this->pre.$this->get($this->i);
      if(method_exists($this->c, $reque))
	  {
      call_user_method($reque, &$this->c);
	  return true;
	  }
	  return false;
   }
   
   static   function call($c,$i,$pre="")
   {
       if(URIM::$instance==null)
       URIM::$instance = new URIM($c,$i,$pre);
      return URIM::$instance->run();
      
   }
   
}
?>