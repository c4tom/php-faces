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
class DJCheckbox extends Component {
    function load($args) {
    parent::load ( $args );
        if(isset($_POST[$this->attributes[name]]))
        $this->attributes[checked]=$_POST["checked"];
    }

    function DJCheckbox(FacesController &$controller,$args=null)
    {
               parent::Component($controller,$args);
               if($args!=null)
               $this->load($args);

    }
    function startTag() {
        $b =  '<input type="checkbox" dojoType="dijit.form.CheckBox" '. $this->doAttributes(). '/>';

        return $b;

    }
    static function endTag() {
       // return "<Button>";
    }

   
   function isSelected()
    {
        return $this->attributes[checked];
    }
   public static function addDojo() {
        return 'dojo.require("dijit.form.CheckBox");';

    }
  
   


}
?>
