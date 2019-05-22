<?php
import("phpf.controllers.facescontroller");
class Wnumbers extends FacesController 
{
    function Wnumbers()
    {
        parent::FacesController();
        $this->render(ApplicationContext::getCurrentView());
    }
   
}
?>