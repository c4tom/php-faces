<?php
import("phpf.controllers.facescontroller");
class Wtab extends FacesController 
{
    function Wtab()
    {
        parent::FacesController();
        $this->render(ApplicationContext::getCurrentView());
    }
   
}
?>