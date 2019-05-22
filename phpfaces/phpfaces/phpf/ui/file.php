<?php
/**
 * @name File
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.ui
 * @version 1.0
 *
 */
require_once CLASS_PATH. "phpf".DS."ui".DS.'component.php';
class File extends Component {
var $isUploaded=false;
var $error;
var $file;

    function File(FacesController &$controller,$args=null) {
      
       parent::Component($controller,$args);
       
    }

    function startTag() {
       
       $html= '<input type="file" ';
        $html.= $this->doAttributes();
        $html.="/>";
        return $html;
    }

    function upload($path=null) {
        $name = $this->attributes["name"];
        $types= array();
        if($path!=null)
        $this->attributes["path"]= $path;

        if ($_FILES[$name]["error"] > 0) {

            $this->error=  $_FILES[$name]["error"];
            $this->isUploaded=false;
        }
        else {
            if (!file_exists($this->attributes["path"] . $_FILES[$name]["name"])) {
                move_uploaded_file($_FILES[$name]["tmp_name"],
                    $this->attributes["path"] . $_FILES[$name]["name"]);
                $this->file =$this->attributes["path"] . $_FILES[$name]["name"];
                $this->isUploaded= true;
               
            }

        }

    }
}
?>