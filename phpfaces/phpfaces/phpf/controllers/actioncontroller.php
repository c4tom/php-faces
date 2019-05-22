<?php
/**
 * @name ActionController
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.controllers
 * @version 1.0
 * @category PHP Faces Core
 */
interface FacesListener{}
include_once CLASS_PATH."phpf".DS."events".DS."systemevents.php";
class ActionController 
{

    public function ActionController()
    {

        $event=  RequestEventFactory::processEvent();
        if($this instanceof RequestListener){
            if(method_exists($this, $event->getMethod())){
                call_user_method($event->getMethod() ,$this ,$event);
            }}
    }

    public function render($view,$data=null)
    {
        $viewfile = SITE_ROOT . DS .ApplicationContext::getAppDirectory().DS. 'views' . DS .$view ;
        if(is_array($data))
        extract($data);
        if ((bool) @ini_get('short_open_tag') === FALSE )
        echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', file_get_contents($viewfile))));
        else
        include($viewfile);
    }

}



?>