<?php
import("phpf.controllers.Facescontroller");
import("phpf.events.ActionEvent");
import("phpf.listeners.ActionListener");
import("dbf.orm");
import("entityes.Blog",true);
class Sampleblogedit extends FacesController implements ActionListener
{
    private $em;
    protected $blog;
    function Sampleblogedit()
    {
        parent::FacesController();
        $this->addActionListener($this);
        $this->em= EntityManager::getInstance();
        $this->load("io.uri");
        $this->blog = $this->em->find("Blog",$this->uri->get(0));
        $this->blog->content = stripcslashes($this->blog->content);
        $this->render("editblog.phpf");
        
    }
 

    public function actionPerformed(ActionEvent $evt)
    {
     
        $this->blog->name= $this->caption->getText();
        $this->blog->content= $this->content->getText();
        $this->em->save($this->blog);
        $this->blog->content = stripcslashes($this->blog->content);
        echo "<b>Saved your content<b>";
        $this->content->setText("");
    }

}
?>