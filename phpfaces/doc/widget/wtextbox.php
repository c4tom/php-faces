<?php
import("phpf.controllers.facescontroller");
import("phpf.events.actionevent");
import("phpf.listeners.actionlistener");
class Wtextbox extends FacesController implements ActionListener
{
    function Wtextbox()
    {
        parent::FacesController();
        $this->addActionListener($this);
        $this->render(ApplicationContext::getCurrentView());
    }
    public function actionPerformed(ActionEvent $evt)
    {
			$this->label->setText($this->text->gettext());
            $this->AjaxResponse();
    }
}
?>