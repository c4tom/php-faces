<?php
class PropertyChangeSupport extends Singleton
{
    private $class;
    static  $propertyChanged;
    /**
     * @return  PropertyChangeSupport
     */
    public static function getInstance() {
        return parent::getInstance(__CLASS__);
    }

     /**
      *
      * @param <entity> $object
      *
      */
    public function PropertyChangeSupport($object=null)
    {
        // parent::getInstance();
        if($object!=null)
        $this->class=$object;
        if(!is_array(PropertyChangeSupport::$propertyChanged))
        PropertyChangeSupport::$propertyChanged=array();
    }
/**
 *
 * @param <string> $property_name
 * @param <type> $oldValue
 * @param <type> $newValue
 *
 */
    public function firePropertyChange($property_name, $oldValue, $newValue)
    { // echo "<br>$oldValue ,$newValue";
        if($oldValue!=$newValue)
        {
            $hash=    spl_object_hash($this->class);
            if(!is_array(PropertyChangeSupport::$propertyChanged[$hash]))
            PropertyChangeSupport::$propertyChanged[$hash]=array();
            PropertyChangeSupport::$propertyChanged[$hash][$property_name]=$property_name;
            //printr(get_class($this->class)."$property_name changed");
        }
    }
/**
 *
 * @param string $property_name
 * @param Object $obj
 * @return boolean
 *
 */
    public function isPropertyChange($property_name,$obj=null)
    {
        if($obj!=null)
        $hash=    spl_object_hash($obj);
        else
        $hash=    spl_object_hash($this->class);
        return (PropertyChangeSupport::$propertyChanged[$hash][$property_name]==$property_name);
    }
/**
 *
 * @param object $obj
 * @return boolean
 *
 */
    public function  isChangedObject($obj)
    {
        //printr($this->propertyChanged[spl_object_hash($obj)]);
        return (isset(PropertyChangeSupport::$propertyChanged[spl_object_hash($obj)]));
    }
}
?>
