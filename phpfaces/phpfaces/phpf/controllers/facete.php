<?php
/**
 * @name Facete
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.controllers
 * @version 1.0
 * @category PHP Faces Core
 */
import("phpf.controllers.facescontroller");
import("phpf.events.*");
import("phpf.listeners.*");
class Facete extends  FacesController implements ActionListener,MouseListener,ValueChangedListener {

    public function Facete()
    {

        parent::FacesController();
        $this->addActionListener($this);
        $this->addMouseListener($this);
        $this->addValueChangedListener($this);
    }
    private function call_method($ek,FacesEvent $evt)
    {
        $comname= $evt->getComponent()->name;
        $m= $comname.$ek;
        if(method_exists($this, $m))
        call_user_method($m, &$this, $evt);
    }
    public function mouseDown(MouseEvent $event) {
       $this->call_method("mouseDown", $event);
    }
    public function mouseOut(MouseEvent $event) {
      $this->call_method("mouseOut", $event);
    }
    public function mouseOver(MouseEvent $event) {
        $this->call_method("mouseOver", $event);
    }
    public function mouseUp(MouseEvent $event) {
        $this->call_method("mouseUp", $event);
    }
    public function mousedbClick(MouseEvent $event) {
        $this->call_method("dbClick", $event);
    }
    public function actionPerformed(ActionEvent $event) {
        $this->call_method("clicked", $event);
    }
    public function valueChanged(ValueChangedEvent $event) {
        $this->call_method("Changed", $event);
    }
}
?>
