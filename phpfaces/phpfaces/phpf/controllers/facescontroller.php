<?php
/**
 * @name FacesController
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.controllers
 * @version 1.0
 *
 */
interface FacesListener {}
class FacesController {
    private $listeners;
    private $interrupt=false;
    private $viewstate="false",$stroge="xhtml",$eventvalidation="false";
    private $validation;
    private $event, $systemEvent;
    private $xhtml64;
    private $vars=array();
    private $buffer;
    private $validateCall=false;
    var $pageVars=array();
    private $specialchars=false;
    private $validates=array();
    private $ispre=false;

    public function getBuffer() {
        return $this->buffer;
    }
    public function setSpecialchars($b) {
        $this->specialchars=$b;
    }
     function isValid() {
        return in_array(false, $this->validates)?false:true;
    }
    protected function isValidCallBack() {
        return $this->validateCall;
    }
    protected function setValidCallBack($b) {
        $this->validateCall= $b;
    }
    public function FacesController() {

        $this->listeners=array();
        if(defined("BASE_URL"))
            $this->append("base_url",BASE_URL);
        $this->append("site_root", SITE_ROOT);

    }
    public function addXHTMLState($name,$value) {
        $this->xhtml64[$name]=$value;
    }
    public function getViewstate() {
        return $this->viewstate;
    }

    public function setViewstate($truestate) {
        $this->viewstate = $truestate;
    }

    public function getstroge() {
        return $this->stroge;
    }

    public function setstroge($strogemethod) {
        $this->stroge = $strogemethod;
    }

    public function geteventvalidation() {
        return $this->eventvalidation;
    }

    public function seteventvalidation($is) {
        if($is=="true") {
            require_once ( CLASS_PATH. "phpf".DS."renderers".DS."EventValidation.php");
            $this->eventvalitor =new EventValidation();

        }
        $this->eventvalidation =$is;

    }

    public function append($name,$value,$pre=true) {
        if(!$this->ispre)
            $this->vars[$name]=$value;
        else
            $this->pageVars[$name]=$value;
    }



    public function getVar($name) {
        return $this->vars[$name];
    }

    private function _eval($__code__) {
        extract($this->vars);
        echo( eval( '?>' . $__code__));
    }
    function _extract($name) {
        extract($this->vars[$name]);
    }
    public final  function render($facesfile,&$head=null) {

        if(Dispatcher::getMode()=="app")
            $facesfile = SITE_ROOT  .ApplicationContext::getAppDirectory().DS. 'views'.DS. $facesfile;
        $this->ispre=true;
        require_once CLASS_PATH."phpf".DS."renderers".DS.'FacesRenderer.php';
        $this->compiler= new FacesRenderer(&$this);
       $head= $this->compiler->render($facesfile);
       $this->append("header", $head);
        extract($this->vars);
        eval($this->compiler->getObjects());
        $this->prerender();
        $this->executeEvents();
        $this->renderend();

    }
    

    public function prerender() {

    }
    public function renderend() {
        $this->response();
    }
    private function response() {
        if(!$this->isInterrupt()) {
            extract($this->pageVars);
            extract(ApplicationContext::getPageVars());
            echo ( eval( '?>' . $this->compiler->getBuffer() ));
            echo $this->compiler->getJCode();
            echo ( $this->getBuffer());
            $this->faceState();
            echo ( $this->buffer );
        }
    }



    public  function addActionListener(ActionListener $listener) {
        $this->listeners["ActionListener"]=$listener;
    }

    public function addAjaxListener(AjaxListener $listener) {
        $this->listeners["AjaxListener"]=$listener;
    }

    public function addMouseListener(MouseListener $listener) {
        $this->listeners["MouseListener"]=$listener;
    }

    public function addRequestListener(RequestListener $listener) {
        $this->listeners["RequestListener"]=$listener;
    }

    public function addValueChangedListener(ValueChangedListener $listener) {
        $this->listeners["ValueChangedListener"]=$listener;
    }
    public function addListener($name,FacesListener $listener) {
        $this->listeners[$name]=$listener;
    }

    protected function getListener($name) {
        return $this->listeners[$name];
    }


    public function getComponents() {
        $results=array();
        foreach($this as $key => $com)
            if($com instanceof Component)
                $results[$key]=$com;
        return $results;
    }


    function componentResponser() {
        $this->AjaxResponse();
    }
    public final function AjaxResponse() {
        $this->interrupt();
        $components =array();
        $attributes= array();
        ob_end_clean();
        header('Content-type: application/json');
        foreach($this as $component)
            if($component instanceof Component)
                if($component->isChange()==true) {
                    $keys = $component->getUpdatedKeys();
                    $values=array();
                    $vars = $component->getAttributes();
                    if($vars)
                        foreach($keys as $key) {
                            $val=  $vars[$key];
                            if(isset($val)&&$val!=null&&($key!="declaredfile"&&$key!="bind"&&$key!="placeholder")) {
                                $values[$key]= $val;
                            }
                        }

                    $values["keys"]=$keys;
                    array_push($components,$values);
                }

        if($this->viewstate=="true" && $this->stroge=="xhtml") {
            $vars= array("id"=>"__VIEW__STATE__", "value"=>base64_encode(serialize($this->xhtml64)), "keys"=>array("value"));
            array_push($components,$vars);

        }

        if($this->eventvalitation=="true") {

            $code=   $this->eventvalitor->toSerialize();
            $vars= array("id"=>"__EVENT__VALIDATION__", "value"=>$code, "keys"=>array("value"));
            $_SESSION["__EVENT"]=$code;
            array_push($components,$vars);
        }
        echo json_encode($components);
    }


    public function load($class,$name=null,$app=false) {
        import($class,$app);
        $pos= stripos($class,".");
        $classname= substr($class, $pos+1,strlen($class) );
        if($name==null)
            $name=strtolower($classname);
        $this->$name = new $classname();
    }


    public  final function executeEvents() {

        if($_SERVER["REQUEST_METHOD"]!="POST")
            return;

        if($this->stroge=="xhtml")
            $this->xhtml64  = unserialize(base64_decode($_POST["__VIEW__STATE__"]));

        foreach($this as $name => $com) {
            if($com instanceof Component) {
                $attributes =  $com->getAttributes();
                //efe($attributes);
                if ($attributes['placeholder'] == "true") {
                    $attributes = $this->isplaceHolder ( $attributes );
                    $com->load($attributes );
                }

                if(isset($_POST[$name]))
                    if($this->specialchars)
                        $this->$name->attributes['text']=htmlspecialchars($_POST [$name]);
                    else
                        $this->$name->attributes['text']=$_POST [$name];

                if (isset($attributes['validator'])) {
                    $class_name =$attributes['validator'];
                    $rules =explode(",",$attributes['rule']);
                    $message_name =explode(",",$attributes['messagefor']);
                    $messages =explode(",",$attributes['message']);
                    if(isset($attributes['success']))
                        $success =explode(",",$attributes['success']);
                    if(!class_exists($class_name))
                        throw new Exception("$class_name Validator Class not found", 10);

                    foreach($rules as $key => $rule) {
                        if(property_exists($this, $message_name[$key] ))
                            $message_component = $this->$message_name[$key];
                        else
                            $message_component = $com;
                        $valid=  call_user_method($rule, $class_name,$com,$message_component," ".$messages[$key]," ".$success[$key]);
                        $com->setValid($valid);
                        array_push($this->validates,$valid );
                    }
                }

            }
        }
        $component=null;
        $this->eventValidationControl();
        if($this->eventvalidation=="false")
            $this->validation=true;
        if($this->validation==true) {

            if (count ( $_REQUEST ) > 0&&$_SERVER["REQUEST_METHOD"]=="POST") {
                if (isset ( $_REQUEST ["component"] )) {
                    $varname = $_REQUEST["component"];
                    $component = $this->$varname;
                    // $component->attributes->text=htmlspecialchars($_REQUEST [$varname]);
                    // $component->attributes['test']=htmlspecialchars($_REQUEST [$varname]);
                    if (isset ( $_REQUEST['eventtype'])) {
                        $eventname=$_REQUEST['eventtype'];
                        $this->systemEvent = System::getEvent();
                        $this->event = ObjectFactory::getNewInstance($eventname,$component,$this->systemEvent);
                        if(!$this->event instanceof FacesEvent)
                            $this->event=null;

                    }
                }
            }
            if($this->isValid()|| $this->validateCall)
                $this->renderEvents();
            elseif($this->systemEvent instanceof AjaxEvent)
                $this->AjaxResponse();
           /* if($this->iseventstate)
            $_SESSION["__EVENT"]=$this->eventvalitor->toSerialize();*/
        }
    }
    private final function eventValidationControl() {
        $postvalid = unserialize (base64_decode ($_POST["__EVENT__VALIDATION__"]));
        $svalid = unserialize (base64_decode ($_SESSION["__EVENT"]));

        if($svalid === $postvalid )
            $this->validation=true;
        else
            $this->validation=false;
    }

    private final function renderEvents() {

        if($this->systemEvent!=NULL) {
            $listener= $this->systemEvent->getListener();
            $method =  $this->systemEvent->getMethod();
            $invoker =$this->getListener($listener);

            if($this instanceof $listener && method_exists($invoker ,$method))
                call_user_method($method , $invoker ,$this->systemEvent);
        }
        if( $this->event!=null) {
            if( $this->event->isConfirm()) {
                $listener= $this->event->getListener();
                $method =  $this->event->getMethod();

                $invoker =$this->getListener($listener);
                if($this  instanceof $listener && method_exists($invoker ,$method))
                    call_user_method($method ,$invoker ,$this->event);
            }
        }
    }

    private  final  function isplaceHolder($att) {

        if($this->viewstate=="true") {

            if($this->stroge=="session") {
                $holdername = $att ["declaredfile"] . "." . $att ["name"];

                if (isset ( $_SESSION [$holdername] )) {
                    $att = $_SESSION [$holdername];

                } else {
                    $_SESSION [$holdername] = $att;
                }

            }
            elseif($this->stroge=="xhtml") {
                $val = $this->xhtml64[$att[name]];
                if(!$val)
                    $this->xhtml64[$att[name]]= $att;
                else
                    $att=$val;
            }
            if(is_object($att)) {
                return objectToArray($att);
            }
            return $att;
        }
        return $att;
    }

    protected  final  function faceState() {

        if($this->eventvalidation=="true") {
            $code=   $this->eventvalitor->toSerialize();
            $this->buffer.="\n".'<input name="__EVENT__VALIDATION__" id="__EVENT__VALIDATION__" type=hidden value="'.
                $code.'"/>';
            $_SESSION["__EVENT"]=$code;
        }
        if($this->viewstate=="true" && $this->stroge=="xhtml")
            $this->buffer.="\n".'<input name="__VIEW__STATE__" id="__VIEW__STATE__" type=hidden value="'.  base64_encode(serialize($this->xhtml64)).'"/>';


    }

    public function interrupt() {
        $this->interrupt=true;
    }
    public function isInterrupt() {
        return $this->interrupt;
    }


}
?>