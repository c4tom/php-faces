<?php
import("phpf.controllers.facescontroller");
import("phpf.events.actionevent");
import("phpf.listeners.actionlistener");
class Himage extends FacesController implements ActionListener
{
    function Himage()
    {
        parent::FacesController();
        $this->addActionListener($this);

        $this->render(ApplicationContext::getCurrentView());



    }
    public function actionPerformed(ActionEvent $evt)
    {        $pic = $this->btn->getText();
             $this->image->setSource(BASE_URL."faces/html/$pic");
			 if($pic=="orangefaces.png")
			  $this->btn->setText("bluefaces.png");
			  else
			 $this->btn->setText("orangefaces.png");
            $this->AjaxResponse();


    }
}
?>