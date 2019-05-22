<?php
import("phpf.controllers.Facescontroller");
import("phpf.events.ActionEvent");
import("phpf.listeners.ActionListener");
import("server.session");
class Callculator extends FacesController implements ActionListener
{
    private $session;
    function  Callculator()
    {
	echo BASE_URL;
        parent::FacesController();
        $this->addActionListener($this);
        $this->session = new Session();
        $this->render(ApplicationContext::getCurrentView());

    }
    public function actionPerformed(ActionEvent $evt) {
        $oldValue =  ltrim($this->resultbox->getText());
        $postValue = $evt->getComponent()->getText();
        $value=0;
        if(!ereg("[0-9]|[\.]",$postValue)){
            if($this->store->text=="0"){
                if($postValue=="+/-")
                $value =  $oldValue[0]=="-"?substr($oldValue,1):"-".$oldValue;
                else
                $value=" ";
            }
            else
            {
                if($postValue=="+")
                $value= doubleval($oldValue)+doubleval($this->store->text);
                elseif($postValue=="*")
                $value= doubleval($oldValue)*doubleval($this->store->text);
                elseif($postValue=="-")
                $value= doubleval($this->store->text)-doubleval($oldValue);
                elseif($postValue=="/")
                $value= doubleval($this->store->text)/doubleval($oldValue);
                elseif($postValue=="+/-")
                $value =  $oldValue[0]=="-"?substr($oldValue,1):"-".$oldValue;
                $oldValue="0";

            }
            if($postValue=="C")
            {
                $value=" ";
                $oldValue="0";
            }
            if($postValue!="+/-")
            $this->store->settext($oldValue);
        }
        else
        $value= $oldValue.$postValue;
        $this->resultbox->setText($value);
        $this->AjaxResponse();
    }
}
?>
