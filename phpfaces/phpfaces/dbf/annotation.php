<?php
class Table extends Annotation
{
    public $name;
}
class Id extends Annotation
{}
class Generated extends Annotation {}
class Column extends Annotation
{
    public $name;
    public $type;
}
class Relation extends Annotation
{
    public $mappedBy;
    public $fk;
    public $pk;
}
class OneToOne extends Relation
{}
class OneToMany extends Relation
{}
class ManyToMany extends Relation
{}
class ManyToOne extends Relation
{}
?>
