<?php
class Session
{
    function set($param,$value) {
        return $_SESSION[$param]=$value;
    }
    function get($param) {
        return $_SESSION[$param];
    }

   
}

?>
