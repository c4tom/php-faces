<?php
require_once CLASS_PATH. "phpf".DS."events".DS."facesevent.php";
class MouseEvent extends FacesEvent
{
public function MouseEvent($component)
{
$this->component=$component;
$this->listener=MouseListener;
$this->method=$_REQUEST['eventmetot'];
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
