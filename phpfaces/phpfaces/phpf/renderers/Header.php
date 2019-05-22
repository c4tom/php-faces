<?php
/**
 * @name TextBox
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.ui
 * @version 1.0
 *
 */

class Header {
private $meta ;
private $style ;
private $title;
private $titleText;
private $text;
function Header() {

}
    function addMeta($name,$content) {
       $this->meta.= '<meta name ="'.$name.'" content="'.$content.'"/>'."\n";
      
    }
    function addScript($file,$base=true)
    {
      $this->js.=  '<script language="javascript" src="'.$file.'" type="text/javascript"></script>'."\n";
    }
      function addLink($file,$base=true)
    {
      $this->style.=  '<link href="'.$file.'" rel="stylesheet" type="text/css" />'."\n";
    }

   function setTitle($title)
    {
      $this->titleText= $title;
      $this->title=  "<title>$this->titleText</title>\n";
    }
    function addText($text)
    {
        $this->text.="\n".$text;
    }

   
    function startHead() {
        $html= "\n".$this->title.$this->meta.$this->style.$this->js.$this->text."\n";
     
        return $html;
    }
    
}
?>