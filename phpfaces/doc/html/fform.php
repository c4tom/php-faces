<?php
import("phpf.controllers.facescontroller");
import("phpf.events.actionevent");
import("phpf.listeners.actionlistener");
class Fform extends FacesController implements ActionListener
{
    function Fform()
    {
        parent::FacesController();
        $this->addActionListener($this);
        $this->render(ApplicationContext::getCurrentView());
    }
    public function actionPerformed(ActionEvent $evt)
    {
         $this->label->setText("Your name : ".$this->textbox->getText()) ;
    }
}
?>