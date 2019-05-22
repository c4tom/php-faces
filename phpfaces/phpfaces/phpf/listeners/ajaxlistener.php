<?php
interface AjaxListener extends FacesListener
{
public function requestAccept(AjaxEvent $event);
}
?>
