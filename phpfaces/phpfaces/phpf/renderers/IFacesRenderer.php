<?php
/**
 * @name IFacesRenderer
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.renderers
 * @version 1.0
 * bu arayüzdeki metotları renderer sınıfının uygulması gereklidir
 * ve bu metotları facesparser çağırır.
 */
interface IFacesRenderer
{
    function startingTag(&$tag) ;
    function closingTag($tag) ;
    function compilerTag(&$tag,$tagtext);
    function textNode($text);
    function startingPHPTag($tag);
    function isCompilerNS($string);
    function startFaces($stirng) ;
    function closingFaces($stirng);
}
?>
