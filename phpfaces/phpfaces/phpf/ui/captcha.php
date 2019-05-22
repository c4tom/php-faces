<?php
/**
 * @name Captcha
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.ui
 * @version 1.0
 *
 */
require_once CLASS_PATH. "phpf".DS."ui".DS.'component.php';
require_once CLASS_PATH. "libs".DS."securimage".DS."securimage.php";
class Captcha extends Component {
    private $img;
    public $code;


    function Captcha(FacesController $controller,$args=null )
    {
        parent::Component($controller);
        if($args!=null)
        $this->load($args);
        $this->img = new securimage();
        $this->code = $this->img->getCode();

    }
    function getSecureImage()
    {
       return $this->img;
    }
    function startTag() {
        $url=
        $b.= '<img src = "'.BASE_URL.'/phpfaces/libs/securimage/securimage_show.php?sid='.md5(uniqid(time())).'" id ="'.$this->attributes[id].'"/>';
        $b.='<a href="#" style="border-style: none;" onclick="document.getElementById(\''.$this->attributes[id].'\').src = \''.BASE_URL.'/phpfaces/libs/securimage/securimage_show.php?\' + Math.random(); return false" title="Refresh">';
        $b.='<img src = "'.BASE_URL.'/phpfaces/libs/securimage/images/refresh.gif" alt="Refresh" border="0" ></a>';
        $b.='<a title="Audible Version of CAPTCHA" href="'.BASE_URL.'/phpfaces/libs/securimage/securimage_play.php" style="border-style: none;"><img border="0"  onclick="this.blur()" alt="Audio Version" src="'.BASE_URL.'/phpfaces/libs/securimage/images/audio_icon.gif"/></a>';
        
    return $b;
    }
    public function check($text)
    {
        return $this->img->check($text);
    }
}

?>
