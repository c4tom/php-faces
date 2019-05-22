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
class ValidationText extends  Component {

    function load($args) {
        //parent::load ( $args );
$this->attributes= $args;
    }
    function ValidationText (FacesController &$controller,$args=null) {
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
        $html= '<input type="text"    dojoType="dijit.form.ValidationTextBox" ';
        $html.= $this->doAttributes();
        $html.="/>";
        return $html;
    }
     public static function addDojo() {
        return 'dojo.require("dijit.form.ValidationTextBox");
   dojo.require("dojox.validate.regexp");
   dojo.require("dojo.parser");
';
    }
}
?>