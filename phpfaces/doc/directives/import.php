@import Tag taglib: a directory and classname prefix tag namespace

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