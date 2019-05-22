<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of compiler
 *
 * @author Bora
 */


/**
 * Derleyici tagleri
 * @var  bir değişken oluştur
 * <@var $isim="true"/>
 * @definenation
 * <@defination eventvalitation="true"/>
 * <@defination viewstate="true" stroge="xhtml" or stroge="session"/>
 * @import phfns 'i ns taglib ile ilişkilendirecek
 * <@import phpfns="com" taglib="phpf.components.*"/>
 * @include dosyayı include edecek
 * <@include file="test.phpf" copiled="true"/>
 */
//require_once 'compiler.php';

require_once CLASS_PATH. "phpf".DS."renderers".DS.'IFacesRenderer.php';
require_once CLASS_PATH. "phpf".DS."renderers".DS.'FacesParser.php';

class FacesPreRenderer implements IFacesRenderer{
    private $parser;
    private $comns;
    private $buffer="";
    private $objectBuffer;
    private $callers;
    private $usedojoParser;
    private $pre=true;
    private $chars=array();
    private $controller;

    function  FacesPreRenderer(FacesController $controller=null)
    {
        $this->parser = new FacesParser($this);
        $this->comns=array();
        $this->callers=array();
        $this->parser->isHTML=false;
        $this->parser->isExp=true;
        $this->controller= $controller;
        // $this->parser->parse(file_get_contents("template2.html"));
        // efe($this->parser->parsedArray);
        //$ser= base64_encode(serialize( $this->parser->parsedArray));
        // efe($this->comns);
        // echo $this->buffer;


    }
    public function render($file)
    {
        $this->view= $file;
        if(file_exists(ApplicationContext::getAppDirectory()))
        $viewfile = $file;
        elseif(ApplicationContext::getAppDirectory())

        $viewfile = SITE_ROOT . DS .ApplicationContext::getAppDirectory().DS. 'views' . DS .$this->view ;
        else
        $viewfile= $this->view;
        $this->parser->parsefile($viewfile);
        $__data__= $this->buffer;
        $this->buffer="";

        $this->pre=false;
        ob_clean();
        ob_start();
        $this->controller->_eval($__data__);
        $data = ob_get_contents();
        ob_end_clean();
        $this->chars=  array_reverse($this->chars);
        $this->parser->parsedArray=array();
        $this->parser->isExp=true;
        $this->parser->parse($data);
    }
   
    public function saveArray() {
        $file = fopen($this->view.".parsed","w");
        fwrite($file,serialize($this->parser->parsedArray));
        fclose($file);
    }

    public function startFaces($string) {
        if($this->pre){
            $this->buffer.=" <faces>";
            array_push($this->chars,$string);
            return;
        }
        if(!$this->controller->isInterrupt())
        $this->buffer.= array_pop($this->chars);
    }
    public function closingFaces($string) {
        if($this->pre){
            $this->buffer.=" </faces>";
            array_push($this->chars,$string);
            return;
        }
        if(!$this->controller->isInterrupt())
        $this->buffer.= array_pop($this->chars);
    }

    public function startingTag($tag) {
        $ns=  $this->getCompilerNS($tag["namespace"]);

        if($ns["type"]=="call")
        {
            $obj = $this->callers[$tag[namespace]];
            $this->buffer .= "\n<?php  ". call_user_method("start".$tag[name]."Tag",$obj, $tag[attributes]) ." ?>";
        }
        elseif(!$this->pre){
            $classname= $tag[name];
            $property =$tag[attributes][name];

         /* $this->controller->$property = new $classname($this->controller);
            $this->controller->$property->load($tag[attributes]);*/
            // if(!property_exists($this->controller, $property)||! ($this->controller->$property instanceof $classname))
            // efe($tag["attributes"]);
            // $this->controller->componentFactory($property, $classname, $tag[attributes]);
            $this->objectBuffer .= "\n\$this->".$property."= new $classname(\$this);";
            $this->objectBuffer .= "\n\$this->".$property."->load(".$this->vars_dump(&$tag[attributes]).");";

            if(!$this->controller->isInterrupt()){
                $this->buffer .= "\n<?php echo \$this->".$tag[attributes][name]."->startTag(); ?>";
                $ren=true;
                $st= call_user_method("getScriptGenerator", $tag[name]);
                if($st!=null)
                $this->jscript.= $st->processEvent($tag[attributes]);

                if(method_exists($tag[name], "addDojo"))
                $str = call_user_method("addDojo", $tag[name]);
                if($str!=null || $str!=""){
                    $this->dojocodes.= "\n".$str;
                    $this->usedojoParser=true;
                }

            }
        }

    }
    public function closingTag($tag) {

        $ns=  $this->getCompilerNS($tag["namespace"]);
        $beginTag = $this->parser->parsedArray[$tag["beginid"]];

        if($ns["type"]=="call")
        {
            $obj = $this->callers[$tag[namespace]];
            $this->buffer .= "\n<?php  ". call_user_method("end".$tag[name]."Tag",$obj, $tag[attributes]) ." ?>";
        }
        elseif(!$this->pre)
        $this->buffer .= "\n<?php echo \$this->".$beginTag[attributes][name]."->endTag(); ?>";
    }

    public function compilerTag($tag,$tagtext) {

        switch ($tag["name"]) {
            case "@import":{

                    import($tag["attributes"]["taglib"]);
                    $name= $tag["attributes"]["phpfns"];
                    $this->comns[$name] = $tag["attributes"];
                    if($tag["attributes"]["type"]=="call")
                    {
                        $class= end(explode(".", $tag["attributes"]["taglib"]));
                        $this->callers[$name]= new CoreTags();
                    }

                    break;
                }
                case "@include":{
                        //import($tag[taglib]);
                        $this->buffer.="<?php include '".$tag["attributes"]["file"]."';?>\n";
                        break;
                    }
                    case "@finclude":{
                            //import($tag[taglib]);
                      /* $this->buffer.="<?php \$this->compile('".$tag["attributes"]["file"]."');?>\n";*/
                            break;
                        }
                        case "@definition":{
                                //import($tag[taglib]);
                                //array_push($this->comns, $tag["attributes"]["phpfns"]);
                                foreach ($tag["attributes"] as $key => $val) {
                                    // $this->objectBuffer.= addslashes("\n\$this->set$key($val);");
                                    call_user_method("set$key", &$this->controller,$val);
                                }
                                //efe($tag["attributes"]);
                                break;
                            }
                            case "@var":{
                                    foreach ($tag["attributes"] as $key => $val) {
                                        $this->buffer.=("<?php $$key = $val;?>\n");
                                    }
                                    break;
                                }
                                case "@item":{
                                        if($this->pre){
                                            $id= $this->parser->getEndOpenTag();
                                            $beginTag = &$this->parser->parsedArray[$id];
                                            if(!is_array($beginTag [attributes][items]))
                                            $beginTag [attributes][items]=array();
                                            array_push($beginTag [attributes][items],$tag[attributes]);
                                        }
                                        else

                                        $this->buffer.="<$tagtext>";


                                        break;
                                    }
                                }
                            }

                            public function textNode($text) {
                                $this->buffer.=$text;
                            }

                            public function startingPHPTag($tag) {
                                $this->buffer.="<?php $tag[code] ?>";
                            }

                            function isCompilerNS($ns)
                            {
                                if(isset($this->comns[$ns])){
                                    if($this->pre==true && $this->comns[$ns]["type"]!="call")
                                    return false;
                                    return true;
                                }
                                return false;
                            }


                            private function getCompilerNS($ns)
                            {
                                if(isset($this->comns[$ns]))
                                return $this->comns[$ns];
                                return false;
                            }

                            private function getJSrcipt()
                            {
                                return  "dojo.addOnLoad(function() {\n".$this->jscript."});";
                                return $this->jscript;
                            }

                            function getCode() {
                                //if($this->objectBuffer!=null){
                                $this->buffer.= "\n".'<script type="text/javascript" src="'.BASE_URL.'dojo/dojo/dojo.js" '.(($this->usedojoParser)? 'djConfig="parseOnLoad: true"':''). '></script>';
                                $this->buffer.="\n".'<script type="text/javascript" src="'.BASE_URL.'faces.js" ></script>';
                                $this->buffer.="\n".'<script type="text/javascript">'.
                                $this->dojocodes."\n".
                                $this->getJSrcipt()."\n".'</script>';//}
                                return $this->buffer;
                            }
                            function getObjects() {
                                return $this->objectBuffer;
                            }
                            private  function vars_dump($vars)
                            {
                                $code = "array(";
                                $count = count($vars);
                                $i=0;
                                foreach ($vars as $key => $value) {
                                    if($key=="bind")
                                    $code.= "'".$key."' =>".$value.",";
                                    else
                                    $code.= "'".$key."' =>". "'".$value."',";

                                }
                                return $code.=")";
                            }
                        }

                        ?>
