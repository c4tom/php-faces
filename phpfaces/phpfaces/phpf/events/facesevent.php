<?php
require_once CLASS_PATH. "phpf".DS."events".DS."systemevents.php";
abstract class FacesEvent
{
protected $name;
protected $component;
protected $id;
protected $method;
protected $listener;
public $requestEvent;
abstract public function getComponent();
abstract public function setComponent(Component $c);
public function isConfirm(){return true;}
public function getListener()
{
return $this->listener;
}
public function getMethod()
{
return $this->method;
}
public function getRequestEvent()
{
return $this->requestEvent;
}
public function FacesEvent(Component $c,RequestEvent $e)
{
   
}
}
?>
