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
class TabPane extends  Component {

    function load($args) {
        //parent::load ( $args );
$this->attributes= $args;
    }
    function TabPane (FacesController &$controller,$args=null) {
        parent::Component($controller,$args);
    }

   

    function startTag() {
        $html= '<div  dojoType="dijit.layout.ContentPane" ';
        $html.= $this->doAttributes();
        $html.="/>";
        return $html;
    }

        function endTag() {

        return "</div>";
    }
  
}
?>