<?php
/**
 * @name Component
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.ui
 * @version 1.0
 *
 */
require_once CLASS_PATH."patterns".DS.'singleton.php';
interface ScriptEventGenerator
{
    public function processEvent($attributes);
}


class StCom extends Singleton implements ScriptEventGenerator
{
    public $dojoevent;
    private static $updater = "null";
    public static function getInstance($u="null") {
        self::$updater = $u;
        return parent::getInstance(__CLASS__);
    }
    public function processEvent($attributes)
    {
        $this->dojoevent="";
        foreach($attributes as $key => $val)
        {
            if(preg_match("[^on.]",$key))
            {
                if(!isset($attributes[forname]))
                $this->noneform($val,$key,$attributes);
                else
                $this->form($val,$key,$attributes);
            }

        }
        return $this->dojoevent;
    }

    private function noneform($val,$key,$attributes)
    {
        if(strtolower($val) == "actionevent")
        $this->dojoevent.='dojo.connect(dojo.byId("'.$attributes["id"].'") , "'.$key.'", function(event) { actionPost("'.$attributes["id"].'",event) });'."\n" ;
        elseif(strtolower($val) == "ajaxevent")
        $this->dojoevent.='dojo.connect(dojo.byId("'.$attributes["id"].'") , "'.$key.'", function(event) { ajaxPost("'.$attributes["id"].'",event) });'."\n" ;
        //unset($this->$key);
    }
    private function form($val,$key,$attributes)
    {
        if(strtolower($val) == "actionevent")
        $this->dojoevent.='dojo.connect(dojo.byId("'.$attributes["id"].'") , "'.$key.'", function(event) { actionPostForm("'.$attributes["forname"].'",'.'"'.$attributes["id"].'",event) });'."\n" ;
        elseif(strtolower($val) == "ajaxevent")
        $this->dojoevent.='dojo.connect(dojo.byId("'.$attributes["id"].'") , "'.$key.'", function(event) { ajaxPostForm("'.$attributes["forname"].'",'.'"'.$attributes["id"].'",event) });'."\n" ;
        //unset($this->$key);
    }

    public function getJScript()
    {
        return $this->dojoevent;
    }

}

class Component  {
    private $ischange=false;
    private $updateKeys=array("id");
    protected $valid=true;
    public $attributes;
    public $items=array();
    protected $controller;
    protected $att;
    public function Component(FacesController &$controller=null,&$att=array())
    {
        $this->controller=$controller;
        $this->load($att);
    }
    public function load($att) {
        $this->attributes=&$att;
        if(isset($att["items"])){
            $this->items= &$att[items];
            unset($this->attributes["items"]);
        }
    }

    function update($name) {
        if ($this->attributes[placeholder] == true)
        {
            if($this->controller->getStroge()=="session"){
                $holdername =$this->attributes[declaredfile].".".$this->attributes[name];
                $_SESSION[$holdername]=$this->attributes;
            }
            else{
                $this->controller->addXHTMLState($this->attributes[name],$this->attributes);
            }}
        $this->ischange=true;
        array_push($this->updateKeys,$name);
    }
    private function getHolder() {
        $holdername =$this->attributes[declaredfile].".".$this->attributes[name];
        if(isset($_SESSION[$holdername]));
        $this->attributes=$_SESSION[$holdername];
    }
    function unsetholder() {
        $holdername =$this->attributes[declaredfile].".".$this->attributes[name];
        if(isset($_SESSION[$holdername]))
        unset($_SESSION[$holdername]);
    }
    public function isChange()
    {
        return $this->ischange;
    }

    function getName()
    {
        return $this->attributes['name'];
    }
    function getAttributes()
    {
        return $this->attributes;

    }

    public function getUpdatedkeys() {
        return $this->updateKeys;
    }


    function setProperty($name,$value) {
        $this->attributes[$name]= $value;
        $this->update($name);

    }
    public function __set($name, $value) {
        if(method_exists($this, "set$name"))
        call_user_method("set$name", &$this, $value);
        else{
            $this->attributes[$name]=$value;
            $this->update($name);
        }
    }
    public function __get($name) {
        if(method_exists($this, "set$name"))
        return call_user_method("get$name", &$this, $value);
        else
        return $this->attributes[$name];
    }

    public function isValid() {
        return $this->valid;
    }

    public function setValid($valid) {
        $this->valid = $valid;
    }

    
function doAttributes($text=null) {
        $keys ="[^bind|^text|^innerHTML|^declaredfile|^forname|^placeholder|^test|^message|^success|^messagefor|^rule|^validator]";

        $html= '';
        if($this->attributes )
        foreach($this->attributes as $key => $value)
        {
         if(!is_object($value))
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

	 function doItemAttributes($item,$text=null) {
        $keys ="[^bind|^text|^innerHTML|^declaredfile|^forname|^placeholder]";

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
	

    public static function getScriptGenerator()
    {
        return StCom::getInstance();
    }

    public function __toString() {
        return $this->attributes[text];
    }
}
?>