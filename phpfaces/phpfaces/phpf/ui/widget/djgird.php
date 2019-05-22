<?php

class DJGrid extends Component {

    function load($args) {
        parent::load ( $args );
    }

    function DJGrid(FacesController $controller,$args)
    {
        parent::Component($controller,$args);

    }

    function addItem($item) {
        $lao = "{";
        foreach($item as $key => $v){
           if($key=="field")
           $v = $item[title];
           if($key=="formatter")
           $lao.=$key .':'.$v.',';
           else
           $lao.=$key .':"'.$v.'",';
        }
           return $lao."},";
    }
function startTag($bind=null) {
        $html= '<table id="Table'.$this->attributes[id].'" style="display:none"><thead><tr>';
         $lao= "<script>var ".$this->attributes[id]."Laoyut = [[";



        foreach ($this->items as $item){
        $html.= "<td>".$item[title]."</td>";
       // $lao.='{field: "'.$item[field].'",name: "'.$item[field].'"},';
       $lao.= $this->addItem($item);

        }
        $lao.="]];</script>";
        
        $html.="</tr></thead><tbody>";

        foreach ($bind as $data){
            foreach ($this->items as $item)
            {

                    $key = $item[field];
				
                    $html.= "<td>".$data->$key. "</td>";

            }
            $html.="</tr>";
        }
        $html.="</tbody></table>";
        $html.='<div dojoType="dojox.data.HtmlStore" dataId="Table'.$this->attributes[id].'" jsId="'.$this->attributes[id].'Store"></div>

  <div  
    dojoType="dojox.grid.DataGrid"
    store="'.$this->attributes[id].'Store"
    structure="'.$this->attributes[id].'Laoyut"'.  $this->doAttributes().'>

  </div>
';
    
        return $lao.$html;

    }

    function endTag() {
        ;
    }


    function getModel() {
        return $this->model;
    }
    function getSelected() {
        return $this->attributes[text];
    }

     public static function addDojo() {
       return ' dojo.require("dojox.data.HtmlStore");
                dojo.require("dojox.grid.DataGrid");';
    }


}
?>

