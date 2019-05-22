<?php
/**
 * @name ObjectRegistry
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package dbf
 * @version 1.0
 * @category PHP Faces ORM
 */
class ObjectRegistry extends Singleton
{
    var  $registed =array();

    public static function getInstance() {
        return parent::getInstance(__CLASS__);
    }

    public function ObjectRegistry() {
        $this->registed =array();
    }
    public function add($obj) {
        $hash=    spl_object_hash($obj);
        $this->registed[$hash]=get_class($obj);
    }

    public  function isRegisted($obj) {
        $hash=    spl_object_hash($obj);
        return (isset($this->registed[$hash]));
    }

    public function remove($obj) {
        $hash=    spl_object_hash($obj);
        unset($this->registed[$hash]);
    }
}
?>
