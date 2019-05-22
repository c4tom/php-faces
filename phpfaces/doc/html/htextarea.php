<?php
import("phpf.controllers.facescontroller");
class Htextarea extends FacesController 
{
protected $name="Bora";
    function Htextarea()
    {
        parent::FacesController();
		$this->render(ApplicationContext::getCurrentView());
        
    }
  
}
?>