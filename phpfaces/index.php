<?php
require_once ("phpfaces/system/PHPFaces.php");
require_once ("applications/config.php");
Dispatcher::dispatch("applications".DS."demos","welcome","http://localhost/phpfaces/");//changed your url
?>