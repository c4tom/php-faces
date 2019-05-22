<?php
class Query extends PDOStatement
{
    private $setter;
    private $results=array();
    private $type;
    protected function Query(ObjectSetter $object_setter=null,$type=null) {
        $this->setter= $object_setter;
        $this->type= $type;

    }
    public function getResultList() {
       
       return $this->results; //$this->setter->kulucka($this);
    }

    public function nextResutlt() {
        return $this->fetch(PDO::FETCH_ASSOC);
    }

    public function getSingle()
    {
       //$res= $this->setter->kulucka($this);
       if(count($this->results)>0)
        return current($this->results);
        return null;
    }
 public function setLimit($min,$max)
    {
    $map=  ClassMap::getInstance();
    $s =  $map->getSuperClass();
   $table= $map->getTableName($s);
   $id =$map->getId($s);
  $this->queryString.="GROUP BY $table.$id LIMIT :limit OFFSET :offset";

 $this->bindParam(":limit",$max,PDO::PARAM_INT);
  $this->bindParam(":offset",$min,PDO::PARAM_INT);

 echo $this->queryString;
    }
    public function execute() {
    try
    {
    parent::execute();

    }
    catch (PDOException $e)
    {
        throw $e;
    }
    if($this->type=="query")
    $this->results= $this->setter->kulucka($this);

    
    else
    array_push($this->results , $this->fetchColumn());
    return $this;
 }


}

class NativeQuery extends PDOStatement
{
    protected function Query() {

    }

    public function nextResutlt() {
        return $this->fetch(PDO::FETCH_ASSOC);
    }
    public function nextObject($className=null) {
        if($className==null)
        return $this->fetch(PDO::FETCH_OBJ);
        return $this->fetchObject($className);
    }
    public function getSingle()
    {
        $this->execute();
        $res= $this->fetch();
        return $res[0];
    }
     public function getResultList($class=null) {
         if($class==null)
         return  $this->fetchAll(PDO::FETCH_OBJ);
         return $this->fetchAll(PDO::FETCH_CLASS,$class);
    }
}


class PDOConnector extends PDO
{
    private $statement ;
    private $parser;
    private $queryGen;
    public function __construct($dns="",$user="",$pass="") {
        if($dns!="")
        $this->connect($dns, $user, $pass);

        
        
    }

    public function nextResutlt() {
        return $this->statement->fetch(PDO::FETCH_ASSOC);

    }


    public function getinsertId() {
        return $this->lastInsertId();
    }
/**
 *
 * @param string $query
 * @param ObjectSetter $setter
 * @return Query
 *
 */
    public function query($query,$setter,$type="query") {
//echo $query;
        
        try {
            return parent::prepare($query,array(PDO::ATTR_STATEMENT_CLASS=>
                    array('Query',array($setter,$type))));
        } catch (PDOException $e) {
            throw $e;
        }

    }
  /**
 *
 * @param string query
 * @return NativeQuery
 *
 */
    public function executeNativequery($query) {
        return parent::prepare($query,array(PDO::ATTR_STATEMENT_CLASS=>
                array('NativeQuery')));
    }

    public function connect($dns,$user,$pass) {
        try {
            parent::__construct(
                $dns,$user,$pass,
                array(PDO::ATTR_PERSISTENT => true,PDO::MYSQL_ATTR_USE_BUFFERED_QUERY=>true,PDO::ATTR_ERRMODE=> PDO::ERRMODE_EXCEPTION));


        } catch (PDOException $exception) {

            throw $exception;
        }

    }}


?>
