<?php
/**
 * @name Entity
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package dbf
 * @version 1.0
 * @category PHP Faces ORM
 */
require_once CLASS_PATH."phpf".DS.'property'.DS.'PropertyChangeSupport.php';
abstract  class Entity {
    protected   $changeSupport =PropertyChangeSupport;
    public function Entity() {
       $this->changeSupport = new PropertyChangeSupport($this);
    }

    public final function __set($name, $value) {
    // echo "<br> $name, $value";
      $oldValue = $this->__get($name);
     $this->changeSupport->firePropertyChange($name, $oldValue, $value);
      if(method_exists($this, "set$name"))
      call_user_method("set$name", $this,$value);
      elseif(method_exists($this, "set"))
      call_user_method("set", $this,$name,$value);
    }

    public final function __get($name) {
       if(method_exists($this, "get$name"))
       return call_user_method("get$name", $this,$value);
       elseif(method_exists($this, "get"))
       return call_user_method("get", $this,$name);
    }

    public  function __dbset($name, $value)
    {
      //if(method_exists($this, "set$name"))
      //call_user_method("set$name", $this,$value);
      if(method_exists($this, "set"))
      call_user_method("set", $this,$name,$value);
    }
   abstract function set($name,$value) ;
   abstract function get($name) ;

}
?>
