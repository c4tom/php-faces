<?php
/**
 * @name FacesCompiler
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.renderers
 * @version 1.0
 *
 */

require_once CLASS_PATH. "phpf".DS."renderers".DS.'IFacesRenderer.php';
require_once CLASS_PATH. "phpf".DS."renderers".DS.'FacesParser.php';

class FacesCompiler implements IFacesRenderer{
    private $parser;
    private $comns;
    private $buffer="";
    private $objectBuffer;
    private $callers;
    private $usedojoParser;
    private $controller;

    function  FacesCompiler($controller)
    {
        $this->parser = new FacesParser($this);
        $this->comns=array();
        $this->callers=array();
        $this->parser->isHTML=false;
        $this->parser->isExp=true;
        $this->controller= $controller;
    }
    public function compile($file)
    {
        $this->view= $file;
        if(ApplicationContext::getAppDirectory())
        $viewfile = SITE_ROOT . DS .ApplicationContext::getAppDirectory().DS. 'views' . DS .$this->view ;
        else
        $viewfile= $this->view;
        $this->parser->parse(file_get_contents($viewfile));

    }
    public function startFaces($string) {
        $this->buffer.=$text;
    }
    public function closingFaces($string) {

        $this->buffer.=$text;
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
            $this->objectBuffer .= "\n\$this->".$tag[attributes][name]."= new $tag[name](\$this,".$this->vars_dump(&$tag[attributes]).");";
            else
            $this->objectBuffer .= "\n\$this->".$tag[attributes][name]."->load(".$this->vars_dump(&$tag[attributes]).");";
            // $this->objectBuffer .= "\n\$this->".$tag[attributes][name]."= new $tag[name](\$this);";
            // $this->objectBuffer .= "\n\$this->".$tag[attributes][name]."->load(".$this->vars_dump(&$tag[attributes]).");";
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


    public function closingTag($tag) {

        $ns=  $this->getCompilerNS($tag["namespace"]);
        $beginTag = $this->parser->parsedArray[$tag["beginid"]];
        if($ns["type"]=="static")
        {
            $obj = $this->callers[$tag[namespace]];
            $this->buffer .=  call_user_method("end".$tag[name]."Tag",$obj, $tag[attributes]);
        }
        else{
        $this->buffer .= "\n<?php echo \$this->".$beginTag[attributes][name]."->endTag(); ?>";
        $this->objectBuffer .= "\n\$this->".$beginTag [attributes][name]."= new $beginTag[name](\$this,".$this->vars_dump(&$beginTag[attributes]).");";
        }
    }

    public function compilerTag($tag,$tagtext) {

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
                                $this->objectBuffer.= "\n\$this->set$key('$val');";
                            }
                            //efe($tag["attributes"]);
                            break;
                        }
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
                                    if(!is_array($beginTag [attributes][items]))
                                    $beginTag [attributes][items]=array();
                                    array_push($beginTag [attributes][items],$tag[attributes]);

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
                            if(isset($this->comns[$ns]))
                            return true;
                            return false;
                        }


                        function getCompilerNS($ns)
                        {
                            if(isset($this->comns[$ns]))
                            return $this->comns[$ns];
                            return false;
                        }


                       private  function vars_dump($vars)
                        {
                          //  efe($vars);


                            $code = "array(";
                            $count = count($vars);

                            foreach ($vars as $key => $value) {
                                if($key=="items")
                                {
                                    $code.= "\n'".items."' => array(";
                                    foreach ($value as $item)
                                    $code.= "\n".$this->vars_dump($item,true).",";
                                    $code.=")";
                                }
                                elseif($key!="bind")
                                $code.= "'".$key."' =>". "'".$value."',";
                                else
                                $code.= "'".$key."' =>".$value.",";
                            }
                            return $code.=")";
                        }

                    


                        public  function save()
                        {
                            $viewfile = SITE_ROOT . DS .ApplicationContext::getAppDirectory().DS. 'views' . DS.FACES_COMPILE_DIR.DS .preg_replace('[/|\^]', ".", $this->view) ;
                            //  echo $viewfile;
                            $file = fopen($viewfile."_gen.php","w");

                            if($this->objectBuffer!=null){
                                $this->buffer.= "\n".'<script type="text/javascript" src="'.BASE_URL.'dojo/dojo/dojo.js" '.(($this->usedojoParser)? 'djConfig="parseOnLoad: true"':''). '></script>';
                                $this->buffer.="\n".'<script type="text/javascript" src="'.BASE_URL.'faces.js" ></script>';
                                $this->buffer.="\n".'<script type="text/javascript">'.
                                $this->dojocodes."\n".
                                $this->getJSrcipt()."\n".'</script>';

                            }
                            fwrite( $file, "<?php".$code.$this->objectBuffer."?>");
                            fclose($file);
                            $file = fopen($viewfile,"w");
                            fwrite( $file, $this->buffer);
                            fclose($file);

                        }

                        private function getJSrcipt()
                        {
                            return  "dojo.addOnLoad(function() {\n".$this->jscript."});";
                            return $this->jscript;
                        }
                    }

                    ?>
