<?php
/**
 * @name TextArea
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.ui
 * @version 1.0
 *
 */
require_once CLASS_PATH. "phpf".DS."ui".DS.'component.php';
class TextArea extends Component {

    function TextArea(FacesController $controller,$args=null)
    {
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

        $b = '<textarea '.$this->doAttributes(). '>';//.$this->attributes->innerHTML;
        return $b;

    }

    static function endTag() {
        return "</textarea>";

    }
}
?>