<?php
/**
 * @name DjButton
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.ui
 * @version 1.0
 *
 */
require_once CLASS_PATH. "phpf".DS."ui".DS.'component.php';
class DJButton extends Component {
    function load($args) {
     $this->attributes=$args;
        if(isset($this->attributes[text]))
        $this->attributes[innerHTML]=&$this->attributes[text];
    }

    function DJButton(FacesController &$controller,$args=null)
    {
               parent::Component($controller,$args);
               if($args!=null)
               $this->load($args);

    }
    function startTag() {
        $b =  '<button dojoType="dijit.form.Button" '. $this->doAttributes(). '> '.$this->attributes[innerHTML]."</button>";

        return $b;

    }
    static function endTag() {
       // return "<Button>";
    }

   
    function setText($val) {
        $this->attributes[innerHTML]= $val;
        $this->update('innerHTML');
        unset($this->attributes[text]);
    }

    function getText() {
        return $this->attributes[innerHTML];
    }

   public static function addDojo() {
        return 'dojo.require("dijit.form.Button");';

    }
     public static function addFoother() {
        return 'dojo.require("dijit.form.Button");';

    }
     public static function addHeader() {
        return 'dojo.require("dijit.form.Button");';

    }


}
?>
