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
class Editor extends  Component {

    function load($args) {
        //parent::load ( $args );
$this->attributes= $args;
    }
    function Editor (FacesController &$controller,$args=null) {
        parent::Component($controller,$args);
    }

  function setText($val) {
        $this->attributes[innerHTML] = $val;
           $this->update('innerHTML');
        unset($this->attributes[text]);

    }

    function getText() {
 
         if(isset($this->attributes[innerHTML]))
        return $this->attributes[innerHTML];
        return $this->attributes[text];
    }


    function startTag() {
        $html= '<textarea  dojoType="dijit.Editor" ';
        $html.= $this->doAttributes();
        $html.="/>";
        return $html;
    }

        function endTag() {

        return "</textarea>";
    }
     public static function addDojo() {
        return 'dojo.require("dijit.Editor");';
    }
}
?>