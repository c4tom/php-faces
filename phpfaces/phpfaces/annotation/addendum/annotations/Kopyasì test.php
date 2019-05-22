<?php
require_once('annotations.php');
/** 
 @Title(value='Welcome', lang='en')
 @Title(value='Wilkommen', lang='de')
 @Title(value='Vitajte', lang='sk')
 @Snippet
 */
class WelcomeScreen {}

And then access them using

$reflection = new ReflectionAnnotatedClass('WelcomeScreen');
$annotations = $reflection->getAllAnnotations(); // array of all 4 annotations
$just_titles = $reflection->getAllAnnotations('Title'); // array of those 3 Title annotations
?>