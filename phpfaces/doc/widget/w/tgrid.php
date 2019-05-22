<?php
import("phpf.controllers.facescontroller");
class Data
{
public $name,$lastname, $id;
function Data($id,$name,$lastname)
{
$this->id= $id;
$this->name=$name;
$this->lastname=$lastname;
}
}

class Tgrid extends FacesController 
{
protected $datas;
    function Tgrid()
    {
        parent::FacesController();
		$this->datas=array(new Data(1,"Huseyin Bora","ABACI"),new Data(2,"Hakan","IREN"),new Data(3,"Enes","AKYUZ"));
		$this->render(ApplicationContext::getCurrentView());
        
    }
  
}
?>