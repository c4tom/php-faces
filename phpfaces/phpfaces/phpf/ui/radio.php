<?php
/**
 * @name Checkbox
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.ui
 * @version 1.0
 *
 */
require_once CLASS_PATH. "phpf".DS."ui".DS.'component.php';
class Radio extends Component {
    function load($args) {
        parent::load ( $args );
        if(isset($_POST[$this->attributes[name]]))
        $this->attributes[checked]=$_POST["checked"];

    }

    function Radio(FacesController $controller,$args=null)
    {
        parent::Component($controller);
        if($args!=null)
        $this->load($args);

    }
    function startTag($bind=null) {
        if($bind)
        $this->attributes[checked]= "checked";
        $b =  '<input type="radio" ' .$this->doAttributes() .'/> ';
        //onclick="'.$this->onclick.'"
        return $b;
    }
   

    function isSelected()
    {
        return $this->attributes[checked];
    }

}
?>