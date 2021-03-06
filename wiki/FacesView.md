\<b\>@import Tag\</b\> taglib: a directory and classname prefix tag
namespace

    <@import prefix="c" taglib="phpf.core" type="static"/>
    this tag call static methods 
    
    <@import prefix="face" taglib="phpf.ui.*"/>
    this tag import components
    it is used
    <face:button name="btn"/>
    <face:textbox name="txtbox"/>
    <face:label name="lbl"/>
    <c:if test="$a==0" >
    <c:out value="zero">
    <c:else>
    <c:out value="not zero">
    </c:else>
    </c:if>

\<b\>@definition Tag\</b\>

viewstate : true or false, uses a storage area for components

stroge : xhtml or session

eventvalidation : true or false

    <@definition viewstate="true" stroge="xhtml"/>
    <@definition eventvalidation="true"/>

\<b\>@set Tag\</b\> create a varible

    <@set name="bora" scope="session"/>
    
    <@set number="111" scope="page"/>
    See this code makes

    $_SESSION["name"]="bora";
    var $number=111;

\<b\>@get Tag\</b\> get a varible

    <@get var="thename" select="name" scope="session"/>
    See this code makes

    var $thename = $_SESSION["name"];

\<b\>@item Tag\</b\> add an item a component

``` 
     <sql:sql name="sql" var="results" query="select * from table where id=:id">
         <@item id="{$id}" type="integer"/>
     </sql:sql>

<com:grid name="girdx" height="100" width="50" bind="$this.birler" border="1">
 <@item  input="text" title="id" key="id" />
 <@item  input="button" key="name" title="name"/>
 <@item  input="link" title="delete"  url="delete/$id/$name/$id" type="path" separator="_"/>
 <@item  input="link" title="edit"  url="delete.php?id=$id&name=$name"/>
</com:grid>
```

\<b\>@pattern Tag\</b\> creates a template for ui components and
\<b\>\<@ui\>\</b\> is used with Example

    <@pattern name="mytag"
                prefix="f"
                extends="button"
                text="My text"
                style="color:navy;font-weight:bold;" 
                onclick="ajaxevent"/>
    it is used
     <@ui name="mytag1" pattern="mytag"/>
     <@ui name="mytag2" pattern="mytag"/>

\<b\>@htmlpattern Tag\</b\> creates a template for html and
\<b\>\<@html\>\</b\> is used with Example

    <@htmlpattern name="html">
                  <div style="color:red;font-weight:bold;" > test html pattern{$i} </div>
                 </@htmlpattern>
    it is used
    <@html pattern="html"/>

\<b\>@face Tag\</b\> include a faces file Example

    <@face file="footer.phpf"/>
    <@face file="banner.html"/>
    <@face file="patterns.phpf"/>

\<b\>@include Tag\</b\> include a php file Example

    <@include file="functions.php"/>
    <@include file="helper.php"/>
    <@include file="view.php"/>
