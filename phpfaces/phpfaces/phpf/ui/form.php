<?php
/**
 * @name Form
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.ui
 * @version 1.0
 *
 */
require_once CLASS_PATH. "phpf".DS."ui".DS.'component.php';
class Form extends Component {

   
    function Form(FacesController &$controller,$args=null)
    {
        parent::Component($controller,$args);
        $this->attributes[method]="post";
        $this->attributes[enctype]="multipart/form-data";
    }

    function startTag() {
        $b = '<form '.$this->doAttributes(). '>';
        return $b;
        return 'echo("' . addslashes ( $b ) . '")';
    }

    function endTag() {
        return "</form>";
        return "echo '</form>'";
    }
}
?>
