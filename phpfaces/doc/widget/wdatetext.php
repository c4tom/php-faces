<?php
import("phpf.controllers.facescontroller");
import("phpf.events.actionevent");
import("phpf.listeners.actionlistener");
class Wdatetext extends FacesController implements ActionListener
{
    function Wdatetext()
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