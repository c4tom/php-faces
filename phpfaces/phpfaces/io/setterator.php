<?php
/**
 * @name Seterator
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package io
 * @version 1.0
 * @category PHP Faces Lib
 */
class Setterator
{
    private $gecenler=array();
    function arrayToObject(&$object,array $array) {
        $class_name = get_class($object);
        $this->gecenler[$class_name];
        foreach ($array as $key => $val) {
            if(property_exists($object, $key))
            $object->$key = $array[$key];
            elseif(is_object($object->$key))
            {
                $class_name = get_class($object->$key);
                if(!isset($this->gecenler[$class_name]))
                $this->arrayToObject($array, $object->$key);
            }

        }
    }

 
function arrayToEntity(array $ar,Entity $object,$onek="",$class=false) {
        $class_name = get_class($object);
        $map=  ClassMap::getInstance();//new ClassMap();
        $array=  $map->getClassColumns($class_name);
        foreach ($array as $key => $val) {
            $monek =  strtolower($onek.$class_name.$key);
            if(isset ($ar[$monek]))
             call_user_method("__set",&$object,$key,$ar[$monek]);
        }
        $sinif_haritasi=$map->getForeignClassesMap($class_name);
        foreach ($sinif_haritasi as $tablo => $deger)
        {
            foreach ($deger as $oge){
                $islenecek_sinif_adi = $oge["class"];
                $property =$oge["property"];
                if($class==true)
                //$onek.=$islenecek_sinif_adi;
                $array=  $map->getClassColumns($islenecek_sinif_adi);
                foreach ($array as $key => $val) {
                    $monek =  strtolower($onek.$islenecek_sinif_adi.$key);
                    if(isset ($ar[$monek]))
                  call_user_method("__set",&$object->$property,$key,$ar[$monek]);
                }
            }
        }
    }
}

?>
