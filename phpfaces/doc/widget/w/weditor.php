<?php
import("phpf.controllers.facescontroller");
class Weditor extends FacesController 
{
    function Weditor()
    {
        parent::FacesController();
        $this->render(ApplicationContext::getCurrentView());
    }
   
}
?>