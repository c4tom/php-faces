<?php
import("phpf.controllers.facescontroller");
class Htextbox extends FacesController 
{
protected $name="Bora";
    function Htextbox()
    {
        parent::FacesController();
		$this->render(ApplicationContext::getCurrentView());
        
    }
  
}
?>