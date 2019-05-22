<?php
import("phpf.controllers.facescontroller");
class Wradio extends FacesController 
{
    function Wradio()
    {
        parent::FacesController();
        $this->render(ApplicationContext::getCurrentView());
    }
   
}
?>