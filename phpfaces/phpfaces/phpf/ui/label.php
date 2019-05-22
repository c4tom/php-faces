<?php
/**
 * @name Label
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.ui
 * @version 1.0
 *
 */
require_once CLASS_PATH. "phpf".DS."ui".DS.'component.php';
class Label extends Component {
    function load($args) {
        parent::load ( $args );
    }

    function Label(FacesController &$controller ,$args=null)
    {   
             parent::Component($controller,$args);
             $this->attributes[innerHTML]= $args[text];
            
            
          
    }
    function startTag($bind=null) {
        if($bind!=null)
        $this->attributes[innerHTML]= $bind;
        $b =  '<span '.$this->doAttributes().'>
                '.$this->attributes[innerHTML];
        return $b;

    }
    static function endTag() {
        return "</span>";
    }

    public function __toString() {

        return $this->attributes->declaredfile . "." . $this->obj->name ;
    }

    function setText($val) {
        $this->attributes[innerHTML]= $val;
        $this->update('innerHTML');
    }

    function getText() {
        return $this->attributes[innerHTML];
    }
}
?>