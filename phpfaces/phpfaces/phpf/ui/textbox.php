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
class TextBox extends Component {


    function TextBox(FacesController &$controller,$args=null) {
        parent::Component($controller,$args);
    }

    function setText($val) {
        $this->attributes['text'] = $val;
          $this->update('text');
    }

    function getText() {
        return $this->attributes['text'];
    }

     function startTag($bind=null) {
        if($bind!=null)
        $this->attributes[text]= $bind;
        $html= '<input type="text" ';
        $html.= $this->doAttributes();
        $html.="/>";
        return $html;
    }
}
?>