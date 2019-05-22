<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of formatted
 *
 * @author Bora
 */
require_once CLASS_PATH. "phpf".DS."ui".DS.'component.php';
class Formatted extends Component {
    private $unformated=null;
   public function getFormatter() {
       return $this->attributes[formatter];
   }

   public function setFormatter($formatter) {
       $this->attributes[formatter] = $formatter;
   }


function Formatted(FacesController &$controller,$args=null) {
        parent::Component($controller,$args);
        $this->unformated = $this->attributes[text];
    }

    function setText($val) {
        $this->attributes['text'] = $val;
        $this->unformated = $this->attributes[text];
        $this->update('text');
    }

    function getText() {
        return $this->attributes['text'];
    }

private function format() {
    if(isset($this->attributes[formatter]))
    {
    $this->attributes[text]= call_user_method("format", $this->attributes[formatter],$this->unformated,$this->attributes[format]);
    }
}
    function startTag($bind=null) {
        if($bind!=null)
        $this->attributes[text]= $bind;
        $this->format();
        $html= '<input type="text" ';
        $html.= $this->doAttributes();
        $html.="/>";
        return $html;
    }
}
?>
