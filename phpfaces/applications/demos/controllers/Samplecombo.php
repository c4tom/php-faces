<?php
import("phpf.controllers.facescontroller");
import("phpf.events.valueshangedevent");
import("phpf.listeners.valuechangedlistener");
class Samplecombo extends FacesController implements ValueChangedListener
{
    function Samplecombo()
    {
        parent::FacesController();
        $this->addValueChangedListener($this);
        $this->render("combo.phpf");
    }
    public function valueChanged(ValueChangedEvent $event) {
        $arr= array(
        "One"=>array(1,2,3,4,5,6,7,8,9,0),
        "Two"=>array(10,20,30,40,50,60,70,80,90),
        "Three"=>array(100,200,300,400,500,600,700,800,900),
        "Four"=>array(1000,2000,3000,4000,5000,6000,7000,8000,9000),
        "Five"=>array(10000,20000,30000,40000,50000,60000,70000,80000,90000)
        );
        $selected= $event->getComponent()->getSelected();
        $cname = $event->getComponent()->getName();
        if($cname=="combo")
        $this->list->setModel($arr[$selected]);
        $this->label->text ="You select :". $selected;
        $this->AjaxResponse();
    }
}
?>