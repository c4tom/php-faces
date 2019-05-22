<?php
/**
 * @name TextBox
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.ui
 * @version 1.0
 *
 */
require_once CLASS_PATH. "phpf".DS."ui".DS.'component.php';
class Hidden extends Component {

    function load($args) {
        //parent::load ( $args );
$this->attributes= $args;
    }
    function Hidden(FacesController &$controller,$args=null) {
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
        $html= '<input type="hidden" ';
        $html.= $this->doAttributes();
        $html.="/>";
        return $html;
    }
}
?>