<?php
import("phpf.controllers.facete");
class Wbutton extends Facete 
{
    function Wbutton()
    {
        parent::Facete();
        $this->render(ApplicationContext::getCurrentView());
    }
    protected function btnClicked( $evt)
    {
            $evt->getComponent()->setText("You Clicked");
            $this->AjaxResponse();
    }
}
?>