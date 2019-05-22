<?php
/**
 * @name classmap
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package dbf.classmap
 * @version 1.0
 * @category PHP Faces ORM
 */
class ClassMap  extends Singleton {

/**
 *
 * @return <ClassMap>
 *
 */
    public static function getInstance() {

        return parent::getInstance(__CLASS__);

    }
    private $array_map=array();
    private $class_list=array();
    private $super_class;
    function ClassMap($class_name=null) {
        $this->super_class= $class_name;
        if($class_name!=null)
            $this->HaritaYap($class_name);
    }

    function run($class_name) {
        if($this->super_class!=$class_name) {

            $this->clearMap();
            $this->super_class= $class_name;
            if($class_name!=null)
                $this->HaritaYap($class_name);
        }
    }

    function merge($class_name) {
        $this->super_class= $class_name;
        if($class_name!=null)
            $this->HaritaYap($class_name);
    }

    private function classSet($class_name) {
        $this->array_map[$class_name]=array();
        $this->array_map[$class_name]["many"]=array();
        $this->array_map[$class_name]["one"]=array();
    }

    function clearMap() {
        unset($this->array_map);
        unset($this->class_list);
        $this->array_map=array();
        $this->class_list=array();
    }
    function getSuperClass() {
        return $this->super_class;
    }
    function getArrayMap() {
        return $this->array_map;
    }
    function getIterator() {
        return new ArrayIterator($this->array_map);
    }
    function getClassList() {
        return $this->class_list;
    }
    function getClass($class_name) {
        return $this->array_map[$class_name];
    }
    function getClassColumns($class_name) {
        return $this->array_map[$class_name]["coulmns"];
    }

    function getClassColumn($class_name,$column_name) {
        return $this->array_map[$class_name]["coulmns"][$column_name];
    }

    function getTableName($class_name) {
        return $this->array_map[$class_name]["table"];
    }

    function getClassOne($class_name) {
        return $this->array_map[$class_name]["one"];
    }
    function getClassMany($class_name) {
        return $this->array_map[$class_name]["many"];
    }

    function getForeignClassesMap($class_name) {
        return array("one"=>$this->array_map[$class_name]["one"],"many"=>$this->array_map[$class_name]["many"]);
    }

    function getArrayId($class_name) {
        return $this->array_map[$class_name]["id"];
    }
    function getId($class_name) {
        return $this->array_map[$class_name]["id"]["id"];
    }
    function isClassLoaded($class_name) {
        return isset($this->class_list[$class_name]);

    }
    function HaritaYap($class_name) {
        if(isset($this->class_list[$class_name]))
            return;
        $this->class_list[$class_name]=$class_name;
        $this->classSet($class_name);

        $reflection = new ReflectionAnnotatedClass($class_name);

        $this->array_map[$class_name]["table"]= $reflection->getAnnotation('Table')->name;
        foreach ($reflection->getProperties(ReflectionProperty::IS_PRIVATE) as $m) {

            if($m->hasAnnotation("Column")) {
                $column_name = $m->getAnnotation("Column")->name;
                $column_type = $m->getAnnotation("Column")->type;
                //$column_size = $m->getAnnotation("Column")->size;
                $property_name = $m->getname();

                $this->array_map[$class_name]["coulmns"][$column_name]=
                    array("name"=>$column_name,"type"=>$column_type,"property"=>$property_name);
            }

            if($m->hasAnnotation("OneToOne"))
                $this->hasOne("OneToOne", $m,$class_name);

            if($m->hasAnnotation("ManyToOne"))
                $this->hasOne("ManyToOne", $m,$class_name);

            if($m->hasAnnotation("OneToMany"))
                $this->hasMany("OneToMany", $m,$class_name);

            if($m->hasAnnotation("ManyToMany"))
                $this->hasMany("ManyToMany", $m,$class_name);



            if($m->hasAnnotation("Id")) {
                $column_name = $m->getAnnotation("Column")->name;
                $column_type = $m->getAnnotation("Column")->type;
                $this->array_map[$class_name]["id"]["id"]=$column_name;
                $this->array_map[$class_name]["id"]["type"]=$column_type;
                $this->array_map[$class_name]["id"]["property"]=$m->getName();
                if($m->hasAnnotation("Generated"))
                    $this->array_map[$class_name]["id"]["generated"]="generated";
            }
        }
    }

    function hasOne($notation,$m,$class_name) {

        $cocuk_class = $m->getAnnotation($notation)->mappedBy;
        $fk = $m->getAnnotation($notation)->fk;
        $pk = $m->getAnnotation($notation)->pk;
        $property_name = $m->getname();
        array_push($this->array_map[$class_name]["one"] ,array("class"=>$cocuk_class,"property"=>$property_name,"fk"=>$fk,"pk"=>$pk));
        if(!isset($this->class_list[$cocuk_class]))
            $this->HaritaYap($cocuk_class);
    }

    function hasMany($notation,$m,$class_name) {

        $cocuk_class = $m->getAnnotation($notation)->mappedBy;
        $fk = $m->getAnnotation($notation)->fk;
        $pk = $m->getAnnotation($notation)->pk;
        $property_name = $m->getname();
        array_push($this->array_map[$class_name]["many"] ,array("class"=>$cocuk_class,"property"=>$property_name,"fk"=>$fk,"pk"=>$pk));
        if(!isset($this->class_list[$cocuk_class]))
            $this->HaritaYap($cocuk_class);

    }
}
?>