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
class RadioGroup extends Component {
    function load($args) {
    parent::load ( $args );
        if(isset($_POST[$this->attributes[name]]))
        $this->attributes[checked]=$_POST["checked"];
    }

    function RadioGroup(FacesController &$controller,$args=null)
    {
               parent::Component($controller,$args);
               if($args!=null)
               $this->load($args);

    }
	
		 function doItemAttributes($item,$text=null) {
        $keys ="[^bind|^text|^innerHTML|^declaredfile|^forname|^placeholder|^label]";

        $html= '';
        if($item)
        foreach($item as $key => $value)
        {
            if(!preg_match ($keys,$key))
            {
                if(preg_match("[^on.]",$key))
                {
                    $val = strtolower($value);
                    if($val != "actionevent" && $val != "ajaxevent")
                    $html.="$key=".'"'.$value .'" ' ;
                }

                else
                $html.="$key=".'"'.$value .'" ' ;
            }

            if($key=="text")
            $html.= 'value="'. $value. '" ';

        }

        return $html;
    }
    function startTag() {
        $html="";
		$i=0;
		foreach ($this->items as $item){
        $html.= '  <input dojoType="dijit.form.RadioButton"  type="radio" ';
        $html .=  $this->doItemAttributes($item). ' name="'.$this->attributes[name].'"/>';
		$html .= '<label for="'.$this->attributes[name].$i.'">'.$item[label].'</label>';
		$i++;
		}
    return $html;
	  //   $html .=    '<label for="sum1"> Metallica </label>' 
       

    }
    static function endTag() {
    
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
