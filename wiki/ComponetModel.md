component model your class extended from the component Methods

Construct function \_\_construct(FacesController
&\(controller,\)args=null)

String startTag($bind=null)

String endTag() Simple a component

    import("phpf.ui.component");
    class Strong extends Component
    {
      function Strong(FacesController &$controller,$args=null)
        {
            parent::Component($controller,$args);
        }
       function startTag() {
            return '<strong>';
            
        }
       function endTag() {
            return '</strong>';
        }
    }

use in view

    <faces>
    <@import prefix="my" taglib="mypath.strong"/>
    <my:strong name="test">
    some text
    </my:strong>
    </faces>

\<b\>Attributes of your component\</b\> $attributes an array

Sample

    import("phpf.ui.component");
    class Strong extends Component
    {
      function Strong(FacesController &$controller,$args=null)
        {
            parent::Component($controller,$args);
           
        }
       function startTag() {
            $html = '<strong>'.'<font color="'.$this->attributes[color].
           '" size="'.$this->attributes[size].'">' ;
              
            return $html;
        }
       function endTag() {
            return '</font></strong>';
        }
    }

using doAttributes method

    function startTag() {
            $html = '<strong>'.'<font'. $this->doAttributes() .'>' ;
            return $html;
        }

use in view

    <faces>
    <@import prefix="my" taglib="mypath.strong"/>
    <my:strong name="test" size="10" face="Arial">
    some text
    </my:strong>
    </faces>

@Items

Sample

    import("phpf.ui.component");
    class Strong extends Component
    {
      function Strong(FacesController &$controller,$args=null)
        {
            parent::Component($controller,$args);
           
        }
       function startTag() {
             $html = '<strong>'.'<font'. $this->doAttributes() .'>' ;
              foreach($this->items as $item)
              $html.= '<br/><a href="'.$item[url].'">'.$item[title].'</a>';          
            return $html;
        }
       function endTag() {
            return '</font></strong>';
        }
    }

use in view

    <faces>
    <@import prefix="my" taglib="mypath.strong"/>
    <my:strong name="test" size="10" face="Arial">
    <@item url="http://www.google.com" title="google"/>
    <@item url="http://www.webmahsulleri.com" title="WebMahsulleri"/>
    some text
    </my:strong>
    </faces>
