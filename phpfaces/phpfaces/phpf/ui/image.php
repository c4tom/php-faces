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
class Image extends Component {
    function load($args) {
        parent::load ( $args );
             if(isset($_POST[$this->attributes[name]])){
           $this->attributes[value]=$_POST[$this->attributes[name]];
           $this->attributes[src]=  $_POST[$this->attributes[name]];

             }
    

    }
    function getAttributes()
    {

        unset($this->attributes[text]);
        $this->attributes[value] =  $this->attributes[src];
       return parent::getAttributes();
        //return $this->att;
    }
    function Image(FacesController $controller,$args=null )
    {
        parent::Component($controller);
        if($args!=null)
        $this->load($args);

    }
    function startTag() {

        $attribute ="";
 unset($this->attributes[text]);

        $b =  '<input type ="image" '.$this->doAttributes()."/>";
        return $b;

    }


    function setSource($val) {
        $this->attributes[src]= $val;
        $this->update('src');
        $this->update('value');
    }

    function getsource() {
        return $this->attributes[src];
    }
}

?>
