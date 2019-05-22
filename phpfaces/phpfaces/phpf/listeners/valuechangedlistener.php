<?php
interface ValueChangedListener extends FacesListener
{
public function valueChanged(ValueChangedEvent $event);
}
?>