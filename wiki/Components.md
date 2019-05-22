phpf.ui.\*

    <f:button>
    <f:label>
    <f:checkbox>
    <f:form>
    <f:combobox>
    <f:hidden>
    <f:textarea>
    <f:image>
    <f:textbox>

phpf.ui.sql.\*

    <sql:sql>

phpf.ui.widget.\*

    <w:accordion>
    <w:accordionpane>
    <w:djtextbox>
    <w:currencytext>
    <w:datebox>
    <w:djbutton>
    <w:djcombobox>
    <w:djchekbox>
    <w:tab>
    <w:tabpane>
    <w:validationtext>
    <w:djgird>
    <w:editor>
    <w:djchekbox>

use in view

    <html>
    <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    
    </head>
    <body>
    <faces>
    <@import prefix="w" taglib="phpf.ui.widget.*"/>
    <w:tab name="tab" style="width:500px;height:100px">
     <w:tabpane name="tab1" title="Little Red Cap">
          Once upon a time there was a dear little girl who was loved by
          every one who looked at her, but most of all by her grandmother,
          and there was nothing that she would not have given to the child.
     </w:tabpane>
      <w:tabpane name="tab2"  title="Hansel and Gretel" closable="true" selected="true">
          Once upon a time there was a dear little girl who was loved by
          every one who looked at her, but most of all by her grandmother,
          and there was nothing that she would not have given to the child.
     </w:tabpane>
    
      <w:tabpane name="tab3"     title="The Three Green Twigs">
          There was once upon a time a hermit who lived in a forest at the foot
          of a mountain, and passed his time in prayer and good works,
          and every evening he carried, to the glory of God, two pails of water
          up the mountain. 
     </w:tabpane>
    
     </w:tab>
    </faces>
    </body>
    </html>
