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
class Group extends Component {
    function load($args) {
    parent::load ( $args );
        if(isset($_POST[$this->attributes[name]]))
        $this->attributes[checked]=$_POST["checked"];
    }

    function Group(FacesController &$controller,$args=null)
    {
               parent::Component($controller,$args);

    }
	
	
    function startTag() {
       
       

    }
    static function endTag() {
    
    }

   function getSelected()
    {
        return $_POST[$this->attributes[name]];
    }
 
  
}
?>
