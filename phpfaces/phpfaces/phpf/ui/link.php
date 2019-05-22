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
class Link extends Component {


    function Link(FacesController &$controller ,$args=null)
    {   
             parent::Component($controller,$args);
             $this->attributes[innerHTML]= $args[text];
            
            
          
    }
    function startTag($bind=null) {
        if($bind!=null)
        $this->attributes[innerHTML]= $bind;
        if(!isset($this->attributes[href]))
        $this->attributes[href]="javascript:void(0)";
        $b =  '<a  '.$this->doAttributes().'>
                '.$this->attributes[innerHTML]."</a>";
        return $b;

    }
    static function endTag() {
        return "</a>";
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