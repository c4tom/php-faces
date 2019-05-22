<?php
/**
 * @name Uri
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package io
 * @version 1.0
 * @category PHP Faces Lib
 */
class Uri
{
    private  $arri=array();
    function Uri() {
        $arr=explode("/",$_SERVER['REQUEST_URI']);
        $base = explode("//", ApplicationContext::getBaseURL());
        if(ApplicationContext::getMode()=="index")
        $index= substr_count($base[1], "/"); //count(explode("/", $base[1]))-1;
        else
        $index=substr_count($base[1], "/")+1;
        $this->arri=array_slice($arr,$index,count($arr));
    }
    function get($i) {

        return $this->arri[$i];

    }
    function getArray()
    {
        return $this->arri;
    }
    function getEnv($env)
    {
        return $_SERVER[$env];
    }
    function redirect($url)
    {
        ob_end_clean();
        header ( 'Location: '.$url );
    }
}
?>