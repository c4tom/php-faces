<?php
import("phpf.controllers.facescontroller");
class Wgrid extends FacesController 
{
    function Wgird()
    {
        parent::FacesController();
        $this->render(ApplicationContext::getCurrentView());
    }
   
}
?>