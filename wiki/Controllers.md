Controller

The Controller serves as an intermediary between the Model, the View,
and any other resources needed to process the HTTP request and generate
a web page.

PHPFaces controller types within:

  - FacesController

<!-- end list -->

  - ActionControler

<!-- end list -->

  - Facete

Facete extends to the FacesController

ActionControler: classic controller structure. however Faces components
will not be used Which controllers are going to use it to expand

Sample:

    import("phpf.controllers.FacesController");
    class Welcome extends FacesController
    {
    function Welcome() {
    parent::FacesController();
    $this->render("view.phpf");
    }
    }
