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
class validator {
 static   function required(Component $c,Component $m,$message,$success) {

        if(trim($c->text)=="") {
            $m->setText($message);
            return false;
        }
        $m->setText($success);
        return true;
    }

  static  function equals(Component $c,Component $m,$message,$success) {
    
       if ($c->text !== $c->test) {
            $m->setText($message);
            return false;
        }
        $m->setText($success);
        return true;
    }


   static function minLength(Component $c,Component $m,$message,$success) {

        if(strlen($c->text) >= $c->min) {
            $m->setText($success);
            return true;
        }
        $m->setText($message);
        return false;
    }

   static function maxLength(Component $c,Component $m,$message,$success) {
        if(strlen($c->text) <= $c->max) {
            $m->setText($success);
            return true;
        }
        $m->setText($message);
        return false;
    }


   static function mail(Component $c,Component $m,$message,$success) {
        if( preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $c->text)) {
            $m->setText($success);
            return true;

        }
        $m->setText($message);
        return false;
    }

   static function alpha(Component $c,Component $m,$message,$success) {
        if(  preg_match("/^([a-z])+$/i", $c->text)) {
            $m->setText($success);
            return true;
        }
        $m->setText($message);
        return false;
    }

   static function alpha_numeric(Component $c,Component $m,$message,$success) {
        if (preg_match("/^([a-z0-9])+$/i", $c->text)) {

            $m->setText($success);
            return true;
        }
        $m->setText($message);
        return false;
    }



   static function numeric(Component $c,Component $m,$message,$success) {
        if ( is_numeric($c->text)) {

            $m->setText($success);
            return true;
        }
        $m->setText($message);
        return false;

    }

   static function integer(Component $c,Component $m,$message,$success) {
        if (preg_match( '/^[\-+]?[0-9]+$/', $c->text)) {
            $m->setText($success);
            return true;
        }
        $m->setText($message);
        return false;
    }



   static function betweenLength(Component $c,Component $m,$message,$success) {
        $length = strlen($c->text);
        if ($length >= $c->min && $length <= $c->max) {
            $m->setText($success);
            return true;
        }
        $m->setText($message);
        return false;

    }

   static function between(Component $c,Component $m,$message,$success) {

        if ($c->text >= $c->min && $c->text <= $c->max) {
            $m->setText($success);
            return true;
        }
        $m->setText($message);
        return false;

    }

   static function boolean(Component $c,Component $m,$message,$success) {
        $booleanList = array(0, 1, '0', '1', true, false,"true","false");
        if (in_array($c->text, $booleanList, true)) {
            $m->setText($success);
            return true;
        }
        $m->setText($message);
        return false;


    }
  static function test(Component $c,Component$m, $message,$success) {
        $reserve=  str_replace ( array("this","."), array("\$c","->"),$c->test);
        $text = "\$value= ($reserve);";
        eval($text);
        if($value){
            $m->setText($success);
        return true;

        }
        else
            $m->setText($message);
             return false;
    }
}
?>