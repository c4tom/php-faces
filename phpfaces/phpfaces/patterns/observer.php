<?php
interface Observer {
function notify(Observable $obj,$args);
}
class Observable
{
private $observers = array();
function notifyObservers($args) {

foreach($this->observers as $obj) {
$obj->notify($this,$args);
}
}
public function addObserver($obj) {
$this->observers[] = $obj;
}
}
?>
