<?php
/**
 * @name Message
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.ui
 * @version 1.0
 *
 */
require_once CLASS_PATH. "phpf".DS."ui".DS.'component.php';
class Message extends Component {
   
function Message(FacesController &$controller ,$args=null)
    {   
             parent::Component($controller,$args);
             $this->attributes[innerHTML]= $args[text];

    }
    function startTag($bind=null) {
        if($bind!=null)
        $this->attributes[innerHTML]= $bind;
        return '<span '.$this->doAttributes().'> '.$this->attributes[innerHTML]."</span>";

    }
    static function endTag() {
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