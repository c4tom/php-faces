<?php
import("phpf.controllers.Facescontroller");
import("phpf.events.ActionEvent");
import("phpf.listeners.ActionListener");
define("BASE_URL","http://localhost/phpFaces/web/");
class FForm extends FacesController implements ActionListener
{
    function FForm()
    {
        parent::FacesController();
        $this->addActionListener($this);
        $this->render("fform.phpf");
    }
    public function actionPerformed(ActionEvent $evt)
    {
         $this->label->setText("Your name : ".$this->textbox->getText()) ;
    }
}
?>