<?php
/**
 * @name DJCombobox
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.ui
 * @version 1.0
 *
 */
require_once CLASS_PATH. "phpf".DS."ui".DS.'component.php';
class DijitGenerator extends Singleton implements ScriptEventGenerator
{
    public $dojoevent;
    public static function getInstance() {
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
                $this->noform($val,$key,$attributes);
                else
                $this->yesform($val,$key,$attributes);
            }

        }
        return $this->dojoevent;
    }

  

    private function noform($val,$key,$attributes)
    {

        if(strtolower($key) == "onchange")
        {
            if(strtolower($val) == "actionevent")
            $this->dojoevent.='dojo.connect(dijit.byId("'.$attributes["id"].'") , "'."onChange".'", function(event) { actionPost("'.$attributes["id"].'","ValueChangedEvent","valueChanged") });'."\n" ;
            elseif(strtolower($val) == "ajaxevent")
            $this->dojoevent.='dojo.connect(dijit.byId("'.$attributes["id"].'") , "'."onChange".'", function(event) { ajaxPost("'.$attributes["id"].'","ValueChangedEvent","valueChanged") });'."\n" ;

        }else
        {
            if(strtolower($val) == "actionevent")
            $this->dojoevent.='dojo.connect(dijit.byId("'.$attributes["id"].'") , "'.$key.'", function(event) { actionPost("'.$attributes["id"].'",event) });'."\n" ;
            elseif(strtolower($val) == "ajaxevent")
            $this->dojoevent.='dojo.connect(dijit.byId("'.$attributes["id"].'") , "'.$key.'", function(event) { ajaxPost("'.$attributes["id"].'",event) });'."\n" ;
        }//unset($this->$key);
    }
    private function yesform($val,$key,$attributes)
    {
        if(strtolower($val) == "actionevent")
        $this->dojoevent.='dojo.connect(dijit.byId("'.$attributes["id"].'") , "'.$key.'", function(event) { actionPostForm("'.$attributes["forname"].'",'.'"'.$attributes["id"].'",event) });'."\n" ;
        elseif(strtolower($val) == "ajaxevent")
        $this->dojoevent.='dojo.connect(dijit.byId("'.$attributes["id"].'") , "'.$key.'", function(event) { ajaxPostForm("'.$attributes["forname"].'",'.'"'.$attributes["id"].'",event) });'."\n" ;
        //unset($this->$key);
    }

    public function getJScript()
    {
        return $this->dojoevent;
    }
}

/*
 *   <script>
  function setValue1(a) {
  alert(a);
   dojo.require("dojo.data.ItemFileReadStore");
  var data = {
items: [
    {name:"Alabama"},
    {name:"Alaska"},
    {name:"American Samoa"},
    {name:"Arizona"}
    ]};

      dijit.byId("combox1").store=new dojo.data.ItemFileReadStore({ data: data });

}
     // dojo.connect(dijit.byId("combox1") , "onChange", function(value) { ajaxPost("combox1","ValueChangedEvent","valueChanged") });
      </script>

 */
class DJComboBox extends Component {

    function load($args) {
        parent::load ( $args );
        //  $this->processEvent();
    }

    function DJComboBox(FacesController &$controller,$args)
    {
        parent::Component($controller,$args);

    }
    function startTag($bind=null) {

        // foreach($this as $key => $val)
        // $t.=" ".$key." = ". $val;
        if($bind!=null)
        $code=$this->processModel($bind);
        $b =  '<select  dojoType="dijit.form.ComboBox" ' . $this->doAttributes(). ' > '.$code;
        return $b;

    }
    static function endTag() {
        return "</select>";
    }

    public function __toString() {

        return $this->attributes->declaredfile . "." . $this->obj->name ;
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
        $this->update("model");

        unset($this->attributes[text]);
        unset($this->attributes[innerHTML]);

        if(System::getEvent() instanceof AjaxEvent){
            $this->attributes["_AJAX_UPDATER_"]="DjComboU";
            $this->update("_AJAX_UPDATER_");
        }

    }

    function getModel() {
        return $this->model;
    }
    function getSelected() {
        return $this->attributes[text];
    }
    public static function addDojo() {
        return 'dojo.require("dijit.form.ComboBox");';
    }
    public static function addJS() {
        return 'function DjComboU(id,data) {
var arr="var json ={items:[";
 for(var item in data["model"])
arr+="{name:"+\'"\'+data["model"][item]+\'"\'+"},";
arr+="]};";
eval(arr);
dojo.require("dojo.data.ItemFileReadStore");
dijit.byId(id).store=new dojo.data.ItemFileReadStore({ data: json });
dijit.byId(id).setValue(data["model"][0]);
}';
    }

    public static function getScriptGenerator()
    {
        return DijitGenerator::getInstance();
    }
}
?>
