<?php
/**
 * @name Table
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.ui
 * @version 1.0
 *
 */
require_once CLASS_PATH. "phpf".DS."ui".DS.'component.php';
class Grid extends Component {
    private $fields;
    private $store;

    public function getStore() {
        return $this->store;
    }

    public function setStore($store) {
        $this->store = $store;
    }


    function load($att) {

        parent::load ( $att );


    }


    function Grid(FacesController &$controller,$att=null )
    {
        parent::Component($controller,$att);
        $this->fields = new ArrayObject();
        //efe($this->attributes);

    }
    private function input($item,$data,$key) {
        if($item[input])
        {
         $kk=array();
         $dd=array();
        if($item[input]=="link")
        {
             if($item["type"]=="path")
             $keys =   explode("/", $item[url]);
             else
             $keys =   explode("=", $item[url]);

          
         unset($keys[0]);
         foreach($keys as $k){
         $ss =explode("&", $k);
         $dk = substr($ss[0], 1);
         if($data->$dk){
         array_push($dd, $data->$dk);
         array_push($kk, $ss[0]);
         
         }
         }

       if($item["type"]=="path")
       return '<a href="'.str_replace(" ",$item[separator],(str_replace ($kk,$dd,$item[url]))).'">'.$item[title]."</a>";
       else
       return '<a href="'.(str_replace ($kk,$dd,$item[url])).'">'.$item[title]."</a>";
   }
   $att = $this->doItemAttributes($item);
   if($item[input]=="img")
   return '<img src ="'.$data->$key.'"'.$att.'/>';
        return '<input type="'.$item[input].'" value ="'.$data->$key.'"'.$att.'/>';
        }
        return $data->$key;
    }
    function startTag($bind=null) {
        $html= "<table ". $this->doAttributes()."><tr>";
        
        foreach ($this->items as $item)
        $html.= "<td>".$item[title]."</td>";
        $html.="</tr>";

        $i=0;
		if($bind)
        foreach ($bind as $data){
            $i++;
            foreach ($this->items as $item)
            {
               
                    $key = $item[key];
                    $html.= "<td>".$this->input($item,$data,$key). "</td>";
                
            }
            $html.="</tr>";
        }
        $html.="</table>";
        return $html;

    }
    function endTag() {
        ;
    }

}
?>
