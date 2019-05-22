<?php
import("phpf.controllers.facescontroller");
class Waccor extends FacesController 
{
    function Waccor()
    {
        parent::FacesController();
        $this->render(ApplicationContext::getCurrentView());
    }
   
}
?>