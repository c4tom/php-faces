<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of filter
 *
 * @author Bora
 */

class Filter {
    static function required(Component $c,Component $m,$message,$success) {

        if(trim($c->getText())=="") {
            $m->setText($message);
            return false;
        }
        $m->setText($success);
        return true;
    }
    static function mail(Component $c,Component $m,$message,$success) {

        if (!filter_var($c->getText(), FILTER_VALIDATE_EMAIL)) {
            $m->setText($message);
            return false;
        }

        $m->setText($success);
        return true;

    }
    static function url(Component $c,Component $m,$message,$success) {
        if (!filter_var($c->getText(), FILTER_VALIDATE_URL)) {
            $m->setText($message);
            return false;
        }
        $m->setText($success);
        return true;

    }

    static function ip(Component $c,Component $m,$message,$success) {
        if (!filter_var($c->getText(), FILTER_VALIDATE_IP)) {
            $m->setText($message);
            return false;
        }
        $m->setText($success);
        return true;

    }

    static function float (Component $c,Component $m,$message,$success) {
        if (!filter_var($c->getText(), FILTER_VALIDATE_FLOAT)) {
            $m->setText($message);
            return false;
        }
        $m->setText($success);
        return true;

    }

    static function int (Component $c,Component $m,$message,$success) {
        if (!filter_var($c->getText(), FILTER_VALIDATE_INT)) {
            $m->setText($message);
            return false;
        }
        $m->setText($success);
        return true;

    }

    static function boolean (Component $c,Component $m,$message,$success) {
        if (!filter_var($c->getText(), FILTER_VALIDATE_BOOLEAN)) {
            $m->setText($message);
            return false;
        }
        $m->setText($success);
        return true;

    }

    static function regexp(Component $c,Component $m,$message,$success) {

        if(filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $com->regxp))) === FALSE) // displays Match was found.
        {
            $m->setText($message);
            return false;
        }
        else {

            $m->setText($success);
            return true;
        }


    }

}
?>
