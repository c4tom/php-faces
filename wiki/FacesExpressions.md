Examples of expression in the faces file a value to send output

``` 

<b>
#{$varible}
</b>
```

operations

``` 

#{1+2}
#{$i+$j}
#{$i*$j}
```

Use in html form

``` 

<b>
<input type = "text" name="text" value="#{$varible}"/>
</b>
```

``` 

<c:for var="$i" begin="0" to="10" step="1">
<input type = "text" name="text#{$i}" value="#{$i}"/>
</c:for>
```
