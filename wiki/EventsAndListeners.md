``` 
    
       
$Event->getComponent();// trigger gives the component
$Event->getRequestEvent();  // trigger gives the HTTP status post,request etc.
     
```

Events ActionEvent (component is clicked) ActionListener works with

Methods

    public function actionPerformed(ActionEvent $event);

MouseEvent (component is mouse events) MouseListener works with

Methods

    public function mouseOver(MouseEvent $event);
    public function mouseOut(MouseEvent $event);
    public function mouseDown(MouseEvent $event);
    public function mouseUp(MouseEvent $event);
    public function mousedbClick(MouseEvent $event);

ValueChangedEvent (value is changed)

Methods

    public function valueChanged(ValueChangedEvent $event);

sample Action event The Controller

    import("phpf.controllers.Facescontroller");
    import("phpf.events.ActionEvent");
    import("phpf.listeners.ActionListener");
    class Sample extends FacesController implements ActionListener
    {
        function Sample()
        {
            parent::FacesController();
            $this->addActionListener($this);
            $this->render("view.phpf");
        }
        public function actionPerformed(ActionEvent $evt)
        {
            $evt->getComponent()->setText("You cliked me");
         
        }
    }

The view.phpf

    <html>
        <head>
            <title></title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    
        </head>
        <body>
            <faces>
                <@import prefix="face" taglib="phpf.ui.button"/>
                <face:button name="btn" onclick="actionevent" text="Click Me"/>
            </faces>
        </body>
    </html>
