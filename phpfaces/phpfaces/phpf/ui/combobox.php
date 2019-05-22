<?php
/**
 * @name Combobox
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.ui
 * @version 1.0
 *
 */
require_once CLASS_PATH. "phpf".DS."ui".DS.'component.php';
class ComboBox extends Component {

    function ComboBox(FacesController &$controller,$args=null)
    {
                parent::Component($controller,$args);

    }
    function startTag($bind=null) {

          if($bind!=null)
        $code=$this->processModel($bind);
        $b =  '<select ' . $this->doAttributes(). ' > '.$code;
        return $b;
      
    }
    static function endTag() {
        return "</select>";
        return("echo '</select>'");
    }


    private function processModel($options=null)
    {

        $data="";
        // if($options !=null)
        //$this->attributes->model= $options;
        if(is_array($options)||is_object($options))	{
            //return "<option>arr</option>" ;
            foreach($options as $option)
            $data.="<option>$option</option>" ;
            return $data;

        }else
        return $model;

    }
    function setModel($val) {
        $this->attributes[model]=array();
        if(is_array($val))
        $this->attributes[model] = $val;
        if(is_object($val))
        foreach($val as $opt)
        array_push(&$this->attributes[model], call_user_method("__toString", &$opt));
        //$this->update();
        unset($this->attributes[text]);
        unset($this->attributes[innerHTML]);
        $this->update('model');
    }

    function getModel() {
        return $this->model;
    }
    function getSelected() {
        return $this->attributes[text];
    }


}
?>
