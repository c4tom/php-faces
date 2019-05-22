<?php
import("phpf.controllers.facescontroller");
class Wtextarea extends FacesController 
{
protected $name="Bora";
    function Wtextarea()
    {
        parent::FacesController();
		$this->render(ApplicationContext::getCurrentView());
        
    }
  
}
?>