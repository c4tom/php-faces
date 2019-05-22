<?php
import("phpf.controllers.Facescontroller");
import("phpf.events.ActionEvent");
import("phpf.listeners.ActionListener");
class Dojox extends FacesController implements ActionListener
{
    function Dojox()
    {
        parent::FacesController();
        $this->addActionListener($this);

        $this->render(ApplicationContext::getCurrentView());



    }
    public function actionPerformed(ActionEvent $evt)
    {
        $this->textbox1->setText("aaaaaaaaaaaaaaa");
        if($evt->getRequestEvent()instanceof AjaxEvent)
        {
            $evt->getComponent()->setText("Click");
            //$this->button6->setText("denemek");
            $this->AjaxResponse();
        }
        else
        if($evt->getComponent()->getName()=="button2")

        $this->button2->setText("TEST STATE");
        $this->label1->setText("your name ".$this->textbox1->gettext()." (=");


    }
}
?>