View

A view is simply a web page, or a page fragment, like a header, footer,
sidebar, etc. In fact, views can flexibly be embedded within other views
(within other views, etc., etc.) if you need this type of hierarchy.

view files are interpreted by the runtime facesrenderer

\<faces\> And \</faces\> tag is interpreted between the codes

Sample Faces View

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
