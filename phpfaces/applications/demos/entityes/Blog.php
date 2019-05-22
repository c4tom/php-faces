<?php
/**
 *  @Table(name = "blog")
 */
class Blog extends Entity
{
 /**
    @Id
    @Generated
    @Column(name = "id" ,type = "integer")
    */
    private $id;

     /**
     @Column(name = "name" ,type = "string")
     */
    private $name;
    
    /**
     @Column(name = "content" ,type = "text")
   */
    private $content;

    public function set($name, $value) {
        $this->$name= $value;
    }
    public function get($name) {
        return $this->$name;
    }

    public function __toString() {
        return $this->name;
    }
}

?>