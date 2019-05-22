<?php
/**
 * @name ObjectSetter
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package dbf
 * @version 1.0
 * @category PHP Faces ORM
 */

class ObjectSetter{
    var $map;
    var $islenmis_haritalar=array();
    var $islenmis_yavru_nesneler=array();
    var $islenmis_ana_nesneler=array();
    var $ana_sinif_ismi;
    var $ana_nesne_anahtari;
    var $nesne_kayitedici;
    var $query;
    var $ana_tablo_ismi;

    function ObjectSetter(ClassMap &$map,ObjectRegistry $registy,$db) {
        $this->map=$map;
        $this->nesne_kayitedici = $registy;
        $this->query=$db;
        $this->ana_sinif_ismi=&$map->getSuperClass();
        $pkId = $this->map->getId($this->ana_sinif_ismi);
        $this->ana_tablo_ismi =$this->map->getTableName($this->ana_sinif_ismi);
        $this->ana_nesne_anahtari = $this->ana_tablo_ismi."_".$pkId;
    }

    private function hazirlan() {
        $this->ana_sinif_ismi=$this->map->getSuperClass();
        $pkId = $this->map->getId($this->ana_sinif_ismi);
        $this->ana_tablo_ismi =$this->map->getTableName($this->ana_sinif_ismi);
        $this->ana_nesne_anahtari = $this->ana_tablo_ismi."_".$pkId;

    }
    public function clear() {
        $this->islenmis_haritalar=array();
        $this->islenmis_ana_nesneler=array();
         $this->islenmis_yavru_nesneler=array();
    }
    function kulucka($query=null) {
        $this->clear();
      if($query!=null)
        $this->query= $query;
        $this->hazirlan();
        while ($veri = $this->query->nextResutlt())
        { 
            unset($this->islenmis_haritalar);//islenmis haritalari sifirla
            $this->islenmis_haritalar=array();
            $yeni_ana_nesne=null;
            if(!isset($this->islenmis_ana_nesneler[$this->ana_sinif_ismi][$veri[$this->ana_nesne_anahtari]])){
                $yeni_ana_nesne = new $this->ana_sinif_ismi();
                $this->atayici($yeni_ana_nesne, $veri,$this->ana_sinif_ismi,$this->ana_tablo_ismi);

                $this->nesne_kayitedici->add($yeni_ana_nesne);
                $this->islenmis_ana_nesneler[$this->ana_sinif_ismi][$veri[$this->ana_nesne_anahtari]]=$yeni_ana_nesne;
            }
            else
            $yeni_ana_nesne = $this->islenmis_ana_nesneler[$this->ana_sinif_ismi][$veri[$this->ana_nesne_anahtari]];
            $this->yavrulat($veri, $yeni_ana_nesne,$this->ana_sinif_ismi);
        }
        return $this->islenmis_ana_nesneler[$this->ana_sinif_ismi];
    }

    function yavrulat($veri,$obj)
    {
        $super_nesne_adi = get_class($obj);
        if(isset($this->islenmis_haritalar[$super_nesne_adi]))
        return;
        $this->islenmis_haritalar[$super_nesne_adi]=$super_nesne_adi;
        $yavru_sinif_adi="";
        $yavru_nesnenin_haritasi = $this->map->getForeignClassesMap($super_nesne_adi);
        foreach ($yavru_nesnenin_haritasi as $iliski_duzeyi=> $yavru_harita_ogesi)
        { 
            
            foreach ($yavru_harita_ogesi as  $yavru_harita_sinif)
            {
                $yavru_sinif_adi = $yavru_harita_sinif["class"];
                $yavru_tablo_adi =$this->map->getTableName($yavru_sinif_adi);
                $nitelik_adi = $yavru_harita_sinif["property"];
             
                $pkId = $this->map->getId($yavru_sinif_adi);
                $yavru_nesne_anahtari= $yavru_tablo_adi."_".$pkId;

                if(isset($this->islenmis_yavru_nesneler[$yavru_sinif_adi][$veri[$yavru_nesne_anahtari]]))
                $yavru_nesne = $this->islenmis_yavru_nesneler[$yavru_sinif_adi][$veri[$yavru_nesne_anahtari]];
                elseif($yavru_sinif_adi ==$this->ana_sinif_ismi)
                $yavru_nesne = $this->islenmis_ana_nesneler[$this->ana_sinif_ismi][$veri[$this->ana_nesne_anahtari]];
                else{
                    $yavru_nesne = new $yavru_sinif_adi();
                    $this->nesne_kayitedici->add($yavru_nesne);
                }
                if(isset($veri[$yavru_nesne_anahtari]))
                {
                    if($iliski_duzeyi=="one")
                    {
                        if(!isset($this->islenmis_yavru_nesneler[$yavru_sinif_adi][$veri[$yavru_nesne_anahtari]]))
                        $this->atayici($yavru_nesne,$veri,$yavru_sinif_adi,$yavru_tablo_adi);
                        call_user_method("__dbset", &$obj, $nitelik_adi,$yavru_nesne);
                    }
                    if($iliski_duzeyi=="many")
                    {
                        if(!$obj->$nitelik_adi instanceof ArrayObject)
                        call_user_method("__dbset", &$obj, $nitelik_adi,new ArrayObject());
                        if(!isset($this->islenmis_yavru_nesneler[$yavru_sinif_adi][$veri[$yavru_nesne_anahtari]]))
                        {
                            $this->atayici($yavru_nesne,$veri,$yavru_sinif_adi,$yavru_tablo_adi);
                            $obj->$nitelik_adi->append($yavru_nesne);
                        }
                    }
                    $this->islenmis_yavru_nesneler[$yavru_sinif_adi][$veri[$yavru_nesne_anahtari]]=$yavru_nesne;
                    $this->yavrulat($veri, $yavru_nesne);
                }
            }
        }
    }

    function atayici($obj,$veriler,$class_name,$table_name=null){

        if(isset($this->islenmis_ana_nesneler[$class_name][$veriler[$this->ana_nesne_anahtari]]))
        return;
        $columns= $this->map->getClassColumns($class_name);

        foreach($columns as $column)
        {
            $nitelik = $column["property"];
            $takmaisim =$table_name."_".$column["name"];
            if(isset($veriler[$takmaisim]))
            call_user_method("__dbset", &$obj, $nitelik,$veriler[$takmaisim]);
        }
    }
}
?>