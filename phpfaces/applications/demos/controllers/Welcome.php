<?php
import("phpf.controllers.ActionController");
class Welcome extends ActionController
{

    function Welcome() {
        parent::ActionController();
     
        $this->render("welcome.html");
    }

}
?>
