<?php
import("phpf.controllers.facescontroller");
import("phpf.events.actionevent");
import("phpf.listeners.actionlistener");
class Wcheckbox extends FacesController implements ActionListener
{
    function Wcheckbox()
    {
        parent::FacesController();
        $this->addActionListener($this);
        $this->render(ApplicationContext::getCurrentView());
    }
    public function actionPerformed(ActionEvent $evt)
    {
            $this->label->setText($this->check->isSelected());
            $this->AjaxResponse();
    }
}
?>