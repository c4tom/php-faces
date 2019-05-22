<?php
/**
 * @name Button
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.ui
 * @version 1.0
 *
 */
require_once CLASS_PATH. "phpf".DS."ui".DS.'component.php';
class Tab extends  Component {

    function load($args) {
        //parent::load ( $args );
$this->attributes= $args;
    }
    function Tab (FacesController &$controller,$args=null) {
        parent::Component($controller,$args);
    }



    function startTag() {
        $html= '<div dojoType="dijit.layout.TabContainer" ';
        $html.= $this->doAttributes();
        $html.="/>";
        return $html;
    }

        function endTag() {

        return "</div>";
    }
     public static function addDojo() {
        return ' dojo.require("dijit.layout.ContentPane");
           dojo.require("dijit.layout.TabContainer");
           dojo.require("dijit.form.Button");';
    }
}
?>