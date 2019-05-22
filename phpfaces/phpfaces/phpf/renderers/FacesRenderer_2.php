<?php
/**
 * @name FacesRenderer
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.renderers
 * @version 1.0
 *
 */
require_once CLASS_PATH. "phpf".DS."renderers".DS.'IFacesRenderer.php';
require_once CLASS_PATH. "phpf".DS."renderers".DS.'FacesParser.php';
class FacesRenderer  implements IFacesRenderer{
    private $parser;
    private $comns;
    private $buffer="";
    private $callers;
    private $usedojoParser;
    private $controller;
    private $objectBuffer;
    private static $struces;
    private $ispattern=false;
    private $curPattern;


    function  FacesRenderer(FacesController $controller=null)
    {   $this->controller= $controller;
        $this->parser = new FacesParser($this);
        $this->comns=array();
        $this->callers=array();
        $this->parser->isHTML=false;
        $this->parser->isExp=true;

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
        $this->parser->parseFile($viewfile);
 echo $this->objectBuffer;
 echo $this->buffer;


    }


    public function startFaces($string) {
        $this->buffer.=$string;
    }

    public function closingFaces($string) {
        $this->buffer.=$string;
    }

    public function startingTag($tag) {

        $ns=  $this->getCompilerNS($tag["namespace"]);
        if($ns["type"]=="static")
        {
            $obj = $this->callers[$tag[namespace]];

            $this->buffer .=  call_user_method("start".$tag[name]."Tag",$obj, $tag[attributes]);

        }
        else{
            $classname= $tag[name];
            $property =$tag[attributes][name];
            if(!isset($tag[attributes][id]))
            $tag[attributes][id]=$property;

            if($tag[type]==FacesParser::START_END_TAG)
            if(!property_exists($this->controller, $property)||! ($this->controller->$property instanceof $classname))
            $this->buffer .= "\n<?php\$this->".$property."= new $classname(\$this,".$this->vars_dump(&$tag[attributes]).");?>";
            else
            $this->buffer .= "\n<?php\$this->".$property."->load(".$this->vars_dump(&$tag[attributes]).");?>";

            if(!$this->controller->isInterrupt()){

                $this->buffer .= "\n<?php echo \$this->".$property."->startTag();?>";
                $ren=true;
                $st= call_user_method("getScriptGenerator", $classname);
                if($st!=null)
                $this->jscript.= $st->processEvent($tag[attributes]);

                if(method_exists($tag[name], "addDojo"))
                $str = call_user_method("addDojo", $classname);
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

        if($ns["type"]=="static")
        {
            $obj = $this->callers[$tag[namespace]];
            $this->buffer .= call_user_method("end".$tag[name]."Tag",$obj, $tag[attributes]);
        }
        else{


            $this->buffer .= "\n<?php echo \$this->".$beginTag[attributes][name]."->endTag(); ?>";
            $this->buffer .= "\n<?php\$this->".$beginTag [attributes][name]."= new $beginTag[name](\$this,".$this->vars_dump(&$beginTag[attributes]).");?>";
        }
    }

    public function compilerTag(&$tag,$tagtext) {
        $this->ispattern=false;
        switch ($tag["name"]) {
            case "@import":{

                    import($tag["attributes"]["taglib"]);
                    $name= $tag["attributes"]["prefix"];
                    $this->comns[$name] = $tag["attributes"];
                    if($tag["attributes"]["type"]=="static")
                    {
                        $class= end(explode(".", $tag["attributes"]["taglib"]));
                        if(!class_exists($class))
                        throw new ErrorException("Tag library class not fount exception <li> Class Name : <b>$class</b> </li><li> Tag : <b> <$tagtext> </b></li>",3,0,$this->view,$tag['line']);

                        $this->callers[$name]= new  $class();

                    }

                    break;
                }
                case "@include":{
                        //import($tag[taglib]);
                        $this->buffer.="<?php include '".$tag["attributes"]["file"]."';?>\n";
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
                        //  <@set sayi="1" isim="bora" scope="session"/>
                        case "@set":{
                                foreach ($tag["attributes"] as $key => $val) {
                                    if($key!="scope")
                                    if($tag["attributes"][scope]=="session")
                                    /*$this->buffer.=("<?php \$_SESSION['$key'] = $val;?>\n");*/
                                    $_SESSION[$key]==$val;
                                    else
                                    /*$this->buffer.=("<?php $$key = $val;?>\n");*/
                                    $this->controller->append($key, $val);
                                }
                                break;
                            }
                            //  <@get var="$isim" select="user.isim"/>
                            case "@get":{
                                    $var= $tag["attributes"]["var"];
                                    $select= $tag["attributes"]["select"];
                                    if($tag["attributes"][scope]=="session")
                                    $this->controller->append($var, $_SESSION[$select]);


                                    else
                                    {
                                        $value=  $this->controller->getVar($select);
                                        if($value)
                                        $this->buffer.=("<?php $$var = $value;?>\n");

                                    }

                                    break;
                                }
                                case "@item":{

                                        $id= $this->parser->getEndOpenTag();
                                        $beginTag = &$this->parser->parsedArray[$id];

                                        if(!is_array($this->parser->parsedArray[$id] [attributes][items]))
                                        $this->parser->parsedArray[$id] [attributes][items]=array();
                                        array_push($this->parser->parsedArray[$id] [attributes][items],$tag[attributes]);


                                        break;
                                    }

                                    case "@pattern":{
                                            $name =$tag[attributes]["name"];
                                            FacesRenderer::$struces[$name]=$this->parser->getInsertId();
                                            $this->ispattern=true;
                                            $this->curPattern=$this->parser->getInsertId();
                                            break;

                                        }
                                        case "@hpattern":{
                                                $name =$tag[attributes]["name"];
                                                FacesRenderer::$struces[$name]=$this->parser->getInsertId();
                                                $this->ispattern=true;
                                                $this->curPattern=$this->parser->getInsertId();
                                                break;

                                            }

                                            case "@tag":{

                                                    $name =$tag[attributes]["name"];
                                                    $pattern_name= $tag[attributes][pattern];
                                                    $id= FacesRenderer::$struces[$pattern_name];
                                                    $pattern =$this->parser->parsedArray[$id];
                                                    $newtag =array();
                                                    $newtag[name]= $pattern[attributes]["extends"];
                                                    $newtag[namespace]= $pattern[attributes]["prefix"];
                                                    $newtag[type]= $tag[type];
                                                    $newtag[attributes] = array_merge($pattern[attributes], $tag[attributes]);
                                                    $tag= $newtag;
                                                    $this->startingTag($newtag);

                                                    break;
                                                }
                                                case "@htag":{

                                                        $name =$tag[attributes]["name"];
                                                        $pattern_name= $tag[attributes][pattern];
                                                        $id= FacesRenderer::$struces[$pattern_name];
                                                        $pattern =$this->parser->parsedArray[$id];
                                                
                                                        $this->buffer.=$pattern["innerHTML"];
                                                        break;
                                                    }

                                                }

                                            }

                                            public function closingCompilerTag($tag)
                                            {
                                                switch ($tag[name]) {
                                                    case "@tag":
                                                        {
                                                            $this->closingTag($tag);
                                                            $beginTag = $this->parser->parsedArray[$tag["beginid"]];
                                                            // efe($beginTag);
                                                            break;
                                                        }
                                                        default:
                                                            break;
                                                }
                                            }
                                           
                                            public function textNode($text) {
                                                if($this->ispattern)
                                                {
                                                    $this->parser->parsedArray[$this->curPattern];
                                                    $this->parser->parsedArray[$this->curPattern]["innerHTML"].= $text;
                                                }
                                                else
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

                                                foreach ($vars as $key => $value) {

                                                    if($key!="items")
                                                    if($key!="bind")
                                                    $code.= "'".$key."' =>". "'".$value."',";
                                                    else
                                                    $code.= "'".$key."' =>".$value.",";
                                                }
                                                if(isset($vars["items"])){
                                                    $code.= "\n'".items."' => array(";
                                                    foreach ($vars["items"] as $item)
                                                    $code.= "\n".$this->vars_dump($item,true).",";
                                                    $code.=")";
                                                }


                                                return $code.=")";
                                            }

                                        }

                                        ?>