<?php
/**
 * @name FacesRenderer
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.renderers
 * @version 1.0
 *
 */
require_once CLASS_PATH. "phpf".DS."renderers".DS.'Header.php';
require_once CLASS_PATH. "phpf".DS."renderers".DS.'IFacesRenderer.php';
require_once CLASS_PATH. "phpf".DS."renderers".DS.'FacesParser.php';


class FacesRenderer  implements IFacesRenderer {
    private $parser;
    static $comns=array();
    static $buffer="";
    static $callers=array();
    private  static $header;
    private $usedojoParser;
    private $controller;
    static $objectBuffer;
    private static $struces;
    private $ispattern=false;
    private $curPattern;
    var $dojocodes;
    static  $jscript;
    private static $classes=array();
    private $classesjs="";

    function  FacesRenderer(FacesController $controller=null) {   $this->controller= $controller;
        $this->parser = new FacesParser($this);
        // $this->comns=array();
        // FacesRenderer::$callers=array();
        $this->parser->isHTML=false;
        $this->parser->isExp=true;
        FacesRenderer::$header= new Header();
    }


    public function render($file) {
        $this->view= $file;
        if(file_exists(ApplicationContext::getAppDirectory()))
            $viewfile = $file;
        elseif(ApplicationContext::getAppDirectory())
            $viewfile = SITE_ROOT . DS .ApplicationContext::getAppDirectory().DS. 'views' . DS .$this->view ;
        else
            $viewfile= $this->view;

        //  echo FacesRenderer::$objectBuffer;
        // echo FacesRenderer::$buffer;
        $this->parser->parseFile($viewfile);
        return FacesRenderer::$header;
    }


    public function startFaces($string) {
        FacesRenderer::$buffer.=$string;
    }

    public function closingFaces($string) {
        FacesRenderer::$buffer.=$string;
    }

    public function startingTag(&$tag) {

        $ns=  $this->getCompilerNS($tag["namespace"]);
        if($ns["type"]=="static") {
            $obj = FacesRenderer::$callers[$tag[namespace]];
             if($this->ispattern) {
                FacesRenderer::$struces[$this->curPattern]["innerHTML"].= call_user_method("start".$tag[name]."Tag",$obj, $tag[attributes]);
            }
            else
           FacesRenderer::$buffer .=  call_user_method("start".$tag[name]."Tag",$obj, $tag[attributes]);
        }
        elseif($ns["type"]=="pattern") {
            $this->startingTag($this->patternUI($tag));
            return;
        }
        elseif($ns["type"]=="htmlpattern") {
            $this->patternHTML($tag);
            return;
        }
        else {

            $classname= $tag[name];
            if(isset($tag[attributes][id]))
                $tag[attributes][name]=$tag[attributes][id];
            elseif(isset($tag[attributes][name]))
                $tag[attributes][id]=$tag[attributes][name];
            // $tag[attributes][header]= $this->parser->header;
            $property =$tag[attributes][name];
            if($tag[type]==FacesParser::START_END_TAG)
                if(!property_exists($this->controller, $property)||! ($this->controller->$property instanceof $classname))
                    FacesRenderer::$objectBuffer .= "\n\$this->".$property."= new $classname(\$this,".$this->vars_dump(&$tag[attributes]).");";
                // $this->controller->$property =new $classname($this->controller,&$tag[attributes]);
                else
                    FacesRenderer::$objectBuffer .= "\n\$this->".$property."->load(".$this->vars_dump(&$tag[attributes]).");";
            //$this->controller->$property->load(&$tag[attributes]);
            if(!$this->controller->isInterrupt()) {
                $bind =$tag[attributes][bind];
                if($this->ispattern) 
                FacesRenderer::$struces[$this->curPattern]["innerHTML"].="\n<?php echo \$this->".$property."->startTag($bind);?>";
              else
              FacesRenderer::$buffer .= "\n<?php echo \$this->".$property."->startTag($bind);?>";
                if($tag[type]==FacesParser::START_END_TAG)
                    $this->generate($tag);

            }
        }

    }

    private function generate($tag) {

        if(!isset($tag[attributes][id]))
            $tag[attributes][id]=$tag[attributes][name];
        $classname= $tag[name];
        $st= call_user_method("getScriptGenerator", $classname);
        if($st!=null)
            FacesRenderer::$jscript.= $st->processEvent($tag[attributes]);
        if(!in_array($classname,FacesRenderer::$classes)) {
            if(method_exists($tag[name], "addDojo"))
                $str = call_user_method("addDojo", $classname);
            if($str!=null || $str!="") {
                $this->dojocodes.= "\n".$str;
                $this->usedojoParser=true;
            }
            if(method_exists($tag[name], "addJS"))
                $this->classesjs.= call_user_method("addJS", $classname);
            array_push(FacesRenderer::$classes, $classname);
        }
    }

    public function closingTag($tag) {
        $ns=  $this->getCompilerNS($tag["namespace"]);
        $beginTag = $this->parser->parsedArray[$tag["beginid"]];

        if($ns["type"]=="static") {
            $obj = FacesRenderer::$callers[$tag[namespace]];
           if($this->ispattern) {
                FacesRenderer::$struces[$this->curPattern]["innerHTML"].= call_user_method("end".$tag[name]."Tag",$obj, $beginTag[attributes]);
            }else
           FacesRenderer::$buffer .= call_user_method("end".$tag[name]."Tag",$obj, $beginTag[attributes]);
        }
        else {
              if($this->ispattern) 
                FacesRenderer::$struces[$this->curPattern]["innerHTML"].="\n<?php echo \$this->".$beginTag[attributes][name]."->endTag(); ?>";
            else
            FacesRenderer::$buffer .= "\n<?php echo \$this->".$beginTag[attributes][name]."->endTag(); ?>";
            /*$this->controller->$beginTag [attributes][name] =new $beginTag[name]($this->controller,&$beginTag[attributes]);*/
            FacesRenderer::$objectBuffer .= "\n\$this->".$beginTag [attributes][name]."= new $beginTag[name](\$this,".$this->vars_dump(&$beginTag[attributes]).");";
            $this->generate($beginTag);

        }
    }


    public function compilerTag(&$tag,$tagtext) {
        $this->ispattern=false;
        switch ($tag["name"]) {
            case "@import": {
                    import($tag["attributes"]["taglib"]);
                    $name= $tag["attributes"]["prefix"];
                    FacesRenderer::$comns[$name] = $tag["attributes"];
                    if($tag["attributes"]["type"]=="static") {
                        $class= end(explode(".", $tag["attributes"]["taglib"]));
                        if(!class_exists($class))
                            throw new ErrorException("Tag library class not fount exception <li> Class Name : <b>$class</b> </li><li> Tag : <b> <$tagtext> </b></li>",3,0,$this->view,$tag['line']);
                        FacesRenderer::$callers[$name]= new  $class();
                    }
                    break;
                }
            case "@include": {
                    FacesRenderer::$buffer.="<?php include '".$tag["attributes"]["file"]."';?>\n";
                    break;
                }
            case "@face": {
                    $renderer = new FacesRenderer($this->controller);
                    $renderer->render($tag["attributes"]["file"]);
                    //$this->objectBuffer.=$renderer->getObjects();
                    // FacesRenderer::$buffer.=$renderer->getBuffer();
                    // $this->jscript.= $renderer->jscript;

                    break;
                }

            case "@definition": {
                    foreach ($tag["attributes"] as $key => $val) {
                        call_user_method("set$key", &$this->controller,$val);
                    }
                    break;
                }
            //  <@set sayi="1" isim="bora" scope="session"/>
            case "@set": {
                    foreach ($tag["attributes"] as $key => $val) {
                        if($key!="scope")
                            if($tag["attributes"]["scope"]=="session")
                                $_SESSION[$key]=$val;

                            else {
                                $this->controller->append($key, $val,false);
                            }
                    }
                    break;
                }
            //  <@get var="$isim" select="user.isim"/>
            case "@get": {
                    $var= $tag["attributes"]["var"];
                    $select= $tag["attributes"]["select"];

                    if($tag["attributes"]["scope"]=="session")
                        $this->controller->append($var, $_SESSION[$select]);

                    else {
                        $value=  $this->controller->getVar($select);
                        if($value)
                            FacesRenderer::$buffer.=("<?php $$var = $value;?>\n");
                    }
                    break;
                }
            case "@item": {
                    $id= $this->parser->getEndOpenTag();
                    $beginTag = &$this->parser->parsedArray[$id];
                    if(!is_array($this->parser->parsedArray[$id] [attributes][items]))
                        $this->parser->parsedArray[$id] [attributes][items]=array();
                    array_push($this->parser->parsedArray[$id] [attributes][items],$tag[attributes]);
                    break;
                }

            case "@pattern": {

                    $name =$tag[attributes]["name"];
                    FacesRenderer::$struces[$name]=$tag;//$this->parser->getInsertId();
                    if($tag[type]!=FacesParser::START_END_TAG)
                        $this->ispattern=true;
                    $tag[type]="pattern";
                    FacesRenderer::$comns[$tag["attributes"][useprefix]] = array("type"=>"pattern");
                    $this->curPattern=$name;//$this->parser->getInsertId();

                    break;
                }
            case "@htmlpattern": {
                    $name =$tag[attributes]["name"];
                    FacesRenderer::$struces[$name]=$tag;//$this->parser->getInsertId();
                    if($tag[type]!=FacesParser::START_END_TAG)
                        $this->ispattern=true;
                    $this->curPattern=$name;//$this->parser->getInsertId();
                    FacesRenderer::$comns[$tag["attributes"][useprefix]] = array("type"=>"htmlpattern");
                    break;
                }

            case "@ui": {
                    $this->startingTag($this->patternUI($tag,true));
                    break;
                }
            case "@html": {
                   $this->patternHTML($tag, true);
                    break;
                }

        }

    }

    private function patternHTML($tag,$at=false) {

        $ns = $tag [name];
        if($at)
        $ns= $tag[attributes][pattern];
        $pattern= FacesRenderer::$struces[$ns];
        FacesRenderer::$buffer.=$pattern["innerHTML"];

    }
    private function patternUI($tag,$at=false) {
      
        $ns = $tag [name];
        if($at)
        $ns= $tag[attributes][pattern];
        $pattern= FacesRenderer::$struces[$ns];
        $newtag =array();
        $newtag[name]= $pattern[attributes]["extends"];
        $newtag[namespace]= $pattern[attributes]["prefix"];
        $newtag[type]= $tag[type];
        $newtag[attributes] = array_merge($pattern[attributes], $tag[attributes]);
        $tag= $newtag;
        // echo "<pre>"; print_r(FacesRenderer::$comns);
        return $newtag;

    }

    public function closingCompilerTag($tag) {
        switch ($tag[name]) {
            case "@ui": {
                    $this->closingTag($tag);
                    $beginTag = $this->parser->parsedArray[$tag["beginid"]];
                    break;
                }
            case "@htmlpattern": {
                    $this->ispattern=false;
                }
            case "@pattern": {
                    $this->ispattern=false;
                }
            default:
                break;
        }
    }

    public function textNode($text) {
        if($this->ispattern) {
        //$this->parser->parsedArray[$this->curPattern];
            FacesRenderer::$struces[$this->curPattern]["innerHTML"].= $text;
        //$this->parser->parsedArray[$this->curPattern]["innerHTML"].= $text;
        }
        else
            FacesRenderer::$buffer.=$text;

    }

    public function startingPHPTag($tag) {
        FacesRenderer::$buffer.="<?php $tag[code] ?>";
    }



    function isCompilerNS($ns) {
        if(isset(FacesRenderer::$comns[$ns])) {
            if($this->pre==true && FacesRenderer::$comns[$ns]["type"]!="call")
                return false;
            return true;
        }
        return false;
    }


    private function getCompilerNS($ns) {
        if(isset(FacesRenderer::$comns[$ns]))
            return FacesRenderer::$comns[$ns];
        return false;
    }

    private function getJSrcipt() {
    // echo FacesRenderer::$jscript;
        return  "dojo.addOnLoad(function() {\n".FacesRenderer::$jscript."});";

    }

    function getJCode() {
    //if($this->objectBuffer!=null){
        $buffer.= "\n".'<script type="text/javascript" src="'.BASE_URL.'dojo/dojo/dojo.js" '.(($this->usedojoParser)? 'djConfig="parseOnLoad: true"':''). '></script>';
        $buffer.="\n".'<script type="text/javascript" src="'.BASE_URL.'faces.js" ></script>';
        $buffer.="\n".'<script type="text/javascript">'.
            $this->dojocodes."\n".
            $this->getJSrcipt()."\n".
            $this->classesjs.
            '</script>';
        return $buffer;
    }

    function getObjects() {
        return  FacesRenderer::$objectBuffer;
    }

    function getBuffer() {
        return FacesRenderer::$buffer;
    }
    private  function vars_dump($vars) {
        $code = "array(";
        $count = count($vars);

        foreach ($vars as $key => $value) {

            if($key!="items"&&$key!="bind") {

            // $value= addslashes($value);
            // $code.= "'".$key."' =>". "'".addslashes($value)."',";
                $code.= "'".$key."' =>". "'".$value."',";
            }

        }
        if(isset($vars["items"])) {
            $code.= "\n'".items."' => array(";
            foreach ($vars["items"] as $item)
                $code.= "\n".$this->vars_dump($item,true).",";
            $code.=")";
        }

        return $code.=")";
    }
    /**
     * @return Header
     */
    static function getHeader() {
        return FacesRenderer::$header;
    }
}

?>