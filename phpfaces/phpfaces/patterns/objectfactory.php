<?php
class ObjectFactory {
	public static function getNewInstance($className, $param1 = null,$param2=null) {
		if (class_exists ( $className )) {
			if ($param1 == NULL)
				return new $className ( );
			else {
				/*$obj = new ReflectionClass ( $className );
				return $obj->newInstanceArgs ( $params );*/
            if ($param2 == NULL)
                    return new $className ($param1);
			return new $className ($param1,$param2);
			}
		}
		 return null;
		//throw new Exception ( "Class [ $className ] not found..." );
	}
}
?>
