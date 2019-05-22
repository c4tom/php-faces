<?php
import("phpf.controllers.facete");
class Facetetest extends Facete
{
    function Facetetest()
    {
        parent::Facete();
        $this->render(ApplicationContext::getCurrentView());

    }
    protected function Button1Clicked($evt) {
        $this->Button1->settext("cliked");
    }
}
?>