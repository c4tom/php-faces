<?php
import("phpf.controllers.facescontroller");
class Ctextbox extends FacesController 
{
    function Ctextbox()
    {
        parent::FacesController();
        $this->render(ApplicationContext::getCurrentView());
    }

}
?>