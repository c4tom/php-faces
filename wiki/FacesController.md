\<h1\>Controller Methods \</h1\> \<ul\>
\<li\>\<strong\>render\</strong\> ( This method render a view
file)\</li\> \<li\>\<strong\>append\</strong\> (add a variable to the
view)\</li\> \<li\>\<strong\>load \</strong\>(a string parameter is the
name of the class,returns an object of class name)\</li\>
\<li\>\<strong\>interrupt\</strong\> (suspends the process of
rendering)\</li\>
\<li\>\<strong\>addListener\</strong\>(\(name,FacesListener \)listener)\</li\>
\<li\>\<strong\>addActionListener \</strong\>(Registers a listener for
ActionEvent. Parameter is an ActionListener)\</li\>
\<li\>\<strong\>addMouseListener \</strong\>(Registers a listener for
mouse events. Parameter is an MouseListener)\</li\>
\<li\>\<strong\>addValueChangedListener\</strong\>(Registers a listener
for ValueChangedEvent. Parameter is an ValueChangedListener)\</li\>
\<li\>\<strong\>getComponents\</strong\> (turn of all componets) \</li\>
\<li\>\<strong\>AjaxResponse\</strong\> (suspends the process of
rendering. the client sends the json data) \</li\> \</ul\>

Sample

    import("phpf.controllers.facescontroller");
    import("phpf.events.actionevent");
    import("phpf.listeners.actionlistener");
    class Simpleevent extends FacesController implements ActionListener
    {
        function Simpleevent()
        {
            parent::FacesController();
            $this->addActionListener($this);
            $this->render("event.phpf");
            $this->load("server.session")
            if($this->session->get("login"))
            echo "You login";
        }
        public function actionPerformed(ActionEvent $evt)
        {
            $evt->getComponent()->setText("You cliked me");
            $this->AjaxResponse();
         
        }
    }
