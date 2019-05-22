<?php
require_once CLASS_PATH. "phpf".DS."ui".DS.'component.php';
class Pager extends Component {
    private $total;
    private $results;
    private $limit,$offset;
    private $request="p";
    private $reg;
    private $previous;
    private $next;
    /**
     *
     * @param FacesController $controller
     * @param Array $args
     *
     */
    function Pager(FacesController &$controller,$args=null) {

        parent::Component($controller,$args);


    }
    public function load($att) {
        parent::load(&$att);
        if($att!=null)
            $this->Set();
        FacesRenderer::getHeader()->addLink(BASE_URL. "/resources/pager.css");
        FacesRenderer::getHeader()->setTitle("sayfa $this->reg");
        $this->previous= isset($this->attributes[previous])?$this->attributes[previous]:"previous";
        $this->next= isset($this->attributes[next])?$this->attributes[next]:"next";
    }
    function getLimitQuery() {
        return "LIMIT $this->limit OFFSET $this->offset";
    }
    function getLimit() {
        return $this->limit;
    }
    function getOffset() {
        return $this->offset;
    }

  function setLimit($l) {
      $this->limit=$l;
    }
    function setOffset($o) {
       $this->offset=$o;
    }

    function setCount($c)
    {
        $this->total=$c;
    }
    private function Set() {
        $em = EntityManager::getInstance();
        if(isset($this->attributes["request"]))
            $this->request = $this->attributes["request"];
        $tbl_name=  $this->attributes["entity"];
        if(isset($this->attributes["entity"])) {
            $param = $this->attributes[query];
            $query = "SELECT COUNT(*) as num FROM $tbl_name $param";
            $query = $em->nativeQuery($query);
            $query->execute();
            $this->total = $query->getSingle();
        }
        else
            $this->total = (int)$this->attributes["count"];

        $this->limit=(int) $this->attributes[limit];
        $adjacents = "2";
        if($this->attributes[type]=="path") {
            import("io.uri");
            $uri = new Uri();
            $size = count($uri->getArray());
            $this->reg = $uri->get($size-1);
        }
        elseif(isset($_GET[$this->request]))
            $this->reg =$_GET[$this->request];


        if($this->reg) {
            $this->page =(int) $this->reg;
            $this->offset =(int) (( $this->page - 1) * $this->limit);

        }else
            $this->offset =0;

        if(isset($this->attributes["entity"])) {
            $map=  $em->getClassMap();
            if(!$map->isClassLoaded($tbl_name))
                $map->run($tbl_name);
            $table= $map->getTableName($tbl_name);
            $id =$map->getId($tbl_name);

            $queryString.="SELECT $tbl_name FROM $tbl_name $tbl_name  $param GROUP BY $tbl_name.$id LIMIT :limit OFFSET :offset";
            $query2 = $em->createQuery($queryString);
            $query2->bindParam("limit",$this->limit,PDO::PARAM_INT);
            $query2->bindParam("offset",$this->offset,PDO::PARAM_INT);
            $query2->execute();
            $result = $query2->getResultList();
            $this->controller->append($this->attributes["resource"],$result);

        }

    }

    function Pages($tbl_name,$limit) {
        if ($this->page == 0) $this->page = 1;
        $prev = $this->page - 1;
        $next = $this->page + 1;
        $lastpage = ceil($this->total/$limit);
        $lpm1 = $lastpage - 1;

        if($this->attributes["type"]=="path") {
            $this->request="";
            $path="";

        }
        else {
            $this->request.="=";
            $path = $_SERVER["PHP_SELF"]."?";
        }
        $pagination = "";
        if($lastpage > 1) {
            $pagination .= "<div class='pagination'>";
            if ($this->page > 1)
                $pagination.= "<a href='".$path."$this->request$prev'>&#171; $this->previous </a>";
            else
                $pagination.= "<span class='disabled'>&#171; $this->previous </span>";

            if ($lastpage < 7 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $this->page)
                        $pagination.= "<span class='current'>$counter</span>";
                    else
                        $pagination.= "<a href='".$path."$this->request$counter'>$counter</a>";
                }
            }
            elseif($lastpage > 5 + ($adjacents * 2)) {
                if($this->page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $this->page)
                            $pagination.= "<span class='current'>$counter</span>";
                        else
                            $pagination.= "<a href='".$path."$this->request$counter'>$counter</a>";
                    }
                    $pagination.= "...";
                    $pagination.= "<a href='".$path."$this->request$lpm1'>$lpm1</a>";
                    $pagination.= "<a href='".$path."$this->request$lastpage'>$lastpage</a>";
                }
                elseif($lastpage - ($adjacents * 2) > $this->page && $this->page > ($adjacents * 2)) {
                    $pagination.= "<a href='".$path."$this->request1'>1</a>";
                    $pagination.= "<a href='".$path."$this->request2'>2</a>";
                    $pagination.= "...";
                    for ($counter = $this->page - $adjacents; $counter <= $this->page + $adjacents; $counter++) {
                        if ($counter == $this->page)
                            $pagination.= "<span class='current'>$counter</span>";
                        else
                            $pagination.= "<a href='".$path."$this->request$counter'>$counter</a>";
                    }
                    $pagination.= "..";
                    $pagination.= "<a href='".$path."$this->request$lpm1'>$lpm1</a>";
                    $pagination.= "<a href='".$path."$this->request$lastpage'>$lastpage</a>";
                }
                else {
                    $pagination.= "<a href='".$path."$this->request1'>1</a>";
                    $pagination.= "<a href='".$path."$this->request2'>2</a>";
                    $pagination.= "..";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $this->page)
                            $pagination.= "<span class='current'>$counter</span>";
                        else
                            $pagination.= "<a href='".$path."$this->request$counter'>$counter</a>";
                    }
                }
            }

            if ($this->page < $counter - 1)
                $pagination.= "<a href='".$path."$this->request$next'>$this->next &#187;</a>";
            else
                $pagination.= "<span class='disabled'>$this->next &#187;</span>";
            $pagination.= "</div>\n";
        }


        return $pagination;
    }
    function startTag() {


        return  $this->Pages( $this->attributes["entity"], $this->attributes[limit]) ;
    }

}
?>
