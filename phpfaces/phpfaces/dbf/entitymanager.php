<?php
/**
 * @name EntityManager
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package dbf
 * @version 1.0
 * @category PHP Faces ORM
 */
class EntityManager extends Singleton {
    private $map;
    private $querymanager;
    private $queryParser;
    private $setter;
    private $objectRegistry;
    private $record;
    private $db;
    /**
     *
     * @return EntityManager
     *
     */
    public static function getInstance() {

        return parent::getInstance(__CLASS__);

    }
    function  EntityManager() {
        $this->map= ClassMap::getInstance();//new ClassMap();
        $this->objectRegistry =  ObjectRegistry::getInstance();
        $this->querymanager = new QueryGenerator($this->map);
       /***************************************************************************/
        $lowprefix =DB_CONNECTOR; //strtolower(DB_CONNECTOR);
        require_once CLASS_PATH."dbf".DS.'connectors'.DS.$lowprefix.'.php';
        $connectorname = $lowprefix."Connector";
        $this->db = new $connectorname();
        $this->db->connect(DB_CONNECTION_STRING, DB_USER,DB_PASS);
        /***************************************************************************/
        $this->queryParser = new FQLToSQL();
        $this->setter= new ObjectSetter($this->map,$this->objectRegistry,$this->db);
        $this->record = new Record($this->objectRegistry,$this->map,$this->db);
        $this->converter =null;
    }
    function run($class_name) {
        $this->map->run($class_name);
        $sql = $this->querymanager->generate();
        $this->setter->kulucka();
    }

    function find($entity,$id,$name="") {
        $this->map->run($entity);
        $query = $this->querymanager->generate();
        $table_name= $this->map->getTableName($entity);
        $pkId =  $this->map->getId($entity);
        $query.=" WHERE ".$table_name."."."$pkId = $id";
        $statement=$this->db->query($query,$this->setter);
        $statement->execute();
        return $statement->getSingle();

    }
    function merge($class_name) {
        $this->map->run(get_class($entity));
    }

    function save($entity) {
        $this->map->run(get_class($entity));
        $this->record->save($entity);
    }
    function delete($entity,$subs=false) {
        $this->map->run(get_class($entity));
        if($subs==false)
            $this->record->delete($entity);
        else
            $this->record->deleterelation($entity);
    }
    /**
     * @return Query
     */
    function  createQuery($q) {
        $query=  $this->queryParser->parseQuery($q);
        return $this->db->query($query,$this->setter,$this->queryParser->type);
    }

    /**
     * @param String $query
     * @return NativeQuery
     */
    function nativeQuery($query) {

        return $this->db->executeNativequery($query);
    }
    function beginTransaction() {
        $this->db->beginTransaction();
    }
    function commit() {
        $this->db->commit();
    }

    function rollBack() {
        $this->db->rollBack();
    }
    /**
     * @return Converter
     */

    function getConverter() {
        if($this->converter==null) {require_once 'Converter.php';
            $this->converter = new Converter();
        }

        return $this->converter;
    }
    /**
     * @return ClassMap
     */
    function getClassMap() {
        return $this->map;
    }


}

?>
