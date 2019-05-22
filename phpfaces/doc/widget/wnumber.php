<?php
import("phpf.controllers.facescontroller");
class Wnumber extends FacesController 
{
    function Wnumber()
    {
        parent::FacesController();
        $this->render(ApplicationContext::getCurrentView());
    }
   
}
?>