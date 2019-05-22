<?php
import("phpf.controllers.facescontroller");
class Vtextbox extends FacesController 
{
    function Vtextbox()
    {
        parent::FacesController();
        $this->render(ApplicationContext::getCurrentView());
    }

}
?>