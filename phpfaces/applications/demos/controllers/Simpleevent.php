<?php
import("phpf.controllers.Facescontroller");
import("phpf.events.ActionEvent");
import("phpf.listeners.ActionListener");
define("BASE_URL","http://localhost/phpFaces/web/");
class Simpleevent extends FacesController implements ActionListener
{
    function Simpleevent()
    {
        parent::FacesController();
        $this->addActionListener($this);
        $this->render("event.phpf");
    }
    public function actionPerformed(ActionEvent $evt)
    {
        $evt->getComponent()->setText("You cliked me");
     
    }
}
?>