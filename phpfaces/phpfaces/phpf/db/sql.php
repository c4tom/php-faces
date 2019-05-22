<?php

require_once CLASS_PATH. "phpf".DS."ui".DS.'component.php';
class Sql extends Component {
    var $set;
    function load($args) {
        parent::load($args);

    }
    function Sql(FacesController &$controller,$args=null) {
        parent::Component($controller,$args);
        if($args!=null)
            $this->load($args);
        $em = EntityManager::getInstance();
        $st= $em->nativeQuery($this->attributes[query]);
        $set = $this->attributes["id"];
        $this->set=$set;
        $k=1;
        if($this->items)
            foreach ($this->items as $item) {
                $key = key($item);
                $st->bindParam($k, &$item[value]);
                $k++;
            }
        $st->execute();
        $list= $st->getResultList();
        $this->controller->append($set,$list);
    }
    function startTag() {


    }
    function endTag() {

    }


    function setQuery($val) {
        $this->attributes[query]= $val;
        $this->update('query');
    }

    function getQuery() {
        return $this->attributes[query];
    }

}
?>

