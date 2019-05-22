<?php
import("phpf.controllers.Facescontroller");
import("phpf.events.ActionEvent");
import("phpf.listeners.ActionListener");
define("BASE_URL","http://localhost/phpFaces/web/");
class Simpleeventajax extends FacesController implements ActionListener
{
    function Simpleeventajax()
    {
        parent::FacesController();
        $this->addActionListener($this);
        $this->render("eventajax.phpf");
    }
    public function actionPerformed(ActionEvent $evt)
    {
        $evt->getComponent()->setText("You cliked me");
        $this->AjaxResponse();
     
    }
}
?>