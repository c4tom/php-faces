<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of validator
 *
 * @author Bora
 */
class filevalidator {
    static   function length(Component $c,Component $m,$message,$success) {
        $name = $c->getName();
        
        if($_FILES[$name]["size"] > (int)$c->length) {
            $m->setText($message);
            return false;
        }
        $m->setText($success);
        return true;
    }

    static  function types(Component $c,Component $m,$message,$success) {
        $name = $c->getName();
        if(!in_array($_FILES[$name]["type"], explode(",",$c->types))) {
            $m->setText($message);
            return false;
        }
        else
            $m->setText($success);
        return true;
    }


    static function exists(Component $c,Component $m,$message,$success) {
      $name = $c->getName();
        if(!file_exists($c->path.$_FILES[$name]["name"]))
       {
            $m->setText($success);
            return true;
        }
        $m->setText($message);
        return false;
    }
}
?>