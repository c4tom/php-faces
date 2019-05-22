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
class Button extends Component {

 function Button(FacesController &$controller,$args=null)
    {
        parent::Component($controller,$args);
        //efe($args);

    }
    function startTag() {
 $b =  '<input type="button" '. $this->doAttributes()."/>";
             
        return  $b ;
    }


    public function __toString() {

        return $this->attributes[declaredfile] . "." . $this->obj->name ;
    }


    function setText($val) {
        $this->attributes['text']= $val;
        parent::update('text');
    }

    function getText() {
        return $this->attributes['text'];
    }

}
?>
