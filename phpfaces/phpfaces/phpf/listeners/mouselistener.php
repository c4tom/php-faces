<?php
interface MouseListener extends FacesListener
{
public function mouseOver(MouseEvent $event);
public function mouseOut(MouseEvent $event);
public function mouseDown(MouseEvent $event);
public function mouseUp(MouseEvent $event);
public function mousedbClick(MouseEvent $event);
}
?>
