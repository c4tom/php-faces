Use core tags \<@import prefix="c" taglib="phpf.core" type="static"/\>

\<b\>out tag\<b\> parameters value sample out

    <c:out value="hi all"/>
    See this code makes
    echo "hi all"

\<b\>if tag\<b\> parameters test sample if

    <c:if test="#{$name=='bora'}">
    <c:out value="#{$name}"/>
    </c:if>

\<b\>else tag\<b\> sample else

    <c:if test="#{$name=='bora'}">
    <c:out value="#{$name}"/>
    <c:else>
    <c:out value="other name #{$name}"/>
    </c:else>
    </c:if>

\<b\>elseif tag\<b\> parameters test sample elseif

    <c:if test="#{$name=='bora'}">
    <c:out value="#{$name}"/>
    <c:elseif test="#{$name=='obama'}">
    <c:out value="name obama"/>
    </c:elseif>
    </c:if>

\<b\>for tag\<b\> parameters var ,begin, to,step sample for

    <c:for var="$i" begin="1" to="10" step="1">
    <c:out value="#{$i}"/>
    </c:for>

Out 1,2,3,4,5,6,7,9,10

\<b\>foreach tag\<b\> parameters var, item sample forech

    <c:foreach var="$this.list" item="$item">
      <br/>
       Name :<c:out value="#{$item.name}"/>
       Id :<c:out value="#{$item.id}"/>
      </c:foreach>
