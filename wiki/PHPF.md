.htaccess file

    Options +ExecCGI
    AddType application/x-httpd-php .phpf s
    AddHandler  application/x-httpd-phpf .phpf
    Action application/x-httpd-phpf www.yourhost.com/system/phpf.php 

    <?php
    import("phpf.controllers.Facescontroller");
    import("phpf.events.ActionEvent");
    import("phpf.listeners.ActionListener");
    define("BASE_URL","www.yourhost.com/");
    class Fform extends FacesController implements ActionListener
    {
        function Fform()
        {
            parent::FacesController();
            $this->addActionListener($this);
            $this->render(ApplicationContext::getCurrentView());//fform.phpf
        }
        public function actionPerformed(ActionEvent $evt)
        {
             $this->label->setText("Your name : ".$this->textbox->getText()) ;
        }
    }
    ?>
