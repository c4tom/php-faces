<?php
/**
 * @name FQLToSQL
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package dbf
 * @version 1.0
 * @category PHP Faces ORM
 */
class FQLToSQL
{
    public $parser;
    public $map;
    public $qm;
    public $q;
    public $anasinif;
    public $kelime;
    public $eskikelime;
    public $type="query";

    function FQLToSQL(QueryGenerator $qm=null)
    {
        $this->map = ClassMap::getInstance();
        $this->qm = new QueryGenerator($this->map);
    }

    function parseQuery($sql)
    {
        $this->parser =new SqlParser();
        $arr= $this->parser->parssing($sql);
        $this->type = $this->parser->type;
        $this->qm->clear();
        $clmns=array();
        $alias="";
        $q="";
        $query =array();
        $sel=false;
        $fro=false;
        $k=0;
        foreach($arr as $data)
        {
            $this->kelime = strtolower($data["soz"]);
            if($this->kelime =="from"&&$fro==false){
                $table= $this->map->getTableName($this->anasinif);
                $query["FROM"]=  $table;
                $fro=true;
            }

            else if($this->kelime =="select"&&$sel==false)
            {
                $from=  $this->parser->nextFrom();
                $this->anasinif = ucfirst(strtolower($from["class"]));
                $alias = $from["alias"];
                $this->map->run($this->anasinif);
                $clmns=$this->map->getClassColumns($this->anasinif);
                if($this->type=="query")
                $this->qm->generate();
                else{
                    $this->yelestir(&$query,$data["val"],$clmns,$alias,$k);
                    continue;

                }
                $clmns=$this->map->getClassColumns($this->anasinif);
                $query["SELECT"]="";
                $sel=true;
            }
            else if($this->kelime =="subquery")
            {
                $this->sub($this->parser->sub[$data["val"][0]],$k,$query);
                unset($data["val"][0]);
                $this->yelestir(&$query,$data["val"],$clmns,$alias,$k);
            }
            else
            $this->yelestir(&$query,$data["val"],$clmns,$alias,$k);
            $this->eskikelime = $this->kelime;
        }
/*SQL oluştur*/
        foreach ($query as $key => $vlas)
        {

            if($key=="SELECT"&&$sel==true){

                $q.=$key." ".$this->qm->select." ";
                $join=true;
                $sel=false;
            }
            elseif($key=="FROM"&&$fro==true)
            {
                if($join)
                $q.= $key." $vlas ".  $this->qm->birlestir." ";
                else
                $q.= $key ." ".$vlas. " ";
                $fro=false;

            }
            else
            {
                $soz =  explode("|",$key);
                $q.= strtoupper($soz[0]) ." ".$vlas. "";
            }
        }
        return $q;

    }

    function yelestir(&$query,$arr,$clmns,$alias,&$k)
    {
        if($this->eskikelime!=$this->kelime)
            $k++;

        $queryval =$query[$this->kelime."|".$k];
        $query[$this->kelime."|".$k]=" ";

        $datas = $arr;


        foreach ($datas as $value)
        {
        if(strpos($value, "."))
        {
            $ex = explode(".", $value);
            $count = count($ex);
            for($i=0;$i<$count;$i++)
            {
                $val = $ex[$i];
                if($count<3)
                {
                    if($val==$alias)
                    $queryval.= " ".$this->map->getTableName($this->anasinif);
                    if ($val==$clmns[$val]["property"])
                    $queryval.=".".$clmns[$val]["name"]." ";
          
                }
                else
                {
                    if($i>0){
                        $foreigns = $this->map->getForeignClassesMap($this->anasinif);
                        $this->classInside($foreigns ,&$ex, &$queryval);
                        break;
                    }
                }}}elseif($value=="(")
        $queryval= trim($queryval).$value;
        else
         $queryval.= " ".$value." ";
        }
        $query[$this->kelime."|".$k]=" ".$queryval;


    }


    function sub(&$arr,&$k,&$query)
    {
        $map = $this->map;
        $anasinif =ucfirst(strtolower($arr["from"]));
        $alias="i";
        if(!$map->isClassLoaded($anasinif))
        {
            $map = new ClassMap($anasinif);
        }

        $clmns=$map->getClassColumns($anasinif);
        foreach($arr as $key => $data){

            $kelime = strtolower($data["soz"]);
            if($kelime=="from")
            unset($data["val"][1]);
            $k++;
            $queryval="";
            if(is_array($data["val"])){
                $this->kelime= $kelime;

                $this->eskikelime=$this->kelime;

                foreach ($data["val"] as $value)

                if(strpos($value, "."))
                {
                    $ex = explode(".", $value);
                    $count = count($ex);

                    for($i=0;$i<$count;$i++)
                    {
                        $val = $ex[$i];
                        if($count<3)
                        {
                            if($val==$alias)
                            $queryval.= " ".$map->getTableName($anasinif);
                            if ($val==$clmns[$val]["property"])
                            $queryval.=".".$clmns[$val]["name"];

                        }
                        else
                        {
                            if($i>0){
                                $foreigns = $map->getForeignClassesMap($anasinif);
                                $this->classInside($foreigns ,&$ex, &$queryval);

                                break;
                            }
                        }}}else
                $queryval.= "".$value."";
                $query[$kelime."|".$k]="".$queryval;

            }
        }
        $k--;

    }


    function classInside($foreigns,&$ex,&$str,$map=null) {
        $ex= array_slice($ex, 1);
        $break=false;
        $val=$ex[0];
        if($map==null)
        $map = $this->map;

        foreach ($foreigns as $key => $has)
        {
            foreach ($has as  $u)
            {

                $prop = $u["property"];
                $fkclass = $u["class"];


                if($prop==$ex[0]){
                    $fkclmn = $map->getClassColumn($fkclass, $u["fk"]);
                    if($fkclmn)
                    if(count($ex)>2)
                    $break=true;
                    break;
                }

            }
            if($break)
            break;
        }
        if(count($ex)>2)
        {
            $foreigns = $map->getForeignClassesMap($fkclass);
            $this->classInside($foreigns, &$ex,&$str);
            return;
        }
        ;
        if(count($ex)>0)
        {
            $clmns=$map->getClassColumns($fkclass);
            $str.= " ".$map->getTableName($fkclass);
            $ex= array_slice($ex, 1);
            $val = $ex[0];
            if ($val==$clmns[$val]["property"])
            $str.= ".".$clmns[$val]["name"];

        }

    }
}

?>
