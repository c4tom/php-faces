<?php
/**
 * @name Accordion
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.ui
 * @version 1.0
 *
 */
require_once CLASS_PATH. "phpf".DS."ui".DS.'component.php';
class Accordion extends  Component {

    function load($args) {
        //parent::load ( $args );
$this->attributes= $args;
    }
    function Accordion (FacesController &$controller,$args=null) {
        parent::Component($controller,$args);
    }

    function setText($val) {
        $this->attributes['text'] = $val;
          $this->update('text');
    }

    function getText() {
        return $this->attributes['text'];
    }

    function startTag() {
        $html= '<div dojoType="dijit.layout.AccordionContainer" ';
        $html.= $this->doAttributes();
        $html.="/>";
        return $html;
    }

        function endTag() {

        return "</div>";
    }
     public static function addDojo() {
        return 'dojo.require("dijit.layout.AccordionContainer");';
    }
}
?>