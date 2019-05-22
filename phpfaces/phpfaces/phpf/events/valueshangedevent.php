<?php
require_once CLASS_PATH. "phpf".DS."events".DS."facesevent.php";
class ValueChangedEvent extends FacesEvent
{
public function ValueChangedEvent(Component $c,RequestEvent $e)
{
$this->component=$c;
$this->requestEvent= $e;
$this->listener=ValueChangedListener;
$this->method="valueChanged";
}
public function getComponent()
{
return $this->component;
}
public function setComponent(Component $component)
{
$this->component=$component;
}
public function getListener()
{
return $this->listener;
}
}
?>
