<?php
import("phpf.controllers.facescontroller");
import("phpf.events.valueshangedevent");
import("phpf.listeners.valuechangedlistener");
class Wcombo extends FacesController implements ValueChangedListener
{
    function Wcombo()
    {
        parent::FacesController();
        $this->addValueChangedListener($this);
        $this->render(ApplicationContext::getCurrentView());
    }
    public function valueChanged(ValueChangedEvent $event) 
    { 
            $this->label->setText($this->combo->getSelected());
			$this->AjaxResponse();
           
    }
}
?>