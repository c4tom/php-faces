Entity objects extends the Entity class.

class properties is indicated by annotation.

Sample

```
/**
 *  @Table(name = "blog")
 */
class Blog extends Entity
{
}
```

Add Columns
```
/**
 *  @Table(name = "blog")
 */
class Blog extends Entity
{

 /**
     @Column(name = "name" ,type = "string")
     */
    private $name;

/**
    @Id
    @Generated
    @Column(name = "id" ,type = "integer")
    */
    private $id;

    

}
```
Setters and Getters

```

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


}
```