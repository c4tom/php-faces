<?php
require_once CLASS_PATH. "phpf".DS."events".DS."facesevent.php";
class ActionEvent extends FacesEvent
{
public function ActionEvent(Component $c,RequestEvent $e)
{
$this->component=$c;
$this->requestEvent= $e;
$this->listener=ActionListener;
$this->method="actionPerformed";
}
public function getComponent()
{
return $this->component;
}
public function setComponent(Component $c)
{
$this->component=$c;
}

public function getListener()
{
return $this->listener;
}
}

?>
