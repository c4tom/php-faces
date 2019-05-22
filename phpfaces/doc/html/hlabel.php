<?php
import("phpf.controllers.facescontroller");
class Hlabel extends FacesController 
{
protected $name="Bora";
    function Hlabel()
    {
     
        parent::FacesController();
		$this->render(ApplicationContext::getCurrentView());
        
    }
  
}
?>